<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ewa_content_api\TraineeProfile;
use Drupal\user\UserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * The signed-in trainee updates their own registration details (Next.js My Profile page).
 *
 * POST /api/auth/profile   (send the Drupal session cookie)
 *   body: any of the editable TraineeProfile keys, e.g. { "firstName": "…", "mobile": "5…" }
 *   200 { ok: true } · 400 { error: "invalid", fields: {…} } · 401 not signed in
 *
 * Not editable here: National ID / Iqama (the username), e-mail and password (Drupal asks for
 * the current password to change those), the declarations, and the selected programs.
 */
class ProfileApiController extends ControllerBase {

  /** Keys a trainee may change, and checks the Next.js form also makes. */
  protected const EDITABLE = [
    'firstName', 'fatherName', 'grandfatherName', 'lastName', 'gender', 'dob', 'height', 'weight',
    'mobile', 'altMobile', 'relation',
    'qualification', 'major', 'graduationYear', 'englishLevel', 'stepGrade', 'gpa', 'university',
    'employed', 'socialInsurance', 'qiwa', 'commercialReg', 'hrdf', 'referral',
  ];

  public function update(Request $request): JsonResponse {
    if ($this->currentUser()->isAnonymous()) {
      return $this->json(['error' => 'signed_out'], 401);
    }
    $account = $this->entityTypeManager()->getStorage('user')->load($this->currentUser()->id());
    if (!$account instanceof UserInterface) {
      return $this->json(['error' => 'signed_out'], 401);
    }

    $body = json_decode((string) $request->getContent(), TRUE);
    if (!is_array($body)) {
      return $this->json(['error' => 'invalid'], 400);
    }
    $data = array_intersect_key($body, array_flip(self::EDITABLE));

    $errors = $this->check($data);
    if ($errors) {
      return $this->json(['error' => 'invalid', 'fields' => $errors], 400);
    }

    TraineeProfile::apply($account, $data);
    $violations = $account->validate();
    // only report problems with the fields that were sent
    $fields = [];
    foreach ($violations as $violation) {
      $field = explode('.', $violation->getPropertyPath())[0];
      foreach (TraineeProfile::FIELDS as $key => [$name]) {
        if ($name === $field && array_key_exists($key, $data)) {
          $fields[$key] = strip_tags((string) $violation->getMessage());
        }
      }
    }
    if ($fields) {
      return $this->json(['error' => 'invalid', 'fields' => $fields], 400);
    }

    $account->save();
    return $this->json(['ok' => TRUE]);
  }

  /** The same rules as the registration form. */
  protected function check(array $d): array {
    $e = [];
    $year = (int) date('Y');
    $range = fn($v, $min, $max) => is_numeric($v) && $v >= $min && $v <= $max;
    foreach (['firstName', 'fatherName', 'grandfatherName', 'lastName'] as $key) {
      if (array_key_exists($key, $d) && trim((string) $d[$key]) === '') {
        $e[$key] = 'required';
      }
    }
    if (array_key_exists('gender', $d) && !in_array($d['gender'], ['male', 'female'], TRUE)) {
      $e['gender'] = 'invalid';
    }
    if (array_key_exists('dob', $d) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $d['dob'])) {
      $e['dob'] = 'invalid';
    }
    if (array_key_exists('height', $d) && !$range($d['height'], 100, 250)) {
      $e['height'] = 'invalid';
    }
    if (array_key_exists('weight', $d) && !$range($d['weight'], 30, 250)) {
      $e['weight'] = 'invalid';
    }
    if (array_key_exists('mobile', $d) && !preg_match('/^5\d{8}$/', (string) $d['mobile'])) {
      $e['mobile'] = 'invalid';
    }
    if (!empty($d['altMobile']) && !preg_match('/^5\d{8}$/', (string) $d['altMobile'])) {
      $e['altMobile'] = 'invalid';
    }
    if (array_key_exists('graduationYear', $d) && !$range($d['graduationYear'], 1970, $year + 1)) {
      $e['graduationYear'] = 'invalid';
    }
    if (isset($d['stepGrade']) && $d['stepGrade'] !== '' && !$range($d['stepGrade'], 0, 100)) {
      $e['stepGrade'] = 'invalid';
    }
    if (array_key_exists('gpa', $d) && !$range($d['gpa'], 0, 5)) {
      $e['gpa'] = 'invalid';
    }
    foreach (['employed', 'socialInsurance', 'qiwa', 'commercialReg', 'hrdf'] as $key) {
      if (array_key_exists($key, $d) && !in_array($d[$key], ['yes', 'no'], TRUE)) {
        $e[$key] = 'invalid';
      }
    }
    return $e;
  }

  protected function json(array $data, int $status = 200): JsonResponse {
    $response = new JsonResponse($data, $status);
    $response->setPrivate();
    $response->headers->addCacheControlDirective('no-store');
    return $response;
  }

}
