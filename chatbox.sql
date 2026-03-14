-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 mars 2026 à 15:04
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `chatbox`
--

CREATE DATABASE IF NOT EXISTS `chatbox` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chatbox`;

-- --------------------------------------------------------

--
-- Structure de la table `archives_messages`
--

CREATE TABLE `archives_messages` (
  `id_archive` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_originale` datetime NOT NULL,
  `destinataire` varchar(50) NOT NULL,
  `date_suppression` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `archives_messages`
--

INSERT INTO `archives_messages` (`id_archive`, `pseudo`, `message`, `date_originale`, `destinataire`, `date_suppression`) VALUES
(1, 'Test', 'Bonjour 2 !', '2026-02-14 11:26:33', 'General', '2026-02-14 11:27:41'),
(2, 'Test2', 'Bonjour tout le monde !', '2026-02-14 11:30:57', 'General', '2026-02-14 11:31:35'),
(3, 'Test2', 'Bonjour Test !', '2026-02-14 11:30:48', 'Test', '2026-02-14 11:31:49');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `idm` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `destinataire` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

--
-- Déclencheurs `messages`
--
DELIMITER $$
CREATE TRIGGER `archivage_message` BEFORE DELETE ON `messages` FOR EACH ROW BEGIN
    INSERT INTO archives_messages (pseudo, message, date_originale, destinataire, date_suppression)
    VALUES (OLD.pseudo, OLD.message, OLD.date, OLD.destinataire, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `idu` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `mdp` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

INSERT INTO `users` (`idu`, `pseudo`, `mdp`, `role`) VALUES
  (1, 'Admin', '$2y$10$Oe0/rWbRSWoQ.TrqyMFPieI6IovL1LCE8A99t8SLyh3aigdi3eleW', 'admin');

--
-- Index pour la table `archives_messages`
--
ALTER TABLE `archives_messages`
  ADD PRIMARY KEY (`id_archive`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idm`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idu`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `archives_messages`
--
ALTER TABLE `archives_messages`
  MODIFY `id_archive` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `idm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `idu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
