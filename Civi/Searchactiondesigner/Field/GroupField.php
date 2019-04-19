<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchactiondesigner\Field;

use CRM_Searchactiondesigner_ExtensionUtil as E;

class GroupField extends AbstractField {

  /**
   * Returns true when this field has additional configuration
   *
   * @return bool
   */
  public function hasConfiguration() {
    return true;
  }

  /**
   * When this field type has additional configuration you can add
   * the fields on the form with this function.
   *
   * @param array $field
   */
  public function buildConfigurationForm(\CRM_Core_Form $form, $field=array()) {
    // Example add a checkbox to the form.
    $group_type_api = civicrm_api3('OptionValye', 'get', array('is_active' => 1, 'option_group_id' => 'group_type', 'options' => array('limit' => 0)));
    $group_types = array();
    foreach($group_type_api['values'] as $group_type) {
      $group_types[$group_type['value']] = $group_type['title'];
    }
    $form->add('select', 'group_type', E::ts('Group Type'), $group_type, false, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- select -'),
      'multiple' => true,
    ));
    if (isset($field['configuration'])) {
      $form->setDefaults(array(
        'group_type' => $field['configuration']['group_type'],
      ));
    }
  }

  /**
   * Process the submitted values and create a configuration array
   *
   * @param $submittedValues   *
   * @return array
   */
  public function processConfiguration($submittedValues) {
    // Add the show_label to the configuration array.
    $configuration['group_type'] = $submittedValues['group_type'];
    return $configuration;
  }

  /**
   * When this field type has configuration specify the template file name
   * for the configuration form.
   *
   * @return false|string
   */
  public function getConfigurationTemplateFileName() {
    return "CRM/Searchactiondesigner/Form/FieldConfiguration/GroupTypeField.tpl";
  }

  /**
   * Add the field to the task form
   *
   * @param \CRM_Core_Form $form
   * @param $field
   */
  public function addFieldToTaskForm(\CRM_Core_Form $form, $field) {
    $is_required = false;
    if (isset($field['is_required'])) {
      $is_required = $field['is_required'];
    }

    $groupApiParams['is_active'] = 1;
    if (isset($field['configuration']['group_type']) && is_array($field['configuration']['group_type'])) {
      $groupApiParams['group_type'] = array('IN' => $field['configuration']['group_type']);
    }
    $groupApiParams['options']['limit'] = 0;
    $groupApi = civicrm_api3('Group', 'get', $groupApiParams);
    $groups = array();
    foreach($groupApi['values'] as $group) {
      $groups[$group['id']] = $group['title'];
    }
    $form->add('select', $field['name'], $field['title'], $groups, $is_required, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- select -'),
    ));
  }

  /**
   * Return the template name of this field.
   *
   * return false|string
   */
  public function getFieldTemplateFileName() {
    return "CRM/Searchactiondesigner/Form/Field/GenericField.tpl";
  }


}