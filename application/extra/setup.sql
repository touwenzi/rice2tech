-- phpMyAdmin SQL Dump
-- version 4.6.6deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: 2017-06-11 11:58:01
-- 服务器版本： 5.7.17-1
-- PHP Version: 7.0.16-3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `setup`
--

-- --------------------------------------------------------

--
-- 表的结构 `tp_article`
--

CREATE TABLE `tp_article` (
  `aid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(20) NOT NULL DEFAULT '佚名',
  `content` text NOT NULL,
  `date` int(10) NOT NULL,
  `columns` varchar(20) DEFAULT NULL,
  `super` varchar(20) DEFAULT NULL,
  `tags` varchar(20) DEFAULT NULL,
  `content_pic` text,
  `title_pic` varchar(255) NOT NULL,
  `brief` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tp_columns`
--

CREATE TABLE `tp_columns` (
  `cid` int(11) NOT NULL,
  `col_name` varchar(20) NOT NULL,
  `super_name` varchar(20) NOT NULL DEFAULT 'super',
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `tp_comment`
--

CREATE TABLE `tp_comment` (
  `cmid` int(11) NOT NULL,
  `aid` int(11) DEFAULT NULL,
  `from_uid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `content` text,
  `from_level` int(255) DEFAULT NULL,
  `level` int(11) DEFAULT '0',
  `date` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `tp_getcomment`
-- (See below for the actual view)
--
CREATE TABLE `tp_getcomment` (
`cmid` int(11)
,`aid` int(11)
,`from_uid` int(11)
,`uid` int(11)
,`content` text
,`level` int(11)
,`date` int(11)
,`nick_name` varchar(20)
,`from_nick_name` varchar(20)
,`icon` varchar(255)
,`from_level` int(255)
);

-- --------------------------------------------------------

--
-- 表的结构 `tp_setting`
--

CREATE TABLE `tp_setting` (
  `title` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tp_super`
--

CREATE TABLE `tp_super` (
  `sid` int(11) NOT NULL,
  `super_name` varchar(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tp_user`
--

CREATE TABLE `tp_user` (
  `Id` int(11) NOT NULL,
  `user_name` varchar(20) NOT NULL DEFAULT '',
  `pass` varchar(255) NOT NULL DEFAULT '',
  `mail` varchar(255) NOT NULL,
  `sex` varchar(2) DEFAULT '',
  `birth` varchar(20) DEFAULT NULL,
  `nick_name` varchar(20) DEFAULT NULL,
  `icon` varchar(255) DEFAULT '/static/profile/icon/default.jpg',
  `info` varchar(255) DEFAULT '该用户很懒，什么都没有留下~',
  `auth` int(10) NOT NULL DEFAULT '1' COMMENT '0:管理员 1:普通用户 2:作者 3:高级用户(审核)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- --------------------------------------------------------

--
-- 视图结构 `tp_getcomment`
--
DROP TABLE IF EXISTS `tp_getcomment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tp_getcomment`  AS  select `tp_comment`.`cmid` AS `cmid`,`tp_comment`.`aid` AS `aid`,`tp_comment`.`from_uid` AS `from_uid`,`tp_comment`.`uid` AS `uid`,`tp_comment`.`content` AS `content`,`tp_comment`.`level` AS `level`,`tp_comment`.`date` AS `date`,`tp_user`.`nick_name` AS `nick_name`,`tp_user_from`.`nick_name` AS `from_nick_name`,`tp_user`.`icon` AS `icon`,`tp_comment`.`from_level` AS `from_level` from ((`tp_comment` join `tp_user`) join `tp_user` `tp_user_from`) where ((`tp_comment`.`uid` = `tp_user`.`Id`) and (`tp_comment`.`from_uid` = `tp_user_from`.`Id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tp_article`
--
ALTER TABLE `tp_article`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `tp_columns`
--
ALTER TABLE `tp_columns`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tp_comment`
--
ALTER TABLE `tp_comment`
  ADD PRIMARY KEY (`cmid`);

--
-- Indexes for table `tp_super`
--
ALTER TABLE `tp_super`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `tp_user`
--
ALTER TABLE `tp_user`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `用户名唯一` (`user_name`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `tp_article`
--
ALTER TABLE `tp_article`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tp_columns`
--
ALTER TABLE `tp_columns`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tp_comment`
--
ALTER TABLE `tp_comment`
  MODIFY `cmid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tp_super`
--
ALTER TABLE `tp_super`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tp_user`
--
ALTER TABLE `tp_user`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `tp_user` (`Id`, `user_name`, `pass`, `mail`, `sex`, `birth`, `nick_name`, `icon`, `info`, `auth`) VALUES ('0', 'SYSTEM', 'asdfsdagqeterqwgq34523764t3uusssggzdfjrwy', 'admin@rice2tech.com', '男', NULL, NULL, '/static/profile/icon/default.jpg', '该用户很懒，什么都没有留下~', '-1');
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
