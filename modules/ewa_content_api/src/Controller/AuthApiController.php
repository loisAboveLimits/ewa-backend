<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ewa_content_api\TraineeProfile;
use Drupal\user\UserAuthInterface;
use Drupal\user\UserFloodControlInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Sign-in for the Next.js site (the Next.js server calls these; browsers never talk to Drupal).
 *
 * POST /api/auth/login   body { "identifier": "<username, e-mail or National ID / Iqama>", "password": "…" }
 *   200 { user…, "logout_token": "…" } + the Drupal session cookie (Set-Cookie)
 *   400 missing fields · 401 wrong details / blocked account · 429 too many attempts
 *   Uses Drupal's own password check and flood control (Configuration › People › Account settings).
 *
 * GET /api/auth/me?lang=en|ar   (send the Drupal session cookie)
 *   200 { uid, name, display_name, mail, roles, created, access, login, langcode, profile } · 401 not signed in
 *   profile = the registration fields (TraineeProfile) + programs [{ tid, name, slug }]
 *
 * Sign out with Drupal core: POST /user/logout?_format=json&token={logout_token}
 */
class AuthApiController extends ControllerBase {

  public function __construct(
    protected UserAuthInterface $userAuth,
    protected UserFloodControlInterface $flood,
    protected CsrfTokenGenerator $csrf,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('user.auth'),
      $container->get('user.flood_control'),
      $container->get('csrf_token'),
    );
  }

  public function login(Request $request): JsonResponse {
    $body = json_decode((string) $request->getContent(), TRUE) ?: [];
    $identifier = trim((string) ($body['identifier'] ?? $body['name'] ?? ''));
    $password = (string) ($body['password'] ?? $body['pass'] ?? '');
    if ($identifier === '' || $password === '') {
      return $this->json(['message' => 'Missing identifier or password.'], 400);
    }

    $config = $this->config('user.flood');
    if (!$this->flood->isAllowed('user.failed_login_ip', $config->get('ip_limit'), $config->get('ip_window'))) {
      return $this->json(['message' => 'Too many failed sign-in attempts. Try again later.'], 429);
    }

    // username, e-mail, or National ID / Iqama number
    $account = TraineeProfile::findAccount($identifier);

    $floodId = NULL;
    if ($account instanceof UserInterface) {
      $floodId = $config->get('uid_only') ? (string) $account->id() : $account->id() . '-' . $request->getClientIp();
      if (!$this->flood->isAllowed('user.failed_login_user', $config->get('user_limit'), $config->get('user_window'), $floodId)) {
        return $this->json(['message' => 'Too many failed sign-in attempts. Try again later.'], 429);
      }
    }

    $uid = ($account instanceof UserInterface && !$account->isBlocked())
      ? $this->userAuth->authenticate($account->getAccountName(), $password)
      : FALSE;

    if (!$uid) {
      $this->flood->register('user.failed_login_ip', $config->get('ip_window'));
      if ($floodId) {
        $this->flood->register('user.failed_login_user', $config->get('user_window'), $floodId);
      }
      return $this->json(['message' => 'Unrecognized username/e-mail or password.'], 401);
    }

    if ($floodId) {
      $this->flood->clear('user.failed_login_user', $floodId);
    }
    user_login_finalize($account);

    return $this->json($this->userData($account) + ['logout_token' => $this->csrf->get('user/logout')]);
  }

  public function me(Request $request): JsonResponse {
    if ($this->currentUser()->isAnonymous()) {
      return $this->json(['message' => 'Not signed in.'], 401);
    }
    $account = $this->entityTypeManager()->getStorage('user')->load($this->currentUser()->id());
    return $account instanceof UserInterface
      ? $this->json($this->userData($account) + [
        // registration fields (ewa_trainee_fields) — program names in ?lang=en|ar
        'profile' => TraineeProfile::read($account, in_array($request->query->get('lang'), ['en', 'ar'], TRUE) ? $request->query->get('lang') : 'en'),
      ])
      : $this->json(['message' => 'Not signed in.'], 401);
  }

  protected function userData(UserInterface $account): array {
    $iso = fn($t) => $t ? gmdate('Y-m-d\TH:i:s\Z', (int) $t) : NULL;
    return [
      'uid' => (int) $account->id(),
      'name' => $account->getAccountName(),
      'display_name' => (string) $account->getDisplayName(),
      'mail' => (string) $account->getEmail(),
      'roles' => array_values(array_diff($account->getRoles(), ['authenticated'])),
      'langcode' => $account->getPreferredLangcode(),
      'created' => $iso($account->getCreatedTime()),
      'access' => $iso($account->getLastAccessedTime()),
      'login' => $iso($account->getLastLoginTime()),
      // first name from registration (ewa_trainee_fields), for greetings
      'first_name' => $account->hasField('field_first_name') ? (string) ($account->get('field_first_name')->value ?? '') : '',
    ];
  }

  /** Personal data: never cached. */
  protected function json(array $data, int $status = 200): JsonResponse {
    $response = new JsonResponse($data, $status);
    $response->setPrivate();
    $response->headers->addCacheControlDirective('no-store');
    return $response;
  }

}
