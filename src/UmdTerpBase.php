<?php

namespace Drupal\umd_terp_base;

use GuzzleHttp\Client;
use Drupal\Component\Serialization\Json;

/**
 * Provides static methods for interacting with UMD Today and UMD Calendar APIs.
 *
 * This class contains utility functions for fetching news and event data,
 * formatting taxonomy responses, and building GraphQL queries for Drupal modules.
 *
 * Usage:
 *   $news = UmdTerpBase::middleware_get_news($query);
 *   $events = UmdTerpBase::middleware_get_events($query);
 *   $formatted = UmdTerpBase::middleware_format_taxonomy($response);
 *
 * @package Drupal\umd_terp_base
 */
class UmdTerpBase {

  /**
   * Gets taxonomies/term IDs/etc from UMD Today for News.
   *
   * @param string $query
   *   The GraphQL query string.
   *
   * @return array|null
   *   The decoded response from the API, or NULL on error.
   */
  public static function middleware_get_news($query) {
    $umd_terp_base_settings = \Drupal::config('umd_terp_base.settings');
    $news_api_bearer_token = $umd_terp_base_settings->get('umd_terp_base.news_api_token');
    if (!empty($news_api_bearer_token)) {
      $graphQLquery = '{"query": "query ' . $query . '"}';
      $response = (new Client)->request('post', 'https://today.umd.edu/graphql', [
        'headers' => [
          'Authorization' => 'Bearer ' . $news_api_bearer_token,
          'Content-Type' => 'application/json',
        ],
        'body' => $graphQLquery,
      ]);
      $result = Json::decode($response->getBody());
      return $result;
    }
    else {
      $message = 'Please set or check the UMD Today News API Bearer token on the UMD Terp modules configuration page.';
      \Drupal::logger('umd_terp_base')->alert($message);
      \Drupal::messenger()->addError($message);
      return;
    }
  }

  /**
   * Gets taxonomies/term IDs/etc from UMD Calendar for Events.
   *
   * @param string $query
   *   The GraphQL query string.
   *
   * @return array|null
   *   The decoded response from the API, or NULL on error.
   */
  public static function middleware_get_events($query) {
    $umd_terp_base_settings = \Drupal::config('umd_terp_base.settings');
    $calendar_api_bearer_token = $umd_terp_base_settings->get('umd_terp_base.calendar_api_token');
    if (!empty($calendar_api_bearer_token)) {
      $graphQLquery = '{"query": "query ' . $query . '"}';
      $response = (new Client)->request('post', 'https://calendar.umd.edu/graphql', [
        'headers' => [
          'Authorization' => 'Bearer ' . $calendar_api_bearer_token,
          'Content-Type' => 'application/json',
        ],
        'body' => $graphQLquery,
      ]);
      $result = Json::decode($response->getBody());
      return $result;
    }
    else {
      $message = 'Please set or check the UMD Today Calendar API Bearer token on the UMD Terp modules configuration page.';
      \Drupal::logger('umd_terp_base')->alert($message);
      \Drupal::messenger()->addError($message);
      return;
    }
  }

  /**
   * Formats taxonomy terms from API response.
   *
   * Flattens the response and ensures all values are strings.
   *
   * @param array $response
   *   The API response containing taxonomy data.
   *
   * @return array
   *   An array of formatted taxonomy terms with 'value' and 'label'.
   */
  public static function middleware_format_taxonomy($response) {
    $collection = [];
    foreach ($response['data']['categories'] as $entry) {
      $collection[] = [
        'value' => $entry['id'],
        'label' => $entry['title'],
      ];
    }
    return $collection;
  }

  /**
   * Builds a GraphQL query for news taxonomy terms and fetches them.
   *
   * @param string $taxonomy
   *   The taxonomy group name.
   *
   * @return array|null
   *   The decoded response from the API, or NULL on error.
   */
  public static function middleware_get_news_taxonomy($taxonomy) {
    $query = 'getCategoriesByType { categories(group: \\"' . $taxonomy . '\\") { title id }}';
    return self::middleware_get_news($query);
  }

  /**
   * Builds a GraphQL query for events taxonomy terms and fetches them.
   *
   * @param string $taxonomy
   *   The taxonomy group name.
   *
   * @return array|null
   *   The decoded response from the API, or NULL on error.
   */
  public static function middleware_get_events_taxonomy($taxonomy) {
    $query = 'getCategoriesByType { categories(group: \\"' . $taxonomy . '\\") { title id }}';
    return self::middleware_get_events($query);
  }

}
