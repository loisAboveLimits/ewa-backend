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
