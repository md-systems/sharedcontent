<?php

/**
 * @file
 * Hooks provided by the Shared Content module.
 */

/**
 * @addtogroup sharedcontent
 * @{
 */

/**
 * Allow to alter the keywords stored in the sharedcontent_index.
 *
 * @param $keywords
 *   Array of keywords.
 * @param $entity
 *  The entity for which the keywords are built for.
 * @param $entity_type
 *  The type of the entity as string.
 */
function hook_sharedcontent_index_keywords_alter(array &$keywords, $entity, $entity_type) {
  $stop_words = array('a', 'I', 'and', 'the');
  foreach ($keywords as $index => $words) {
    if (in_array($words, $stop_words)) {
      unset($keywords[$index]);
    }
  }
}

/**
 * Allow to alter the tags stored in the sharedcontent_index.
 *
 * @param $tags
 *   Array of tags.
 * @param $entity
 *  The entity for which the tags are built for.
 * @param $entity_type
 *  The type of the entity as string.
 */
function hook_sharedcontent_index_tags_alter(array &$tags, $entity, $entity_type) {
  $stop_words = array('a', 'I', 'and', 'the');
  foreach ($tags as $index => $tag) {
    if (in_array($tag, $stop_words)) {
      unset($tags[$index]);
    }
  }
}

/**
 * @} End of "addtogroup sharedcontent".
 */