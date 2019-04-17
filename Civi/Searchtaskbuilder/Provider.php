<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

namespace Civi\Searchtaskbuilder;

use CRM_Searchtaskbuilder_ExtensionUtil as E;

class Provider {

  private $fieldClassNames = array();

  private $fieldTitles = array();

  public function __construct() {
    $this->addFieldType('text', 'Civi\Searchtaskbuilder\Field\TextField', E::ts('Text field'));
  }


  /**
   * @param $name
   * @param $class
   * @param $label
   * @return \Civi\Searchtaskbuilder\Provider
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
   * @return \Civi\Searchtaskbuilder\Field\AbstractField
   */
  public function getFieldTypeByName($name) {
    return new $this->fieldClassNames[$name]();
  }

}