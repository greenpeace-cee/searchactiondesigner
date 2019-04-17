<?php

use CRM_Searchtaskbuilder_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchtaskbuilder_Form_FieldType extends CRM_Core_Form {

  protected $type;

  /**
   * @var Civi\Searchtaskbuilder\Field\AbstractField
   */
  protected $typeClass;

  public function preProcess() {
    parent::preProcess();

    $provider = searchtaskbuilder_get_provider();
    $this->type = CRM_Utils_Request::retrieve('type', 'String', CRM_Core_DAO::$_nullObject, TRUE);
    $this->typeClass = $provider->getFieldTypeByName($this->type);

    $this->assign('suppressForm', TRUE);
    $this->controller->_generateQFKey = FALSE;
  }


  public function buildQuickForm() {
    if ($this->typeClass->hasConfiguration()) {
      $this->assign('configuration_template', $this->typeClass->getConfigurationTemplateFileName());
      $this->typeClass->buildConfigurationForm($this);
    }
    parent::buildQuickForm();
  }

  public function postProcess() {

  }

}
