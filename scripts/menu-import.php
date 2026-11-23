<?php

/**
 * Import a menu exported with menu-export.php.
 *
 *   drush php:script menu-import.php -- front-menu.json
 *
 * - Creates the menu if it doesn't exist yet.
 * - Links are matched by UUID: existing ones are updated, missing ones are created,
 *   so running it twice is safe. Links on this site that are NOT in the file are left alone.
 * - Parents and translations (e.g. Arabic titles) are kept.
 * Then clear the cache: drush cr
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\system\Entity\Menu;

$file = $extra[0] ?? NULL;
if (!$file || !is_readable($file)) {
  fwrite(STDERR, "Usage: drush php:script menu-import.php -- front-menu.json\n");
  exit(1);
}
$data = json_decode(file_get_contents($file), TRUE);
if (!is_array($data['menu'] ?? NULL) || !is_array($data['links'] ?? NULL)) {
  fwrite(STDERR, "Not a menu export file.\n");
  exit(1);
}

$menu_name = $data['menu']['id'];
if (!Menu::load($menu_name)) {
  Menu::create([
    'id' => $menu_name,
    'label' => $data['menu']['label'],
    'description' => $data['menu']['description'] ?? '',
  ])->save();
  echo "Created menu \"$menu_name\".\n";
}

// parents before children, so every parent exists when its child is saved
$links = $data['links'];
$byUuid = [];
foreach ($links as $row) {
  $byUuid[$row['uuid']] = $row;
}
$depth = function (array $row) use (&$depth, $byUuid): int {
  $parent = $row['parent'] ? substr($row['parent'], strlen('menu_link_content:')) : '';
  return $parent && isset($byUuid[$parent]) ? 1 + $depth($byUuid[$parent]) : 0;
};
usort($links, fn($a, $b) => $depth($a) <=> $depth($b));

$repository = \Drupal::service('entity.repository');
$languages = \Drupal::languageManager()->getLanguages();
$created = $updated = 0;

foreach ($links as $row) {
  $link = $repository->loadEntityByUuid('menu_link_content', $row['uuid']);
  $values = [
    'menu_name' => $menu_name,
    'title' => $row['title'],
    'description' => $row['description'],
    'link' => ['uri' => $row['link'], 'options' => $row['options'] ?? []],
    'parent' => $row['parent'],
    'weight' => $row['weight'],
    'expanded' => $row['expanded'],
    'enabled' => $row['enabled'],
  ];

  if ($link instanceof MenuLinkContent) {
    foreach ($values as $field => $value) {
      $link->set($field, $value);
    }
    $updated++;
  }
  else {
    $link = MenuLinkContent::create($values + ['uuid' => $row['uuid'], 'langcode' => $row['langcode']]);
    $created++;
  }

  foreach ($row['translations'] as $langcode => $t) {
    if (!isset($languages[$langcode])) {
      continue; // language not enabled on this site
    }
    if ($langcode === $link->language()->getId()) {
      continue;
    }
    if ($link->hasTranslation($langcode)) {
      $translation = $link->getTranslation($langcode);
      $translation->set('title', $t['title']);
      $translation->set('description', $t['description']);
    }
    else {
      $link->addTranslation($langcode, ['title' => $t['title'], 'description' => $t['description']]);
    }
  }

  $link->save();
}

\Drupal::service('plugin.manager.menu.link')->rebuild();
echo "Menu \"$menu_name\": $created created, $updated updated. Now run: drush cr\n";
