-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2019 at 3:18 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `spm_proj_2019`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `init_data` ()  BEGIN
    SET AUTOCOMMIT = 0;
    START TRANSACTION;
    SET FOREIGN_KEY_CHECKS=0;
    
    TRUNCATE TABLE `bid`;

    TRUNCATE TABLE `bidding_results`;

    TRUNCATE TABLE `course`;

    TRUNCATE TABLE `course_completed`;

    TRUNCATE TABLE `prerequisite`;

    TRUNCATE TABLE `section`;

    TRUNCATE TABLE `student`;
    
    SET FOREIGN_KEY_CHECKS=1;

    INSERT INTO `student` (`userid`, `password`, `name`, `school`, `edollar`) VALUES
    ('amy.ng.2009', 'qwerty128', 'Amy NG', 'SIS', 200),
    ('ben.ng.2009', 'qwerty129', 'Ben NG', 'SIS', 200),
    ('calvin.ng.2009', 'qwerty130', 'Calvin NG', 'SIS', 200),
    ('dawn.ng.2009', 'qwerty131', 'Dawn NG', 'SIS', 200),
    ('eddy.ng.2009', 'qwerty132', 'Eddy NG', 'SIS', 200),
    ('fred.ng.2009', 'qwerty133', 'Fred NG', 'SIS', 200),
    ('gary.ng.2009', 'qwerty134', 'Gary NG', 'SIS', 200),
    ('harry.ng.2009', 'qwerty135', 'Harry NG', 'SIS', 200),
    ('ian.ng.2009', 'qwerty136', 'Ian NG', 'SIS', 200),
    ('jerry.ng.2009', 'qwerty137', 'Jerry NG', 'SIS', 200),
    ('kelly.ng.2009', 'qwerty138', 'Kelly NG', 'SIS', 200),
    ('larry.ng.2009', 'qwerty139', 'Larry NG', 'SIS', 200),
    ('maggie.ng.2009', 'qwerty140', 'Maggie NG', 'SIS', 200),
    ('neilson.ng.2009', 'qwerty141', 'Neilson NG', 'SIS', 200),
    ('olivia.ng.2009', 'qwerty142', 'Olivia NG', 'SIS', 200),
    ('parker.ng.2009', 'qwerty143', 'Parker NG', 'SOE', 200),
    ('quiten.ng.2009', 'qwerty144', 'Quiten NG', 'SOE', 200),
    ('ricky.ng.2009', 'qwerty145', 'Ricky NG', 'SOE', 200),
    ('steven.ng.2009', 'qwerty146', 'Steven NG', 'SOE', 200),
    ('timothy.ng.2009', 'qwerty147', 'Timothy NG', 'SOE', 200),
    ('ursala.ng.2009', 'qwerty148', 'Ursala NG', 'SOE', 200),
    ('valarie.ng.2009', 'qwerty149', 'Valarie NG', 'SOB', 200),
    ('winston.ng.2009', 'qwerty150', 'Winston NG', 'SOB', 200),
    ('xavier.ng.2009', 'qwerty151', 'Xavier NG', 'SOB', 200),
    ('yasir.ng.2009', 'qwerty152', 'Yasir NG', 'SOB', 200),
    ('zac.ng.2009', 'qwerty153', 'Zac NG', 'SOB', 200);

    INSERT INTO `course` (`courseid`, `school`, `title`, `description`, `exam_date`, `exam_start`, `exam_end`) VALUES
    ('ECON001', 'SOE', 'Microeconomics', 'Microeconomics is about economics in smaller scale (e.g. firm-scale)', '2013-11-01', '15:30:00', '18:45:00'),
    ('ECON002', 'SOE', 'Macroeconomics', 'You don\'t learn about excel macros here.', '2013-11-01', '08:30:00', '11:45:00'),
    ('IS100', 'SIS', 'Calculus', 'The basic objective of Calculus is to relate small-scale (differential) quantities to large-scale (integrated) quantities. This is accomplished by means of the Fundamental Theorem of Calculus. Students should demonstrate an understanding of the integral as a cumulative sum, of the derivative as a rate of change, and of the inverse relationship between integration and differentiation.', '2013-11-19', '08:30:00', '11:45:00'),
    ('IS101', 'SIS', 'Advanced Calculus', 'This is a second course on calculus. It is more advanced definitely.', '2013-11-18', '12:00:00', '15:15:00'),
    ('IS102', 'SIS', 'Java programming', 'This course teaches you on Java programming. I love Java definitely.', '2013-11-17', '15:30:00', '18:45:00'),
    ('IS103', 'SIS', 'Web Programming', 'JSP, Servlets using Tomcat', '2013-11-16', '08:30:00', '11:45:00'),
    ('IS104', 'SIS', 'Advanced Programming', 'How to write code that nobody can understand', '2013-11-15', '12:00:00', '15:15:00'),
    ('IS105', 'SIS', 'Data Structures', 'Data structure is a particular way of storing and organizing data in a computer so that it can be used efficiently. Arrays, Lists, Stacks and Trees will be covered.', '2013-11-14', '15:30:00', '18:45:00'),
    ('IS106', 'SIS', 'Database Modeling & Design', 'Data modeling in software engineering is the process of creating a data model by applying formal data model descriptions using data modeling techniques.', '2013-11-13', '08:30:00', '11:45:00'),
    ('IS107', 'SIS', 'IT Outsourcing', 'This course teaches you on how to outsource your programming projects to others.', '2013-11-12', '12:00:00', '15:15:00'),
    ('IS108', 'SIS', 'Organization Behaviour', 'Organizational Behavior (OB) is the study and application of knowledge about how people, individuals, and groups act in organizations.', '2013-11-11', '15:30:00', '18:45:00'),
    ('IS109', 'SIS', 'Cloud Computing', 'Cloud computing is Internet-based computing, whereby shared resources, software and information are provided to computers and other devices on-demand, like the electricity grid.', '2013-11-10', '08:30:00', '11:45:00'),
    ('IS200', 'SIS', 'Final Touch', 'Learn how eat, dress and talk.', '2013-11-09', '12:00:00', '15:15:00'),
    ('IS201', 'SIS', 'Fun with Shell Programming', 'Shell scripts are a fundamental part of the UNIX and Linux programming environment.', '2013-11-08', '15:30:00', '18:45:00'),
    ('IS202', 'SIS', 'Enterprise integration', 'Enterprise integration is a technical field of Enterprise Architecture, which focused on the study of things like system interconnection, electronic data interchange, product data exchange and distributed computing environments, and it\'s possible other solutions.[1', '2013-11-07', '08:30:00', '11:45:00'),
    ('IS203', 'SIS', 'Software Engineering', 'The Sleepless Era.', '2013-11-06', '12:00:00', '15:15:00'),
    ('IS204', 'SIS', 'Database System Administration', 'Database administration is a complex, often thankless chore.', '2013-11-05', '15:30:00', '18:45:00'),
    ('IS205', 'SIS', 'All Talk, No Action', 'The easiest course of all. We will sit around and talk.', '2013-11-04', '08:30:00', '11:45:00'),
    ('IS206', 'SIS', 'Operation Research', 'Operations research, also known as operational research, is an interdisciplinary branch of applied mathematics and formal science that uses advanced analytical methods such as mathematical modeling, statistical analysis, and mathematical optimization to arrive at optimal or near-optimal solutions to complex decision-making problems.', '2013-11-03', '12:00:00', '15:15:00'),
    ('IS207', 'SIS', 'GUI Bloopers', 'Common User Interface Design Don\'ts and Dos', '2013-11-03', '15:30:00', '18:45:00'),
    ('IS208', 'SIS', 'Artifical Intelligence', 'The science and engineering of making intelligent machine', '2013-11-03', '08:30:00', '11:45:00'),
    ('IS209', 'SIS', 'Information Storage and Management', 'Information storage and management (ISM) - once a relatively straightforward operation -has developed into a highly sophisticated pillar of information technology, requiring proven technical expertise.', '2013-11-02', '12:00:00', '15:15:00'),
    ('MGMT001', 'SOB', 'Business,Government, and Society', 'learn the interrelation amongst the three', '2013-11-02', '08:30:00', '11:45:00'),
    ('MGMT002', 'SOB', 'Technology and World Change', 'As technology changes, so does the world', '2013-11-01', '12:00:00', '15:15:00');

    INSERT INTO `prerequisite` (`courseid`, `prerequisiteid`) VALUES
    ('IS101', 'IS100'),
    ('IS103', 'IS102'),
    ('IS109', 'IS102'),
    ('IS104', 'IS103'),
    ('IS203', 'IS103'),
    ('IS203', 'IS106'),
    ('IS204', 'IS106'),
    ('IS209', 'IS106');

    INSERT INTO `section` (`courseid`, `section`, `day`, `start`, `end`, `instructor`, `venue`, `size`) VALUES
    ('ECON001', 'S1', '4', '08:30:00', '11:45:00', 'John KHOO', 'Seminar Rm 2-34', 10),
    ('ECON002', 'S1', '5', '15:30:00', '18:45:00', 'Andy KHOO', 'Seminar Rm 2-35', 10),
    ('IS100', 'S1', '1', '08:30:00', '11:45:00', 'Albert KHOO', 'Seminar Rm 2-1', 10),
    ('IS100', 'S2', '2', '12:00:00', '15:15:00', 'Billy KHOO', 'Seminar Rm 2-2', 10),
    ('IS101', 'S1', '3', '15:30:00', '18:45:00', 'Cheri KHOO', 'Seminar Rm 2-3', 10),
    ('IS101', 'S2', '4', '08:30:00', '11:45:00', 'Daniel KHOO', 'Seminar Rm 2-4', 10),
    ('IS101', 'S3', '5', '12:00:00', '15:15:00', 'Ernest KHOO', 'Seminar Rm 2-5', 10),
    ('IS102', 'S1', '1', '15:30:00', '18:45:00', 'Felicia KHOO', 'Seminar Rm 2-6', 10),
    ('IS102', 'S2', '2', '08:30:00', '11:45:00', 'Gerald KHOO', 'Seminar Rm 2-7', 10),
    ('IS102', 'S3', '3', '12:00:00', '15:15:00', 'Henry KHOO', 'Seminar Rm 2-8', 10),
    ('IS103', 'S1', '4', '15:30:00', '18:45:00', 'Ivy KHOO', 'Seminar Rm 2-9', 10),
    ('IS103', 'S2', '5', '08:30:00', '11:45:00', 'Jason KHOO', 'Seminar Rm 2-10', 10),
    ('IS103', 'S3', '1', '12:00:00', '15:15:00', 'Kat KHOO', 'Seminar Rm 2-11', 10),
    ('IS104', 'S1', '2', '15:30:00', '18:45:00', 'Linn KHOO', 'Seminar Rm 2-12', 10),
    ('IS104', 'S2', '3', '08:30:00', '11:45:00', 'Michael KHOO', 'Seminar Rm 2-13', 10),
    ('IS105', 'S1', '4', '12:00:00', '15:15:00', 'Nathaniel KHOO', 'Seminar Rm 2-14', 10),
    ('IS105', 'S2', '5', '15:30:00', '18:45:00', 'Oreilly KHOO', 'Seminar Rm 2-15', 10),
    ('IS106', 'S1', '1', '08:30:00', '11:45:00', 'Peter KHOO', 'Seminar Rm 2-16', 10),
    ('IS106', 'S2', '2', '12:00:00', '15:15:00', 'Queen KHOO', 'Seminar Rm 2-17', 10),
    ('IS107', 'S1', '3', '15:30:00', '18:45:00', 'Ray KHOO', 'Seminar Rm 2-18', 10),
    ('IS107', 'S2', '4', '08:30:00', '11:45:00', 'Simon KHOO', 'Seminar Rm 2-19', 10),
    ('IS108', 'S1', '5', '12:00:00', '15:15:00', 'Tim KHOO', 'Seminar Rm 2-20', 10),
    ('IS109', 'S1', '2', '08:30:00', '11:45:00', 'Vincent KHOO', 'Seminar Rm 2-22', 10),
    ('IS109', 'S2', '3', '12:00:00', '15:15:00', 'Winnie KHOO', 'Seminar Rm 2-23', 10),
    ('IS200', 'S1', '4', '15:30:00', '18:45:00', 'Xtra KHOO', 'Seminar Rm 2-24', 10),
    ('IS201', 'S1', '5', '08:30:00', '11:45:00', 'Yale KHOO', 'Seminar Rm 2-25', 10),
    ('IS202', 'S1', '1', '12:00:00', '15:15:00', 'Zen KHOO', 'Seminar Rm 2-26', 10),
    ('IS203', 'S1', '2', '15:30:00', '18:45:00', 'Anderson KHOO', 'Seminar Rm 2-27', 10),
    ('IS204', 'S1', '3', '08:30:00', '11:45:00', 'Bing KHOO', 'Seminar Rm 2-28', 10),
    ('IS205', 'S1', '4', '12:00:00', '15:15:00', 'Carlo KHOO', 'Seminar Rm 2-29', 10),
    ('IS206', 'S1', '5', '15:30:00', '18:45:00', 'Dickson KHOO', 'Seminar Rm 2-30', 10),
    ('IS207', 'S1', '1', '08:30:00', '11:45:00', 'Edmund KHOO', 'Seminar Rm 2-31', 10),
    ('IS208', 'S1', '2', '12:00:00', '15:15:00', 'Febrice KHOO', 'Seminar Rm 2-32', 10),
    ('MGMT001', 'S1', '3', '08:30:00', '11:45:00', 'Gavin KHOO', 'Seminar Rm 2-33', 10),
    ('MGMT002', 'S1', '3', '15:30:00', '18:45:00', 'Bob KHOO', 'Seminar Rm 2-37', 10);

    INSERT INTO `course_completed` (`userid`, `courseid`) VALUES
    ('amy.ng.2009', 'IS100'),
    ('gary.ng.2009', 'IS100'),
    ('ben.ng.2009', 'IS102'),
    ('ben.ng.2009', 'IS103');

    INSERT INTO `bid` (`userid`, `amount`, `courseid`, `section`) VALUES
    ('ben.ng.2009', 11, 'IS100', 'S1'),
    ('calvin.ng.2009', 12, 'IS100', 'S1'),
    ('dawn.ng.2009', 13, 'IS100', 'S1'),
    ('eddy.ng.2009', 14, 'IS100', 'S1'),
    ('fred.ng.2009', 15, 'IS100', 'S1'),
    ('gary.ng.2009', 16, 'IS100', 'S1'),
    ('harry.ng.2009', 17, 'IS100', 'S1'),
    ('ian.ng.2009', 18, 'IS100', 'S1'),
    ('larry.ng.2009', 19, 'IS100', 'S1'),
    ('maggie.ng.2009', 20, 'IS100', 'S1'),
    ('neilson.ng.2009', 21, 'IS100', 'S1'),
    ('olivia.ng.2009', 22, 'IS100', 'S1'),
    ('parker.ng.2009', 24, 'IS100', 'S1'),
    ('quiten.ng.2009', 24, 'IS100', 'S1'),
    ('ricky.ng.2009', 24, 'IS100', 'S1'),
    ('steven.ng.2009', 26, 'IS100', 'S1'),
    ('timothy.ng.2009', 27, 'IS100', 'S1'),
    ('ursala.ng.2009', 28, 'IS100', 'S1'),
    ('valarie.ng.2009', 29, 'IS100', 'S1'),
    ('winston.ng.2009', 30, 'IS100', 'S1'),
    ('xavier.ng.2009', 31, 'IS100', 'S1'),
    ('yasir.ng.2009', 32, 'IS100', 'S1'),
    ('zac.ng.2009', 33, 'IS100', 'S1');
    
    COMMIT;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bid`
--

CREATE TABLE `bid` (
  `userid` varchar(128) NOT NULL,
  `amount` int(11) NOT NULL,
  `courseid` varchar(10) NOT NULL,
  `section` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bid`
