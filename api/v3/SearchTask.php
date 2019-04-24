<?php
use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * SearchTask.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_search_task_create_spec(&$spec) {
  $fields = CRM_Searchactiondesigner_BAO_SearchTask::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
    if ($fieldname != 'id' && isset($field['required']) && $field['required']) {
      $spec[$fieldname]['api.required'] = true;
    }
  }
}

/**
 * SearchTask.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_create($params) {
  $id = null;
  if (isset($params['id'])) {
    $id = $params['id'];
  }
  $params['name'] = CRM_Searchactiondesigner_BAO_SearchTask::checkName($params['title'], $id, $params['name']);
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * SearchTask.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * SearchTask.get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_search_task_get_spec(&$spec) {
  $fields = CRM_Searchactiondesigner_BAO_SearchTask::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
  }
}

/**
 * SearchTask.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * SearchTask.check_name API specification
 *
 * @param $params
 */
function _civicrm_api3_search_task_check_name_spec($params) {
  $params['id'] = array(
    'name' => 'id',
    'title' => E::ts('ID'),
  );
  $params['title'] = array(
    'name' => 'title',
    'title' => E::ts('Title'),
    'api.required' => true,
  );
  $params['name'] = array(
    'name' => 'name',
    'title' => E::ts('Name'),
  );
}

/**
 * SearchTaskAction.check_name API
 *
 * @param $params
 */
function civicrm_api3_search_task_check_name($params) {
  $name = CRM_Searchactiondesigner_BAO_SearchTask::checkName($params['title'], $params['id'], $params['name']);
  return array(
    'name' => $name,
  );
}

/**
 * SearchTask.Import API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_search_task_import_spec(&$spec) {
}

/**
 * SearchTask.Import API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_search_task_import($params) {
  $returnValues = array();
  $returnValues['import'] = CRM_Searchactiondesigner_Importer::importFromExtensions();
  $returnValues['is_error'] = 0;
  return $returnValues;
}
