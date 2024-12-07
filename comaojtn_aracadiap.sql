-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 07 déc. 2024 à 15:44
-- Version du serveur : 10.6.20-MariaDB-cll-lve
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `comaojtn_aracadiap`
--

-- --------------------------------------------------------

--
-- Structure de la table `animal`
--

CREATE TABLE `animal` (
  `id` int(11) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `race` varchar(50) DEFAULT NULL,
  `habitat_id` int(11) DEFAULT NULL,
  `etat` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `animal`
--

INSERT INTO `animal` (`id`, `prenom`, `race`, `habitat_id`, `etat`, `image_url`) VALUES
(1, 'Simba', 'Lion', 2, 'En bonne santé', 'images/lion.jpg'),
(3, 'Kaa', 'Serpent', 1, 'Vivace', 'images/serpent.jpeg'),
(4, 'Croco', 'Crocodile', 3, 'En observation vétérinaire', 'images/crocodile.jpg'),
(13, 'Zara ', 'Girafe', 2, 'En bonne santé	', 'images/gf.jpeg'),
(14, 'Koko ', 'Gorille', 1, 'En bonne santé	', 'images/Gorille.jpg'),
(16, 'Gator ', 'Alligator', 3, 'En bonne santé	', 'images/Alligator.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `contenu` text NOT NULL,
  `statut` enum('en_attente','valide','invalide') DEFAULT 'en_attente',
  `date` date NOT NULL,
  `valide` tinyint(1) DEFAULT 0,
  `commentaire` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `pseudo`, `nom`, `email`, `utilisateur_id`, `contenu`, `statut`, `date`, `valide`, `commentaire`) VALUES
(1, '', 'yves', 'yves@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres beau'),
(2, '', 'yves1', 'yves1@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres tres bien '),
(3, '', 'milo', 'milo@gmail.com', NULL, '', 'en_attente', '0000-00-00', 1, 'tres jolis');

-- --------------------------------------------------------

--
-- Structure de la table `consommation`
--

CREATE TABLE `consommation` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `nourriture` varchar(100) NOT NULL,
  `grammage` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `email`, `message`, `date`) VALUES
(1, 'yvesnet9@gmail.com', 'salut', '2024-11-16 17:24:02'),
(2, 'ami.toutenun@gmail.com', 'salut cava', '2024-11-16 17:25:03');

-- --------------------------------------------------------

--
-- Structure de la table `habitat`
--

CREATE TABLE `habitat` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `habitat`
--

INSERT INTO `habitat` (`id`, `nom`, `description`, `image_url`) VALUES
(1, 'Jungle', 'Découvrez nos animaux exotiques dans leur habitat naturel.', 'images/jungle.jpeg'),
(2, 'Savane', 'Habitat naturel des lions, girafes, et éléphants.', 'images/savane.jpg'),
(3, 'Marais', 'Lieu de vie des crocodiles, flamants roses, et tortues.', 'images/marais.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `habitat_comments`
--

CREATE TABLE `habitat_comments` (
  `id` int(11) NOT NULL,
  `habitat_id` int(11) NOT NULL,
  `commentaire_habitat` text NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `habitat_comments`
--

INSERT INTO `habitat_comments` (`id`, `habitat_id`, `commentaire_habitat`, `date`) VALUES
(1, 2, 'erer', '2024-11-29');

-- --------------------------------------------------------

--
-- Structure de la table `interactions`
--

CREATE TABLE `interactions` (
  `id` int(11) NOT NULL,
  `type` enum('animal','habitat') NOT NULL,
  `cible_id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `date_interaction` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nourriture`
--

CREATE TABLE `nourriture` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `quantite` decimal(10,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `nourriture`
--

INSERT INTO `nourriture` (`id`, `animal_id`, `date`, `heure`, `quantite`) VALUES
(1, 1, '2024-11-30', '15:04:00', 122.00),
(2, 1, '2024-11-30', '15:04:00', 122.00),
(3, 3, '2024-11-29', '23:27:00', 1.00),
(4, 4, '2024-12-06', '23:59:00', 344.00);

-- --------------------------------------------------------

--
-- Structure de la table `rapport_veterinaire`
--

CREATE TABLE `rapport_veterinaire` (
  `id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `etat` varchar(255) NOT NULL,
  `nourriture` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rapport_veterinaire`
--

INSERT INTO `rapport_veterinaire` (`id`, `animal_id`, `date`, `etat`, `nourriture`, `details`, `date_creation`) VALUES
(1, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(2, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(3, 2, '2024-11-29', 'bonne santer ', 'dd', NULL, '2024-11-29 18:56:54'),
(4, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(5, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(6, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(7, 2, '2024-11-29', 'bonne santer ', 'dd', '', '2024-11-29 18:56:54'),
(8, 1, '2024-11-29', 'ccc', 'ccc', 'ccc', '2024-11-29 18:56:54'),
(9, 1, '2024-11-29', 'ccc', 'ccc', 'ccc', '2024-11-29 18:56:54'),
(10, 2, '2024-11-29', 'bone', 'grain', 'ccc', '2024-11-29 18:56:54'),
(11, 2, '2024-11-29', 'bo,', 'ggg', 'gggg', '2024-11-29 18:56:54'),
(12, 1, '2024-12-07', 'bonne santer', 'crokette', 'fff', '2024-12-07 14:03:52'),
(13, 1, '2024-12-07', 'bonne santer', 'crokette', 'sd', '2024-12-07 14:29:57'),
(14, 1, '2024-12-07', 'bonne santer', 'crokette', 'sd', '2024-12-07 14:37:23');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `service`
--

INSERT INTO `service` (`id`, `nom`, `description`) VALUES
(2, 'Restauration', 'Des points de restauration proposant des repas frais et locaux, adaptés à tous les âges.'),
(3, 'Visite des habitats avec guide (gratuite)', 'Explorez nos habitats en compagnie de nos guides passionnés pour en apprendre plus sur nos animaux.'),
(4, 'Visite en petit train', 'Un moyen pratique et amusant pour découvrir l’ensemble du zoo sans se fatiguer.');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `role` enum('administrateur','employe','veterinaire') NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `role`, `password`) VALUES
(6, 'employe1', 'employe', 'arcadia'),
(4, 'admin@admin.com', 'administrateur', 'admin'),
(5, 'veterinaire@zoo.com', 'veterinaire', 'vet');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `animal`
--
ALTER TABLE `animal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitat_id` (`habitat_id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `consommation`
--
ALTER TABLE `consommation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Index pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `habitat`
--
ALTER TABLE `habitat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `habitat_comments`
--
ALTER TABLE `habitat_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitat_id` (`habitat_id`);

--
-- Index pour la table `interactions`
--
ALTER TABLE `interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `nourriture`
--
ALTER TABLE `nourriture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Index pour la table `rapport_veterinaire`
--
ALTER TABLE `rapport_veterinaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `animal`
--
ALTER TABLE `animal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `consommation`
--
ALTER TABLE `consommation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `habitat`
--
ALTER TABLE `habitat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `habitat_comments`
--
ALTER TABLE `habitat_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `interactions`
--
ALTER TABLE `interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `nourriture`
--
ALTER TABLE `nourriture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `rapport_veterinaire`
--
ALTER TABLE `rapport_veterinaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `animal`
--
ALTER TABLE `animal`
  ADD CONSTRAINT `animal_ibfk_1` FOREIGN KEY (`habitat_id`) REFERENCES `habitat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
