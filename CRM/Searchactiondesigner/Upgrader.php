<?php
use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Searchactiondesigner_Upgrader extends CRM_Searchactiondesigner_Upgrader_Base {


  public function install() {

  }

  public function uninstall() {

  }

  public function upgrade_1001() {
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_search_task` ADD COLUMN `permission` varchar(255) NULL;");
    return TRUE;
  }

  /**
   * Look up extension dependency error messages and display as Core Session Status
   *
   * @param array $unmet
   */
  public static function displayDependencyErrors(array $unmet){
    foreach ($unmet as $ext) {
      $message = self::getUnmetDependencyErrorMessage($ext);
      CRM_Core_Session::setStatus($message, E::ts('Prerequisite check failed.'), 'error');
    }
  }

  /**
   * Mapping of extensions names to localized dependency error messages
   *
   * @param string $unmet an extension name
   */
  public static function getUnmetDependencyErrorMessage($unmet) {
    switch ($unmet) {
      case 'action-provider':
        return ts('Search Action Designer was installed successfully, but you must also install and enable the <a href="%1">action-provider Extension (version 1.37 or newer)</a>.', array(1 => 'https://lab.civicrm.org/extensions/action-provider'));
      case 'formfieldlibrary':
        return ts('Search Action Designer was installed successfully, but you must also install and enable the <a href="%1">formfieldlibrary Extension (version 1.11 or newer)</a>.', array(1 => 'https://lab.civicrm.org/extensions/formfieldlibrary'));
    }

    CRM_Core_Error::fatal(ts('Unknown error key: %1', array(1 => $unmet)));
  }

  /**
   * Extension Dependency Check
   *
   * @return Array of names of unmet extension dependencies; NOTE: returns an
   *         empty array when all dependencies are met.
   */
  public static function checkExtensionDependencies() {
    $unmet = array('action-provider', 'formfieldlibrary');
    $extensions = civicrm_api3('Extension', 'get', array('options' => array('limit' => 0)));
    foreach($extensions['values'] as $ext) {
      if ($ext['key'] == 'action-provider' && $ext['status'] == 'installed') {
        if (version_compare($ext['version'], '1.37', '>=')) {
          unset($unmet[array_search('action-provider', $unmet)]);
        }
      }
      if ($ext['key'] == 'formfieldlibrary' && $ext['status'] == 'installed') {
        if (version_compare($ext['version'], '1.11', '>=')) {
          unset($unmet[array_search('formfieldlibrary', $unmet)]);
        }
      }
    }
    return $unmet;
  }

}
