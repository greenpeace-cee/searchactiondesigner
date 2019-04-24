<?php

use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchactiondesigner_Form_Import extends CRM_Core_Form {

  /**
   * Function to perform processing before displaying form (overrides parent function)
   *
   * @access public
   */
  function preProcess() {
    parent::preProcess();
  }

  public function buildQuickForm() {
    $this->add('textarea', 'code', E::ts('Import code'), 'rows=30 style="width:100%"', true);
    $this->addButtons(array(
      array('type' => 'next', 'name' => E::ts('Import'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => E::ts('Cancel'))
    ));
  }

  public function postProcess() {
    $values = $this->exportValues();
    $importCode = json_decode($values['code'], true);
    $importResult = CRM_Searchactiondesigner_Importer::import($importCode, '');

    CRM_Core_Session::setStatus(E::ts('Imported search task'), '', 'success');

    $redirectUrl = CRM_Utils_System::url('civicrm/searchactiondesigner/edit', array('reset' => 1, 'action' => 'update', 'id' => $importResult['new_id']));
    CRM_Utils_System::redirect($redirectUrl);
  }

}
