-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 17 mars 2023 à 09:59
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestionarticles`
--

-- --------------------------------------------------------

--
-- Structure de la table `etre_publisher`
--

DROP TABLE IF EXISTS `etre_publisher`;
CREATE TABLE IF NOT EXISTS `etre_publisher` (
  `Login` varchar(50) COLLATE utf8_bin NOT NULL,
  `Id_article` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`Login`,`Id_article`),
  KEY `Id_article` (`Id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `etre_publisher`
--

INSERT INTO `etre_publisher` (`Login`, `Id_article`) VALUES
('Bob', 'A0001');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
