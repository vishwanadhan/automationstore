-- phpMyAdmin SQL Dump
-- version 4.0.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 26, 2015 at 07:19 AM
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
(1, 'Please remove me.', '1', '2015-10-21 10:50:43');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `deprecatedpack`
--

INSERT INTO `deprecatedpack` (`id`, `depComment`, `depPackVersion`, `depPackId`, `depPackType`, `depBy`, `depDate`, `flag`) VALUES
(2, '<font color = red>Deprecated </font>', '1.0.0', '1', 'Workflow', 'abhit', '2015-10-17 00:26:17', '1'),
(4, 'Testing comment', '1.5.2', '8', 'Report', 'bsachin', '2015-10-21 01:50:12', '1'),
(5, 'testing comment', '1.1.2', '9', 'Performance', 'bsachin', '2015-10-21 02:22:58', '1'),
(7, 'This pack is deprecated due to older version.', '2.0.0', '45', 'Workflow', 'shivanig', '2015-10-24 09:28:23', '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

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
(15, 'charshit', 6, 'report', 'harshit', 'chawla', 'Fiber Channel Report', '1.2.3.4.5', '', '12.34.AB', '', 'NONE', '', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-20 21:27:26'),
(16, 'idmtestint01', 23, 'workflow', 'siebel', 'test', 'Oracle cache query pack with new scheme', '5.0.1', '', '3.0.0.0.0', 'sinhaa', 'NETAPP', '2015-10-20 04:36:10', 'Network appliance', ' ', '2015-10-20 22:33:01'),
(17, 'shivanig', 8, 'workflow', 'shivani', 'gupta', 'Protect volume with Snapmirror', '1.1.1', '', '4.0.0.0.0', 'NetApp', 'NONE', '2015-10-17 03:39:29', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-20 23:34:30'),
(18, 'arunv2', 26, 'workflow', 'arun', 'verma', 'Oracle cache query pack with new scheme', '5.0.2', '', '3.0.0.0.0', 'sinhaa', 'NETAPP', '2015-10-20 22:35:59', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:03:40'),
(19, 'arunv2', 12, 'workflow', 'arun', 'verma', 'Re-create SnapMirror and SnapVault protection after MetroCluster switchover and switchback', '1.0.1', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-19 03:27:32', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:04:44'),
(20, 'arunv2', 10, 'workflow', 'arun', 'verma', 'Datasource Script for VMware vCenter 6.0', '1.1.0', '', '3.1.0', 'NetApp', 'NETAPP', '2015-10-18 23:30:21', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:05:38'),
(21, 'arunv2', 9, 'workflow', 'arun', 'verma', 'Foreign LUN Import', '1.0.0', '', '3.1.0.0.0', 'NetApp', 'NETAPP', '2015-10-17 04:43:23', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:06:13'),
(22, 'arunv2', 28, 'workflow', 'arun', 'verma', 'Dummy Fibre Channel pack', '1.2.5', '', '3.0.0.0.0', 'Shivani', 'NONE', '2015-10-21 00:01:03', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:07:33'),
(23, 'arunv2', 25, 'workflow', 'arun', 'verma', 'Test File To Test DAR UPLOAD', '1.0.1', '', '3.1', '', 'NONE', '2015-10-20 07:04:36', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:08:11'),
(24, 'arunv2', 21, 'workflow', 'arun', 'verma', 'sample pack dar tesr v2', '8.0.0', '', '3.0.0', '', 'NONE', '2015-10-20 04:30:20', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:10:41'),
(25, 'arunv2', 11, 'workflow', 'arun', 'verma', 'Modify Snapmirror Relationship', '1.2.3', '', '4.0.0.0.0', 'NetApp', 'NONE', '2015-10-19 03:08:58', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:11:18'),
(26, 'arunv2', 7, 'report', 'arun', 'verma', 'Ocum event report', '1.0.0', '', '7.1ga', '', 'NETAPP', '', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:12:13'),
(27, 'arunv2', 6, 'report', 'arun', 'verma', 'Fiber Channel Report', '1.2.3.4.5', '', '12.34.AB', '', 'NONE', '', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:12:44'),
(28, 'arunv2', 4, 'report', 'arun', 'verma', 'Report', '1.0.0', '', '1.1.1', '', 'NONE', '', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:13:19'),
(29, 'arunv2', 3, 'report', 'arun', 'verma', 'New Report', '1.0.0', '', '1.1.1', '', 'NONE', '', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:13:52'),
(30, 'arunv2', 5, 'performance', 'arun', 'verma', 'test name', '12.0.0', '', '11.0.0', '', 'NETAPP', '2015-10-20 05:25:05', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:14:58'),
(31, 'arunv2', 3, 'performance', 'arun', 'verma', 'Performance', '11.00', '', '10.00', '', 'NONE', '2015-10-17 03:08:39', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:17:54'),
(32, 'arunv2', 2, 'performance', 'arun', 'verma', 'Performance File', '1.0.0', '', '01.00', '', 'NETAPP', '2015-10-17 00:38:27', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-21 00:18:36'),
(33, 'gashutos', 35, 'workflow', 'ashutosh', 'garg', 'Dummy Fibre Channel pack', '1.1.0', '', '3.0.0.0.0', 'NetApp', 'NETAPP', '2015-10-21 00:38:44', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-21 01:24:11'),
(34, 'idmtestint01', 9, 'report', 'siebel', 'test', 'Events mail', '1.5.5', '', '2.5.6', '', 'NETAPP', '', 'Network appliance', ' ', '2015-10-21 01:57:21'),
(35, 'idmtestint01', 9, 'performance', 'siebel', 'test', 'Dummy OPM pack', '1.1.2', '', '3.1rc', '', 'NETAPP', '2015-10-21 02:07:32', 'Network appliance', ' ', '2015-10-21 02:19:36'),
(36, 'idmtestint01', 8, 'performance', 'siebel', 'test', 'Dummy OPM pack', '1.1.1', '', '3.1rc', '', 'NETAPP', '2015-10-21 02:06:34', 'Network appliance', ' ', '2015-10-21 02:27:49'),
(37, 'idmstageuser314', 29, 'workflow', 'stageuser', 'test314', 'Oracle cache query pack with new scheme', '5.0.2', '', '3.0.0.0.0', 'sinhaa', 'NONE', '2015-10-21 00:21:28', 'Network appliance', ' ', '2015-10-22 23:00:27'),
(38, 'arunv2', 40, 'workflow', 'arun', 'verma', 'test my pack', '21.3.45', '', '4.0.0', '', 'NONE', '2015-10-22 22:31:55', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-22 23:06:37'),
(39, 'idmstageuser314', 42, 'workflow', 'stageuser', 'test314', 'Oracle Cache Query Pack', '1.0.0', '', '4.0.0.0', 'Test', 'NETAPP', '2015-10-22 23:17:34', 'Network appliance', ' ', '2015-10-22 23:19:56'),
(40, 'arunv2', 29, 'workflow', 'arun', 'verma', 'Oracle cache query pack with new scheme', '5.0.2', '', '3.0.0.0.0', 'sinhaa', 'NONE', '2015-10-21 00:21:28', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-23 00:08:21'),
(41, 'shivanig', 29, 'workflow', 'shivani', 'gupta', 'Oracle cache query pack with new scheme', '5.0.2', '', '3.0.0.0.0', 'sinhaa', 'NONE', '2015-10-21 00:21:28', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-23 01:08:14'),
(42, 'arunv2', 51, 'workflow', 'arun', 'verma', 'Oracle cache query pack ', '12.0.0', '', '4.0.0.0.0', 'Test', 'NETAPP', '2015-10-23 03:24:50', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-23 03:48:45'),
(43, 'arunv2', 52, 'workflow', 'arun', 'verma', 'Oracle cache query pack with new scheme', '6.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 03:52:04', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-23 03:53:58'),
(44, 'arunv2', 53, 'workflow', 'arun', 'verma', 'Oracle cache query pack with new scheme', '7.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 03:56:49', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-23 04:00:13'),
(45, 'idmtestint01', 45, 'workflow', 'siebel', 'test', 'Oracle cache query pack with new scheme', '2.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 01:16:51', 'Network appliance', ' ', '2015-10-24 09:19:26'),
(46, 'idmstageuser314', 45, 'workflow', 'stageuser', 'test314', 'Oracle cache query pack with new scheme', '2.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 01:16:51', 'Network appliance', ' ', '2015-10-24 09:22:04'),
(47, 'idmstageuser300', 45, 'workflow', 'stageuser', 'test300', 'Oracle cache query pack with new scheme', '2.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 01:16:51', 'Network appliance', ' ', '2015-10-24 09:23:46'),
(48, 'idmstageuser314', 54, 'workflow', 'stageuser', 'test314', 'Test Dar File Upload', '1.0.0', '', '5.0.0', '', 'NONE', '2015-10-24 10:14:55', 'Network appliance', ' ', '2015-10-24 10:16:31'),
(49, 'shivanig', 45, 'workflow', 'shivani', 'gupta', 'Oracle cache query pack with new scheme', '2.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 01:16:51', 'Network appliance, inc', 'ifci tower, 8th floor, wing a no. 61, nehru place', '2015-10-26 00:06:25'),
(50, 'arunv2', 45, 'workflow', 'arun', 'verma', 'Oracle cache query pack with new scheme', '2.0.0', '', '3.0.0.0.0', 'Shivani', 'NETAPP', '2015-10-23 01:16:51', 'Network appliance, inc', '3rd floor, fair winds block egl software park off intermediate ring road karnataka', '2015-10-26 00:12:18');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `flagreport`
--

INSERT INTO `flagreport` (`id`, `flagPackUuid`, `flagPackName`, `flagPackVersion`, `trademark`, `infringement`, `flagComment`, `flagPackType`, `flagBy`, `flagPackOwner`, `flagDate`, `flagStatus`) VALUES
(1, 'ba11fac4-9710-4f2b-90ed-a7efbd4e723a', 'Manage SnapMirror-SnapVault Cascade Relationship', '1.0.0', 'true', 'true', 'test comments.', 'Workflow', 'shivanig', 'Arun.Verma@netapp.com', '2015-10-17 03:28:21', '0'),
(2, 'ba11fac4-9710-4f2b-90ed-a7efbd4e723a', 'Manage SnapMirror-SnapVault Cascade Relationship', '1.0.0', 'true', 'false', 'Test grievance comment', 'Workflow', 'gashutos', 'Arun.Verma@netapp.com', '2015-10-17 04:02:50', '1'),
(3, '', 'Ocum event report', '1.0.0', 'true', 'true', 'testing the field', 'OCUM', 'idmtestint01', '', '2015-10-21 00:08:34', '1'),
(4, '', 'Dummy Report', '1.5.2', 'false', 'false', 'testing flag comment', 'OCUM', 'bsachin', '', '2015-10-21 01:48:27', '1'),
(5, '', 'Dummy Report', '1.5.2', 'true', 'true', 'testing flag comment', 'OCUM', 'bsachin', '', '2015-10-21 01:48:51', '0'),
(6, '', 'Dummy OPM pack', '1.1.2', 'true', 'true', 'testing comment', 'OPM', 'idmtestint01', '', '2015-10-21 02:16:20', '1'),
(7, '', 'Fibre channel New pack', '1.5.5', 'true', 'true', 'test comments..', 'OCUM', 'shivanig', '', '2015-10-21 04:06:16', '1'),
(8, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle Cache Query Pack', '1.0.0', 'true', 'true', 'before approval by user.', 'Workflow', 'idmstageuser314', 'idmStageUser314@netapp.com', '2015-10-22 23:19:22', '1'),
(9, '', 'Events mail', '1.5.5', 'true', 'true', 'fsfsdf~`!@#$%^&*()_+{}|:"<>?,./;''[]\\-=09586 u52y3h45u23h45iu2 h3522\r\n5\r\n5634\r\n63\r\n46346,3477m54l7m45l77457,45n74''5745\r\n7457k45mn7;54l7n45745', 'OCUM', 'shivanig', '', '2015-10-23 05:42:30', '1');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `ocumreports`
--

INSERT INTO `ocumreports` (`id`, `reportName`, `reportDescription`, `reportVersion`, `versionChanges`, `OCUMVersion`, `minONTAPVersion`, `maxONTAPVersion`, `otherPrerequisite`, `certifiedBy`, `authorName`, `authorEmail`, `authorContact`, `reportFilePath`, `reportDate`) VALUES
(8, 'Dummy Report', 'NA', '1.5.2', 'NA', '2.5.6', '1.2.5', '1.1.1', 'NA', 'NETAPP', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'wfs_data/ocum_2015-10-21-00-25-49-4819/events_mail.zip', '2015-10-21 00:25:51'),
(9, 'Events mail', 'Nothing', '1.5.5', 'NA', '2.5.6', '4.2.1', '0.0.0', 'NA', 'NETAPP', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'wfs_data/ocum_2015-10-21-00-33-01-7223/events_mail.zip', '2015-10-21 00:33:05'),
(10, 'Fibre channel', 'Nothing', '1.5.7', 'NA', '2.5.6', '3.3.5', '1.2.4', 'Nothing', 'NETAPP', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'wfs_data/ocum_2015-10-21-00-34-31-1856/Fibre_Channel_server_configuration_and_provisioning_pack (2) (1).zip', '2015-10-21 00:34:33'),
(11, 'Fibre channel New pack', 'Nothing', '1.5.5', 'Nothing', '2.5.6', '23.5.4', '', 'NA', 'NETAPP', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'wfs_data/ocum_2015-10-21-00-35-35-8091/Fibre_Channel_server_configuration_and_provisioning_pack (2) (2).zip', '2015-10-21 00:35:37'),
(12, 'Report Namevnvnbv', '`~!@#$%^&*()_+{}|:"<>?,./;''[]\\-=29834981237`3267`12`135492365i8ijfewiuru uy r hweuyrgw rqwrq\r\nwtiweotueitueriutwqt''wÃ©te treiouyiureu yt iuer tuierhtiu erhtiueht iu e h tueh tuierh r tiuehtiuwehituuweiotj wt jweo twoitjwoietjoiw tiowt jiowtjiowt iwetjiwejtiuwethiuwethiuwhet uiwe thwiueth ui43h5iu345ii24i2141512523%^346#$67l34745&%^*L6$!$;1~%!@%236/26 @6 @#632 @!:%!@5 @#^34 7l54 7l @^#$& 54*l45l; 7k4lk56lk45k67 @#!6#634&45&456#5/32%236.346743.74.63$^#$6.23 4k l1`23k1n`3mn`1312 3mn12 4m235 34^#$^457n45m75kmv ejgiu39u834h985h2985y85u gjgniuwe g3tgh349th239t238r2398 r394r349 r 34  j3 rj32uirj23423#.', '12.34.56', '`~!@#$%^&*()_+{}|:"<>?,./;''[]\\-=29834981237`3267`12`135492365i8ijfewiuru uy r hweuyrgw rqwrq\r\nwtiweotueitueriutwqt''wÃ©te treiouyiureu yt iuer tuierhtiu erhtiueht iu e h tueh tuierh r tiuehtiuwehituuweiotj wt jweo twoitjwoietjoiw tiowt jiowtjiowt iwetjiwejtiuwethiuwethiuwhet uiwe thwiueth ui43h5iu345ii24i2141512523%^346#$67l34745&%^*L6$!$;1~%!@%236/26 @6 @#632 @!:%!@5 @#^34 7l54 7l @^#$& 54*l45l; 7k4lk56lk45k67 @#!6#634&45&456#5/32%236.346743.74.63$^#$6.23 4k l1`23k1n`3mn`1312 3mn12 4m235 34^#$^457n45m75kmv ejgiu39u834h985h2985y85u gjgniuwe g3tgh349th239t238r2398 r394r349 r 34  j3 rj32uirj23423#.', '213.234.325', '654.67.798', '908.68.767', '`~!@#$%^&*()_+{}|:"<>?,./;''[]\\-=29834981237`3267`12`135492365i8ijfewiuru uy r hweuyrgw rqwrq\r\nwtiweotueitueriutwqt''wÃ©te treiouyiureu yt iuer tuierhtiu erhtiueht iu e h tueh tuierh r tiuehtiuwehituuweiotj wt jweo twoitjwoietjoiw tiowt jiowtjiowt iwetjiwejtiuwethiuwethiuwhet uiwe thwiueth ui43h5iu345ii24i2141512523%^346#$67l34745&%^*L6$!$;1~%!@%236/26 @6 @#632 @!:%!@5 @#^34 7l54 7l @^#$& 54*l45l; 7k4lk56lk45k67 @#!6#634&45&456#5/32%236.346743.74.63$^#$6.23 4k l1`23k1n`3mn`1312 3mn12 4m235 34^#$^457n45m75kmv ejgiu39u834h985h2985y85u gjgniuwe g3tgh349th239t238r2398 r394r349 r 34  j3 rj32uirj23423#.', 'NETAPP', 'Author Name', 'email@email.com', '<A HREF="http://www.netapp.com/us/services-support/index.aspx"> Support </A>', 'wfs_data/ocum_2015-10-22-21-50-30-3817/ZipPack.zip', '2015-10-22 21:51:15'),
(14, 'Fibre channel New pack', 'Fibre channel New pack', '2.0.0', 'Fibre channel New pack', '1.0.0', '3.3.3', '', 'Fibre channel New pack', 'NETAPP', 'test author', 'test@email.com', '', 'wfs_data/ocum_2015-10-23-04-43-07-807/Oracle_cache_query_New.zip', '2015-10-23 04:43:19');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `opmpacks`
--

INSERT INTO `opmpacks` (`id`, `packName`, `packDescription`, `packVersion`, `versionChanges`, `OPMVersion`, `minONTAPVersion`, `maxONTAPVersion`, `otherPrerequisite`, `certifiedBy`, `authorName`, `packFilePath`, `packDate`) VALUES
(6, 'Chrome Driver pack', 'NA', '1.2.2', 'Nothing', '1.1.1', '3.3.5', '1.0.0', 'NA', 'NETAPP', 'Arun Verma', 'wfs_data/opm_2015-10-21-00-36-44-8922/chromedriver_win32 (1).zip', '2015-10-21 00:36:47'),
(7, 'Performance Fibre Channel', 'NA', '1.2.2', 'Nothing', '2.3.4', '1.2.3', '3.3.3', 'NA', 'NETAPP', 'Arun Verma', 'wfs_data/opm_2015-10-21-00-37-40-7513/Fibre_Channel_server_configuration_and_provisioning_pack (2) (3).zip', '2015-10-21 00:37:43'),
(8, 'Dummy OPM pack', 'Testing description', '1.1.1', 'Nothing ', '3.1rc', '8.1', '', '', 'NETAPP', 'Sachin Balmane', 'wfs_data/opm_2015-10-21-02-06-31-4316/test pack OPM.zip', '2015-10-21 02:06:34'),
(10, 'This is report name This is report name This is report name This is report name This is report name This is report name', 'Description: <h3>This is a pack with new cache query and a new scheme. </h3>\r\n<h2> This is just a dummy text </h2>\r\n<ui><li>India</li> <li>China</li> <li>US</li> <li>UK</li> </ui>', '2.0.0', 'Changes:\r\n<h3>This is a pack with new cache query and a new scheme. </h3>\r\n<h2> This is just a dummy text </h2>\r\n<ui><li>India</li> <li>China</li> <li>US</li> <li>UK</li> </ui>', '1.0.0', '1.1.1', '99.99.99', 'Other:\r\n<h3>This is a pack with new cache query and a new scheme. </h3>\r\n<h2> This is just a dummy text </h2>\r\n<ui><li>India</li> <li>China</li> <li>US</li> <li>UK</li> </ui>', 'NETAPP', 'Author Name', 'wfs_data/opm_2015-10-23-04-13-51-1686/Oracle_cache_query_New.zip', '2015-10-23 04:14:33'),
(11, 'This is report name This is report name This is report name This is report name This is report name This is report name', 'hsdf s sd fhjhfvbasdhf', '1.0.0', '', '1.0.0', '1.1.1', '11.11.11', '', 'NETAPP', 'test author', 'wfs_data/opm_2015-10-23-04-40-02-072/Oracle_cache_query_New.zip', '2015-10-23 04:40:17'),
(12, 'pack', 'test', '11.00', 'test', '11', '11', '11', 'test', 'NETAPP', 'gaurav', 'wfs_data/opm_2015-10-25-23-10-59-7601/packs.zip', '2015-10-25 23:11:07');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `packdetails`
--

INSERT INTO `packdetails` (`id`, `uuid`, `packName`, `packDescription`, `author`, `certifiedBy`, `version`, `minWfaVersion`, `minsoftwareversion`, `maxsoftwareversion`, `keywords`, `packFilePath`, `packDate`, `cautionStatus`, `cautionContent`, `post_approved`, `preRequisites`, `whatsChanged`, `contactName`, `contactEmail`, `contactPhone`, `tags`, `modDate`) VALUES
(29, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle cache query pack with new scheme', 'this is a packe with new cache query and a new scheme<h1>This is just a dummy text </h1>\r\n<p><ul><li>India </li><li>China </li><li>US </li><li>UK </li></ul></p>\r\n	', 'sinhaa', 'NONE', '5.0.2', '3.0.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-21-00-21-01-3564/Oracle_cache_query_with_new_scheme_pack_signed_updated.zip', '2015-10-21 00:21:28', 'true', 'PHA+TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSBiZWZvcmUgdXNpbmcgdGhpcyBQYWNrLiBQbGVhc2UgY29udGFjdCB5b3VyIGFjY291bnQgdGVhbSB0byBzY2hlZHVsZSB0aGUgU0RXLiBVc2luZyB0aGlzIHBhY2sgd2l0aG91dCBhdHRlbmRpbmcgdGhlIFNEVywgaXMgZG9uZSBzbyBhdCB0aGUgcmlzayBvZiB0aGUgQ3VzdG9tZXIgYW5kIGFnYWluc3QgTmV0QXBwJnJzcXVvO3MgcmVjb21tZW5kYXRpb24uPC9wPg0K', 'true', 'NA', 'NA', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'cachequery,', '2015-10-25 23:16:14'),
(30, '7d44f66f-1b7f-46f8-be7c-d12bdd825ca8', 'Dummy Fibre Channel pack', 'This pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). Following are the workflows contained in the pack: 1. FCP server configuration for Windows and ESX 2. FCP LUN provisioning for Windows and ESX', 'Shivani', 'NONE', '1.2.5', '3.0.0.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-21-00-22-33-0875/ZipPack.zip', '2015-10-21 00:23:02', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'NA', 'NA', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'fibrechannelpack,', ''),
(31, 'sample_pack_dar_tesr_v2', 'sample pack dar tesr v2', 'sample pack dar tesr v1', '', 'NONE', '1.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-21-00-26-16-0459/definitions_10_20_15__13_34_27.dar', '2015-10-21 00:26:32', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'testdar,', ''),
(32, 'sample_pack_dar_tesr_v2', 'sample pack dar tesr v2', 'sample pack dar tesr v1', '', 'NONE', '2.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-21-00-26-42-5925/definitions_10_20_15__13_34_27.dar', '2015-10-21 00:26:56', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'testdar2,', ''),
(33, 'sample_pack_dar_tesr_v1', 'sample pack dar tesr v1', 'sample pack dar tesr v1', '', 'NONE', '1.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-21-00-27-11-2417/definitions_10_17_15__09_24_31.dar', '2015-10-21 00:27:27', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'testdarv1,', ''),
(34, 'sample_pack_dar_tesr_v1', 'sample pack dar tesr v1', 'sample pack dar tesr v1', '', 'NONE', '2.0.0', '3.0.0', 'No', 'Partial', '', 'wfs_data/build_2015-10-21-00-27-35-8577/definitions_10_17_15__09_24_31.dar', '2015-10-21 00:27:52', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'sample pack dar tesr v1', 'sample pack dar tesr v1', 'sample pack dar test', 'test@test.com', '0-987654321', 'testdarv2,', ''),
(35, '7d44f66f-1b7f-46f8-be7c-d12bdd825ca7', 'Dummy Fibre Channel pack', 'This pack contains two workflows to set up the FC service on a Storage Virtual Machine (SVM, formerly known as Vserver), in preparation for provisioning a LUN for use as a datastore using an FC HBA the host computer (Windows or ESX). Following are the workflows contained in the pack: 1. FCP server configuration for Windows and ESX 2. FCP LUN provisioning for Windows and ESX', 'NetApp', 'NETAPP', '1.1.0', '3.0.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-21-00-38-06-8617/Fibre_Channel_server_configuration_and_provisioning_pack.zip', '2015-10-21 00:38:44', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'NA', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'fibrechannel,workflowpack,', ''),
(36, '312fa634-3114-49f8-8376-98aee8e10b0f', 'Foreign LUN Import', 'During the process of storage consolidation in your data center, you need to migrate data from third-party LUNs to a NetApp cluster. This pack contains four workflows that enable you to migrate data from third-party LUNs to a NetApp cluster using Foreign LUN Import (FLI) functionality. These packs support both Online and Offline FLI.\r\n	\r\n	The workflows in this pack should be executed in the following recommended sequence:\r\n		1. FLI Setup And Import\r\n		2. FLI Status And Restart\r\n		3. FLI Post Jobs\r\n		4. FLI Report\r\n\r\n	Prerequisites:\r\n\r\n		1. You must have installed WFA 3.1 \r\n		2. You must ensure that OnCommand Unified Manager 6.3 is added as a data source in your WFA\r\n\r\n		This pack contains a configuration file called "data-collector_lun_import_ext.conf". You can obtain the same from any help content of the workflows. The configuration file must be placed in the following location as per the OS selected for the Unified Manager:\r\n			i.  Windows user: C:\\Program Files\\NetApp\\ocum\\etc\\data-collector\r\n			ii. Linux\\Virtual Appliance (vApp) user (with root login): /opt/netapp/ocum/etc/data-collector/\r\n	', 'NetApp', 'NETAPP', '1.6.0', '3.1.0.0.0', 'No', 'No', '', 'wfs_data/build_2015-10-21-00-42-39-1868/Foreign_LUN_Import.zip', '2015-10-21 00:43:09', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'NA', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'fibrechannel,workflowpack,dummy,', ''),
(37, '5ad2a7c7-eaae-467f-b4a1-58f12ff8c45d', 'OnCommand Insight Connector', ' <strong>This is just a dummy html text inside cdata </strong> ', 'NetApp', 'NETAPP', '1.1.0', '3.0.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-21-00-46-22-6935/OnCommandInsight.zip', '2015-10-21 00:46:57', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'NA', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'pack,', ''),
(38, 'Test_Pack', 'Test Pack', 'Nothing', '', 'NONE', '1.5.4', '3.1.0.0.0', 'Yes', 'Yes', '', 'wfs_data/build_2015-10-21-05-36-27-2953/test_ckbv.dar', '2015-10-21 05:36:56', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'testpack,', ''),
(39, 'Test_Pack', 'Test Pack', 'Nothing', '', 'NONE', '1.4.8', '1.6.9', 'Yes', 'No', '', 'wfs_data/build_2015-10-21-05-38-15-5597/test_ckbv.dar', '2015-10-21 05:38:52', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'testworkflow,', ''),
(43, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle cache query pack with new scheme', 'this is a packe with new cache query and a new scheme<h1>This is just a dummy text </h1>\r\n<p><ul><li>India </li><li>China </li><li>US </li><li>UK </li></ul></p>\r\n	', 'sinhaa', 'NONE', '4.0.2', '3.0.0.0.0', 'No', 'No', '', 'wfs_data/build_2015-10-23-00-09-57-0327/Oracle_cache_query_with_new_scheme_pack_signed_updated.zip', '2015-10-23 00:10:33', 'true', 'PHA+TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSBiZWZvcmUgdXNpbmcgdGhpcyBQYWNrLiBQbGVhc2UgY29udGFjdCB5b3VyIGFjY291bnQgdGVhbSB0byBzY2hlZHVsZSB0aGUgU0RXLiBVc2luZyB0aGlzIHBhY2sgd2l0aG91dCBhdHRlbmRpbmcgdGhlIFNEVywgaXMgZG9uZSBzbyBhdCB0aGUgcmlzayBvZiB0aGUgQ3VzdG9tZXIgYW5kIGFnYWluc3QgTmV0QXBwJnJzcXVvO3MgcmVjb21tZW5kYXRpb24uPC9wPg0K', 'true', 'NO', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'testpack,oraclecache,', ''),
(49, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle cache query pack with new scheme', 'this is a packe with new cache query and a new scheme<h1>This is just a dummy text </h1>\r\n<p><ul><li>India </li><li>China </li><li>US </li><li>UK </li></ul></p>\r\n	', 'sinhaa', 'NONE', '4.0.3', '3.0.0.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-23-02-10-59-1719/Oracle_cache_query_with_new_scheme_pack_signed_updated.zip', '2015-10-23 02:11:22', 'true', 'PHA+TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSBiZWZvcmUgdXNpbmcgdGhpcyBQYWNrLiBQbGVhc2UgY29udGFjdCB5b3VyIGFjY291bnQgdGVhbSB0byBzY2hlZHVsZSB0aGUgU0RXLiBVc2luZyB0aGlzIHBhY2sgd2l0aG91dCBhdHRlbmRpbmcgdGhlIFNEVywgaXMgZG9uZSBzbyBhdCB0aGUgcmlzayBvZiB0aGUgQ3VzdG9tZXIgYW5kIGFnYWluc3QgTmV0QXBwJnJzcXVvO3MgcmVjb21tZW5kYXRpb24uPC9wPg0K', 'true', 'NA', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'oraclequery,', ''),
(54, 'Test_Dar_File_Upload', 'Test Dar File Upload', 'test description', '', 'NONE', '1.0.0', '5.0.0', 'Yes', 'Partial', '', 'wfs_data/build_2015-10-24-10-13-18-019/definitions_10_17_15__09_24_31.dar', '2015-10-24 10:14:55', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'other requirements', 'new chnages', 'test shivi user', 'shivani.gupta@netapp.com', '<A HREF="www.google.co.in" > Google </A>', 'dar,', ''),
(55, 'Test_Dar_File', 'Test Dar File', 'test description test test test test', '', 'NONE', '1.5.0', '4.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-24-10-17-49-87/definitions_10_17_15__09_24_31.dar', '2015-10-24 10:19:21', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'test user name', 'shivani.gupta@netapp.com', '9000000000000000000000000000', 'dar,dar1,dar2,', ''),
(56, 'Test_Dar_File', 'Test Dar File', 'test description pack', '', 'NONE', '2.0.0', '5.0.0.0', 'Partial', 'Yes', '', 'wfs_data/build_2015-10-24-10-22-04-4509/VMwarevCenter (3).dar', '2015-10-24 10:23:33', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'Nothing', 'Nothing', 'test user name', 'shivani.gupta@netapp.com', '9000000000000000000000000000', 'dar1,dar2,newpack,', ''),
(58, 'a6c4e30e-ce81-48f7-92cb-fcbcb25a454b', 'Oracle cache query pack with new scheme', 'this is a packe with new cache query and a new scheme&lt;h1&gt;This is just a dummy text &lt;/h1&gt;\r\n&lt;p&gt;&lt;ul&gt;&lt;li&gt;India &lt;/li&gt;&lt;li&gt;China &lt;/li&gt;&lt;li&gt;US &lt;/li&gt;&lt;li&gt;UK &lt;/li&gt;&lt;/ul&gt;&lt;/p&gt;\r\n	', 'Shivani', 'NETAPP', '2.0.0', '3.0.0.0.0', 'No', 'Yes', '', 'wfs_data/build_2015-10-26-00-18-23-6652/Oracle_cache_query_New.zip', '2015-10-26 00:18:42', 'false', 'TmV0QXBwIHN0cm9uZ2x5IHJlY29tbWVuZHMgdGhhdCB0aGUgY3VzdG9tZXIgYXR0ZW5kIHRoZSBTZXJ2aWNlIERlc2lnbiBXb3Jrc2hvcCAoU0RXKSBjb25kdWN0ZWQgYXQgdGhlaXIgc2l0ZSAgYmVmb3JlIHVzaW5nIHRoaXMgUGFjay4gUGxlYXNlIGNvbnRhY3QgeW91ciBhY2NvdW50IHRlYW0gdG8gc2NoZWR1bGUgdGhlIFNEVy4gVXNpbmcgdGhpcyBwYWNrIHdpdGhvdXQgYXR0ZW5kaW5nIHRoZSBTRFcsIGlzIGRvbmUgc28gYXQgdGhlIHJpc2sgb2YgdGhlIEN1c3RvbWVyIGFuZCBhZ2FpbnN0IE5ldEFwcOKAmXMgcmVjb21tZW5kYXRpb24u', 'true', 'NA', 'Nothing', 'Arun Verma', 'arun.verma@netapp.com', '09873203079', 'taguser,', '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=487 ;

--
-- Dumping data for table `packentities`
--

INSERT INTO `packentities` (`entityId`, `packId`, `entityType`, `uuid`, `name`, `description`, `version`, `certifiedBy`, `minOntapVersion`, `scheme`, `entityDate`) VALUES
(246, 14, 'Category', '41323e8f-edf5-41d5-8a12-04819e73b378', 'Storage Provisioning', 'A set of workflows for storage provisioning in 7-Mode and Clustered Data ONTAP storage systems. This includes workflows which provision storage objects like volumes, LUNs, qtrees, configure NFS exports, create CIFS shares, create igroups and map provisioned LUNs to those igroups.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(247, 14, 'Command', '0f4d7d13-516e-4d95-b8e0-9f9ea59b6c10', 'Modify Initiator Group', 'Add or Remove Initiators from Igroup', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(248, 14, 'Category', '3fd4e387-3003-47bc-9631-0c7c6279767f', 'Setup', 'A set of workflows that help setup the storage environment.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(249, 14, 'Workflow', 'baacba9d-d8a0-415f-8b42-9f64207657c4', 'FCP LUN Provisioning', 'Provisioning one or more LUNs on Clustered Data ONTAP storage systems for Windows and ESX. This workflow includes:\n\n- Creating a new aggregate or use existing one.\n- Creating a new volume or use existing one.\n- Creating a new igroup or use/modify existing one.\n- Creating one or more LUNs.\n- Mapping the LUNs to either a newly created igroup or to a specified existing igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(250, 14, 'Workflow', 'b67daf1c-f5b6-413d-982f-72a85b95be60', 'FCP Server Configuration', 'This workflow configures FCP service on a Storage Virtual Machine.\n\nConfiguring FCP services on a Storage Virtual Machine involves the following steps:\n    1. Creating a new aggregate or using an existing one.\n    2. Creating a new Storage Virtual Machine and setting up Active Directory account.\n    3. Setting up FCP service.\n    4. Creating a Portset.\n    5. Creating a FCP Logical Interface.\n    6. Adding FCP Logical Interface to Portset.\n\nPre-requisites:\n\n1) FCP license must be enabled.', '1.0.0', 'NETAPP', '', '', '2015-10-19 22:56:46'),
(353, 29, 'Scheme', '91bf8d58-74cf-4ff6-a8b3-0cead03b3f81', 'oracle', '', '1.0.0', 'NONE', '', '', '2015-10-21 00:21:28'),
(354, 29, 'DictionaryEntry', 'dda0ae06-6a3a-47ef-8415-eb3f6160fffb', 'student', '', '1.0.0', 'NONE', '', '', '2015-10-21 00:21:28'),
(355, 29, 'SqlDataProviderType', '5f51ef3d-67a6-44f6-8d67-79c49f78d348', 'Oracle JDBC_', '', '1.0.0', 'NONE', '', '', '2015-10-21 00:21:28'),
(356, 29, 'CacheQuery', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', '', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:21:28'),
(357, 30, 'Category', '41323e8f-edf5-41d5-8a12-04819e73b378', 'Storage Provisioning', 'A set of workflows for storage provisioning in 7-Mode and Clustered Data ONTAP storage systems. This includes workflows which provision storage objects like volumes, LUNs, qtrees, configure NFS exports, create CIFS shares, create igroups and map provisioned LUNs to those igroups.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:23:02'),
(358, 30, 'Command', '0f4d7d13-516e-4d95-b8e0-9f9ea59b6c10', 'Modify Initiator Group', 'Add or Remove Initiators from Igroup', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:23:02'),
(359, 30, 'Category', '3fd4e387-3003-47bc-9631-0c7c6279767f', 'Setup', 'A set of workflows that help setup the storage environment.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:23:02'),
(360, 30, 'Workflow', 'baacba9d-d8a0-415f-8b42-9f64207657c4', 'FCP LUN Provisioning', 'Provisioning one or more LUNs on Clustered Data ONTAP storage systems for Windows and ESX. This workflow includes:\n\n- Creating a new aggregate or use existing one.\n- Creating a new volume or use existing one.\n- Creating a new igroup or use/modify existing one.\n- Creating one or more LUNs.\n- Mapping the LUNs to either a newly created igroup or to a specified existing igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:23:02'),
(361, 30, 'Workflow', 'b67daf1c-f5b6-413d-982f-72a85b95be60', 'FCP Server Configuration', 'This workflow configures FCP service on a Storage Virtual Machine.\n\nConfiguring FCP services on a Storage Virtual Machine involves the following steps:\n    1. Creating a new aggregate or using an existing one.\n    2. Creating a new Storage Virtual Machine and setting up Active Directory account.\n    3. Setting up FCP service.\n    4. Creating a Portset.\n    5. Creating a FCP Logical Interface.\n    6. Adding FCP Logical Interface to Portset.\n\nPre-requisites:\n\n1) FCP license must be enabled.', '1.2.3', 'NETAPP', '', '', '2015-10-21 00:23:02'),
(362, 35, 'Category', '41323e8f-edf5-41d5-8a12-04819e73b378', 'Storage Provisioning', 'A set of workflows for storage provisioning in 7-Mode and Clustered Data ONTAP storage systems. This includes workflows which provision storage objects like volumes, LUNs, qtrees, configure NFS exports, create CIFS shares, create igroups and map provisioned LUNs to those igroups.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:38:44'),
(363, 35, 'Command', '0f4d7d13-516e-4d95-b8e0-9f9ea59b6c10', 'Modify Initiator Group', 'Add or Remove Initiators from Igroup', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:38:44'),
(364, 35, 'Category', '3fd4e387-3003-47bc-9631-0c7c6279767f', 'Setup', 'A set of workflows that help setup the storage environment.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:38:44'),
(365, 35, 'Workflow', 'baacba9d-d8a0-415f-8b42-9f64207657c4', 'FCP LUN Provisioning', 'Provisioning one or more LUNs on Clustered Data ONTAP storage systems for Windows and ESX. This workflow includes:\n\n- Creating a new aggregate or use existing one.\n- Creating a new volume or use existing one.\n- Creating a new igroup or use/modify existing one.\n- Creating one or more LUNs.\n- Mapping the LUNs to either a newly created igroup or to a specified existing igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:38:44'),
(366, 35, 'Workflow', 'b67daf1c-f5b6-413d-982f-72a85b95be60', 'FCP Server Configuration', 'This workflow configures FCP service on a Storage Virtual Machine.\n\nConfiguring FCP services on a Storage Virtual Machine involves the following steps:\n    1. Creating a new aggregate or using an existing one.\n    2. Creating a new Storage Virtual Machine and setting up Active Directory account.\n    3. Setting up FCP service.\n    4. Creating a Portset.\n    5. Creating a FCP Logical Interface.\n    6. Adding FCP Logical Interface to Portset.\n\nPre-requisites:\n\n1) FCP license must be enabled.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:38:44'),
(367, 36, 'Workflow', '1e86b211-b985-428d-8f38-b6147530d574', 'FLI Post Jobs', 'This workflow breaks the LUN import relationships from the cluster and also deletes any LUN of a failed LUN import operation.\n\nDeletion of LUN import relationship involves the following steps:\n    1. Deleting LUN import relationship for selected LUN import\n    2. Making LUN online if stated\n    3. Deleting LUN from ONTAP for failed LUN import\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTAP Version: 8.3.1 and above\n6) UM Version: 6.3', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(368, 36, 'Command', '2179516b-498b-4506-bcd7-f99989a842bc', 'Database Report Genarator', 'Generates a database report for a specific database table under a specific database schema.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(369, 36, 'Workflow', 'bf5fc3eb-4298-489b-9ed2-c502b338bf69', 'FLI Setup And Import', 'This workflow enables you to import data from foreign RAID array LUNs to cluster LUNs.\n\nMigration of foreign LUNs involves the following steps:\n    1. Marking disk''s as foreign\n    2. Creating LUNs by size of foreign disk\n    3. Making LUNs offline\n    4. Mapping newly created LUNs to existing I-group\n    5. Creating LUN import with/without throttle\n    6. Starting of LUN imports (Offline/Online)\n    7. Verification of LUN imports (Offline)\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTAP Vsersion: 8.3.1 and above\n6) UM Version: 6.3', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(370, 36, 'Dictionary Entry', '9248e6d7-5842-4fe0-9a5d-946e49cd0343', 'Disk_Path', 'A disk path in a storage system.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(371, 36, 'Workflow', '1837b62c-a373-4616-a210-467e138bde9d', 'FLI Report', 'This workflow generates a status report of the LUN import in the form of CSV. A file in the following format &lt;lun_report_dd_mm_yyyy__hh_mm_ss.csv&gt; will be created at the location, WFA Installation Directory-&gt;jboss-&gt;standalone-&gt;tmp.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(372, 36, 'Cache Query', 'c08ea9c5-d77c-457b-8f5e-736efc6335c9', 'Lun_Import for query OnCommand Unified Manager_6.3', '', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(373, 36, 'Workflow', 'ed78c244-20e1-41e0-b102-57c6f70e3716', 'FLI Status And Restart', 'This workflow is a helper workflow for the workflow &quot;FLI Setup And Import&quot;.\nThis workflow enables you to manage LUN migration jobs. You can also use this workflow to start LUN import operation.\n\nWorkflow displays all migration jobs (IN_PROGRESS, FAILED, STOPPED, PAUSED and COMPLETED).\nYou can perform the following actions:\n    1. Starting migration jobs which are stopped (stopped/not yet started)\n    2. Pausing migration jobs which are active or failed\n    3. Resuming migration jobs which are paused\n    4. Stopping migration jobs (import/verification) which are active or failed\n    5. Modifying throttle value (on any migration job)\n    6. Changing of import state (offline -&gt; online and vice-versa) when the import is stopped, paused\n    7. Verifying already completed import\n\nPrerequisites:\n\n1) Destination SVM and Igroup should be available\n2) Destination FlexVol should be available\n3) Ensure that FCP logical interface is available on all nodes\n4) WFA Server: WFA 3.1\n5) ONTA', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(374, 36, 'Command', '0bec2260-f295-44a9-b129-59cd34987e94', 'LUN Map', 'Maps the LUN to all the initiators in the specified initiator group.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(375, 36, 'Command', 'f74f6b65-3caa-49d9-be6a-967ad59fd6f9', 'LUN Create by Size of Foreign Disk', 'Creates a single unmapped LUN of foreign disk size on a Storage Virtual Machine.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(376, 36, 'Dictionary Entry', '234e2812-490d-4c38-9701-c71fe7621791', 'Lun_Import', 'Information of a Foreign LUN Import relationship.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(377, 36, 'Command', '8df8a6ff-0dbe-4783-8897-ea9a38aefe7d', 'Wait for LUN Import and Verify', 'This command will monitor active LUN import operations on a given node. \n\nIf the number of active LUN import  operations on the given node is below the specified limit, the command will return successfully. Otherwise the command will wait until the number of LUN import is below the limit.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(378, 36, 'Command', '49f5450c-edbf-4a36-ba8f-931a1d04a3dd', 'LUN Unmap', 'Unmaps a Cluster-Mode LUN from an igroup.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(379, 36, 'Command', '51d1650d-a805-43d4-9997-88e8f4601093', 'LUN Delete', 'Destroy the specified LUN. This operation will fail if the LUN is currently mapped and is online. The force option can be used to destroy it regardless of being online or mapped.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(380, 36, 'Cache Query', '26674358-a81f-4827-83be-87f87d08b5ce', 'Disk_Path for query OnCommand Unified Manager_6.3', '', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(381, 36, 'Command', '507db80a-f34b-44a6-a628-43569f672d71', 'Change LUN State', 'This command used to change LUN state (offline -&gt; online and online -&gt; offline) on basis of ''ChangeState'' selected.\nIf ChangeState is ''offline'' then -&gt; Disables block-protocol accesses to the LUN. Mappings, if any, configured for the LUN are not altered.\nIf ChangeState is ''online'' then -&gt; Re-enables block-protocol accesses to the LUN.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(382, 36, 'Command', '182046db-7eba-4840-849d-e66ff2f09139', 'LUN Import', 'This command used to perform different ''lun import'' operations.\n ''create'' - Create a LUN import relationship with the purpose of importing foreign disk data into the LUN\n ''delete'' - Deletes the import relationship of the specified LUN or the specified foreign disk\n ''start'' - Start the import for the specified LUN\n ''pause'' - Pause the import for the specified LUN\n ''resume'' - Resume the import for the specified LUN\n ''stop'' - Stop and abort the import for the specified LUN. All import checkpoint data will be lost\n ''throttle'' - Modify the max throughput limit for the specified import relationship\n ''verify-start'' - Start the verification of the foreign disk and LUN data. The import admin/operational state must be stopped/stopped or started/completed. The verification does a block for block comparison of the LUN and foreign disk. If the verification detects a mismatch between the foreign disk and LUN then the verify will stop\n ''verify-stop'' - Stop the verify for the specified LUN', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(383, 36, 'Command', '21def7fe-c283-4ad0-ab5c-e735495c110d', 'Modify Storage Disk', 'Modify the attributes of storage-disk object. A true value indicates an array LUN has been designated as a foreign LUN and cannot be assigned', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:43:09'),
(384, 36, 'Category', '9abc6dec-4f9f-41cb-bb3b-29fd4fc9cd9f', 'Migration', 'A set of workflows that help manage storage. This includes workflows for resizing storage, migrating volumes from their current locations to other storage systems and aggregates, and moving volumes non-disruptively in Cluster-Mode.', '1.0.0', 'NONE', '', '', '2015-10-21 00:43:09'),
(385, 37, 'Scheme', '03df995f-50fe-4c60-b1b4-86415ee53b33', 'oci', '', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(386, 37, 'Dictionary Entry', 'a0869f12-b5f9-400d-ae87-f82bf5cc1b1b', 'Internal_Volume', 'Internal volume as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(387, 37, 'Dictionary Entry', '5ed1c56d-be44-4368-bcf6-b36d9c7d3bba', 'Virtual_Machine', 'Virtual machine as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(388, 37, 'Dictionary Entry', '39660258-0d16-4011-8a7e-22920c1d05d7', 'Volume', 'Volumes as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(389, 37, 'Dictionary Entry', '70a3b1d4-fa32-4662-9bd6-0ce63ebf7535', 'Application', 'Application information as defined in OnCommand Insight. This helps to track the applications running in the environment and associate it with compute and storage resources.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(390, 37, 'Dictionary Entry', 'babd1c0b-3917-4ecc-a22f-ff5caf5d8170', 'DataStore_Performance', 'A set of counters for the DataStore. The counter value is the average of hourly sample data for the last 15 days.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(391, 37, 'Dictionary Entry', '33534350-11db-4b05-8d49-62d315571685', 'Storage_Array_Performance', 'A set of counters for the Storage Array. The counter value is the average of hourly sample data for the last 15 days.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(392, 37, 'Dictionary Entry', '2cef9bf3-0b2b-430e-a310-77d0e9ef23da', 'Storage_Pool_Annotation', 'Annotation value applied to Storage Pool in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(393, 37, 'Script Data Source Type', 'df48c2fd-54ff-4d0d-bbd2-0f28fcbcd5c6', 'OnCommand Insight_7.X', '', '1.1.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(394, 37, 'Dictionary Entry', '56a5b305-e8ed-4d1c-bd55-83a18da8baa0', 'Switch_Annotation', 'Annotation value applied to Switch in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(395, 37, 'Dictionary Entry', '1450b3ee-7354-4873-8e15-b8e7a670ae06', 'Volume_Application', 'Association object representing the association between a volume and an application.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(396, 37, 'Dictionary Entry', 'c4eeffe9-f111-4882-8928-4e5bc2970a28', 'Storage_Node_Annotation', 'Annotation value applied to Storage Node in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(397, 37, 'Dictionary Entry', '73b4d918-cc78-4e9c-becd-eda4edf5bc43', 'Host', 'Host as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(398, 37, 'Dictionary Entry', '91a25a80-c320-44e7-8207-6859a4540e12', 'Host_Application', 'Association object representing the association between a host and an application.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(399, 37, 'Dictionary Entry', '40acee28-ed00-476d-8e8d-514b4a4e6a63', 'DataStore', 'DataStores as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(400, 37, 'Dictionary Entry', '3dd8819a-e276-44c9-af42-75fd5617a2e3', 'Host_Annotation', 'Annotation value applied to Host in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(401, 37, 'Dictionary Entry', '7b4e3d29-feea-4997-9a61-4b2b59bcc9dc', 'Storage_Pool', 'Storage pool as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(402, 37, 'Dictionary Entry', '292315e7-9965-4633-aa14-e002b0b39a65', 'Fabric', 'Fabric as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(403, 37, 'Dictionary Entry', '11a8f0b2-d836-4745-9ef9-73edc8114061', 'Storage_Node_Performance', 'A set of counters for the Storage Node. The counter value is the average of hourly sample data for the last 15 days.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(404, 37, 'Dictionary Entry', '43d17e0b-2b44-43e2-9ccf-d18c2d7fdcac', 'Volume_Annotation', 'Annotation value applied to Volume in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(405, 37, 'Dictionary Entry', '22656253-514a-41cf-b5a8-8ece2a867f68', 'Storage_Array', 'Storage Arrays as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(406, 37, 'Dictionary Entry', '21a97868-8ad3-49b8-ac88-bbeb162562eb', 'Annotation_Enum_Value', 'Annotation enum values as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(407, 37, 'Dictionary Entry', 'b105ba00-0c6c-4d74-95a3-1a29b5d00da5', 'Switch', 'Switch as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(408, 37, 'Dictionary Entry', 'add59636-1d12-4286-8279-f2ec4cf50e5c', 'Disk', 'Disk as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(409, 37, 'Dictionary Entry', '1260d8a5-3dc6-4137-a528-d15347159d7b', 'Internal_Volume_Application', 'Association object representing the association between a internal volume and an application.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(410, 37, 'Dictionary Entry', '2487dbfd-a997-4440-8399-2103565517a5', 'Storage_Node', 'Storage node as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(411, 37, 'Dictionary Entry', 'c7b2d9c7-ea20-4b65-9f0c-524a4a033245', 'Storage_Array_Annotation', 'Annotation value applied to Storage Array in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(412, 37, 'Dictionary Entry', '7fb19d68-ba6e-44cb-95d0-94cf141fdae4', 'Annotation', 'Annotation information as defined in OnCommand Insight. Annotations are like specialized notes that can be assigned to storage or compute resources of the environment. Annotations help customizing OnCommand Insight to track data as per the corporate requirements. Some examples of annotations could be asset end of life, tier, data center.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(413, 37, 'Dictionary Entry', 'd39daab7-4159-471d-b415-6fe1efb90dd0', 'Vmdk', 'VMDKs as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(414, 37, 'Dictionary Entry', '93082def-8884-4191-beeb-cbf4c28b39c5', 'DataStore_Annotation', 'Annotation value applied to DataStore in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(415, 37, 'Dictionary Entry', '36d41aed-97cb-4191-b413-96287cc1505d', 'Port', 'Port as discovered by OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(416, 37, 'Dictionary Entry', '10166bba-4b6b-4fc4-8bd9-5be3c824231c', 'DataStore_Host', 'Hosts that are mapped to a datastore.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(417, 37, 'Dictionary Entry', 'c7e9248c-3a15-45c5-a250-e67b5792cc41', 'Internal_Volume_Annotation', 'Annotation value applied to Internal Volume in OnCommand Insight.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(418, 37, 'Dictionary Entry', '16c9d810-72fe-457b-94d2-bf9d38007598', 'Virtual_Machine_Application', 'Association object representing the association between a virtual machine and an application.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(419, 37, 'Dictionary Entry', '77a95064-8077-4502-b5de-4952e1cc6459', 'Storage_Pool_Performance', 'A set of counters for the Storage Pool. The counter value is the average of hourly sample data for the last 15 days.', '1.0.0', 'NETAPP', '', '', '2015-10-21 00:46:57'),
(428, 43, 'Scheme', '91bf8d58-74cf-4ff6-a8b3-0cead03b3f81', 'oracle', '', '1.0.0', 'NONE', '', '', '2015-10-23 00:10:33'),
(429, 43, 'DictionaryEntry', 'dda0ae06-6a3a-47ef-8415-eb3f6160fffb', 'student', '', '1.0.0', 'NONE', '', '', '2015-10-23 00:10:33'),
(430, 43, 'SqlDataProviderType', '5f51ef3d-67a6-44f6-8d67-79c49f78d348', 'Oracle JDBC_', '', '1.0.0', 'NONE', '', '', '2015-10-23 00:10:33'),
(431, 43, 'CacheQuery', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', '', '1.0.0', 'NETAPP', '', '', '2015-10-23 00:10:33'),
(452, 49, 'Scheme', '91bf8d58-74cf-4ff6-a8b3-0cead03b3f81', 'oracle', '', '1.0.0', 'NONE', '', '', '2015-10-23 02:11:22'),
(453, 49, 'DictionaryEntry', 'dda0ae06-6a3a-47ef-8415-eb3f6160fffb', 'student', '', '1.0.0', 'NONE', '', '', '2015-10-23 02:11:22'),
(454, 49, 'SqlDataProviderType', '5f51ef3d-67a6-44f6-8d67-79c49f78d348', 'Oracle JDBC_', '', '1.0.0', 'NONE', '', '', '2015-10-23 02:11:22'),
(455, 49, 'CacheQuery', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', '', '1.0.0', 'NETAPP', '', '', '2015-10-23 02:11:22'),
(483, 58, 'Scheme', '91bf8d58-74cf-4ff6-a8b3-0cead03b3f81', 'oracle', '', '1.0.0', 'NETAPP', '', '', '2015-10-26 00:18:42'),
(484, 58, 'DictionaryEntry', 'dda0ae06-6a3a-47ef-8415-eb3f6160fffb', 'student', '', '1.0.0', 'NETAPP', '', '', '2015-10-26 00:18:42'),
(485, 58, 'SqlDataProviderType', '5f51ef3d-67a6-44f6-8d67-79c49f78d348', 'Oracle JDBC_', '', '1.0.0', 'NETAPP', '', '', '2015-10-26 00:18:42'),
(486, 58, 'CacheQuery', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', 'd1c1e765-52bf-4cea-95ee-4d59e8a6812b', '', '1.0.0', 'NETAPP', '', '', '2015-10-26 00:18:42');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table to store user details during upload' AUTO_INCREMENT=58 ;

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
(24, 25, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-20 07:04:36', 'idmtestint01'),
(25, 26, 'siebel', 'test', 'siebeltest@netapp.com', '2015-10-20 22:35:59', 'idmtestint01'),
(26, 27, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-20 23:44:26', 'shivanig'),
(27, 28, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-21 00:01:03', 'shivanig'),
(28, 29, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 00:21:28', 'arunv2'),
(29, 30, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 00:23:02', 'arunv2'),
(30, 31, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-21 00:26:32', 'charshit'),
(31, 32, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-21 00:26:56', 'charshit'),
(32, 33, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-21 00:27:27', 'charshit'),
(33, 34, 'harshit', 'chawla', 'Harshit.Chawla@netapp.com', '2015-10-21 00:27:52', 'charshit'),
(34, 35, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 00:38:44', 'arunv2'),
(35, 36, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 00:43:10', 'arunv2'),
(36, 37, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 00:46:57', 'arunv2'),
(37, 38, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 05:36:56', 'arunv2'),
(38, 39, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-21 05:38:52', 'arunv2'),
(39, 40, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-22 22:31:55', 'idmstageuser314'),
(40, 41, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-22 23:10:16', 'idmstageuser314'),
(41, 42, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-22 23:17:34', 'idmstageuser314'),
(42, 43, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 00:10:33', 'arunv2'),
(43, 44, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-23 01:11:53', 'idmstageuser314'),
(44, 45, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-23 01:16:51', 'idmstageuser314'),
(45, 46, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 01:39:30', 'arunv2'),
(46, 47, 'Shivani', 'gupta', 'shivani.gupta@netapp.com', '2015-10-23 01:44:24', 'shivanig'),
(47, 48, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-23 02:07:06', 'idmstageuser314'),
(48, 49, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 02:11:22', 'arunv2'),
(49, 50, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 02:12:54', 'arunv2'),
(50, 51, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-23 03:24:50', 'idmstageuser314'),
(51, 52, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 03:52:04', 'arunv2'),
(52, 53, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-23 03:56:49', 'arunv2'),
(53, 54, 'shivani', 'gupta', 'Shivani.Gupta@netapp.com', '2015-10-24 10:14:55', 'shivanig'),
(54, 55, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-24 10:19:21', 'idmstageuser314'),
(55, 56, 'stageuser', 'test314', 'idmStageUser314@netapp.com', '2015-10-24 10:23:33', 'idmstageuser314'),
(56, 57, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-26 00:17:04', 'arunv2'),
(57, 58, 'arun', 'verma', 'Arun.Verma@netapp.com', '2015-10-26 00:18:42', 'arunv2');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`id`, `ratingValue`, `ratingPackVersion`, `ratingPackId`, `ratingPackType`, `ratingBy`, `ratingDate`) VALUES
(1, 5, '1.1.1', '3', 'Workflow', 'shivanig', '2015-10-16 23:27:49'),
(2, 5, '1.1.2', '9', 'Performance', 'idmtestint01', '2015-10-21 02:20:05'),
(3, 5, '1.0.0', '42', 'Workflow', 'idmstageuser314', '2015-10-22 23:20:13'),
(4, 5, '2.0.0', '45', 'Workflow', 'idmtestint01', '2015-10-24 09:19:51'),
(5, 3, '2.0.0', '45', 'Workflow', 'idmstageuser314', '2015-10-24 09:22:31'),
(6, 2, '2.0.0', '45', 'Workflow', 'idmstageuser300', '2015-10-24 09:24:32');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Details of packs rejected along with user and admin details' AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rejectedPacks`
--

INSERT INTO `rejectedPacks` (`id`, `packName`, `firstName`, `lastName`, `userEmail`, `adminComments`, `adminName`, `adminEmail`, `rejectDate`) VALUES
(1, 'Oracle cache query pack with new scheme', 'stageuser', 'test314', 'idmStageUser314@netapp.com', 'Not correct.', 'shivani gupta', 'Shivani.Gupta@netapp.com', '2015-10-22 23:10:51'),
(2, 'Oracle Cache Query Pack', 'stageuser', 'test314', 'idmStageUser314@netapp.com', 'dumy pack ', 'arun verma', 'Arun.Verma@netapp.com', '2015-10-23 00:00:22'),
(3, 'Oracle cache query pack with new scheme', 'stageuser', 'test314', 'idmStageUser314@netapp.com', 'test rejection comments. test rejection comments.test rejection comments.test rejection comments.test rejection comments.test rejection comments.test rejection comments.\n\ntest rejection comments.test rejection comments.test rejection comments.test rejection comments.test rejection comments.test rejection comments.', 'shivani gupta', 'Shivani.Gupta@netapp.com', '2015-10-23 01:12:42'),
(5, 'Oracle cache query pack with new scheme', 'arun', 'verma', 'Arun.Verma@netapp.com', 'dummy comment ', 'arun verma', 'Arun.Verma@netapp.com', '2015-10-23 01:40:08'),
(6, 'Oracle cache query pack with new scheme', 'Shivani', 'gupta', 'shivani.gupta@netapp.com', 'This is your pack Shivani. Please confirm the mail.', 'arun verma', 'Arun.Verma@netapp.com', '2015-10-23 01:50:09'),
(7, 'Oracle cache query pack', 'stageuser', 'test314', 'idmStageUser314@netapp.com', 'test comments.', 'shivani gupta', 'Shivani.Gupta@netapp.com', '2015-10-23 02:08:05'),
(8, 'Quota Management', 'arun', 'verma', 'Arun.Verma@netapp.com', 'kjdjewcv ew vhew vh', 'shivani gupta', 'Shivani.Gupta@netapp.com', '2015-10-23 02:13:30');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `username`, `email`, `phone`, `receiveNotification`, `receiveMail`, `lastLogin`) VALUES
(1, 'arun', 'verma', 'arunv2', 'Arun.Verma@netapp.com', '', 'true', 'true', '2015-10-23 00:50:54'),
(2, 'shivani', 'gupta', 'shivanig', 'Shivani.Gupta@netapp.com', '2', 'true', 'true', '2015-10-23 04:31:08'),
(3, 'ashutosh', 'garg', 'gashutos', 'Ashutosh.Garg@netapp.com', '', 'true', 'true', '2015-10-21 01:45:27'),
(4, 'siebel', 'test', 'idmtestint01', 'siebeltest@netapp.com', '', 'true', 'true', '2015-10-25 22:32:29'),
(5, 'harshit', 'chawla', 'charshit', 'Harshit.Chawla@netapp.com', '', 'true', 'true', '2015-10-15 00:16:19'),
(6, 'ashish', 'joshi', 'ashish8', 'Ashish.Joshi@netapp.com', '', 'false', 'false', '2015-10-20 07:10:39'),
(8, 'sharaf', 'ahmad', 'sharaf', 'Sharaf.Ahmad@netapp.com', '123456789123', 'true', 'true', '2015-10-16 03:00:28'),
(9, 'gaurav', 'kalia', 'gkalia', 'Gaurav.Kalia2@netapp.com', '9999999999', 'true', 'true', '2015-10-19 02:37:08'),
(10, 'sachin', 'balmane', 'bsachin', 'Sachin.BD@netapp.com', '', 'true', 'true', '2015-10-20 05:14:50'),
(11, 'abhi', 'thakur', 'abhit', 'Abhi.Thakur@netapp.com', '9845515269', 'true', 'true', '2015-10-20 07:20:00'),
(12, 'alex', 'marzec', 'marzec', 'marzec@netapp.com', '', 'true', 'false', '2015-09-23 07:10:14'),
(13, 'karuppusamy', 'komarasamy', 'karuppuk', 'Karuppusamy.Komarasamy@netapp.com', '', 'true', 'false', '2015-09-30 00:05:26'),
(14, 'prabu', 'arjunan', 'arjunan', 'Prabu.Arjunan@netapp.com', '', 'true', 'false', '2015-09-30 04:17:27'),
(15, 'stageuser', 'test300', 'idmstageuser300', 'idmStageUser300@netapp.com', '', 'true', 'true', '2015-10-24 09:33:30'),
(16, 'stageuser', 'test350', 'idmstageuser350', 'idmStageUser350@netapp.com', '', 'true', 'true', '2015-10-19 04:11:12'),
(17, 'stageuser', 'test314', 'idmstageuser314', 'idmStageUser314@netapp.com', '', 'true', 'true', '2015-10-24 09:22:42'),
(18, 'pavan', 'desai', 'pavand', 'Pavan.Desai@netapp.com', '', 'true', 'false', '2015-10-09 04:43:55'),
(19, 'ram', 'jaiswal', 'jaiswalr', 'Ram.Jaiswal@netapp.com', '', 'false', 'false', '2015-10-15 04:27:34'),
(21, '', '', '', '', '', 'true', 'true', '2015-10-26 00:10:12');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
