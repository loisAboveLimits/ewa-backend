# EWA Menu API (Drupal 10.3+ / 11)

Serves any Drupal menu as JSON for the Next.js site.

## Install
1. Copy this folder to `web/modules/custom/ewa_menu_api`
   (or `modules/custom/ewa_menu_api` if your Drupal root has no `web/` folder).
2. Enable it: `drush en ewa_menu_api -y` (or Extend → "EWA Menu API").
3. Rebuild caches: `drush cr`.

## Use
- English main menu: `GET {DRUPAL_BASE_URL}/api/menu/main/en`
- Arabic main menu:  `GET {DRUPAL_BASE_URL}/api/menu/main/ar`
- Footer (any menu machine name): `/api/menu/footer/en`

Arabic titles come from translated menu links
(Configuration → Regional and language → Content language → enable "Custom menu link" translation,
then Structure → Menus → Main navigation → edit link → Translate).

Response: nested array — see the comment at the top of src/Controller/MenuApiController.php.
