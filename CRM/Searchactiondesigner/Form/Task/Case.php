<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Form_Task_Case extends CRM_Case_Form_Task {

  protected $searchTaskId;
  protected $searchTask;

  public function preProcess() {
    $session = CRM_Core_Session::singleton();
    $userContext = str_replace("force=1", "", $session->readUserContext());
    parent::preProcess();
    $session->replaceUserContext($userContext);

    $isStandAlone = CRM_Utils_Request::retrieve('standalone', 'Integer');
    if ($isStandAlone) {
      $userContext = CRM_Utils_System::url('civicrm/contact/view/case', ['reset' => 1, 'id' => reset($this->_entityIds), 'action' => 'view']);
      $session->replaceUserContext($userContext);
    }

    if (empty($this->_task) && CRM_Utils_Request::retrieveValue('searchactiondesigner_id', 'Integer')) {
      $this->_task = 'searchactiondesigner_'.CRM_Utils_Request::retrieveValue('searchactiondesigner_id', 'Integer');
    }
    if (strpos($this->_task,'searchactiondesigner_') !== 0) {
      throw new \Exception(E::ts('Invalid search task'));
    }
    $this->searchTaskId = substr($this->_task, 21);

    $this->searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    $this->assign('searchTask', $this->searchTask);
    $this->assign('status', E::ts("Number of selected cases: %1", array(1=>count($this->_entityIds))));
  }

  public function buildQuickForm() {
    CRM_Searchactiondesigner_Form_Task_Helper::buildQuickForm($this, $this->searchTaskId);
    $this->addDefaultButtons(E::ts('Next'), 'upload');
  }

  public function postProcess() {
    $submittedValues = $this->controller->exportValues();
    CRM_Searchactiondesigner_Form_Task_Helper::postProcess($this->searchTaskId, $submittedValues, $this->_entityIds);
  }

  public function getSearchFormValues() {
    $isStandAlone = CRM_Utils_Request::retrieve('standalone', 'Integer');
    if ($isStandAlone) {
      // Little hack for standalone use. We get the case_ids from the url and store them in the session
      // we use the Search session page for this.
      // And we pretend we have selected only one case id from the search.
      $case_ids = explode(",", CRM_Utils_Request::retrieve('case_ids', 'CommaSeparatedIntegers', $this, TRUE));
      $searchFormValues = $this->controller->exportValues('Search');
      $searchFormValues['radio_ts'] = 'ts_sel';
      foreach ($case_ids as $case_id) {
        $searchFormValues[CRM_Core_Form::CB_PREFIX . $case_id] = '1';
      }
      $data =& $this->controller->container();
      $data['values']['Search'] = $searchFormValues;
    }
    return parent::getSearchFormValues();
  }


}
