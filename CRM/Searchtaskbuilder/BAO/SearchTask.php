<?php
use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_BAO_SearchTask extends CRM_Searchtaskbuilder_DAO_SearchTask {

  /**
   * Create a new SearchTask based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Searchtaskbuilder_DAO_SearchTask|NULL
   *
  public static function create($params) {
    $className = 'CRM_Searchtaskbuilder_DAO_SearchTask';
    $entityName = 'SearchTask';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
