<?php

namespace Drupal\umd_terp_base;

use Drupal\Core\Url;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Routing\ResettableStackedRouteMatchInterface;

/**
 * Provides an edit toolbar button service for Drupal entity pages.
 *
 * This service determines if an edit button should be displayed in the admin toolbar
 * for canonical entity routes (nodes and taxonomy terms) and generates the appropriate
 * render array for the toolbar. It interacts with Drupal's local task system to find
 * available edit routes and builds toolbar menu items accordingly.
 *
 * Usage:
 *   $toolbar = \Drupal::service('umd_terp_base.toolbar');
 *   $edit_button = $toolbar->addEdit();
 *
 * @package Drupal\umd_terp_base
 */
class UmdTerpToolbar {

  use StringTranslationTrait;

  /**
   * Stores local task links for the current route.
   *
   * @var array
   */
  private $localLinks = [];

  /**
   * Local task manager service.
   *
   * @var \Drupal\Core\Menu\LocalTaskManagerInterface
   */
  private $localTaskManager;

  /**
   * Route match service.
   *
   * @var \Drupal\Core\Routing\ResettableStackedRouteMatchInterface
   */
  private $routeMatch;

  /**
   * Stores toolbar menu items to be rendered.
   *
   * @var array
   */
  private $toolbarMenuItems = [];

  /**
   * Stores the current local tasks for the route.
   *
   * @var array
   */
  private $currentLocalTasks;

  /**
   * Stores the current route name.
   *
   * @var array
   */
  private $currentRoute;

  /**
   * Constructs the UmdTerpToolbar service.
   *
   * @param \Drupal\Core\Menu\LocalTaskManagerInterface $localTaskManager
   *   The local task manager service.
   * @param \Drupal\Core\Routing\ResettableStackedRouteMatchInterface $routeMatch
   *   The route match service.
   */
  public function __construct(LocalTaskManagerInterface $localTaskManager, ResettableStackedRouteMatchInterface $routeMatch) {
    $this->localTaskManager = $localTaskManager;
    $this->routeMatch = $routeMatch;
    $this->setCurrentLocalTasks();
    $this->setCurrentRoute();
  }

  /**
   * Adds an edit button to the toolbar if the current route is an entity canonical route.
   *
   * @return array
   *   A render array for the toolbar edit button, or an empty array if not applicable.
   */
  public function addEdit() {
    if (!empty($this->currentLocalTasks)) {
      if (in_array(
        $this->currentRoute,
        ['entity.taxonomy_term.canonical', 'entity.node.canonical']
      )) {
        if ($this->localTaskExists('entity.node.edit_form')) {
          $this->toolbarMenuItems['toolbar_edit'] = $this->renderableButton(
            'entity.node.edit_form'
          );
        }
        elseif ($this->localTaskExists('entity.taxonomy_term.edit_form')) {
          $this->toolbarMenuItems['toolbar_edit'] = $this->renderableButton(
            'entity.taxonomy_term.edit_form'
          );
        }
      }
    }
    // Only return toolbarMenuItems if a real button was added.
    return !empty($this->toolbarMenuItems) ? $this->toolbarMenuItems : [];
  }

  /**
   * Builds a render array for the edit button for the given route.
   *
   * @param string $route
   *   The route name for the edit form.
   *
   * @return array
   *   A render array for the toolbar edit button.
   */
  private function renderableButton($route) {
    $content = $this->localLinks[$route];
    return [
      '#type' => 'toolbar_item',
      'tab' => [
        '#type' => 'link',
        '#title' => $this->t('Edit'),
        '#url' => Url::fromRoute(
          $route,
          $content['url']->getRouteParameters()
        ),
        '#attributes' => [
          'title' => $this->t('Edit'),
          'class' => [
            'toolbar-icon',
            'toolbar-icon-edit',
          ],
        ],
        '#cache' => [
          'contexts' => [
            'url.path',
          ],
        ],
      ],
      '#wrapper_attributes' => [
        'class' => ['edit-toolbar-tab'],
        'id' => 'edit-tab-button',
      ],
      '#weight' => 1000,
    ];
  }

  /**
   * Populates localLinks and currentLocalTasks based on the current route.
   */
  private function setCurrentLocalTasks() {
    $this->currentLocalTasks = $this->localTaskManager->getLocalTasks($this->routeMatch->getRouteName(), 0);

    if (!empty($this->currentLocalTasks)) {
      foreach ($this->currentLocalTasks['tabs'] as $route => $link) {
        $this->localLinks[$route] = $link['#link'];
      }
    }
  }

  /**
   * Sets the current route name from local tasks.
   */
  private function setCurrentRoute() {
    $this->currentRoute = $this->currentLocalTasks['route_name'];
  }

  /**
   * Checks if a local task exists for the current route.
   *
   * @param string $localTask
   *   The local task route name.
   *
   * @return bool
   *   TRUE if the local task exists, FALSE otherwise.
   */
  private function localTaskExists($localTask) {
    return in_array($localTask, array_keys($this->localLinks)) ? TRUE : FALSE;
  }

}
