# Hooks

This extension implements several hooks.

## hook_civicrm_search_task_types

This hook is called to build a list with available search task types. 
Each search task is assigned to a certain type, e.g. A contact search or an activity search.

### Example

```php

function hook_civicrm_search_task_types(&$types) {
  // Add the possibility to build search tasks to my custom search 
  // with the object type 'my_search_type'
  // The object type must be the same as the object type used in hook_civicrm_searchTasks
  $types['my_search_type'] = array(
    'title' => ts('This is a custom search type'),
  ); 
}

```