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
}
