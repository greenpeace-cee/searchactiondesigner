<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'Cron:SearchTask.Import',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Import Search Tasks',
      'description' => 'Updates the search task(s) from source files. Run this job manually if you want to update your changes into the database.',
      'run_frequency' => 'Daily',
      'api_entity' => 'SearchTask',
      'api_action' => 'Import',
      'parameters' => '',
    ),
  ),
);
