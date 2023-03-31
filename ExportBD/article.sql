-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 31 mars 2023 à 00:53
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
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `Id_article` varchar(50) COLLATE utf8_bin NOT NULL,
  `Date_publication` date DEFAULT NULL,
  `Contenu` text COLLATE utf8_bin NOT NULL,
  `Publisher` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`Id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`Id_article`, `Date_publication`, `Contenu`, `Publisher`) VALUES
('A0001', '2022-11-16', 'Les comédiens ont un langage singulier : Ils se souviennent, improvisent, colorent leur mémoire, citent les formules oubliées, inventent même des phrases drôles, cruelles, qui se répètent et deviennent des citations.', 'Bob'),
('A0002', '2023-01-05', 'Elle a rencontré son âme sœur dans un café, mais n\'a jamais eu le courage de lui parler. Des années plus tard, elle l\'a revu, marié et avec des enfants, et a réalisé qu\'elle avait laissé passer l\'amour de sa vie.', 'Camille58'),
('A0003', '2023-03-01', 'Après des mois de voyage dans l\'espace, ils ont enfin atteint leur destination, seulement pour découvrir qu\'ils étaient arrivés dans un univers parallèle où tout était exactement pareil, sauf eux-mêmes.', 'Bob'),
('A0004', '2023-03-31', 'Elle a trouvé une vieille boîte dans le grenier de sa grand-mère, remplie de lettres d\'amour de son grand-père décédé. En lisant les lettres, elle a réalisé que son grand-père avait été amoureux d\'une autre femme avant de rencontrer sa grand-mère.', 'Camille58');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
