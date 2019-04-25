<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

class CRM_Searchactiondesigner_Importer {

  /**
   * Import a search action
   *
   * @param $data
   * @param $filename
   *
   * @return array
   * @throws \Exception
   */
  public static function import($data, $filename) {
    $search_task_id = CRM_Searchactiondesigner_BAO_SearchTask::getId($data['name']);
    $status = CRM_Searchactiondesigner_BAO_SearchTask::getStatus($data['name']);
    $new_status = null;
    $new_id = null;

    switch ($status) {
      case CRM_Searchactiondesigner_Status::IN_DATABASE:
        // Update to overriden
        CRM_Searchactiondesigner_BAO_SearchTask::setStatusAndSourceFile($data['name'], CRM_Searchactiondesigner_Status::OVERRIDDEN, $filename);
        $new_id = $search_task_id;
        $new_status = CRM_Searchactiondesigner_Status::OVERRIDDEN;
        break;
      case CRM_Searchactiondesigner_Status::OVERRIDDEN:
        $new_id = $search_task_id;
        $new_status = CRM_Searchactiondesigner_Status::OVERRIDDEN;
        break;
      default:
        $new_id = self::importSearchTask($data, $filename, $search_task_id);
        $new_status = CRM_Searchactiondesigner_Status::IN_CODE;
        break;
    }

    $return = array(
      'original_id' => $search_task_id,
      'new_id' => $new_id,
      'original_status' => $status,
      'new_status' => $new_status,
      'file' => $filename,
    );

    return $return;
  }

  /**
   * Import a search task
   *
   * @param $data
   * @param $filename
   * @param $search_task_id
   *
   * @return mixed
   * @throws \Exception
   */
  public static function importSearchTask($data, $filename, $search_task_id) {
    $params = $data;
    unset($params['fields']);
    unset($params['actions']);
    if ($search_task_id) {
      $params['id'] = $search_task_id;
    }
    $params['status'] = CRM_Searchactiondesigner_Status::IN_CODE;
    $params['source_file'] = $filename;
    $result = civicrm_api3('SearchTask', 'create', $params);
    $id = $result['id'];

    // Clear all existing fields and actions
    $fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $id, 'options' => array('limit' => 0)));
    foreach($fields['values'] as $field) {
      civicrm_api3('SearchTaskField', 'delete', array('id' => $field['id']));
    }
    $actions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $id, 'options' => array('limit' => 0)));
    foreach($actions['values'] as $action) {
      civicrm_api3('SearchTaskAction', 'delete', array('id' => $field['id']));
    }

    foreach($data['fields'] as $field) {
      $params = $field;
      $params['search_task_id'] = $id;
      civicrm_api3('SearchTaskField', 'create', $params);
    }
    foreach($data['actions'] as $action) {
      $params = $action;
      $params['search_task_id'] = $id;
      civicrm_api3('SearchTaskAction', 'create', $params);
    }

    return $id;
  }


  /**
   * Imports search tasks from files in an extension directory.
   *
   * This scans the extension directory search-tasks/ for json files.
   */
  public static function importFromExtensions() {
    $return = array();
    $importedIds = array();
    $extensions = self::getExtensionFileListWithSearchTasks();
    foreach($extensions as $ext_file) {
      $data = json_decode($ext_file['data'], true);
      $return[$ext_file['file']] = self::import($data, $ext_file['file']);
      $importedIds[] = $return[$ext_file['file']]['new_id'];
    }

    // Remove all search tasks which are in code or overridden but not imported
    $dao = CRM_Core_DAO::executeQuery("SELECT id, name FROM civicrm_search_task WHERE id NOT IN (".implode($importedIds, ",").") AND status IN (".CRM_Searchactiondesigner_Status::IN_CODE.", ".CRM_Searchactiondesigner_Status::OVERRIDDEN.")");
    while ($dao->fetch()) {
      civicrm_api3('SearchTask', 'delete', array('id' => $dao->id));
      $return['deleted search tasks'][] = $dao->id.": ".$dao->name;
    }
    return $return;
  }

  /**
   * Returns a list with search tasks files within an extension folder.
   *
   * @return array
   */
  private static function getExtensionFileListWithSearchTasks() {
    $return = array();
    $extensions = civicrm_api3('Extension', 'get', array('options' => array('limit' => 0)));
    foreach($extensions['values'] as $ext) {
      if ($ext['status'] != 'installed') {
        continue;
      }

      $path = $ext['path'].'/searchactions';
      if (!is_dir($path)) {
        continue;
      }

      foreach (glob($path."/*.json") as $file) {
        $return[] = array(
          'file' => $ext['key']. '/searchactions/'.basename($file),
          'data' => file_get_contents($file),
        );
      }
    }
    return $return;
  }

}