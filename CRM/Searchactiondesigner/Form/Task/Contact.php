<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Task_Contact extends CRM_Contact_Form_Task {

  protected $searchTaskId;
  protected $searchTask;

  public function preProcess() {
    $session = CRM_Core_Session::singleton();
    $url = $session->readUserContext();
    parent::preProcess();
    $session->replaceUserContext($url);
    if (empty($this->_task) && CRM_Utils_Request::retrieveValue('searchactiondesigner_id', 'Integer')) {
      $this->_task = 'searchactiondesigner_'.CRM_Utils_Request::retrieveValue('searchactiondesigner_id', 'Integer');
    }
    if (strpos($this->_task,'searchactiondesigner_') === 0) {
      $this->searchTaskId = substr($this->_task, 21);
    }
    if (empty($this->searchTaskId)) {
      throw new \Exception(E::ts('Invalid search task'));
    }

    $this->searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    $this->assign('searchTask', $this->searchTask);
    $this->assign('status', E::ts("Selected contacts: %1", array(1=>count($this->_contactIds))));
  }

  public function buildQuickForm() {
    CRM_Searchactiondesigner_Form_Task_Helper::buildQuickForm($this, $this->searchTaskId);
    $this->addDefaultButtons(E::ts('Next'), 'upload');
  }

  public function postProcess() {
    $submittedValues = $this->controller->exportValues();
    CRM_Searchactiondesigner_Form_Task_Helper::postProcess($this->searchTaskId, $submittedValues, $this->_contactIds);
  }


}
