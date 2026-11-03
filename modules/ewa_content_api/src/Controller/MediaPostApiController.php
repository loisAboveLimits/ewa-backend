<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\ewa_content_api\Slug;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Media Center posts by category and slug (for /media-center/[category]/[slug]).
 *
 * GET /api/media/post/{category}/{slug}/{langcode}   one post (404 JSON when not found)
 * GET /api/media/featured/{category}/{langcode}      section 1: featured posts (?limit=3)
 * GET /api/media/latest/{category}/{langcode}        section 2: upcoming (events) / latest (?limit=3)
 * GET /api/media/posts/{category}/{langcode}         section 3: all posts, newest first
 *
 * {category} "all" = every tab category together (Campus & Facilities is left out).
 * Dates: Events use their "Date" field (field_date); every other category uses the
 * publishing date (node "Authored on"). The value used is returned as "date".
 *
 * {category} is the slug of a "Media Center" taxonomy term name: "Events" → events,
 * "News & Articles" → news-articles, "Campus & Facilities" → campus-facilities.
 * {slug} is made from the post's original (English) Main Title — see \Drupal\ewa_content_api\Slug.
 */
class MediaPostApiController extends ControllerBase {

  protected const BUNDLE = 'media_center';
  protected const VOCABULARY = 'media_center';
  protected const ALL = 'all';
  protected const EVENTS = 'events';
  /** Categories that are not news-style posts, left out of "all". */
  protected const NOT_IN_ALL = ['campus-facilities'];

