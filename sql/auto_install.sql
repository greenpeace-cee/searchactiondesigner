-- +--------------------------------------------------------------------+
-- | CiviCRM version 5                                                  |
-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC (c) 2004-2019                                |
-- +--------------------------------------------------------------------+
-- | This file is a part of CiviCRM.                                    |
-- |                                                                    |
-- | CiviCRM is free software; you can copy, modify, and distribute it  |
-- | under the terms of the GNU Affero General Public License           |
-- | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
-- |                                                                    |
-- | CiviCRM is distributed in the hope that it will be useful, but     |
-- | WITHOUT ANY WARRANTY; without even the implied warranty of         |
-- | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
-- | See the GNU Affero General Public License for more details.        |
-- |                                                                    |
-- | You should have received a copy of the GNU Affero General Public   |
-- | License and the CiviCRM Licensing Exception along                  |
-- | with this program; if not, contact CiviCRM LLC                     |
-- | at info[AT]civicrm[DOT]org. If you have questions about the        |
-- | GNU Affero General Public License or the licensing of CiviCRM,     |
-- | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--


-- +--------------------------------------------------------------------+
-- | CiviCRM version 5                                                  |
-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC (c) 2004-2019                                |
-- +--------------------------------------------------------------------+
-- | This file is a part of CiviCRM.                                    |
-- |                                                                    |
-- | CiviCRM is free software; you can copy, modify, and distribute it  |
-- | under the terms of the GNU Affero General Public License           |
-- | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
-- |                                                                    |
-- | CiviCRM is distributed in the hope that it will be useful, but     |
-- | WITHOUT ANY WARRANTY; without even the implied warranty of         |
-- | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
-- | See the GNU Affero General Public License for more details.        |
-- |                                                                    |
-- | You should have received a copy of the GNU Affero General Public   |
-- | License and the CiviCRM Licensing Exception along                  |
-- | with this program; if not, contact CiviCRM LLC                     |
-- | at info[AT]civicrm[DOT]org. If you have questions about the        |
-- | GNU Affero General Public License or the licensing of CiviCRM,     |
-- | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
-- +--------------------------------------------------------------------+
--
-- Generated from drop.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the exisiting tables
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_search_task_field`;
DROP TABLE IF EXISTS `civicrm_search_task_action`;
DROP TABLE IF EXISTS `civicrm_search_task`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_search_task
-- *
-- *******************************************************/
CREATE TABLE `civicrm_search_task` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique SearchTask ID',
     `title` varchar(255) NOT NULL   ,
     `description` text NULL   ,
     `success_message` text NULL   ,
     `help_text` text NULL   ,
     `type` varchar(255) NOT NULL   ,
     `is_active` tinyint NOT NULL   ,
     `status` int unsigned NULL  DEFAULT 0 ,
     `records_per_batch` int unsigned NOT NULL  DEFAULT 25 ,
     `source_file` varchar(255) NULL    
,
        PRIMARY KEY (`id`)
 
 
 
)    ;

-- /*******************************************************
-- *
-- * civicrm_search_task_action
-- *
-- *******************************************************/
CREATE TABLE `civicrm_search_task_action` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique SearchTaskAction ID',
     `search_task_id` int unsigned NOT NULL   COMMENT 'FK to Search Task',
     `name` varchar(255) NULL   ,
     `title` varchar(255) NOT NULL   ,
     `type` varchar(255) NOT NULL   ,
     `mapping` text NULL   ,
     `configuration` text NULL   ,
     `condition_configuration` text NULL   ,
     `weight` int NULL    
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_search_task_action_search_task_id FOREIGN KEY (`search_task_id`) REFERENCES `civicrm_search_task`(`id`) ON DELETE CASCADE  
)    ;

-- /*******************************************************
-- *
-- * civicrm_search_task_field
-- *
-- * Field for the Search Task
-- *
-- *******************************************************/
CREATE TABLE `civicrm_search_task_field` (


     `id` int unsigned NOT NULL AUTO_INCREMENT  COMMENT 'Unique SearchTaskField ID',
     `search_task_id` int unsigned NOT NULL   COMMENT 'FK to Search Task',
     `name` varchar(255) NULL   ,
     `title` varchar(255) NOT NULL   ,
     `type` varchar(255) NOT NULL   ,
     `is_required` tinyint NULL   ,
     `default_value` text NULL   ,
     `configuration` text NULL   ,
     `weight` int NULL    
,
        PRIMARY KEY (`id`)
 
 
,          CONSTRAINT FK_civicrm_search_task_field_search_task_id FOREIGN KEY (`search_task_id`) REFERENCES `civicrm_search_task`(`id`) ON DELETE CASCADE  
)    ;

 
