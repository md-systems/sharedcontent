<?php

namespace Drupal\sharedcontent\Plugin\rest\resource;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\rest\Plugin\rest\resource\EntityResource;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Resources for SharedContent
 *
 * @Plugin(
 *   id = "sharedcontent",
 *   label = @Translation("SharedContent"),
 *   serialization_class = "Drupal\Core\Entity\Entity",
 *   derivative = "Drupal\sharedcontent\Plugin\Derivative\SharedContentDerivative"
 * )
 */
class SharedContentResource extends EntityResource {

  /**
   * Responds to entity GET requests.
   *
   * @param mixed $uuid
   *   The entity uuid.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the loaded entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   */
  public function get($uuid) {
    $definition = $this->getPluginDefinition();
    $entity = entity_load_by_uuid($definition['entity_type'], $uuid);

    if ($entity) {
      if (!$entity->access('view')) {
        throw new AccessDeniedHttpException();
      }
      $entity = clone $entity;
      $exposed_fields = $entity->getExposedFields();
      foreach ($entity as $field_name => $field) {
        if (!$field->access('view') || !in_array($field_name, $exposed_fields)) {
          unset($entity->{$field_name});
        }
      }
      return new ResourceResponse($entity);
    }
    throw new NotFoundHttpException(t('Entity with ID @id not found', array('@id' => $uuid)));
  }

  /**
   * Responds to entity POST requests and saves the new entity.
   *
   * @param mixed $id
   *   Ignored. A new entity is created with a new ID.
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   */
  public function post($id, EntityInterface $entity = NULL) {
    if ($entity == NULL) {
      throw new BadRequestHttpException(t('No entity content received.'));
    }

    if (!$entity->access('create')) {
      throw new AccessDeniedHttpException();
    }

    $definition = $this->getPluginDefinition();
    // Verify that the deserialized entity is of the type that we expect to
    // prevent security issues.
    if ($entity->entityType() != $definition['entity_type']) {
      throw new BadRequestHttpException(t('Invalid entity type'));
    }
    // POSTed entities must not have an ID set, because we always want to create
    // new entities here.
    if (!$entity->isNew()) {
      throw new BadRequestHttpException(t('Only new entities can be created'));
    }
    $exposed_fields = $entity->getExposedFields();
    foreach ($entity as $field_name => $field) {
      if (!$field->access('create') || !in_array($field_name, $exposed_fields)) {
        throw new AccessDeniedHttpException(t('Access denied on creating field @field.', array('@field' => $field_name)));
      }
    }

    // Validate the received data before saving.
    $this->validate($entity);
    try {
      $entity->save();
      watchdog('rest', 'Created entity %type with UUID %uuid.', array('%type' => $entity->entityType(), '%uuid' => $entity->uuid()));

      $url = url(strtr($this->pluginId, ':', '/') . '/' . $entity->uuid(), array('absolute' => TRUE));
      // 201 Created responses have an empty body.
      return new ResourceResponse(NULL, 201, array('Location' => $url));
    }
    catch (EntityStorageException $e) {
      throw new HttpException(500, t('Internal Server Error'), $e);
    }
  }
}
