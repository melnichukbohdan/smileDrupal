<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Class PetsOwnersForm.
 * Crete form
 * @package Drupal\pets_owners_storage\Form
 */
class PetsOwnersForm extends FormBase {


  /**
   * This method assigns a unique ID form
   */
  public function getFormId() {
    return 'pets_owners_form';
  }

  /*
   * this method build form and added parameter's for input
   */
  public function buildForm(array $form, FormStateInterface $form_state, $uid = NULL) {

    // Get values from DB.
    $values = Database::getConnection()
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
      ])->condition('uid', $uid)
      ->execute()->fetchAssoc();


    // Build form.
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 100,
      '#required' => TRUE,
    ];
    $gender = [
      'male' => $this->t('male'),
      'female' => $this->t('female'),
      'unknown' => $this->t('unknown'),
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => $gender,
      '#default_value' => $gender['unknown'],
    ];
    $prefix = [
      'mr' => $this->t('mr'),
      'mrs' => $this->t('mrs'),
      'ms' => $this->t('ms'),
    ];
    $form['prefix'] = [
      '#type' => 'select',
      '#title' => $this->t('Prefix'),
      '#options' => $prefix,
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
      '#min' => 1,
      '#max' => 120,
      '#required' => TRUE,
    ];
    $form['parents'] = [
      '#type' => 'details',
      '#title' => $this->t('Parents'),
    ];
    $form['parents']['father'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Father`s name'),
    ];
    $form['parents']['mother'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mother`s name'),
    ];
    $form['have_pets'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Have you some pets?'),
    ];
    $form['pets_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Names(s) of your pet(s)'),
      '#states' => [
        'invisible' => [
          'input[name="have_pets"]' => [
            'checked' => FALSE,
          ],
        ],
      ],
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    /**
     * Condition
     */
    if (!empty($values)) {
      $form['name']['#default_value'] = $values['name'];
      $form['prefix']['#default_value'] = $values['prefix'];
      $form['gender']['#default_value'] = $values['gender'];
      $form['age']['#default_value'] = $values['age'];
      $form['parents']['father']['#default_value'] = $values['father'];
      $form['parents']['mother']['#default_value'] = $values['mother'];
      $form['have_pets']['#default_value'] = 1;
      $form['pets_name']['#default_value'] = $values['pets_name'];
      $form['email']['#default_value'] = $values['email'];
      $form['actions']['delete'] = [
        '#type' => 'submit',
        '#value' => 'delete',
        // Custom function on submit.
        '#submit' => ['::delete'],
      ];
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Change'),
        '#submit' => ['::change'],
      ];
      // Pass value $uid to submit form.
      $form_state->set('uid', $uid);
      return $form;
    }
    return $form;
  }

  /**
   * @inheritDoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (($form_state->getValue('age') < '1') || ($form_state->getValue('age') > '120')) {
      $form_state->setErrorByName('age', $this->t('Please enter valid age'));
    }

    if (empty(trim($form_state->getValue('name'))) || (mb_strlen($form_state->getValue('name')) > 100)) {
      $form_state->setErrorByName('name', $this->t('Please enter valid name'));
    }

    if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Please enter valid email address'));
    }
  }


  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $connection->insert('pets_owners_storage')
      ->fields([
        'prefix' => $form_state->getValue('prefix'),
        'name' => $form_state->getValue('name'),
        'gender' => $form_state->getValue('gender'),
        'age' => $form_state->getValue('age'),
        'father' => $form_state->getValue('father'),
        'mother' => $form_state->getValue('mother'),
        'pets_name' => $form_state->getValue('pets_name'),
        'email' => $form_state->getValue('email'),
      ])
      ->execute();
    $form_state->setRedirect('pets_owners_storage.content');
  }

  /**
   * Custom submit function delete from DB.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function delete(array &$form, FormStateInterface $form_state) {
    $uid = $form_state->get('uid');
    $query = \Drupal::database();
    $query->delete('pets_owners_storage')
      ->condition('uid', $uid)
      ->execute();
    $text = 'Record uid => ' . $uid . ' was removed from database.';
    \Drupal::messenger()->addMessage($text);
    $form_state->setRedirect('pets_owners_storage.content');
  }

  /**
   * Change value of the form.
   */
  public function change(array &$form, FormStateInterface $form_state) {
    $uid = $form_state->get('uid');
    $query = \Drupal::database();
    $query->update('pets_owners_storage')
      ->fields([
        'prefix' => $form_state->getValue('prefix'),
        'name' => $form_state->getValue('name'),
        'gender' => $form_state->getValue('gender'),
        'age' => $form_state->getValue('age'),
        'father' => $form_state->getValue('father'),
        'mother' => $form_state->getValue('mother'),
        'pets_name' => $form_state->getValue('pets_name'),
        'email' => $form_state->getValue('email'),
      ])
      ->condition('uid', $uid)
      ->execute();
    $text = $this->t('The value changed');
    \Drupal::messenger()->addMessage($text);
    $form_state->setRedirect('pets_owners_storage.content');
  }

}
