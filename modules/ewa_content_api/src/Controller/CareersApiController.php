<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\Core\Url;
use Drupal\ewa_content_api\Slug;
use Drupal\file\FileInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Careers (content type "careers") for the Join page careers carousel.
 *
 * GET /api/careers/{langcode}            every published career, newest first
 * GET /api/careers/{langcode}?limit=5    at most 5
 * GET /api/career/{slug}/{langcode}      one career (404 JSON when not found) — for /career/[slug]
 *
 * Every item has:
 *   nid, slug, href ("/career/{slug}"), title, main_title,
 *   published (Authored on, UTC ISO), lang (language actually returned)
 *   date      the first date field of the content type (e.g. a deadline), else published
 *   location  the field whose machine name contains "location" / "city" / "branch" / "place"
 *   image     the first image / media image field: { image, alt }
 *   link      the first Link field (e.g. an "Apply" URL), else ""
 *   excerpt   field_excerpt / field_sub_title / field_summary when there is one
 *   content   field_content / field_description / body (HTML) — else the first long text field
 *   fields    every field_* of the node as plain values — handy to check what the site receives
 *
 * {slug} is made from the career's original (English) Main Title, like Media Center posts
 * (a "field_slug" value wins when the content type has one — filled in on save).
 * The fields are found by type and name, so the API keeps working when fields are renamed
 * or added. A career without a translation in {langcode} is returned in its original language.
 */
class CareersApiController extends ControllerBase {

  protected const BUNDLE = 'careers';
  /** URL of an item's inner page on the Next.js site, before the slug. */
  protected const PATH = '/career/';
  /** Item value the list is sorted by, newest first: 'published' or 'date'. */
  protected const SORT_BY = 'published';
  protected const DATE_TYPES = ['datetime', 'daterange', 'timestamp', 'smartdate'];
  protected const LOCATION_HINTS = ['location', 'city', 'branch', 'place', 'region'];
  protected const EXCERPT_FIELDS = ['field_excerpt', 'field_sub_title', 'field_subtitle', 'field_summary'];
  protected const CONTENT_FIELDS = ['field_content', 'field_description', 'field_body'];
  protected const LONG_TEXT = ['text_long', 'text_with_summary'];

