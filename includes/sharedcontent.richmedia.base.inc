<?php
/**
 * @file
 * Contains SharedContentRichMediaBase.
 */

/**
 * Base implementation of SharedContentRichMedia.
 */
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
  public function getJsFiles(SharedContentIndex $index, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getJsSettings().
   */
  public function getJsSettings(SharedContentIndex $index, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getInlineJs().
   */
  public function getInlineJs(SharedContentIndex $index, $view_mode, $langcode) {
    return array();
  }

  /**
   * Implements getCssFiles().
   */
  public function getCssFiles(SharedContentIndex $index, $view_mode, $langcode) {
    return array();
  }

  /**
   * Returns an array of loaded indexes, by default including parents.
   *
   * @todo: Pass in the $index and change this to getParents().
   *
   * @param SharedContentIndex $index
   *   The index record.
   * @param bool $parents
   *   (optional) FALSE if no parents should be loaded, defaults to TRUE.
   *
   * @return array
   *   Array with at least the index entity for the given UUID.
   */
  protected function getIndexes(SharedContentIndex $index, $parents = TRUE) {
    if (empty($indexes[$index->uuid])) {
      $indexes[$index->uuid][] = $index;
      while ($parents && !empty($indexes[$index->uuid][count($indexes[$index->uuid]) - 1]->parent_uuid)) {
        $indexes[$index->uuid][] = sharedcontent_index_load_by_uuid($indexes[$index->uuid][count($indexes) - 1]->parent_uuid);
      }
    }
    return $indexes[$index->uuid];
  }
}
