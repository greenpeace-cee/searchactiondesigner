<?php

use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchtaskbuilder_Form_Action extends CRM_Core_Form {

  protected $searchTaskId;

  protected $actionId;

  protected $actionType;

  protected $action = array();

  protected $actionConfiguration = array();

  protected $actionMapping = array();

  /**
   * @var Civi\ActionProvider\Action\AbstractAction
   */
  protected $actionClass;

  public function preProcess() {
    parent::preProcess();
    $provider = searchtaskbuilder_get_action_provider();

    $this->actionId = CRM_Utils_Request::retrieve('id', 'Integer');
    $this->searchTaskId = CRM_Utils_Request::retrieve('search_task_id', 'Integer');
    $this->assign('search_task_id', $this->searchTaskId);


    if ($this->actionId) {
      $this->action = civicrm_api3('SearchTaskAction', 'getsingle', array('id' => $this->actionId));
      $this->assign('actionObject', $this->action);
      $this->actionClass = $provider->getActionByName($this->action['type']);
      $this->actionType = $this->action['type'];
      if (isset($this->action['configuration'])) {
        $this->actionConfiguration = $this->action['configuration'];
      }
      if (isset($this->action['mapping'])) {
        $this->actionMapping = $this->action['mapping'];
      }
    }

    $type = CRM_Utils_Request::retrieve('type', 'String');
    if ($type) {
      $this->actionType = $type;
      $this->actionClass = $provider->getActionByName($type);
    }
  }

  public function buildQuickForm() {
    $this->add('hidden', 'search_task_id');
    $this->add('hidden', 'id');

    if ($this->_action == CRM_Core_Action::DELETE) {
      $this->addButtons(array(
        array('type' => 'next', 'name' => E::ts('Delete'), 'isDefault' => TRUE,),
        array('type' => 'cancel', 'name' => E::ts('Cancel'))
      ));
      return parent::buildQuickForm();
    }

    $provider = searchtaskbuilder_get_action_provider();
    $this->add('select', 'type', E::ts('Type'), $provider->getActionTitles(), true, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- select -'),
    ));
    $this->add( 'text','title', E::ts('Title'), array('size' => 100, 'maxlength' => 255), true);
    $this->add( 'text','name', E::ts('Name'), array('size' => 100, 'maxlength' => 255), false);

    if ($this->actionClass) {
      \Civi\ActionProvider\Utils\UserInterface\AddConfigToQuickForm::buildForm($this, $this->actionClass, $this->actionType);
      $defaults = \Civi\ActionProvider\Utils\UserInterface\AddConfigToQuickForm::setDefaultValues($this->actionClass, $this->actionConfiguration, $this->actionType);
      $this->setDefaults($defaults);

      CRM_Searchtaskbuilder_Form_ActionConfiguration::addMapping($this, $this->searchTaskId, $this->actionClass, $this->actionType, $this->actionMapping, $this->actionId);
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
    if ($this->actionId) {
      $defaults['id'] = $this->actionId;
      $defaults['type'] = $this->action['type'];
      $defaults['title'] = $this->action['title'];
      $defaults['name'] = $this->action['name'];
    }
    return $defaults;
  }

  public function postProcess() {
    $redirectUrl = CRM_Utils_System::url('civicrm/searchtaskbuilder/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    if ($this->_action == CRM_Core_Action::DELETE) {
      $session = CRM_Core_Session::singleton();
      civicrm_api3('SearchTaskAction', 'delete', array('id' => $this->actionId));
      $session->setStatus(E::ts('Action removed'), E::ts('Removed'), 'success');
      CRM_Utils_System::redirect($redirectUrl);
    }

    $values = $this->exportValues();
    $params['type'] = $values['type'];
    $params['title'] = $values['title'];
    $params['name'] = $values['name'];
    $params['search_task_id'] = $this->searchTaskId;
    if ($this->actionId) {
      $params['id'] = $this->actionId;
    }
    if ($this->actionClass) {
      $configuration = \Civi\ActionProvider\Utils\UserInterface\AddConfigToQuickForm::getSubmittedConfiguration($this, $this->actionClass, $this->actionType);
      $params['configuration'] = $configuration;
      $params['mapping'] = CRM_Searchtaskbuilder_Form_ActionConfiguration::processMapping($this->actionClass, $this->actionType, $values);
    }

    $result = civicrm_api3('SearchTaskAction', 'create', $params);

    CRM_Utils_System::redirect($redirectUrl);
  }

}
