<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class ConfirmDeleteForm extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $poid = NULL) {
    $this->id = $poid;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $query = \Drupal::database();
    $query->delete('pets_owners_storage')
      ->condition('poid', $this->id)
      ->execute();
    $text = 'Record poid => ' . $this->id . ' was removed from database.';
    \Drupal::messenger()->addMessage($text);
    // Redirect to a page that show all the records.
    $form_state->setRedirect('pets_owners_storage.content');
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "confirm_delete_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('pets_owners_storage.content');
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete user id %id?', ['%id' => $this->id]);
  }

}
