<?php
/**
 * @author Jaap Jansma <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Searchtaskbuilder_ExtensionUtil as E;

class CRM_Searchtaskbuilder_Status {

  const IN_DATABASE = 0;
  const IN_CODE = 1;
  const OVERRIDDEN = 2;

  public static function statusLabels() {
    return array(
      CRM_Searchtaskbuilder_Status::IN_DATABASE => E::ts('In database'),
      CRM_Searchtaskbuilder_Status::IN_CODE => E::ts('In code'),
      CRM_Searchtaskbuilder_Status::OVERRIDDEN => E::ts('Overriden'),
    );
  }

}