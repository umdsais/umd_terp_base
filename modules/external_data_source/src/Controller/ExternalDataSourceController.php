<?php

namespace Drupal\external_data_source\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\external_data_source\Plugin\ExternalDataSourceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\external_data_source\Plugin\ExternalDataSourceManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Masterminds\HTML5\Parser\UTF8Utils;

/**
 * Controller for External Data Source plugin endpoints.
 *
 * Provides endpoints for plugin autocomplete and formatting plugin responses
 * for select field options in Drupal forms.
 *
 * @package Drupal\external_data_source\Controller
 */
class ExternalDataSourceController extends ControllerBase {

  /**
   * The plugin manager for external data source plugins.
   *
   * @var \Drupal\external_data_source\Plugin\ExternalDataSourceManager
   */
  protected $pluginManagerExternalWsSource;

  /**
   * Constructs the controller with the plugin manager.
   *
   * @param \Drupal\external_data_source\Plugin\ExternalDataSourceManager $plugin_manager_external_ws_source
   *   The plugin manager service.
   */
  public function __construct(ExternalDataSourceManager $plugin_manager_external_ws_source) {
    $this->pluginManagerExternalWsSource = $plugin_manager_external_ws_source;
  }

  /**
   * {@inheritdoc}
   *
   * Dependency injection factory method.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.external_data_source')
    );
  }

  /**
   * Provides autocomplete for external data source plugins.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The HTTP request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing plugin options.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   *   If the requested plugin is not found.
   */
  public function autocomplete(Request $request) {
    $requestedPlugin = $request->query->get('plugin_name');
    $plugin_definitions = $this->pluginManagerExternalWsSource->getDefinitions();
    $plugins = [];
    if (count($plugin_definitions)) {
      foreach ($plugin_definitions as $plugin) {
        $plugins[$plugin['id']] = (string) $plugin['name']
          . ' - ' . (string) $plugin['description'];
      }
    }
    if (!array_key_exists($requestedPlugin, $plugins)) {
      throw new NotFoundHttpException();
    }
    $pluginInstance = new $plugin_definitions[$requestedPlugin]['class']();
    $pluginInstance->setRequest($request);
    return new JsonResponse($pluginInstance->getResponse());
  }

  /**
   * Formats plugin response for select/checkbox field options.
   *
   * @param \Drupal\external_data_source\Plugin\ExternalDataSourceInterface $pluginInstance
   *   The plugin instance.
   *
   * @return array
   *   An array of options for form fields.
   *
   * @throws \Masterminds\HTML5\Exception
   */
  public function optionsForSelect(ExternalDataSourceInterface $pluginInstance) {
    $response = $pluginInstance->getResponse();
    $options = [];
    foreach ($response as $key => $value) {
      $options[UTF8Utils::convertToUTF8((string) $value['value'])] = UTF8Utils::convertToUTF8((string) $value['label']);
    }
    return $options;
  }

}
