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
    $task = array();
    // We need this id later to determine which search task builder we need to instanciate
    $id = 'searchactiondesigner_' . $searchTask['id'];

    $task['title'] = $searchTask['title'];
    $task['class'] = CRM_Searchactiondesigner_Type::getClassNameByType($searchTask['type']);
    $task['result'] = false;
    $tasks[$id] = $task;
  }
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

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function searchactiondesigner_civicrm_managed(&$entities) {
  $unmet = CRM_Searchactiondesigner_Upgrader::checkExtensionDependencies();
  CRM_Searchactiondesigner_Upgrader::displayDependencyErrors($unmet);
  if (!count($unmet)) {
    $imported = CRM_Searchactiondesigner_Importer::importFromExtensions();
    $importedTitles = array();
    foreach($imported as $import) {
      $importedTitles[] = $import['title'];
    }
    if (count($importedTitles)) {
      CRM_Core_Session::setStatus(E::ts("Search actions imported: <br>-&nbsp;%1", array(1=>implode("<br>-&nbsp;", $importedTitles))), E::ts("Imported Search Actions"), 'success' );
    }
  }
  _searchactiondesigner_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function searchactiondesigner_civicrm_xmlMenu(&$files) {
  $imported = CRM_Searchactiondesigner_Importer::importFromExtensions();
  $importedTitles = array();
  foreach($imported as $import) {
    $importedTitles[] = $import['title'];
  }
  if (count($importedTitles)) {
    CRM_Core_Session::setStatus(E::ts("Search actions imported: <br>-&nbsp;%1", array(1=>implode("<br>-&nbsp;", $importedTitles))), E::ts("Imported Search Actions"), 'success' );
  }
  _searchactiondesigner_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function searchactiondesigner_civicrm_postInstall() {
  _searchactiondesigner_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function searchactiondesigner_civicrm_uninstall() {
  _searchactiondesigner_civix_civicrm_uninstall();
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
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function searchactiondesigner_civicrm_disable() {
  _searchactiondesigner_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function searchactiondesigner_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _searchactiondesigner_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function searchactiondesigner_civicrm_caseTypes(&$caseTypes) {
  _searchactiondesigner_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function searchactiondesigner_civicrm_angularModules(&$angularModules) {
  _searchactiondesigner_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function searchactiondesigner_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _searchactiondesigner_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function searchactiondesigner_civicrm_entityTypes(&$entityTypes) {
  _searchactiondesigner_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function searchactiondesigner_civicrm_preProcess($formName, &$form) {

} // */
