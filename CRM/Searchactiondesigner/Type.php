<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Type {

  private static $_types;

  /**
   * Returns a list of possible search task types.
   */
  public static function getTypes() {
    if (!self::$_types) {
      self::$_types = array(
        'contact' => array(
          'title' => E::ts('Contact search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Contact',
          'configuration_class' => 'CRM_Searchactiondesigner_Form_Configuration_Contact',
          'id_field_title' => E::ts('Contact ID'),
        ),
        'activity' => array(
          'title' => E::ts('Activity search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Activity',
          'id_field_title' => E::ts('Activity ID'),
        ),
        'contribution' => array(
          'title' => E::ts('Contribution search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Contribution',
          'id_field_title' => E::ts('Contribution ID'),
        ),
        'membership' => array(
          'title' => E::ts('Membership search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Membership',
          'id_field_title' => E::ts('Membership ID'),
        ),
        'event' => array(
          'title' => E::ts('Event Participant search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Event',
          'id_field_title' => E::ts('Participant ID'),
        ),
        'case' => array(
          'title' => E::ts('Case search tasks'),
          'class' => 'CRM_Searchactiondesigner_Form_Task_Case',
          'id_field_title' => E::ts('Case ID'),
          'configuration_class' => 'CRM_Searchactiondesigner_Form_Configuration_Case',
        ),
      );

      $hook = CRM_Utils_Hook::singleton();
      $hook->invoke(['types'], self::$_types, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, 'search_action_designer_types');
    }
    return self::$_types;
  }

  /**
   * Return a list with the search task type titles
   *
   * @return array
   */
  public static function getTitles() {
    $types = self::getTypes();
    $titles = array();
    foreach($types as $name => $type) {
      $titles[$name] = $type['title'];
    }
    return $titles;
  }

  /**
   * Returns the class name of the task type
   *
   * @param $type
   * @return mixed
   */
  public static function getClassNameByType($type) {
    $types = self::getTypes();
    return $types[$type]['class'];
  }

  public static function getConfigurationClass(string $type):? CRM_Searchactiondesigner_Form_ConfigurationInterface {
    $types = self::getTypes();
    if (!empty($types[$type]['configuration_class'])) {
      $configurationClassName = $types[$type]['configuration_class'];
      return new $configurationClassName();
    }
    return null;
  }

  /**
   * Returns the title of the task type ID field
   *
   * @param $type
   * @return mixed
   */
  public static function getIdFieldTitle($type) {
    $types = self::getTypes();
    return $types[$type]['id_field_title'];
  }

}
