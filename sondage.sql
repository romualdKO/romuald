-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 25 oct. 2024 à 10:15
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sondage`
--

-- --------------------------------------------------------

--
-- Structure de la table `polls`
--

DROP TABLE IF EXISTS `polls`;
CREATE TABLE IF NOT EXISTS `polls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `polls`
--

INSERT INTO `polls` (`id`, `title`, `description`, `created_by`, `created_at`) VALUES
(6, 'sport et vie', 'l\'importance du sport dans la vie', 1, '2024-10-25 00:13:39'),
(5, 'garba', 'qui veut manger', 2, '2024-10-24 21:31:52');

-- --------------------------------------------------------

--
-- Structure de la table `poll_options`
--

DROP TABLE IF EXISTS `poll_options`;
CREATE TABLE IF NOT EXISTS `poll_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `poll_id` int NOT NULL,
  `option_text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `poll_options`
--

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`) VALUES
(1, 5, 'oui'),
(2, 5, 'non'),
(3, 6, 'oui'),
(4, 6, 'non');

-- --------------------------------------------------------

--
-- Structure de la table `poll_votes`
--

DROP TABLE IF EXISTS `poll_votes`;
CREATE TABLE IF NOT EXISTS `poll_votes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `option_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `option_id` (`option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `poll_votes`
--

INSERT INTO `poll_votes` (`id`, `option_id`, `user_id`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 4),
(4, 1, 5),
(5, 3, 1),
(6, 3, 2),
(7, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`) VALUES
(1, 'steven', '$2y$10$PTI1AAMPM738DkLytk874.Sz3WeGwzMpIXLfSYO7RjrM8/krZdsOe', 'stevenamani130@gmail.com'),
(2, 'romuald', '$2y$10$JPkk306xea5QMSKawCipd.jRyaqu43.Z/fbW6vc0RcJsHFnHK3RP2', 'romualdndri9@gmai.com'),
(3, 'Albert', '$2y$10$oiorb5XKro1yJaeEHXmI/eu7Ge6X0CWhoT94IlkFhM7PcWQk7QdDm', 'albertcoulibaly81@gmail.com'),
(4, 'Mousse', '$2y$10$Fot9f1XZswhFLlqI11aQTOey1ymBoZUTR6f15QhGV20rvNeIZ58tq', 'mousselago16@gmail.com'),
(5, 'SANDE KOUADIO ', '$2y$10$O5Zmet2vQz8/9Jvds3HgPOipibDHnwML/C7U1rfn868YJP43YkkhC', 'kouadiojeanmarcsande@gmail.com'),
(6, 'M\'bo', '$2y$10$RgndgpKDGfIQPu2I6cf8aeSdM3bmkLQORBXH.LRCw1yjT3L.yffpe', 'emmanuelgmc123@gmail.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
