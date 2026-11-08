<?php

namespace Drupal\ewa_content_api;

use Drupal\taxonomy\TermInterface;
use Drupal\user\UserInterface;

/**
 * The trainee fields on user accounts (created by the ewa_trainee_fields module), mapped to
 * the names the Next.js registration form uses. Fields that don't exist are skipped.
 */
final class TraineeProfile {

  /** Next.js name => [Drupal field, kind] */
  public const FIELDS = [
    'nationalId' => ['field_national_id', 'string'],
    'firstName' => ['field_first_name', 'string'],
    'fatherName' => ['field_father_name', 'string'],
    'grandfatherName' => ['field_grandfather_name', 'string'],
    'lastName' => ['field_last_name', 'string'],
    'gender' => ['field_gender', 'string'],
    'dob' => ['field_date_of_birth', 'date'],
    'height' => ['field_height', 'int'],
    'weight' => ['field_weight', 'int'],
    'mobile' => ['field_mobile', 'string'],
    'altMobile' => ['field_alt_mobile', 'string'],
    'relation' => ['field_alt_relation', 'string'],
    'healthDeclaration' => ['field_health_declaration', 'bool'],
    'qualification' => ['field_qualification', 'string'],
    'major' => ['field_major', 'string'],
    'graduationYear' => ['field_graduation_year', 'int'],
    'englishLevel' => ['field_english_level', 'string'],
    'stepGrade' => ['field_step_grade', 'int'],
    'gpa' => ['field_gpa', 'decimal'],
    'university' => ['field_university', 'string'],
    'employed' => ['field_employed', 'yesno'],
    'socialInsurance' => ['field_social_insurance', 'yesno'],
    'qiwa' => ['field_qiwa', 'yesno'],
    'commercialReg' => ['field_commercial_reg', 'yesno'],
    'hrdf' => ['field_hrdf', 'yesno'],
    'referral' => ['field_referral', 'string'],
    'dataConfirm' => ['field_data_confirm', 'bool'],
  ];

  public const PROGRAMS_FIELD = 'field_programs';
  public const PROGRAMS_VOCABULARY = 'programs';

  /**
   * The account for a sign-in identifier: username, e-mail, or National ID / Iqama number
   * (field_national_id — for accounts whose username is something else). Arabic-Indic digits
   * (٠١٢…) are accepted too. NULL when nothing matches.
   */
  public static function findAccount(string $identifier): ?UserInterface {
    $identifier = trim($identifier);
    if ($identifier === '') {
      return NULL;
    }
    $storage = \Drupal::entityTypeManager()->getStorage('user');
    $found = $storage->loadByProperties(['name' => $identifier]) ?: $storage->loadByProperties(['mail' => $identifier]);
    if (!$found) {
      $digits = strtr($identifier, [
        '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
        '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
      ]);
      $digits = preg_replace('/\s+/', '', $digits);
      if (preg_match('/^\d{10}$/', $digits)) {
        $found = $storage->loadByProperties(['name' => $digits]);
        $fields = \Drupal::service('entity_field.manager')->getFieldStorageDefinitions('user');
        if (!$found && isset($fields['field_national_id'])) {
          $found = $storage->loadByProperties(['field_national_id' => $digits]);
        }
      }
    }
    $account = $found ? reset($found) : NULL;
    return $account instanceof UserInterface ? $account : NULL;
  }

  /** Copies the registration values onto the account. */
  public static function apply(UserInterface $account, array $data): void {
    foreach (self::FIELDS as $key => [$field, $kind]) {
      if (!$account->hasField($field) || !array_key_exists($key, $data)) {
        continue;
      }
      $value = $data[$key];
      $value = match ($kind) {
        'bool' => (bool) $value,
        'yesno' => $value === 'yes' ? TRUE : ($value === 'no' ? FALSE : NULL),
        'int' => ($value === '' || $value === NULL) ? NULL : (int) $value,
        'decimal' => ($value === '' || $value === NULL) ? NULL : (string) round((float) $value, 2),
        'date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value) ? (string) $value : NULL,
        default => trim((string) $value) === '' ? NULL : trim((string) $value),
      };
      $account->set($field, $value);
    }
  }

  /**
   * Program terms for the course ids of the registration form ("facilities-management"…),
   * matched on the slug of each term's (original) name. Returns [term ids, unmatched ids].
   */
  public static function matchPrograms(array $courseIds): array {
    $storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $terms = $storage->loadByProperties(['vid' => self::PROGRAMS_VOCABULARY]);
    $bySlug = [];
    foreach ($terms as $term) {
      $bySlug[Slug::make((string) $term->getUntranslated()->label())] = (int) $term->id();
    }
    $tids = [];
    $unmatched = [];
    foreach (array_unique(array_map('strval', $courseIds)) as $id) {
      $slug = Slug::make($id);
      $tid = $bySlug[$slug] ?? NULL;
      if (!$tid) {
        // "diesel-engine-operation" ↔ "diesel-engine-operation-technology"
        foreach ($bySlug as $termSlug => $candidate) {
          if (str_starts_with($termSlug, $slug) || str_starts_with($slug, $termSlug)) {
            $tid = $candidate;
            break;
          }
        }
      }
      if ($tid) {
        $tids[] = $tid;
      }
      else {
        $unmatched[] = $id;
      }
    }
    return [array_values(array_unique($tids)), $unmatched];
  }

  /** The trainee values (Next.js names) + programs in the given language, for /api/auth/me. */
  public static function read(UserInterface $account, string $langcode): array {
    $out = [];
    foreach (self::FIELDS as $key => [$field, $kind]) {
      if (!$account->hasField($field)) {
        continue;
      }
      $item = $account->get($field);
      if ($item->isEmpty()) {
        $out[$key] = NULL;
        continue;
      }
      $value = $item->value;
      $out[$key] = match ($kind) {
        'bool' => (bool) $value,
        'yesno' => $value ? 'yes' : 'no',
        'int' => (int) $value,
        'decimal' => (float) $value,
        default => (string) $value,
      };
    }

    $programs = [];
    if ($account->hasField(self::PROGRAMS_FIELD)) {
      foreach ($account->get(self::PROGRAMS_FIELD)->referencedEntities() as $term) {
        if ($term instanceof TermInterface) {
          $t = $term->hasTranslation($langcode) ? $term->getTranslation($langcode) : $term;
          $programs[] = ['tid' => (int) $term->id(), 'name' => (string) $t->label(), 'slug' => Slug::make((string) $term->getUntranslated()->label())];
        }
      }
    }
    $out['programs'] = $programs;
    return $out;
  }

}
