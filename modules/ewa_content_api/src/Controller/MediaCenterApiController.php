<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\File\FileUrlGeneratorInterface;
use Drupal\media\MediaInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Media Center items of one category, each with its gallery as a nested array.
 *
 * GET /api/media/campus/{langcode}  (category = taxonomy term 12, "Campus & Facilities")
 * GET /api/media/category/{tid}/{langcode}
 *
 * [
 *   {
 *     "nid": 43,
 *     "title": "Ghazi Al-Gosaibi Hall - EN",
 *     "main_title": "Ghazi Al-Gosaibi Hall",
 *     "content": "<p>…</p>",
 *     "weight": 1,
 *     "gallery": [ { "delta": 0, "mid": 12, "image": "/ewa/sites/default/files/…jpg", "alt": "…" }, … ]
 *   },
 *   …
 * ]
 *
 * Sorted by the "Weigth" field (field_weigth) ASC, then node id.
 * Only nodes that exist in {langcode} are returned, in that language.
 */
class MediaCenterApiController extends ControllerBase {

  /** Media Center content type and its fields. */
  protected const BUNDLE = 'media_center';
  protected const FIELD_CATEGORY = 'field_taxonomy';
  protected const FIELD_WEIGHT = 'field_weigth';
  protected const FIELD_MAIN_TITLE = 'field_main_title';
  protected const FIELD_CONTENT = 'field_content';
  protected const FIELD_GALLERY = 'field_gallery';

  public function __construct(protected FileUrlGeneratorInterface $fileUrlGenerator) {}

  public static function create(ContainerInterface $container): static {
    return new static($container->get('file_url_generator'));
  }

  public function category(string $tid, string $langcode): CacheableJsonResponse {
    $cache = (new CacheableMetadata())
      ->addCacheTags(['node_list', 'node_list:' . self::BUNDLE, 'media_list', 'taxonomy_term:' . $tid])
      ->addCacheContexts(['url.path', 'user.permissions']);

    $storage = $this->entityTypeManager()->getStorage('node');
    $nids = $storage->getQuery()
      ->accessCheck(TRUE)
      ->condition('type', self::BUNDLE)
      ->condition(self::FIELD_CATEGORY, (int) $tid)
      ->condition('status', 1, '=', $langcode)
      ->condition('langcode', $langcode)
      ->execute();

    $items = [];
    foreach ($storage->loadMultiple($nids) as $node) {
      if (!$node instanceof NodeInterface || !$node->hasTranslation($langcode)) {
        continue;
      }
      $node = $node->getTranslation($langcode);
      if (!$node->isPublished() || !$node->access('view')) {
        continue;
      }
      $cache->addCacheableDependency($node);

      $items[] = [
        'nid' => (int) $node->id(),
        'title' => $node->label(),
        'main_title' => $this->plainText($node, self::FIELD_MAIN_TITLE),
        'content' => $this->html($node, self::FIELD_CONTENT),
        'weight' => (int) ($node->hasField(self::FIELD_WEIGHT) ? $node->get(self::FIELD_WEIGHT)->value : 0),
        'gallery' => $this->gallery($node, $langcode, $cache),
      ];
    }

    // weight ASC, then node id
    usort($items, fn(array $a, array $b) => [$a['weight'], $a['nid']] <=> [$b['weight'], $b['nid']]);

    return (new CacheableJsonResponse($items))->addCacheableDependency($cache);
  }

  /** Formatted text field → filtered HTML (as the text format allows). */
  protected function html(ContentEntityInterface $entity, string $field): string {
    if (!$entity->hasField($field) || $entity->get($field)->isEmpty()) {
      return '';
    }
    $item = $entity->get($field)->first();
    return trim((string) ($item->processed ?? $item->value ?? ''));
  }

  /** Formatted text field → one line of plain text. */
  protected function plainText(ContentEntityInterface $entity, string $field): string {
    $text = html_entity_decode(strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], ' ', $this->html($entity, $field))), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/u', ' ', $text));
  }

  /** The node's gallery (media references) in field order. */
  protected function gallery(NodeInterface $node, string $langcode, CacheableMetadata $cache): array {
    if (!$node->hasField(self::FIELD_GALLERY)) {
      return [];
    }
    $out = [];
    foreach ($node->get(self::FIELD_GALLERY) as $delta => $item) {
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
      if (!$media->hasField($source) || $media->get($source)->isEmpty()) {
        continue;
      }
      $file = $media->get($source)->entity;
      if (!$file) {
        continue;
      }
      $cache->addCacheableDependency($file);

      $out[] = [
        'delta' => (int) $delta,
        'mid' => (int) $media->id(),
        'image' => $this->fileUrlGenerator->generateString($file->getFileUri()),
        'alt' => (string) ($media->get($source)->alt ?: $media->label()),
      ];
    }
    return $out;
  }

}
