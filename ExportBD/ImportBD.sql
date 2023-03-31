create database IF NOT EXISTS gestionarticles;

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



DROP TABLE IF EXISTS gestionarticles.article;
CREATE TABLE IF NOT EXISTS gestionarticles.article (
  `Id_article` varchar(50) COLLATE utf8_bin NOT NULL,
  `Date_publication` date DEFAULT NULL,
  `Contenu` text COLLATE utf8_bin NOT NULL,
  `Publisher` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`Id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO gestionarticles.article (`Id_article`, `Date_publication`, `Contenu`, `Publisher`) VALUES
('A0001', '2022-11-16', 'Les comédiens ont un langage singulier : Ils se souviennent, improvisent, colorent leur mémoire, citent les formules oubliées, inventent même des phrases drôles, cruelles, qui se répètent et deviennent des citations.', 'Bob'),
('A0002', '2023-01-05', 'Elle a rencontré son âme sœur dans un café, mais n\'a jamais eu le courage de lui parler. Des années plus tard, elle l\'a revu, marié et avec des enfants, et a réalisé qu\'elle avait laissé passer l\'amour de sa vie.', 'Camille58'),
('A0003', '2023-03-01', 'Après des mois de voyage dans l\'espace, ils ont enfin atteint leur destination, seulement pour découvrir qu\'ils étaient arrivés dans un univers parallèle où tout était exactement pareil, sauf eux-mêmes.', 'Bob'),
('A0004', '2023-03-31', 'Elle a trouvé une vieille boîte dans le grenier de sa grand-mère, remplie de lettres d\'amour de son grand-père décédé. En lisant les lettres, elle a réalisé que son grand-père avait été amoureux d\'une autre femme avant de rencontrer sa grand-mère.', 'Camille58');
COMMIT;



DROP TABLE IF EXISTS gestionarticles.utilisateur;
CREATE TABLE IF NOT EXISTS gestionarticles.utilisateur (
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `role` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO gestionarticles.utilisateur (`username`, `password`, `role`) VALUES
('DarkModerator', 'DARKMDP', 'moderator'),
('Bob', 'BOBMDP', 'publisher'),
('Camille58', '58MDP', 'publisher'),
('Baptiste ', 'JeanMange', 'Publisher');
COMMIT;



DROP TABLE IF EXISTS gestionarticles.interagir;
CREATE TABLE IF NOT EXISTS gestionarticles.interagir (
  `Username` varchar(50) COLLATE utf8_bin NOT NULL,
  `Id_article` varchar(50) COLLATE utf8_bin NOT NULL,
  `Est_like` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Username`,`Id_article`),
  KEY `Id_article` (`Id_article`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;



INSERT INTO gestionarticles.interagir (`Username`, `Id_article`, `Est_like`) VALUES
('Camille58', 'A0001', 1),
('Bob', 'A0001', 1);
COMMIT;

