-- phpMyAdmin SQL Dump
-- version 4.1.13
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 05. Mrz 2015 um 19:14
-- Server Version: 5.6.12
-- PHP-Version: 5.4.20

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `jacq_input`
--

--
-- Daten für Tabelle `frmwrk_AuthAssignment`
--

INSERT INTO `frmwrk_AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('editorLivingplant', 2, NULL, 'N;'),
('grp_admin', 1, NULL, 'N;');

--
-- Daten für Tabelle `frmwrk_AuthItem`
--

INSERT INTO `frmwrk_AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('acs_greenhouse', 1, 'Allow access to plants which are inside the greenhouse', '', 's:0:"";'),
('editorLivingplant', 2, 'editor for living plants', NULL, 'N;'),
('grp_admin', 2, 'main admin, has all roles assigned (so can do everything)', '', 's:0:"";'),
('grp_gardener', 2, 'Group for Gardeners', NULL, 'N;'),
('grp_guest', 2, 'guest (default) role', '', 's:0:"";'),
('grp_labelPrinters', 2, 'Group for Label printing', NULL, 'N;'),
('grp_leadGardener', 2, 'Group for lead gardeners', NULL, 'N;'),
('grp_Scientist', 2, 'user groups for Scientists', '', 's:0:"";'),
('grp_volunteers', 2, 'user group for volunteers', NULL, 'N;'),
('managerLivingplant', 2, 'manage living plants', NULL, 'N;'),
('managerOrganisation', 2, 'manage organisation entries', NULL, 'N;'),
('managerScientificNameInformation', 2, 'Manager for Scientific Name Information', NULL, 'N;'),
('managerTreeRecordFile', 2, 'manage tree record files', NULL, 'N;'),
('oprtn_aclBotanicalObject', 0, 'ACL for botanical object level', NULL, 'N;'),
('oprtn_aclClassification', 0, 'ACL for classification level', NULL, 'N;'),
('oprtn_aclOrganisation', 0, 'ACL access for organisation level', NULL, 'N;'),
('oprtn_assignLabelType', 0, 'Assign label for printing', NULL, 'N;'),
('oprtn_clearLabelType', 0, 'Clear label assignment(s)', NULL, 'N;'),
('oprtn_createLivingplant', 0, 'create a living plant', '', 's:0:"";'),
('oprtn_createOrganisation', 0, 'create / update an organisation', NULL, 'N;'),
('oprtn_createScientificNameInformation', 0, 'Create / Update scientific name information', NULL, 'N;'),
('oprtn_createTreeRecordFile', 0, 'create / update a tree record file', '', 's:0:"";'),
('oprtn_createUser', 0, 'create user', '', 's:0:"";'),
('oprtn_deleteLivingplant', 0, 'delete a living plant', '', 's:0:"";'),
('oprtn_deleteOrganisation', 0, 'delete an organisation entry', NULL, 'N;'),
('oprtn_deleteTreeRecordFile', 0, 'delete a tree record file', NULL, 'N;'),
('oprtn_deleteUser', 0, 'delete user', NULL, 'N;'),
('oprtn_indexSeminum', 0, 'Access to index seminum actions', NULL, 'N;'),
('oprtn_inventory', 0, 'Inventory Access', NULL, 'N;'),
('oprtn_readLivingplant', 0, 'read/show a living plant', '', 's:0:"";'),
('oprtn_showClassificationBrowser', 0, 'show classification browser', NULL, 'N;'),
('oprtn_showStatistics', 0, 'show statistics', NULL, 'N;'),
('rbacManager', 2, 'manage RBAC', NULL, 'N;'),
('tsk_createOrganisation', 1, 'create / update an organisation', NULL, 'N;'),
('tsk_createTreeRecordFile', 1, 'create / update a tree record file', NULL, 'N;'),
('tsk_deleteLivingplant', 1, 'delete a living plant', '', 's:0:"";'),
('tsk_deleteOrganisation', 1, 'delete an organisation', NULL, 'N;'),
('tsk_deleteTreeRecordFile', 1, 'delete a tree record file', NULL, 'N;'),
('tsk_editLivingplant', 1, 'Edit a living plant', '', 's:0:"";'),
('tsk_inventory', 1, 'Inventory Access', NULL, 'N;'),
('tsk_manageACL', 1, 'manage ACL access', NULL, 'N;'),
('tsk_managementLabels', 1, 'Label Manager', NULL, 'N;'),
('tsk_managerUser', 1, 'manager users', NULL, 'N;'),
('tsk_manageScientificNameInformation', 1, 'Manager for Scientific Name Information', NULL, 'N;');

