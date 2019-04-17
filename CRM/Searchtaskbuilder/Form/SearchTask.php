<?php

use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchtaskbuilder_Form_SearchTask extends CRM_Core_Form {

  private $searchTaskId;

  private $currentUrl;

  /**
   * Function to perform processing before displaying form (overrides parent function)
   *
   * @access public
   */
  function preProcess() {
    $this->searchTaskId = CRM_Utils_Request::retrieve('id', 'Integer');
    $this->currentUrl = CRM_Utils_System::url('civicrm/searchtaskbuilder/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    $this->assign('search_task_id', $this->searchTaskId);

    $session = CRM_Core_Session::singleton();
    switch($this->_action) {
      case CRM_Core_Action::DISABLE:
        //CRM_Dataprocessor_BAO_DataProcessor::disable($this->dataProcessorId);
        $session->setStatus('Search task disabled', 'Disable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::ENABLE:
        //CRM_Dataprocessor_BAO_DataProcessor::enable($this->dataProcessorId);
        $session->setStatus('Search task enabled', 'Enable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::REVERT:
        //CRM_Dataprocessor_BAO_DataProcessor::revert($this->dataProcessorId);
        $session->setStatus('Search task reverted', 'Revert', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::EXPORT:
        //$this->assign('export', json_encode(CRM_Dataprocessor_BAO_DataProcessor::export($this->dataProcessorId), JSON_PRETTY_PRINT));
        break;
    }

    if ($this->searchTaskId) {
      $this->addFields();
      $this->addActions();
      $addActionUrl = CRM_Utils_System::url('civicrm/searchtaskbuilder/action', 'reset=1&action=add&search_task_id='.$this->searchTaskId, TRUE);
      $this->assign('addActionUrl', $addActionUrl);
    }
  }

  public function buildQuickForm() {
    $this->add('hidden', 'id');
    if ($this->_action != CRM_Core_Action::DELETE) {
      $this->add('select', 'type', E::ts('Available for'), CRM_Searchtaskbuilder_Type::getTitles(), TRUE);
      $this->add('text', 'title', E::ts('Title'), array('size' => 100, 'maxlength' => 255), TRUE);
      $this->add('text', 'description', E::ts('Description'), array('size' => 100, 'maxlength' => 255));
      $this->add('wysiwyg', 'help_text', E::ts('Help text for this search task'), array('rows' => 6, 'cols' => 80));
      $this->add('checkbox', 'is_active', E::ts('Enabled'));
    }
    if ($this->_action == CRM_Core_Action::ADD) {
      $this->addButtons(array(
        array('type' => 'next', 'name' => E::ts('Next'), 'isDefault' => TRUE,),
        array('type' => 'cancel', 'name' => E::ts('Cancel'))));
    } elseif ($this->_action == CRM_Core_Action::DELETE) {
      $this->addButtons(array(
        array('type' => 'next', 'name' => E::ts('Delete'), 'isDefault' => TRUE,),
        array('type' => 'cancel', 'name' => E::ts('Cancel'))));
    } elseif ($this->_action == CRM_Core_Action::EXPORT) {
      $this->addButtons(array(
        array('type' => 'cancel', 'name' => E::ts('Go back'), 'isDefault' => TRUE),
      ));
    } else {
      $this->addButtons(array(
        array('type' => 'next', 'name' => E::ts('Save'), 'isDefault' => TRUE,),
        array('type' => 'cancel', 'name' => E::ts('Cancel'))));
    }
    parent::buildQuickForm();
  }

  /**
   * Function to set default values (overrides parent function)
   *
   * @return array $defaults
   * @access public
   */
  function setDefaultValues() {
    $defaults = array();
    $defaults['id'] = $this->searchTaskId;
    switch ($this->_action) {
      case CRM_Core_Action::ADD:
        $this->setAddDefaults($defaults);
        break;
      case CRM_Core_Action::UPDATE:
        $this->setUpdateDefaults($defaults);
        break;
    }
    return $defaults;
  }

  public function postProcess() {
    $session = CRM_Core_Session::singleton();
    if ($this->_action == CRM_Core_Action::DELETE) {
      civicrm_api3('SearchTask', 'delete', array('id' => $this->searchTaskId));

      $session->setStatus(E::ts('Search Task removed'), E::ts('Removed'), 'success');
      $redirectUrl = $session->popUserContext();
      CRM_Utils_System::redirect($redirectUrl);
    }

    $values = $this->exportValues();
    $params['type'] = $values['type'];
    $params['title'] = $values['title'];
    $params['description'] = $values['description'];
    $params['help_text'] = $values['help_text'];
    $params['is_active'] = !empty($values['is_active']) ? 1 : 0;
    if ($this->searchTaskId) {
      $params['id'] = $this->searchTaskId;
    }

    $result = civicrm_api3('SearchTask', 'create', $params);
    $redirectUrl = CRM_Utils_System::url('civicrm/searchtaskbuilder/edit', array('reset' => 1, 'action' => 'update', 'id' => $result['id']));
    CRM_Utils_System::redirect($redirectUrl);
  }

  /**
   * Function to set default values if action is add
   *
   * @param array $defaults
   * @access protected
   */
  protected function setAddDefaults(&$defaults) {
    $defaults['is_active'] = 1;
  }

  /**
   * Function to set default values if action is update
   *
   * @param array $defaults
   * @access protected
   */
  protected function setUpdateDefaults(&$defaults) {
    $searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
    if (!empty($searchTask)) {
      $defaults['title'] = $searchTask['title'];
      $defaults['type'] = $searchTask['type'];
      if (isset($searchTask['description'])) {
        $defaults['description'] = $searchTask['description'];
      } else {
        $defaults['description'] = '';
      }
      if (isset($searchTask['help_text'])) {
        $defaults['help_text'] = $searchTask['help_text'];
      } else {
        $defaults['help_text'] = '';
      }
      $defaults['is_active'] = $searchTask['is_active'];
    }
  }

  protected function addFields() {
    $provider = searchtaskbuilder_get_provider();
    $this->assign('field_types', $provider->getFieldTypes());
    $fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
    CRM_Utils_Weight::addOrder($fields['values'], 'CRM_Searchtaskbuilder_DAO_SearchTaskField', 'id', $this->currentUrl, 'search_task_id='.$this->searchTaskId);
    $this->assign('fields', $fields['values']);
  }

  protected function addActions() {
    $provider = searchtaskbuilder_get_action_provider();
    $this->assign('action_types', $provider->getActionTitles());
    $actions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
    CRM_Utils_Weight::addOrder($actions['values'], 'CRM_Searchtaskbuilder_DAO_SearchTaskAction', 'id', $this->currentUrl, 'search_task_id='.$this->searchTaskId);
    $this->assign('actions', $actions['values']);
  }



}
