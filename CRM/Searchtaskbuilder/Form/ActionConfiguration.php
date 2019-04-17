<?php

use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchtaskbuilder_Form_ActionConfiguration extends CRM_Core_Form {

  protected $type;

  /**
   * @var Civi\ActionProvider\Action\AbstractAction
   */
  protected $action;

  protected $searchTaskId;

  protected $actionId;

  public function preProcess() {
    parent::preProcess();

    $provider = searchtaskbuilder_get_action_provider();
    $this->searchTaskId = CRM_Utils_Request::retrieve('search_task_id', 'Integer');
    $this->actionId = CRM_Utils_Request::retrieve('id', 'Integer');
    $this->type = CRM_Utils_Request::retrieve('type', 'String', CRM_Core_DAO::$_nullObject, TRUE);

    $this->action = $provider->getActionByName($this->type);

    $this->assign('suppressForm', TRUE);
    $this->controller->_generateQFKey = FALSE;
  }


  public function buildQuickForm() {
    \Civi\ActionProvider\Utils\UserInterface\AddConfigToQuickForm::buildForm($this, $this->action, $this->type);
    $defaults = \Civi\ActionProvider\Utils\UserInterface\AddConfigToQuickForm::setDefaultValues($this->action, array(), $this->type);
    $this->setDefaults($defaults);

    self::addMapping($this, $this->searchTaskId, $this->action, $this->type, array(), $this->actionId);

    parent::buildQuickForm();
  }

  public function postProcess() {

  }

  public static function addMapping($form, $searchTaskId, $action, $prefix, $currentMapping, $actionId) {
    $availableFields = CRM_Searchtaskbuilder_Mapping::getFieldsForMapping($searchTaskId, $actionId);
    $actionProviderMappingFields = array();
    $defaults = array();
    foreach($action->getParameterSpecification() as $spec) {
      $name = $prefix.'_mapping_'.$spec->getName();
      $form->add('select', $name, $spec->getTitle(), $availableFields, $spec->isRequired(), array(
        'style' => 'min-width:250px',
        'class' => 'crm-select2 huge',
        'placeholder' => E::ts('- select -'),
      ));
      $actionProviderMappingFields[] = $name;

      if (isset($currentMapping[$spec->getName()])) {
        $defaults[$name] = $currentMapping[$spec->getName()];
      }
    }
    $form->assign('actionProviderMappingFields', $actionProviderMappingFields);
    $form->setDefaults($defaults);
  }

  public static function processMapping($action, $prefix, $submittedValues) {
    $return = array();
    foreach($action->getParameterSpecification() as $spec) {
      $name = $prefix.'_mapping_'.$spec->getName();
      if (isset($submittedValues[$name])) {
        $return[$spec->getName()] = $submittedValues[$name];
      }
    }
    return $return;
  }

}
