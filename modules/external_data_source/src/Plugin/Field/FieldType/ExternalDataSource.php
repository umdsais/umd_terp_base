<?php

namespace Drupal\external_data_source\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'external_data_source' field type.
 *
 * @FieldType(
 *   id = "external_data_source",
 *   label = "External Data Source Field",
 *   description = "External Data Source Field Type",
 *   category = "External Data Source Fields",
 *   default_widget = "external_data_source_select_widget",
 *   default_formatter = "external_data_source_formatter",
 *   list_class = "\Drupal\Core\Field\FieldItemList",
 * )
 */
class ExternalDataSource extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'max_length' => 255,
        // Default ws.
      'ws' => 'audience',
        // Default default number of results.
      'count' => 10,
      'is_ascii' => FALSE,
      'case_sensitive' => FALSE,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['value'] = DataDefinition::create('string')
      ->setLabel('Text value')
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
          'binary' => $field_definition->getSetting('case_sensitive'),
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getConstraints() {
    $constraints = parent::getConstraints();

    if ($max_length = $this->getSetting('max_length')) {
      $constraint_manager = \Drupal::typedDataManager()
        ->getValidationConstraintManager();
      $constraints[] = $constraint_manager->create('ComplexData', [
        'value' => [
          'Length' => [
            'max' => $max_length,
            'maxMessage' => $this->t('%name: may not be longer than @max characters.', [
              '%name' => $this->getFieldDefinition()->getLabel(),
              '@max' => $max_length,
            ]),
          ],
        ],
      ]);
    }

    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['value'] = $random->word(mt_rand(1, $field_definition->getSetting('max_length')));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    // Detect all external_ws_source available Plugins.
    $type = \Drupal::service('plugin.manager.external_data_source');
    $plugin_definitions = $type->getDefinitions();
    $plugins = [];
    if (count($plugin_definitions)) {
      foreach ($plugin_definitions as $plugin) {
        $plugins[$plugin['id']] = $plugin['name']->__toString()
          . ' - ' . $plugin['description']->__toString();
      }
    }
    $elements = [];
    $elements['ws'] = [
      '#type' => 'select',
      '#title' => $this->t('External Data Source'),
      '#default_value' => $this->getSetting('ws'),
      '#options' => $plugins,
      '#description' => $this->t('The External Data Source'),
      '#required' => TRUE,
      // If there are already entries we can not change the ws anymore.
      '#disabled' => $has_data,
    ];
    $elements['count'] = [
      '#type' => 'number',
      '#title' => $this->t('Max result count'),
      '#default_value' => $this->getSetting('count') ? $this->getSetting('count') : 10,
      '#required' => TRUE,
      '#min' => 1,
      // If there are already entries we can not change the ws anymore.
      '#disabled' => $has_data,
    ];
    $elements['max_length'] = [
      '#type' => 'number',
      '#title' => $this->t('Maximum length'),
      '#default_value' => $this->getSetting('max_length'),
      '#required' => TRUE,
      '#description' => $this->t('The maximum length of the field in characters.'),
      '#min' => 1,
      '#disabled' => $has_data,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('value')->getValue();
    return $value === NULL || $value === '';
  }

}