--

INSERT INTO `bid` (`userid`, `amount`, `courseid`, `section`) VALUES
('ben.ng.2009', 11, 'IS100', 'S1'),
('calvin.ng.2009', 12, 'IS100', 'S1'),
('dawn.ng.2009', 13, 'IS100', 'S1'),
('eddy.ng.2009', 14, 'IS100', 'S1'),
('fred.ng.2009', 15, 'IS100', 'S1'),
('gary.ng.2009', 16, 'IS100', 'S1'),
('harry.ng.2009', 17, 'IS100', 'S1'),
('ian.ng.2009', 18, 'IS100', 'S1'),
('larry.ng.2009', 19, 'IS100', 'S1'),
('maggie.ng.2009', 20, 'IS100', 'S1'),
('neilson.ng.2009', 21, 'IS100', 'S1'),
('olivia.ng.2009', 22, 'IS100', 'S1'),
('parker.ng.2009', 24, 'IS100', 'S1'),
('quiten.ng.2009', 24, 'IS100', 'S1'),
('ricky.ng.2009', 24, 'IS100', 'S1'),
('steven.ng.2009', 26, 'IS100', 'S1'),
('timothy.ng.2009', 27, 'IS100', 'S1'),
('ursala.ng.2009', 28, 'IS100', 'S1'),
('valarie.ng.2009', 29, 'IS100', 'S1'),
('winston.ng.2009', 30, 'IS100', 'S1'),
('xavier.ng.2009', 31, 'IS100', 'S1'),
('yasir.ng.2009', 32, 'IS100', 'S1'),
('zac.ng.2009', 33, 'IS100', 'S1');