--
-- Daten für Tabelle `frmwrk_AuthItemChild`
--

INSERT INTO `frmwrk_AuthItemChild` (`parent`, `child`) VALUES
('grp_admin', 'acs_greenhouse'),
('grp_gardener', 'acs_greenhouse'),
('grp_admin', 'editorLivingplant'),
('managerLivingplant', 'editorLivingplant'),
('grp_leadGardener', 'grp_gardener'),
('grp_admin', 'grp_guest'),
('grp_gardener', 'grp_guest'),
('grp_volunteers', 'grp_guest'),
('grp_admin', 'managerLivingplant'),
('grp_admin', 'managerOrganisation'),
('grp_admin', 'managerScientificNameInformation'),
('grp_admin', 'managerTreeRecordFile'),
('tsk_manageACL', 'oprtn_aclBotanicalObject'),
('tsk_manageACL', 'oprtn_aclClassification'),
('tsk_manageACL', 'oprtn_aclOrganisation'),
('grp_leadGardener', 'oprtn_assignLabelType'),
('tsk_managementLabels', 'oprtn_assignLabelType'),
('tsk_managementLabels', 'oprtn_clearLabelType'),
('tsk_editLivingplant', 'oprtn_createLivingplant'),
('tsk_createOrganisation', 'oprtn_createOrganisation'),
('tsk_manageScientificNameInformation', 'oprtn_createScientificNameInformation'),
('tsk_createTreeRecordFile', 'oprtn_createTreeRecordFile'),
('tsk_managerUser', 'oprtn_createUser'),
('tsk_deleteLivingplant', 'oprtn_deleteLivingplant'),
('tsk_deleteOrganisation', 'oprtn_deleteOrganisation'),
('tsk_deleteTreeRecordFile', 'oprtn_deleteTreeRecordFile'),
('tsk_managerUser', 'oprtn_deleteUser'),
('grp_admin', 'oprtn_indexSeminum'),
('tsk_inventory', 'oprtn_inventory'),
('grp_Scientist', 'oprtn_readLivingplant'),
('grp_volunteers', 'oprtn_readLivingplant'),
('oprtn_createLivingplant', 'oprtn_readLivingplant'),
('grp_guest', 'oprtn_showClassificationBrowser'),
('grp_admin', 'oprtn_showStatistics'),
('grp_admin', 'rbacManager'),
('managerOrganisation', 'tsk_createOrganisation'),
('managerTreeRecordFile', 'tsk_createTreeRecordFile'),
('managerLivingplant', 'tsk_deleteLivingplant'),
('managerOrganisation', 'tsk_deleteOrganisation'),
('managerTreeRecordFile', 'tsk_deleteTreeRecordFile'),
('editorLivingplant', 'tsk_editLivingplant'),
('grp_gardener', 'tsk_editLivingplant'),
('grp_admin', 'tsk_inventory'),
('grp_admin', 'tsk_manageACL'),
('grp_labelPrinters', 'tsk_managementLabels'),
('managerLivingplant', 'tsk_managementLabels'),
('grp_admin', 'tsk_managerUser'),
('managerScientificNameInformation', 'tsk_manageScientificNameInformation');

--
-- Daten für Tabelle `frmwrk_user`
--

INSERT INTO `frmwrk_user` (`id`, `username`, `password`, `salt`, `user_type_id`, `employment_type_id`, `title_prefix`, `firstname`, `lastname`, `title_suffix`, `birthdate`, `organisation_id`, `force_password_change`) VALUES
(1, 'admin', '9a2195f1155c03534626b5272c695da3c553cbd8', 'P?R,lV=;j/', 2, 1, '', '', '', '', '2013-12-10', 4, 0),
(2, 'editor', '7bbc1c5812d76078e8b0954afe521dc7bcbcfd59', '=PHazE,4Sc', 1, 1, '', '', '', '', '0000-00-00', 1, 1);
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
