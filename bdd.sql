-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 19 sep. 2025 à 10:20
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
-- Base de données : `workshop`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` int(100) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `resumee` varchar(500) NOT NULL,
  `tags` varchar(500) NOT NULL,
  `contenu` text NOT NULL,
  `auteurs` varchar(500) NOT NULL,
  `date_publication` date NOT NULL,
  `img_principale` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `titre`, `resumee`, `tags`, `contenu`, `auteurs`, `date_publication`, `img_principale`) VALUES
(1, 'Nouvelle espèce découverte !', 'Une nouvelle espèce de primate découverte dans une région isolée de Chine.', '1,2,3', 'En Chine méridionale, une grotte vient d\'être découverte contenant les restes d\'une espèce inconnue de primate. Les chercheurs estiment que cette découverte pourrait bouleverser notre compréhension de l\'évolution.', '1,2', '2025-09-14', 'images/primate.jpg'),
(2, 'Les avancées de l\'IA en médecine', 'Des chercheurs utilisent l\'IA pour prédire les maladies avant leur apparition.', '2,4', 'Grâce à l\'apprentissage automatique, des algorithmes permettent de détecter les premiers signes de certaines maladies chroniques, ouvrant la voie à une prévention plus efficace.', '3', '2025-09-10', 'images/ia_medecine.jpg'),
(3, 'Climat : records de chaleur', 'L\'été 2025 a battu de nouveaux records de température dans plusieurs pays.', '3,5', 'Selon l\'Organisation météorologique mondiale, les vagues de chaleur se multiplient et s\'intensifient, causant des problèmes sanitaires et environnementaux.', '2,4', '2025-08-22', 'images/climat.jpg'),
(4, 'Découverte d\'exoplanètes', 'La NASA annonce la découverte de trois nouvelles exoplanètes potentiellement habitables.', '1,4,5', 'Les télescopes spatiaux ont permis d\'identifier plusieurs planètes situées dans la zone habitable de leur étoile. Les chercheurs étudient leurs atmosphères afin de détecter des signes de vie.', '5', '2025-09-01', 'images/exoplanetes.jpg'),
(5, 'Énergies renouvelables : progrès', 'Une nouvelle génération de panneaux solaires atteint un rendement record.', '2,3', 'Une startup européenne a mis au point une technologie solaire capable de produire 40% d\'énergie en plus que les panneaux classiques, ouvrant des perspectives prometteuses.', '1,3', '2025-09-12', 'images/solaire.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `auteurs`
--

CREATE TABLE `auteurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `auteurs`
--

INSERT INTO `auteurs` (`id`, `nom`, `prenom`) VALUES
(1, 'Durand', 'Alice'),
(2, 'Martin', 'Julien'),
(3, 'Nguyen', 'Sophie'),
(4, 'Kouassi', 'Amadou'),
(5, 'Rodriguez', 'Carlos');

-- --------------------------------------------------------

--
-- Structure de la table `carte_bancaire`
--

CREATE TABLE `carte_bancaire` (
  `user_id` int(11) NOT NULL,
  `titulaire` varchar(50) NOT NULL,
  `numero_carte` int(30) NOT NULL,
  `expiration` varchar(10) NOT NULL,
  `cvv` int(4) NOT NULL,
  `type_carte` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `carte_bancaire`
--

INSERT INTO `carte_bancaire` (`user_id`, `titulaire`, `numero_carte`, `expiration`, `cvv`, `type_carte`) VALUES
(5, 'Nicolas Hubert', 2147483647, '10/25', 123, 'mastercard');

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `articles_id` int(11) NOT NULL,
  `date_like` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `articles_id`, `date_like`) VALUES
(12, 1, 5, '2025-09-18'),
(14, 1, 4, '2025-09-10'),
(16, 3, 3, '2025-09-03'),
(17, 3, 2, '2025-09-15'),
(25, 2, 4, '2025-09-09'),
(27, 2, 3, '2025-09-18');

-- --------------------------------------------------------

--
-- Structure de la table `lus`
--

CREATE TABLE `lus` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `articles_id` int(11) NOT NULL,
  `date_lecture` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lus`
--

INSERT INTO `lus` (`id`, `user_id`, `articles_id`, `date_lecture`) VALUES
(12, 2, 2, '2025-09-18'),
(15, 1, 4, '2025-09-01'),
(16, 1, 3, '2025-09-17'),
(17, 1, 2, '2025-09-09'),
(18, 3, 2, '2025-09-09'),
(19, 3, 4, '2025-09-09'),
(20, 3, 5, '2025-09-16'),
(21, 3, 1, '2025-09-09'),
(22, 3, 3, '2025-09-02'),
(23, 2, 4, '2025-09-18'),
(28, 2, 3, '2025-09-18');

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `nom`) VALUES
(1, 'Découverte'),
(2, 'Science'),
(3, 'Environnement'),
(4, 'Technologie'),
(5, 'Espace');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(101) NOT NULL,
  `nom` varchar(11) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mdp` varchar(1000) NOT NULL,
  `tel` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `mdp`, `tel`) VALUES
(1, 'FAB', 'Paul', 'p@p.com', 'paul', '02'),
(2, 'd\'harambure', 'Emily', 'emily.haramb@gmail.com', '$2y$10$A990HsINa6.AuJ5u7Mp1sOLRpmTdXXmflvWnRSvR2UW3.7lWMOSCO', '05473920'),
(3, 'Horty', '_', 'hort@horty.com', 'horty', '06569865'),
(5, 'hubert', 'nicolas', 'nicolas@gmail.com', '$2y$10$0.jcLxc7Paz3PnqFkzaVkO.OEH77hLc4FJCdHIOHmua5rB4.qMJ.m', '0612345678');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `carte_bancaire`
--
ALTER TABLE `carte_bancaire`
  ADD PRIMARY KEY (`user_id`);

--
-- Index pour la table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `lus`
--
ALTER TABLE `lus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `carte_bancaire`
--
ALTER TABLE `carte_bancaire`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `lus`
--
ALTER TABLE `lus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(101) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
