-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 06 Janvier 2016 à 20:14
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `posi_dalia`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE IF NOT EXISTS `activite` (
  `id_activite` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom_activite` varchar(255) NOT NULL,
  `theme_activite` varchar(255) DEFAULT NULL,
  `descript_activite` tinytext,
  PRIMARY KEY (`id_activite`),
  UNIQUE KEY `nom_activite` (`nom_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE IF NOT EXISTS `administrateur` (
  `id_admin` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_admin` varchar(100) NOT NULL,
  `pass_admin` varchar(50) NOT NULL,
  `droits` enum('user','custom','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `nom_admin` (`nom_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `nom_admin`, `pass_admin`, `droits`) VALUES
(1, 'n.beurion', '23c7f170fc2cc597ae8606cf475e2c2db0d06964', 'admin'),
(2, 'g.billard', 'c3df37b721e69b78485ef179a8f944cbf9694e35', 'admin'),
(3, 'f.rampion', 'ec2bd5bfd64cac4f26f8c5cf0d35c6d2929ac57b', 'admin'),
(4, 'Nico', '1e51a52d3f403d3b05d2e8653fd9547f742986de', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `code_cat` varchar(20) NOT NULL,
  `nom_cat` varchar(255) NOT NULL,
  `descript_cat` tinytext,
  PRIMARY KEY (`code_cat`),
  UNIQUE KEY `nom_cat` (`nom_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`code_cat`, `nom_cat`, `descript_cat`) VALUES
('10', 'Comprendre et communiquer Ã  l&#39;oral', ''),
('1010', 'Comprendre un Ã©noncÃ© oral', ''),
('1020', 'Identifier les caractÃ©ristiques, les attentes du ou des interlocuteurs', ''),
('1030', 'Adopter le registre de langue adaptÃ© aux destinataires et Ã  la situation', ''),
('1040', 'Avoir une rÃ©action pertinente Ã  un Ã©noncÃ© oral : rÃ©ponse, action, reformulation', ''),
('1045', 'test 2eme niveau', ''),
('10451010', 'Test niveau 4', ''),
('20', 'Lire et comprendre l&#39;Ã©crit', ''),
('2010', 'Lire et comprendre un Ã©crit simple de langage courant', ''),
('2020', 'ReconnaÃ®tre les diffÃ©rents types d&#39;Ã©crits', ''),
('2030', 'Lire et interprÃ©ter les diffÃ©rentes reprÃ©sentations graphiques : tableaux, graphiques, logos, sigles, pictogrammes...', ''),
('2040', 'DÃ©duire les actions, rÃ©ponses, solutions possibles suite Ã  la lecture d&#39;un Ã©noncÃ©', ''),
('30', 'Communiquer par Ã©crit', ''),
('3010', 'ReprÃ©senter par Ã©crit, de faÃ§on lisible, tous les signes de l&#39;Ã©criture en franÃ§ais', ''),
('3020', 'Reproduire les mots du franÃ§ais usuel et/ou du domaine professionnel', ''),
('3030', 'Construire des Ã©noncÃ©s cohÃ©rents dans leur forme gÃ©nÃ©rale (ordre des mots, des idÃ©es...)', ''),
('3040', 'RÃ©aliser diffÃ©rentes formes d&#39;Ã©crits (notes, compte-rendu, rÃ©sumÃ©, consigne...)', ''),
('40', 'ApprÃ©hender l&#39;espace', ''),
('4010', 'Se situer et siter des objets dans l&#39;espace', ''),
('401010', 'Situer les Ã©lements les uns par rapport aux autres', ''),
('401020', 'Distinguer, relever des repÃ¨res dans l&#39;espace rÃ©el et les nommer', ''),
('401030', 'ApprÃ©cier, estimer des grandeurs, des distances, des directions', ''),
('4020', 'ReconnaÃ®tre et comprendre les principales reprÃ©sentations graphiques d&#39;un espace ou d&#39;un objet', ''),
('402010', 'ReconnaÃ®tre et comprendre un plan', ''),
('4030', 'Se repÃ©rer et s&#39;orienter sur un plan simple', ''),
('50', 'ApprÃ©hender le temps', ''),
('5010', 'Se situer dans le temps', ''),
('501010', 'Reproduire et contrÃ´ler des rythmes variÃ©s et changeants', ''),
('501020', 'Planifier des actions chronologiques Ã  court, moyen et long terme', ''),
('501030', 'Se repÃ©rer dans le dÃ©coupage du temps et son vocabulaire : horaire, journalier, mensuel, annuel, millÃ©naire...', ''),
('5020', 'Combiner le temps avec d&#39;autres donnÃ©es', ''),
('5030', 'Effectuer des actions en respectant des consignes temporelles', ''),
('60', 'Utiliser les mathÃ©matiques en situation professionnelle', ''),
('6010', 'Lire et Ã©crire des grandeurs avec des chiffres et des nombres, entiers et dÃ©cimaux', ''),
('6020', 'Appliquer les techniques d&#39;opÃ©rations Ã©lÃ©mentaires sur des nombres, entiers et dÃ©cimaux', ''),
('602010', 'Appliquer des additions', ''),
('602020', 'Appliquer des multiplications', ''),
('602030', 'Appliquer des divisions', ''),
('6030', 'ProblÃ©matiser des situations', ''),
('6040', 'Appliquer les opÃ©rations pertinentes Ã  la rÃ©solution d&#39;un problÃ¨me', '');

-- --------------------------------------------------------

--
-- Structure de la table `cat_activite`
--

CREATE TABLE IF NOT EXISTS `cat_activite` (
  `ref_cat` varchar(20) NOT NULL,
  `ref_activite` int(5) unsigned NOT NULL,
  KEY `I_FK_cat_activite` (`ref_cat`),
  KEY `I_FK_activite_cat` (`ref_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `cat_preco`
--

CREATE TABLE IF NOT EXISTS `cat_preco` (
  `ref_code_cat` varchar(20) NOT NULL,
  `ref_preco` int(5) unsigned NOT NULL,
  KEY `I_FK_preco_cat` (`ref_code_cat`),
  KEY `I_FK_cat_preco` (`ref_preco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `degre`
--

CREATE TABLE IF NOT EXISTS `degre` (
  `id_degre` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_degre` varchar(100) NOT NULL,
  `descript_degre` tinytext,
  PRIMARY KEY (`id_degre`),
  UNIQUE KEY `nom_degre` (`nom_degre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `degre`
--

INSERT INTO `degre` (`id_degre`, `nom_degre`, `descript_degre`) VALUES
(1, '1', 'DegrÃ© 1'),
(2, '2', 'DegrÃ© 2'),
(3, '3', 'DegrÃ© 3');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE IF NOT EXISTS `inscription` (
  `id_inscription` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_user` int(5) unsigned NOT NULL,
  `ref_intervenant` int(5) unsigned DEFAULT NULL,
  `date_inscription` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id_inscription`),
  KEY `date_inscription` (`date_inscription`),
  KEY `I_FK_inscript_intervenant` (`ref_intervenant`),
  KEY `I_FK_inscript_utilisateur` (`ref_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `intervenant`
--

CREATE TABLE IF NOT EXISTS `intervenant` (
  `id_intervenant` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_organ` int(5) unsigned DEFAULT NULL,
  `nom_intervenant` varchar(200) DEFAULT NULL,
  `email_intervenant` varchar(100) NOT NULL DEFAULT '',
  `tel_intervenant` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_intervenant`),
  KEY `email_intervenant` (`email_intervenant`),
  KEY `I_FK_intervenant_organ` (`ref_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Structure de la table `niveau_etudes`
--

CREATE TABLE IF NOT EXISTS `niveau_etudes` (
  `id_niveau` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_niveau` varchar(100) NOT NULL,
  `descript_niveau` tinytext,
  PRIMARY KEY (`id_niveau`),
  UNIQUE KEY `nom_niveau` (`nom_niveau`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `niveau_etudes`
--

INSERT INTO `niveau_etudes` (`id_niveau`, `nom_niveau`, `descript_niveau`) VALUES
(1, 'Niveau VI et Vbis : abandon CAP - BEP - 3e', 'Sorties en cours de 1er cycle de l''enseignement secondaire (6Ã¨me Ã  3Ã©me) ou abandons en cours de CAP ou BEP avant l''annÃ©e terminale.'),
(2, 'Niveau V : CAP - BEP - 2e cycle', 'Sorties aprÃ¨s l''annÃ©e terminale de CAP ou BEP ou sorties de 2nd cycle gÃ©nÃ©ral et technologique avant l''annÃ©e terminale (seconde, premiÃ¨re ou terminale).'),
(3, 'Niveau IV : Bac', 'Sorties des classes de terminale de l''enseignement secondaire (avec le baccalaurÃ©at). Abandons des Ã©tudes supÃ©rieures.'),
(4, 'Niveau III : Bac+2', 'Sorties avec un diplÃ´me de niveau Bac + 2 ans (DUT, BTS, DEUG, Ã©coles des formations sanitaires ou sociales, etc...).'),
(5, 'Niveau II : Bac+3, bac+4', 'Sorties avec un diplÃ´me de niveau bac+3 Ã  bac+4 (licence, maÃ®trise, master I).'),
(6, 'Niveau I : Bac+5 et plus', 'Sorties avec un diplôme de niveau bac+5 et + (master II, DEA, DESS, doctorat, diplÃ´me de grande Ã©cole).');

-- --------------------------------------------------------

--
-- Structure de la table `organisme`
--

CREATE TABLE IF NOT EXISTS `organisme` (
  `id_organ` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `numero_interne` varchar(50) DEFAULT NULL,
  `nom_organ` varchar(100) NOT NULL DEFAULT '',
  `adresse_organ` tinytext,
  `code_postal_organ` char(5) DEFAULT NULL,
  `ville_organ` varchar(200) DEFAULT NULL,
  `tel_organ` char(10) DEFAULT NULL,
  `fax_organ` char(10) DEFAULT NULL,
  `email_organ` varchar(100) DEFAULT NULL,
  `nbre_posi_max` int(10) unsigned NOT NULL DEFAULT '0',
  `nbre_posi_total` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_organ`),
  UNIQUE KEY `nom_organ` (`nom_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Structure de la table `preconisation`
--

CREATE TABLE IF NOT EXISTS `preconisation` (
  `id_preco` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_type` int(5) unsigned DEFAULT NULL,
  `nom_preco` varchar(255) DEFAULT NULL,
  `descript_preco` tinytext,
  `taux_min` int(10) unsigned DEFAULT NULL,
  `taux_max` int(10) unsigned DEFAULT NULL,
  `num_ordre` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_preco`),
  UNIQUE KEY `nom_preco` (`nom_preco`),
  KEY `I_FK_preco_type` (`ref_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id_question` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_degre` int(2) unsigned DEFAULT NULL,
  `num_ordre_question` int(3) NOT NULL,
  `type_question` enum('qcm','champ_saisie') NOT NULL DEFAULT 'qcm',
  `intitule_question` tinytext NOT NULL,
  `image_question` varchar(255) DEFAULT NULL,
  `audio_question` varchar(255) DEFAULT NULL,
  `video_question` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_question`),
  UNIQUE KEY `num_ordre_question` (`num_ordre_question`),
  KEY `type_question` (`type_question`),
  KEY `I_FK_question_degre` (`ref_degre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id_question`, `ref_degre`, `num_ordre_question`, `type_question`, `intitule_question`, `image_question`, `audio_question`, `video_question`) VALUES
(1, 1, 1, 'qcm', 'Comment s&#39;appelle ce type de document ?', 'img_1_56692adaec612.jpg', 'audio_1_56692adb10a78.mp3', NULL),
(2, 2, 2, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_2_56692b0bd06de.jpg', 'audio_2_56692b0be52eb.mp3', NULL),
(3, 2, 3, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_3_56692b6a6e582.jpg', 'audio_3_56692b6a80a7e.mp3', NULL),
(4, 2, 4, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_4_56692bb069744.jpg', 'audio_4_56692bb07f2f1.mp3', NULL),
(5, 1, 5, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_5_56692e3b19853.jpg', 'audio_5_56692d623fabd.mp3', NULL),
(6, 3, 6, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_6_56692dac69bf3.jpg', 'audio_6_56692dac7d090.mp3', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `question_cat`
--

CREATE TABLE IF NOT EXISTS `question_cat` (
  `ref_question` int(5) unsigned NOT NULL,
  `ref_cat` varchar(20) DEFAULT NULL,
  KEY `I_FK_question_cat` (`ref_question`),
  KEY `I_FK_cat_question` (`ref_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `question_cat`
--

INSERT INTO `question_cat` (`ref_question`, `ref_cat`) VALUES
(1, '1010'),
(2, '1010'),
(3, '1030'),
(4, '1040'),
(5, '10451010'),
(6, '1045');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE IF NOT EXISTS `reponse` (
  `id_reponse` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_question` int(5) unsigned NOT NULL,
  `num_ordre_reponse` tinyint(3) unsigned NOT NULL,
  `intitule_reponse` tinytext NOT NULL,
  `est_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_reponse`),
  KEY `num_ordre_reponse` (`num_ordre_reponse`),
  KEY `I_FK_reponse_question` (`ref_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `reponse`
--

INSERT INTO `reponse` (`id_reponse`, `ref_question`, `num_ordre_reponse`, `intitule_reponse`, `est_correct`) VALUES
(1, 1, 1, 'Index des rues', 0),
(2, 1, 2, 'Feuille de route', 0),
(3, 1, 3, 'Plan de ville', 1),
(4, 1, 4, 'Carte routiÃ¨re', 0),
(5, 2, 1, 'A2', 0),
(6, 2, 2, 'C3', 0),
(7, 2, 3, 'B1', 0),
(8, 2, 4, 'D3', 1),
(9, 3, 1, '7h00', 0),
(10, 3, 2, '7h15', 1),
(11, 3, 3, '7h30', 0),
(12, 3, 4, '7h45', 0),
(13, 5, 1, 'Ascenseur', 0),
(14, 5, 2, 'Sortie de secours', 0),
(15, 5, 3, 'Interdiction de fumer', 1),
(16, 5, 4, 'Travaux', 0),
(17, 6, 1, '95 â‚¬', 0),
(18, 6, 2, '950 â‚¬', 1),
(19, 6, 3, '9500 â‚¬', 0);

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE IF NOT EXISTS `resultat` (
  `id_result` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_session` int(10) unsigned NOT NULL,
  `ref_question` int(5) unsigned NOT NULL,
  `ref_reponse_qcm` int(5) unsigned DEFAULT NULL,
  `ref_reponse_qcm_correcte` int(5) unsigned DEFAULT NULL,
  `reponse_champ` text,
  `validation_reponse_champ` tinyint(1) DEFAULT NULL,
  `temps_reponse` double NOT NULL,
  PRIMARY KEY (`id_result`),
  KEY `I_FK_result_session` (`ref_session`),
  KEY `I_FK_result_question` (`ref_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id_session` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_user` int(5) unsigned NOT NULL,
  `ref_intervenant` int(5) unsigned DEFAULT NULL,
  `ref_valid_acquis` int(2) unsigned DEFAULT NULL,
  `date_session` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `session_accomplie` tinyint(1) NOT NULL DEFAULT '0',
  `temps_total` double NOT NULL,
  `score_pourcent` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_session`),
  KEY `date_session` (`date_session`),
  KEY `I_FK_session_user` (`ref_user`),
  KEY `I_FK_session_intervenant` (`ref_intervenant`),
  KEY `I_FK_session_acquis` (`ref_valid_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Structure de la table `type_preco`
--

CREATE TABLE IF NOT EXISTS `type_preco` (
  `id_type` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(255) NOT NULL,
  `descript_type` tinytext,
  PRIMARY KEY (`id_type`),
  UNIQUE KEY `nom_type` (`nom_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `type_preco`
--

INSERT INTO `type_preco` (`id_type`, `nom_type`, `descript_type`) VALUES
(1, '10 heures', NULL),
(2, '20 heures', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_niveau` int(2) unsigned DEFAULT NULL,
  `nom_user` varchar(100) NOT NULL,
  `prenom_user` varchar(100) NOT NULL,
  `date_naiss_user` date NOT NULL DEFAULT '0000-00-00',
  `adresse_user` tinytext,
  `code_postal_user` char(5) DEFAULT NULL,
  `ville_user` varchar(100) DEFAULT NULL,
  `tel_user` char(10) DEFAULT NULL,
  `email_user` varchar(100) DEFAULT NULL,
  `nbre_sessions_totales` int(5) unsigned NOT NULL DEFAULT '0',
  `nbre_sessions_accomplies` int(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_user`),
  KEY `nom_user` (`nom_user`),
  KEY `prenom_user` (`prenom_user`),
  KEY `date_naiss_user` (`date_naiss_user`),
  KEY `I_FK_util_niveau` (`ref_niveau`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Structure de la table `valid_acquis`
--

CREATE TABLE IF NOT EXISTS `valid_acquis` (
  `id_acquis` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_acquis` varchar(100) NOT NULL,
  `descript_acquis` tinytext,
  PRIMARY KEY (`id_acquis`),
  UNIQUE KEY `nom_acquis` (`nom_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Contenu de la table `valid_acquis`
--

INSERT INTO `valid_acquis` (`id_acquis`, `nom_acquis`, `descript_acquis`) VALUES
(1, 'DegrÃ© 1', ''),
(2, 'DegrÃ© 2', ''),
(3, 'DegrÃ© 3', ''),
(4, 'DegrÃ© 4', '');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `cat_activite`
--
ALTER TABLE `cat_activite`
  ADD CONSTRAINT `FK_cat_activite_activ` FOREIGN KEY (`ref_activite`) REFERENCES `activite` (`id_activite`),
  ADD CONSTRAINT `FK_cat_activite_cat` FOREIGN KEY (`ref_cat`) REFERENCES `categorie` (`code_cat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cat_preco`
--
ALTER TABLE `cat_preco`
  ADD CONSTRAINT `FK_cat_preco_cat` FOREIGN KEY (`ref_code_cat`) REFERENCES `categorie` (`code_cat`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_cat_preco_preco` FOREIGN KEY (`ref_preco`) REFERENCES `preconisation` (`id_preco`) ON DELETE CASCADE;

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `FK_inscript_intervenant` FOREIGN KEY (`ref_intervenant`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_inscript_util` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `intervenant`
--
ALTER TABLE `intervenant`
  ADD CONSTRAINT `FK_intervenant_organ` FOREIGN KEY (`ref_organ`) REFERENCES `organisme` (`id_organ`) ON DELETE SET NULL;

--
-- Contraintes pour la table `preconisation`
--
ALTER TABLE `preconisation`
  ADD CONSTRAINT `FK_type_preco_preco` FOREIGN KEY (`ref_type`) REFERENCES `type_preco` (`id_type`) ON DELETE SET NULL;

--
-- Contraintes pour la table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_question_degre` FOREIGN KEY (`ref_degre`) REFERENCES `degre` (`id_degre`) ON DELETE SET NULL;

--
-- Contraintes pour la table `question_cat`
--
ALTER TABLE `question_cat`
  ADD CONSTRAINT `FK_question_cat_cat` FOREIGN KEY (`ref_cat`) REFERENCES `categorie` (`code_cat`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_question_cat_quest` FOREIGN KEY (`ref_question`) REFERENCES `question` (`id_question`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponse`
--
ALTER TABLE `reponse`
  ADD CONSTRAINT `FK_reponse_question` FOREIGN KEY (`ref_question`) REFERENCES `question` (`id_question`) ON DELETE CASCADE;

--
-- Contraintes pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD CONSTRAINT `FK_result_question` FOREIGN KEY (`ref_question`) REFERENCES `question` (`id_question`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_result_session` FOREIGN KEY (`ref_session`) REFERENCES `session` (`id_session`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `FK_session_acquis` FOREIGN KEY (`ref_valid_acquis`) REFERENCES `valid_acquis` (`id_acquis`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_session_intervenant` FOREIGN KEY (`ref_intervenant`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_session_user` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_user_niveau` FOREIGN KEY (`ref_niveau`) REFERENCES `niveau_etudes` (`id_niveau`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
