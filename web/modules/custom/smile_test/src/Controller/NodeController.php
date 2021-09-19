<?php

namespace Drupal\smile_test\Controller;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\Core\Access\AccessResult;

/**
 * Class NodeController.
 *
 * @package Drupal\smile_test\Controller
 */
class NodeController {

      // method for outputting the node whose number was entered by the administrator
  public function node_render($nid) {
    if (!is_numeric($nid)) {
      throw new AccessDeniedHttpException();
    }
      $entity_type = 'node';
      $view_mode = 'default';

      $node = \Drupal::entityTypeManager()
        ->getStorage($entity_type)
        ->load($nid);
      $element = \Drupal::entityTypeManager()
        ->getViewBuilder($entity_type)
        ->view($node, $view_mode);
      $output = render($element);
      return [
        '#markup' => $output
      ];
    }
      // method for checking the user permission
  public function access() {
    $user = \Drupal::currentUser();
    $user->hasPermission('access content');
    $roles = $user->getRoles();
     if (in_array( 'administrator', $roles)) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }
}
