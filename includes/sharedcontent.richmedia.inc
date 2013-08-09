<?php
/**
 * @file
 * Contains SharedContentRichMedia.
 */

use Drupal\sharedcontent\Plugin\Core\Entity\Index;
/**
 * Interface for Rich Media Plugin classes.
 */
interface SharedContentRichMedia {
  /**
   * Get JS files to be added when displaying an embedded Shared Content.
   *
   * @param \Drupal\sharedcontent\Plugin\Core\Entity\Index $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS files.
   */
  public function getJsFiles(Index $index, $view_mode, $langcode);

  /**
   * Get JS settings to be added when displaying an embedded Shared Content.
   *
   * @param \Drupal\sharedcontent\Plugin\Core\Entity\Index $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS settings.
   */
  public function getJsSettings(Index $index, $view_mode, $langcode);

  /**
   * Get inline JS.
   *
   * @param \Drupal\sharedcontent\Plugin\Core\Entity\Index $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of JS settings.
   */
  public function getInlineJs(Index $index, $view_mode, $langcode);

  /**
   * Get CSS files to be added when displaying an embedded Shared Content.
   *
   * @param \Drupal\sharedcontent\Plugin\Core\Entity\Index $index
   *   The index record.
   * @param string $view_mode
   *   The view mode the entity was rendered in.
   * @param string $langcode
   *   The language the enetity was rendered for.
   *
   * @return array
   *   Array of CSS files.
   */
  public function getCssFiles(Index $index, $view_mode, $langcode);
}
