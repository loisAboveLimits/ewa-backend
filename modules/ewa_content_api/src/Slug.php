<?php

namespace Drupal\ewa_content_api;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\node\NodeInterface;

/**
 * URL slugs for Media Center posts and categories.
 *
 * A post's slug comes from the Main Title of its original (English) version, so
 * /media-center/events/renewable-energy-innovation-summit and
 * /ar/media-center/events/renewable-energy-innovation-summit are the same post.
 * If the content type has a "field_slug" text field, a value typed there wins
 * (and it is filled in automatically on save — see ewa_content_api.module).
 */
final class Slug {

  /** "Renewable Energy: 2027 Summit!" → "renewable-energy-2027-summit" */
  public static function make(string $text): string {
    $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = \Drupal::service('transliteration')->transliterate($text, 'en', '');
    $text = mb_strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim((string) $text, '-');
  }

  /** The slug of a post (from field_slug when set, otherwise its original Main Title). */
  public static function forNode(NodeInterface $node): string {
    $original = $node->getUntranslated();
    if ($original->hasField('field_slug') && !$original->get('field_slug')->isEmpty()) {
      $slug = self::make((string) $original->get('field_slug')->value);
      if ($slug !== '') {
        return $slug;
      }
    }
    $title = self::fieldText($original, 'field_main_title') ?: (string) $original->label();
    return self::make($title) ?: 'post-' . $node->id();
  }

  /** Plain text of a (formatted) text field. */
  public static function fieldText(ContentEntityInterface $entity, string $field): string {
    if (!$entity->hasField($field) || $entity->get($field)->isEmpty()) {
      return '';
    }
    $item = $entity->get($field)->first();
    $value = (string) ($item->processed ?? $item->value ?? '');
    $value = str_replace(['<br>', '<br/>', '<br />', '</p>'], ' ', $value);
    $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return trim(preg_replace('/\s+/u', ' ', $value));
  }

}
