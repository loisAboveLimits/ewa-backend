<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\File\FileExists;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Flood\FloodInterface;
use Drupal\node\NodeInterface;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Entity\WebformOptions;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionForm;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Webforms filled in on the Next.js site (contact form, career applications).
 *
 * GET  /api/webform/{webform_id}?lang=ar
 *   → { id, title, open, options: { sponsorship: [{value,label}] }, files: { cv: { extensions, max_mb } } }
 * POST /api/webform/{webform_id}
 *   { lang, page, data: { first_name, email, … }, files: { cv: { name, data (base64) } } }
 *   201 { ok: true, sid } · 400 { error: "invalid", fields } · 403 closed · 404 · 429 too many
 *
 * The submission is saved like one made on the Drupal form, so the webform's e-mail
 * handlers and the Results tab work as usual. Only webforms listed in ALLOWED can be used.
 *
 * ewa_careers: send data.career_id = the career's node id; the "position" element is filled
 * in here from that career's Main Title (so it can't be changed by the visitor).
 * A CV is saved when the webform has a file element (File / Document file), e.g. keyed "cv" or "resume".
 */
class WebformApiController extends ControllerBase {

  /** Webforms the Next.js site may read and submit. */
  protected const ALLOWED = ['ewa_contact_us', 'ewa_careers'];

  /** Content type of the careers the ewa_careers webform refers to. */
  protected const CAREER_BUNDLE = 'careers';

  /** File element types that accept an uploaded file (e.g. the CV). */
  protected const FILE_TYPES = ['managed_file', 'webform_document_file'];

  /** Largest upload accepted, in MB (the element's own limit applies when smaller). */
  protected const MAX_FILE_MB = 5;

  /** Submissions per visitor per hour. */
  protected const FLOOD_LIMIT = 10;

