-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 22. Okt 2012 um 11:08
-- Server Version: 5.5.27
-- PHP-Version: 5.3.16

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `jacq_input`
--

SET foreign_key_checks = 0;

--
-- Daten für Tabelle `frmwrk_AuthAssignment`
--

INSERT INTO `frmwrk_AuthAssignment` (`itemname`, `userid`, `bizrule`, `data`) VALUES
('admin', '1', NULL, 'N;'),
('editorLivingplant', '2', NULL, 'N;');

--
-- Daten für Tabelle `frmwrk_AuthItem`
--

INSERT INTO `frmwrk_AuthItem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('admin', 2, 'main admin, has all roles assigned (so can do everything)', NULL, 'N;'),
('editorLivingplant', 2, 'editor for living plants', NULL, 'N;'),
('guest', 2, 'guest (default) role', NULL, 'N;'),
('managerLivingplant', 2, 'manage living plants', NULL, 'N;'),
('managerOrganisation', 2, 'manage organisation entries', NULL, 'N;'),
('managerTreeRecordFile', 2, 'manage tree record files', NULL, 'N;'),
('oprtn_createLivingplant', 0, 'create a living plant', '', 's:0:"";'),
('oprtn_createOrganisation', 0, 'create / update an organisation', NULL, 'N;'),
('oprtn_createTreeRecordFile', 0, 'create / update a tree record file', '', 's:0:"";'),
('oprtn_deleteLivingplant', 0, 'delete a living plant', '', 's:0:"";'),
('oprtn_deleteOrganisation', 0, 'delete an organisation entry', NULL, 'N;'),
('oprtn_deleteTreeRecordFile', 0, 'delete a tree record file', NULL, 'N;'),
('oprtn_readLivingplant', 0, 'read/show a living plant', '', 's:0:"";'),
('rbacManager', 2, 'manage RBAC', NULL, 'N;'),
('tsk_createOrganisation', 1, 'create / update an organisation', NULL, 'N;'),
('tsk_createTreeRecordFile', 1, 'create / update a tree record file', NULL, 'N;'),
('tsk_deleteLivingplant', 1, 'delete a living plant', '', 's:0:"";'),
('tsk_deleteOrganisation', 1, 'delete an organisation', NULL, 'N;'),
('tsk_deleteTreeRecordFile', 1, 'delete a tree record file', NULL, 'N;'),
('tsk_editLivingplant', 1, 'Edit a living plant', '', 's:0:"";');

--
-- Daten für Tabelle `frmwrk_AuthItemChild`
--

INSERT INTO `frmwrk_AuthItemChild` (`parent`, `child`) VALUES
('admin', 'editorLivingplant'),
('managerLivingplant', 'editorLivingplant'),
('admin', 'managerLivingplant'),
('admin', 'managerOrganisation'),
('admin', 'managerTreeRecordFile'),
('tsk_editLivingplant', 'oprtn_createLivingplant'),
('tsk_createOrganisation', 'oprtn_createOrganisation'),
('tsk_createTreeRecordFile', 'oprtn_createTreeRecordFile'),
('tsk_deleteLivingplant', 'oprtn_deleteLivingplant'),
('tsk_deleteOrganisation', 'oprtn_deleteOrganisation'),
('tsk_deleteTreeRecordFile', 'oprtn_deleteTreeRecordFile'),
('guest', 'oprtn_readLivingplant'),
('oprtn_createLivingplant', 'oprtn_readLivingplant'),
('admin', 'rbacManager'),
('managerOrganisation', 'tsk_createOrganisation'),
('managerTreeRecordFile', 'tsk_createTreeRecordFile'),
('managerLivingplant', 'tsk_deleteLivingplant'),
('managerOrganisation', 'tsk_deleteOrganisation'),
('managerTreeRecordFile', 'tsk_deleteTreeRecordFile'),
('editorLivingplant', 'tsk_editLivingplant');

--
-- Daten für Tabelle `frmwrk_user`
--

INSERT INTO `frmwrk_user` (`id`, `username`, `password`, `salt`) VALUES
(1, 'admin', '9ab6e20b2bb6fc6d5a8140a904af35c88a961f7d', 'hU8I9=ku'),
(2, 'editor', 'fe759e678a18e0b2d1239fa7ff1c29ae3c206227', 'jUhdia(14');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

SET foreign_key_checks = 1;