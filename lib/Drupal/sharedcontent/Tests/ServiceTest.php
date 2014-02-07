<?php

/**
 * @file
 * Contains \Drupal\sharedcontent\Tests\ServiceTest.
 */

namespace Drupal\sharedcontent\Tests;

use Drupal\Component\Uuid\Php;
use Drupal\sharedcontent\Plugin\rest\resource\SharedContentResource;
use Drupal\system\Tests\Entity\EntityUnitTestBase;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class ServiceTest extends EntityUnitTestBase {

  static function getInfo() {
    return array(
      'name' => 'Service tests',
      'description' => 'Test the service resources.',
      'group' => 'Shared Content',
    );
  }

  /**
   * The UUID object to be used for generating UUIDs.
   *
   * @var \Drupal\Component\Uuid\UuidInterface
   */
  protected $uuid;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    // Initiate the generator object.
    $this->uuid = new Php();

    $modules = array('sharedcontent', 'rest');
    $this->enableModules($modules);
    $this->installSchema('sharedcontent', array(
      'sharedcontent_index',
      'sharedcontent_assignment',
    ));
  }

  /**
   * Test the resource for the index entity.
   */
  public function testIndexResource() {
    $configuration = array();
    $plugin_id = 'sharedcontent:index';
    $plugin_definition = array(
      'id' => 'sharedcontent:index',
      'entity_type' => 'sharedcontent_index',
      'serialization_class' => 'Drupal\\sharedcontent\\Entity\\Index',
      'label' => 'Shared Content Index',
      'derivative' => 'Drupal\\sharedcontent\\Plugin\\Derivative\\SharedContentDerivative',
      'class' => 'Drupal\\sharedcontent\\Plugin\\rest\\resource\\SharedContentResource',
      'provider' => 'sharedcontent',
    );
    $serializer_formats = array('hal_json', 'json', 'xml');
    $resource = new SharedContentResource($configuration, $plugin_id, $plugin_definition, $serializer_formats);

    $index = entity_create('sharedcontent_index', array(
      'entity_type' => 'node',
      'entity_uuid' => $this->uuid->generate(),
      'entity_bundle' => 'article',
      'title' => $this->randomName(),
      'url' => url('node/1', array('absolute' => TRUE)),
     ));
    $index->save();

    $GLOBALS['user'] = $this->createUser();
    $response = $resource->get($index->uuid());

    $field_expectations = array(
      'id' => FALSE,
      'uuid' => TRUE,
      'parent_uuid' => TRUE,
      'origin' => FALSE,
      'connection_name' => FALSE,
      'entity_uuid' => TRUE,
      'entity_type' => TRUE,
      'entity_bundle' => TRUE,
      'title' => TRUE,
      'langcode' => TRUE,
      'translationset_id' => TRUE,
      'keywords' => TRUE,
      'tags' => TRUE,
      'url' => TRUE,
      'status' => TRUE,
      'accessibility' => FALSE,
      'entity_created' => TRUE,
      'entity_changed' => TRUE,
      'created' => FALSE,
      'changed' => FALSE,
      'is_linkable' => FALSE,
    );

    $msg_template = 'Expected %field to be %presence';
    foreach ($response->getResponseData() as $field_name => $field) {
      $expected = isset($field_expectations[$field_name]) && $field_expectations[$field_name];
      $msg = format_string($msg_template, array(
        '%field' => $field_name,
        '%presence' => $expected ? 'present' : 'absent',
      ));
      $this->assertEqual($expected, isset($response->getResponseData()->$field_name), $msg);
    }

    $index = entity_create('sharedcontent_index', array(
      'entity_type' => 'node',
      'entity_uuid' => $this->uuid->generate(),
      'entity_bundle' => 'article',
      'title' => $this->randomName(),
      'url' => url('node/2', array('absolute' => TRUE)),
    ));

    foreach ($index as $field_name => $field) {
      if (!in_array($field_name, $index->getExposedFields())) {
        unset($index->$field_name);
      }
    }

    $response = $resource->post(NULL, $index);
    $this->assertEqual(201, $response->getStatusCode(), 'Response code indicates entity created');
    $index_created = entity_load_multiple_by_properties('sharedcontent_index', array('entity_uuid' => $index->getEntityUuid()));
    $this->assertEqual(1, count($index_created), 'Found index in database');
    $index_created = reset($index_created);
    $this->assertEqual($index->getEntityUuid(), $index_created->getEntityUuid(), 'The index record found matches the created one.');
    $pattern = format_string('|/sharedcontent/index/@uuid$|', array('@uuid' => $index_created->uuid()));
    $this->assertTrue(preg_match($pattern, $response->headers->get('Location')), 'Found correct location in response');

    $index->setEntityBundle('page');
    foreach ($index as $field_name => $field) {
      if ($field_name != 'entity_bundle') {
        unset($index->$field_name);
      }
    }
    $response = $resource->patch($index_created->uuid(), $index);
    $this->assertEqual(204, $response->getStatusCode(), 'Response code indicates no content');
    $index_changed = entity_load_by_uuid('sharedcontent_index', $index_created->uuid());
    $this->assertEqual('page', $index_changed->getEntityBundle(), 'The changed record was altered correctly');

    try {
      $resource->delete($index->id());
      $this->fail('Deleting entity not prohibited.');
    }
    catch (MethodNotAllowedHttpException $e) {
      $this->pass('Deleting entity not allowed.');
    }
  }
}
