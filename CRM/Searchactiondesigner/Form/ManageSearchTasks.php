<?php

use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Searchactiondesigner_Form_ManageSearchTasks extends CRM_Core_Form {

  public function preProcess() {
    parent::preProcess();

    $formValues = $this->getSubmitValues();

    $this->setTitle(E::ts('Manage Search Tasks'));

    $whereClauses = array("1");
    if (isset($formValues['title']) && !empty($formValues['title'])) {
      $whereClauses[] = "`title` LIKE '%". CRM_Utils_Type::escape($formValues['title'], 'String')."%'";
    }
    if (isset($formValues['description']) && !empty($formValues['description'])) {
      $whereClauses[]  = "`description` LIKE '%". CRM_Utils_Type::escape($formValues['description'], 'String')."%'";
    }
    if (isset($formValues['is_active']) && $formValues['is_active'] == '0') {
      $whereClauses[] = "`is_active` = 0";
    } elseif (isset($formValues['is_active']) && $formValues['is_active'] == '1') {
      $whereClauses[] = "`is_active` = 1";
    }
    if (isset($formValues['type'])) {
      $typeClauses = array();
      foreach($formValues['type'] as $type) {
        $typeClauses[] = "`type` = '".CRM_Utils_Type::escape($type, 'String')."'";
      }
      if (count($typeClauses)) {
        $whereClauses[] = "(".implode(" OR ", $typeClauses).")";
      }
    }

    $whereStatement = implode(" AND ", $whereClauses);
    $sql = "SELECT * FROM civicrm_search_task WHERE {$whereStatement} ORDER BY is_active, title";
    $searchTasks = array();
    $dao = CRM_Core_DAO::executeQuery($sql, array(), false, 'CRM_Searchactiondesigner_DAO_SearchTask');
    while($dao->fetch()) {
      $row = array();
      CRM_Core_DAO::storeValues($dao, $row);
      switch ($row['status']) {
        case CRM_Searchactiondesigner_Status::IN_CODE:
          $row['status_label'] = E::ts('In code');
          break;
        case CRM_Searchactiondesigner_Status::OVERRIDDEN:
          $row['status_label'] = E::ts('Overridden');
          break;
        case CRM_Searchactiondesigner_Status::IN_DATABASE:
          $row['status_label'] = E::ts('In database');
          break;
      }
      $searchTasks[] = $row;
    }
    $this->assign('search_tasks', $searchTasks);
    $this->assign('types', CRM_Searchactiondesigner_Type::getTitles());

    $session = CRM_Core_Session::singleton();
    $qfKey = CRM_Utils_Request::retrieve('qfKey', 'String', $this);
    $urlPath = CRM_Utils_System::getUrlPath();
    $urlParams = 'force=1';
    if ($qfKey) {
      $urlParams .= "&qfKey=$qfKey";
    }
    $session->replaceUserContext(CRM_Utils_System::url($urlPath, $urlParams));
  }

  public function buildQuickForm() {
    parent::buildQuickForm();

    $this->add('text', 'title', E::ts('Title contains'), array('class' => 'huge'));
    $this->add('text', 'description', E::ts('Description contains'), array('class' => 'huge'));
    $this->add('select', 'type', E::ts('Available for'), CRM_Searchactiondesigner_Type::getTitles(), TRUE, array(
      'style' => 'min-width:250px',
      'class' => 'crm-select2 huge',
      'placeholder' => E::ts('- select -'),
      'multiple' => true,
    ));

    $this->addYesNo('is_active', E::ts('Is active'), true);

    $this->addButtons(array(
      array(
        'type' => 'refresh',
        'name' => E::ts('Search'),
        'isDefault' => TRUE,
      ),
    ));
  }

  public function postProcess() {

  }

}
