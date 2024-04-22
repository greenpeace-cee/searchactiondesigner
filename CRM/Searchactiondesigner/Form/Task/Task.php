<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Task_Task extends CRM_Core_Form_Task {

  protected $searchTaskId;
  protected $searchTask;

  public function preProcess() {
    $this->setEntityShortName();
    $session = CRM_Core_Session::singleton();
    $url = $session->readUserContext();
    // Standalone mode e.g. from SearchKit
    $isStandalone = !empty($_GET['id']) && !empty($_GET['searchactiondesigner_id']);
    if ($isStandalone) {
      $this->_task = 'searchactiondesigner_' . $_GET['searchactiondesigner_id'];
      $this->_entityIds = explode(',', CRM_Utils_Request::retrieve('id', 'CommaSeparatedIntegers', $this, TRUE));
    }
    else {
      parent::preProcess();
    }
    $session->replaceUserContext($url);

    if (strpos($this->_task,'searchactiondesigner_') !== 0) {
      throw new \Exception(E::ts('Invalid search task'));
    }
    $this->searchTaskId = substr($this->_task, 21);

    $this->searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    $this->assign('searchTask', $this->searchTask);
  }

  protected function setEntityShortName() {
    self::$entityShortname = 'Searchactiondesigner_Form_Task';
  }

  public function buildQuickForm() {
    CRM_Searchactiondesigner_Form_Task_Helper::buildQuickForm($this, $this->searchTaskId);
    $label = E::ts('Next');
    if (!empty($this->searchTask['proceed_label'])) {
      $label = $this->searchTask['proceed_label'];
    }
    $this->addDefaultButtons($label, 'upload');
  }

  public function postProcess() {
    $submittedValues = $this->controller->exportValues();
    CRM_Searchactiondesigner_Form_Task_Helper::postProcess($this->searchTaskId, $submittedValues, $this->_entityIds);
  }


}
