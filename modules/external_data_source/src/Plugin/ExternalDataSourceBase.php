<?php

namespace Drupal\external_data_source\Plugin;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Base class for External Data Source plugins.
 */
abstract class ExternalDataSourceBase implements ExternalDataSourceInterface {
  use StringTranslationTrait;

  /**
   * The request from the controller.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  public $request;

  /**
   * Requested result count.
   *
   * @var int
   */
  public $count;

  /**
   * Requested result string search query.
   *
   * @var string
   */
  public $q;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->pluginDefinition['name'];
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->pluginDefinition['description'];
  }

  /**
   * GetResponse
   * Call WS to retrieve data.
   */
  abstract public function getResponse();

  /**
   * GetRequest
   * getting sent request.
   *
   * @return \Symfony\Component\HttpFoundation\Request $request
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * Detect & convert special char to UTF8.
   *
   * @param array $data
   *
   * @return array
   */
  public function sanitizeArray(array $data) {
    $stringCleaner = new UTF8Utils();
    $cleanOptions = [];
    foreach ($data as $key => $value) {
      $cleanOptions[$stringCleaner::convertToUTF8($key)] = $stringCleaner::convertToUTF8($value);
    }
    return $cleanOptions;
  }

  /**
   * FormatResponse.
   *
   * @param array $response
   *   Formatting data retrieved from ws to match [{"value":"","label":""},
   *   {"value":"", "label":""}] return array $collection retrieved suggestions.
   *
   * @return array $collection
   */
  public function formatResponse(array $response) {
    $collection = [];
    foreach ($response as $entry) {
      $collection[] = [
        'value' => (string) $entry->label,
        'label' => (string) $entry->label . ' (' . (string) $entry->value . ')',
      ];
    }
    return $collection;
  }

}
