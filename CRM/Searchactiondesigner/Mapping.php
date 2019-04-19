<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Mapping {

  /**
   * Returns an array with all the fields available for parameter mapping
   *
   * @param $search_task_id
   * @param null $id
   * @return array
   */
  public static function getFieldsForMapping($search_task_id, $id=null) {
    $actionProvider = searchactiondesigner_get_action_provider();
    $return = array();
    $searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $search_task_id));
    $return['id'] = CRM_Searchactiondesigner_Type::getIdFieldTitle($searchTask['type']);
    $searchTaskFields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $search_task_id, 'options' => array('limit' => 0)));
    foreach($searchTaskFields['values'] as $searchTaskField) {
      $return['input.'.$searchTaskField['name']] = E::ts('User input').' :: '.$searchTaskField['title'];
    }
    $searchTaskActions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $search_task_id, 'options' => array('limit' => 0)));
    foreach($searchTaskActions['values'] as $searchTaskAction) {
      if ($id && $searchTaskAction['id'] == $id) {
        break;
      }

      $action = $actionProvider->getActionByName($searchTaskAction['type']);
      foreach($action->getOutputSpecification() as $spec) {
        $return['action.'.$searchTaskAction['name'].'.'.$spec->getName()] = E::ts('Action').' :: '.$searchTaskAction['title'] . ' :: '.$spec->getTitle();
      }

    }
    return $return;
  }

}
