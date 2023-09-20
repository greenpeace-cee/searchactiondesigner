<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use Civi\ActionProvider\Parameter\Specification;
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
    $fieldLibrary = searchactiondesigner_get_form_field_library();
    foreach($fields as $id => $field) {
      $fieldClass = $fieldLibrary->getFieldTypeByName($field['type']);
      $fieldClass->addFieldToForm($form, $field);
      $fields[$id]['template'] = $fieldClass->getFieldTemplateFileName();
    }
    $form->assign('fields', $fields);
  }

  public static function postProcess($search_task_id, $submittedValues, $ids) {
    $name = 'searchactiondesigner_'.$search_task_id.'_'.date('Ymdhis').'_'.CRM_Core_Session::getLoggedInContactID();
    $fieldLibrary = searchactiondesigner_get_form_field_library();
    $search_task = civicrm_api3('SearchTask', 'getsingle', array('id' => $search_task_id));
    $fields = self::getFields($search_task_id);
    $inputMapping = array();
    foreach($fields as $field) {
      $fieldClass = $fieldLibrary->getFieldTypeByName($field['type']);
      if ($fieldClass->isFieldValueSubmitted($field, $submittedValues)) {
        $submittedValue = $fieldClass->getSubmittedFieldValue($field, $submittedValues);
        foreach($submittedValue as $outputName => $outputValue) {
          $inputMapping['input.' . $field['name'].'.'.$outputName] = $outputValue;
        }
      }
    }

    if (count($ids) <= $search_task['records_per_batch']) {
      // Process directly
      // Set time limit to 0 as this script may run for quite a while. Depending on the
      // actions.
      set_time_limit(0);
      $actionProvider = searchactiondesigner_get_action_provider();
      self::processItems($ids, $inputMapping, $search_task_id, $name);
      if (isset($search_task['success_message']) && !empty($search_task['success_message'])) {
        CRM_Core_Session::setStatus($search_task['success_message'], $search_task['title'], 'success');
      }
      $actionProvider->finishBatch($name, true);
      self::finishBatchOnFormFields($search_task_id, $submittedValues);
    } else {
      // Create a batch
      $session = CRM_Core_Session::singleton();

      $queue = CRM_Queue_Service::singleton()->create(array(
        'type' => 'Sql',
        'name' => $name,
        'reset' => TRUE, //do flush queue upon creation
      ));

      $total = count($ids);
      $batches =  array_chunk($ids, $search_task['records_per_batch']);
      $current = 0;
      foreach($batches as $batch) {
        $current = $current + $search_task['records_per_batch'];
        if ($current > $total) {
          $current = $total;
        }
        $title = $search_task['title'] . ' ' . $current .'/'.$total;
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

      $title = E::ts('Finishing task %1', array(1=>$search_task['title']));
      //create a task without parameters
      $task = new CRM_Queue_Task(
        array(
          'CRM_Searchactiondesigner_Form_Task_Helper',
          'finishBatch'
        ), //call back method
        array($search_task_id, $submittedValues), //parameters,
        $title
      );
      //now add this task to the queue
      $queue->createItem($task);

      $url = str_replace("&amp;", "&", $session->readUserContext());

      $runner = new CRM_Queue_Runner(array(
        'title' => $search_task['title'],
        'queue' => $queue, //the queue object
        'errorMode'=> CRM_Queue_Runner::ERROR_ABORT, //abort upon error and keep task in queue
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
    // Set time limit to 0 as this script may run for quite a while. Depending on the
    // actions.
    set_time_limit(0);
    $actionProvider = searchactiondesigner_get_action_provider();
    self::processItems($batch, $inputMapping, $search_task_id, $ctx->queue->getName());
    $actionProvider->finishBatch($ctx->queue->getName(), false);
    return TRUE;
  }

  /**
   * Finish the batch
   *
   * @param $ctx
   * @param $search_task_id
   * @param array $submittedValues
   *
   * @return bool
   * @throws \CiviCRM_API3_Exception
   */
  public static function finishBatch(CRM_Queue_TaskContext $ctx, $search_task_id, $submittedValues=[]) {
    // Set time limit to 0 as this script may run for quite a while. Depending on the
    // actions.
    set_time_limit(0);
    // Retrieve the action provider
    $actionProvider = searchactiondesigner_get_action_provider();
    $batchName = $ctx->queue->getName();

    // Set a finish status message.
    $search_task = civicrm_api3('SearchTask', 'getsingle', array('id' => $search_task_id));
    if (isset($search_task['success_message']) && !empty($search_task['success_message'])) {
      CRM_Core_Session::setStatus($search_task['success_message'], $search_task['title'], 'success');
    }

    // Find all actions and initialize the class
    // We do this so the actions are initialized and able to finish.
    $actions = self::getActions($search_task_id);
    foreach($actions as $action) {
      $actionProvider->getBatchActionByName($action['type'], $action['configuration'], $batchName);
    }
    // Finish the batch.
    $actionProvider->finishBatch($batchName, true);

    self::finishBatchOnFormFields($search_task_id, $submittedValues);
    return TRUE;
  }

  /**
   * Call onBatchFinished on each field.
   * @param $search_task_id
   * @param $submittedValues
   *
   * @return void
   * @throws \CiviCRM_API3_Exception
   */
  protected static function finishBatchOnFormFields($search_task_id, $submittedValues) {
    $fieldLibrary = searchactiondesigner_get_form_field_library();
    $fields = self::getFields($search_task_id);
    foreach($fields as $field) {
      $fieldClass = $fieldLibrary->getFieldTypeByName($field['type']);
      if ($fieldClass) {
        $fieldClass->onBatchFinished($field, $submittedValues);
      }
    }
  }

  /**
   * Process multiple items
   *
   * @param $ids
   * @param $inputMapping
   * @param $search_task_id
   * @param $batchName
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function processItems($ids, $inputMapping, $search_task_id, $batchName) {
    foreach($ids as $id) {
      self::processItem($id, $inputMapping, $search_task_id, $batchName);
    }
  }

  public static function setMetadata(\Civi\ActionProvider\Metadata $metadata, $search_task_id) {
    $metadata->getSpecificationBag()->addSpecification(new Specification('search_task_tid', 'Integer', E::ts('Search Task ID')));
    $metadata->getMetadata()->setParameter('search_task_tid', $search_task_id);
  }

  /**
   * Process a single item.
   *
   * @param $id
   * @param $inputMapping
   * @param $search_task_id
   * @param $batchName
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function processItem($id, $inputMapping, $search_task_id, $batchName) {
    $actionProvider = searchactiondesigner_get_action_provider();
    self::setMetadata($actionProvider->getMetadata(), $search_task_id);
    $actions = self::getActions($search_task_id);
    $mapping = $inputMapping;
    $mapping['id'] = $id;
    foreach($actions as $action) {
      $actionClass = $actionProvider->getBatchActionByName($action['type'], $action['configuration'], $batchName);
      $parameterBag = $actionProvider->createParameterBag();
      $conditionParameters = $actionProvider->createParameterBag();
      $invalidConditionOutput = $actionProvider->createParameterBag();
      foreach($mapping as $field => $value) {
        $parameterBag->setParameter($field, $value);
        $conditionParameters->setParameter($field, $value);
        $invalidConditionOutput->setParameter($field, $value);
      }

      // Create a condition class for this action
      if (!isset($action['condition_configuration']) || !is_array($action['condition_configuration'])) {
        $action['condition_configuration'] = array();
      }
      if (!isset($action['condition_configuration']['parameter_mapping']) || !is_array($action['condition_configuration']['parameter_mapping'])) {
        $action['condition_configuration']['parameter_mapping'] = array();
      }
      if (!isset($action['condition_configuration']['output_mapping']) || !is_array($action['condition_configuration']['output_mapping'])) {
        $action['condition_configuration']['output_mapping'] = array();
      }
      $condition = self::getConditionClass($action['condition_configuration']);
      $actionClass->setCondition($condition);

      $mappedParameterBag = $actionProvider->createdMappedParameterBag($parameterBag, $action['mapping']);
      $mappedConditionParameters = $actionProvider->createdMappedParameterBag($conditionParameters, $action['condition_configuration']['parameter_mapping']);
      $mappedInvalidConditionOutput = $actionProvider->createdMappedParameterBag($invalidConditionOutput, $action['condition_configuration']['output_mapping']);
      $output = $actionClass->execute($mappedParameterBag, $mappedConditionParameters, $mappedInvalidConditionOutput);
      foreach($output->toArray() as $key => $value) {
        $mapping['action.' . $action['name'] . '.' . $key] = $value;
      }
    }
  }

  /**
   * Returns null or a ActionProvider AbstractCondition class.
   * @param $condition_configuration
   *
   * @return \Civi\ActionProvider\Condition\AbstractCondition|null
   */
  public static function getConditionClass($condition_configuration) {
    if (!is_array($condition_configuration) || empty($condition_configuration) || !isset($condition_configuration['name'])) {
      return null;
    }
    $provider = searchactiondesigner_get_action_provider();
    $condition = $provider->getConditionByName($condition_configuration['name']);
    if ($condition) {
      $configuration = $condition->getDefaultConfiguration();
      if (isset($condition_configuration['configuration']) && is_string($condition_configuration['configuration'])) {
        $condition_configuration['configuration'] = json_decode($condition_configuration['configuration'], TRUE);
      }
      if (empty($condition_configuration['configuration']) || !is_array($condition_configuration['configuration'])) {
        $condition_configuration['configuration'] = [];
      }
      foreach ($condition_configuration['configuration'] as $name => $value) {
        $configuration->setParameter($name, $value);
      }
      $condition->setConfiguration($configuration);

      return $condition;
    }
    return null;
  }

}
