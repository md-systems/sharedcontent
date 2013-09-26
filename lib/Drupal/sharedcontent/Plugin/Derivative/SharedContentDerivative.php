<?php

namespace Drupal\sharedcontent\Plugin\Derivative;

use Drupal\rest\Plugin\Derivative\EntityDerivative;

class SharedContentDerivative extends EntityDerivative {

  /**
   * Implements DerivativeInterface::getDerivativeDefinitions().
   */
  public function getDerivativeDefinitions(array $base_plugin_definition) {
    if (!isset($this->derivatives)) {
      $definitions = array();
      $definitions['index'] = $this->entityManager->getDefinition('sharedcontent_index');
      $definitions['assignment'] = $this->entityManager->getDefinition('sharedcontent_assignment');

      foreach ($definitions as $resource => $entity_info) {
        $this->derivatives[$resource] = array(
          'id' => 'sharedcontent:' . $resource,
          'entity_type' => $entity_info['id'],
          'serialization_class' => $entity_info['class'],
          'label' => $entity_info['label'],
        );
        $this->derivatives[$resource] += $base_plugin_definition;
      }
    }
    return $this->derivatives;
  }
}
