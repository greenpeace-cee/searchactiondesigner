<?php

use Civi\ActionProvider\Parameter\SpecificationBag;
use CRM_Searchactiondesigner_ExtensionUtil as E;
use Civi\ActionProvider\Utils\UserInterface\AddConditionConfigToQuickForm;
use Civi\ActionProvider\Utils\UserInterface\AddMappingToQuickForm;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchactiondesigner_Form_Condition extends CRM_Core_Form {

  protected $searchTaskId;

  protected $actionId;

  protected $conditionType;

  protected $action = array();

  protected $conditionConfiguration = array();

  protected $conditionMapping = array();

  protected $availableFields = array();

  /**
   * @var Civi\ActionProvider\Condition\AbstractCondition
   */
  protected $conditionClass;

  /**
   * @var Civi\ActionProvider\Action\AbstractAction
   */
  protected $actionClass;

  protected $snippet;

  public function preProcess() {
    parent::preProcess();
    $provider = searchactiondesigner_get_action_provider();

    $this->snippet = CRM_Utils_Request::retrieve('snippet', 'String');
    if ($this->snippet) {
      $this->assign('suppressForm', TRUE);
      $this->controller->_generateQFKey = FALSE;
    }

    $this->actionId = CRM_Utils_Request::retrieve('id', 'Integer', \CRM_Core_DAO::$_nullObject, TRUE);
    $this->searchTaskId = CRM_Utils_Request::retrieve('search_task_id', 'Integer', \CRM_Core_DAO::$_nullObject, TRUE);
    $this->assign('search_task_id', $this->searchTaskId);

    $this->action = civicrm_api3('SearchTaskAction', 'getsingle', array('id' => $this->actionId));
    $this->actionClass = $provider->getActionByName($this->action['type']);
    $this->assign('actionObject', $this->action);
    if (isset($this->action['condition_configuration']) && is_array($this->action['condition_configuration'])) {
      if (isset($this->action['condition_configuration']['name'])) {
        $this->conditionType = $this->action['condition_configuration']['name'];
        $this->conditionClass = $provider->getConditionByName($this->conditionType);
      }
      if (isset($this->action['condition_configuration']['configuration'])) {
        $this->conditionConfiguration = $this->action['condition_configuration']['configuration'];
      }
      if (isset($this->action['condition_configuration']['parameter_mapping'])) {
        $this->conditionMapping = $this->action['condition_configuration']['parameter_mapping'];
      }
    }

    $type = CRM_Utils_Request::retrieve('type', 'String');
    if ($type) {
      $this->conditionType = $type;
      $this->conditionClass = $provider->getConditionByName($type);
    }
    $this->assign('conditionClass', $this->conditionClass);

    $this->availableFields = CRM_Searchactiondesigner_Mapping::getFieldsForMapping($this->searchTaskId, $this->actionId);
  }

  public function buildQuickForm() {
    if (!$this->snippet) {
      $this->add('hidden', 'search_task_id');
      $this->add('hidden', 'id');
    }

    if ($this->_action == CRM_Core_Action::DELETE) {
      $this->addButtons(array(
        array('type' => 'next', 'name' => E::ts('Delete'), 'isDefault' => TRUE,),
        array('type' => 'cancel', 'name' => E::ts('Cancel'))
      ));
      return parent::buildQuickForm();
    }

    $provider = searchactiondesigner_get_action_provider();
    $conditions = [];
    foreach($provider->getConditions() as $condition_name => $condition) {
      $conditions[$condition_name] = $condition->getTitle();
    }
    $this->add('select', 'type', E::ts('Type'), $conditions, false, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- no condition -'),
    ));

    if ($this->conditionClass) {
      AddConditionConfigToQuickForm::buildForm($this, $this->conditionClass, $this->conditionType);
      $defaults = AddConditionConfigToQuickForm::setDefaultValues($this->conditionClass, $this->conditionConfiguration, $this->conditionType);
      $this->setDefaults($defaults);
      AddMappingToQuickForm::addMapping($this->conditionType.'_parameter_', $this->conditionClass->getParameterSpecification(), $this->action['condition_configuration']['parameter_mapping'], $this, $this->availableFields);
      AddMappingToQuickForm::addMapping($this->conditionType.'_output_', $this->actionClass->getOutputSpecification(), $this->action['condition_configuration']['output_mapping'], $this, $this->availableFields);
      $this->assign('parameter_mapping_prefix', $this->conditionType.'_parameter_');
      $this->assign('output_mapping_prefix', $this->conditionType.'_output_');
      $this->assign('isSubmitted', $this->isSubmitted());
    }

    $this->addButtons(array(
      array('type' => 'next', 'name' => E::ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => E::ts('Cancel'))
    ));

    parent::buildQuickForm();
  }

  public function setDefaultValues() {
    $defaults = array();
    $defaults['search_task_id'] = $this->searchTaskId;
    $defaults['id'] = $this->actionId;
    $defaults['type'] = $this->conditionType;
    return $defaults;
  }

  /**
   * Function that can be defined in Form to override or.
   * perform specific action on cancel action
   */
  public function cancelAction() {
    $this->searchTaskId = CRM_Utils_Request::retrieve('search_task_id', 'Integer');
    $redirectUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    CRM_Utils_System::redirect($redirectUrl);
  }

  public function postProcess() {
    $redirectUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    $values = $this->exportValues();
    $condfition_configuration = 'null';
    if ($this->conditionClass) {
      $condfition_configuration = array();
      $condfition_configuration['name'] = $this->conditionType;
      $condfition_configuration['configuration'] = AddConditionConfigToQuickForm::getSubmittedConfiguration($this, $this->conditionClass, $this->conditionType);
      $condfition_configuration['parameter_mapping'] = AddMappingToQuickForm::processMapping($values, $this->conditionType.'_parameter_', $this->conditionClass->getParameterSpecification());
      $condfition_configuration['output_mapping'] = AddMappingToQuickForm::processMapping($values, $this->conditionType.'_output_', $this->actionClass->getOutputSpecification());
    }
    $params = $this->action;
    $params['condition_configuration'] = $condfition_configuration;
    $result = civicrm_api3('SearchTaskAction', 'create', $params);
    CRM_Utils_System::redirect($redirectUrl);
  }



}
