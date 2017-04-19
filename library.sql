-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-04-19 12:57:30
-- 服务器版本： 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE `admin` (
  `id` char(10) NOT NULL,
  `pwd` char(32) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `contact` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `pwd`, `name`, `contact`) VALUES
('root', '63a9f0ea7bb98050796b649e85481845', 'root', 'i@llonely.com');

-- --------------------------------------------------------

--
-- 表的结构 `book`
--

CREATE TABLE `book` (
  `bno` char(8) NOT NULL,
  `category` varchar(10) DEFAULT NULL,
  `title` varchar(40) DEFAULT NULL,
  `press` varchar(30) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `author` varchar(20) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 触发器 `book`
--
DELIMITER $$
CREATE TRIGGER `borrow_check` AFTER UPDATE ON `book` FOR EACH ROW BEGIN
    IF NEW.stock < 0
    THEN
      SIGNAL SQLSTATE '45001'
      SET MESSAGE_TEXT = 'Stock is empty!';
    END IF;
  END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- 表的结构 `borrow`
--

CREATE TABLE `borrow` (
  `cno` char(7) DEFAULT NULL,
  `bno` char(8) DEFAULT NULL,
  `admin_id` char(10) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `uuid` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `card`
--

CREATE TABLE `card` (
  `cno` char(7) NOT NULL,
  `name` varchar(10) DEFAULT NULL,
  `department` varchar(40) DEFAULT NULL,
  `type` enum('T','S') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`bno`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `cno` (`cno`),
  ADD KEY `bno` (`bno`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `card`
--
ALTER TABLE `card`
  ADD PRIMARY KEY (`cno`);

--
-- 限制导出的表
--

--
-- 限制表 `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`cno`) REFERENCES `card` (`cno`),
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`bno`) REFERENCES `book` (`bno`),
  ADD CONSTRAINT `borrow_ibfk_3` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
