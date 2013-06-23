<?php
/**
 * @file
 * Contains SharedContentRichMedia.
 */

/**
 * Interface for Rich Media Plugin classes.
 */
interface SharedContentRichMedia {
  /**
   * Get JS files to be added when displaying an embedded Shared Content.
   *
   * @param SharedContentIndex $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS files.
   */
  public function getJsFiles(SharedContentIndex $index, $view_mode, $langcode);

  /**
   * Get JS settings to be added when displaying an embedded Shared Content.
   *
   * @param SharedContentIndex $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS settings.
   */
  public function getJsSettings(SharedContentIndex $index, $view_mode, $langcode);

  /**
   * Get inline JS.
   *
   * @param SharedContentIndex $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS settings.
   */
  public function getInlineJs(SharedContentIndex $index, $view_mode, $langcode);

  /**
   * Get CSS files to be added when displaying an embedded Shared Content.
   *
   * @param SharedContentIndex $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of CSS files.
   */
  public function getCssFiles(SharedContentIndex $index, $view_mode, $langcode);
}
