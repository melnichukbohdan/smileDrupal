<?php

namespace Drupal\simple_ajax\Form;

use Drupal\Core\Ajax\AfterCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 *
 */
class AjaxForm extends FormBase {

  /**
   * @inheritDoc
   */
  public function getFormId() {
    return 'ajax_form';
  }

  /**
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your text'),
    ];
    $form['ajax_container'] = [
      '#type' => 'container',
      '#attributes' => [
        // Add class.
        'class' => [
          'ajax_container',
        ],
        // Hide container.
        'style' => [
          'display:none;',
        ],
      ],
    ];
    $form['first'] = [
      '#type' => 'checkbox',
      '#title' => '#1',
      // Call ajax function.
      '#ajax' => [
        'callback' => '::fieldAjax',
        'event' => 'change',
      ],
    ];
    $form['ajax_container']['text1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Added text field 1'),
    ];
    $form['ajax_container']['text2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Added text field 2'),
    ];

    $form['second'] = [
      '#type' => 'checkbox',
      '#title' => '#2',
      '#ajax' => [
        'callback' => '::googleAjax',
        'event' => 'change',
      ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    $form['ajax_link'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'ajax_link',
        ],
      ],
    ];
    return $form;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function fieldAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if ($form_state->getValue('first') === 1) {
      $response->addCommand(new CssCommand('.ajax_container', ['display' => 'block']));
    }
    else {
      $response->addCommand(new CssCommand('.ajax_container', ['display' => 'none']));
    }
    return $response;
  }

  /**
   *
   */
  public function googleAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $selector = '.ajax_link';
    // Hide or show link.
    if ($form_state->getValue('second') === 1) {
      $data = '<a href="https://www.google.com/">Google</a>';
    }
    else {
      $data = '';
    }
    $response->addCommand(new HtmlCommand($selector, $data));
    return $response;
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
