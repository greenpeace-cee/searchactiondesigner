<?php
use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_BAO_SearchTask extends CRM_Searchactiondesigner_DAO_SearchTask {

  protected static $importingSearchTasks = [];

  /**
   * Create and check whether a name already exists.
   *
   * @param $title
   * @param null $id
   * @param null $name
   *
   * @return string|string[]|null
   */
  public static function checkName($title, $id=null,$name=null) {
    if (!$name) {
      $name = preg_replace('@[^a-z0-9_]+@','_',strtolower($title));
    }

    $name = preg_replace('@[^a-z0-9_]+@','_',strtolower($name));
    $name_part = $name;

    $sql = "SELECT COUNT(*) FROM civicrm_search_task WHERE `name` = %1";
    $sqlParams[1] = array($name, 'String');
    if (isset($id)) {
      $sql .= " AND `id` != %2";
      $sqlParams[2] = array($id, 'Integer');
    }

    $i = 1;
    while(CRM_Core_DAO::singleValueQuery($sql, $sqlParams) > 0) {
      $i++;
      $name = $name_part .'_'.$i;
      $sqlParams[1] = array($name, 'String');
    }
    return $name;
  }

  /**
   * Returns the id of the search task.
   *
   * @param string $searchTaskName
   *   The name of the search task.
   * @return int
   *   The id of the search task.
   */
  public static function getId($searchTaskName) {
    $sql = "SELECT `id` FROM `civicrm_search_task` WHERE `name` = %1";
    $params[1] = array($searchTaskName, 'String');
    $id = CRM_Core_DAO::singleValueQuery($sql, $params);
    return $id;
  }

  /**
   * Returns the status of the search task.
   * @See CRM_Searchactiondesigner_Status for possible values.
   *
   * @param string $searchTaskName
   *   The name of the search task.
   * @return int
   *   The status of the search task.
   */
  public static function getStatus($searchTaskName) {
    $sql = "SELECT `status` FROM `civicrm_search_task` WHERE `name` = %1";
    $params[1] = array($searchTaskName, 'String');
    $status = CRM_Core_DAO::singleValueQuery($sql, $params);
    if ($status === null) {
      $status = CRM_Searchactiondesigner_Status::UNKNOWN;
    }
    return $status;
  }

  /**
   * Updates the status and source file of the search action.
   * @See CRM_Searchactiondesigner_Status for possible status values.
   *
   * @param string $searchTaskName
   *   The name of the search task.
   * @param int $status
   *   The status value.
   * @param string $source_file
   *   The source file. Leave empty when status is IN_DATABASE.
   */
  public static function setStatusAndSourceFile($searchTaskName, $status, $source_file) {
    $sql = "UPDATE `civicrm_search_task` SET `status` = %2, `source_file` = %3 WHERE `name` = %1";
    $params[1] = array($searchTaskName, 'String');
    $params[2] = array($status, 'Integer');
    $params[3] = array($source_file, 'String');
    CRM_Core_DAO::executeQuery($sql, $params);
  }

  /**
   * Update the status from in code to overriden when a data processor has been changed
   *
   * @param $searchTaskId
   */
  public static function updateAndChekStatus($searchTaskId) {
    $sql = "SELECT `status`, `name` FROM `civicrm_search_task` WHERE `id` = %1";
    $params[1] = array($searchTaskId, 'Integer');
    $dao = CRM_Core_DAO::executeQuery($sql, $params);
    if ($dao->fetch()) {
      if (!in_array($dao->name, self::$importingSearchTasks) && $dao->status == CRM_Searchactiondesigner_Status::IN_CODE) {
        $sql = "UPDATE `civicrm_search_task` SET `status` = %2 WHERE `id` = %1";
        $params[1] = array($searchTaskId, 'String');
        $params[2] = array(CRM_Searchactiondesigner_Status::OVERRIDDEN, 'Integer');
        CRM_Core_DAO::executeQuery($sql, $params);
      }
    }
  }

  /**
   * Store the search tasj name so we know that we are importing this search task
   * and should not update its status on the way.
   *
   * @param $searchTaskName
   */
  public static function setSearchTaskToImportingState($searchTaskName) {
    self::$importingSearchTasks[] = $searchTaskName;
  }

}
