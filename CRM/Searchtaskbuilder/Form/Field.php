<?php

use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchtaskbuilder_Form_Field extends CRM_Core_Form {

  protected $searchTaskId;

  protected $fieldId;

  protected $field = array();

  /**
   * @var Civi\Searchtaskbuilder\Field\AbstractField
   */
  protected $fieldTypeClass;

  public function preProcess() {
    parent::preProcess();

    $provider = searchtaskbuilder_get_provider();

    $this->fieldId = CRM_Utils_Request::retrieve('id', 'Integer');
    $this->searchTaskId = CRM_Utils_Request::retrieve('search_task_id', 'Integer');
    $this->assign('search_task_id', $this->searchTaskId);

    $this->assign('has_configuration', false);
    if ($this->fieldId) {
      $this->field = civicrm_api3('SearchTaskField', 'getsingle', array('id' => $this->fieldId));
      $this->assign('field', $this->field);
      $this->fieldTypeClass = $provider->getFieldTypeByName($this->field['type']);
      $this->assign('has_configuration', $this->fieldTypeClass->hasConfiguration());
    }

    $type = CRM_Utils_Request::retrieve('type', 'String');
    if ($type) {
      $this->fieldTypeClass = $provider->getFieldTypeByName($type);
      $this->assign('has_configuration', $this->fieldTypeClass->hasConfiguration());
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

    $provider = searchtaskbuilder_get_provider();
    $this->add('select', 'type', E::ts('Type'), $provider->getFieldTypes(), true, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- select -'),
    ));
    $this->add( 'text','title', E::ts('Title'), array('size' => 100, 'maxlength' => 255), true);
    $this->add( 'text','name', E::ts('Name'), array('size' => 100, 'maxlength' => 255), false);
    $this->add('checkbox', 'is_required', E::ts('Is required'));

    if ($this->fieldTypeClass && $this->fieldTypeClass->hasConfiguration()) {
      $this->fieldTypeClass->buildConfigurationForm($this, $this->field);
      $this->assign('configuration_template', $this->fieldTypeClass->getConfigurationTemplateFileName());
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
    if ($this->fieldId) {
      $defaults['id'] = $this->fieldId;
      $defaults['type'] = $this->field['type'];
      $defaults['title'] = $this->field['title'];
      $defaults['name'] = $this->field['name'];
      if (isset($this->field['is_required'])) {
        $defaults['is_required'] = $this->field['is_required'];
      }
    }
    return $defaults;
  }

  public function postProcess() {
    $redirectUrl = CRM_Utils_System::url('civicrm/searchtaskbuilder/edit', array('reset' => 1, 'action' => 'update', 'id' => $this->searchTaskId));
    if ($this->_action == CRM_Core_Action::DELETE) {
      $session = CRM_Core_Session::singleton();
      civicrm_api3('SearchTaskField', 'delete', array('id' => $this->fieldId));
      $session->setStatus(E::ts('Field removed'), E::ts('Removed'), 'success');
      CRM_Utils_System::redirect($redirectUrl);
    }

    $values = $this->exportValues();
    $params['type'] = $values['type'];
    $params['title'] = $values['title'];
    $params['name'] = $values['name'];
    $params['is_required'] = isset($values['is_required']) ? $values['is_required'] : false;
    $params['search_task_id'] = $this->searchTaskId;
    if ($this->fieldId) {
      $defaults['id'] = $this->fieldId;
    }

    if ($this->fieldTypeClass && $this->fieldTypeClass->hasConfiguration()) {
      $params['configuration'] = $this->fieldTypeClass->processConfiguration($values);
    }

    $result = civicrm_api3('SearchTaskField', 'create', $params);

    CRM_Utils_System::redirect($redirectUrl);
  }


}