  public function __construct(protected FileUrlGeneratorInterface $fileUrlGenerator) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('file_url_generator'));
  }

  /** One post. */
  public function post(string $category, string $slug, string $langcode): CacheableJsonResponse {
    $cache = $this->baseCache();
    $term = $this->findCategory($category, $cache);
    if (!$term) {
      return $this->notFound("Unknown category \"$category\"", $cache);
    }

    $nodes = $this->postsWithSlugs($term, $cache);
    $node = $nodes[$slug] ?? NULL;
    if (!$node || !$node->hasTranslation($langcode)) {
      return $this->notFound("No post \"$slug\" in \"$category\" ($langcode)", $cache);
    }
    $node = $node->getTranslation($langcode);
    if (!$node->isPublished() || !$node->access('view')) {
      return $this->notFound("No post \"$slug\" in \"$category\" ($langcode)", $cache);
    }

    return (new CacheableJsonResponse($this->serialize($node, $slug, $term, $langcode, TRUE, $cache)))
      ->addCacheableDependency($cache);
  }

  /**
   * Section 3 — every post of a category, by date, newest first.
   * GET /api/media/posts/{category}/{langcode}
   */
  public function posts(string $category, string $langcode): CacheableJsonResponse {
    $cache = $this->baseCache();
    $items = $this->listItems($category, $langcode, $cache);
    if ($items === NULL) {
      return $this->notFound("Unknown category \"$category\"", $cache);
    }
    usort($items, [static::class, 'newestFirst']);
    return (new CacheableJsonResponse($items))->addCacheableDependency($cache);
  }

  /**
   * Section 1 — posts with "Featured" checked, by date, newest first (default 3).
   * GET /api/media/featured/{category}/{langcode}?limit=3
   */
  public function featured(string $category, string $langcode, Request $request): CacheableJsonResponse {
    $cache = $this->baseCache()->addCacheContexts(['url.query_args:limit']);
    $items = $this->listItems($category, $langcode, $cache);
    if ($items === NULL) {
      return $this->notFound("Unknown category \"$category\"", $cache);
    }
    return (new CacheableJsonResponse($this->featuredItems($category, $items, $this->limit($request))))
      ->addCacheableDependency($cache);
  }

  /** Featured posts; for "all" they take turns across categories. */
  protected function featuredItems(string $category, array $items, int $limit): array {
    $items = array_values(array_filter($items, fn(array $i) => $i['featured']));
    if ($category === self::ALL) {
      return array_slice($this->mix($items, fn(array $group) => $this->sortNewestFirst($group)), 0, $limit);
    }
    usort($items, [static::class, 'newestFirst']);
    return array_slice($items, 0, $limit);
  }

  /**
   * Section 2 — upcoming / latest (default 3).
   * GET /api/media/latest/{category}/{langcode}?limit=3
   * Events: the next upcoming events (event date from now on, soonest first); if there are
   * fewer than {limit}, the most recent past events fill the rest.
   * Other categories (and "all"): the latest by date, newest first.
   */
  public function latest(string $category, string $langcode, Request $request): CacheableJsonResponse {
    $cache = $this->baseCache()->addCacheContexts(['url.query_args:limit']);
    $items = $this->listItems($category, $langcode, $cache);
    if ($items === NULL) {
      return $this->notFound("Unknown category \"$category\"", $cache);
    }
    $limit = $this->limit($request);

    $hasEvents = $category === self::EVENTS || ($category === self::ALL && in_array(self::EVENTS, array_column(array_column($items, 'category'), 'slug'), TRUE));
    if ($hasEvents) {
      // "upcoming" changes with time, not only when content is saved: recheck every hour,
      // and keep the anonymous page cache (which ignores max-age) from storing it forever
      $cache->mergeCacheMaxAge(3600);
      \Drupal::service('page_cache_kill_switch')->trigger();
    }

    if ($category === self::ALL) {
      // the "All" tab: leave out what section 1 already shows, then take turns across categories,
      // each category in its own order (events: upcoming first)
      $shown = array_column($this->featuredItems(self::ALL, $items, $limit), 'nid');
      $rest = array_values(array_filter($items, fn(array $i) => !in_array($i['nid'], $shown, TRUE)));
      $mixed = $this->mix($rest, fn(array $group) => $group && $group[0]['category']['slug'] === self::EVENTS
        ? $this->sortUpcoming($group)
        : $this->sortNewestFirst($group));
      return (new CacheableJsonResponse(array_slice($mixed, 0, $limit)))->addCacheableDependency($cache);
    }

    $items = $category === self::EVENTS ? $this->sortUpcoming($items) : $this->sortNewestFirst($items);
    return (new CacheableJsonResponse(array_slice($items, 0, $limit)))->addCacheableDependency($cache);
  }

  /** Events: upcoming (soonest first), then past ones (most recent first). */
  protected function sortUpcoming(array $items): array {
    $now = gmdate('Y-m-d\TH:i:s\Z');
    $upcoming = array_values(array_filter($items, fn(array $i) => $i['date'] >= $now));
    $past = array_values(array_filter($items, fn(array $i) => $i['date'] < $now));
    usort($upcoming, fn(array $a, array $b) => [$a['date'], $a['nid']] <=> [$b['date'], $b['nid']]);
    usort($past, [static::class, 'newestFirst']);
    return array_merge($upcoming, $past);
  }

  protected function sortNewestFirst(array $items): array {
    usort($items, [static::class, 'newestFirst']);
    return $items;
  }

  /**
   * Mixes posts of different categories: each category is put in order with $sort, then they
   * take turns (1st of each category, then 2nd of each, …). Categories go in the order of their
   * newest post, so the "All" tab never shows one category only while others have posts.
   */
  protected function mix(array $items, callable $sort): array {
    $groups = [];
    foreach ($items as $item) {
      $groups[$item['category']['slug']][] = $item;
    }
    $groups = array_map($sort, $groups);
    uasort($groups, fn(array $a, array $b) => strcmp($b[0]['published'], $a[0]['published']));

    $out = [];
    for ($round = 0; $groups; $round++) {
      foreach ($groups as $slug => $group) {
        if (!isset($group[$round])) {
          unset($groups[$slug]);
          continue;
        }
        $out[] = $group[$round];
      }
    }
    return $out;
  }

  /**
   * All posts of a category (or of every tab category for "all") in one language, unsorted.
   * NULL when the category doesn't exist.
   */
  protected function listItems(string $category, string $langcode, CacheableMetadata $cache): ?array {
    if ($category === self::ALL) {
      $terms = array_filter(
        $this->allCategories($cache),
        fn(TermInterface $t) => !in_array(Slug::make((string) $t->getUntranslated()->label()), self::NOT_IN_ALL, TRUE)
      );
    }
    else {
      $term = $this->findCategory($category, $cache);
      if (!$term) {
        return NULL;
      }
      $terms = [$term];
    }

    // a post tagged with several categories appears once in "all": under its first category
    // (the first term in its Taxonomy field), or the first one met if that one isn't listed
    $termIds = array_map(fn(TermInterface $t) => (int) $t->id(), $terms);
    $seen = [];

    $items = [];
    foreach ($terms as $term) {
      foreach ($this->postsWithSlugs($term, $cache) as $slug => $node) {
        if (!$node->hasTranslation($langcode)) {
          continue;
        }
        if ($category === self::ALL) {
          $primary = (int) $node->get('field_taxonomy')->target_id;
          if (isset($seen[$node->id()]) || ($primary !== (int) $term->id() && in_array($primary, $termIds, TRUE))) {
            continue;
          }
          $seen[$node->id()] = TRUE;
        }
        $node = $node->getTranslation($langcode);
        if ($node->isPublished() && $node->access('view')) {
          $items[] = $this->serialize($node, $slug, $term, $langcode, FALSE, $cache);
        }
      }
    }
    return $items;
  }

  protected static function newestFirst(array $a, array $b): int {
    return [$b['date'], $b['nid']] <=> [$a['date'], $a['nid']];
  }

  protected function limit(Request $request): int {
    return max(1, min(50, (int) ($request->query->get('limit') ?: 3)));
  }

  /* ---------------------------------------------------------------- */

  protected function baseCache(): CacheableMetadata {
    return (new CacheableMetadata())
      ->addCacheTags(['node_list', 'node_list:' . self::BUNDLE, 'taxonomy_term_list', 'media_list'])
      ->addCacheContexts(['url.path', 'user.permissions']);
  }

  protected function notFound(string $message, CacheableMetadata $cache): CacheableJsonResponse {
    return (new CacheableJsonResponse(['message' => $message], 404))->addCacheableDependency($cache);
  }

  /** The Media Center term whose (original) name makes this slug. */
  protected function findCategory(string $category, CacheableMetadata $cache): ?TermInterface {
    foreach ($this->allCategories($cache) as $term) {
      if (Slug::make((string) $term->getUntranslated()->label()) === $category) {
        return $term;
      }
    }
    return NULL;
  }

  /** @return \Drupal\taxonomy\TermInterface[] */
  protected function allCategories(CacheableMetadata $cache): array {
    $terms = $this->entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => self::VOCABULARY]);
    $terms = array_filter($terms, fn($t) => $t instanceof TermInterface);
    foreach ($terms as $term) {
      $cache->addCacheableDependency($term);
    }
    return $terms;
  }

  /**
   * Published posts of a category keyed by slug. Two posts with the same title get
   * "-2", "-3"… (in node id order) so every slug stays unique and stable.
   *
   * @return \Drupal\node\NodeInterface[]
   */
  protected function postsWithSlugs(TermInterface $term, CacheableMetadata $cache): array {
    $storage = $this->entityTypeManager()->getStorage('node');
    $nids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', self::BUNDLE)
      ->condition('field_taxonomy', $term->id())
      ->condition('status', 1)
      ->sort('nid', 'ASC')
      ->execute();

    $bySlug = [];
    foreach ($storage->loadMultiple($nids) as $node) {
      if (!$node instanceof NodeInterface) {
        continue;
      }
      $cache->addCacheableDependency($node);
      $base = Slug::forNode($node);
      $slug = $base;
      for ($n = 2; isset($bySlug[$slug]); $n++) {
        $slug = "$base-$n";
      }
      $bySlug[$slug] = $node;
    }
    return $bySlug;
  }

  protected function serialize(NodeInterface $node, string $slug, TermInterface $term, string $langcode, bool $full, CacheableMetadata $cache): array {
    $category = Slug::make((string) $term->getUntranslated()->label());
    $termName = $term->hasTranslation($langcode) ? $term->getTranslation($langcode)->label() : $term->label();
    // Events → the event's "Date" field; other categories → publishing date (Authored on)
    $eventDate = $node->hasField('field_date') && !$node->get('field_date')->isEmpty() ? (string) $node->get('field_date')->value : '';
    $eventDate = $eventDate !== '' && strlen($eventDate) > 10 && !str_ends_with($eventDate, 'Z') ? $eventDate . 'Z' : $eventDate;
    $published = gmdate('Y-m-d\TH:i:s\Z', (int) $node->getCreatedTime());
    $date = ($category === self::EVENTS && $eventDate !== '') ? $eventDate : $published;

    $data = [
      'nid' => (int) $node->id(),
      'slug' => $slug,
      'href' => "/media-center/$category/$slug",
      'category' => ['tid' => (int) $term->id(), 'slug' => $category, 'name' => $termName],
      'title' => $node->label(),
      'main_title' => Slug::fieldText($node, 'field_main_title') ?: $node->label(),
      'excerpt' => Slug::fieldText($node, 'field_excerpt'),
      // the date the post is sorted and shown by (UTC ISO)
      'date' => $date,
      'event_date' => $eventDate,
      'published' => $published,
      'featured' => $node->hasField('field_featured') && (bool) $node->get('field_featured')->value,
      'weight' => (int) ($node->hasField('field_weigth') ? $node->get('field_weigth')->value : 0),
      'image' => $this->mediaImage($node, 'field_image', $langcode, $cache)[0] ?? NULL,
    ];
    if ($full) {
      $data['content'] = $this->html($node, 'field_content');
      $data['gallery'] = $this->mediaImage($node, 'field_gallery', $langcode, $cache);
    }
    return $data;
  }

  protected function html(ContentEntityInterface $entity, string $field): string {
    if (!$entity->hasField($field) || $entity->get($field)->isEmpty()) {
      return '';
    }
    $item = $entity->get($field)->first();
    return trim((string) ($item->processed ?? $item->value ?? ''));
  }

  /** Image(s) of a media reference field: [{ mid, image, alt }]. */
  protected function mediaImage(NodeInterface $node, string $field, string $langcode, CacheableMetadata $cache): array {
    if (!$node->hasField($field)) {
      return [];
    }
    $out = [];
    foreach ($node->get($field) as $item) {
      $media = $item->entity;
      if (!$media instanceof MediaInterface) {
        continue;
      }
      if ($media->hasTranslation($langcode)) {
        $media = $media->getTranslation($langcode);
      }
      if (!$media->access('view')) {
        continue;
      }
      $cache->addCacheableDependency($media);
      $source = $media->getSource()->getConfiguration()['source_field'] ?? 'field_media_image';
      $file = $media->hasField($source) ? $media->get($source)->entity : NULL;
      if (!$file) {
        continue;
      }
      $cache->addCacheableDependency($file);
      $out[] = [
        'mid' => (int) $media->id(),
        'image' => $this->fileUrlGenerator->generateString($file->getFileUri()),
        'alt' => (string) ($media->get($source)->alt ?: $media->label()),
      ];
    }
    return $out;
  }

}
