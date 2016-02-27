-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 27, 2016 at 09:18 AM
-- Server version: 5.5.44-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dcp`
--
CREATE DATABASE IF NOT EXISTS `dcp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dcp`;

-- --------------------------------------------------------

--
-- Table structure for table `addrequest`
--

CREATE TABLE IF NOT EXISTS `addrequest` (
  `name` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `port` varchar(10) NOT NULL,
  `owner` varchar(500) NOT NULL,
  `time` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `remark` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `usr` varchar(63) NOT NULL,
  `passw` varchar(63) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bakar_chat`
--

CREATE TABLE IF NOT EXISTS `bakar_chat` (
  `chatid` int(11) NOT NULL,
  `cid1` int(11) NOT NULL COMMENT '1st client id',
  `cid2` int(11) NOT NULL COMMENT '2nd client id',
  `stime` varchar(200) NOT NULL COMMENT 'start time of chat',
  `etime` varchar(200) NOT NULL COMMENT 'end time of chat',
  `randid` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bakar_clients`
--

CREATE TABLE IF NOT EXISTS `bakar_clients` (
  `cid` int(11) NOT NULL COMMENT 'client id generated for session',
  `randid` varchar(500) NOT NULL COMMENT 'random id sent',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL COMMENT 'status of client',
  `remark` varchar(100) NOT NULL COMMENT 'ip of client'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bakar_msg`
--

CREATE TABLE IF NOT EXISTS `bakar_msg` (
  `msgid` int(11) NOT NULL,
  `chatid` int(11) NOT NULL,
  `sentby` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL COMMENT '1=del. 0=pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dcrequests`
--

CREATE TABLE IF NOT EXISTS `dcrequests` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `name` varchar(300) NOT NULL,
  `timeofreq` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) NOT NULL,
  `timesolv` timestamp NULL DEFAULT NULL,
  `remark` varchar(100) NOT NULL,
  `fulfilledby` varchar(100) DEFAULT NULL,
  `link` varchar(400) DEFAULT NULL,
  `ip` varchar(50) NOT NULL,
  `torrent_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hubs_archive`
--

CREATE TABLE IF NOT EXISTS `hubs_archive` (
  `ip` varchar(100) NOT NULL,
  `port` varchar(100) NOT NULL,
  `when` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hubs_info`
--

CREATE TABLE IF NOT EXISTS `hubs_info` (
  `ip` varchar(100) NOT NULL,
  `port` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `last_online` varchar(100) NOT NULL,
  `olcount` int(11) NOT NULL DEFAULT '0',
  `totcount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `in_hub`
--

CREATE TABLE IF NOT EXISTS `in_hub` (
  `user` varchar(64) NOT NULL,
  `hub` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE IF NOT EXISTS `item_category` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `last_update`
--

CREATE TABLE IF NOT EXISTS `last_update` (
  `time` varchar(100) NOT NULL,
  `totcount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `last_update_proxy`
--

CREATE TABLE IF NOT EXISTS `last_update_proxy` (
  `time` varchar(100) NOT NULL,
  `totcount` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proxy`
--

CREATE TABLE IF NOT EXISTS `proxy` (
  `ip` varchar(63) NOT NULL,
  `port` int(11) NOT NULL DEFAULT '3128',
  `status` varchar(31) NOT NULL DEFAULT 'Down',
  `olcount` bigint(20) NOT NULL DEFAULT '0',
  `totcount` bigint(20) NOT NULL DEFAULT '0',
  `speed` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `proxy_history`
--

CREATE TABLE IF NOT EXISTS `proxy_history` (
  `ip` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `speed` varchar(50) NOT NULL COMMENT 'kbps'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suggestions`
--

CREATE TABLE IF NOT EXISTS `suggestions` (
  `user` varchar(63) NOT NULL,
  `sug` varchar(255) NOT NULL,
  `ip` varchar(35) NOT NULL,
  `sl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`usr`);

--
-- Indexes for table `bakar_chat`
--
ALTER TABLE `bakar_chat`
  ADD PRIMARY KEY (`chatid`),
  ADD UNIQUE KEY `randid` (`randid`),
  ADD KEY `cid1` (`cid1`),
  ADD KEY `cid2` (`cid2`);

--
-- Indexes for table `bakar_clients`
--
ALTER TABLE `bakar_clients`
  ADD PRIMARY KEY (`cid`),
  ADD UNIQUE KEY `randid` (`randid`);

--
-- Indexes for table `bakar_msg`
--
ALTER TABLE `bakar_msg`
  ADD PRIMARY KEY (`msgid`,`chatid`),
  ADD KEY `chatid` (`chatid`),
  ADD KEY `sentby` (`sentby`);

--
-- Indexes for table `dcrequests`
--
ALTER TABLE `dcrequests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hubs_info`
--
ALTER TABLE `hubs_info`
  ADD PRIMARY KEY (`ip`,`port`);

--
-- Indexes for table `in_hub`
--
ALTER TABLE `in_hub`
  ADD PRIMARY KEY (`user`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proxy`
--
ALTER TABLE `proxy`
  ADD PRIMARY KEY (`ip`,`port`);

--
-- Indexes for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD PRIMARY KEY (`sl`),
  ADD KEY `user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bakar_chat`
--
ALTER TABLE `bakar_chat`
  MODIFY `chatid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bakar_clients`
--
ALTER TABLE `bakar_clients`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'client id generated for session';
--
-- AUTO_INCREMENT for table `bakar_msg`
--
ALTER TABLE `bakar_msg`
  MODIFY `msgid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dcrequests`
--
ALTER TABLE `dcrequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `suggestions`
--
ALTER TABLE `suggestions`
  MODIFY `sl` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bakar_chat`
--
ALTER TABLE `bakar_chat`
  ADD CONSTRAINT `bakar_chat_ibfk_1` FOREIGN KEY (`cid1`) REFERENCES `bakar_clients` (`cid`),
  ADD CONSTRAINT `bakar_chat_ibfk_2` FOREIGN KEY (`cid2`) REFERENCES `bakar_clients` (`cid`);

--
-- Constraints for table `bakar_msg`
--
ALTER TABLE `bakar_msg`
  ADD CONSTRAINT `bakar_msg_ibfk_1` FOREIGN KEY (`chatid`) REFERENCES `bakar_chat` (`chatid`),
  ADD CONSTRAINT `bakar_msg_ibfk_2` FOREIGN KEY (`sentby`) REFERENCES `bakar_clients` (`cid`);

--
-- Constraints for table `in_hub`
--
ALTER TABLE `in_hub`
  ADD CONSTRAINT `in_hub_ibfk_1` FOREIGN KEY (`user`) REFERENCES `admin` (`usr`);

--
-- Constraints for table `suggestions`
--
ALTER TABLE `suggestions`
  ADD CONSTRAINT `suggestions_ibfk_1` FOREIGN KEY (`user`) REFERENCES `admin` (`usr`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
