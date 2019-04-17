<?php

require_once 'searchtaskbuilder.civix.php';
use CRM_Searchtaskbuilder_ExtensionUtil as E;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @return \Civi\Searchtaskbuilder\Provider
 */
function searchtaskbuilder_get_provider() {
  return \Civi::service('searchtaskbuilder_provider');
}

/**
 * Implements hook_civicrm_searchTasks().
 *
 * @param $objectType
 * @param $tasks
 */
function searchtaskbuilder_civicrm_searchTasks( $objectType, &$tasks ) {
  $searchTasks = civicrm_api3('SearchTask', 'get', array(
    'type' => $objectType,
    'is_active' => 1,
    'options' => array('limit' => 0),
  ));
  foreach($searchTasks['values'] as $searchTask) {
    $task = array();
    // We need this id later to determine which search task builder we need to instanciate
    $id = 'searchtaskbuilder_' . $searchTask['id'];

    $task['title'] = $searchTask['title'];
    $task['class'] = CRM_Searchtaskbuilder_Type::getClassNameByType($searchTask['type']);
    $task['result'] = false;
    $tasks[$id] = $task;
  }
}


/**
 * Implements hook_civicrm_container()
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_container/
 */
function searchtaskbuilder_civicrm_container(ContainerBuilder $container) {
  // Register the TypeFactory
  $container->setDefinition('searchtaskbuilder_provider', new Definition('Civi\Searchtaskbuilder\Provider'));
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function searchtaskbuilder_civicrm_config(&$config) {
  _searchtaskbuilder_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function searchtaskbuilder_civicrm_xmlMenu(&$files) {
  _searchtaskbuilder_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function searchtaskbuilder_civicrm_install() {
  _searchtaskbuilder_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function searchtaskbuilder_civicrm_postInstall() {
  _searchtaskbuilder_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function searchtaskbuilder_civicrm_uninstall() {
  _searchtaskbuilder_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function searchtaskbuilder_civicrm_enable() {
  _searchtaskbuilder_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function searchtaskbuilder_civicrm_disable() {
  _searchtaskbuilder_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function searchtaskbuilder_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _searchtaskbuilder_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function searchtaskbuilder_civicrm_managed(&$entities) {
  _searchtaskbuilder_civix_civicrm_managed($entities);
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
function searchtaskbuilder_civicrm_caseTypes(&$caseTypes) {
  _searchtaskbuilder_civix_civicrm_caseTypes($caseTypes);
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
function searchtaskbuilder_civicrm_angularModules(&$angularModules) {
  _searchtaskbuilder_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function searchtaskbuilder_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _searchtaskbuilder_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function searchtaskbuilder_civicrm_entityTypes(&$entityTypes) {
  _searchtaskbuilder_civix_civicrm_entityTypes($entityTypes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function searchtaskbuilder_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 */
function searchtaskbuilder_civicrm_navigationMenu(&$menu) {
  _searchtaskbuilder_civix_insert_navigation_menu($menu, 'Administer', array(
    'label' => E::ts('Search Task Builder'),
    'name' => 'search_task_builder',
    'url' => CRM_Utils_System::url('civicrm/searchtaskbuilder/manage', 'reset=1', true),
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _searchtaskbuilder_civix_navigationMenu($menu);
}
