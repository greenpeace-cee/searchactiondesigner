<?php

use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchactiondesigner_Form_SearchTask extends CRM_Core_Form {

  private $searchTaskId;

  private $currentUrl;

  /**
   * @var string
   */
  private $type = '';

  /**
   * @var \CRM_Searchactiondesigner_Form_ConfigurationInterface
   */
  private $configurationClass;

  /**
   * @var array
   */
  private $configuration = [];

  protected $snippet;

  /**
   * Function to perform processing before displaying form (overrides parent function)
   *
   * @access public
   */
  function preProcess() {
    $this->snippet = CRM_Utils_Request::retrieve('snippet', 'String');
    if ($this->snippet) {
      $this->assign('suppressForm', TRUE);
      $this->controller->_generateQFKey = FALSE;
    }

    $provider = searchactiondesigner_get_action_provider();
    $this->searchTaskId = CRM_Utils_Request::retrieve('id', 'Integer');
    CRM_Searchactiondesigner_Form_Task_Helper::setMetadata($provider->getMetadata(), $this->searchTaskId);
    $this->currentUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    $this->assign('search_task_id', $this->searchTaskId);

    $session = CRM_Core_Session::singleton();
    switch($this->_action) {
      case CRM_Core_Action::DISABLE:
        civicrm_api3('SearchTask', 'create', array('id' => $this->searchTaskId, 'is_ative' => 0));
        $session->setStatus('Search task disabled', 'Disable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::ENABLE:
        civicrm_api3('SearchTask', 'create', array('id' => $this->searchTaskId, 'is_ative' => 1));
        $session->setStatus('Search task enabled', 'Enable', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::REVERT:
        CRM_Searchactiondesigner_BAO_SearchTask::revert($this->searchTaskId);
        $session->setStatus('Search task reverted', 'Revert', 'success');
        CRM_Utils_System::redirect($session->readUserContext());
        break;
      case CRM_Core_Action::EXPORT:
        $fieldLibrary = searchactiondesigner_get_form_field_library();
        $export = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
        $fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
        $actions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
        unset($export['id']);
        unset($export['status']);
        unset($export['source_file']);
        $export['fields'] = array();
        $export['actions'] = array();
        foreach($fields['values'] as $field) {
          unset($field['id']);
          unset($field['search_task_id']);
          $fieldClass = $fieldLibrary->getFieldTypeByName($field['type']);
          $field['configuration'] = $fieldClass->exportConfiguration($field['configuration']);
          $export['fields'][] = $field;
        }
        foreach($actions['values'] as $action) {
          unset($action['id']);
          unset($action['search_task_id']);
          $export['actions'][] = $action;
        }
        $this->assign('export', json_encode($export, JSON_PRETTY_PRINT));
        break;
    }

    if ($this->searchTaskId) {
      $searchTask = civicrm_api3('SearchTask', 'getsingle', array('id' => $this->searchTaskId));
      $this->type = $searchTask['type'];
      if (isset($searchTask['configuration']) && is_array($searchTask['configuration'])) {
        $this->configuration = $searchTask['configuration'];
      }
      $this->assign('searchTask', $searchTask);
      $this->addFields();
      $this->addActions();
      $addActionUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/action', 'reset=1&action=add&search_task_id='.$this->searchTaskId, TRUE);
      $this->assign('addActionUrl', $addActionUrl);
    }

    $type = CRM_Utils_Request::retrieve('type', 'String');
    if (!empty($type)) {
      $this->type = $type;
    }

    if (!empty($this->type)) {
      $configurationClass = CRM_Searchactiondesigner_Type::getConfigurationClass($this->type);
      if ($configurationClass instanceof CRM_Searchactiondesigner_Form_ConfigurationInterface) {
        $this->configurationClass = $configurationClass;
      }
    }
  }

  public function buildQuickForm() {
    $configurationElements = [];
    if (!$this->snippet) {
      $this->add('hidden', 'id');
    }
    if ($this->_action != CRM_Core_Action::DELETE) {
      $this->add('select', 'type', E::ts('Available for'), CRM_Searchactiondesigner_Type::getTitles(), TRUE, array(
        'style' => 'min-width:250px',
        'class' => 'crm-select2 huge',
        'placeholder' => E::ts('- select -'),
      ));
      $this->add('text', 'title', E::ts('Title'), array('size' => 50, 'maxlength' => 255), TRUE);
      $this->add( 'text','name', E::ts('Name'), array('size' => 50, 'maxlength' => 255), false);
      $this->add('text', 'description', E::ts('Description'), array('size' => 100, 'maxlength' => 255));
      $this->add('text', 'success_message', E::ts('Success Message'), array('size' => 100, 'maxlength' => 255));
      $this->add('wysiwyg', 'help_text', E::ts('Help text for this search task'), array('rows' => 6, 'cols' => 80));
      $this->add('checkbox', 'is_active', E::ts('Enabled'));
      $this->add('text', 'records_per_batch', E::ts('Records per batch'), array('size' => 4, 'maxlength' => 4), TRUE);
      $this->add('select','permission', E::ts('Permission'), \CRM_Core_Permission::basicPermissions(), FALSE, array(
        'style' => 'min-width:250px',
        'class' => 'crm-select2 huge',
        'placeholder' => E::ts('- select -'),
      ));
      $this->addRule('records_per_batch', E::ts("Invalid number"), 'numeric');
      if ($this->configurationClass) {
        $configurationElements = $this->configurationClass->buildConfigurationForm($this, $this->configuration);
      }
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
    $this->assign('configurationElements', $configurationElements);
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
    $params['name'] = $values['name'];
    $params['title'] = $values['title'];
    $params['description'] = $values['description'];
    $params['help_text'] = $values['help_text'];
    $params['success_message'] = $values['success_message'];
    $params['records_per_batch'] = $values['records_per_batch'];
    $params['permission'] = $values['permission'];
    $params['is_active'] = !empty($values['is_active']) ? 1 : 0;
    if ($this->searchTaskId) {
      $params['id'] = $this->searchTaskId;
    }
    $params['configuration'] = [];
    if ($this->configurationClass) {
      $params['configuration'] = $this->configurationClass->processSubmittedValues($values);
    }

    $result = civicrm_api3('SearchTask', 'create', $params);
    $redirectUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/edit', array('reset' => 1, 'action' => 'update', 'id' => $result['id']));
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
    $defaults['success_message'] = E::ts('Action done');
    $defaults['records_per_batch'] = 25;
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
      $defaults['name'] = $searchTask['name'];
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
      $defaults['success_message'] = $searchTask['success_message'];
      $defaults['records_per_batch'] = $searchTask['records_per_batch'];
      $defaults['is_active'] = $searchTask['is_active'];
      $defaults['permission'] = $searchTask['permission'] ?? '';
    }
  }

  protected function addFields() {
    $fieldLibrary = searchactiondesigner_get_form_field_library();
    $this->assign('field_types', $fieldLibrary->getFieldTypes());
    $fields = civicrm_api3('SearchTaskField', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
    CRM_Utils_Weight::addOrder($fields['values'], 'CRM_Searchactiondesigner_DAO_SearchTaskField', 'id', $this->currentUrl, 'search_task_id='.$this->searchTaskId);
    $this->assign('fields', $fields['values']);
  }

  protected function addActions() {
    $provider = searchactiondesigner_get_action_provider();
    $this->assign('action_types', $provider->getActionTitles());
    $actions = civicrm_api3('SearchTaskAction', 'get', array('search_task_id' => $this->searchTaskId, 'options' => array('limit' => 0)));
    CRM_Utils_Weight::addOrder($actions['values'], 'CRM_Searchactiondesigner_DAO_SearchTaskAction', 'id', $this->currentUrl, 'search_task_id='.$this->searchTaskId);
    $this->assign('actions', $actions['values']);
  }



}
