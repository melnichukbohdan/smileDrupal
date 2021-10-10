<?php
  
  namespace Drupal\video\Plugin\Field\FieldFormatter;
  
  use Drupal\Core\Field\FieldItemListInterface;
  use Drupal\Core\Field\FormatterBase;
  use Drupal;
  use Drupal\Core\Link;
  use Drupal\Core\Url;
  
  /**
   * Plugin implementation of the 'VideoDefaultFormatter' formatter.
   *
   * @FieldFormatter(
   *   id = "VideoDefaultFormatter",
   *   label = @Translation("Video"),
   *   field_types = {
   *     "Video"
   *   }
   * )
   */
  class VideoDefaultFormatter extends FormatterBase {
    
    /**
     * Define how the field type is showed.
     *
     * Inside this method we can customize how the field is displayed inside
     * pages.
     */
    public function viewElements (FieldItemListInterface $items, $langcode) {

      $elements = [];
      foreach ($items as $delta => $item) {
        $elements[$delta] = [
          '#markup' => '<iframe id="ytplayer" type="text/html" width="804"
           height="452"
           src="https://www.youtube.com/embed/' . $this->parse_yturl($item->url) . '"
           frameborder="0"/>',
          '#allowed_tags' => ['iframe', 'a'],
        ];
      }
      return $elements;
    }


  public function parse_yturl($url)
    {
      $pattern = '#^(?:https?://)?';
      $pattern .= '(?:www\.)?';
      $pattern .= '(?:';
      $pattern .=   'youtu\.be/';
      $pattern .=   '|youtube\.com';
      $pattern .=   '(?:';
      $pattern .=     '/embed/';
      $pattern .=     '|/v/';
      $pattern .=     '|/watch\?v=';
      $pattern .=     '|/watch\?.+&v=';
      $pattern .=   ')';
      $pattern .= ')';
      $pattern .= '([\w-]{11})';
      $pattern .= '(?:.+)?$#x';
      preg_match($pattern, $url, $matches);
      return (isset($matches[1])) ? $matches[1] : false;
    }
    
  }
