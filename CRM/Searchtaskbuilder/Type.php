<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_Type {

  private static $_types;

  /**
   * Returns a list of possible search task types.
   */
  public static function getTypes() {
    if (!self::$_types) {
      self::$_types = [
        'contact' => [
          'title' => E::ts('Contact search tasks'),
          'class' => 'CRM_Searchtaskbuilder_Form_Task_Contact',
        ],
        // @Todo Implement the types below
        /*'activity' => ['title' => E::ts('Activity search tasks')],
        'contribution' => ['title' => E::ts('Contribution search tasks')],
        'membership' => ['title' => E::ts('Membership search tasks')],
        'participant' => ['title' => E::ts('Participant search tasks')],
        'case' => ['title' => E::ts('Case search tasks')],*/
      ];

      $hook = CRM_Utils_Hook::singleton();
      $hook->invoke(['types'], self::$_types, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, CRM_Core_DAO::$_nullObject, 'search_task_types');
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

  public static function getClassNameByType($type) {
    $types = self::getTypes();
    return $types[$type]['class'];
  }

}