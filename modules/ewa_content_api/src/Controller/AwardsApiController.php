<?php

namespace Drupal\ewa_content_api\Controller;

/**
 * Awards (content type "awards") for the Next.js Awards page, by language.
 *
 * GET /api/awards/{langcode}           every published award, by its date field, newest first
 * GET /api/awards/{langcode}?limit=10  at most 10
 *
 * Same output as the careers API (see CareersApiController): nid, slug, title, main_title,
 * lang, published, date (the award's first date field, else the publishing date), image,
 * excerpt, content (HTML) and fields (every field_* as plain values).
 */
class AwardsApiController extends CareersApiController {

  protected const BUNDLE = 'awards';
  protected const PATH = '/awards/';
  protected const SORT_BY = 'date';

}
