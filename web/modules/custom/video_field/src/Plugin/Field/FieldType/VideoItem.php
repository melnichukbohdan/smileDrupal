<?php

namespace Drupal\video_field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;


/**
 * Plugin implementation of the 'you_tube_field' field type.
 *
 * @FieldType(
 *   id = "video_field",
 *   label = @Translation ("Video URL"),
 *   module = "video_field",
 *   description = @Translation("Output video"),
 *   default_widget = "video_field_widget",
 *   default_formatter = "video_field_formatter"
 * )
 */
class VideoItem extends FieldItemBase {

  /**
   * @inheritDoc
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => 2083,
          'not null' => FALSE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * @inheritDoc
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties = DataDefinition::create('string')
      ->setLabel(t('Video Url'));

    return $properties;
  }
}
