-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2017 at 05:29 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `movies`
--

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE IF NOT EXISTS `movie` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `image_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` (`id`, `name`, `year`, `description`, `image_path`) VALUES
(1, 'Intouchables', 2011, 'After he becomes a quadriplegic from a paragliding accident, an aristocrat hires a young man from the projects to be his caregiver.', '1507281084intouchables.jpg'),
(2, 'The Pianist', 2002, 'A Polish Jewish musician struggles to survive the destruction of the Warsaw ghetto of World War II.', NULL),
(3, 'Interstellar', 2015, 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity''s survival.', '1507240863interstellar.jpg'),
(4, 'The Matrix', 1999, 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.', NULL),
(5, 'Inception', 2010, 'A thief, who steals corporate secrets through use of dream-sharing technology, is given the inverse task of planting an idea into the mind of a CEO.', NULL),
(15, 'The Dark Knight', 2008, 'When the menace known as the Joker emerges from his mysterious past, he wreaks havoc and chaos on the people of Gotham, the Dark Knight must accept one of the greatest psychological and physical tests of his ability to fight injustice.', NULL),
(16, 'We''re the Millers', 2013, 'A veteran pot dealer creates a fake family as part of his plan to move a huge shipment of weed into the U.S. from Mexico.', '1507229673millers.jpg'),
(17, 'Into the Wild', 2007, 'After graduating from Emory University, top student and athlete Christopher McCandless abandons his possessions, gives his entire $24,000 savings account to charity and hitchhikes to Alaska to live in the wilderness. Along the way, Christopher encounters a series of characters that shape his life.', '1507233134wild.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `image_path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `name`, `date_of_birth`, `image_path`) VALUES
(1, 'Omar Sy', '1978-01-20', '1507282662omar.jpg'),
(2, 'Francois Cluzet', '1955-09-21', NULL),
(3, 'Eric Toledano', '1973-07-03', NULL),
(4, 'Olivier Nakache', '1973-04-15', NULL),
(5, 'Adrien Brody', '1973-04-14', NULL),
(6, 'Keanu Reeves', '1964-09-02', NULL),
(8, 'Matthew McConaughey', '1969-11-04', NULL),
(9, 'Tom Hardy', '1977-09-15', NULL),
(10, 'Brad Pitt', '1963-12-18', NULL),
(11, 'George Clooney', '1961-05-06', NULL),
(12, 'Kevin Spacey', '1959-07-26', NULL),
(13, 'Bryan Cranston', '1956-03-07', NULL),
(15, 'Christopher Nolan', '1970-07-30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `person_id`, `movie_id`) VALUES
(1, 'actor', 1, 1),
(2, 'actor', 2, 1),
(3, 'director', 3, 1),
(4, 'director', 4, 1),
(5, 'writer', 3, 1),
(6, 'writer', 4, 1),
(7, 'actor', 5, 2),
(8, 'actor', 12, 2),
(12, 'actor', 9, 2),
(13, 'director', 2, 2),
(14, 'actor', 9, 15),
(15, 'director', 15, 15),
(16, 'actor', 8, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`), ADD KEY `IDX_57698A6A217BBB47` (`person_id`), ADD KEY `IDX_57698A6A8F93B6FC` (`movie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `role`
--
ALTER TABLE `role`
ADD CONSTRAINT `FK_57698A6A217BBB47` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `FK_57698A6A8F93B6FC` FOREIGN KEY (`movie_id`) REFERENCES `movie` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
