-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 09 juin 2020 à 17:36
-- Version du serveur :  5.7.30-0ubuntu0.18.04.1
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Structure de la table `project`
--

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `chapo` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `dateUpdated` datetime NOT NULL,
  `linkToProject` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `project`
--

INSERT INTO `project` (`id`, `userId`, `title`, `chapo`, `content`, `author`, `dateUpdated`, `linkToProject`) VALUES
(60, 1, 'DÃ©finissez votre stratÃ©gie d\'apprentissage', 'DÃ©finir ses objectifs, son projet professionnel et connaitre desoutils pour mieux apprendre,', 'Lâ€™objectif de ce premier projet est de vous donner toutes les clÃ©s pour rÃ©ussir votre parcours, puis votre insertion professionnelle ! \r\n\r\nAinsi, vous allez mettre en place votre planning de formation, et vous familiariser avec les projets que vous aurez Ã  rÃ©aliser pendant votre parcours. Ce projet vous permettra Ã©galement de prÃ©parer votre premiÃ¨re sÃ©ance de mentorat oÃ¹ vous vous prÃ©senterez Ã  votre mentor ! \r\n\r\nNotez cependant que le format des projets sera diffÃ©rent par la suite ! Vous serez immergÃ© dans le monde professionnel et rÃ©aliserez des livrables comme dans une vraie vie professionnelle.\r\n\r\nVotre planning de formation\r\n\r\nVous venez de vous inscrire chez OpenClassrooms pour un parcours de formation et vous ne savez pas trop par oÃ¹ commencer. Câ€™est tout Ã  fait normal ! La pÃ©dagogie par projet est moins guidÃ©e que les mÃ©thodes de formation classiques. Cependant, elle vous permet de gagner rapidement en autonomie et dâ€™aller Ã  votre rythme. Cela vous donnera la possibilitÃ© dâ€™Ãªtre pleinement opÃ©rationnel dÃ¨s la fin de votre formation !\r\n\r\nAlors, pour vous organiser, pourquoi ne pas dÃ©marrer par la mise en place de votre planning de formation ?\r\n\r\nPassez en revue chaque projet. Lisez rapidement les Ã©noncÃ©s pour vous familiariser avec le vocabulaire de votre futur mÃ©tier. \r\n\r\nEn fonction de votre emploi du temps personnel, du temps que vous souhaitez consacrer Ã  la formation et de vos contraintes, dÃ©terminez quand vous allez pouvoir travailler dans la semaine et les dates prÃ©visionnelles de soutenance de chacun de ces projets.\r\n\r\nObjectifs d\'apprentissage et gestion des moments de frustration\r\n\r\nPosez-vous les questions suivantes et Ã©crivez un bref paragraphe que vous prÃ©senterez Ã  votre mentor :\r\n\r\n    Quel est mon objectif Ã  long terme ? Peut-Ãªtre aspirez-vous Ã  changer de carriÃ¨re dans une nouvelle entreprise, ou alors dÃ©velopper de nouvelles compÃ©tences au sein de votre mÃ©tier. \r\n    Comment vais-je gÃ©rer les moments de frustration ? Les thÃ¨mes que vous allez aborder ne vont pas toujours Ãªtre une promenade de santÃ©. Pensez Ã  vos expÃ©riences passÃ©es, Ã  l\'Ã©cole, en entreprise ou dans votre vie personnelle, et rappelez-vous comment vous avez rÃ©ussi Ã  surmonter ces difficultÃ©s.\r\n\r\nLes canaux de discussion Workspace\r\n\r\nEntre les sessions de mentorat, vous pourrez discuter avec d\'autres Ã©tudiants et mÃªme d\'autres mentors sur un canal de discussion Workplace (un forum en ligne). C\'est l\'endroit oÃ¹ se retrouvent les autres personnes qui suivent la formation : apprenez Ã  vous y connecter rÃ©guliÃ¨rement, cela vous permettra de vous Ã©pauler entre Ã©tudiants et mentors, mais aussi d\'obtenir une aide parfois trÃ¨s utile entre deux sessions de mentorat.\r\n\r\nCommencez par vous prÃ©senter en quelques lignes, cela aidera Ã  faire connaissance et Ã  rencontrer des personnes prÃ¨s de chez vous qui partagent les mÃªmes objectifs et passions ! \r\nLivrables\r\n- Votre planning de formation, en indiquant notamment : le nombre dâ€™heures consacrÃ©es Ã  la formation chaque semaine. Et les dates prÃ©visionnelles de soutenances.\r\n- Un paragraphe vous prÃ©sentant Ã  votre mentor, avec 4 informations clÃ©s \r\n        vos objectifs d\'apprentissage ;\r\n        comment vous Ãªtes parvenu Ã  surmonter des difficultÃ©s et Ã  relever des dÃ©fis par le passÃ© (pour prÃ©parer les Ã©ventuels moments de frustration !) ;\r\n        le nom et lâ€™activitÃ© de votre employeur si vous Ãªtes en alternance ;\r\n        et enfin, des informations sur le financement de votre formation, notamment le nom du financeur et la description du programme financÃ© (si vous le connaissez) ;\r\n    Une capture dâ€™Ã©cran dâ€™un post sur Workplace oÃ¹ vous vous prÃ©sentez Ã  la communautÃ©.\r\n\r\n', 'Nicolas', '2020-06-03 18:45:44', 'http://github.com/nicolasrll');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `login`, `password`, `role`, `active`) VALUES
(1, 'admin-p5', '$2y$10$06AxFg/qa2H5/lyRN/4RDOX7t17oVrI7/MOD/EUgKPq/fFLc/pGfq', 'admin', 1),
(2, 'user1', '$2y$10$3m7vS8xQzLjNAMvGNnD4n.iqZ6.Mr9713.0OIk.8RdUOB9a8dyecW', 'user', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_userProject` (`userId`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `project`
--
ALTER TABLE `project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `fk_userProject` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
