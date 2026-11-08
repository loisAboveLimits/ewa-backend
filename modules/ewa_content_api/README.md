# EWA Content API

Nested JSON for the Next.js site.

## Install
1. Copy this folder to `web/modules/custom/ewa_content_api` (next to `ewa_menu_api`).
2. In the **EWA Media Center API's** view, delete the **REST API - Campus** display
   (or change its path) — this module now answers `/api/media/campus/{langcode}`.
3. `drush en ewa_content_api -y && drush cr`

## Endpoints
- `GET /api/media/campus/en` — Campus & Facilities (term 12) in English
- `GET /api/media/campus/ar` — the same in Arabic
- `GET /api/media/category/{tid}/{langcode}` — any Media Center category

Each item: `nid`, `title`, `main_title` (plain text), `content` (HTML), `weight`, `gallery[]`
(`delta`, `mid`, `image` URL, `alt`). Sorted by the Weigth field ASC, then node id.

## Media Center posts (for /media-center/[category]/[slug])
- `GET /api/media/post/{category}/{slug}/{langcode}` — one post, or `404 {"message": …}`
- `GET /api/media/posts/{category}/{langcode}` — all posts of a category, newest first

`{category}` = slug of the Media Center term name (Events → `events`, News & Articles → `news-articles`).
`{slug}` = made automatically from the post's English **Main Title**
("Renewable Energy Innovation Summit" → `renewable-energy-innovation-summit`). The Arabic version
uses the same slug. Same titles in one category get `-2`, `-3`…
Optional: add a plain-text field **Slug** (`field_slug`) to Media Center — it is filled from the
Main Title on save when empty, and a value typed there wins.

Post JSON: `nid, slug, href, category{tid,slug,name}, title, main_title, excerpt, date (UTC ISO),
featured, weight, image{mid,image,alt}, content (HTML), gallery[]`.

After updating the module files: `drush cr`.

## Media Center category page sections
`{category}` = events, news-articles, success-stories, graduate-projects, gallery — or `all`
(every category except Campus & Facilities).

| Section | Endpoint | What |
|---|---|---|
| 1 | `GET /api/media/featured/{category}/{lang}?limit=3` | "Featured" posts, newest first |
| 2 | `GET /api/media/latest/{category}/{lang}?limit=3` | Events: upcoming, soonest first (past events fill up if fewer than 3). Others: latest |
| 3 | `GET /api/media/posts/{category}/{lang}` | all posts, newest first |

`date` = the event's **Date** field for Events, the **publishing date** (Authored on) for every
other category. Each item also has `event_date` and `published`.

## Careers

`GET /api/careers/{langcode}` (`?limit=5`) — published nodes of the content type **careers**, newest first.
Each item: `nid`, `title`, `main_title`, `lang`, `published`, `date` (first date field, else published),
`location` (field named *location / city / branch / place / region*), `image` (`{ image, alt }`),
`link` (first Link field), `excerpt`, and `fields` (every `field_*` as plain values).
`slug`, `href` (`/career/{slug}`) and `content` (HTML) are included too.
`GET /api/career/{slug}/{langcode}` — one career (404 JSON when not found). The slug comes from the
career's English Main Title (or `field_slug` when the content type has one — filled in on save).
A career without a translation in `{langcode}` comes back in its original language.

## Awards

`GET /api/awards/{langcode}` (`?limit=10`) — published nodes of the content type **awards**, sorted by
their date field (newest first; the publishing date when an award has no date). Same item shape as the
careers API: `nid`, `title`, `main_title`, `date`, `published`, `image` (`{ image, alt }`), `excerpt`,
`content` (HTML), `fields`. An award without a translation in `{langcode}` comes back in its original language.

## Team

`GET /api/team/{langcode}` — published nodes of the content type **team**.
`GET /api/team/{slug}/{langcode}` — one member (404 JSON when not found); the slug comes from the member's
English name (node title). Same item shape as the careers API plus `position` (`field_position`).

## Sign-in (Next.js login + Trainee Dashboard)

- `POST /api/auth/login` — body `{ "identifier": "<username or e-mail>", "password": "…" }`.
  Success: user data + `logout_token`, and Drupal's session cookie in `Set-Cookie`.
  401 wrong details or blocked account, 429 too many failed attempts (Drupal flood control).
- `GET /api/auth/me` — the signed-in user (send the session cookie), 401 when signed out.
- Sign out: core `POST /user/logout?_format=json&token={logout_token}` with the session cookie.

The Next.js server makes these calls, so Drupal sees the Next.js server's IP for every visitor.
Drupal's per-IP flood limit (50 failures / hour by default) therefore counts all visitors together:
configure `reverse_proxy` / `reverse_proxy_addresses` in settings.php for the Next.js server so Drupal
uses the visitor's IP from `X-Forwarded-For` (the Next.js login route sends it).

## Password reset (Next.js "Forgot password?")

- `POST /api/auth/password/forgot` — `{ "identifier": "<username or e-mail>", "lang": "en" | "ar" }`.
  Always answers 200; when the account exists it e-mails a one-time link (Drupal's own token,
  valid for `password_reset_timeout`, 24 h by default) to the Next.js page `/reset-password`.
- `POST /api/auth/password/reset` — `{ "uid", "ts", "hash", "password" }` (password ≥ 8 characters).
  200 changed · 400 `weak` · 403 `invalid` (wrong, expired or already used link).

Required in `settings.php` (the address of the Next.js site, no trailing slash):

    $settings['ewa_frontend_url'] = 'http://localhost:3000';

Without it, the e-mail links to Drupal's own reset page. Drupal must be able to send e-mail
(e.g. the SMTP or Symfony Mailer module on servers without a local mail server).

## Registration (Next.js "Register in EWA")

`POST /api/auth/register` — creates the trainee's Drupal account from the registration form:
username = National ID, e-mail, password, and the fields of the **ewa_trainee_fields** module
(install that module first). Selected courses are matched to terms of the `programs` vocabulary
by name ("facilities-management" ↔ "Facilities Management"); unmatched ones are logged.

Account status follows Configuration › People › Account settings › "Who can register accounts?":
- Visitors → active at once (`status: "active"`), the trainee can sign in straight away.
- Visitors, but administrator approval is required → blocked (`status: "pending"`); Drupal e-mails
  the trainee and the site; activate the account under People.
- Administrators only → registration is refused (`error: "closed"`).

`GET /api/auth/me?lang=en|ar` now also returns `profile` (the registration values + programs).

Sign-in and "forgot password" accept the username, the e-mail, or the National ID / Iqama number
(also for accounts whose username is something else, through `field_national_id`).
