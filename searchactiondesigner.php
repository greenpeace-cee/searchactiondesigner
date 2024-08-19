<?php

require_once 'searchactiondesigner.civix.php';
use CRM_Searchactiondesigner_ExtensionUtil as E;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @return \Civi\FormFieldLibrary\Library
 */
function searchactiondesigner_get_form_field_library() {
  return \Civi::service('formfieldlibrary');
}

/**
 * @return \Civi\ActionProvider\Provider
 */
function searchactiondesigner_get_action_provider() {
  $container = \Civi::container();
  if ($container->has('action_provider')) {
    $action_provider_container = $container->get('action_provider');
    return $action_provider_container->getProviderByContext('searchactiondesigner');
  }
  return null;
}

/**
 * Implements hook_civicrm_searchTasks().
 *
 * @param $objectType
 * @param $tasks
 */
function searchactiondesigner_civicrm_searchTasks( $objectType, &$tasks ) {
  $searchTasks = civicrm_api3('SearchTask', 'get', array(
    'type' => $objectType,
    'is_active' => 1,
    'options' => array('limit' => 0),
  ));
  foreach($searchTasks['values'] as $searchTask) {
    if (!empty($searchTask['permission']) && !\CRM_Core_Permission::check($searchTask['permission'])) {
      continue;
    }
    $task = array();
    // We need this id later to determine which search task builder we need to instanciate
    $id = 'searchactiondesigner_' . $searchTask['id'];

    $task['title'] = $searchTask['title'];
    $task['class'] = CRM_Searchactiondesigner_Type::getClassNameByType($searchTask['type']);
    $task['result'] = false;
    // Support for standalone actions (e.g. from SearchKit)
    if ($objectType == 'contact') {
      $task['url'] = 'civicrm/searchactiondesigner/form/task/contact?searchactiondesigner_id=' . $searchTask['id'];
    }
    else {
      $task['url'] = 'civicrm/searchactiondesigner/form/task/generic?searchactiondesigner_id=' . $searchTask['id'];
    }
    $tasks[$id] = $task;
  }
}

function searchactiondesigner_civicrm_summaryActions(array &$actions, int $contactId = null) {
  if (empty($contactId)) {
    return;
  }
  $searchTasks = civicrm_api3('SearchTask', 'get', array(
    'type' => 'contact',
    'is_active' => 1,
    'options' => array('limit' => 0),
  ));
  foreach($searchTasks['values'] as $searchTask) {
    if (!empty($searchTask['permission']) && !\CRM_Core_Permission::check($searchTask['permission'])) {
      continue;
    }
    if (empty($searchTask['configuration']) && empty($searchTask['configuration']['summary'])) {
      continue;
    }
    $section = $searchTask['configuration']['summary'];
    if ($section && $section == 'primaryActions') {
      $section = '';
    }
    $weight = 0;
    if (isset($searchTask['configuration']['weight'])) {
      $weight = $searchTask['configuration']['weight'];
    }
    $task = array();
    // We need this id later to determine which search task builder we need to instanciate
    $id = 'searchactiondesigner_' . $searchTask['name'];

    $task['title'] = $searchTask['title'];
    $task['description'] = $searchTask['description'] ?? '';
    $task['ref'] = 'crm-contact-' . $id;
    $task['class'] = $id;
    $task['key'] = $id;
    $task['tab'] = $id;
    $task['href'] = CRM_Utils_System::url('civicrm/searchactiondesigner/form/task/contact', ['searchactiondesigner_id' => $searchTask['id'], 'standalone' => 1, 'cids' => $contactId]);
    $task['weight'] = $weight;
    if ($section) {
      $actions[$section][$id] = $task;
    } else {
      $actions[$id] = $task;
    }
  }
}

