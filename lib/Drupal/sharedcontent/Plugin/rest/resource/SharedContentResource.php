<?php

namespace Drupal\sharedcontent\Plugin\rest\resource;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\rest\Plugin\rest\resource\EntityResource;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
}
