<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\EntityTypeManagerInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * Class PetsDataController.
 *
 * @package Drupal\pets_owners_storage\Controller
 */

class PetsOwnerStorageController extends ControllerBase {

  /**
   * @return array
   */
  public function getContent() {
    $content = [];
    $content['message'] = [
      '#markup' => $this->t('Generate a list of all entries in the database from '),
    ];
    // Render link.
    $content['link'] = [
      '#title' => $this->t('Form'),
      '#type' => 'link',
      '#url' => Url::fromRoute('pets_owners_storage.form'),
    ];
    $headers = [
      $this->t('Id'),
      $this->t('Prefix'),
      $this->t('Name'),
      $this->t('Gender'),
      $this->t('Age'),
      $this->t('Father`s name'),
      $this->t('Mother`s name'),
      $this->t('Pets name'),
      $this->t('Email'),
      $this->t('Delete'),
      $this->t('Edit'),
    ];
    $entries = Database::getConnection()
      ->select('pets_owners_storage', 'p')
      ->fields('p', [
        'poid',
        'prefix',
        'name',
        'gender',
        'age',
        'father',
        'mother',
        'pets_name',
        'email',
      ])->execute();
    $rows = [];
    $i = 0;



  foreach ($entries as $entry) {
    $rows[] = array_map('Drupal\Component\Utility\Html::escape', (array) $entry);
    //link delete user
    $link_delete = Url::fromRoute('pets_owners_storage.delete',['poid'=>$rows[$i]['poid']]);
    $link_delete->setOptions([
      'attributes' => [
        'class' => ['use-ajax', 'button', 'button--small'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode(['width' => 400]),
      ],
    ]);
    $ajax_button = ['#type' => 'markup',
      '#markup' => Link::fromTextAndUrl(t('Delete'), $link_delete)->toString(),
      '#attached' => ['library' => ['core/drupal.dialog.ajax']]];
    $rows[$i]['delete']=\Drupal::service('renderer')->render($ajax_button);
    //link update user data
    $edit = Url::fromUserInput('/pets_owners_form/' . $rows[$i]['poid']);
    $rows[$i]['edit'] = Link::fromTextAndUrl('Edit', $edit);
    $i++;
  }

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available.'),
    ];
    return $content;
  }
}

