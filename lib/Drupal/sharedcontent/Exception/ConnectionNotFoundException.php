<?php
/**
 * @file
 * Contains \Drupal\sharedcontent\Exception\ConnectionNotFoundException.
 */

namespace Drupal\sharedcontent\Exception;

/**
 * Connection not found.
 *
 * To push an assignment to a remote system using a connection name for
 * which no connection is configured.
 *
 * To prevent this exception to occur again, either remove the connection
 * name from all index records that does contain them or create a valid
 * connection using this connection mane.
 */
class ConnectionNotFoundException extends \Exception {

  /**
   * Constructor.
   *
   * @param string $connection_name
   *   The name of the connection the exception occurred.
   * @param Exception $previous
   *   (optional) The previous exception used for the exception chaining.
   */
  public function __construct($connection_name, Exception $previous = NULL) {
    $message = format_string('Could not find connection with name !name.', array(
      '!name' => $connection_name,
    ));
    parent::__construct($message, 0, $previous);
  }
}
