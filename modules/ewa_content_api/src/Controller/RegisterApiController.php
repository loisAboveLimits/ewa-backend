<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Flood\FloodInterface;
use Drupal\ewa_content_api\TraineeProfile;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Trainee registration from the Next.js "Register in EWA" form.
 *
 * POST /api/auth/register  { ...registration fields, password, courses: [ids], lang }
 *   201 { status: "active" | "pending", unmatched_programs: [...] }
 *   400 { error: "invalid", fields: { nationalId: "...", ... } }
 *   409 { error: "nationalId_taken" | "email_taken" }
 *   403 { error: "closed" }   (Account settings: only administrators can register accounts)
 *   429 { error: "too_many" }
 *
 * The account's username is the National ID, so trainees sign in with their National ID or
 * e-mail. Whether the account is active straight away follows Configuration › People ›
 * Account settings › "Who can register accounts?" (visitors → active, approval → blocked until
 * an administrator activates it; Drupal then e-mails the trainee and the administrator).
 */
class RegisterApiController extends ControllerBase {

  public const MIN_PASSWORD = 8;

  public function __construct(protected FloodInterface $flood) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('flood'));
  }

  public function register(Request $request): JsonResponse {
    $mode = $this->config('user.settings')->get('register');
    if ($mode === 'admin_only') {
      return $this->json(['error' => 'closed'], 403);
    }
    if (!$this->flood->isAllowed('ewa_content_api.register', 10, 3600)) {
      return $this->json(['error' => 'too_many'], 429);
    }
    $this->flood->register('ewa_content_api.register', 3600);

    $data = json_decode((string) $request->getContent(), TRUE) ?: [];
    // National ID (starts with 1) or Iqama (starts with 2) — becomes the username
    $nationalId = preg_replace('/\s+/', '', strtr((string) ($data['nationalId'] ?? ''), ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']));
    $data['nationalId'] = $nationalId;
    $mail = trim((string) ($data['email'] ?? ''));
    $password = (string) ($data['password'] ?? '');
    $lang = in_array($data['lang'] ?? '', ['en', 'ar'], TRUE) ? $data['lang'] : 'en';

    // the main checks again (the form checks everything, but never trust the browser)
    $errors = [];
    if (!preg_match('/^[12]\d{9}$/', $nationalId)) {
      $errors['nationalId'] = 'invalid';
    }
    if (!\Drupal::service('email.validator')->isValid($mail)) {
      $errors['email'] = 'invalid';
    }
    if (mb_strlen($password) < self::MIN_PASSWORD) {
      $errors['password'] = 'invalid';
    }
    foreach (['firstName', 'lastName'] as $key) {
      if (trim((string) ($data[$key] ?? '')) === '') {
        $errors[$key] = 'invalid';
      }
    }
    if ($errors) {
      return $this->json(['error' => 'invalid', 'fields' => $errors], 400);
    }

    $storage = $this->entityTypeManager()->getStorage('user');
    if ($storage->loadByProperties(['name' => $nationalId]) || TraineeProfile::findAccount($nationalId)) {
      return $this->json(['error' => 'nationalId_taken'], 409);
    }
    if ($storage->loadByProperties(['mail' => $mail])) {
      return $this->json(['error' => 'email_taken'], 409);
    }

    $active = $mode === 'visitors';
    /** @var \Drupal\user\UserInterface $account */
    $account = $storage->create([
      'name' => $nationalId,
      'mail' => $mail,
      'init' => $mail,
      'pass' => $password,
      'status' => $active ? 1 : 0,
      'langcode' => $lang,
      'preferred_langcode' => $lang,
    ]);
    TraineeProfile::apply($account, $data);

    $unmatched = [];
    if ($account->hasField(TraineeProfile::PROGRAMS_FIELD)) {
      [$tids, $unmatched] = TraineeProfile::matchPrograms((array) ($data['courses'] ?? []));
      $account->set(TraineeProfile::PROGRAMS_FIELD, $tids);
    }

    $violations = $account->validate();
    if (count($violations)) {
      $fields = [];
      foreach ($violations as $violation) {
        $path = explode('.', $violation->getPropertyPath())[0];
        $key = $path === 'name' ? 'nationalId' : ($path === 'mail' ? 'email' : ($path === 'pass' ? 'password' : $path));
        $fields[$key] = strip_tags((string) $violation->getMessage());
      }
      $this->getLogger('ewa_content_api')->warning('Registration rejected: @fields', ['@fields' => json_encode($fields)]);
      return $this->json(['error' => 'invalid', 'fields' => $fields], 400);
    }

    $account->save();
    if ($unmatched) {
      $this->getLogger('ewa_content_api')->notice('Registration of %name: programs not found in the "programs" vocabulary: @ids', [
        '%name' => $nationalId,
        '@ids' => implode(', ', $unmatched),
      ]);
    }
    if (!$active) {
      // Drupal's own "pending approval" e-mails (to the trainee and the site e-mail)
      _user_mail_notify('register_pending_approval', $account);
    }

    return $this->json(['status' => $active ? 'active' : 'pending', 'unmatched_programs' => $unmatched], 201);
  }

  protected function json(array $data, int $status = 200): JsonResponse {
    $response = new JsonResponse($data, $status);
    $response->setPrivate();
    $response->headers->addCacheControlDirective('no-store');
    return $response;
  }

}
