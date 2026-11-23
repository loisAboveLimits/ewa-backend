<?php

/**
 * Export a Drupal menu (default: "front") with all its links and translations to JSON.
 *
 *   drush php:script menu-export.php > front-menu.json
 *   drush php:script menu-export.php -- footer-menu-name > footer-menu.json
 *
 * Copy the JSON to the other site and run menu-import.php there.
 */

use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\system\Entity\Menu;

$menu_name = $extra[0] ?? 'front';

$menu = Menu::load($menu_name);
if (!$menu) {
  fwrite(STDERR, "Menu \"$menu_name\" not found.\n");
  exit(1);
}

$out = [
  'menu' => [
    'id' => $menu->id(),
    'label' => $menu->label(),
    'description' => $menu->getDescription(),
  ],
  'links' => [],
];

$ids = \Drupal::entityQuery('menu_link_content')
  ->accessCheck(FALSE)
  ->condition('menu_name', $menu_name)
  ->sort('weight')
  ->execute();

foreach (MenuLinkContent::loadMultiple($ids) as $link) {
  $default = $link->getUntranslated();
  $row = [
    'uuid' => $link->uuid(),
    'langcode' => $default->language()->getId(),
    'title' => $default->getTitle(),
    'description' => (string) $default->getDescription(),
    'link' => $default->get('link')->uri,
    'options' => $default->get('link')->options ?? [],
    // "menu_link_content:<uuid>" of the parent link, or "" for top level
    'parent' => (string) $default->getParentId(),
    'weight' => (int) $default->getWeight(),
    'expanded' => (bool) $default->isExpanded(),
    'enabled' => (bool) $default->isEnabled(),
    'translations' => [],
  ];
  foreach ($link->getTranslationLanguages(FALSE) as $langcode => $language) {
    $t = $link->getTranslation($langcode);
    $row['translations'][$langcode] = [
      'title' => $t->getTitle(),
      'description' => (string) $t->getDescription(),
    ];
  }
  $out['links'][] = $row;
}

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";
fwrite(STDERR, count($out['links']) . " links exported from \"$menu_name\".\n");
