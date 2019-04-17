<?php
use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_BAO_SearchTaskField extends CRM_Searchtaskbuilder_DAO_SearchTaskField {

  public static function checkName($title, $search_task_id, $id=null,$name=null) {
    if (!$name) {
      $name = preg_replace('@[^a-z0-9_]+@','_',strtolower($title));
    }

    $name = preg_replace('@[^a-z0-9_]+@','_',strtolower($name));
    $name_part = $name;

    $sql = "SELECT COUNT(*) FROM civicrm_search_task_field WHERE `name` = %1 AND `search_task_id` = %2";
    $sqlParams[1] = array($name, 'String');
    $sqlParams[2] = array($search_task_id, 'String');
    if (isset($id)) {
      $sql .= " AND `id` != %3";
      $sqlParams[3] = array($id, 'Integer');
    }

    $i = 1;
    while(CRM_Core_DAO::singleValueQuery($sql, $sqlParams) > 0) {
      $i++;
      $name = $name_part .'_'.$i;
      $sqlParams[1] = array($name, 'String');
    }
    return $name;
  }

}
