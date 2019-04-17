<?php
use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_BAO_SearchTaskAction extends CRM_Searchtaskbuilder_DAO_SearchTaskAction {

  /**
   * Create a new SearchTaskAction based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Searchtaskbuilder_DAO_SearchTaskAction|NULL
   *
  public static function create($params) {
    $className = 'CRM_Searchtaskbuilder_DAO_SearchTaskAction';
    $entityName = 'SearchTaskAction';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
