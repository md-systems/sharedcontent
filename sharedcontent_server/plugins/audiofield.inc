<?php

/**
 * @file
 * RichMedia plugin for AudioField.
 *
 * See project:
 * drupal.org/project/jplayer
 *
 * @todo Add files only if there is an audio field rendered.
 */
class SharedContentRichMediaAudioField extends SharedContentRichMediaBase {

  /**
   * Override getCssFiles().
   */
  function getCssFiles($uuid, $view_mode, $langcode) {
    return array(
      variable_get('audiofield_players_dir', 'sites/all/libraries/player') . "/jplayer/jplayer.css",
    );
  }

  /**
   * Override getJsFiles().
   */
  function getJsFiles($uuid, $view_mode, $langcode) {
    return array(
      variable_get('audiofield_players_dir', 'sites/all/libraries/player') . "/jplayer/jquery.jplayer.min.js",
    );
  }
}