-- --------------------------------------------------------

--
-- Table structure for table `bidding_results`
--

CREATE TABLE `bidding_results` (
  `userid` varchar(128) NOT NULL,
  `courseid` varchar(10) NOT NULL,
  `section` varchar(10) NOT NULL,
  `round` int(11) NOT NULL,
  `dtvalue` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseid` varchar(10) NOT NULL,
  `school` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_start` time NOT NULL,
  `exam_end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseid`, `school`, `title`, `description`, `exam_date`, `exam_start`, `exam_end`) VALUES
('ECON001', 'SOE', 'Microeconomics', 'Microeconomics is about economics in smaller scale (e.g. firm-scale)', '2013-11-01', '15:30:00', '18:45:00'),
('ECON002', 'SOE', 'Macroeconomics', 'You don\'t learn about excel macros here.', '2013-11-01', '08:30:00', '11:45:00'),
('IS100', 'SIS', 'Calculus', 'The basic objective of Calculus is to relate small-scale (differential) quantities to large-scale (integrated) quantities. This is accomplished by means of the Fundamental Theorem of Calculus. Students should demonstrate an understanding of the integral as a cumulative sum, of the derivative as a rate of change, and of the inverse relationship between integration and differentiation.', '2013-11-19', '08:30:00', '11:45:00'),
('IS101', 'SIS', 'Advanced Calculus', 'This is a second course on calculus. It is more advanced definitely.', '2013-11-18', '12:00:00', '15:15:00'),
('IS102', 'SIS', 'Java programming', 'This course teaches you on Java programming. I love Java definitely.', '2013-11-17', '15:30:00', '18:45:00'),
('IS103', 'SIS', 'Web Programming', 'JSP, Servlets using Tomcat', '2013-11-16', '08:30:00', '11:45:00'),
('IS104', 'SIS', 'Advanced Programming', 'How to write code that nobody can understand', '2013-11-15', '12:00:00', '15:15:00'),
('IS105', 'SIS', 'Data Structures', 'Data structure is a particular way of storing and organizing data in a computer so that it can be used efficiently. Arrays, Lists, Stacks and Trees will be covered.', '2013-11-14', '15:30:00', '18:45:00'),
('IS106', 'SIS', 'Database Modeling & Design', 'Data modeling in software engineering is the process of creating a data model by applying formal data model descriptions using data modeling techniques.', '2013-11-13', '08:30:00', '11:45:00'),
('IS107', 'SIS', 'IT Outsourcing', 'This course teaches you on how to outsource your programming projects to others.', '2013-11-12', '12:00:00', '15:15:00'),
('IS108', 'SIS', 'Organization Behaviour', 'Organizational Behavior (OB) is the study and application of knowledge about how people, individuals, and groups act in organizations.', '2013-11-11', '15:30:00', '18:45:00'),
('IS109', 'SIS', 'Cloud Computing', 'Cloud computing is Internet-based computing, whereby shared resources, software and information are provided to computers and other devices on-demand, like the electricity grid.', '2013-11-10', '08:30:00', '11:45:00'),
('IS200', 'SIS', 'Final Touch', 'Learn how eat, dress and talk.', '2013-11-09', '12:00:00', '15:15:00'),
('IS201', 'SIS', 'Fun with Shell Programming', 'Shell scripts are a fundamental part of the UNIX and Linux programming environment.', '2013-11-08', '15:30:00', '18:45:00'),
('IS202', 'SIS', 'Enterprise integration', 'Enterprise integration is a technical field of Enterprise Architecture, which focused on the study of things like system interconnection, electronic data interchange, product data exchange and distributed computing environments, and it\'s possible other solutions.[1', '2013-11-07', '08:30:00', '11:45:00'),
('IS203', 'SIS', 'Software Engineering', 'The Sleepless Era.', '2013-11-06', '12:00:00', '15:15:00'),
('IS204', 'SIS', 'Database System Administration', 'Database administration is a complex, often thankless chore.', '2013-11-05', '15:30:00', '18:45:00'),
('IS205', 'SIS', 'All Talk, No Action', 'The easiest course of all. We will sit around and talk.', '2013-11-04', '08:30:00', '11:45:00'),
('IS206', 'SIS', 'Operation Research', 'Operations research, also known as operational research, is an interdisciplinary branch of applied mathematics and formal science that uses advanced analytical methods such as mathematical modeling, statistical analysis, and mathematical optimization to arrive at optimal or near-optimal solutions to complex decision-making problems.', '2013-11-03', '12:00:00', '15:15:00'),
('IS207', 'SIS', 'GUI Bloopers', 'Common User Interface Design Don\'ts and Dos', '2013-11-03', '15:30:00', '18:45:00'),
('IS208', 'SIS', 'Artifical Intelligence', 'The science and engineering of making intelligent machine', '2013-11-03', '08:30:00', '11:45:00'),
('IS209', 'SIS', 'Information Storage and Management', 'Information storage and management (ISM) - once a relatively straightforward operation -has developed into a highly sophisticated pillar of information technology, requiring proven technical expertise.', '2013-11-02', '12:00:00', '15:15:00'),
('MGMT001', 'SOB', 'Business,Government, and Society', 'learn the interrelation amongst the three', '2013-11-02', '08:30:00', '11:45:00'),
('MGMT002', 'SOB', 'Technology and World Change', 'As technology changes, so does the world', '2013-11-01', '12:00:00', '15:15:00');

