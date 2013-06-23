<?php

/**
 * @file
 * Contains SharedContentBaseController.
 */

/**
 * Base controller for sharedcontent.
 *
 * This controller takes care that all entities have a valid uuid and the
 * the created and changed properties are correctly set.
 */
class SharedContentBaseController extends EntityAPIController {

  /**
   * Override EntityAPIController.
   */
  public function create(array $values = array()) {
    $values += array(
      'uuid' => $this->uuidGenerate(),
      'created' => REQUEST_TIME,
    );
    return parent::create($values);
  }

  /**
   * Override EntityAPIController.
   */
  public function save($entity) {
    // Make sure we have a valid uuid for this entity.
    // For testing purposed we allow invalid uuids.
    if (!$this->uuidIsValid($entity->uuid) && !variable_get('sharedcontent_test', FALSE)) {
      $entity->uuid = $this->uuidGenerate();
    }
    $entity->changed = REQUEST_TIME;

    return parent::save($entity);
  }

  /**
   * Generates a Universally Unique IDentifier (UUID).
   *
   * @see http://api.drupal.org/api/drupal/core%21lib%21Drupal%21Component%21Uuid%21Php.php/function/Php%3A%3Agenerate/8
   *
   * @return string
   *   A 32 byte integer represented as a hex string formatted with 4 hypens.
   */
  public function uuidGenerate() {
    $hex = substr(hash('sha256', drupal_random_bytes(16)), 0, 32);

    // The field names refer to RFC 4122 section 4.1.2.
    $time_low = substr($hex, 0, 8);
    $time_mid = substr($hex, 8, 4);

    $time_hi_and_version = base_convert(substr($hex, 12, 4), 16, 10);
    $time_hi_and_version &= 0x0FFF;
    $time_hi_and_version |= (4 << 12);

    $clock_seq_hi_and_reserved = base_convert(substr($hex, 16, 4), 16, 10);
    $clock_seq_hi_and_reserved &= 0x3F;
    $clock_seq_hi_and_reserved |= 0x80;

    $clock_seq_low = substr($hex, 20, 2);
    $nodes = substr($hex, 20);

    $uuid = sprintf('%s-%s-%04x-%02x%02x-%s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $clock_seq_low, $nodes);

    return $uuid;
  }

  /**
   * Checks that a string appears to be in the format of a UUID.
   *
   * Plugins should not implement validation, since UUIDs should be in a
   * consistent format across all plugins.
   *
   * @see http://api.drupal.org/api/drupal/core%21lib%21Drupal%21Component%21Uuid%21Uuid.php/function/Uuid%3A%3AisValid/8
   *
   * @param string $uuid
   *   The string to test.
   *
   * @return string
   *   TRUE if the string is well formed, FALSE otherwise.
   */
  public function uuidIsValid($uuid) {
    return preg_match("/^[0-9a-f]{8}-([0-9a-f]{4}-){3}[0-9a-f]{12}$/", $uuid);
  }

}
