<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Flood\FloodInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Site\Settings;
use Drupal\Core\Url;
use Drupal\ewa_content_api\TraineeProfile;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Password reset for the Next.js site.
 *
 * POST /api/auth/password/forgot  { "identifier": "<username or e-mail>", "lang": "en|ar" }
 *   Always 200 (never tells whether an account exists). When it does, e-mails a one-time link
 *   to the Next.js page {ewa_frontend_url}/reset-password?uid=…&ts=…&hash=…
 *   (Arabic: {ewa_frontend_url}/ar/reset-password…). 429 when too many requests.
 *
 * POST /api/auth/password/reset   { "uid": 1, "ts": 1700000000, "hash": "…", "password": "…" }
 *   200 password changed · 400 weak password · 403 link invalid / expired / already used.
 *
 * The link uses Drupal's own one-time token (same as core's /user/reset links): it expires after
 * "password_reset_timeout" (24 h by default) and stops working once the password is changed.
 *
 * settings.php:  $settings['ewa_frontend_url'] = 'https://www.example.com';
 * Without it the e-mail links to Drupal's own reset page instead.
 */
class PasswordApiController extends ControllerBase {

  public const MIN_LENGTH = 8;

  public function __construct(
    protected FloodInterface $flood,
    protected MailManagerInterface $mailManager,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('flood'), $container->get('plugin.manager.mail'));
  }

  public function forgot(Request $request): JsonResponse {
    $body = json_decode((string) $request->getContent(), TRUE) ?: [];
    $identifier = trim((string) ($body['identifier'] ?? ''));
    $lang = in_array($body['lang'] ?? '', ['en', 'ar'], TRUE) ? $body['lang'] : 'en';
    if ($identifier === '') {
      return $this->json(['message' => 'Missing identifier.'], 400);
    }

    $config = $this->config('user.flood');
    if (!$this->flood->isAllowed('user.password_request_ip', $config->get('ip_limit'), $config->get('ip_window'))) {
      return $this->json(['message' => 'Too many requests. Try again later.'], 429);
    }
    $this->flood->register('user.password_request_ip', $config->get('ip_window'));

    // username, e-mail, or National ID / Iqama number
    $account = TraineeProfile::findAccount($identifier);

    if ($account instanceof UserInterface && $account->isActive() && $account->getEmail()) {
      $floodId = (string) $account->id();
      if ($this->flood->isAllowed('user.password_request_user', $config->get('user_limit'), $config->get('user_window'), $floodId)) {
        $this->flood->register('user.password_request_user', $config->get('user_window'), $floodId);
        $this->sendResetMail($account, $lang);
      }
    }

    // same answer whether or not the account exists
    return $this->json(['ok' => TRUE]);
  }

  public function reset(Request $request): JsonResponse {
    $body = json_decode((string) $request->getContent(), TRUE) ?: [];
    $uid = (int) ($body['uid'] ?? 0);
    $timestamp = (int) ($body['ts'] ?? 0);
    $hash = (string) ($body['hash'] ?? '');
    $password = (string) ($body['password'] ?? '');

    if (mb_strlen($password) < self::MIN_LENGTH) {
      return $this->json(['message' => 'Password too short.', 'error' => 'weak'], 400);
    }

    $account = $uid ? $this->entityTypeManager()->getStorage('user')->load($uid) : NULL;
    $timeout = (int) $this->config('user.settings')->get('password_reset_timeout');
    $now = \Drupal::time()->getRequestTime();

    $valid = $account instanceof UserInterface
      && $account->isActive()
      && $timestamp <= $now
      && ($now - $timestamp) < $timeout
      // like core: a link stops working once the account has signed in after it was made
      && $account->getLastLoginTime() < $timestamp
      && $hash !== ''
      && hash_equals(user_pass_rehash($account, $timestamp), $hash);

    if (!$valid) {
      return $this->json(['message' => 'This link is invalid or has expired.', 'error' => 'invalid'], 403);
    }

    $account->setPassword($password);
    $account->save();
    $this->flood->clear('user.password_request_user', (string) $account->id());
    $this->getLogger('ewa_content_api')->notice('Password reset for %name through the website.', ['%name' => $account->getAccountName()]);

    return $this->json(['ok' => TRUE]);
  }

  protected function sendResetMail(UserInterface $account, string $lang): void {
    $timestamp = \Drupal::time()->getRequestTime();
    $hash = user_pass_rehash($account, $timestamp);
    $frontend = rtrim((string) Settings::get('ewa_frontend_url', ''), '/');

    if ($frontend !== '') {
      $query = http_build_query(['uid' => $account->id(), 'ts' => $timestamp, 'hash' => $hash]);
      $url = $frontend . ($lang === 'en' ? '' : '/' . $lang) . '/reset-password?' . $query;
    }
    else {
      // no Next.js address configured: Drupal's own one-time login page
      $url = Url::fromRoute('user.reset', ['uid' => $account->id(), 'timestamp' => $timestamp, 'hash' => $hash], ['absolute' => TRUE])->toString();
    }

    $hours = max(1, (int) round($this->config('user.settings')->get('password_reset_timeout') / 3600));
    $name = $account->getDisplayName();
    $params = $lang === 'ar'
      ? [
        'subject' => 'إعادة تعيين كلمة المرور – أكاديمية الطاقة والمياه',
        'body' => "مرحبًا $name،\n\nتلقينا طلبًا لإعادة تعيين كلمة المرور لحسابك في أكاديمية الطاقة والمياه.\nلاختيار كلمة مرور جديدة، افتح الرابط التالي (صالح لمدة $hours ساعة ولمرة واحدة فقط):\n\n$url\n\nإذا لم تطلب ذلك، يمكنك تجاهل هذه الرسالة وستبقى كلمة المرور كما هي.\n\nأكاديمية الطاقة والمياه",
      ]
      : [
        'subject' => 'Reset your password – Energy & Water Academy',
        'body' => "Hello $name,\n\nWe received a request to reset the password of your Energy & Water Academy account.\nTo choose a new password, open this link (it works once, for $hours hours):\n\n$url\n\nIf you didn't ask for this, you can ignore this e-mail and your password will stay the same.\n\nEnergy & Water Academy",
      ];

    $result = $this->mailManager->mail('ewa_content_api', 'password_reset', $account->getEmail(), $lang, $params, NULL, TRUE);
    if (empty($result['result'])) {
      $this->getLogger('ewa_content_api')->error('Password reset e-mail to %mail could not be sent.', ['%mail' => $account->getEmail()]);
    }
  }

  protected function json(array $data, int $status = 200): JsonResponse {
    $response = new JsonResponse($data, $status);
    $response->setPrivate();
    $response->headers->addCacheControlDirective('no-store');
    return $response;
  }

}
