-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2015 at 05:15 AM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `workflowstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminlogin`
--

CREATE TABLE IF NOT EXISTS `adminlogin` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `userName` varchar(100) NOT NULL DEFAULT '',
  `userType` enum('1','2') NOT NULL COMMENT '1=administrator 2=other User',
  `password` varchar(250) NOT NULL DEFAULT '',
  `emailId` varchar(250) NOT NULL DEFAULT '',
  `hash` varchar(250) NOT NULL DEFAULT '',
  `adminLevelId` int(5) NOT NULL DEFAULT '0',
  `lastLogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `addDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `addedBy` tinyint(2) NOT NULL DEFAULT '0',
  `modDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modBy` tinyint(2) NOT NULL DEFAULT '0',
  `status` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `adminlogin`
--

INSERT INTO `adminlogin` (`id`, `userName`, `userType`, `password`, `emailId`, `hash`, `adminLevelId`, `lastLogin`, `addDate`, `addedBy`, `modDate`, `modBy`, `status`) VALUES
(2, 'ashish8', '1', '21232f297a57a5a743894a0e4a801fc3', 'ashish.joshi@infogain.com', '', 0, '2014-05-21 10:23:48', '2014-05-05 00:00:00', 0, '0000-00-00 00:00:00', 0, '1'),
(3, 'arunv2', '1', '21232f297a57a5a743894a0e4a801fc3', 'arun.verma@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(9, 'paragp', '2', '', 'Parag.Patil@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(10, 'shivanig', '1', '', 'shivani.gupta@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(11, 'vabbi', '2', '', 'vineet.abbi@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(12, 'nagak', '2', '', 'Nagarathna.kulkarni@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(13, 'sharaf', '1', '', 'Sharaf.Ahmad@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(14, 'paragj', '2', '', 'Parag.Joshi@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(15, 'pclark', '2', '', 'Phillip.Clark@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(16, 'rrao', '2', '', 'Ravindra.rao@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(17, 'pavand', '2', '', 'Pavan.Desai@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(18, 'abhit', '1', '', 'Abhi.Thakur@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(20, 'bestinj', '2', '', 'BESTIN.JOSE@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(21, 'sinhaa', '2', '', 'Abhishek.Sinha@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(22, 'charshit', '1', '', 'harshit.chawla@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(23, 'gashutos', '1', '', 'ashutosh.garg@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(24, 'karuppuk', '1', '', 'Karuppusamy.Komarasamy@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(25, 'gkalia', '1', '', 'gaurav.kalia@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(26, 'bsachin', '1', '', 'sachin.balmane@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0'),
(27, 'arjunan', '1', '', 'Prabu.Arjunan@netapp.com', '', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE IF NOT EXISTS `alert` (
  `alertId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` longtext NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '''0''=Inactive ''1'' = Active',
  `lastUpdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`alertId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `alert`
--

INSERT INTO `alert` (`alertId`, `message`, `status`, `lastUpdate`) VALUES
(1, 'test me', '1', '2015-10-20 12:13:36');

-- --------------------------------------------------------

--
-- Table structure for table `deprecatedpack`
--

CREATE TABLE IF NOT EXISTS `deprecatedpack` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `depComment` text NOT NULL,
  `depPackVersion` varchar(30) NOT NULL,
  `depPackId` varchar(20) NOT NULL,
  `depPackType` varchar(50) NOT NULL,
  `depBy` varchar(100) NOT NULL,
  `depDate` varchar(100) NOT NULL,
  `flag` enum('0','1') NOT NULL COMMENT '0=inactive, 1=active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `deprecatedpack`
--

INSERT INTO `deprecatedpack` (`id`, `depComment`, `depPackVersion`, `depPackId`, `depPackType`, `depBy`, `depDate`, `flag`) VALUES
(2, '<font color = red>Deprecated </font>', '1.0.0', '1', 'Workflow', 'abhit', '2015-10-17 00:26:17', '1');

-- --------------------------------------------------------

--
-- Table structure for table `download_history`
--

CREATE TABLE IF NOT EXISTS `download_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `packId` int(10) unsigned NOT NULL,
  `packType` varchar(50) NOT NULL DEFAULT 'workflow',
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `packName` varchar(200) NOT NULL,
  `packVersion` varchar(30) NOT NULL,
  ` uuid` varchar(100) NOT NULL,
  `minWfaVersion` varchar(30) NOT NULL,
  `author` varchar(50) NOT NULL,
  `certifiedBy` varchar(200) NOT NULL,
  `packDate` varchar(100) NOT NULL,
  `companyName` varchar(200) NOT NULL,
  `companyAddress` varchar(300) NOT NULL,
  `downloadDate` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `download_history`
--

INSERT INTO `download_history` (`id`, `userId`, `packId`, `packType`, `firstName`, `lastName`, `packName`, `packVersion`, ` uuid`, `minWfaVersion`, `author`, `certifiedBy`, `packDate`, `companyName`, `companyAddress`, `downloadDate`) VALUES
(1, 'shivanig', 3, 'workflow', 'shivani', 'gupta', 'chandank test new version', '1.1.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-16 23:26:36', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-16 23:27:31'),
(2, 'arunv2', 3, 'workflow', 'arun', 'verma', 'chandank test new version', '1.1.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-16 23:26:36', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-16 23:53:34'),
(3, 'shivanig', 4, 'workflow', 'shivani', 'gupta', 'Dummy Fibre Channel pack', '1.2.5', '', '3.0.0.0.0', 'Arun Verma', 'NONE', '2015-10-16 23:49:48', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-17 00:04:27'),
(4, 'ashish8', 2, 'workflow', 'ashish', 'joshi', 'chandank test', '1.2.0', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-16 23:24:40', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-17 00:05:32'),
(5, 'ashish8', 5, 'workflow', 'ashish', 'joshi', 'chandank test', '1.1.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-16 23:57:02', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-17 00:06:19'),
(6, 'shivanig', 6, 'workflow', 'shivani', 'gupta', 'chandank test new version', '1.1.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-17 00:09:28', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-17 00:10:46'),
(7, 'idmtestint01', 10, 'workflow', 'siebel', 'test', 'Datasource Script for VMware vCenter 6.0', '1.1.0', '', '3.1.0', 'NetApp', 'NETAPP', '2015-10-18 23:30:21', 'Network appliance', ' ', '2015-10-19 03:08:17'),
(8, 'abhit', 12, 'workflow', 'abhi', 'thakur', 'Re-create SnapMirror and SnapVault protection after MetroCluster switchover and switchback', '1.0.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-19 03:27:32', 'Network appliance, inc', 'cinnabar hills egl software park, off intermediate ring road', '2015-10-19 03:28:55'),
(9, 'abhit', 12, 'workflow', 'abhi', 'thakur', 'Re-create SnapMirror and SnapVault protection after MetroCluster switchover and switchback', '1.0.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-19 03:27:32', 'Network appliance, inc', 'cinnabar hills egl software park, off intermediate ring road', '2015-10-19 03:29:24'),
(10, 'shivanig', 12, 'workflow', 'shivani', 'gupta', 'Re-create SnapMirror and SnapVault protection after MetroCluster switchover and switchback', '1.0.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-19 03:27:32', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-19 03:30:22'),
(11, 'idmstageuser314', 18, 'workflow', 'stageuser', 'test314', 'Dummy Fibre Channel pack', '1.2.5', '', '3.0.0.0.0', 'Arun Verma', 'NETAPP', '2015-10-20 03:34:56', 'Network appliance', ' ', '2015-10-20 03:36:08'),
(12, 'idmstageuser314', 18, 'workflow', 'stageuser', 'test314', 'Dummy Fibre Channel pack', '1.2.5', '', '3.0.0.0.0', 'Arun Verma', 'NETAPP', '2015-10-20 03:34:56', 'Network appliance', ' ', '2015-10-20 03:53:34'),
(13, 'shivanig', 5, 'performance', 'shivani', 'gupta', 'test name', '12.0.0', '', '11.0.0', '', 'NETAPP', '2015-10-20 05:25:05', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-20 05:25:55'),
(14, 'idmtestint01', 24, 'workflow', 'siebel', 'test', 'Test File To Test DAR UPLOAD', '1.0.0', '', '3.1', '', 'NONE', '2015-10-20 07:01:06', 'Network appliance', ' ', '2015-10-20 07:03:05'),
(15, 'charshit', 6, 'report', 'harshit', 'chawla', 'Fiber Channel Report', '1.2.3.4.5', '', '12.34.AB', '', 'NONE', '', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-20 21:27:26');

-- --------------------------------------------------------

--
-- Table structure for table `flagreport`
--

CREATE TABLE IF NOT EXISTS `flagreport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flagPackUuid` varchar(100) DEFAULT NULL,
  `flagPackName` varchar(150) NOT NULL,
  `flagPackVersion` varchar(30) NOT NULL,
  `trademark` varchar(10) NOT NULL DEFAULT 'false',
  `infringement` varchar(10) NOT NULL DEFAULT 'false',
  `flagComment` text NOT NULL,
  `flagPackType` varchar(100) NOT NULL,
  `flagBy` varchar(150) NOT NULL,
  `flagPackOwner` varchar(150) NOT NULL,
  `flagDate` varchar(100) NOT NULL,
  `flagStatus` enum('0','1') NOT NULL COMMENT '0=inactive, 1=active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `flagreport`
--

INSERT INTO `flagreport` (`id`, `flagPackUuid`, `flagPackName`, `flagPackVersion`, `trademark`, `infringement`, `flagComment`, `flagPackType`, `flagBy`, `flagPackOwner`, `flagDate`, `flagStatus`) VALUES
(1, 'ba11fac4-9710-4f2b-90ed-a7efbd4e723a', 'Manage SnapMirror-SnapVault Cascade Relationship', '1.0.0', 'true', 'true', 'test comments.', 'Workflow', 'shivanig', 'Arun.Verma@netapp.com', '2015-10-17 03:28:21', '0'),
(2, 'ba11fac4-9710-4f2b-90ed-a7efbd4e723a', 'Manage SnapMirror-SnapVault Cascade Relationship', '1.0.0', 'true', 'false', 'Test grievance comment', 'Workflow', 'gashutos', 'Arun.Verma@netapp.com', '2015-10-17 04:02:50', '1');

-- --------------------------------------------------------

--
-- Table structure for table `ocumreports`
--

CREATE TABLE IF NOT EXISTS `ocumreports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reportName` varchar(120) NOT NULL,
  `reportDescription` text NOT NULL,
  `reportVersion` varchar(30) NOT NULL,
  `versionChanges` text,
  `OCUMVersion` varchar(30) NOT NULL,
  `minONTAPVersion` varchar(30) NOT NULL,
  `maxONTAPVersion` varchar(30) DEFAULT NULL,
  `otherPrerequisite` text,
  `certifiedBy` varchar(20) NOT NULL,
  `authorName` varchar(120) NOT NULL,
  `authorEmail` varchar(150) NOT NULL,
  `authorContact` varchar(150) NOT NULL,
  `reportFilePath` varchar(200) DEFAULT NULL,
  `reportDate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ocumreports`
--

INSERT INTO `ocumreports` (`id`, `reportName`, `reportDescription`, `reportVersion`, `versionChanges`, `OCUMVersion`, `minONTAPVersion`, `maxONTAPVersion`, `otherPrerequisite`, `certifiedBy`, `authorName`, `authorEmail`, `authorContact`, `reportFilePath`, `reportDate`) VALUES
(1, 'Report Name', 'test report test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report.\r\n1. test reporttest reporttest reporttest report\r\n2. test reporttest reporttest reporttest reporttest reporttest reporttest report\r\n3. test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report\r\n4. test reporttest reporttest report.', '1.0.0', 'Changes:\r\ntest report test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report.\r\n1. test reporttest reporttest reporttest report\r\n2. test reporttest reporttest reporttest reporttest reporttest reporttest report\r\n3. test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report\r\n4. test reporttest reporttest report.', '1.0.0', '1.1.1', '99.99.99', 'Other:\r\ntest report test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report.\r\n1. test reporttest reporttest reporttest report\r\n2. test reporttest reporttest reporttest reporttest reporttest reporttest report\r\n3. test reporttest reporttest reporttest reporttest reporttest reporttest reporttest reporttest report\r\n4. test reporttest reporttest report.', 'NETAPP', 'test author', 'test@email.com', '9000000000', 'wfs_data/ocum_2015-10-16-23-42-27-405/Fibre_Channel_server_configuration_and_provisioning_pack (2) (2) (1).zip', '2015-10-16 23:42:42'),
(2, 'Report Name', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.\r\n\r\nNote: This workflow is qualified to work with Data ONTAP version 8.2.x', '1.0.1', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.\r\n\r\nNote: This workflow is qualified to work with Data ONTAP version 8.2.x', '11.00', '1.1.1', '5.0.0', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.\r\n\r\nNote: This workflow is qualified to work with Data ONTAP version 8.2.x', 'NETAPP', 'test author', 'shivani.gupta@netapp.com', '<A HREF="http://www.netapp.com/us/services-support/index.aspx"> Support </A>', 'wfs_data/ocum_2015-10-17-00-43-46-9927/chromedriver_win32.zip', '2015-10-17 00:44:18'),
(3, 'New Report', 'kjawhd qwrqwnbqawe qe12h4ej12hj4j1''24 k125123kj5  h12kj 4h12j3h`12@!~$#>3256#>4 756 89^&>)(&*)?89-({}p[\\\r\n;[jrykerjtw jrt iuqwruwe hruiwehr12\r\n$2', '1.0.0', 'kjawhd qwrqwnbqawe qe12h4ej12hj4j1''24 k125123kj5  h12kj 4h12j3h`12@!~$#>3256#>4 756 89^&>)(&*)?89-({}p[\\\r\n;[jrykerjtw jrt iuqwruwe hruiwehr12\r\n$2', '1.1.1', '11.11.11', '99.99.99', 'kjawhd qwrqwnbqawe qe12h4ej12hj4j1''24 k125123kj5  h12kj 4h12j3h`12@!~$#>3256#>4 756 89^&>)(&*)?89-({}p[\\\r\n;[jrykerjtw jrt iuqwruwe hruiwehr12\r\n$2', 'NONE', 'test name', 'test@mail.com', '', 'wfs_data/ocum_2015-10-17-00-57-56-0869/chromedriver_win32.zip', '2015-10-17 00:58:02'),
(4, 'Report', 'nans cans fdnas f sf   adfn asfn safn fa sfna sfna sfmnas fnmas fnas fnas fna fa sfa fa f asf asnf asf asnf asf asnf ansf a sfnas  afn as fa fnas', '1.0.0', 'nas fnas fnas fna sfabsf absfkjasnfkjqwnfkjqwfjqwfbfaskjfskjafsd  fjf wje fjew fjfewnn we f wjefjewnfrjew rfn jewn jeknwrf er renwejr ejw erw ewrejw hewfh ewrheew rfjew rfjewrf jewkjewj kwe  kjwe kjwehrew', '1.1.1', '1.1.1', '99.99.99', 'sn fnsd fjhaf jqw rjkqw rnqw rqwr qwkr qjwr qjhwnrqhwrjqwrbqwjrbwqkrnqwlkrnqwlkrnq wr qwr qwr q wrq wr qwrqw rjqw rj qwrjqw r jqw rj qwrjqwr qw rqwr qwr qwr qwkr jqwkr wqkr qwrqwkrjqwrk qwr qw r qwr qw rqwr  qwr rwq', 'NONE', 'test name', 'test@mail.com', '', 'wfs_data/ocum_2015-10-17-03-10-34-8968/Fibre_Channel_server_configuration_and_provisioning_pack (2).zip', '2015-10-17 03:10:40'),
(6, 'Fiber Channel Report', 'The workflows in this pack should be executed in the following recommended sequence:\r\n		1. Re-create SnapMirror and SnapVault protection after MetroCluster switchover\r\n		2. Retain SnapMirror and SnapVault data before MetroCluster switchback\r\n		3. Re-create SnapMirror and SnapVault protection after MetroCluster switchback\r\n	\r\n	Note: This workflow contains new dictionaries and you must reset the cm_storage scheme in WFA by using the following steps:\r\n		1.Log in to WFA.\r\n		2.Click Execution.\r\n		3.Right-click the data source type that has cm_storage scheme, and then click Reset Scheme.', '1.2.3.4.5', 'The workflows in this pack should be executed in the following recommended sequence:\r\n		1. Re-create SnapMirror and SnapVault protection after MetroCluster switchover\r\n		2. Retain SnapMirror and SnapVault data before MetroCluster switchback\r\n		3. Re-create SnapMirror and SnapVault protection after MetroCluster switchback\r\n	\r\n	Note: This workflow contains new dictionaries and you must reset the cm_storage scheme in WFA by using the following steps:\r\n		1.Log in to WFA.\r\n		2.Click Execution.\r\n		3.Right-click the data source type that has cm_storage scheme, and then click Reset Scheme.', '12.34.AB', '11.00.90', '99.00.00', 'The workflows in this pack should be executed in the following recommended sequence:\r\n		1. Re-create SnapMirror and SnapVault protection after MetroCluster switchover\r\n		2. Retain SnapMirror and SnapVault data before MetroCluster switchback\r\n		3. Re-create SnapMirror and SnapVault protection after MetroCluster switchback\r\n	\r\n	Note: This workflow contains new dictionaries and you must reset the cm_storage scheme in WFA by using the following steps:\r\n		1.Log in to WFA.\r\n		2.Click Execution.\r\n		3.Right-click the data source type that has cm_storage scheme, and then click Reset Scheme.', 'NONE', 'test author', 'shivani.gupta@netapp.com', '', 'wfs_data/ocum_2015-10-20-02-55-33-4324/Fibre_Channel_server_configuration_and_provisioning_pack (2).zip', '2015-10-20 02:55:41');

-- --------------------------------------------------------

--
-- Table structure for table `opmpacks`
--

CREATE TABLE IF NOT EXISTS `opmpacks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packName` varchar(120) NOT NULL,
  `packDescription` text NOT NULL,
  `packVersion` varchar(30) NOT NULL,
  `versionChanges` text,
  `OPMVersion` varchar(30) NOT NULL,
  `minONTAPVersion` varchar(30) NOT NULL,
  `maxONTAPVersion` varchar(30) DEFAULT NULL,
  `otherPrerequisite` text,
  `certifiedBy` varchar(20) NOT NULL,
  `authorName` varchar(120) NOT NULL,
  `packFilePath` varchar(200) DEFAULT NULL,
  `packDate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `opmpacks`
--

INSERT INTO `opmpacks` (`id`, `packName`, `packDescription`, `packVersion`, `versionChanges`, `OPMVersion`, `minONTAPVersion`, `maxONTAPVersion`, `otherPrerequisite`, `certifiedBy`, `authorName`, `packFilePath`, `packDate`) VALUES
(1, 'test name', '12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453\r\n12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453 12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453\r\n12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453', '11.SD.1M3', '12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453', 'AD.234', '1.1.1', '99.99.99', '12wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 345312wefrwes sd frb3n 45n 3425rmn234 5rn34 r3n4 trk34 t5k345t34l 5345t3 543j 5l34 5lj345l 345 34l5 34l 5 345 34l 534 5 3453', 'NETAPP', 'test author', 'wfs_data/opm_2015-10-17-00-24-18-588/Fibre_Channel_server_configuration_and_provisioning_pack (2).zip', '2015-10-17 00:24:26'),
(2, 'Performance File', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.', '1.0.0', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.', '01.00', '1.0.0', '99.99.99', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.', 'NETAPP', 'Author Name', 'wfs_data/opm_2015-10-17-00-38-22-4587/chromedriver_win32.zip', '2015-10-17 00:38:27'),
(3, 'Performance', 'asn ds adbn asd', '11.00', 'bsadfasf j hfj assfjahfjasfjsdfhsdf hsdbf sdhfkjsdhf jsh', '10.00', '1.1.1', '99.99.99', 'sdbfc hjsdf h fhasfhsfhsfjsfskjdfhjdf j n fknsdf nsd fns dfnsd fsnd fsnd fsnd fsd fnsd fnsd fsnd fksdfsdfskdf sd fsd f sdfs dfksdf k', 'NONE', 'test name', 'wfs_data/opm_2015-10-17-03-08-28-0347/Fibre_Channel_server_configuration_and_provisioning_pack (2).zip', '2015-10-17 03:08:39'),
(5, 'test name', 'aS', '12.0.0', 'WS', '11.0.0', '1.1.1', '11.11.11', 'AS', 'NETAPP', 'Author Name', 'wfs_data/opm_2015-10-20-05-25-00-3163/30MBPack.zip', '2015-10-20 05:25:05');

-- --------------------------------------------------------

--
-- Table structure for table `packdetails`
--

CREATE TABLE IF NOT EXISTS `packdetails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(100) NOT NULL,
  `packName` varchar(200) NOT NULL,
  `packDescription` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `certifiedBy` varchar(100) NOT NULL,
  `version` varchar(30) NOT NULL,
  `minWfaVersion` varchar(30) NOT NULL,
  `minsoftwareversion` varchar(30) NOT NULL,
  `maxsoftwareversion` varchar(30) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `packFilePath` varchar(200) DEFAULT NULL,
  `packDate` varchar(100) NOT NULL,
  `cautionStatus` enum('true','false') NOT NULL DEFAULT 'false',
  `cautionContent` longtext NOT NULL,
  `post_approved` enum('true','false') NOT NULL DEFAULT 'true',
  `preRequisites` text,
  `whatsChanged` text,
  `contactName` varchar(150) DEFAULT NULL,
  `contactEmail` varchar(150) DEFAULT NULL,
  `contactPhone` varchar(150) DEFAULT NULL,
  `tags` varchar(500) NOT NULL,
  `modDate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `packdetails`
--

INSERT INTO `packdetails` (`id`, `uuid`, `packName`, `packDescription`, `author`, `certifiedBy`, `version`, `minWfaVersion`, `minsoftwareversion`, `maxsoftwareversion`, `keywords`, `packFilePath`, `packDate`, `cautionStatus`, `cautionContent`, `post_approved`, `preRequisites`, `whatsChanged`, `contactName`, `contactEmail`, `contactPhone`, `tags`, `modDate`) VALUES
(2, '6fc61582-b79e-491f-8374-7fe9c57c2557', 'chandank test', 'This pack contains 3 workflows to enable you to re-create the lost SnapMirror and SnapVault configuration after switchover/switchback operation in a MetroCluster environment.\r\n\r\n	The workflows in this pack should be executed in the following recommended sequence:\r\n		1. Re-create SnapMirror and SnapVault protection after MetroCluster switchover\r\n		2. Retain SnapMirror and SnapVault data before MetroCluster switchback\r\n		3. Re-create SnapMirror and SnapVault protection after MetroCluster switchback\r\n	\r\n	Note: This workflow contains new dictionaries and you must reset the cm_storage scheme in WFA by using the following steps:\r\n		1.Log in to WFA.\r\n		2.Click Execution.\r\n		3.Right-click the data source type that has cm_storage scheme, and then click Reset Scheme.\r\n	', 'NetApp', 'NETAPP', '1.2.0', '3.0.0.0.0', 'No', 'No', '', 'wfs_data/build_2015-10-16-23-22-27-788/test_ckbv (3).dar', '2015-10-16 23:24:40', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Changes', 'Author Name', 'test@email.com', '9000-000-000', 'Nothing', ''),
(7, 'ba11fac4-9710-4f2b-90ed-a7efbd4e723a', 'Manage SnapMirror-SnapVault Cascade Relationship', 'This pack contains a workflow, Manage SnapMirror-SnapVault Cascade Relationship that enables you to manage the schedules and transfer updates of your existing SnapMirror-SnapVault cascade relationships.\r\n\r\nThe execution of this workflow involves the following steps:\r\n	1. Generate a list of Snapshot copies at the SnapMirror destination that matches with SnapMirror labels at SnapVault destination.\r\n	2. Transfer the Snapshot copies identified in the previous step from SnapMirror destination to SnapVault destination.\r\nWhile transferring the Snapshot copies, you must check the keep and preserve fields of the SnapMirror label at SnapVault destination and then proceed with the transfer.\r\n\r\nNote: This workflow is qualified to work with Data ONTAP version 8.2.x', 'NetApp', 'NETAPP', '1.0.0', '3.0.0.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-17-00-12-45-3389/Manage SnapMirror-SnapVault Cascade Relationship.dar', '2015-10-17 00:13:16', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'NA', 'Arun Verma', 'arun.verma@netapp.com', '<A HREF="http://www.netapp.com/us/services-support/ngs-contacts.aspx">Support</A>', 'channelfibre,', '2015-10-17 00:49:12'),
(8, '1aa35bcc-7f82-4c5c-a1c6-eac666f2d7d7', 'Protect volume with Snapmirror', 'Workflow for protecting the volume with snapmirror', 'NetApp', 'NONE', '1.1.1', '4.0.0.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-17-03-38-23-5167/Protect_Vol_SM.dar', '2015-10-17 03:39:29', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'other text ^&*@#^&*%@^#%*(*&!(@&#(*!&@#(*@&!(*#&(*!@&*(@!&(#!(*#^!*&^#(!*#_!(_(*_!&#(*!&#(!^(#*^*(!^#(&!^#(*&!)*#_!(#_!*)!&)&!)(#&*)(!&*#)', 'What''s changed', 'test author', 'test@in.com', '1-800-666-7777', 'tag,', ''),
(9, '312fa634-3114-49f8-8376-98aee8e10b0f', 'Foreign LUN Import', 'During the process of storage consolidation in your data center, you need to migrate data from third-party LUNs to a NetApp cluster. This pack contains four workflows that enable you to migrate data from third-party LUNs to a NetApp cluster using Foreign LUN Import (FLI) functionality. These packs support both Online and Offline FLI.\r\n	\r\n	The workflows in this pack should be executed in the following recommended sequence:\r\n		1. FLI Setup And Import\r\n		2. FLI Status And Restart\r\n		3. FLI Post Jobs\r\n		4. FLI Report\r\n\r\n	Prerequisites:\r\n\r\n		1. You must have installed WFA 3.1 \r\n		2. You must ensure that OnCommand Unified Manager 6.3 is added as a data source in your WFA\r\n\r\n		This pack contains a configuration file called "data-collector_lun_import_ext.conf". You can obtain the same from any help content of the workflows. The configuration file must be placed in the following location as per the OS selected for the Unified Manager:\r\n			i.  Windows user: C:\\Program Files\\NetApp\\ocum\\etc\\data-collector\r\n			ii. Linux\\Virtual Appliance (vApp) user (with root login): /opt/netapp/ocum/etc/data-collector/\r\n	', 'NetApp', 'NETAPP', '1.0.0', '3.1.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-17-04-42-31-3719/Foreign_LUN_Import.dar', '2015-10-17 04:43:23', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'NetApp', 'abhit@netapp.com', '<A HREF="http://www.netapp.com/us/services-support/ngs-contacts.aspx">Support</A>', 'Nothing', ''),
(10, 'c94d39f4-9b14-46a8-8a3a-f3b0e6698153', 'Datasource Script for VMware vCenter 6.0', 'This pack contains datasource script to acquire VMware vCenter server data into WFA database for heterogeneous IT automation activity. Prerequisites for this Data Source: 1. WFA 3.1 2. PowerShell 3.0 on the WFA Server 3. vMWare PowerCLI 6.0 4. VMWare vCenter (version 6.0). To create a new Data Source, follow these steps: 1. Import the VMware vCenter Data Source into your WFA server. 2. Use Execution->Data Sources and click New, entering IP, port, user and password credentials for the VMware vCenter server and set the timeout value to 3600. 3. After determining the amount of time to complete data acquisition from the Data Source, reduce the Timeout based on the speed of the VMware vCenter data acquisition.', 'NetApp', 'NETAPP', '1.1.0', '3.1.0', 'Partial', 'No', '', 'wfs_data/build_2015-10-18-23-29-09-3367/VMwarevCenter.zip', '2015-10-18 23:30:21', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'What''s changed', 'test author', 'test@in.com', '1-800-666-7777', 'tag,', ''),
(11, '838a8e69-2127-44ef-b4f2-fe66fb188c8f', 'Modify Snapmirror Relationship', 'Workflow for modifying the snapmirror relationship', 'NetApp', 'NONE', '1.2.3', '4.0.0.0.0', 'Yes', 'No', '', 'wfs_data/build_2015-10-19-03-08-42-714/Modify_SMRelationship_Pack-3.dar', '2015-10-19 03:08:58', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'Sachin Balmane', 'bsachin@netapp.com', '9632831633', 'Nothing', ''),
(12, '6fc61582-b79e-491f-8374-7fe9c57c2556', 'Re-create SnapMirror and SnapVault protection after MetroCluster switchover and switchback', 'This pack contains 3 workflows to enable you to re-create the lost SnapMirror and SnapVault configuration after switchover/switchback operation in a MetroCluster environment.\r\n\r\n	The workflows in this pack should be executed in the following recommended sequence:\r\n		1. Re-create SnapMirror and SnapVault protection after MetroCluster switchover\r\n		2. Retain SnapMirror and SnapVault data before MetroCluster switchback\r\n		3. Re-create SnapMirror and SnapVault protection after MetroCluster switchback\r\n	\r\n	Note: This workflow contains new dictionaries and you must reset the cm_storage scheme in WFA by using the following steps:\r\n		1.Log in to WFA.\r\n		2.Click Execution.\r\n		3.Right-click the data source type that has cm_storage scheme, and then click Reset Scheme.\r\n	', 'NetApp', 'NETAPP', '1.0.1', '3.0.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-19-03-24-51-4831/Re-create_SnapMirror_and_SnapVault_protection_after_MetroCluster_switchover_and_switchback.dar', '2015-10-19 03:27:32', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing. This file size is 300MB.', 'Abhi', 'abhit@netapp.com', '<A HREF="http://www.netapp.com/us/services-support/ngs-contacts.aspx">Support</A>', 'Nothing', ''),
(18, '7d44f66f-1b7f-46f8-be7c-d12bdd825ca8', 'Dummy Fibre Channel pack', 'This pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). Following are the workflows contained in the pack: 1. FCP server configuration for Windows and ESX 2. FCP LUN provisioning for Windows and ESX', 'Arun Verma', 'NETAPP', '1.2.5', '3.0.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-20-03-31-39-4004/Fibre_Channel_server_configuration_and_provisioning_pack (2).zip', '2015-10-20 03:34:56', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'This pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). \r\nFollowing are the workflows contained in the pack: \r\n1. FCP server configuration for Windows and ESX \r\n2. FCP LUN provisioning for Windows and ESXThis pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). \r\nFollowing are the workflows contained in the pack: \r\n1. FCP server configuration for Windows and ESX \r\n2. FCP LUN provisioning for Windows and ESX', 'This pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). \r\nFollowing are the workflows contained in the pack: \r\n1. FCP server configuration for Windows and ESX \r\n2. FCP LUN provisioning for Windows and ESX', 'Shivani Gupta', 'shivani.gupta@netapp.com', '<A HREF = "http://www.w3schools.com"> Link </A>', '30mbpack,', ''),
(20, 'sample_pack_dar_tesr_v2', 'sample pack dar tesr v2', 'sample pack dar tesr v1', '', 'NONE', '1.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-20-04-26-28-0699/definitions_10_20_15__13_34_27.dar', '2015-10-20 04:28:56', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'Nothing', ''),
(21, 'sample_pack_dar_tesr_v2', 'sample pack dar tesr v2', 'sample pack dar tesr v1', '', 'NONE', '8.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-20-04-30-05-1624/definitions_10_20_15__13_34_27.dar', '2015-10-20 04:30:20', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'Nothing', ''),
(23, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle cache query pack with new scheme', 'this is a packe with new cache query and a new scheme<h1>This is just a dummy text </h1>\r\n<p><ul><li>India </li><li>China </li><li>US </li><li>UK </li></ul></p>\r\n	', 'sinhaa', 'NETAPP', '5.0.1', '3.0.0.0.0', 'Yes', 'No', '', 'wfs_data/build_2015-10-20-04-35-30-0396/Oracle_cache_query_with_new_scheme_pack_signed.zip', '2015-10-20 04:36:10', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'nothing', 'test', 'test@test.com', '0987654321', 'test,', ''),
(24, 'Test_File_To_Test_DAR_UPLOAD', 'Test File To Test DAR UPLOAD', 'Test File To Test DAR UPLOAD', '', 'NONE', '1.0.0', '3.1', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-20-07-00-18-0076/definitions_10_20_15__19_29_58.dar', '2015-10-20 07:01:06', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Test.', 'Nothing', 'Abhi', 'abhibt@yahoo.com', '+919845515269', 'Nothing', ''),
(25, 'Test_File_To_Test_DAR_UPLOAD', 'Test File To Test DAR UPLOAD', 'Test', '', 'NONE', '1.0.1', '3.1', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-20-07-03-56-2032/definitions_10_20_15__19_29_58.dar', '2015-10-20 07:04:36', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'None', 'Abhi', 'abhibt@yahoo.com', '+919845515269', 'Nothing', '');

-- --------------------------------------------------------

--
-- Table structure for table `packentities`
--

CREATE TABLE IF NOT EXISTS `packentities` (
  `entityId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `packId` int(10) unsigned NOT NULL,
  `entityType` varchar(100) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `version` varchar(30) NOT NULL,
  `certifiedBy` varchar(50) NOT NULL,
  `minOntapVersion` varchar(30) NOT NULL,
  `scheme` varchar(100) NOT NULL,
  `entityDate` varchar(100) NOT NULL,
  PRIMARY KEY (`entityId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=339 ;

--
-- Dumping data for table `packentities`
--

INSERT INTO `packentities` (`entityId`, `packId`, `entityType`, `uuid`, `name`, `description`, `version`, `certifiedBy`, `minOntapVersion`, `scheme`, `entityDate`) VALUES
(2, 2, 'Dictionary Entry', '820b27ab-f164-4db9-bf58-919f34fab1d1', 'SnapMirror_MetroCluster', 'Volume SnapMirror relationship for Disaster recovery, Load sharing, Remote data access and Vault.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(3, 2, 'Dictionary Entry', 'ebbdd23f-1226-4f29-93f6-b4e284fc251c', 'SnapMirror_Policy_Rule_MetroCluster', 'A definition of a rule in a SnapMirror policy.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(4, 2, 'Filter', '98b06968-a6d2-4429-944b-202cf6282712', 'Filter cron schedules in the local site in a MetroCluster', 'Returns cron schedule details  in the local site that do not exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(5, 2, 'Cache Query', '01aaa4a6-8775-4838-bda2-6953087fdc71', '01aaa4a6-8775-4838-bda2-6953087fdc71', '', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(6, 2, 'Command', '0df8a100-707b-43d2-9fbb-a37d1736804f', 'Retain SnapMirror policy - MetroCluster', 'Retain SnapMirror policies and insert into wfa playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(7, 2, 'Command', 'af5f154e-8ce1-4255-a8b7-f41b69270c81', 'Create SnapMirror - BRE To LRSE', 'Creates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, a source and a destination volume should have been created by the create volume command. The source volume should be in the online state and be of the read-write (RW) type. The destination volume should be in the online state and be of the data protection (DP) type. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(8, 2, 'Workflow', '48d315d2-5d1a-4a77-8b6f-8a0c709636f2', 'Re-create SnapMirror and SnapVault Protection after MetroCluster Switchback', 'This workflow re-creates SnapMirror and SnapVault configurations after a MetroCluster switchback operation.\n\nRe-creating SnapMirror and SnapValut involves the following steps:\n\n1) Creating a cron schedule in the local site\n2) Creating a SnapMirror policy in the local site\n3) Creating a SnapMirror rule in the local site\n4) Creating a SnapMirror relationship in the local site\n\nPrerequisites:\n\n1) MetroCluster cluster  and Non-MetroCluster cluster must be peered.\n2) Storage Virtual Machines of the MetroCluster cluster and Non-MetroCluster cluster must be peered.\n3) Ensure that data from switchover is retained before the MetroCluster switchback operation.  \n\n\n', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(9, 2, 'Cache Query', '588853d3-d0ea-45af-b93a-c4ded117b207', '588853d3-d0ea-45af-b93a-c4ded117b207', '', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(10, 2, 'Filter', '6c165d90-c294-487c-983b-e5fbd827dda7', 'Filter SnapMirror policies in the remote site in a MetroCluster', 'Returns SnapMirror policies in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(11, 2, 'Command', '5b562b4b-b5f7-4a5d-9ae7-99169bf01522', 'Retain SnapMirror - MetroCluster', 'Retain SnapMirror relations and insert into wfa playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(12, 2, 'Command', 'ee0a1f0d-20c2-4213-9755-d0fd9bf96d8a', 'Create SnapMirror - MetroCluster', 'Creates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, a source and a destination volume should have been created by the create volume command. The source volume should be in the online state and be of the read-write (RW) type. The destination volume should be in the online state and be of the data protection (DP) type. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(13, 2, 'Finder', '78268ea5-6bd6-4adc-aba1-a3272dd6579e', 'Find SnapMirror relationships in the local site in a MetroCluster', 'Find SnapMirror relationships in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(14, 2, 'Finder', 'bf6335ff-f5eb-421e-a100-c9200a6bbb9b', 'Find SnapMirror policy rules in the remote site in a MetroCluster', 'Find SnapMirror policy rules in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(15, 2, 'Finder', '93e0ea78-a93b-43f8-bf20-e746a0a0b013', 'Find SnapMirror relationships in the remote site in a MetroCluster', 'Find SnapMirror relationships in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(16, 2, 'Dictionary Entry', '537411fa-8240-4522-9846-048a169ac7bf', 'SnapMirror_MetroCluster', 'Volume SnapMirror relationship for Disaster Recovery, Load Sharing, Remote Data Access, Vault, Restoring Data Protection and Extended Data Protection.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(17, 2, 'Command', '7b067fcf-e120-4c17-9765-8fab4e0e35c7', 'SnapMirror update - MetroCluster', 'Updates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, SnapMirror or SnapVault relationship should be created. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(18, 2, 'Finder', '317185e7-07d7-40f3-a92c-bc9161c7ced3', 'Find SnapMirror policies in the local site in a MetroCluster', 'Find SnapMirror policies in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(19, 2, 'Command', '0ab043bb-e2be-41ed-9746-67c88c7365b1', 'Quiesce SnapMirror - MetroCluster', 'Quiesce a SnapMirror or SnapVault relationship terminated in the given destination volume.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(20, 2, 'Workflow', '5e90b8ae-44fd-4b12-99e2-5e51a5097cc0', 'Re-create SnapMirror and SnapVault Protection after MetroCluster Switchover', 'This workflow re-creates SnapMirror and SnapVault configurations after a MetroCluster switchover operation.\n\nRe-creating SnapMirror and SnapValut involves the following steps:\n\n1) Creating a cron schedule in the remote site\n2) Creating a SnapMirror policy in the remote site\n3) Creating a SnapMirror rule in the remote site\n4) Creating a SnapMirror relationship in the remote site\n\nPrerequisites:\n\n1) MetroCluster cluster  and Non-MetroCluster cluster must be peered.\n2) Storage Virtual Machines of the MetroCluster cluster and Non-MetroCluster cluster must be peered.\n', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(21, 2, 'Filter', '10961620-6b7f-4974-a0e2-1634d20f1c56', 'Filter cron schedules in the remote site in MetroCluster', 'Returns cron schedule details in remote site that do not exist in local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(22, 2, 'Command', 'da303d7d-c67e-4714-8a91-8c2124bdca4a', 'Add rule to SnapMirror policy - MetroCluster', 'Add a rule to a SnapMirror Policy on the clustered Data ONTAP. &quot;SnapMirror Policy Rule&quot; determines the retention settings of the Snapshots on the SnapVault destination. Each SnapMirror Policy Rule has a &quot;SnapMirror Label&quot; field. Clustered ONTAP transfers only the &quot;labeled&quot; source Snapshots to the SnapVault destination. If the source Snapshot does not have one of the SnapMirror labels specified in the &quot;SnapMirror Policy Rules&quot;, that source Snapshot is not transferred to the SnapVault destination. Source volume has a &quot;Snapshot Policy&quot; which has a schedule for creating local snapshots. SnapMirror label specified in the &quot;Snapshot Policy Schedule&quot; of the source volume is applied to all the Snapshots created by that schedule. The same SnapMirror label is used in the &quot;SnapMirror Policy Rule&quot; to specify the retention settings for those Snapshots on the SnapVault destination.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(23, 2, 'Finder', '386c4055-5332-4515-b8fe-38570a0b79e4', 'Find cron schedules in the remote site in a MetroCluster.', 'Find cron schedule details in the remote site that do not exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(24, 2, 'Dictionary Entry', 'cf9f053f-4e0e-4836-b46d-a20b3371a682', 'Cluster_MetroCluster', 'A cluster is a collection of nodes that share a single set of replicated configuration databases. This dictionary entry contains additional  MetroCluster partner information.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(25, 2, 'Filter', '66a37244-0758-44d8-a335-5a679c0a43bc', 'Filter SnapMirror policy rules in the local site in a MetroCluster', 'Returns SnapMirror policy rules in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(26, 2, 'Workflow', '5fb4278d-c077-4ca1-bde6-24f118cc1df0', 'Retain SnapMirror and SnapVault configurations before MetroCluster Switchback', 'This workflow retains  SnapMirror and SnapVault configurations data in the local database (WFA).\n\nYou must execute this workflow before your MetroCluster switchback operation.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(27, 2, 'Command', '78ed8101-d179-49ef-8c79-1ae4ed39b6a9', 'Delete Records - MetroCluster', 'Delete MetroCluster cron Schedules, SnapMirror policies, SnapMirror policy rules and SnapMirror relations from WFA playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(28, 2, 'Finder', '5372bcd7-c82c-456c-af88-13eba2b24fd1', 'Find SnapMirror policies in the remote site in a MetroCluster', 'Find SnapMirror policies in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(29, 2, 'Filter', '6bb3e561-af9b-4a5b-ab90-5c7d26328db1', 'Filter SnapMirror policy rules in the remote site in a MetroCluster', 'Returns SnapMirror policy rules in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(30, 2, 'Command', '89467f07-5099-4cb1-af07-0600b2cdbc50', 'Create SnapMirror policy - MetroCluster', 'Create a SnapMirror Policy on the Clustered ONTAP. SnapMirror policy can be assigned to the SnapMirror or SnapVault relationships.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(31, 2, 'Filter', '179e84e8-13a0-44c6-9a0e-2dfbc9d85cbf', 'Filter SnapMirror relationships in the remote site in a MetroCluster', 'Returns SnapMirror relationships in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(32, 2, 'Command', 'ce297284-4dc3-4c3d-ab1c-e8caa13469f1', 'Retain SnapMirror policy rule - MetroCluster', 'Retain SnapMirror policy rules and insert into WFA playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(33, 2, 'Dictionary Entry', 'b17aba64-d3fe-4df1-bcb8-99e88c8b3041', 'SnapMirror_Policy_MetroCluster', 'SnapMirror policy encapsulates a set of rules applicable for protection relationships. In case of vault relationships, the rules that define which Snapshot copies are protected.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(34, 2, 'Filter', '1a2baf7c-14e5-4d33-84ea-fb2c2a7d223f', 'Filter MetroCluster details by partner MetroCuster', 'Returns MetroCluster name by specified partner MetroCluster.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(35, 2, 'Command', '122aaa51-c304-4206-ac71-a285413651bf', 'SnapMirror update - MetroCluster', 'Updates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, SnapMirror or SnapVault relationship should be created. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(36, 2, 'Finder', '50b60958-d652-4ed1-b834-5f2b0f9dc500', 'Find cron schedules in the local site in a MetroCluster.', 'Find cron schedule details in the local site that do not exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(37, 2, 'Filter', 'c97f138e-249d-4240-9a4c-66463bb122f2', 'Filter SnapMirror policies in the local site in a MetroCluster', 'Returns SnapMirror policies in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(38, 2, 'Category', '41ec7214-c8a8-4484-8d0f-cfacbd16f5b0', 'Data Protection', 'A set of workflows that configure data protection relationships. These workflows deal with different data protection technologies such as Volume SnapMirror and SnapVault.', '1.0.0', 'NONE', '', '', '2015-10-16 23:24:40'),
(39, 2, 'Scheme', '37b39b5e-d285-4821-8828-2b0f58a457ea', 'playground', '', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(40, 2, 'Filter', '9c16d77d-5621-44d3-9c71-1abe24678540', 'Filter SnapMirror relationships in the local site in a MetroCluster', 'Returns SnapMirror relationships in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(41, 2, 'Finder', '0c2be7b4-1d1b-45be-a542-341c378b4140', 'Find SnapMirror policy rules in the local site in a MetroCluster', 'Find SnapMirror policy rules in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-16 23:24:40'),
(167, 7, 'Command', 'aaa8c825-a55b-4a33-8c80-b0fd6cb56ea8', 'Modify SnapMirror - DPG', 'Modify SnapMirror changes one or more parameters of a SnapMirror or SnapVault relationship. The SnapMirror or SnapVault relationship is identified by its source and destination Clusters, Storage Virtual Machines, and Volumes and these must be specified as the command input parameters.\n\nChanges made by the Modify SnapMirror command do not take effect until the next manual or scheduled update of the relationship. Changes do not affect updates that have started and have not finished yet. This command is not supported on Infinite Volume constituents.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(168, 7, 'Command', '947eb640-e7b1-4281-9588-bcd0e943b768', 'Generate Snapshot Set To Be Updated - PMV', 'The command will generate a set of snapshots to be updated (copied/removed) in the vault location.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(169, 7, 'Command', 'e6e80603-c475-40b1-b886-b6a4facdc2c6', 'Wait for Snapshot Update - PMV', 'The command will wait for the SnapMirror relationship  to complete the update process.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(170, 7, 'Script Data Source Type', '537bf62a-9541-44f4-baf9-83e1900b6145', 'Clustered Data ONTAP - Snapshot_', '', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(171, 7, 'Workflow', 'b21e3fdf-d718-4f13-b9a9-a1caf1a8a05e', 'Manage SnapMirror-SnapVault Cascade Relationship', 'This workflow Manage SnapMirrror-SnapVault Cascade Relationship sets up the transfer of user defined snapshots from  snapmirror destination to snapvault by reading the SnapVault policy at the Vault Side.\n\nThe feature of transferring the user defined snapshots from mirror to vault is not available in the SN.x releases, hence, this workflow can be used in place if the user wants to transfer the user defined snapshots and the workflow will be scheduled from a windows scheduler and executed as many number of times based on the schedule.\n\nSnapMirrror-SnapVault Cascade Relationship involves the following steps:\n\n1) Generate a list of snapshots at SnapMirror Destination that are matching with snapmirror labels at SnapVault Destination.\n2) Transfer the snapshots identified in previous step from SnapMirror Destination to SnapVault Destination.\n3) While Transferring the snapshots check the keep and preserve fields of snapmirror label at SnapVault destination and then transfer.\n\nPre-requisites:\n=', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(172, 7, 'Dictionary Entry', '5e2cf19e-c07f-452d-84fe-ce8a64170f69', 'Snapshot', 'Snapshot of a volume on a Clustered Data ONTAP.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(173, 7, 'Filter', 'eedaebca-4526-4507-873f-b5e934ac68e8', 'Filter Snapshots by snapmirror label - PMV', 'Returns a list of snapshots that matches the snapmirror labels from the vault side.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(174, 7, 'Scheme', '69a9700a-f4cd-4819-bd56-2d611a60cc3e', 'cm_storage_smsv', '', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(175, 7, 'Finder', 'ff4bcacf-8903-4e46-a1c3-1b62260c3448', 'Find snapshots by snapmirror label', '', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(176, 7, 'Command', '22be6f59-3935-4759-bb74-b97bc858e5c7', 'Snapmirror SnapShot Update And Delete - PMV', 'The command will updated the snapshot specified from mirror to vault destination.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(177, 7, 'Command', 'b37a1042-345d-438b-93cf-a097813c44fc', 'Lock SnapMirror Snapshot - PMV', 'The command will lock the given snapshot.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(178, 7, 'Command', 'e2f6997a-0faf-4863-9951-ab52fbd1327b', 'Wait for SnapMirror update', 'The command will wait for the SnapMirror relationship  to complete the update process.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(179, 7, 'Command', 'fb5bd546-b7c5-4f37-a94b-f1ec98bbefee', 'Unlock SnapMirror SnapShot - PMV', 'The command will unlock a given snapshot.', '1.0.0', 'NETAPP', '', '', '2015-10-17 00:13:16'),
(180, 7, 'Category', '41ec7214-c8a8-4484-8d0f-cfacbd16f5b0', 'Data Protection', 'A set of workflows that configure data protection relationships. These workflows deal with different data protection technologies such as Volume SnapMirror and SnapVault.', '1.0.0', 'NONE', '', '', '2015-10-17 00:13:16'),
(181, 8, 'Workflow', '78ee8bf7-e5dc-4d0c-99a1-b751c2d63701', 'Protect volume with SnapMirror - clone', 'Create SnapMirror relationship for the specified volume. Use the aggregate specified in the user input for provisioning the destination volume. A peering relationship is created between source and destination clusters using the existing inter cluster logical interfaces (LIFs). Also, a peering relationship is created between source and destination Storage Virtual Machines.', '1.0.0', 'NONE', '', '', '2015-10-17 03:39:29'),
(182, 9, 'Workflow', '1e86b211-b985-428d-8f38-b6147530d574', 'FLI Post Jobs', 'This workflow breaks the LUN import relationships from the cluster and also deletes any LUN of a failed LUN import operation.\n\nDeletion of LUN import relationship involves the following steps:\n    1. Deleting LUN import relationship for selected LUN import\n    2. Making LUN online if stated\n    3. Deleting LUN from ONTAP for failed LUN import\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTAP Version: 8.3.1 and above\n6) UM Version: 6.3', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(183, 9, 'Command', '2179516b-498b-4506-bcd7-f99989a842bc', 'Database Report Genarator', 'Generates a database report for a specific database table under a specific database schema.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(184, 9, 'Workflow', 'bf5fc3eb-4298-489b-9ed2-c502b338bf69', 'FLI Setup And Import', 'This workflow enables you to import data from foreign RAID array LUNs to cluster LUNs.\n\nMigration of foreign LUNs involves the following steps:\n    1. Marking disk''s as foreign\n    2. Creating LUNs by size of foreign disk\n    3. Making LUNs offline\n    4. Mapping newly created LUNs to existing I-group\n    5. Creating LUN import with/without throttle\n    6. Starting of LUN imports (Offline/Online)\n    7. Verification of LUN imports (Offline)\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTAP Vsersion: 8.3.1 and above\n6) UM Version: 6.3', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(185, 9, 'Dictionary Entry', '9248e6d7-5842-4fe0-9a5d-946e49cd0343', 'Disk_Path', 'A disk path in a storage system.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(186, 9, 'Workflow', '1837b62c-a373-4616-a210-467e138bde9d', 'FLI Report', 'This workflow generates a status report of the LUN import in the form of CSV. A file in the following format &lt;lun_report_dd_mm_yyyy__hh_mm_ss.csv&gt; will be created at the location, WFA Installation Directory-&gt;jboss-&gt;standalone-&gt;tmp.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(187, 9, 'Cache Query', 'c08ea9c5-d77c-457b-8f5e-736efc6335c9', 'Lun_Import for query OnCommand Unified Manager_6.3', '', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(188, 9, 'Workflow', 'ed78c244-20e1-41e0-b102-57c6f70e3716', 'FLI Status And Restart', 'This workflow is a helper workflow for the workflow &quot;FLI Setup And Import&quot;.\nThis workflow enables you to manage LUN migration jobs. You can also use this workflow to start LUN import operation.\n\nWorkflow displays all migration jobs (IN_PROGRESS, FAILED, STOPPED, PAUSED and COMPLETED).\nYou can perform the following actions:\n    1. Starting migration jobs which are stopped (stopped/not yet started)\n    2. Pausing migration jobs which are active or failed\n    3. Resuming migration jobs which are paused\n    4. Stopping migration jobs (import/verification) which are active or failed\n    5. Modifying throttle value (on any migration job)\n    6. Changing of import state (offline -&gt; online and vice-versa) when the import is stopped, paused\n    7. Verifying already completed import\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTA', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(189, 9, 'Command', '0bec2260-f295-44a9-b129-59cd34987e94', 'LUN Map', 'Maps the LUN to all the initiators in the specified initiator group.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(190, 9, 'Command', 'f74f6b65-3caa-49d9-be6a-967ad59fd6f9', 'LUN Create by Size of Foreign Disk', 'Creates a single unmapped LUN of foreign disk size on a Storage Virtual Machine.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(191, 9, 'Dictionary Entry', '234e2812-490d-4c38-9701-c71fe7621791', 'Lun_Import', 'Information of a Foreign LUN Import relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(192, 9, 'Command', '8df8a6ff-0dbe-4783-8897-ea9a38aefe7d', 'Wait for LUN Import and Verify', 'This command will monitor active LUN import operations on a given node. \n\nIf the number of active LUN import  operations on the given node is below the specified limit, the command will return successfully. Otherwise the command will wait until the number of LUN import is below the limit.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(193, 9, 'Command', '49f5450c-edbf-4a36-ba8f-931a1d04a3dd', 'LUN Unmap', 'Unmaps a Cluster-Mode LUN from an igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(194, 9, 'Command', '51d1650d-a805-43d4-9997-88e8f4601093', 'LUN Delete', 'Destroy the specified LUN. This operation will fail if the LUN is currently mapped and is online. The force option can be used to destroy it regardless of being online or mapped.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(195, 9, 'Cache Query', '26674358-a81f-4827-83be-87f87d08b5ce', 'Disk_Path for query OnCommand Unified Manager_6.3', '', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(196, 9, 'Command', '507db80a-f34b-44a6-a628-43569f672d71', 'Change LUN State', 'This command used to change LUN state (offline -&gt; online and online -&gt; offline) on basis of ''ChangeState'' selected.\nIf ChangeState is ''offline'' then -&gt; Disables block-protocol accesses to the LUN. Mappings, if any, configured for the LUN are not altered.\nIf ChangeState is ''online'' then -&gt; Re-enables block-protocol accesses to the LUN.', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(197, 9, 'Command', '182046db-7eba-4840-849d-e66ff2f09139', 'LUN Import', 'This command used to perform different ''lun import'' operations.\n ''create'' - Create a LUN import relationship with the purpose of importing foreign disk data into the LUN\n ''delete'' - Deletes the import relationship of the specified LUN or the specified foreign disk\n ''start'' - Start the import for the specified LUN\n ''pause'' - Pause the import for the specified LUN\n ''resume'' - Resume the import for the specified LUN\n ''stop'' - Stop and abort the import for the specified LUN. All import checkpoint data will be lost\n ''throttle'' - Modify the max throughput limit for the specified import relationship\n ''verify-start'' - Start the verification of the foreign disk and LUN data. The import admin/operational state must be stopped/stopped or started/completed. The verification does a block for block comparison of the LUN and foreign disk. If the verification detects a mismatch between the foreign disk and LUN then the verify will stop\n ''verify-stop'' - Stop the verify for the specified LUN', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(198, 9, 'Command', '21def7fe-c283-4ad0-ab5c-e735495c110d', 'Modify Storage Disk', 'Modify the attributes of storage-disk object. A true value indicates an array LUN has been designated as a foreign LUN and cannot be assigned', '1.0.0', 'NETAPP', '', '', '2015-10-17 04:43:23'),
(199, 9, 'Category', '9abc6dec-4f9f-41cb-bb3b-29fd4fc9cd9f', 'Migration', 'A set of workflows that help manage storage. This includes workflows for resizing storage, migrating volumes from their current locations to other storage systems and aggregates, and moving volumes non-disruptively in Cluster-Mode.', '1.0.0', 'NONE', '', '', '2015-10-17 04:43:23'),
(200, 10, 'Script Data Source Type', '9adc0b76-24b0-427f-9cb5-51f8430cf88a', 'VMware vCenter_6.0', '', '1.0.0', 'NONE', '', '', '2015-10-18 23:30:21'),
(201, 11, 'Workflow', 'e6fe0044-335a-4039-b193-18fd742d0141', 'Modify SnapMirror relationship clone', 'This workflow changes modifiable attributes such as max transfer rate, SnapMirror policy and SnapMirror schedule of a SnapMirror or SnapVault relationship. The SnapMirror or SnapVault relationship is identified by its destination triplet i.e. Destination Cluster, Storage Virtual Machine, and Volume and these must be specified along with one of the modifiable attributes as the input parameters.  \n\nChanges made by the Modify SnapMirror workflow do not take effect until the next manual or scheduled update of the relationship. Changes do not affect updates that have started and have not finished yet.\n', '1.0.0', 'NONE', '', '', '2015-10-19 03:08:58'),
(202, 12, 'Dictionary Entry', '820b27ab-f164-4db9-bf58-919f34fab1d1', 'SnapMirror_MetroCluster', 'Volume SnapMirror relationship for Disaster recovery, Load sharing, Remote data access and Vault.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(203, 12, 'Dictionary Entry', 'ebbdd23f-1226-4f29-93f6-b4e284fc251c', 'SnapMirror_Policy_Rule_MetroCluster', 'A definition of a rule in a SnapMirror policy.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(204, 12, 'Filter', '98b06968-a6d2-4429-944b-202cf6282712', 'Filter cron schedules in the local site in a MetroCluster', 'Returns cron schedule details  in the local site that do not exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(205, 12, 'Cache Query', '01aaa4a6-8775-4838-bda2-6953087fdc71', '01aaa4a6-8775-4838-bda2-6953087fdc71', '', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(206, 12, 'Command', '0df8a100-707b-43d2-9fbb-a37d1736804f', 'Retain SnapMirror policy - MetroCluster', 'Retain SnapMirror policies and insert into wfa playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(207, 12, 'Command', 'af5f154e-8ce1-4255-a8b7-f41b69270c81', 'Create SnapMirror - BRE To LRSE', 'Creates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, a source and a destination volume should have been created by the create volume command. The source volume should be in the online state and be of the read-write (RW) type. The destination volume should be in the online state and be of the data protection (DP) type. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(208, 12, 'Workflow', '48d315d2-5d1a-4a77-8b6f-8a0c709636f2', 'Re-create SnapMirror and SnapVault Protection after MetroCluster Switchback', 'This workflow re-creates SnapMirror and SnapVault configurations after a MetroCluster switchback operation.\n\nRe-creating SnapMirror and SnapValut involves the following steps:\n\n1) Creating a cron schedule in the local site\n2) Creating a SnapMirror policy in the local site\n3) Creating a SnapMirror rule in the local site\n4) Creating a SnapMirror relationship in the local site\n\nPrerequisites:\n\n1) MetroCluster cluster  and Non-MetroCluster cluster must be peered.\n2) Storage Virtual Machines of the MetroCluster cluster and Non-MetroCluster cluster must be peered.\n3) Ensure that data from switchover is retained before the MetroCluster switchback operation.  \n\n\n', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(209, 12, 'Cache Query', '588853d3-d0ea-45af-b93a-c4ded117b207', '588853d3-d0ea-45af-b93a-c4ded117b207', '', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(210, 12, 'Filter', '6c165d90-c294-487c-983b-e5fbd827dda7', 'Filter SnapMirror policies in the remote site in a MetroCluster', 'Returns SnapMirror policies in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(211, 12, 'Command', '5b562b4b-b5f7-4a5d-9ae7-99169bf01522', 'Retain SnapMirror - MetroCluster', 'Retain SnapMirror relations and insert into wfa playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(212, 12, 'Command', 'ee0a1f0d-20c2-4213-9755-d0fd9bf96d8a', 'Create SnapMirror - MetroCluster', 'Creates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, a source and a destination volume should have been created by the create volume command. The source volume should be in the online state and be of the read-write (RW) type. The destination volume should be in the online state and be of the data protection (DP) type. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(213, 12, 'Finder', '78268ea5-6bd6-4adc-aba1-a3272dd6579e', 'Find SnapMirror relationships in the local site in a MetroCluster', 'Find SnapMirror relationships in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(214, 12, 'Finder', 'bf6335ff-f5eb-421e-a100-c9200a6bbb9b', 'Find SnapMirror policy rules in the remote site in a MetroCluster', 'Find SnapMirror policy rules in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(215, 12, 'Finder', '93e0ea78-a93b-43f8-bf20-e746a0a0b013', 'Find SnapMirror relationships in the remote site in a MetroCluster', 'Find SnapMirror relationships in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(216, 12, 'Dictionary Entry', '537411fa-8240-4522-9846-048a169ac7bf', 'SnapMirror_MetroCluster', 'Volume SnapMirror relationship for Disaster Recovery, Load Sharing, Remote Data Access, Vault, Restoring Data Protection and Extended Data Protection.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(217, 12, 'Command', '7b067fcf-e120-4c17-9765-8fab4e0e35c7', 'SnapMirror update - MetroCluster', 'Updates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, SnapMirror or SnapVault relationship should be created. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(218, 12, 'Finder', '317185e7-07d7-40f3-a92c-bc9161c7ced3', 'Find SnapMirror policies in the local site in a MetroCluster', 'Find SnapMirror policies in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(219, 12, 'Command', '0ab043bb-e2be-41ed-9746-67c88c7365b1', 'Quiesce SnapMirror - MetroCluster', 'Quiesce a SnapMirror or SnapVault relationship terminated in the given destination volume.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(220, 12, 'Workflow', '5e90b8ae-44fd-4b12-99e2-5e51a5097cc0', 'Re-create SnapMirror and SnapVault Protection after MetroCluster Switchover', 'This workflow re-creates SnapMirror and SnapVault configurations after a MetroCluster switchover operation.\n\nRe-creating SnapMirror and SnapValut involves the following steps:\n\n1) Creating a cron schedule in the remote site\n2) Creating a SnapMirror policy in the remote site\n3) Creating a SnapMirror rule in the remote site\n4) Creating a SnapMirror relationship in the remote site\n\nPrerequisites:\n\n1) MetroCluster cluster  and Non-MetroCluster cluster must be peered.\n2) Storage Virtual Machines of the MetroCluster cluster and Non-MetroCluster cluster must be peered.\n', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(221, 12, 'Filter', '10961620-6b7f-4974-a0e2-1634d20f1c56', 'Filter cron schedules in the remote site in MetroCluster', 'Returns cron schedule details in remote site that do not exist in local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(222, 12, 'Command', 'da303d7d-c67e-4714-8a91-8c2124bdca4a', 'Add rule to SnapMirror policy - MetroCluster', 'Add a rule to a SnapMirror Policy on the clustered Data ONTAP. &quot;SnapMirror Policy Rule&quot; determines the retention settings of the Snapshots on the SnapVault destination. Each SnapMirror Policy Rule has a &quot;SnapMirror Label&quot; field. Clustered ONTAP transfers only the &quot;labeled&quot; source Snapshots to the SnapVault destination. If the source Snapshot does not have one of the SnapMirror labels specified in the &quot;SnapMirror Policy Rules&quot;, that source Snapshot is not transferred to the SnapVault destination. Source volume has a &quot;Snapshot Policy&quot; which has a schedule for creating local snapshots. SnapMirror label specified in the &quot;Snapshot Policy Schedule&quot; of the source volume is applied to all the Snapshots created by that schedule. The same SnapMirror label is used in the &quot;SnapMirror Policy Rule&quot; to specify the retention settings for those Snapshots on the SnapVault destination.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(223, 12, 'Finder', '386c4055-5332-4515-b8fe-38570a0b79e4', 'Find cron schedules in the remote site in a MetroCluster.', 'Find cron schedule details in the remote site that do not exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(224, 12, 'Dictionary Entry', 'cf9f053f-4e0e-4836-b46d-a20b3371a682', 'Cluster_MetroCluster', 'A cluster is a collection of nodes that share a single set of replicated configuration databases. This dictionary entry contains additional  MetroCluster partner information.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(225, 12, 'Filter', '66a37244-0758-44d8-a335-5a679c0a43bc', 'Filter SnapMirror policy rules in the local site in a MetroCluster', 'Returns SnapMirror policy rules in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(226, 12, 'Workflow', '5fb4278d-c077-4ca1-bde6-24f118cc1df0', 'Retain SnapMirror and SnapVault configurations before MetroCluster Switchback', 'This workflow retains  SnapMirror and SnapVault configurations data in the local database (WFA).\n\nYou must execute this workflow before your MetroCluster switchback operation.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(227, 12, 'Command', '78ed8101-d179-49ef-8c79-1ae4ed39b6a9', 'Delete Records - MetroCluster', 'Delete MetroCluster cron Schedules, SnapMirror policies, SnapMirror policy rules and SnapMirror relations from WFA playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(228, 12, 'Finder', '5372bcd7-c82c-456c-af88-13eba2b24fd1', 'Find SnapMirror policies in the remote site in a MetroCluster', 'Find SnapMirror policies in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(229, 12, 'Filter', '6bb3e561-af9b-4a5b-ab90-5c7d26328db1', 'Filter SnapMirror policy rules in the remote site in a MetroCluster', 'Returns SnapMirror policy rules in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(230, 12, 'Command', '89467f07-5099-4cb1-af07-0600b2cdbc50', 'Create SnapMirror policy - MetroCluster', 'Create a SnapMirror Policy on the Clustered ONTAP. SnapMirror policy can be assigned to the SnapMirror or SnapVault relationships.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(231, 12, 'Filter', '179e84e8-13a0-44c6-9a0e-2dfbc9d85cbf', 'Filter SnapMirror relationships in the remote site in a MetroCluster', 'Returns SnapMirror relationships in the remote site that does not already exist in the local site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(232, 12, 'Command', 'ce297284-4dc3-4c3d-ab1c-e8caa13469f1', 'Retain SnapMirror policy rule - MetroCluster', 'Retain SnapMirror policy rules and insert into WFA playground scheme.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(233, 12, 'Dictionary Entry', 'b17aba64-d3fe-4df1-bcb8-99e88c8b3041', 'SnapMirror_Policy_MetroCluster', 'SnapMirror policy encapsulates a set of rules applicable for protection relationships. In case of vault relationships, the rules that define which Snapshot copies are protected.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(234, 12, 'Filter', '1a2baf7c-14e5-4d33-84ea-fb2c2a7d223f', 'Filter MetroCluster details by partner MetroCuster', 'Returns MetroCluster name by specified partner MetroCluster.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(235, 12, 'Command', '122aaa51-c304-4206-ac71-a285413651bf', 'SnapMirror update - MetroCluster', 'Updates a SnapMirror or SnapVault relationship between a source and destination volume. Before this command can be used, SnapMirror or SnapVault relationship should be created. Clustered ONTAP 8.2 or later is required for creating a SnapVault relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(236, 12, 'Finder', '50b60958-d652-4ed1-b834-5f2b0f9dc500', 'Find cron schedules in the local site in a MetroCluster.', 'Find cron schedule details in the local site that do not exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(237, 12, 'Filter', 'c97f138e-249d-4240-9a4c-66463bb122f2', 'Filter SnapMirror policies in the local site in a MetroCluster', 'Returns SnapMirror policies in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(238, 12, 'Category', '41ec7214-c8a8-4484-8d0f-cfacbd16f5b0', 'Data Protection', 'A set of workflows that configure data protection relationships. These workflows deal with different data protection technologies such as Volume SnapMirror and SnapVault.', '1.0.0', 'NONE', '', '', '2015-10-19 03:27:32'),
(239, 12, 'Scheme', '37b39b5e-d285-4821-8828-2b0f58a457ea', 'playground', '', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(240, 12, 'Filter', '9c16d77d-5621-44d3-9c71-1abe24678540', 'Filter SnapMirror relationships in the local site in a MetroCluster', 'Returns SnapMirror relationships in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(241, 12, 'Finder', '0c2be7b4-1d1b-45be-a542-341c378b4140', 'Find SnapMirror policy rules in the local site in a MetroCluster', 'Find SnapMirror policy rules in the local site that does not already exist in the remote site in a MetroCluster environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 03:27:32'),
(246, 14, 'Category', '41323e8f-edf5-41d5-8a12-04819e73b378', 'Storage Provisioning', 'A set of workflows for storage provisioning in 7-Mode and Clustered Data ONTAP storage systems. This includes workflows which provision storage objects like volumes, LUNs, qtrees, configure NFS exports, create CIFS shares, create igroups and map provisioned LUNs to those igroups.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(247, 14, 'Command', '0f4d7d13-516e-4d95-b8e0-9f9ea59b6c10', 'Modify Initiator Group', 'Add or Remove Initiators from Igroup', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(248, 14, 'Category', '3fd4e387-3003-47bc-9631-0c7c6279767f', 'Setup', 'A set of workflows that help setup the storage environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(249, 14, 'Workflow', 'baacba9d-d8a0-415f-8b42-9f64207657c4', 'FCP LUN Provisioning', 'Provisioning one or more LUNs on Clustered Data ONTAP storage systems for Windows and ESX. This workflow includes:\n\n- Creating a new aggregate or use existing one.\n- Creating a new volume or use existing one.\n- Creating a new igroup or use/modify existing one.\n- Creating one or more LUNs.\n- Mapping the LUNs to either a newly created igroup or to a specified existing igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(250, 14, 'Workflow', 'b67daf1c-f5b6-413d-982f-72a85b95be60', 'FCP Server Configuration', 'This workflow configures FCP service on a Storage Virtual Machine.\n\nConfiguring FCP services on a Storage Virtual Machine involves the following steps:\n    1. Creating a new aggregate or using an existing one.\n    2. Creating a new Storage Virtual Machine and setting up Active Directory account.\n    3. Setting up FCP service.\n    4. Creating a Portset.\n    5. Creating a FCP Logical Interface.\n    6. Adding FCP Logical Interface to Portset.\n\nPre-requisites:\n\n1) FCP license must be enabled.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(326, 18, 'Category', '41323e8f-edf5-41d5-8a12-04819e73b378', 'Storage Provisioning', 'A set of workflows for storage provisioning in 7-Mode and Clustered Data ONTAP storage systems. This includes workflows which provision storage objects like volumes, LUNs, qtrees, configure NFS exports, create CIFS shares, create igroups and map provisioned LUNs to those igroups.', '1.0.0', 'NETAPP', '', '', '2015-10-20 03:34:56'),
(327, 18, 'Command', '0f4d7d13-516e-4d95-b8e0-9f9ea59b6c10', 'Modify Initiator Group', 'Add or Remove Initiators from Igroup', '1.0.0', 'NETAPP', '', '', '2015-10-20 03:34:56'),
(328, 18, 'Category', '3fd4e387-3003-47bc-9631-0c7c6279767f', 'Setup', 'A set of workflows that help setup the storage environment.', '1.0.0', 'NETAPP', '', '', '2015-10-20 03:34:56'),
(329, 18, 'Workflow', 'baacba9d-d8a0-415f-8b42-9f64207657c4', 'FCP LUN Provisioning', 'Provisioning one or more LUNs on Clustered Data ONTAP storage systems for Windows and ESX. This workflow includes:\n\n- Creating a new aggregate or use existing one.\n- Creating a new volume or use existing one.\n- Creating a new igroup or use/modify existing one.\n- Creating one or more LUNs.\n- Mapping the LUNs to either a newly created igroup or to a specified existing igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-20 03:34:56'),
(330, 18, 'Workflow', 'b67daf1c-f5b6-413d-982f-72a85b95be60', 'FCP Server Configuration', 'This workflow configures FCP service on a Storage Virtual Machine.\n\nConfiguring FCP services on a Storage Virtual Machine involves the following steps:\n    1. Creating a new aggregate or using an existing one.\n    2. Creating a new Storage Virtual Machine and setting up Active Directory account.\n    3. Setting up FCP service.\n    4. Creating a Portset.\n    5. Creating a FCP Logical Interface.\n    6. Adding FCP Logical Interface to Portset.\n\nPre-requisites:\n\n1) FCP license must be enabled.', '1.2.3', 'NETAPP', '', '', '2015-10-20 03:34:56'),
(335, 23, 'Scheme', '91bf8d58-74cf-4ff6-a8b3-0cead03b3f81', 'oracle', '', '1.0.0', 'NONE', '', '', '2015-10-20 04:36:10'),
(336, 23, 'DictionaryEntry', 'dda0ae06-6a3a-47ef-8415-eb3f6160fffb', 'student', '', '1.0.0', 'NONE', '', '', '2015-10-20 04:36:10'),
(337, 23, 'SqlDataProviderType', '5f51ef3d-67a6-44f6-8d67-79c49f78d348', 'Oracle JDBC_', '', '1.0.0', 'NONE', '', '', '2015-10-20 04:36:10'),
(338, 23, 'CacheQuery', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', '', '1.0.0', 'NETAPP', '', '', '2015-10-20 04:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `packUser`
--

CREATE TABLE IF NOT EXISTS `packUser` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `packId` int(10) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `userEmail` varchar(300) NOT NULL,
  `uploadDate` varchar(100) NOT NULL,
  `userId` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table to store user details during upload' AUTO_INCREMENT=25 ;

--
-- Dumping data for table `packUser`
--

INSERT INTO `packUser` (`id`, `packId`, `firstName`, `lastName`, `userEmail`, `uploadDate`, `userId`) VALUES
(1, 1, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-16 23:21:27', 'shivanig'),
(2, 2, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-16 23:24:41', 'shivanig'),
(3, 3, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-16 23:26:36', 'idmtestint01'),
(4, 4, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-16 23:49:48', 'arunv2'),
(5, 5, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-16 23:57:02', 'arunv2'),
(6, 6, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-17 00:09:28', 'idmtestint01'),
(7, 7, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-17 00:13:16', 'arunv2'),
(8, 8, 'ashish', 'joshi', 'Ashish.Joshi@netapp.com', '2015-10-17 03:39:29', 'ashish8'),
(9, 9, 'abhi', 'thakur', 'Abhi.Thakur@netapp.com', '2015-10-17 04:43:24', 'abhit'),
(10, 10, 'ashish', 'joshi', 'Ashish.Joshi@netapp.com', '2015-10-18 23:30:22', 'ashish8'),
(11, 11, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-19 03:08:58', 'idmtestint01'),
(12, 12, 'abhi', 'thakur', 'Abhi.Thakur@netapp.com', '2015-10-19 03:27:33', 'abhit'),
(13, 13, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-19 05:27:29', 'charshit'),
(14, 14, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-19 22:56:46', 'arunv2'),
(15, 15, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-20 00:09:52', 'arunv2'),
(16, 16, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-20 03:16:49', 'arunv2'),
(17, 17, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-20 03:25:29', 'arunv2'),
(18, 18, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-20 03:34:56', 'shivanig'),
(19, 20, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-20 04:28:56', 'charshit'),
(20, 21, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-20 04:30:20', 'charshit'),
(21, 22, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-20 04:31:56', 'charshit'),
(22, 23, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-20 04:36:10', 'charshit'),
(23, 24, 'abhi', 'thakur', 'Abhi.Thakur@netapp.com', '2015-10-20 07:01:06', 'abhit'),
(24, 25, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-20 07:04:36', 'idmtestint01');

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ratingValue` int(11) NOT NULL,
  `ratingPackVersion` varchar(30) NOT NULL,
  `ratingPackId` varchar(20) NOT NULL,
  `ratingPackType` varchar(50) NOT NULL,
  `ratingBy` varchar(100) NOT NULL,
  `ratingDate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `ratingValue`, `ratingPackVersion`, `ratingPackId`, `ratingPackType`, `ratingBy`, `ratingDate`) VALUES
(1, 5, '1.1.1', '3', 'Workflow', 'shivanig', '2015-10-16 23:27:49');

-- --------------------------------------------------------

--
-- Table structure for table `rejectedPacks`
--

CREATE TABLE IF NOT EXISTS `rejectedPacks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `packName` varchar(500) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `userEmail` varchar(300) NOT NULL,
  `adminComments` longtext NOT NULL,
  `adminName` varchar(200) NOT NULL,
  `adminEmail` varchar(200) NOT NULL,
  `rejectDate` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Details of packs rejected along with user and admin details' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `receiveNotification` enum('true','false') NOT NULL DEFAULT 'true',
  `receiveMail` enum('true','false') NOT NULL DEFAULT 'true',
  `lastLogin` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `username`, `email`, `phone`, `receiveNotification`, `receiveMail`, `lastLogin`) VALUES
(1, 'arun', 'verma', 'arunv2', 'Arun.Verma@netapp.com', '9999999999', 'true', 'true', '2015-10-20 03:50:31'),
(2, 'shivani', 'gupta', 'shivanig', 'Shivani.Gupta@netapp.com', '', 'true', 'true', '2015-10-19 02:46:52'),
(3, 'ashutosh', 'garg', 'gashutos', 'Ashutosh.Garg@netapp.com', '', 'true', 'true', '2015-10-15 05:27:10'),
(4, 'siebel', 'test', 'idmtestint01', 'siebeltest@netapp.com', '', 'true', 'true', '2015-10-20 09:40:39'),
(5, 'harshit', 'chawla', 'charshit', 'Harshit.Chawla@netapp.com', '', 'true', 'true', '2015-10-15 00:16:19'),
(6, 'ashish', 'joshi', 'ashish8', 'Ashish.Joshi@netapp.com', '', 'false', 'false', '2015-10-20 07:10:39'),
(8, 'sharaf', 'ahmad', 'sharaf', 'Sharaf.Ahmad@netapp.com', '123456789123', 'true', 'true', '2015-10-16 03:00:28'),
(9, 'gaurav', 'kalia', 'gkalia', 'Gaurav.Kalia2@netapp.com', '9999999999', 'true', 'true', '2015-10-19 02:37:08'),
(10, 'sachin', 'balmane', 'bsachin', 'Sachin.BD@netapp.com', '', 'true', 'true', '2015-10-20 05:14:50'),
(11, 'abhi', 'thakur', 'abhit', 'Abhi.Thakur@netapp.com', '9845515269', 'true', 'true', '2015-10-20 07:20:00'),
(12, 'alex', 'marzec', 'marzec', 'marzec@netapp.com', '', 'true', 'false', '2015-09-23 07:10:14'),
(13, 'karuppusamy', 'komarasamy', 'karuppuk', 'Karuppusamy.Komarasamy@netapp.com', '', 'true', 'false', '2015-09-30 00:05:26'),
(14, 'prabu', 'arjunan', 'arjunan', 'Prabu.Arjunan@netapp.com', '', 'true', 'false', '2015-09-30 04:17:27'),
(15, 'stageuser', 'test300', 'idmstageuser300', 'idmStageUser300@netapp.com', '', 'true', 'true', '2015-10-19 03:45:17'),
(16, 'stageuser', 'test350', 'idmstageuser350', 'idmStageUser350@netapp.com', '', 'true', 'true', '2015-10-19 04:11:12'),
(17, 'stageuser', 'test314', 'idmstageuser314', 'idmStageUser314@netapp.com', '', 'true', 'true', '2015-10-19 04:10:48'),
(18, 'pavan', 'desai', 'pavand', 'Pavan.Desai@netapp.com', '', 'true', 'false', '2015-10-09 04:43:55'),
(19, 'ram', 'jaiswal', 'jaiswalr', 'Ram.Jaiswal@netapp.com', '', 'false', 'false', '2015-10-15 04:27:34');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
