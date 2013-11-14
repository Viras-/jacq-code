-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. Nov 2013 um 04:36
-- Server Version: 5.5.33
-- PHP-Version: 5.3.17

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `jacq_input`
--

--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_accessBotanicalObject`
--

TRUNCATE TABLE `frmwrk_accessBotanicalObject`;
--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_accessOrganisation`
--

TRUNCATE TABLE `frmwrk_accessOrganisation`;
--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_AuthAssignment`
--

TRUNCATE TABLE `frmwrk_AuthAssignment`;
--
-- Daten für Tabelle `frmwrk_AuthAssignment`
--

INSERT INTO `frmwrk_AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('editorLivingplant', 2, NULL, 'N;'),
('grp_admin', 1, NULL, 'N;');

--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_AuthItem`
--

TRUNCATE TABLE `frmwrk_AuthItem`;
--
-- Daten für Tabelle `frmwrk_AuthItem`
--

INSERT INTO `frmwrk_AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('acs_greenhouse', 1, 'Allow access to plants which are inside the greenhouse', '', 's:0:"";'),
('editorLivingplant', 2, 'editor for living plants', NULL, 'N;'),
('grp_admin', 2, 'main admin, has all roles assigned (so can do everything)', '', 's:0:"";'),
('grp_guest', 2, 'guest (default) role', '', 's:0:"";'),
('managerLivingplant', 2, 'manage living plants', NULL, 'N;'),
('managerOrganisation', 2, 'manage organisation entries', NULL, 'N;'),
('managerTreeRecordFile', 2, 'manage tree record files', NULL, 'N;'),
('oprtn_assignLabelType', 0, 'Assign label for printing', NULL, 'N;'),
('oprtn_clearLabelType', 0, 'Clear label assignment(s)', NULL, 'N;'),
('oprtn_createLivingplant', 0, 'create a living plant', '', 's:0:"";'),
('oprtn_createOrganisation', 0, 'create / update an organisation', NULL, 'N;'),
('oprtn_createTreeRecordFile', 0, 'create / update a tree record file', '', 's:0:"";'),
('oprtn_deleteLivingplant', 0, 'delete a living plant', '', 's:0:"";'),
('oprtn_deleteOrganisation', 0, 'delete an organisation entry', NULL, 'N;'),
('oprtn_deleteTreeRecordFile', 0, 'delete a tree record file', NULL, 'N;'),
('oprtn_readLivingplant', 0, 'read/show a living plant', '', 's:0:"";'),
('oprtn_showClassificationBrowser', 0, 'show classification browser', NULL, 'N;'),
('rbacManager', 2, 'manage RBAC', NULL, 'N;'),
('tsk_createOrganisation', 1, 'create / update an organisation', NULL, 'N;'),
('tsk_createTreeRecordFile', 1, 'create / update a tree record file', NULL, 'N;'),
('tsk_deleteLivingplant', 1, 'delete a living plant', '', 's:0:"";'),
('tsk_deleteOrganisation', 1, 'delete an organisation', NULL, 'N;'),
('tsk_deleteTreeRecordFile', 1, 'delete a tree record file', NULL, 'N;'),
('tsk_editLivingplant', 1, 'Edit a living plant', '', 's:0:"";'),
('tsk_managementLabels', 1, 'Label Manager', NULL, 'N;');

--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_AuthItemChild`
--

TRUNCATE TABLE `frmwrk_AuthItemChild`;
--
-- Daten für Tabelle `frmwrk_AuthItemChild`
--

INSERT INTO `frmwrk_AuthItemChild` (`parent`, `child`) VALUES
('grp_admin', 'acs_greenhouse'),
('grp_admin', 'editorLivingplant'),
('managerLivingplant', 'editorLivingplant'),
('grp_admin', 'grp_guest'),
('grp_admin', 'managerLivingplant'),
('grp_admin', 'managerOrganisation'),
('grp_admin', 'managerTreeRecordFile'),
('tsk_managementLabels', 'oprtn_assignLabelType'),
('tsk_managementLabels', 'oprtn_clearLabelType'),
('tsk_editLivingplant', 'oprtn_createLivingplant'),
('tsk_createOrganisation', 'oprtn_createOrganisation'),
('tsk_createTreeRecordFile', 'oprtn_createTreeRecordFile'),
('tsk_deleteLivingplant', 'oprtn_deleteLivingplant'),
('tsk_deleteOrganisation', 'oprtn_deleteOrganisation'),
('tsk_deleteTreeRecordFile', 'oprtn_deleteTreeRecordFile'),
('grp_guest', 'oprtn_readLivingplant'),
('oprtn_createLivingplant', 'oprtn_readLivingplant'),
('grp_guest', 'oprtn_showClassificationBrowser'),
('grp_admin', 'rbacManager'),
('managerOrganisation', 'tsk_createOrganisation'),
('managerTreeRecordFile', 'tsk_createTreeRecordFile'),
('managerLivingplant', 'tsk_deleteLivingplant'),
('managerOrganisation', 'tsk_deleteOrganisation'),
('managerTreeRecordFile', 'tsk_deleteTreeRecordFile'),
('editorLivingplant', 'tsk_editLivingplant'),
('managerLivingplant', 'tsk_managementLabels');

--
-- TRUNCATE Tabelle vor dem Einfügen `frmwrk_user`
--

TRUNCATE TABLE `frmwrk_user`;
--
-- Daten für Tabelle `frmwrk_user`
--

INSERT INTO `frmwrk_user` (`id`, `username`, `password`, `salt`) VALUES
(1, 'admin', '9ab6e20b2bb6fc6d5a8140a904af35c88a961f7d', 'hU8I9=ku'),
(2, 'editor', 'fe759e678a18e0b2d1239fa7ff1c29ae3c206227', 'jUhdia(14');
SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
