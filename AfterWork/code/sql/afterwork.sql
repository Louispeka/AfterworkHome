-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 25 sep. 2024 à 13:47
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `afterwork`
--

-- --------------------------------------------------------

--
-- Structure de la table `bars`
--

DROP TABLE IF EXISTS `bars`;
CREATE TABLE IF NOT EXISTS `bars` (
  `bar_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bar_id`),
  UNIQUE KEY `bar_id` (`bar_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bars`
--

INSERT INTO `bars` (`bar_id`, `name`, `address`, `description`, `created_at`) VALUES
(1, 'Zebulon', '36 rue d\'Angleterre', 'Un bar convivial avec une sélection de bières artisanales.', '2024-09-24 11:00:18'),
(2, 'Le Beerstro', '10 Rue du Pont Neuf', 'Le Beerstro reprend les codes de la Taverne d\'antan et les réinvente.', '2024-09-25 10:45:32'),
(3, 'Le Peacock', '14 Pl. Rihour', 'Cette nouvelle maison lilloise joue sur une parfaite combinaison bar/restaurant pour recevoir habitués, passants du moment et voyageurs des quatre coins du monde dans une ambiance des plus conviviales.', '2024-09-25 13:20:02'),
(4, 'Helter Skelter', '80 Rue Saint-André', 'Helter Skelter est le lieu de rassemblement unique en son genre avec son concept exclusif', '2024-09-25 13:20:18');

-- --------------------------------------------------------

--
-- Structure de la table `bar_accounts`
--

DROP TABLE IF EXISTS `bar_accounts`;
CREATE TABLE IF NOT EXISTS `bar_accounts` (
  `bar_account_id` int NOT NULL AUTO_INCREMENT,
  `bar_id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`bar_account_id`),
  UNIQUE KEY `username` (`username`),
  KEY `bar_id` (`bar_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bar_accounts`
--

INSERT INTO `bar_accounts` (`bar_account_id`, `bar_id`, `username`, `password_hash`, `created_at`) VALUES
(1, 1, 'zebu59', '$2y$10$B09cvC83WSia.Q6EQK6QTeGdYEgsEc0wyKmNt8pv.FX1xmKe4Z36K', '2024-09-24 13:53:48'),
(2, 2, 'beer59', '$2y$10$6vv/YpY2cAz5tFqvkDnF4.tJGFhjaE6PUt/Uc1z06pAmpxR2q4YAq', '2024-09-25 10:47:51'),
(3, 0, 'peac59', '$2y$10$0HaLWR70iaORI104wRz4EeZswX4QJwYe6P4sE/Sx6wvpuqo8rPZNu', '2024-09-25 13:26:17'),
(4, 0, 'helt59', '$2y$10$yIKv0EGECup17O8RFGOHNu4eupldKOZBHuyKdPyAcSRTMnAOrk8Uq', '2024-09-25 13:26:58');

-- --------------------------------------------------------

--
-- Structure de la table `bar_hours`
--

DROP TABLE IF EXISTS `bar_hours`;
CREATE TABLE IF NOT EXISTS `bar_hours` (
  `hours_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bar_id` int DEFAULT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Open',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`hours_id`),
  UNIQUE KEY `hours_id` (`hours_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `bar_hours`
--

INSERT INTO `bar_hours` (`hours_id`, `bar_id`, `day_of_week`, `opening_time`, `closing_time`, `status`, `created_at`) VALUES
(1, 1, 'Lundi', '00:00:00', '00:00:00', 'Fermé', '2024-09-24 10:12:55'),
(2, 1, 'Mardi', '17:00:00', '23:45:00', 'Open', '2024-09-24 10:12:55'),
(3, 1, 'Mercredi', '17:00:00', '23:45:00', 'Open', '2024-09-24 10:12:55'),
(4, 1, 'Jeudi', '17:00:00', '23:45:00', 'Open', '2024-09-24 10:12:55'),
(5, 1, 'Vendredi', '17:00:00', '01:00:00', 'Open', '2024-09-24 10:12:55'),
(6, 1, 'Samedi', '17:00:00', '02:00:00', 'Open', '2024-09-24 10:12:55'),
(7, 1, 'Dimanche', '00:00:00', '00:00:00', 'Fermé', '2024-09-24 10:12:55'),
(8, 2, 'Lundi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(9, 2, 'Mardi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(10, 2, 'Mercredi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(11, 2, 'Jeudi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(12, 2, 'Vendredi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(13, 2, 'Samedi', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(14, 2, 'Dimanche', '11:45:00', '00:00:00', 'Open', '2024-09-25 10:51:27'),
(15, 3, 'Lundi', '08:30:00', '00:00:00', 'Open', '2024-09-25 13:23:41'),
(16, 3, 'Mardi', '08:30:00', '00:00:00', 'Open', '2024-09-25 13:23:41'),
(17, 3, 'Mercredi', '08:30:00', '00:00:00', 'Open', '2024-09-25 13:23:41'),
(18, 3, 'Jeudi', '08:30:00', '01:00:00', 'Open', '2024-09-25 13:23:41'),
(19, 3, 'Vendredi', '08:30:00', '02:00:00', 'Open', '2024-09-25 13:23:41'),
(20, 3, 'Samedi', '08:30:00', '02:00:00', 'Open', '2024-09-25 13:23:41'),
(21, 3, 'Dimanche', '09:00:00', '00:00:00', 'Open', '2024-09-25 13:23:41'),
(22, 4, 'Lundi', '18:00:00', '01:00:00', 'Open', '2024-09-25 13:24:33'),
(23, 4, 'Mardi', '18:00:00', '01:00:00', 'Open', '2024-09-25 13:24:33'),
(24, 4, 'Mercredi', '18:00:00', '01:00:00', 'Open', '2024-09-25 13:24:33'),
(25, 4, 'Jeudi', '18:00:00', '02:00:00', 'Open', '2024-09-25 13:24:33'),
(26, 4, 'Vendredi', '18:00:00', '02:00:00', 'Open', '2024-09-25 13:24:33'),
(27, 4, 'Samedi', '16:00:00', '02:00:00', 'Open', '2024-09-25 13:24:33'),
(28, 4, 'Dimanche', '16:00:00', '01:00:00', 'Open', '2024-09-25 13:24:33');

-- --------------------------------------------------------

--
-- Structure de la table `beers`
--

DROP TABLE IF EXISTS `beers`;
CREATE TABLE IF NOT EXISTS `beers` (
  `beer_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `alcohol_content` decimal(5,2) DEFAULT NULL,
  `brewery` varchar(255) DEFAULT NULL,
  `bar_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`beer_id`),
  UNIQUE KEY `beer_id` (`beer_id`),
  KEY `idx_beers_name` (`name`(250))
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `beers`
--

INSERT INTO `beers` (`beer_id`, `name`, `type`, `alcohol_content`, `brewery`, `bar_id`, `created_at`) VALUES
(1, 'Triple K', 'Belgian Tripel', 8.50, 'Brasserie Dupont', 1, '2024-09-24 10:30:53'),
(2, 'La Chouffe', 'Blonde', 8.00, 'Brasserie d\'Achouffe', 1, '2024-09-25 13:31:18'),
(3, 'Affligem Blonde', 'Blonde', 6.70, 'Brasserie Affligem', 3, '2024-09-25 13:31:18'),
(4, 'Chimay Bleue', 'Brune', 9.00, 'Chimay Brewery', 3, '2024-09-25 13:31:18'),
(5, 'Leffe Blonde', 'Blonde', 6.60, 'Brasserie Leffe', 3, '2024-09-25 13:31:18'),
(6, 'Orval', 'Trappiste', 6.20, 'Orval Brewery', 1, '2024-09-25 13:31:18'),
(7, 'Duvel', 'Blonde', 8.50, 'Duvel Moortgat Brewery', 4, '2024-09-25 13:31:18'),
(8, 'Blanche de Namur', 'Blanche', 4.50, 'Brasserie Du Bocq', 2, '2024-09-25 13:31:18'),
(9, 'La Corne du Bois des Pendus', 'Blonde', 8.00, 'Brasserie Thiriez', 2, '2024-09-25 13:31:18');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservation_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `bar_id` int DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `number_of_people` int NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reservation_id`),
  UNIQUE KEY `reservation_id` (`reservation_id`),
  KEY `idx_reservations_date` (`reservation_date`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `user_id`, `bar_id`, `reservation_date`, `reservation_time`, `number_of_people`, `status`, `created_at`) VALUES
(5, 2, 2, '2024-09-25', '17:42:00', 3, 'Pending', '2024-09-25 12:43:07'),
(4, 2, 2, '2024-09-25', '14:20:00', 2, 'Pending', '2024-09-25 12:16:32');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `bar_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `review_id` (`review_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'Louis', 'Peka', 'louispeka@example.com', '$2y$10$i9jQKv1ZaXJ5BWAM.Qyw5OdEntoL/WklPHLAbXNcVmayP2PbiyFta', '2024-09-24 10:31:25'),
(2, 'Louis', 'Van elsuve', 'louis.vanelsuve@gmail.com', '$2y$10$hSvO1cvTllO0uiA9HUyemOYwLeoJPgIqMaKe0LWyodEq2OWCOz0Le', '2024-09-24 10:46:15'),
(3, 'jean marie', 'Messende', 'jeanmarie.messende@gmail.com', '$2y$10$19fpKZyYpd4tTMXHcwtP8O7tNp5iioUQJqee5q3bmyaGGZkpzNkgO', '2024-09-25 13:33:44');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