function searchactiondesigner_civicrm_caseSummary(int $caseId) {
  $searchTasks = civicrm_api3('SearchTask', 'get', array(
    'type' => 'case',
    'is_active' => 1,
    'options' => array('limit' => 0),
  ));
  $actions = [];
  $taskCount = 0;
  foreach($searchTasks['values'] as $searchTask) {
    if (!empty($searchTask['permission']) && !\CRM_Core_Permission::check($searchTask['permission'])) {
      continue;
    }
    if (empty($searchTask['configuration']) || empty($searchTask['configuration']['summary'])) {
      continue;
    }
    $weight = 0;
    $taskCount ++;
    if (isset($searchTask['configuration']['weight'])) {
      $weight = $searchTask['configuration']['weight'];
    }
    $task = array();
    // We need this id later to determine which search task builder we need to instanciate
    $id = 'searchactiondesigner_' . $searchTask['name'];

    $task['title'] = $searchTask['title'];
    $task['description'] = $searchTask['description'] ?? '';
    $task['class'] = $id;
    $task['href'] = CRM_Utils_System::url('civicrm/searchactiondesigner/form/task/case', ['searchactiondesigner_id' => $searchTask['id'], 'standalone' => '1', 'case_ids' => $caseId]);
    $task['weight'] = $weight;
    $actions[$weight][] = $task;
  }
  ksort($actions);
  $smarty = CRM_Core_Smarty::singleton();
  $vars['actions'] = $actions;
  $vars['taskCount'] = $taskCount;
  $smarty->pushScope($vars);
  $content = $smarty->fetch('CRM/Searchactiondesigner/CaseSummary.tpl');
  $smarty->popScope();
  return array('searchactiondesigner' => array('value' => $content, 'label' => E::ts('Tasks')));
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function searchactiondesigner_civicrm_navigationMenu(&$menu) {
  _searchactiondesigner_civix_insert_navigation_menu($menu, 'Administer/Customize Data and Screens', array(
    'label' => E::ts('Search Action Designer'),
    'name' => 'searchactiondesigner',
    'url' => CRM_Utils_System::url('civicrm/searchactiondesigner/manage', 'reset=1', true),
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _searchactiondesigner_civix_navigationMenu($menu);
}

/**
 * Implements hook_civicrm_container()
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function searchactiondesigner_civicrm_container(ContainerBuilder $container) {
  // Register the TypeFactory
  $definition = new Definition('Civi\Searchactiondesigner\Library');
  $definition->setPublic(true);
  if (method_exists(Definition::class, 'setPrivate')) {
    $definition->setPrivate(FALSE);
  }
  $container->setDefinition('searchactiondesigner_provider', $definition);
}

/**
 * Implementation of hook_civicrm_pageRun
 *
 * Handler for pageRun hook.
 */
function searchactiondesigner_civicrm_pageRun(&$page) {
  if ($page instanceof CRM_Admin_Page_Extensions) {
    _searchactiondesigner_prereqCheck();
  }
}

function _searchactiondesigner_prereqCheck() {
  $unmet = CRM_Searchactiondesigner_Upgrader::checkExtensionDependencies();
  CRM_Searchactiondesigner_Upgrader::displayDependencyErrors($unmet);
}

function _searchactiondesigner_import() {
  $unmet = CRM_Searchactiondesigner_Upgrader::checkExtensionDependencies();
  // following test prevents an error after an upgrade
  if (!count($unmet) && \Civi\Core\Container::singleton()->has('formfieldlibrary')) {
    $imported = CRM_Searchactiondesigner_Importer::importFromExtensions();
    $importedTitles = array();
    foreach($imported as $import) {
      $importedTitles[] = $import['title'];
    }
    if (count($importedTitles)) {
      CRM_Core_Session::setStatus(E::ts("Search actions imported: <br>-&nbsp;%1", array(1=>implode("<br>-&nbsp;", $importedTitles))), E::ts("Imported Search Actions"), 'success' );
    }
  }
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function searchactiondesigner_civicrm_managed(&$entities) {
  _searchactiondesigner_import();
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function searchactiondesigner_civicrm_config(&$config) {
  _searchactiondesigner_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function searchactiondesigner_civicrm_install() {
  _searchactiondesigner_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function searchactiondesigner_civicrm_enable() {
  _searchactiondesigner_civix_civicrm_enable();
}

/**
 * Implements hook_search_action_designer_types().
 *
 * Makes all APIv4 entities available as actions for use in SearchKit
 */
function searchactiondesigner_search_action_designer_types(&$types) {
  try {
    $searchKit = civicrm_api3('Extension', 'get', [
      'key' => 'org.civicrm.search_kit',
      'status' => 'installed',
    ]);
    if (!empty($searchKit['values'])) {
      $entities = civicrm_api4('Entity', 'get', [
        'where' => [
          ['searchable', '!=', 'none'],
        ],
        'checkPermissions' => FALSE,
      ]);
      foreach ($entities as $entity) {
        $types['search_kit_' . $entity['name']] = [
          'title' => E::ts('Search Kit: %1', [1 => $entity['title_plural']]),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Task',
          'id_field_title' => E::ts('%1 ID', [1 => $entity['title']]),
        ];
      }
    }
  } catch (Throwable $e) {
    // Do nothing.
  }
}

/**
 * Implements hook_civicrm_searchKitTasks().
 *
 * Generate list of tasks for SearchKit
 *
 * @param array $tasks
 * @param bool $checkPermissions
 * @param int $userId
 */
function searchactiondesigner_civicrm_searchKitTasks(&$tasks, $checkPermissions, $userId) {
  try {
    $searchTasks = civicrm_api3('SearchTask', 'get', [
      'is_active' => 1,
      'options' => ['limit' => 0],
      'type' => ['LIKE' => 'search_kit_%'],
    ]);
    foreach ($searchTasks['values'] as $searchTask) {
      if (!empty($searchTask['permission']) && !\CRM_Core_Permission::check($searchTask['permission'])) {
        continue;
      }
      $task = [];
      $id = 'searchactiondesigner_' . $searchTask['id'];
      $entity = substr($searchTask['type'], strlen('search_kit_'));

      $task['title'] = $searchTask['title'];
      $task['icon'] = 'fa-gears';
      $task['crmPopup'] = [
        'path' => "'civicrm/searchactiondesigner/form/task/generic'",
        'query' => "{searchactiondesigner_id: {$searchTask['id']}, reset: 1, id: ids.join(',')}",
      ];
      $tasks[$entity][$id] = $task;
    }
  } catch (\CiviCRM_API3_Exception $e) {
    // Do nothing.
  }
}
