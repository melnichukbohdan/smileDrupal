<?php

namespace Drupal\pets_owners_storage\Controller;

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
        'uid',
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
      $delete = Url::fromUserInput('/pets_owners_delete/' . $rows[$i]['uid']);
      $rows[$i]['delete'] = Link::fromTextAndUrl('Delete', $delete);
      $edit = Url::fromUserInput('/pets_owners_form/' . $rows[$i]['uid']);
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

  /**
   * @param null $uid
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function delete($uid = NULL) {
    $query = \Drupal::database();
    $query->delete('pets_owners_storage')
      ->condition('uid', $uid)
      ->execute();
    $text = 'Record uid => ' . $uid . ' was removed from database.';
    \Drupal::messenger()->addMessage($text);
    // Redirect to a page that show all the records.
    return $this->redirect('pets_owners_storage.content');
  }

}

