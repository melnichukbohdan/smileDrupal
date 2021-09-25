<?php

namespace Drupal\smile_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\EntityTypeManagerInterface;


/**
 * Class smileTestController.
 *
 * @package Drupal\SmileTest\Controller
 */
class SmileTestController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Returns a render-able array for a text page.
   */

   public function content()
  {
    $build = [
      '#markup' => $this->t('It is my first route ever'),
    ];
    return $build;
  }
}

