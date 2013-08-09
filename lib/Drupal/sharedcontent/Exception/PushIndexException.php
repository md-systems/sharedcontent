<?php
/**
 * @file
 * Contains \Drupal\sharedcontent\Exception\PushIndexException.
 */

namespace Drupal\sharedcontent\Exception;

use Drupal\sharedcontent\Plugin\Core\Entity\Index;

/**
 * Push index exception
 *
 * This exception is thrown on errors while pushing an index record to
 * a remote system. Refer to the encapsulated exception for further
 * details.
 *
 * @todo Provide a mechanism to push the index record and its associated
 *       assignments manually.
 */
class PushIndexException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $connection_name
   *   The name of the connection the exception occurred.
   * @param \Drupal\sharedcontent\Plugin\Core\Entity\Index $index
   *   The index record that was expected to be pushed.
   * @param Exception $previous
   *   (optional) The previous exception used for the exception chaining.
   */
  public function __construct($connection_name, Index $index, Exception $previous = NULL) {
    $message = format_string('Could not push index !uuid to connection !name.', array(
      '!uuid' => isset($index->uuid) ? $index->uuid : NULL,
      '!name' => $connection_name,
    ));
    parent::__construct($message, 0, $previous);
  }
}
