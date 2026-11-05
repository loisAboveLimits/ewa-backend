<?php

namespace Drupal\ewa_content_api\Controller;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\node\NodeInterface;

/**
 * Team members (content type "team") for the Next.js /team/[slug] pages, by language.
 *
 * GET /api/team/{langcode}              every published team member
 * GET /api/team/{slug}/{langcode}       one team member (404 JSON when not found)
 *
 * {slug} is made from the member's original (English) name (node title), e.g.
 * "Thamer Al-Sharhan" → thamer-al-sharhan. Same item shape as the careers API plus
 * "position" (field_position).
 */
class TeamApiController extends CareersApiController {

  protected const BUNDLE = 'team';
  protected const PATH = '/team/';
  protected const SORT_BY = 'published';

  protected function serialize(NodeInterface $node, string $slug, string $langcode, CacheableMetadata $cache): array {
    $item = parent::serialize($node, $slug, $langcode, $cache);
    $position = $item['fields']['field_position'] ?? '';
    $item['position'] = is_array($position) ? implode(', ', array_map('strval', $position)) : trim(strip_tags((string) $position));
    return $item;
  }

}
