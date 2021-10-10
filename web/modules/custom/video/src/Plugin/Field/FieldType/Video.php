<?php
  
  namespace Drupal\video\Plugin\Field\FieldType;
  
  use Drupal\Core\Field\FieldItemBase;
  use Drupal\Core\TypedData\DataDefinition;
  use Drupal\Core\Field\FieldStorageDefinitionInterface ;


  /**
   * Plugin implementation of the 'video' field type.
   *
   * @FieldType(
   *   id = "Video",
   *   label = @Translation("Video"),
   *   description = @Translation("Stores an video."),
   *   category = @Translation("Custom"),
   *   default_widget = "VideoDefaultWidget",
   *   default_formatter = "VideoDefaultFormatter"
   * )
   */
  class Video extends FieldItemBase {

    /**
    * {@inheritdoc}
    */
    public static function schema (FieldStorageDefinitionInterface $storage) {

      return [
        'columns' => [
          'url' => [
          'type' => 'text',
          'size' => 'tiny',
          'not null' => FALSE,
          ],
        ],
      ];
    }

  /**
   * {@inheritdoc}
   */
      public static function propertyDefinitions (FieldStorageDefinitionInterface $storage) {
        $properties['url'] = DataDefinition::create('string')
          ->setLabel(t('url'));
        return $properties;
      }

      /**
       * {@inheritdoc}
       */
      public function isEmpty() {
          $value = $this->get('url')->getValue();
          return $value === NULL || $value === '';
      }
}
