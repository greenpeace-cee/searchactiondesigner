<?php
use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * SearchTaskField.create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_search_task_field_create_spec(&$spec) {
  $fields = CRM_Searchtaskbuilder_BAO_SearchTaskField::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
    if ($fieldname != 'id' && isset($field['required']) && $field['required']) {
      $spec[$fieldname]['api.required'] = true;
    }
  }
}

/**
 * SearchTaskField.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_field_create($params) {
  if (!isset($params['weight']) && !isset($params['id'])) {
    $params['weight'] = CRM_Utils_Weight::getDefaultWeight('CRM_Searchtaskbuilder_DAO_SearchTaskField', array('search_task_id' => $params['search_task_id']));
  }
  $id = null;
  if (isset($params['id'])) {
    $id = $params['id'];
  }
  $params['name'] = CRM_Searchtaskbuilder_BAO_SearchTaskField::checkName($params['title'], $params['search_task_id'], $id, $params['name']);
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
 * SearchTaskField.delete API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_field_delete($params) {
  return _civicrm_api3_basic_delete(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

/**
* SearchTaskField.get API specification (optional)
* This is used for documentation and validation.
*
* @param array $spec description of fields supported by this API call
* @return void
* @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
*/
function _civicrm_api3_search_task_field_get_spec(&$spec) {
  $fields = CRM_Searchtaskbuilder_BAO_SearchTaskField::fields();
  foreach($fields as $fieldname => $field) {
    $spec[$fieldname] = $field;
  }
}

/**
 * SearchTaskField.get API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_search_task_field_get($params) {
  if (!isset($params['options']) || !isset($params['options']['sort'])) {
    $params['options']['sort'] = 'weight ASC';
  }
  $return = _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
  foreach($return['values'] as $id => $value) {
    if (isset($value['configuration'])) {
      $return['values'][$id]['configuration'] = json_decode($value['configuration'], TRUE);
    }
  }
  return $return;
}

/**
 * SearchTaskField.check_name API specification
 *
 * @param $params
 */
function _civicrm_api3_search_task_field_check_name_spec($params) {
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
 * SearchTaskField.check_name API
 *
 * @param $params
 */
function civicrm_api3_search_task_field_check_name($params) {
  $name = CRM_Searchtaskbuilder_BAO_SearchTaskField::checkName($params['title'], $params['search_task_id'], $params['id'], $params['name']);
  return array(
    'name' => $name,
  );
}
