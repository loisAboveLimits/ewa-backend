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
