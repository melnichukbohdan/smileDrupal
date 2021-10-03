<?php

namespace Drupal\video_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;



  /**
   * Plugin implementation of the 'snippets_default' widget.
   *
   * @FieldWidget(
   *   id = "video_field_widget",
   *   label = @Translation("Video URL"),
   *   module = "video_field",
   *   field_types = {
   *     "video_field"
   *   }
   * )
   */

class VideoFieldWidget extends WidgetBase {

   /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $value = $this->get_youtube_id_from_url($value);
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#maxlength' => 256,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    return ['value' => $element];
  }

  /**
   * @param $url
   * @return mixed
   * ID video from youtube
   */
  public function get_youtube_id_from_url($url)  {
    preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches);
    return $matches[0];
  }

  /**
   * Validate the link youtube.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) === 0) {
      $form_state->setValueForElement($element, '');
    }
  }

//  /**
//   * Validate the color text field.
//   */
//  public function validate($element, FormStateInterface $form_state) {
//    $value = $element['#value'];
//    if (strlen($value) == 0) {
//      $form_state->setValueForElement($element, '');
//      return;
//    }
//    if(!preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $value, $matches)) {
//      $form_state->setError($element, t("Youtube video URL is not correct."));
//    }
//  }
}
