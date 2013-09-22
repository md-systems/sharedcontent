<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\IndexInterface.
 */

namespace Drupal\sharedcontent;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityInterface;

interface IndexInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Status value for the sharedcontent_index entity.
   *
   * States that the indexed entity was deleted or can not be accessed
   * for other reasons. Records with this status can not be linked any
   * more. Already linked records will not be displayed any longer.
   */
  const STATUS_NOT_REACHABLE = 0;

  /**
   * Status value for the sharedcontent_index entity.
   *
   * States that the indexed entity is existing and visible and therefore this
   * index record can be linked and displayed.
   */
  const STATUS_VISIBLE = 1;

  /**
   * Status value for the sharedcontent_index entity.
   *
   * States that the indexed entity is existing but not visible and therefore
   * this index record can be linked but not displayed.
   */
  const STATUS_LINKABLE = 2;

  /**
   * The index record is created and have a corresponding content locally.
   */
  const BUNDLE_LOCAL = 'local';

  /**
   * The index record was received during a synchronization process.
   */
  const BUNDLE_REMOTE = 'remote';

  /**
   * The index record was received by a push from a client.
   */
  const BUNDLE_SHADOW = 'shadow';

  /**
   * Accessibility status (not accessible).
   */
  const ACCESSIBILITY_NONE = 0;

  /**
   * Accessibility status (public accessible).
   */
  const ACCESSIBILITY_PUBLIC = 1;

  /**
   * Accessibility status (restricted).
   */
  const ACCESSIBILITY_RESTRICTED = 2;

  /**
   * A chapter record may be created without parent. The string defined
   * by this constant is used instead of the parent uuid so we are able
   * to track those orphaned records.
   */
  const ORPHAN = 'orphaned chapter';

  /**
   * Checks if an entity is indexable.
   *
   * Whether or not an entity can be indexed is retrieved from configuration and
   * is based on the entity type and bundle.
   *
   * @param EntityInterface $entity
   *   The entity to check.
   *
   * @return bool
   *   TRUE if the entity is indexable, FALSE otherwise.
   */
  public static function isIndexable(EntityInterface $entity);

  /**
   * Configure indexability of an entity.
   *
   * Set the indexability for a entity bundle based on an entity.
   *
   * @param EntityInterface $entity
   *   The entity which bundle ist to be configured.
   * @param bool $value
   *   Whether or not the entity bundle should be indexable.
   */
  public static function setIndexableByEntity(EntityInterface $entity, $value);

  /**
   * Configure indexability of an entity.
   *
   * Set the indexability for a entity bundle.
   *
   * @param string $entity_type
   *   The entity type to be configured.
   * @param string $bundle
   *   The entity bundle to be configured.
   * @param bool $value
   *   Whether or not the entity bundle should be indexable.
   */
  public static function setIndexable($entity_type, $bundle, $value);

  /**
   * Index an entity.
   *
   * Creates or updates an index record for the given entity.
   *
   * @param EntityInterface $entity
   *   The entity to be indexed.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The created or updated index record.
   *
   * @throws \Drupal\sharedcontent\Exception\IndexingException
   */
  public static function indexEntity(EntityInterface $entity);

  /**
   * Update indexed data.
   *
   * @param EntityInterface $entity
   *   The entity to take the updated data from.
   *
   * @throws \Drupal\sharedcontent\Exception\IndexingException
   */
  public function updateData(EntityInterface $entity);

  /**
   * Returns the connection name.
   *
   * @return int
   *   Name of the connection the index record was retrieved from.
   */
  public function getConnectionName();

  /**
   * Sets the connection name.
   *
   * @param string $connection_name
   *   The name of the new connection for this index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setConnectionName($connection_name);

  /**
   * Sets the bundle.
   *
   * @param string $origin
   *   The new origin of this index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setBundle($origin);

  /**
   * Sets the uuid.
   *
   * @param string $uuid
   *   The uuid of this index record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setUuid($uuid);


  /**
   * Returns the parent uuid.
   *
   * @return string|null
   *   UUID of a possible parent record or null.
   */
  public function getParentUuid();

  /**
   * Sets the parent uuid.
   *
   * @param string $uuid
   *   UUID of the new parent record.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setParentUuid($uuid);

  /**
   * Returns the entity id.
   *
   * @return int
   *   The id of the indexed entity.
   */
  public function getEntityUuid();

  /**
   * Sets the entity id.
   *
   * @param int $id
   *   Id of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setEntityId($id);

  /**
   * Returns the entity type.
   *
   * @return string
   *   The type of the indexed entity.
   */
  public function getEntityType();

  /**
   * Sets the entity type.
   *
   * @param string $type
   *   Type of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setEntityType($type);

  /**
   * Returns the entity bundle.
   *
   * @return string
   *   The type of the indexed bundle.
   */
  public function getEntityBundle();

  /**
   * Sets the entity bundle.
   *
   * @param string $bundle
   *   Bundle of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setEntityBundle($bundle);

  /**
   * Returns the entity title.
   *
   * @return string
   *   The title of the indexed bundle.
   */
  public function getTitle();

  /**
   * Sets the entity title.
   *
   * @param string $title
   *   Title of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setTitle($title);

  /**
   * Returns the entity url.
   *
   * @return string
   *   The url of the indexed bundle.
   */
  public function getUrl();

  /**
   * Sets the entity url.
   *
   * @param string $url
   *   Url of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setUrl($url);

  /**
   * Returns the entity language.
   *
   * @return string
   *   The language of the indexed bundle.
   */
  public function getLangcode();

  /**
   * Sets the entity language.
   *
   * @param string $langcode
   *   Language of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setLangcode($langcode);

  /**
   * Returns the translation set.
   *
   * @return int
   *   Id of the translation set.
   */
  public function getTranslationSetId();

  /**
   * Sets the translation set.
   *
   * @param int $id
   *   Id of the translation set.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setTranslationSetId($id);

  /**
   * Returns the keywords.
   *
   * @return string
   *   Keywords describing the indexed entity as a space delimited bag of words.
   */
  public function getKeywords();

  /**
   * Sets the keywords.
   *
   * @param string $keywords
   *   Keywords for the indexed entity as a space delimited bag of words.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setKeywords($keywords);

  /**
   * Returns the tags.
   *
   * @return string
   *   Tags describing the indexed entity as a space delimited bag of words.
   */
  public function getTags();

  /**
   * Sets the tags.
   *
   * @param string $tags
   *   Tags for the indexed entity as a space delimited bag of words.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setTags($tags);

  /**
   * Checks if referenced entity is linkable.
   *
   * @return bool
   *   TRUE if the record can be linked, FALSE otherwise.
   *
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_LINKABLE
   */
  public function isLinkable();

  /**
   * Checks if referenced entity is visible.
   *
   * @return bool
   *   TRUE if the indexed record is publicly visible, FALSE otherwise.
   *
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_VISIBLE
   */
  public function isVisible();

  /**
   * Checks if referenced entity is reachable.
   *
   * @return bool
   *   TRUE if the indexed record is reachable, FALSE otherwise.
   *
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_NOT_REACHABLE
   */
  public function isReachable();

  /**
   * Returns the status.
   *
   * @return int
   *   Number indicating if the record can be linked or displayed or if the
   *  indexed entity was deleted.
   *
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_VISIBLE
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_LINKABLE
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_NOT_REACHABLE
   */
  public function getStatus();

  /**
   * Sets the status.
   *
   * @param int $status
   *   Status of the indexed entity.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   *
   * @see \Drupal\sharedcontent\IndexInterface::getStatus()
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_VISIBLE
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_LINKABLE
   * @see \Drupal\sharedcontent\IndexInterface::STATUS_NOT_REACHABLE
   */
  public function setStatus($status);

  /**
   * Returns the entities creation date.
   *
   * @return int
   *   Timestamp of the creation date.
   */
  public function getEntityCreatedTime();

  /**
   * Sets the entities creation date.
   *
   * @param int $created
   *   Timestamp of the creation date.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setEntityCreatedTime($created);

  /**
   * Returns the entities changed date.
   *
   * @return int
   *   Timestamp of the changed date.
   */
  public function getEntityChangedTime();

  /**
   * Sets the entities changed date.
   *
   * @param int $changed
   *   Timestamp of the changed date.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   */
  public function setEntityChangedTime($changed);

  /**
   * Returns the records creation date.
   *
   * @return int
   *   Timestamp of the creation date.
   */
  public function getCreatedTime();

  /**
   * Returns the accessibility.
   *
   * @return int
   *   Number indicating if the record is accessible.
   *   0 - if not accessible,
   *   1 - if public accessible
   *   2 - if restricted.
   */
  public function getAccessibility();

  /**
   * Sets the status.
   *
   * @param int $accessibility
   *   Number indicating if the record is accessible.
   *
   * @return \Drupal\sharedcontent\IndexInterface
   *   The called index entity.
   *
   * @see \Drupal\sharedcontent\IndexInterface::getAccessibility()
   */
  public function setAccessibility($accessibility);
}
