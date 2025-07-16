# ExternalDataSourceController Documentation

## Overview

`ExternalDataSourceController` is a controller class for the External Data Source Drupal module. It provides endpoints for plugin autocomplete and for formatting plugin responses as select field options.

## Class: ExternalDataSourceController

### Properties

- **pluginManagerExternalWsSource**: The plugin manager for external data source plugins.

### Methods

- **__construct(ExternalDataSourceManager $plugin_manager_external_ws_source)**: Injects the plugin manager service.

- **create(ContainerInterface $container)**: Factory method for dependency injection.

- **autocomplete(Request $request)**: Returns a JSON response for plugin autocomplete, listing available plugins and their descriptions.

- **optionsForSelect(ExternalDataSourceInterface $pluginInstance)**: Returns a formatted array of options for use in select or checkbox fields, converting values and labels to UTF-8.

## Usage

Use this controller to provide AJAX endpoints for plugin selection and to format plugin data for form fields in Drupal.

## See Also

- [Drupal Controller API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Controller%21ControllerBase.php/class/ControllerBase/8.2.x)
- [Symfony HTTP Foundation](https://symfony.com/doc/current/components/http_foundation.html)
- [Drupal Plugin API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Component%21Plugin%21PluginManagerInterface.php/interface/PluginManagerInterface/8.2.x)
