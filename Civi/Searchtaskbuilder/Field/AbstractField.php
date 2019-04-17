<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchtaskbuilder\Field;

abstract class AbstractField {

  /**
   * Returns true when this field has additional configuration
   *
   * @return bool
   */
  public function hasConfiguration() {
    return false;
  }

  /**
   * When this field type has additional configuration you can add
   * the fields on the form with this function.
   *
   * @param \CRM_Core_Form $form
   * @param array $field
   */
  public function buildConfigurationForm(\CRM_Core_Form $form, $field=array()) {
    // Example add a checkbox to the form.
    // $form->add('checkbox', 'show_label', E::ts('Show label'));
  }

  /**
   * When this field type has configuration specify the template file name
   * for the configuration form.
   *
   * @return false|string
   */
  public function getConfigurationTemplateFileName() {
    // Example return "CRM/Searchtaskbuilder/Form/FieldConfiguration/TextField.tpl";
    return false;
  }


  /**
   * Process the submitted values and create a configuration array
   *
   * @param $submittedValues   *
   * @return array
   */
  public function processConfiguration($submittedValues) {
    // Add the show_label to the configuration array.
    // $configuration['show_label'] = $submittedValues['show_label'];
    // return $configuration;
    return array();
  }

  /**
   * Add the field to the task form
   *
   * @param \CRM_Core_Form $form
   * @param $field
   */
  public function addFieldToTaskForm(\CRM_Core_Form $form, $field) {
    // $form->add('text', $field['name'], $field['title'], $field['is_required']);
  }

  /**
   * Return the template name of this field.
   *
   * return false|string
   */
  public function getFieldTemplateFileName() {
    return "CRM/Searchtaskbuilder/Form/Field/GenericField.tpl";
  }

}