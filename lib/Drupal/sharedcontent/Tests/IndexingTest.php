<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\IndexingTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\Core\Language\Language;
use Drupal\sharedcontent\Entity\Index;
use Drupal\sharedcontent\IndexInterface;
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
    'field',
    'sharedcontent',
    'sharedcontent_server',
  );

  /**
   * The indexing service.
   *
   * @var \Drupal\sharedcontent\Indexing.
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

    $this->indexing = \Drupal::service('sharedcontent.indexing');
  }

  /**
   * Test indexing for nodes.
   */
  public function testIndexingNode() {
    $this->enableModules(array('node'));
    $this->installSchema('node', array(
      'node',
      'node_field_data',
      'node_field_revision',
    ));

    // Given bundle 'indexed' of entity 'node' is 'not enabled' for indexing.
    $this->indexing->setIndexable('node', 'indexable', FALSE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $entity = entity_create('node', array(
      'title' => 'Not indexed node',
      'type' => 'indexed',
    ));
    $entity->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($entity), 'No index record was created.');

    // Given bundle 'indexed' of entity 'node' is 'enabled' for indexing.
    $this->indexing->setIndexableByEntity($entity, TRUE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $entity = entity_create('node', array(
      'title' => 'Indexed node',
      'type' => 'indexed',
    ));
    $entity->save();

    // Then a new index record was created.
    $this->assertTrue(sharedcontent_index_exists($entity), 'Found index record for created node.');

    // And the created index matches the values from the indexed 'node'.
    $index = sharedcontent_index_load_by_entity($entity);
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

    // When I delete the node.
    $entity->delete();

    // Then the status of the index is set to "not reachable;..
    $index = sharedcontent_index_load_by_entity($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test indexing for users.
   */
  public function testIndexingUser() {
    $this->installSchema('system', array('sequences'));
    $this->installSchema('user', array('users', 'users_data', 'users_roles'));

    // Given bundle 'user' of entity 'user' is 'not enabled' for indexing.
    $this->indexing->setIndexable('user', 'user', FALSE);

    // When I create a new entity of type 'user' with bundle 'user'.
    $entity = entity_create('user', array(
      'name' => 'Non indexed user',
      'mail' => 'test@example.com',
      'status' => 1,
      'language' => 'en',
    ));
    $entity->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($entity), 'No index record was created.');

    // Given bundle 'user' of entity 'user' is 'enabled' for indexing.
    $this->indexing->setIndexableByEntity($entity, TRUE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $entity = entity_create('user', array(
      'name' => 'Indexed user',
      'mail' => 'test@example.com',
      'status' => 1,
      'language' => 'en',
    ));
    $entity->save();

    // Then a new index record was created.
    $this->assertTrue(sharedcontent_index_exists($entity), 'Found index record for created node.');

    // And the created index matches the values from the indexed 'node'.
    $index = sharedcontent_index_load_by_entity($entity);

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

    // When I delete the node.
    $entity->delete();

    // Then the status of the index is set to "not reachable;..
    $index = sharedcontent_index_load_by_entity($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test indexing for files.
   */
  public function testIndexingFile() {
    $this->enableModules(array('file'));
    $this->installSchema('file', array('file_managed', 'file_usage'));

    // Given bundle 'field' of entity 'file' is 'not enabled' for indexing.
    $this->indexing->setIndexable('file', 'file', FALSE);

    // When I create a new entity of type 'user' with bundle 'user'.
    file_put_contents('public://non_indexed.txt', $this->randomName());
    $entity = entity_create('file', array(
      'uri' => 'public://non_indexed.txt',
    ));
    $entity->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($entity), 'No index record was created.');

    // Given bundle 'user' of entity 'user' is 'enabled' for indexing.
    $this->indexing->setIndexableByEntity($entity, TRUE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    file_put_contents('public://indexed.txt', $this->randomName());
    $entity = entity_create('file', array(
      'uri' => 'public://indexed.txt',
    ));
    $entity->save();

    // Then a new index record was created.
    $this->assertTrue(sharedcontent_index_exists($entity), 'Found index record for created node.');

    // And the created index matches the values from the indexed 'node'.
    $index = sharedcontent_index_load_by_entity($entity);

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

    // When I delete the node.
    $entity->delete();

    // Then the status of the index is set to "not reachable;..
    $index = sharedcontent_index_load_by_entity($entity);
    $this->assertEqual($index->getStatus(), IndexInterface::STATUS_NOT_REACHABLE, 'Index hat status not reachable.');
  }

  /**
   * Test indexing for terms.
   */
  public function testIndexingTerm() {
    $this->enableModules(array('taxonomy'));
    $this->installSchema('taxonomy', array(
      'taxonomy_term_data',
      'taxonomy_term_hierarchy',
      'taxonomy_index',
    ));

    $vocabulary = entity_create('taxonomy_vocabulary', array(
      'vid' => 'test_vocab',
      'name' => 'Test vocabulary',
    ));
    $vocabulary->save();

    // Given bundle 'user' of entity 'user' is 'not enabled' for indexing.
    $this->indexing->setIndexable('taxonomy_term', 'test_vocab', FALSE);

    // When I create a new entity of type 'user' with bundle 'user'.
    $entity = entity_create('taxonomy_term', array(
      'name' => 'Non indexed term',
      'vid' => $vocabulary->id(),
    ));
    $entity->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($entity), 'No index record was created.');

    // Given bundle 'user' of entity 'user' is 'enabled' for indexing.
    $this->indexing->setIndexableByEntity($entity, TRUE);

    // When I create a new entity of type 'node' with bundle 'indexed'.
    $entity = entity_create('taxonomy_term', array(
      'name' => 'Indexed term',
      'vid' => $vocabulary->id(),
    ));
    $entity->save();

    // Then a new index record was created.
    $this->assertTrue(sharedcontent_index_exists($entity), 'Found index record for created node.');

    // And the created index matches the values from the indexed 'node'.
    $index = sharedcontent_index_load_by_entity($entity);

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

    // When I delete the node.
    $entity->delete();

    // Then the status of the index is set to "not reachable;..
    $index = sharedcontent_index_load_by_entity($entity);
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

    // Given queued indexing is enabled.
    \Drupal::config('sharedcontent.settings')->set('queued', TRUE);

    // When I create a content that gets indexed.
    $this->indexing->setIndexable('node', 'indexed', TRUE);
    $account = entity_create('user', array(
      'name' => $this->randomName(),
      'status' => 1,
    ));
    $account->enforceIsNew();
    $account->save();
    $entity = entity_create('node', array(
      'title' => 'Indexed node',
      'type' => 'indexed',
      'uid' => $account,
    ));
    $entity->save();

    // Then no index record was created.
    $this->assertFalse(sharedcontent_index_exists($entity), 'No index record was created.');

    // When cron gets executed.
    $this->assertTrue(drupal_cron_run(), 'Cron run was successful.');

    // Then an index record exists for the created content.
    $this->assertTrue(sharedcontent_index_exists($entity), 'Index record was created.');
  }
}
