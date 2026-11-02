<?php

declare(strict_types=1);

namespace Drupal\ewa_menu_api\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Url;
use Drupal\menu_link_content\MenuLinkContentInterface;
use Drupal\path_alias\AliasManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Returns a Drupal menu as a nested JSON array.
 *
 * Each item:
 * {
 *   "id": "menu_link_content:8c1e…",   plugin id (stable key)
 *   "title": "About EWA",              translated to {langcode}
 *   "description": "",
 *   "url": "/ewa/ar/about",            full Drupal URL (with base path + language prefix)
 *   "path": "/about",                  alias only — use this in Next.js <Link href>
 *   "external": false,
 *   "weight": 0,
 *   "expanded": false,
 *   "children": [ …same shape… ]
 * }
 */
final class MenuApiController extends ControllerBase {

  public function __construct(
    private readonly MenuLinkTreeInterface $menuTree,
    private readonly AliasManagerInterface $aliasManager,
    private readonly EntityRepositoryInterface $entityRepository,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('menu.link_tree'),
      $container->get('path_alias.manager'),
      $container->get('entity.repository'),
    );
  }

  public function menu(string $menu, string $langcode = ''): CacheableJsonResponse {
    $menu_entity = $this->entityTypeManager()->getStorage('menu')->load($menu);
    if (!$menu_entity) {
      throw new NotFoundHttpException("Menu '$menu' does not exist.");
    }

    $language = $langcode !== ''
      ? $this->languageManager()->getLanguage($langcode)
      : $this->languageManager()->getCurrentLanguage(LanguageInterface::TYPE_CONTENT);
    if (!$language) {
      throw new NotFoundHttpException("Language '$langcode' is not enabled.");
    }

    // Enabled links only, checked for access, sorted by weight (like the menu block does).
    $tree = $this->menuTree->load($menu, (new MenuTreeParameters())->onlyEnabledLinks());
    $tree = $this->menuTree->transform($tree, [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ]);

    // Cached until the menu or any custom menu link changes.
    $cache = (new CacheableMetadata())
      ->addCacheableDependency($menu_entity)
      ->addCacheTags(['config:system.menu.' . $menu, 'menu_link_content_list'])
      ->addCacheContexts(['url.path', 'user.permissions']);

    $items = $this->buildItems($tree, $language, $cache);

    $response = new CacheableJsonResponse($items);
    $response->addCacheableDependency($cache);
    return $response;
  }

  /**
   * @param \Drupal\Core\Menu\MenuLinkTreeElement[] $tree
   */
  private function buildItems(array $tree, LanguageInterface $language, CacheableMetadata $cache): array {
    $items = [];

    foreach ($tree as $element) {
      assert($element instanceof MenuLinkTreeElement);

      if ($element->access !== NULL) {
        $cache->addCacheableDependency($element->access);
        if (!$element->access->isAllowed()) {
          continue;
        }
      }

      $link = $element->link;
      $title = (string) $link->getTitle();
      $description = (string) $link->getDescription();

      // Links created in the UI: use the translation for the requested language.
      if ($link->getProvider() === 'menu_link_content') {
        $entity = $this->entityRepository->loadEntityByUuid('menu_link_content', (string) $link->getDerivativeId());
        if ($entity instanceof MenuLinkContentInterface) {
          if ($entity->hasTranslation($language->getId())) {
            $entity = $entity->getTranslation($language->getId());
          }
          $title = (string) $entity->label();
          $description = (string) $entity->getDescription();
          $cache->addCacheableDependency($entity);
        }
      }

      $url = $link->getUrlObject();
      $url->setOption('language', $language);
      $generated = $url->toString(TRUE);
      $cache->addCacheableDependency($generated);

      $items[] = [
        'id' => $link->getPluginId(),
        'title' => $title,
        'description' => $description,
        'url' => $generated->getGeneratedUrl(),
        'path' => $this->frontEndPath($url, $language->getId()),
        'external' => $url->isExternal(),
        'weight' => (int) $link->getWeight(),
        'expanded' => (bool) $link->isExpanded(),
        'children' => $element->subtree ? $this->buildItems($element->subtree, $language, $cache) : [],
      ];
    }

    return $items;
  }

  /**
   * Path for the Next.js site: the path alias without Drupal's base path or
   * language prefix ("/about"), the full URL for external links, "" for <nolink>.
   */
  private function frontEndPath(Url $url, string $langcode): string {
    if ($url->isExternal()) {
      return $url->toString();
    }
    if ($url->isRouted()) {
      $route = $url->getRouteName();
      if ($route === '<nolink>' || $route === '<none>' || $route === '<button>') {
        return '';
      }
      if ($route === '<front>') {
        return '/';
      }
      $internal = '/' . ltrim($url->getInternalPath(), '/');
      return $this->aliasManager->getAliasByPath($internal, $langcode);
    }
    // Unrouted "base:" / "internal:" URIs, e.g. base:/about
    $path = preg_replace('#^(base|internal):#', '', $url->getUri());
    return '/' . ltrim((string) $path, '/');
  }

}
