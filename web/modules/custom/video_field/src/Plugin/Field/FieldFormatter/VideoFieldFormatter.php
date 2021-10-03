<?php

namespace Drupal\video_field\Plugin\Field\FieldFormatter;

use \Drupal\Core\Field\FormatterBase;
use \Drupal\Core\Field\FieldItemListInterface;


/**
 * Plugin implementation of the 'video_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "video_field_formatter",
 *   label = @Translation("Video URL"),
 *   module = "video_field",
 *   field_types = {
 *     "video_field"
 *   }
 * )
 */
class VideoFieldFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        // We create a render array to produce the desired markup,
        // <iframe width="560" height="315" src="https://www.youtube.com/embed/$item->value" frameborder="0"></iframe>.
        // See theme_html_tag().
        '#type' => 'html_tag',
        '#tag' => 'iframe',
        '#attributes' => [
          'width' => '560',
          'height' => '315',
          'frameborder' => '0',
          'src' => "https://www.youtube.com/embed/$item->value",
        ],
      ];
    }

    return $elements;
  }
//  public function viewElements(FieldItemListInterface $items, $langcode) {
//    $elements = array();
//
//    foreach ($items as $delta => $item) {
//      preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $item->value, $matches);
//
//      if (!empty($matches)) {
//        $content = '<a href="' . $item->value . '" target="_blank"><img src="http://img.youtube.com/vi/' . $matches[0] . '/0.jpg"></a>';
//        $elements[$delta] = [
//          '#type' => 'html_tag',
//          '#tag' => 'p',
//          '#value' => $content,
//        ];
//      }
//    }
//
//    return $elements;
//  }

}
