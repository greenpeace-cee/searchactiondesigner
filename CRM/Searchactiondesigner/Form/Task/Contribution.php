<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Task_Contribution extends CRM_Contribute_Form_Task {

  protected $searchTaskId;
  protected $searchTask;

  public function preProcess() {
    $session = CRM_Core_Session::singleton();
    $url = $session->readUserContext();
    parent::preProcess();
    $session->replaceUserContext($url);

    if (strpos($this->_task,'searchactiondesigner_') !== 0) {
      throw new \Exception(E::ts('Invalid search task'));
    }
    $this->searchTaskId = substr($this->_task, 21);

    $this->searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    $this->assign('searchTask', $this->searchTask);
    $this->assign('status', E::ts("Number of selected contributions: %1", array(1=>count($this->_contributionIds))));
  }

  public function buildQuickForm() {
    CRM_Searchactiondesigner_Form_Task_Helper::buildQuickForm($this, $this->searchTaskId);
    $this->addDefaultButtons(E::ts('Next', 'upload'));
  }

  public function postProcess() {
    $submittedValues = $this->controller->exportValues();
    CRM_Searchactiondesigner_Form_Task_Helper::postProcess($this->searchTaskId, $submittedValues, $this->_contributionIds);
  }


}
