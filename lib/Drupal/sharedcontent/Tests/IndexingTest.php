<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\IndexingTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\Core\Language\Language;
use Drupal\sharedcontent\IndexInterface;
use Drupal\sharedcontent\Services\NullIndexing;
use Drupal\simpletest\DrupalUnitTestBase;

class IndexingTest extends DrupalUnitTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array(
    'user',
    'system',
    'entity',
    'field',
    'sharedcontent',
  );

  /**
   * The indexing service.
   *
   * @var \Drupal\sharedcontent\Services\IndexingServiceBase.
   */
  protected $indexing;

  static function getInfo() {
    return array(
      'name' => 'Indexing tests',
      'description' => 'Test the indexing of content.',
      'group' => 'Shared Content',
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installSchema('sharedcontent', array(
      'sharedcontent_index',
      'sharedcontent_assignment',
    ));

    $this->indexing = \Drupal::service('sharedcontent.indexing.default');
  }

  /**
   * Test indexing for nodes.
   */
  public function testIndexingNode() {
    $this->enableModules(array('node'));

    $entity = entity_create('node', array(
      'title' => 'Indexed node',
      'type' => 'indexed',
      'created' => REQUEST_TIME,
      'changed' => REQUEST_TIME,
    ));

    $this->indexing->index($entity);

    $this->assertTrue($this->indexing->exists($entity), 'Found index record for created node.');
    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getConnectionName(), NULL, 'The connection name is empty.');
    $this->assertEqual($index->getChangedTime(), REQUEST_TIME, 'The index record was last changed within this request.');
    $this->assertEqual($index->getCreatedTime(), REQUEST_TIME, 'The index record was created within this request.');
    $this->assertEqual($index->getEntityChangedTime(), $entity->getChangedTime(), 'The changed time matches.');
    $this->assertEqual($index->getEntityCreatedTime(), $entity->getCreatedTime(), 'The created time matches.');
    $this->assertEqual($index->getEntityType(), 'node', 'The entity type matches.');
    $this->assertEqual($index->getEntityBundle(), 'indexed', 'The entity bundle matches.');
    $this->assertEqual($index->getEntityUuid(), $entity->uuid(), 'The indexed id matches.');
    $this->assertEqual($index->getKeywords(), NULL, 'The keywords are empty.');
    $this->assertEqual($index->getLangcode(), Language::LANGCODE_DEFAULT, 'The language is undefined.');
    $this->assertEqual($index->getParentUuid(), NULL, 'The index has no parent.');
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'The index record has status visible.');
    $this->assertEqual($index->getTags(), NULL, 'The tags are empty.');
    $this->assertEqual($index->getTitle(), 'Indexed node', 'The title matches.');
    $node_uri = $entity->uri();
    $this->assertTrue(preg_match("|{$node_uri['path']}$|", $index->getUrl()), 'The translation set id is empty.');

    $this->indexing->delete($entity);

    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index has status not reachable.');
  }

  /**
   * Test indexing for users.
   */
  public function testIndexingUser() {
    // When I create a new entity of type 'node' with bundle 'indexed'.
    $entity = entity_create('user', array(
      'name' => 'Indexed user',
      'status' => 1,
    ));

    $this->indexing->index($entity);

    $this->assertTrue($this->indexing->exists($entity), 'Found index record for created node.');
    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getConnectionName(), NULL, 'The connection name is empty.');
    $this->assertEqual($index->getChangedTime(), REQUEST_TIME, 'The index record was last changed within this request.');
    $this->assertEqual($index->getCreatedTime(), REQUEST_TIME, 'The index record was created within this request.');
    $this->assertEqual($index->getEntityChangedTime(), REQUEST_TIME, 'The changed time matches.');
    $this->assertEqual($index->getEntityCreatedTime(), $entity->getCreatedTime(), 'The created time matches.');
    $this->assertEqual($index->getEntityType(), 'user', 'The entity type matches.');
    $this->assertEqual($index->getEntityBundle(), 'user', 'The entity bundle matches.');
    $this->assertEqual($index->getEntityUuid(), $entity->uuid(), 'The indexed id matches.');
    $this->assertEqual($index->getKeywords(), NULL, 'The keywords are empty.');
    $this->assertEqual($index->getLangcode(), Language::LANGCODE_DEFAULT, 'The language is undefined.');
    $this->assertEqual($index->getParentUuid(), NULL, 'The index has no parent.');
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'The index record has status visible.');
    $this->assertEqual($index->getTags(), NULL, 'The tags are empty.');
    $this->assertEqual($index->getTitle(), 'Indexed user', 'The title matches.');
    $node_uri = $entity->uri();
    $this->assertTrue(preg_match("|{$node_uri['path']}$|", $index->getUrl()), 'The translation set id is empty.');

    $this->indexing->delete($entity);

    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test indexing for files.
   */
  public function testIndexingFile() {
    $this->enableModules(array('file'));

    file_put_contents('public://indexed.txt', $this->randomName());
    $entity = entity_create('file', array(
      'uri' => 'public://indexed.txt',
      'timestamp' => REQUEST_TIME,
    ));

    $this->indexing->index($entity);

    $this->assertTrue($this->indexing->exists($entity), 'Found index record for created node.');
    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getConnectionName(), NULL, 'The connection name is empty.');
    $this->assertEqual($index->getChangedTime(), REQUEST_TIME, 'The index record was last changed within this request.');
    $this->assertEqual($index->getCreatedTime(), REQUEST_TIME, 'The index record was created within this request.');
    $this->assertEqual($index->getEntityChangedTime(), REQUEST_TIME, 'The changed time matches.');
    $this->assertEqual($index->getEntityCreatedTime(), REQUEST_TIME, 'The created time matches.');
    $this->assertEqual($index->getEntityType(), 'file', 'The entity type matches.');
    $this->assertEqual($index->getEntityBundle(), 'file', 'The entity bundle matches.');
    $this->assertEqual($index->getEntityUuid(), $entity->uuid(), 'The indexed id matches.');
    $this->assertEqual($index->getKeywords(), NULL, 'The keywords are empty.');
    $this->assertEqual($index->getLangcode(), Language::LANGCODE_DEFAULT, 'The language is undefined.');
    $this->assertEqual($index->getParentUuid(), NULL, 'The index has no parent.');
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'The index record has status visible.');
    $this->assertEqual($index->getTags(), NULL, 'The tags are empty.');
    $this->assertEqual($index->getTitle(), 'indexed.txt', 'The title matches.');
    $this->assertTrue(preg_match('|indexed.txt$|', $index->getUrl()), 'The uri matches.');

    $this->indexing->delete($entity);

    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test indexing for terms.
   */
  public function testIndexingTerm() {
    $this->enableModules(array('taxonomy'));

    $entity = entity_create('taxonomy_term', array(
      'name' => 'Indexed term',
      'vid' => 'test_vocab',
      'changed' => REQUEST_TIME,
    ));

    $this->indexing->index($entity);

    $this->assertTrue($this->indexing->exists($entity), 'Found index record for created node.');
    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getConnectionName(), NULL, 'The connection name is empty.');
    $this->assertEqual($index->getChangedTime(), REQUEST_TIME, 'The index record was last changed within this request.');
    $this->assertEqual($index->getCreatedTime(), REQUEST_TIME, 'The index record was created within this request.');
    $this->assertEqual($index->getEntityChangedTime(), $entity->getChangedTime(), 'The changed time matches.');
    $this->assertEqual($index->getEntityCreatedTime(), REQUEST_TIME, 'The created time matches.');
    $this->assertEqual($index->getEntityType(), 'taxonomy_term', 'The entity type matches.');
    $this->assertEqual($index->getEntityBundle(), 'test_vocab', 'The entity bundle matches.');
    $this->assertEqual($index->getEntityUuid(), $entity->uuid(), 'The indexed id matches.');
    $this->assertEqual($index->getKeywords(), NULL, 'The keywords are empty.');
    $this->assertEqual($index->getLangcode(), Language::LANGCODE_DEFAULT, 'The language is undefined.');
    $this->assertEqual($index->getParentUuid(), NULL, 'The index has no parent.');
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'The index record has status visible.');
    $this->assertEqual($index->getTags(), NULL, 'The tags are empty.');
    $this->assertEqual($index->getTitle(), 'Indexed term', 'The title matches.');
    $node_uri = $entity->uri();
    $this->assertTrue(preg_match("|{$node_uri['path']}$|", $index->getUrl()), 'The translation set id is empty.');

    $this->indexing->delete($entity);

    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test queued indexing.
   */
  public function testQueuedIndexing() {
    $this->enableModules(array('node', 'shared'));
    $this->installSchema('system', array('sequences', 'queue'));
    $this->installSchema('user', array('users'));
    $this->installSchema('node', array(
      'node',
      'node_field_data',
      'node_field_revision',
    ));

    $service = \Drupal::service('sharedcontent.indexing.queued');

    $account = entity_create('user', array('name' => $this->randomName(), 'status' => 1));
    $account->enforceIsNew();
    $account->save();
    $entity = entity_create('node', array(
      'title' => $this->randomName(),
      'type' => 'indexed',
      'uid' => $account,
    ));
    $entity->save();

    $service->index($entity);
    $this->assertFalse($service->exists($entity), 'No index record was created.');

    $service->dequeue(array(
      'entity_type' => $entity->entityType(),
      'entity_id' => $entity->id(),
      'op' => 'index',
    ));
    $this->assertTrue($service->exists($entity), 'Index record was created.');

    $service->delete($entity);
    $index = $this->indexing->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_VISIBLE, 'Index hat status has not changed.');

    $service->dequeue(array(
      'entity_type' => $entity->entityType(),
      'entity_id' => $entity->id(),
      'op' => 'delete',
    ));
    $index = $service->load($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status changed to not reachable.');
  }

  /**
   * Test queued indexing.
   */
  public function testIndexingServiceFactory() {
    $this->enableModules(array('node'));
    \Drupal::config('sharedcontent.indexing')->set('node.default', 'default');
    \Drupal::config('sharedcontent.indexing')->set('node.queued', 'queue');

    $entity_null = entity_create('node', array('title' => $this->randomName(), 'type' => 'null'));
    $entity_default = entity_create('node', array('title' => $this->randomName(), 'type' => 'default'));
    $entity_queued = entity_create('node', array('title' => $this->randomName(), 'type' => 'queued'));

    $factory = \Drupal::service('sharedcontent.indexing');

    $service_null = $factory->get($entity_null);
    $service_default = $factory->get($entity_default);
    $service_factory = $factory->get($entity_queued);

    $this->assertTrue($service_null instanceof NullIndexing, 'Got null service for unconfigured bundle.');
    $this->assertTrue($service_default instanceof NullIndexing, 'Got default service for default bundle.');
    $this->assertTrue($service_factory instanceof NullIndexing, 'Got null service for queued bundle.');
  }
}
