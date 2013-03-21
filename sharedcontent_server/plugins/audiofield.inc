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
    if (!$this->showJPlayer($uuid, $view_mode)) {
      return array();
    }
    return array(
      variable_get('audiofield_players_dir', 'sites/all/libraries/player') . "/jplayer/jplayer.css",
    );
  }

  /**
   * Override getJsFiles().
   */
  function getJsFiles($uuid, $view_mode, $langcode) {
    if (!$this->showJPlayer($uuid, $view_mode)) {
      return array();
    }
    return array(
      variable_get('audiofield_players_dir', 'sites/all/libraries/player') . "/jplayer/jquery.jplayer.min.js",
    );
  }

  /**
   * Test if a JW Player will be rendered
   *
   * @param $uuid
   *   The uuid of the index record.
   * @param $view_mode
   *   The view mode.
   * @return bool
   *   TRUE if a Carousel Gallery will be rendered, FALSE otherwise.
   */
  function showJPlayer($uuid, $view_mode) {
    if (module_exists('sharedcontent_server')) {
      foreach ($this->getIndexes($uuid) as $index) {
        $fields = field_info_instances($index->entity_type, $index->entity_bundle);
        foreach ($fields as $key => $field) {
          if (isset($field['display'][$view_mode]['module'])
            && $field['display'][$view_mode]['module'] == 'audiofield') {
            return TRUE;
          }
        }
      }
    }
    return FALSE;
  }
}
