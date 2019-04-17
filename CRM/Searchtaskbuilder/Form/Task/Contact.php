<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_Form_Task_Contact extends CRM_Contact_Form_Task {

  protected $searchTaskId;
  protected $searchTask;
  protected $fields;

  public function preProcess() {
    parent::preProcess();

    if (strpos($this->_task,'searchtaskbuilder_') !== 0) {
      throw new \Exception(E::ts('Invalid search task'));
    }
    $this->searchTaskId = substr($this->_task, 18);

    $this->searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    $this->fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
    $this->fields = $this->fields['values'];

    $provider = searchtaskbuilder_get_provider();
    foreach($this->fields as $id => $field) {
      $this->fields[$id]['class'] = $provider->getFieldTypeByName($field['type']);
      $this->fields[$id]['template'] = $this->fields[$id]['class']->getFieldTemplateFileName();
    }

    $this->assign('searchTask', $this->searchTask);
    $this->assign('fields', $this->fields);
  }

  public function buildQuickForm() {
    parent::buildQuickForm();

    foreach($this->fields as $field) {
      $field['class']->addFieldToTaskForm($this, $field);
    }
  }


}