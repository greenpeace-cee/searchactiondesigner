<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from searchactiondesigner/xml/schema/CRM/Searchactiondesigner/SearchTask.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:398fa1c6001695b2406c1caab7fe9b6c)
 */
use CRM_Searchactiondesigner_ExtensionUtil as E;

/**
 * Database access object for the SearchTask entity.
 */
class CRM_Searchactiondesigner_DAO_SearchTask extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_search_task';

  /**
   * Field to show when displaying a record.
   *
   * @var string
   */
  public static $_labelField = 'title';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = FALSE;

  /**
   * Unique SearchTask ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $title;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $description;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $success_message;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $proceed_label;

  /**
   * @var string
   *   (SQL type: text)
   *   Note that values will be retrieved from the database as a string.
   */
  public $help_text;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $type;

  /**
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $status;

  /**
   * @var int|string
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $records_per_batch;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $source_file;

  /**
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $permission;

  /**
   * @var string
   *   (SQL type: longtext)
   *   Note that values will be retrieved from the database as a string.
   */
  public $configuration;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_search_task';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Search Tasks') : E::ts('Search Task');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique SearchTask ID'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.id',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.title',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.name',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Description'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.description',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'success_message' => [
          'name' => 'success_message',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Success Message'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.success_message',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'proceed_label' => [
          'name' => 'proceed_label',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Proceed label'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.proceed_label',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'help_text' => [
          'name' => 'help_text',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Help text'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.help_text',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'type' => [
          'name' => 'type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Type'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.type',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is active'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.is_active',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'status' => [
          'name' => 'status',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Status'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.status',
          'default' => '0',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'records_per_batch' => [
          'name' => 'records_per_batch',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Number of records per batch'),
          'required' => TRUE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.records_per_batch',
          'default' => '25',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'source_file' => [
          'name' => 'source_file',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Source File'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.source_file',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'permission' => [
          'name' => 'permission',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Permission'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.permission',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'add' => NULL,
        ],
        'configuration' => [
          'name' => 'configuration',
          'type' => CRM_Utils_Type::T_LONGTEXT,
          'title' => E::ts('Configuration'),
          'required' => FALSE,
          'usage' => [
            'import' => FALSE,
            'export' => FALSE,
            'duplicate_matching' => FALSE,
            'token' => FALSE,
          ],
          'where' => 'civicrm_search_task.configuration',
          'table_name' => 'civicrm_search_task',
          'entity' => 'SearchTask',
          'bao' => 'CRM_Searchactiondesigner_DAO_SearchTask',
          'localizable' => 0,
          'serialize' => self::SERIALIZE_JSON,
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'search_task', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'search_task', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
