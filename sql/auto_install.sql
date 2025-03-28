-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
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
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique SearchTask ID',
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NULL,
  `description` text NULL,
  `success_message` text NULL,
  `proceed_label` varchar(255) NULL,
  `help_text` text NULL,
  `type` varchar(255) NOT NULL,
  `is_active` tinyint NOT NULL,
  `status` int unsigned NULL DEFAULT 0,
  `records_per_batch` int unsigned NOT NULL DEFAULT 25,
  `source_file` varchar(255) NULL,
  `permission` varchar(255) NULL,
  `configuration` longtext NULL,
  PRIMARY KEY (`id`)
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_search_task_action
-- *
-- *******************************************************/
CREATE TABLE `civicrm_search_task_action` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique SearchTaskAction ID',
  `search_task_id` int unsigned NOT NULL COMMENT 'FK to Search Task',
  `name` varchar(255) NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `mapping` longtext NULL,
  `configuration` longtext NULL,
  `condition_configuration` longtext NULL,
  `weight` int NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_search_task_action_search_task_id FOREIGN KEY (`search_task_id`) REFERENCES `civicrm_search_task`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;

-- /*******************************************************
-- *
-- * civicrm_search_task_field
-- *
-- * Field for the Search Task
-- *
-- *******************************************************/
CREATE TABLE `civicrm_search_task_field` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique SearchTaskField ID',
  `search_task_id` int unsigned NOT NULL COMMENT 'FK to Search Task',
  `name` varchar(255) NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_required` tinyint NULL,
  `default_value` text NULL,
  `configuration` text NULL,
  `weight` int NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT FK_civicrm_search_task_field_search_task_id FOREIGN KEY (`search_task_id`) REFERENCES `civicrm_search_task`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;