-- --------------------------------------------------------

--
-- Table structure for table `course_completed`
--

CREATE TABLE `course_completed` (
  `userid` varchar(128) NOT NULL,
  `courseid` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course_completed`
--

INSERT INTO `course_completed` (`userid`, `courseid`) VALUES
('amy.ng.2009', 'IS100'),
('gary.ng.2009', 'IS100'),
('ben.ng.2009', 'IS102'),
('ben.ng.2009', 'IS103');

-- --------------------------------------------------------

--
-- Table structure for table `prerequisite`
--

CREATE TABLE `prerequisite` (
  `courseid` varchar(10) NOT NULL,
  `prerequisiteid` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prerequisite`
--

INSERT INTO `prerequisite` (`courseid`, `prerequisiteid`) VALUES
('IS101', 'IS100'),
('IS103', 'IS102'),
('IS109', 'IS102'),
('IS104', 'IS103'),
('IS203', 'IS103'),
('IS203', 'IS106'),
('IS204', 'IS106'),
('IS209', 'IS106');

-- --------------------------------------------------------

--
-- Table structure for table `round`
--

CREATE TABLE `round` (
  `rowid` int(11) NOT NULL,
  `roundid` int(11) NOT NULL,
  `statusid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `round`
--

INSERT INTO `round` (`rowid`, `roundid`, `statusid`) VALUES
(1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `courseid` varchar(10) NOT NULL,
  `section` varchar(10) NOT NULL,
  `day` varchar(10) NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `instructor` varchar(100) NOT NULL,
  `venue` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `minbid` int(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`courseid`, `section`, `day`, `start`, `end`, `instructor`, `venue`, `size`) VALUES
('ECON001', 'S1', '4', '08:30:00', '11:45:00', 'John KHOO', 'Seminar Rm 2-34', 10),
('ECON002', 'S1', '5', '15:30:00', '18:45:00', 'Andy KHOO', 'Seminar Rm 2-35', 10),
('IS100', 'S1', '1', '08:30:00', '11:45:00', 'Albert KHOO', 'Seminar Rm 2-1', 10),
('IS100', 'S2', '2', '12:00:00', '15:15:00', 'Billy KHOO', 'Seminar Rm 2-2', 10),
('IS101', 'S1', '3', '15:30:00', '18:45:00', 'Cheri KHOO', 'Seminar Rm 2-3', 10),
('IS101', 'S2', '4', '08:30:00', '11:45:00', 'Daniel KHOO', 'Seminar Rm 2-4', 10),
('IS101', 'S3', '5', '12:00:00', '15:15:00', 'Ernest KHOO', 'Seminar Rm 2-5', 10),
('IS102', 'S1', '1', '15:30:00', '18:45:00', 'Felicia KHOO', 'Seminar Rm 2-6', 10),
('IS102', 'S2', '2', '08:30:00', '11:45:00', 'Gerald KHOO', 'Seminar Rm 2-7', 10),
('IS102', 'S3', '3', '12:00:00', '15:15:00', 'Henry KHOO', 'Seminar Rm 2-8', 10),
('IS103', 'S1', '4', '15:30:00', '18:45:00', 'Ivy KHOO', 'Seminar Rm 2-9', 10),
('IS103', 'S2', '5', '08:30:00', '11:45:00', 'Jason KHOO', 'Seminar Rm 2-10', 10),
('IS103', 'S3', '1', '12:00:00', '15:15:00', 'Kat KHOO', 'Seminar Rm 2-11', 10),
('IS104', 'S1', '2', '15:30:00', '18:45:00', 'Linn KHOO', 'Seminar Rm 2-12', 10),
('IS104', 'S2', '3', '08:30:00', '11:45:00', 'Michael KHOO', 'Seminar Rm 2-13', 10),
('IS105', 'S1', '4', '12:00:00', '15:15:00', 'Nathaniel KHOO', 'Seminar Rm 2-14', 10),
('IS105', 'S2', '5', '15:30:00', '18:45:00', 'Oreilly KHOO', 'Seminar Rm 2-15', 10),
('IS106', 'S1', '1', '08:30:00', '11:45:00', 'Peter KHOO', 'Seminar Rm 2-16', 10),
('IS106', 'S2', '2', '12:00:00', '15:15:00', 'Queen KHOO', 'Seminar Rm 2-17', 10),
('IS107', 'S1', '3', '15:30:00', '18:45:00', 'Ray KHOO', 'Seminar Rm 2-18', 10),
('IS107', 'S2', '4', '08:30:00', '11:45:00', 'Simon KHOO', 'Seminar Rm 2-19', 10),
('IS108', 'S1', '5', '12:00:00', '15:15:00', 'Tim KHOO', 'Seminar Rm 2-20', 10),
('IS109', 'S1', '2', '08:30:00', '11:45:00', 'Vincent KHOO', 'Seminar Rm 2-22', 10),
('IS109', 'S2', '3', '12:00:00', '15:15:00', 'Winnie KHOO', 'Seminar Rm 2-23', 10),
('IS200', 'S1', '4', '15:30:00', '18:45:00', 'Xtra KHOO', 'Seminar Rm 2-24', 10),
('IS201', 'S1', '5', '08:30:00', '11:45:00', 'Yale KHOO', 'Seminar Rm 2-25', 10),
('IS202', 'S1', '1', '12:00:00', '15:15:00', 'Zen KHOO', 'Seminar Rm 2-26', 10),
('IS203', 'S1', '2', '15:30:00', '18:45:00', 'Anderson KHOO', 'Seminar Rm 2-27', 10),
('IS204', 'S1', '3', '08:30:00', '11:45:00', 'Bing KHOO', 'Seminar Rm 2-28', 10),
('IS205', 'S1', '4', '12:00:00', '15:15:00', 'Carlo KHOO', 'Seminar Rm 2-29', 10),
('IS206', 'S1', '5', '15:30:00', '18:45:00', 'Dickson KHOO', 'Seminar Rm 2-30', 10),
('IS207', 'S1', '1', '08:30:00', '11:45:00', 'Edmund KHOO', 'Seminar Rm 2-31', 10),
('IS208', 'S1', '2', '12:00:00', '15:15:00', 'Febrice KHOO', 'Seminar Rm 2-32', 10),
('MGMT001', 'S1', '3', '08:30:00', '11:45:00', 'Gavin KHOO', 'Seminar Rm 2-33', 10),
('MGMT002', 'S1', '3', '15:30:00', '18:45:00', 'Bob KHOO', 'Seminar Rm 2-37', 10);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `userid` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `name` varchar(100) NOT NULL,
  `school` varchar(5) NOT NULL,
  `edollar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`userid`, `password`, `name`, `school`, `edollar`) VALUES
('amy.ng.2009', 'qwerty128', 'Amy NG', 'SIS', 200),
('ben.ng.2009', 'qwerty129', 'Ben NG', 'SIS', 200),
('calvin.ng.2009', 'qwerty130', 'Calvin NG', 'SIS', 200),
('dawn.ng.2009', 'qwerty131', 'Dawn NG', 'SIS', 200),
('eddy.ng.2009', 'qwerty132', 'Eddy NG', 'SIS', 200),
('fred.ng.2009', 'qwerty133', 'Fred NG', 'SIS', 200),
('gary.ng.2009', 'qwerty134', 'Gary NG', 'SIS', 200),
('harry.ng.2009', 'qwerty135', 'Harry NG', 'SIS', 200),
('ian.ng.2009', 'qwerty136', 'Ian NG', 'SIS', 200),
('jerry.ng.2009', 'qwerty137', 'Jerry NG', 'SIS', 200),
('kelly.ng.2009', 'qwerty138', 'Kelly NG', 'SIS', 200),
('larry.ng.2009', 'qwerty139', 'Larry NG', 'SIS', 200),
('maggie.ng.2009', 'qwerty140', 'Maggie NG', 'SIS', 200),
('neilson.ng.2009', 'qwerty141', 'Neilson NG', 'SIS', 200),
('olivia.ng.2009', 'qwerty142', 'Olivia NG', 'SIS', 200),
('parker.ng.2009', 'qwerty143', 'Parker NG', 'SOE', 200),
('quiten.ng.2009', 'qwerty144', 'Quiten NG', 'SOE', 200),
('ricky.ng.2009', 'qwerty145', 'Ricky NG', 'SOE', 200),
('steven.ng.2009', 'qwerty146', 'Steven NG', 'SOE', 200),
('timothy.ng.2009', 'qwerty147', 'Timothy NG', 'SOE', 200),
('ursala.ng.2009', 'qwerty148', 'Ursala NG', 'SOE', 200),
('valarie.ng.2009', 'qwerty149', 'Valarie NG', 'SOB', 200),
('winston.ng.2009', 'qwerty150', 'Winston NG', 'SOB', 200),
('xavier.ng.2009', 'qwerty151', 'Xavier NG', 'SOB', 200),
('yasir.ng.2009', 'qwerty152', 'Yasir NG', 'SOB', 200),
('zac.ng.2009', 'qwerty153', 'Zac NG', 'SOB', 200);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`userid`,`courseid`),
  ADD KEY `bid_fk1` (`courseid`,`section`);

