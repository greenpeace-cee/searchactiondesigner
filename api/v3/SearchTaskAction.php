<?php
use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * SearchTaskAction.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_search_task_action_create_spec(&$spec) {
  $fields = CRM_Searchactiondesigner_BAO_SearchTaskAction::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
    if ($fieldname != 'id' && isset($field['required']) && $field['required']) {
      $spec[$fieldname]['api.required'] = true;
    }
  }
}

/**
 * SearchTaskAction.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_action_create($params) {
  if (!isset($params['weight']) && !isset($params['id'])) {
    $params['weight'] = CRM_Utils_Weight::getDefaultWeight('CRM_Searchactiondesigner_DAO_SearchTaskAction', array('search_task_id' => $params['search_task_id']));
  }
  $id = null;
  if (isset($params['id'])) {
    $id = $params['id'];
  }
  $params['name'] = CRM_Searchactiondesigner_BAO_SearchTaskAction::checkName($params['title'], $params['search_task_id'], $id, $params['name']);
  $return = _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
  CRM_Searchactiondesigner_BAO_SearchTask::updateAndChekStatus($params['search_task_id']);
  return $return;
}

/**
 * SearchTaskAction.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_action_delete($params) {
  $data = civicrm_api3('SearchTaskAction', 'getsingle', array('id' => $params['id']));
  CRM_Searchactiondesigner_BAO_SearchTask::updateAndChekStatus($data['search_task_id']);
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * SearchTaskAction.get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_search_task_action_get_spec(&$spec) {
  $fields = CRM_Searchactiondesigner_BAO_SearchTaskAction::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
  }
}

/**
 * SearchTaskAction.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_action_get($params) {
  if (!isset($params['options']) || !isset($params['options']['sort'])) {
    $params['options']['sort'] = 'weight ASC';
  }
  $return = _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
  foreach($return['values'] as $id => $value) {
    if (isset($value['configuration'])) {
      $return['values'][$id]['configuration'] = json_decode($value['configuration'], TRUE);
    }
    if (isset($value['mapping'])) {
      $return['values'][$id]['mapping'] = json_decode($value['mapping'], TRUE);
    }
  }
  return $return;
}

/**
 * SearchTaskAction.check_name API specification
 *
 * @param $params
 */
function _civicrm_api3_search_task_action_check_name_spec($params) {
  $params['id'] = array(
    'name' => 'id',
    'title' => E::ts('ID'),
  );
  $params['title'] = array(
    'name' => 'title',
    'title' => E::ts('Title'),
    'api.required' => true,
  );
  $params['search_task_id'] = array(
    'name' => 'search_task_id',
    'title' => E::ts('Search Task Id'),
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
function civicrm_api3_search_task_action_check_name($params) {
  $name = CRM_Searchactiondesigner_BAO_SearchTaskAction::checkName($params['title'], $params['search_task_id'], $params['id'], $params['name']);
  return array(
    'name' => $name,
  );
}
