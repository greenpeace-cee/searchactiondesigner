<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchactiondesigner\Field;

use CRM_Searchactiondesigner_ExtensionUtil as E;

class MessageTemplate extends AbstractField {


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

    $message_template_api = civicrm_api3('MessageTemplate', 'get', array('is_active' => 1, 'workflow_id' => array("IS NULL" => 1), 'options' => array('limit' => 0)));
    $message_templates = array();
    foreach($message_template_api['values'] as $message_template) {
      $message_templates[$message_template['id']] = $message_template['msg_title'];
    }
    $form->add('select', $field['name'], $field['title'], $message_templates, $is_required, array(
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

  /**
   * Return the submitted field value
   *
   * @param $field
   * @param $submittedValues
   * @return mixed
   */
  public function getSubmittedFieldValue($field, $submittedValues) {
    $messageTemplateId = $submittedValues[$field['name']];
    $messageTemplate = civicrm_api3('MessageTemplate', 'getsingle', array('id' => $messageTemplateId));
    if (isset($messageTemplate['msg_html']) && !empty($messageTemplate['msg_html'])) {
      return $messageTemplate['msg_html'];
    } elseif (isset($messageTemplate['msg_text']) && !empty($messageTemplate['msg_text'])) {
      return $messageTemplate['msg_text'];
    }
    return '';
  }



}