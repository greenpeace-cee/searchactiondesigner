<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchactiondesigner_ExtensionUtil as E;

class CRM_Searchactiondesigner_Status {

  const UNKNOWN = -1;
  const IN_DATABASE = 0;
  const IN_CODE = 1;
  const OVERRIDDEN = 2;

  public static function statusLabels() {
    return array(
      CRM_Searchactiondesigner_Status::IN_DATABASE => E::ts('In database'),
      CRM_Searchactiondesigner_Status::IN_CODE => E::ts('In code'),
      CRM_Searchactiondesigner_Status::OVERRIDDEN => E::ts('Overriden'),
    );
  }

}