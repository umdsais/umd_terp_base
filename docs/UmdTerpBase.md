# UmdTerpBase Documentation

## Overview

`UmdTerpBase` is a static utility class for the UMD Terp Base Drupal module. It provides methods for interacting with UMD Today and UMD Calendar APIs, formatting taxonomy responses, and building GraphQL queries for use in Drupal modules.

## Class: UmdTerpBase


### Methods

- **middleware_get_news($query)**: Fetches news data from the UMD Today API using a GraphQL query string. Requires a valid API bearer token in module configuration.

- **middleware_get_events($query)**: Fetches event data from the UMD Calendar API using a GraphQL query string. Requires a valid API bearer token in module configuration.

- **middleware_format_taxonomy($response)**: Flattens and formats taxonomy term data from an API response into an array of value/label pairs.

- **middleware_get_news_taxonomy($taxonomy)**: Builds and executes a GraphQL query to fetch news taxonomy terms for a given group.

- **middleware_get_events_taxonomy($taxonomy)**: Builds and executes a GraphQL query to fetch event taxonomy terms for a given group.


## Usage

Call the static methods to fetch and format data for use in Drupal modules. Ensure API tokens are set in the module configuration before use.


## See Also

- [Guzzle HTTP Client](https://docs.guzzlephp.org/en/stable/)
- [Drupal Configuration API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Config%21Config.php/class/Config/8.2.x)
- [Drupal Messenger API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Messenger%21MessengerInterface.php/interface/MessengerInterface/8.2.x)
- [Drupal Logger API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Logger%21LoggerChannelFactoryInterface.php/interface/LoggerChannelFactoryInterface/8.2.x)
