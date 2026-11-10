<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Flood\FloodInterface;
use Drupal\webform\Entity\Webform;
use Drupal\webform\Utility\WebformOptionsHelper;
use Drupal\webform\WebformInterface;
use Drupal\webform\WebformSubmissionForm;
use Drupal\webform\WebformSubmissionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Webforms filled in on the Next.js site (contact forms).
 *
 * GET  /api/webform/{webform_id}?lang=ar   → { id, title, open, options: { sponsorship: [{value,label}] } }
 * POST /api/webform/{webform_id}           { lang, data: { first_name, email, … } }
 *   201 { ok: true, sid } · 400 { error: "invalid", fields } · 403 closed · 404 · 429 too many
 *
 * The submission is saved like one made on the Drupal form, so the webform's e-mail
 * handlers and the Results tab work as usual. Only webforms listed in ALLOWED can be used.
 */
class WebformApiController extends ControllerBase {

  /** Webforms the Next.js site may read and submit. */
  protected const ALLOWED = ['ewa_contact_us'];

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
    foreach ($webform->getElementsInitializedFlattenedAndHasValue() as $key => $element) {
      if (empty($element['#options'])) {
        continue;
      }
      $title = mb_strtolower(trim((string) ($element['#title'] ?? '')));
      $list = [];
      foreach (WebformOptionsHelper::getElementOptions($element) as $value => $label) {
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
      if (isset($allowed[$key]) && (is_scalar($value) || $value === NULL)) {
        $data[$key] = trim((string) $value);
      }
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
      $fields = [];
      foreach ($result as $key => $message) {
        $fields[explode('][', (string) $key)[0]] = trim(strip_tags((string) $message));
      }
      return $this->json(['error' => 'invalid', 'fields' => $fields], 400);
    }
    return $this->json(['error' => 'unavailable'], 500);
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
