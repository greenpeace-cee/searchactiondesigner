<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Task_Helper {

  private static $fields = array();

  private static $actions = array();

  /**
   * @param $search_task_id
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  private static function getFields($search_task_id) {
    if (!isset(self::$fields[$search_task_id])) {
      $fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $search_task_id, 'options' => array('limit' => 0)));
      self::$fields[$search_task_id] = $fields['values'];
    }
    return self::$fields[$search_task_id];
  }

  /**
   * @param $search_task_id
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  private static function getActions($search_task_id) {
    if (!isset(self::$actions[$search_task_id])) {
      $actions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $search_task_id, 'options' => array('limit' => 0)));
      self::$actions[$search_task_id] = $actions['values'];
    }
    return self::$actions[$search_task_id];
  }

  public static function buildQuickForm($form, $search_task_id) {
    $fields = self::getFields($search_task_id);
    $provider = searchactiondesigner_get_provider();
    foreach($fields as $id => $field) {
      $fieldClass = $provider->getFieldTypeByName($field['type']);
      $fieldClass->addFieldToTaskForm($form, $field);
      $fields[$id]['template'] = $fieldClass->getFieldTemplateFileName();
    }
    $form->assign('fields', $fields);
  }

  public static function postProcess($search_task_id, $submittedValues, $ids) {
    $provider = searchactiondesigner_get_provider();
    $search_task = civicrm_api3('SearchTask', 'getsingle', array('id' => $search_task_id));
    $fields = self::getFields($search_task_id);
    $inputMapping = array();
    foreach($fields as $field) {
      $fieldClass = $provider->getFieldTypeByName($field['type']);
      if ($fieldClass->isFieldValueSubmitted($field, $submittedValues)) {
        $inputMapping['input.' . $field['name']] = $fieldClass->getSubmittedFieldValue($field, $submittedValues);
      }
    }

    if (count($ids) <= $search_task['records_per_batch']) {
      // Process directly
      self::processItems($ids, $inputMapping, $search_task_id);
    } else {
      // Create a batch
      $session = CRM_Core_Session::singleton();

      $name = 'searchactiondesigner_'.$search_task_id.'_'.date('Ymdhis').'_'.CRM_Core_Session::getLoggedInContactID();

      $queue = CRM_Queue_Service::singleton()->create(array(
        'type' => 'Sql',
        'name' => $name,
        'reset' => TRUE, //do flush queue upon creation
      ));

      $total = count($ids);
      $batches =  array_chunk($ids, $search_task['records_per_batch']);
      $i = 0;
      foreach($batches as $batch) {
        $i = $i + $search_task['records_per_batch'];
        $title = $search_task['title'] . ' ' . $i .'/'.$total;
        //create a task without parameters
        $task = new CRM_Queue_Task(
          array(
            'CRM_Searchactiondesigner_Form_Task_Helper',
            'processBatch'
          ), //call back method
          array($search_task_id, $inputMapping, $batch), //parameters,
          $title
        );
        //now add this task to the queue
        $queue->createItem($task);

      }

      $url = str_replace("&amp;", "&", $session->readUserContext());

      $runner = new CRM_Queue_Runner(array(
        'title' => $search_task['title'],
        'queue' => $queue, //the queue object
        'errorMode'=> CRM_Queue_Runner::ERROR_CONTINUE, //abort upon error and keep task in queue
        'onEnd' => array('postProcess', 'onEnd'), //method which is called as soon as the queue is finished
        'onEndUrl' => $url,
      ));

      $runner->runAllViaWeb(); // does not return
    }
  }

  /**
   * Process a batch from the queue runner
   *
   * @param $ctx
   * @param $search_task_id
   * @param $inputMapping
   * @param $batch
   *
   * @return bool
   * @throws \CiviCRM_API3_Exception
   */
  public static function processBatch(CRM_Queue_TaskContext $ctx, $search_task_id, $inputMapping, $batch) {
    self::processItems($batch, $inputMapping, $search_task_id);
    if ($ctx->queue->numberOfItems() <= 1) {
      $search_task = civicrm_api3('SearchTask', 'getsingle', array('id' => $search_task_id));
      if (isset($search_task['success_message']) && !empty($search_task['success_message'])) {
        CRM_Core_Session::setStatus($search_task['success_message'], $search_task['title'], 'success');
      }
    }
    return TRUE;
  }

  /**
   * Process multiple items
   *
   * @param $ids
   * @param $inputMapping
   * @param $search_task_id
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function processItems($ids, $inputMapping, $search_task_id) {
    foreach($ids as $id) {
      self::processItem($id, $inputMapping, $search_task_id);
    }
  }

  /**
   * Process a single item.
   *
   * @param $id
   * @param $inputMapping
   * @param $search_task_id
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function processItem($id, $inputMapping, $search_task_id) {
    $actionProvider = searchactiondesigner_get_action_provider();
    $actions = self::getActions($search_task_id);
    $mapping = $inputMapping;
    $mapping['id'] = $id;
    foreach($actions as $action) {
      $actionClass = $actionProvider->getActionByName($action['type']);
      $actionClass->getConfiguration()->fromArray($action['configuration']);

      $parameterBag = $actionProvider->createParameterBag();
      $conditionParameters = $actionProvider->createParameterBag();
      $invalidConditionOutput = $actionProvider->createParameterBag();
      foreach($mapping as $field => $value) {
        $parameterBag->setParameter($field, $value);
      }
      $mappedParameterBag = $actionProvider->createdMappedParameterBag($parameterBag, $action['mapping']);
      $output = $actionClass->execute($mappedParameterBag, $conditionParameters, $invalidConditionOutput);
      foreach($output->toArray() as $key => $value) {
        $mapping['action.' . $action['name'] . '.' . $key] = $value;
      }
    }
  }

}