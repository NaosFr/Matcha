-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  mer. 14 mars 2018 à 14:23
-- Version du serveur :  5.6.35
-- Version de PHP :  7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `matcha`
--

-- --------------------------------------------------------

--
-- Structure de la table `assoc`
--

CREATE TABLE `assoc` (
  `id_assoc` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `blocked`
--

CREATE TABLE `blocked` (
  `id_blocked` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_blocked` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `liked`
--

CREATE TABLE `liked` (
  `id_liked` int(11) NOT NULL,
  `id_who` int(11) NOT NULL,
  `id_user_target` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id_msg` int(11) NOT NULL,
  `txt` text NOT NULL,
  `date` date NOT NULL,
  `id_user_send` int(11) NOT NULL,
  `id_user_reiceve` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `notif`
--

CREATE TABLE `notif` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_user_notified` int(11) DEFAULT NULL,
  `txt` text,
  `date` date DEFAULT NULL,
  `seen` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `reported`
--

CREATE TABLE `reported` (
  `id_reported` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_report` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id_tag` int(11) NOT NULL,
  `tag` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `passwd` varchar(300) DEFAULT NULL,
  `last_name` text,
  `first_name` text,
  `bio` text,
  `sexe` int(4) DEFAULT '0',
  `orientation` int(4) DEFAULT '0',
  `age` int(4) DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `latitude` decimal(9,6) DEFAULT '0.000000',
  `longitude` decimal(9,6) NOT NULL DEFAULT '0.000000',
  `confirm` int(11) DEFAULT NULL,
  `cle` text NOT NULL,
  `cle_passwd` text,
  `last_log` int(11) DEFAULT NULL,
  `notif` int(4) NOT NULL DEFAULT '1',
  `ip` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `email`, `login`, `passwd`, `last_name`, `first_name`, `bio`, `sexe`, `orientation`, `age`, `score`, `latitude`, `longitude`, `confirm`, `cle`, `cle_passwd`, `last_log`, `notif`, `ip`) VALUES
(1, 'ncella98@gmail.com', 'ncella', '8d1e214d80c712762ba521bd6a097571a31f822bf63ffd8c1cbafb8ec3e85858fcca65679b7f9f90439bac34fe0b02f7f459465220632671fe3e1a2d6999e9ff', 'CELLA', 'Nicolas', 'Lorem ipsum dolor sit amet, condsectetur adipisicing elit. Odit esse itaque, quaerat doloremque, eligendi eos commodi molestiae? Dicta ipsam recusswandae m4r4olestias sint eius sapiente blanditiis impedit, nobis itaque, eum sunt!s', 1, 2, 20, 148, '48.886900', '2.320400', 1, 'fc8aa1cf51c8387e6e91734e00ac0282', '15f52cc92a62078d514a6723ab5a2a7e', 1521033782, 0, '62.210.32.152');

-- --------------------------------------------------------

--
-- Structure de la table `visited`
--

CREATE TABLE `visited` (
  `id_visited` int(11) NOT NULL,
  `id_who` int(11) NOT NULL,
  `id_user_target` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `assoc`
--
ALTER TABLE `assoc`
  ADD PRIMARY KEY (`id_assoc`);

--
-- Index pour la table `blocked`
--
ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id_blocked`);

--
-- Index pour la table `liked`
--
ALTER TABLE `liked`
  ADD PRIMARY KEY (`id_liked`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id_msg`);

--
-- Index pour la table `notif`
--
ALTER TABLE `notif`
  ADD PRIMARY KEY (`id_notif`);

--
-- Index pour la table `reported`
--
ALTER TABLE `reported`
  ADD PRIMARY KEY (`id_reported`);

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id_tag`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Index pour la table `visited`
--
ALTER TABLE `visited`
  ADD PRIMARY KEY (`id_visited`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `assoc`
--
ALTER TABLE `assoc`
  MODIFY `id_assoc` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `blocked`
--
ALTER TABLE `blocked`
  MODIFY `id_blocked` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `liked`
--
ALTER TABLE `liked`
  MODIFY `id_liked` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id_msg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;
--
-- AUTO_INCREMENT pour la table `notif`
--
ALTER TABLE `notif`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `reported`
--
ALTER TABLE `reported`
  MODIFY `id_reported` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id_tag` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `visited`
--
ALTER TABLE `visited`
  MODIFY `id_visited` int(11) NOT NULL AUTO_INCREMENT;