  public function __construct(protected FloodInterface $flood) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('flood'));
  }

  /** The webform's select / radio options (e.g. Sponsorship) in the requested language. */
  public function elements(Request $request, string $webform_id): JsonResponse {
    $webform = $this->loadWebform($webform_id, (string) $request->query->get('lang', ''));
    if (!$webform) {
      return $this->json(['error' => 'not_found'], 404);
    }
    $options = [];
    $files = [];
    foreach ($webform->getElementsInitializedFlattenedAndHasValue() as $key => $element) {
      if (in_array($element['#type'] ?? '', self::FILE_TYPES, TRUE)) {
        $files[$key] = ['extensions' => $this->extensions($element), 'max_mb' => $this->maxMb($element)];
        continue;
      }
      if (empty($element['#options'])) {
        continue;
      }
      $title = mb_strtolower(trim((string) ($element['#title'] ?? '')));
      $list = [];
      foreach (WebformOptions::getElementOptions($element) as $value => $label) {
        $label = trim(strip_tags((string) $label));
        // skip a placeholder option such as "0|Sponsorship" (the forms show their own placeholder)
        if ((string) $value === '' || mb_strtolower($label) === $title) {
          continue;
        }
        $list[] = ['value' => (string) $value, 'label' => $label];
      }
      $options[$key] = $list;
    }
    $response = new JsonResponse([
      'id' => $webform->id(),
      'title' => $webform->label(),
      'open' => $webform->isOpen(),
      'options' => $options,
      'files' => (object) $files,
    ]);
    $response->setPublic();
    $response->setMaxAge(300);
    return $response;
  }

  /** Saves a submission. */
  public function submit(Request $request, string $webform_id): JsonResponse {
    $body = json_decode((string) $request->getContent(), TRUE);
    if (!is_array($body) || !is_array($body['data'] ?? NULL)) {
      return $this->json(['error' => 'invalid'], 400);
    }
    $lang = (string) ($body['lang'] ?? '');
    $webform = $this->loadWebform($webform_id, $lang);
    if (!$webform) {
      return $this->json(['error' => 'not_found'], 404);
    }
    if (!$webform->isOpen() || !$webform->access('submission_create')) {
      return $this->json(['error' => 'closed'], 403);
    }

    // the Next.js server forwards the visitor's address; otherwise use the caller's
    $ip = trim(explode(',', (string) $request->headers->get('X-Forwarded-For', ''))[0]) ?: $request->getClientIp();
    $event = 'ewa_content_api.webform.' . $webform_id;
    if (!$this->flood->isAllowed($event, self::FLOOD_LIMIT, 3600, $ip)) {
      return $this->json(['error' => 'too_many'], 429);
    }

    // only the webform's own elements, as plain text
    $allowed = $webform->getElementsInitializedFlattenedAndHasValue();
    $data = [];
    foreach ($body['data'] as $key => $value) {
      if (isset($allowed[$key]) && !in_array($allowed[$key]['#type'] ?? '', self::FILE_TYPES, TRUE) && (is_scalar($value) || $value === NULL)) {
        $data[$key] = trim((string) $value);
      }
    }

    $fields = [];
    if ($webform->id() === 'ewa_careers') {
      $fields += $this->prepareCareer($data, $lang);
    }
    if (!$fields && is_array($body['files'] ?? NULL)) {
      $fields += $this->attachFiles($webform, $body['files'], $data);
    }
    if ($fields) {
      $this->flood->register($event, 3600, $ip);
      return $this->json(['error' => 'invalid', 'fields' => $fields], 400);
    }

    $values = [
      'webform_id' => $webform->id(),
      'data' => $data,
      'langcode' => $this->languageManager()->getLanguage($lang) ? $lang : $this->languageManager()->getDefaultLanguage()->getId(),
      'remote_addr' => $ip,
      'uri' => '/' . trim((string) ($body['page'] ?? 'contact'), '/'),
    ];

    $result = WebformSubmissionForm::submitFormValues($values);
    $this->flood->register($event, 3600, $ip);

    if ($result instanceof WebformSubmissionInterface) {
      return $this->json(['ok' => TRUE, 'sid' => (int) $result->id()], 201);
    }
    if (is_array($result) && $result) {
      foreach ($result as $key => $message) {
        $fields[explode('][', (string) $key)[0]] = trim(strip_tags((string) $message));
      }
      return $this->json(['error' => 'invalid', 'fields' => $fields], 400);
    }
    return $this->json(['error' => 'unavailable'], 500);
  }

  /**
   * ewa_careers: checks career_id (a published career node) and sets position to its Main Title
   * (the original-language title, so all applications for one job read the same).
   */
  protected function prepareCareer(array &$data, string $lang): array {
    $nid = $data['career_id'] ?? '';
    if ($nid === '') {
      return [];
    }
    $node = ctype_digit($nid) ? $this->entityTypeManager()->getStorage('node')->load((int) $nid) : NULL;
    if (!$node instanceof NodeInterface || $node->bundle() !== self::CAREER_BUNDLE || !$node->isPublished()) {
      return ['career_id' => 'This position is no longer available.'];
    }
    $original = $node->getUntranslated();
    $title = $original->hasField('field_main_title') ? trim(strip_tags((string) $original->get('field_main_title')->value)) : '';
    $data['career_id'] = (string) $node->id();
    $data['position'] = $title !== '' ? $title : $original->label();
    return [];
  }

  /** Saves uploaded files (base64) for the webform's file elements; returns field errors. */
  protected function attachFiles(WebformInterface $webform, array $files, array &$data): array {
    $errors = [];
    foreach ($files as $key => $file) {
      $element = $webform->getElement($key);
      if (!$element || !in_array($element['#type'] ?? '', self::FILE_TYPES, TRUE) || !is_array($file)) {
        continue;
      }
      $name = (string) ($file['name'] ?? 'file');
      $name = preg_replace('/[^\w.\- ]+/u', '_', basename($name)) ?: 'file';
      $bytes = base64_decode((string) ($file['data'] ?? ''), TRUE);
      $extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
      if ($bytes === FALSE || $bytes === '') {
        $errors[$key] = 'The file could not be read.';
      }
      elseif (!in_array($extension, $this->extensions($element), TRUE)) {
        $errors[$key] = 'This file type is not allowed.';
      }
      elseif (strlen($bytes) > $this->maxMb($element) * 1024 * 1024) {
        $errors[$key] = 'The file is too large.';
      }
      else {
        $scheme = $element['#uri_scheme'] ?? 'private';
        if (!\Drupal::service('stream_wrapper_manager')->isValidScheme($scheme)) {
          $scheme = 'public';
        }
        // webform moves files from "_sid_" into the submission's folder when it is saved
        $directory = "$scheme://webform/{$webform->id()}/_sid_";
        \Drupal::service('file_system')->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY | FileSystemInterface::MODIFY_PERMISSIONS);
        $entity = \Drupal::service('file.repository')->writeData($bytes, "$directory/$name", FileExists::Rename);
        $entity->setTemporary();
        $entity->save();
        $data[$key] = (string) $entity->id();
      }
    }
    return $errors;
  }

  /** Allowed file extensions of a file element. */
  protected function extensions(array $element): array {
    $list = $element['#file_extensions'] ?? '';
    $list = $list !== '' ? $list : 'pdf doc docx';
    return array_values(array_filter(preg_split('/[\s,]+/', strtolower($list))));
  }

  /** Upload limit of a file element in MB (never more than MAX_FILE_MB). */
  protected function maxMb(array $element): float {
    $mb = isset($element['#max_filesize']) && is_numeric($element['#max_filesize']) ? (float) $element['#max_filesize'] : self::MAX_FILE_MB;
    return min($mb, self::MAX_FILE_MB);
  }

  protected function loadWebform(string $id, string $lang): ?WebformInterface {
    if (!in_array($id, self::ALLOWED, TRUE)) {
      return NULL;
    }
    // labels and messages in the visitor's language (config translations)
    if ($lang && ($language = $this->languageManager()->getLanguage($lang))) {
      $this->languageManager()->setConfigOverrideLanguage($language);
    }
    $webform = Webform::load($id);
    return $webform instanceof WebformInterface ? $webform : NULL;
  }

  protected function json(array $data, int $status = 200): JsonResponse {
    $response = new JsonResponse($data, $status);
    $response->setPrivate();
    $response->headers->addCacheControlDirective('no-store');
    return $response;
  }

}
