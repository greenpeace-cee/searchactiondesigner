<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchactiondesigner;

use CRM_Searchactiondesigner_ExtensionUtil as E;

class Provider {

  private $fieldClassNames = array();

  private $fieldTitles = array();

  public function __construct() {
    $this->addFieldType('text', 'Civi\Searchactiondesigner\Field\TextField', E::ts('Text field'));
    $this->addFieldType('option_group', 'Civi\Searchactiondesigner\Field\OptionGroupField', E::ts('Option Group'));
    $this->addFieldType('group', 'Civi\Searchactiondesigner\Field\GroupField', E::ts('Group'));
    $this->addFieldType('message_template', 'Civi\Searchactiondesigner\Field\MessageTemplate', E::ts('Message Template'));
  }


  /**
   * @param $name
   * @param $class
   * @param $label
   * @return \Civi\Searchactiondesigner\Provider
   */
  public function addFieldType($name, $class, $label) {
    $this->fieldClassNames[$name] = $class;
    $this->fieldTitles[$name] = $label;
    return $this;
  }

  /**
   * @return array<String>
   */
  public function getFieldTypes() {
    return $this->fieldTitles;
  }

  /**
   * @param $name
   *
   * @return \Civi\Searchactiondesigner\Field\AbstractField
   */
  public function getFieldTypeByName($name) {
    return new $this->fieldClassNames[$name]();
  }

}