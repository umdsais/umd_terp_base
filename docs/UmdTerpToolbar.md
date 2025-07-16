# UmdTerpToolbar Documentation

## Overview

`UmdTerpToolbar` is a Drupal service class that provides logic for rendering an edit button in the toolbar for entity pages. It interacts with Drupal's local task system to determine if an edit button should be displayed for the current route, and generates the appropriate render array for the toolbar.

## Class: UmdTerpToolbar


### Properties

- **localLinks**: Stores local task links for the current route.

- **localTaskManager**: Injected service for managing local tasks.

- **routeMatch**: Injected service for matching the current route.

- **toolbarMenuItems**: Stores toolbar menu items to be rendered.

- **currentLocalTasks**: Stores the current local tasks for the route.

- **currentRoute**: Stores the current route name.


### Methods

- **__construct(LocalTaskManagerInterface, ResettableStackedRouteMatchInterface)**: Injects required services and initializes local tasks and route.

- **addEdit()**: Adds an edit button to the toolbar if the current route is an entity canonical route and the edit form local task exists.

- **renderableButton($route)**: Returns a render array for the edit button for the given route.

- **setCurrentLocalTasks()**: Populates localLinks and currentLocalTasks based on the current route.

- **setCurrentRoute()**: Sets the current route name from local tasks.

- **localTaskExists($localTask)**: Checks if a local task exists for the current route.


## Usage

This service is typically used to add an edit button to the Drupal admin toolbar for entity pages, such as nodes or taxonomy terms, when the user has permission to edit the entity.

## Extending

To extend or modify the behavior, override the relevant methods or inject additional services as needed.


## See Also

- [Drupal Local Task API](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Menu%21LocalTaskManagerInterface.php/interface/LocalTaskManagerInterface/8.2.x)

- [Drupal Toolbar API](https://api.drupal.org/api/drupal/core%21modules%21toolbar%21toolbar.api.php/group/toolbar/8.2.x)
