<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchtaskbuilder\Field;

use CRM_Searchtaskbuilder_ExtensionUtil as E;

class TextField extends AbstractField {

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
    $form->add('checkbox', 'show_label', E::ts('Show label'));
    if (isset($field['configuration'])) {
      $form->setDefaults(array(
        'show_label' => $field['configuration']['show_label'],
      ));
    }
  }

  /**
   * When this field type has configuration specify the template file name
   * for the configuration form.
   *
   * @return false|string
   */
  public function getConfigurationTemplateFileName() {
    return "CRM/Searchtaskbuilder/Form/FieldConfiguration/TextField.tpl";
  }


  /**
   * Process the submitted values and create a configuration array
   *
   * @param $submittedValues   *
   * @return array
   */
  public function processConfiguration($submittedValues) {
    // Add the show_label to the configuration array.
    $configuration['show_label'] = $submittedValues['show_label'];
    return $configuration;
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
    $form->add('text', $field['name'], $field['title'], array(), $is_required);
  }

}