--
-- Indexes for table `bidding_results`
--
ALTER TABLE `bidding_results`
  ADD PRIMARY KEY (`userid`,`courseid`,`section`,`round`,`dtvalue`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseid`);

--
-- Indexes for table `course_completed`
--
ALTER TABLE `course_completed`
  ADD PRIMARY KEY (`userid`,`courseid`),
  ADD KEY `course_complete_fk1` (`courseid`);

--
-- Indexes for table `prerequisite`
--
ALTER TABLE `prerequisite`
  ADD PRIMARY KEY (`courseid`,`prerequisiteid`),
  ADD KEY `prerequisite_fk1` (`prerequisiteid`);

--
-- Indexes for table `round`
--
ALTER TABLE `round`
  ADD PRIMARY KEY (`rowid`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`courseid`,`section`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`userid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `bid_fk1` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  ADD CONSTRAINT `bid_fk2` FOREIGN KEY (`courseid`,`section`) REFERENCES `section` (`courseid`, `section`);

--
-- Constraints for table `course_completed`
--
ALTER TABLE `course_completed`
  ADD CONSTRAINT `course_complete_fk1` FOREIGN KEY (`userid`) REFERENCES `student` (`userid`),
  ADD CONSTRAINT `course_complete_fk2` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`);

--
-- Constraints for table `prerequisite`
--
ALTER TABLE `prerequisite`
  ADD CONSTRAINT `prerequisite_fk1` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`),
  ADD CONSTRAINT `prerequisite_fk2` FOREIGN KEY (`prerequisiteid`) REFERENCES `course` (`courseid`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `section_fk` FOREIGN KEY (`courseid`) REFERENCES `course` (`courseid`);