  public function __construct(protected FileUrlGeneratorInterface $fileUrlGenerator) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('file_url_generator'));
  }

  public function list(string $langcode, Request $request): CacheableJsonResponse {
    $cache = $this->baseCache()->addCacheContexts(['url.query_args:limit']);
    $limit = (int) $request->query->get('limit', 0);

    $items = [];
    foreach ($this->careersBySlug($cache) as $slug => $node) {
      $node = $this->inLanguage($node, $langcode);
      if (!$node) {
        continue;
      }
      $items[] = $this->serialize($node, $slug, $langcode, $cache);
    }
    // newest first (careers: by publishing date; subclasses can sort by another value)
    $key = static::SORT_BY;
    usort($items, fn(array $a, array $b) => strcmp((string) $b[$key], (string) $a[$key]) ?: $b['nid'] <=> $a['nid']);
    if ($limit > 0) {
      $items = array_slice($items, 0, $limit);
    }

    return (new CacheableJsonResponse($items))->addCacheableDependency($cache);
  }

  /** One career by slug. */
  public function career(string $slug, string $langcode): CacheableJsonResponse {
    $cache = $this->baseCache();
    $node = $this->careersBySlug($cache)[$slug] ?? NULL;
    $node = $node ? $this->inLanguage($node, $langcode) : NULL;
    if (!$node) {
      return (new CacheableJsonResponse(['message' => "No " . static::BUNDLE . " item \"$slug\" ($langcode)"], 404))->addCacheableDependency($cache);
    }
    return (new CacheableJsonResponse($this->serialize($node, $slug, $langcode, $cache)))->addCacheableDependency($cache);
  }

  protected function baseCache(): CacheableMetadata {
    return (new CacheableMetadata())
      ->addCacheTags(['node_list', 'node_list:' . static::BUNDLE, 'taxonomy_term_list', 'media_list'])
      ->addCacheContexts(['url.path', 'user.permissions']);
  }

  /** The career in this language (original language when untranslated), or NULL when hidden. */
  protected function inLanguage(NodeInterface $node, string $langcode): ?NodeInterface {
    if ($node->hasTranslation($langcode)) {
      $node = $node->getTranslation($langcode);
    }
    return $node->isPublished() && $node->access('view') ? $node : NULL;
  }

  /**
   * Published careers keyed by slug. Two careers with the same title get "-2", "-3"…
   * (in node id order) so every slug stays unique and stable.
   *
   * @return \Drupal\node\NodeInterface[]
   */
  protected function careersBySlug(CacheableMetadata $cache): array {
    $storage = $this->entityTypeManager()->getStorage('node');
    $nids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', static::BUNDLE)
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

  protected function serialize(NodeInterface $node, string $slug, string $langcode, CacheableMetadata $cache): array {
    $published = gmdate('Y-m-d\TH:i:s\Z', (int) $node->getCreatedTime());
    $fields = [];
    $date = $location = $link = $excerpt = $content = $longText = '';
    $image = NULL;

    foreach ($node->getFieldDefinitions() as $name => $definition) {
      if (!str_starts_with($name, 'field_')) {
        continue;
      }
      $list = $node->get($name);
      $type = $definition->getType();
      $value = $this->plain($list, $type, $langcode, $cache);
      $fields[$name] = $value;
      if ($list->isEmpty()) {
        continue;
      }

      if ($date === '' && in_array($type, self::DATE_TYPES, TRUE)) {
        $date = is_array($value) ? (string) ($value[0] ?? '') : (string) $value;
      }
      if ($location === '' && $this->nameHas($name, self::LOCATION_HINTS)) {
        $location = is_array($value) ? implode(', ', array_map('strval', $value)) : (string) $value;
      }
      if ($link === '' && $type === 'link') {
        $link = is_array($value) ? (string) ($value[0]['url'] ?? '') : '';
      }
      if ($excerpt === '' && in_array($name, self::EXCERPT_FIELDS, TRUE)) {
        $excerpt = trim(strip_tags(is_array($value) ? implode(' ', array_map('strval', $value)) : (string) $value));
      }
      if ($content === '' && in_array($name, self::CONTENT_FIELDS, TRUE)) {
        $content = is_array($value) ? implode("\n", array_map('strval', $value)) : (string) $value;
      }
      if ($longText === '' && in_array($type, self::LONG_TEXT, TRUE) && !in_array($name, self::EXCERPT_FIELDS, TRUE)) {
        $longText = is_array($value) ? implode("\n", array_map('strval', $value)) : (string) $value;
      }
      if ($image === NULL && ($type === 'image' || ($type === 'entity_reference' && $definition->getSetting('target_type') === 'media'))) {
        $images = $this->images($list, $langcode, $cache);
        $image = $images[0] ?? NULL;
      }
    }

    // "body" has no field_ prefix, so it is not in the loop above
    if ($content === '' && $node->hasField('body') && !$node->get('body')->isEmpty()) {
      $content = (string) $this->plain($node->get('body'), 'text_with_summary', $langcode, $cache);
    }

    return [
      'nid' => (int) $node->id(),
      'slug' => $slug,
      'href' => static::PATH . $slug,
      'title' => $node->label(),
      'main_title' => Slug::fieldText($node, 'field_main_title') ?: $node->label(),
      'lang' => $node->language()->getId(),
      'published' => $published,
      'date' => $date !== '' ? $date : $published,
      'location' => $location,
      'image' => $image,
      'link' => $link,
      'excerpt' => $excerpt,
      'content' => $content !== '' ? $content : $longText,
      'fields' => $fields,
    ];
  }

  protected function nameHas(string $name, array $hints): bool {
    foreach ($hints as $hint) {
      if (str_contains($name, $hint)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /** A field as plain JSON: one value, or a list when the field has several. */
  protected function plain(FieldItemListInterface $list, string $type, string $langcode, CacheableMetadata $cache): mixed {
    $values = [];
    foreach ($list as $item) {
      switch ($type) {
        case 'image':
        case 'file':
          $file = $item->entity;
          $values[] = $file instanceof FileInterface ? $this->fileUrlGenerator->generateString($file->getFileUri()) : NULL;
          break;

        case 'entity_reference':
        case 'entity_reference_revisions':
          $entity = $item->entity;
          if ($entity instanceof MediaInterface) {
            $values[] = $this->images($list, $langcode, $cache)[0]['image'] ?? $entity->label();
            break 2;
          }
          if ($entity instanceof ContentEntityInterface) {
            $cache->addCacheableDependency($entity);
            if ($entity->hasTranslation($langcode)) {
              $entity = $entity->getTranslation($langcode);
            }
            $values[] = (string) $entity->label();
          }
          break;

        case 'link':
          $url = '';
          try {
            $url = Url::fromUri((string) $item->uri)->toString();
          }
          catch (\Throwable $e) {
            $url = (string) $item->uri;
          }
          $values[] = ['url' => $url, 'title' => (string) ($item->title ?? '')];
          break;

        case 'datetime':
        case 'daterange':
        case 'smartdate':
          $v = (string) $item->value;
          $values[] = strlen($v) > 10 && !str_ends_with($v, 'Z') ? $v . 'Z' : $v;
          break;

        case 'timestamp':
          $values[] = gmdate('Y-m-d\TH:i:s\Z', (int) $item->value);
          break;

        case 'list_string':
        case 'list_integer':
        case 'list_float':
          $allowed = $list->getFieldDefinition()->getSetting('allowed_values') ?? [];
          $values[] = (string) ($allowed[$item->value] ?? $item->value);
          break;

        case 'boolean':
          $values[] = (bool) $item->value;
          break;

        case 'text':
        case 'text_long':
        case 'text_with_summary':
          $values[] = trim((string) ($item->processed ?? $item->value ?? ''));
          break;

        default:
          $values[] = $item->value ?? NULL;
      }
    }
    if (!$values) {
      return NULL;
    }
    return count($values) === 1 ? $values[0] : $values;
  }

  /** Images of an image field or a media reference field: [{ image, alt }]. */
  protected function images(FieldItemListInterface $list, string $langcode, CacheableMetadata $cache): array {
    $out = [];
    foreach ($list as $item) {
      $entity = $item->entity;
      if ($entity instanceof FileInterface) {
        $cache->addCacheableDependency($entity);
        $out[] = ['image' => $this->fileUrlGenerator->generateString($entity->getFileUri()), 'alt' => (string) ($item->alt ?? '')];
        continue;
      }
      if (!$entity instanceof MediaInterface) {
        continue;
      }
      if ($entity->hasTranslation($langcode)) {
        $entity = $entity->getTranslation($langcode);
      }
      if (!$entity->access('view')) {
        continue;
      }
      $cache->addCacheableDependency($entity);
      $source = $entity->getSource()->getConfiguration()['source_field'] ?? 'field_media_image';
      $file = $entity->hasField($source) ? $entity->get($source)->entity : NULL;
      if (!$file instanceof FileInterface) {
        continue;
      }
      $cache->addCacheableDependency($file);
      $out[] = [
        'image' => $this->fileUrlGenerator->generateString($file->getFileUri()),
        'alt' => (string) ($entity->get($source)->alt ?: $entity->label()),
      ];
    }
    return $out;
  }

}
