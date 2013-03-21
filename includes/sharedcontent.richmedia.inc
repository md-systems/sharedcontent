<?php

/**
 * @file
 * RichMedia plugin interface.
 */
interface SharedContentRichMedia {
  /**
   * Get JS files to be added when displaying an embedded Shared Content.
   *
   * @param $uuid
   *   The uuid of the index record.
   * @param $view_mode
   *   The view mode the entity was rendered in.
   * @param $langcode
   *   The language the enetity was rendered for.
   * @return
   *  Array of JS files.
   */
  function getJsFiles($uuid, $view_mode, $langcode);

  /**
   * Get JS settings to be added when displaying an embedded Shared Content.
   *
   * @param $uuid
   *   The uuid of the index record.
   * @param $view_mode
   *   The view mode the entity was rendered in.
   * @param $langcode
   *   The language the enetity was rendered for.
   * @return
   *  Array of JS settings.
   */
  function getJsSettings($uuid, $view_mode, $langcode);

  /**
   * Get inline JS.
   *
   * @param $uuid
   *   The uuid of the index record.
   * @param $view_mode
   *   The view mode the entity was rendered in.
   * @param $langcode
   *   The language the enetity was rendered for.
   * @return
   *  Array of JS settings.
   */
  function getInlineJs($uuid, $view_mode, $langcode);

  /**
   * Get CSS files to be added when displaying an embedded Shared Content.
   *
   * @param $uuid
   *   The uuid of the index record.
   * @param $view_mode
   *   The view mode the entity was rendered in.
   * @param $langcode
   *   The language the enetity was rendered for.
   * @return
   *  Array of CSS files.
   */
  function getCssFiles($uuid, $view_mode, $langcode);
}

class SharedContentRichMediaBase implements SharedContentRichMedia {

  /**
   * Internal cache for loaded indexes.
   *
   * @var array
   */
  protected $indexes = array();

  /**
   * Implements getJsFiles().
   */
  function getJsFiles($uuid, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getJsSettings().
   */
  function getJsSettings($uuid, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getInlineJs().
   */
  function getInlineJs($uuid, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getCssFiles().
   */
  function getCssFiles($uuid, $view_mode, $langcode) {
    return array();
  }

  /**
   * Returns an array of loaded indexes, by default including parents.
   *
   * @todo: Pass in the $index and change this to getParents().
   *
   * @param string $uuid
   *   UUID of the shared content index.
   * @param bool $parents
   *   (optional) FALSE if no parents should be loaded, defaults to TRUE.
   *
   * @return array
   *   Array with at least the index entity for the given UUID.
   */
  protected function getIndexes($uuid, $parents = TRUE) {
    if (empty($indexes[$uuid])) {
      $indexes[$uuid][] = sharedcontent_index_load_by_uuid($uuid);
      while ( $parents && !empty($indexes[$uuid][count($indexes[$uuid]) - 1]->parent_uuid)) {
        $indexes[$uuid][] = sharedcontent_index_load_by_uuid($indexes[$uuid][count($indexes) - 1]->parent_uuid);
      }
    }
    return $indexes[$uuid];
  }
}
