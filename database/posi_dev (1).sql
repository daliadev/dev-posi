-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 28 Octobre 2016 à 15:49
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `posi_dev`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE IF NOT EXISTS `administrateur` (
  `id_admin` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_admin` varchar(100) NOT NULL,
  `pass_admin` varchar(50) NOT NULL,
  `droits` enum('user','custom-public','custom-admin','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `nom_admin` (`nom_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `nom_admin`, `pass_admin`, `droits`) VALUES
(1, 'n.beurion', '23c7f170fc2cc597ae8606cf475e2c2db0d06964', 'admin'),
(2, 'g.billard', 'c3df37b721e69b78485ef179a8f944cbf9694e35', 'admin'),
(3, 'f.rampion', 'ec2bd5bfd64cac4f26f8c5cf0d35c6d2929ac57b', 'admin'),
(4, 'admin', '1e51a52d3f403d3b05d2e8653fd9547f742986de', 'custom-admin'),
(6, 'test', '1a6b247a1cf31f518e64534236ab71b6e4919ed8', 'custom-public');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `code_cat` varchar(20) NOT NULL,
  `ref_posi` varchar(10) NOT NULL DEFAULT '1',
  `nom_cat` varchar(255) NOT NULL,
  `descript_cat` tinytext,
  PRIMARY KEY (`code_cat`),
  UNIQUE KEY `nom_cat` (`nom_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`code_cat`, `ref_posi`, `nom_cat`, `descript_cat`) VALUES
('10', '1', 'Comprendre et communiquer Ã  l&#39;oral', ''),
('1010', '1', 'Comprendre un Ã©noncÃ© oral', ''),
('1020', '1', 'Identifier les caractÃ©ristiques, les attentes du ou des interlocuteurs', ''),
('1030', '1', 'Adopter le registre de langue adaptÃ© aux destinataires et Ã  la situation', ''),
('1045', '1', 'Avoir une rÃ©action pertinente Ã  un Ã©noncÃ© oral : rÃ©ponse, action, reformulation', ''),
('104510', '1', 'RÃ©pondre Ã  un interlocuteur', ''),
('10451010', '1', 'Test update', ''),
('20', '1', 'Lire et comprendre l&#39;Ã©crit', ''),
('2010', '1', 'Lire et comprendre un Ã©crit simple de langage courant', ''),
('2020', '1', 'ReconnaÃ®tre les diffÃ©rents types d&#39;Ã©crits', ''),
('2030', '1', 'Lire et interprÃ©ter les diffÃ©rentes reprÃ©sentations graphiques : tableaux, graphiques, logos, sigles, pictogrammes...', ''),
('2040', '1', 'DÃ©duire les actions, rÃ©ponses, solutions possibles suite Ã  la lecture d&#39;un Ã©noncÃ©', ''),
('30', '1', 'Communiquer par Ã©crit', ''),
('3010', '1', 'ReprÃ©senter par Ã©crit, de faÃ§on lisible, tous les signes de l&#39;Ã©criture en franÃ§ais', ''),
('3020', '1', 'Reproduire les mots du franÃ§ais usuel et/ou du domaine professionnel', ''),
('3030', '1', 'Construire des Ã©noncÃ©s cohÃ©rents dans leur forme gÃ©nÃ©rale (ordre des mots, des idÃ©es...)', ''),
('3040', '1', 'RÃ©aliser diffÃ©rentes formes d&#39;Ã©crits (notes, compte-rendu, rÃ©sumÃ©, consigne...)', ''),
('40', '3', 'ApprÃ©hender l&#39;espace', ''),
('4010', '3', 'Se situer et siter des objets dans l&#39;espace', ''),
('401010', '3', 'Situer les Ã©lements les uns par rapport aux autres', ''),
('401020', '3', 'Distinguer, relever des repÃ¨res dans l&#39;espace rÃ©el et les nommer', ''),
('401030', '3', 'ApprÃ©cier, estimer des grandeurs, des distances, des directions', ''),
('4020', '3', 'ReconnaÃ®tre et comprendre les principales reprÃ©sentations graphiques d&#39;un espace ou d&#39;un objet', ''),
('402010', '3', 'ReconnaÃ®tre et comprendre un plan', ''),
('4030', '3', 'Se repÃ©rer et s&#39;orienter sur un plan simple', ''),
('50', '3', 'ApprÃ©hender le temps', ''),
('5010', '3', 'Se situer dans le temps', ''),
('501010', '3', 'Reproduire et contrÃ´ler des rythmes variÃ©s et changeants', ''),
('501020', '3', 'Planifier des actions chronologiques Ã  court, moyen et long terme', ''),
('501030', '3', 'Se repÃ©rer dans le dÃ©coupage du temps et son vocabulaire : horaire, journalier, mensuel, annuel, millÃ©naire...', ''),
('5020', '3', 'Combiner le temps avec d&#39;autres donnÃ©es', ''),
('5030', '3', 'Effectuer des actions en respectant des consignes temporelles', ''),
('60', '2', 'Utiliser les mathÃ©matiques en situation professionnelle', ''),
('6010', '2', 'Lire et Ã©crire des grandeurs avec des chiffres et des nombres, entiers et dÃ©cimaux', ''),
('6020', '2', 'Appliquer les techniques d&#39;opÃ©rations Ã©lÃ©mentaires sur des nombres, entiers et dÃ©cimaux', ''),
('602010', '2', 'Appliquer des additions', ''),
('602020', '2', 'Appliquer des multiplications', ''),
('602030', '2', 'Appliquer des divisions', ''),
('6030', '2', 'ProblÃ©matiser des situations', ''),
('6040', '2', 'Appliquer les opÃ©rations pertinentes Ã  la rÃ©solution d&#39;un problÃ¨me', '');

-- --------------------------------------------------------

--
-- Structure de la table `cat_activite`
--

CREATE TABLE IF NOT EXISTS `cat_activite` (
  `id_cat_activite` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_cat` varchar(20) NOT NULL,
  `ref_activite` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id_cat_activite`),
  KEY `I_FK_cat_activite` (`ref_cat`),
  KEY `I_FK_activite_cat` (`ref_activite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `cat_preco`
--

CREATE TABLE IF NOT EXISTS `cat_preco` (
  `id_cat_preco` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_code_cat` varchar(20) NOT NULL,
  `ref_preco` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id_cat_preco`),
  KEY `I_FK_preco_cat` (`ref_code_cat`),
  KEY `I_FK_cat_preco` (`ref_preco`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `cat_preco`
--

INSERT INTO `cat_preco` (`id_cat_preco`, `ref_code_cat`, `ref_preco`) VALUES
(1, '10451010', 1),
(2, '10', 2),
(3, '10', 3),
(4, '10', 4),
(5, '10', 5),
(6, '1010', 6),
(7, '1030', 8),
(8, '1030', 9),
(9, '104510', 10),
(10, '104510', 11),
(11, '2010', 12),
(12, '2010', 13),
(13, '20', 14),
(14, '20', 15),
(15, '20', 16),
(16, '1010', 17);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `degre`
--

INSERT INTO `degre` (`id_degre`, `nom_degre`, `descript_degre`) VALUES
(1, '1', 'Degré 1'),
(2, '2', 'Degré 2'),
(3, '3', 'Degré 3');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `inscription`
--

INSERT INTO `inscription` (`id_inscription`, `ref_user`, `ref_intervenant`, `date_inscription`) VALUES
(1, 1, 1, '2016-02-15'),
(2, 1, 2, '2016-02-23'),
(3, 2, 3, '2016-02-26'),
(4, 3, 4, '2016-02-29'),
(5, 4, 5, '2016-02-29'),
(6, 5, 6, '2016-02-29'),
(8, 1, 7, '2016-04-18'),
(11, 9, 8, '2016-04-21'),
(12, 10, 9, '2016-05-03'),
(13, 11, 10, '2016-05-26'),
(14, 12, 11, '2016-05-31'),
(15, 13, 12, '2016-05-31'),
(16, 14, 13, '2016-06-27'),
(17, 15, 13, '2016-07-25'),
(18, 1, 13, '2016-10-06'),
(19, 16, 13, '2016-10-07');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `intervenant`
--

INSERT INTO `intervenant` (`id_intervenant`, `ref_organ`, `nom_intervenant`, `email_intervenant`, `tel_intervenant`) VALUES
(1, 1, NULL, 'test@first.fr', NULL),
(2, 1, NULL, 'test@resultats.fr', NULL),
(3, 2, NULL, 'test@uni-narbonne.fr', NULL),
(4, 4, NULL, 'test@uniformation-rouen.fr', NULL),
(5, 2, NULL, 'test@uniformation-narbonne.fr', NULL),
(6, 1, NULL, 'test@uniformation-rouen2.fr', NULL),
(7, 1, NULL, 'test@test.fr', NULL),
(8, 5, NULL, 'test@lyon.fr', NULL),
(9, 4, NULL, 'test.caen@test.fr', NULL),
(10, 5, NULL, 'test@jhgqsg.fr', NULL),
(11, 1, NULL, 'test@test-image.fr', NULL),
(12, 1, NULL, 'test@image.fr', NULL),
(13, 1, NULL, 'test@video.fr', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

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
  `nbre_posi_total` int(10) unsigned NOT NULL DEFAULT '0',
  `nbre_posi_max` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_organ`),
  UNIQUE KEY `nom_organ` (`nom_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `organisme`
--

INSERT INTO `organisme` (`id_organ`, `numero_interne`, `nom_organ`, `adresse_organ`, `code_postal_organ`, `ville_organ`, `tel_organ`, `fax_organ`, `email_organ`, `nbre_posi_total`, `nbre_posi_max`) VALUES
(1, 'f5be2ca6', 'Uniformation - Rouen', NULL, '76000', NULL, '0235695847', NULL, NULL, 4, 0),
(2, 'f13f7a40', 'Uniformation - Narbonne', NULL, '11100', NULL, '0452369585', NULL, NULL, 2, 0),
(3, 'f14a3d88', 'Uniformation - Saint-Malo', NULL, '35400', NULL, '0245857496', NULL, NULL, 0, 0),
(4, 'f14ef912', 'Uniformation - Caen', NULL, '14000', NULL, '0214526385', NULL, NULL, 2, 0),
(5, 'f1578e27', 'Uniformation - Lyon', NULL, '69000', NULL, '0425316945', NULL, NULL, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `parcours_preco`
--

CREATE TABLE IF NOT EXISTS `parcours_preco` (
  `id_parcours` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `volume_parcours` int(10) unsigned DEFAULT NULL,
  `nom_parcours` varchar(255) NOT NULL,
  `descript_parcours` tinytext,
  PRIMARY KEY (`id_parcours`),
  UNIQUE KEY `nom_parcours` (`nom_parcours`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `parcours_preco`
--

INSERT INTO `parcours_preco` (`id_parcours`, `volume_parcours`, `nom_parcours`, `descript_parcours`) VALUES
(1, 10, '10 heures de formation', 'Test'),
(2, 20, '20 heures de formation', 'Test de description'),
(3, 30, '30 heures de formation', ''),
(4, 40, '40 heures de formation', ''),
(5, 50, '50 heures de formation', '');

-- --------------------------------------------------------

--
-- Structure de la table `positionnement`
--

CREATE TABLE IF NOT EXISTS `positionnement` (
  `id_posi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_posi` varchar(255) NOT NULL,
  `descript_posi` tinytext,
  PRIMARY KEY (`id_posi`),
  UNIQUE KEY `nom_posi` (`nom_posi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `positionnement`
--

INSERT INTO `positionnement` (`id_posi`, `nom_posi`, `descript_posi`) VALUES
(1, 'Domaine 1', 'Français'),
(2, 'Domaine 2', 'Maths'),
(3, 'Domaine 3', 'Gestes, postures et orientation');

-- --------------------------------------------------------

--
-- Structure de la table `preconisation`
--

CREATE TABLE IF NOT EXISTS `preconisation` (
  `id_preco` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_parcours` int(5) unsigned DEFAULT NULL,
  `nom_preco` varchar(255) NOT NULL,
  `descript_preco` tinytext,
  `taux_min` int(10) unsigned DEFAULT NULL,
  `taux_max` int(10) unsigned DEFAULT NULL,
  `num_ordre` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_preco`),
  KEY `I_FK_preco_parcours` (`ref_parcours`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `preconisation`
--

INSERT INTO `preconisation` (`id_preco`, `ref_parcours`, `nom_preco`, `descript_preco`, `taux_min`, `taux_max`, `num_ordre`) VALUES
(1, 2, '', NULL, 0, 50, 0),
(2, 5, '', '', 0, 50, 0),
(3, 3, '', '', 51, 75, 1),
(4, 2, '', '', 76, 90, 2),
(5, 1, '', '', 91, 100, 3),
(6, 3, '', '', 0, 50, 0),
(8, 2, '', '', 0, 50, 0),
(9, 1, '', '', 51, 100, 1),
(10, 2, '', '', 0, 50, 0),
(11, 1, '', '', 51, 100, 1),
(12, 1, '', '', 0, 50, 0),
(13, 2, '', '', 51, 100, 1),
(14, 3, '', '', 0, 40, 0),
(15, 2, '', '', 41, 70, 1),
(16, 1, '', '', 71, 100, 2),
(17, 2, '', '', 51, 100, 1);

-- --------------------------------------------------------

--
-- Structure de la table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id_question` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_posi` varchar(10) NOT NULL DEFAULT '1',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id_question`, `ref_posi`, `ref_degre`, `num_ordre_question`, `type_question`, `intitule_question`, `image_question`, `audio_question`, `video_question`) VALUES
(1, '2', NULL, 3, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_3_5669755257020.jpg', 'audio_3_5669755257bd8.mp3', NULL),
(2, '1', 1, 1, 'qcm', 'Comment s&#39;appelle ce document ?', 'img_1_5669723605024.jpg', 'audio_1_5669723605bdc.mp3', NULL),
(3, '3', NULL, 2, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_2_574d9918be597.jpg', 'audio_2_566972360d4f7.mp3', 'video_2_55a7794336916.mp4'),
(4, '1', 2, 4, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã Â  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_4_56af663187a2d.jpg', 'audio_4_56af6631b0696.mp3', NULL),
(5, '3', 3, 5, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_5_56af67df9a0a3.jpg', 'audio_5_56af67dfb3ebe.mp3', NULL),
(6, '2', 1, 6, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_6_56af687db3830.jpg', 'audio_6_56af687dd052c.mp3', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `question_cat`
--

CREATE TABLE IF NOT EXISTS `question_cat` (
  `id_question_cat` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `ref_question` int(5) unsigned NOT NULL,
  `ref_cat` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_question_cat`),
  KEY `I_FK_question_cat` (`ref_question`),
  KEY `I_FK_cat_question` (`ref_cat`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `question_cat`
--

INSERT INTO `question_cat` (`id_question_cat`, `ref_question`, `ref_cat`) VALUES
(1, 1, '10'),
(2, 2, '1010'),
(3, 3, '1010'),
(4, 4, '1030'),
(5, 5, '104510'),
(6, 6, '2010');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `reponse`
--

INSERT INTO `reponse` (`id_reponse`, `ref_question`, `num_ordre_reponse`, `intitule_reponse`, `est_correct`) VALUES
(1, 1, 1, '7h00', 0),
(2, 1, 2, '7h15', 1),
(3, 1, 3, '7h30', 0),
(4, 1, 4, '7h45', 0),
(5, 2, 1, 'Index des rues', 0),
(6, 2, 2, 'Feuille de route', 0),
(7, 2, 3, 'Plan de ville', 1),
(8, 2, 4, 'Carte routiÃ¨re', 0),
(9, 3, 1, 'A2', 0),
(10, 3, 2, 'C3', 0),
(11, 3, 3, 'B1', 0),
(12, 3, 4, 'D3', 1),
(13, 5, 1, 'Place handicapÃ©', 0),
(14, 5, 2, 'Sortie de secours', 0),
(15, 5, 3, 'Interdiction de fumer', 1),
(16, 5, 4, 'Travaux', 0),
(17, 6, 1, '95 euros', 0),
(18, 6, 2, '950 euros', 1),
(19, 6, 3, '9500 euros', 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

--
-- Contenu de la table `resultat`
--

INSERT INTO `resultat` (`id_result`, `ref_session`, `ref_question`, `ref_reponse_qcm`, `ref_reponse_qcm_correcte`, `reponse_champ`, `validation_reponse_champ`, `temps_reponse`) VALUES
(1, 1, 2, 7, 7, NULL, 0, 16.566534996033),
(2, 1, 3, 10, 12, NULL, 0, 17.155534982681),
(3, 1, 1, 3, 2, NULL, 0, 27.871893167496),
(4, 1, 4, NULL, NULL, 'hsfghsfh', 0, 29.873781919479),
(5, 1, 5, 15, 15, NULL, 0, 10.261856079102),
(6, 1, 6, 18, 18, NULL, 0, 21.42699098587),
(7, 2, 2, 7, 7, NULL, 0, 30.943899869919),
(8, 2, 3, 10, 12, NULL, 0, 16.510519981384),
(9, 2, 1, 2, 2, NULL, 0, 19.543725013733),
(10, 2, 4, NULL, NULL, 'Test', 0, 26.25300693512),
(11, 2, 5, 15, 15, NULL, 0, 44.718091011047),
(12, 2, 6, 18, 18, NULL, 0, 20.974008083344),
(13, 3, 2, 7, 7, NULL, 0, 20.461994171143),
(14, 3, 3, 10, 12, NULL, 0, 38.736994028091),
(15, 3, 1, 2, 2, NULL, 0, 18.341994047165),
(16, 3, 4, NULL, NULL, 'yukyukyuk', 0, 17.48099398613),
(17, 3, 5, 15, 15, NULL, 0, 12.983994007111),
(18, 3, 6, 18, 18, NULL, 0, 42.841460943222),
(19, 4, 2, 8, 7, NULL, 0, 17.816344976425),
(20, 4, 3, 10, 12, NULL, 0, 18.290861129761),
(21, 4, 1, 4, 2, NULL, 0, 20.016861200333),
(22, 4, 4, NULL, NULL, 'gfhgf hgf j', 0, 19.71753692627),
(23, 4, 5, 15, 15, NULL, 0, 11.827926874161),
(24, 4, 6, 19, 18, NULL, 0, 21.165951013565),
(25, 5, 2, 7, 7, NULL, 0, 21.716717004776),
(26, 5, 3, 10, 12, NULL, 0, 15.178997993469),
(27, 5, 1, 3, 2, NULL, 0, 18.344465017319),
(28, 5, 4, NULL, NULL, 'test', 0, 20.472612142563),
(29, 5, 5, 15, 15, NULL, 0, 9.3621830940247),
(30, 5, 6, 18, 18, NULL, 0, 21.158925056458),
(31, 6, 2, 7, 7, NULL, 0, 16.990965843201),
(32, 6, 3, 12, 12, NULL, 0, 896.27776002884),
(33, 6, 1, 4, 2, NULL, 0, 16.856231212616),
(34, 6, 4, NULL, NULL, 'ghgkgku', 0, 59.347924947739),
(35, 6, 5, 15, 15, NULL, 0, 10.702888965607),
(36, 6, 6, 18, 18, NULL, 0, 25.613281965256),
(37, 7, 2, 5, 7, NULL, 0, 16.726602077484),
(38, 7, 3, 12, 12, NULL, 0, 15.326423883438),
(39, 7, 1, 2, 2, NULL, 0, 20.447735071182),
(40, 7, 4, NULL, NULL, 'fgqdfgqdfgqdfg', 0, 18.933749914169),
(41, 7, 5, 15, 15, NULL, 0, 8.7767539024353),
(42, 7, 6, 18, 18, NULL, 0, 21.792012214661),
(43, 8, 2, 7, 7, NULL, 0, 23.40487909317),
(44, 8, 3, 12, 12, NULL, 0, 40.12541103363),
(45, 8, 1, 2, 2, NULL, 0, 68.831844091415),
(46, 8, 4, NULL, NULL, 'AchÃ¨tes du pain !', 0, 82.573885917664),
(47, 8, 5, 15, 15, NULL, 0, 59.455666065216),
(48, 8, 6, 18, 18, NULL, 0, 21.282999992371),
(49, 9, 2, 7, 7, NULL, 0, 20.247846126556),
(50, 9, 3, 10, 12, NULL, 0, 50.036813020706),
(51, 10, 2, 7, 7, NULL, 0, 43.155658006668),
(52, 11, 2, 5, 7, NULL, 0, 17.139585971832),
(53, 12, 2, 7, 7, NULL, 0, 17.046202898026),
(54, 12, 3, 10, 12, NULL, 0, 15.85768699646),
(55, 12, 1, 2, 2, NULL, 0, 16.58636713028),
(56, 12, 4, NULL, NULL, 'rutiryuiyui', 0, 18.44389295578),
(57, 12, 5, 14, 15, NULL, 0, 10.889719009399),
(58, 12, 6, 18, 18, NULL, 0, 21.430675029755),
(59, 14, 2, 7, 7, NULL, 0, 18.437628984451),
(60, 15, 2, 5, 7, NULL, 0, 22.608794927597),
(61, 16, 2, 6, 7, NULL, 0, 20.641770839691),
(62, 18, 2, 6, 7, NULL, 0, 18.612643003464),
(63, 20, 2, 5, 7, NULL, 0, 24.604411840439),
(64, 22, 2, 5, 7, NULL, 0, 61.968185186386),
(65, 23, 2, 5, 7, NULL, 0, 20.945919036865),
(66, 23, 3, 10, 12, NULL, 0, 1471.4485371113),
(67, 24, 2, 6, 7, NULL, 0, 75.769431829453),
(68, 25, 2, 5, 7, NULL, 0, 11.23916220665),
(69, 25, 3, 9, 12, NULL, 0, 177.30858516693),
(70, 25, 1, 1, 2, NULL, 0, 511.68614387512),
(71, 25, 4, NULL, NULL, 'hgfhsgshfghf', 0, 22.102015972137),
(72, 25, 5, 13, 15, NULL, 0, 15.504279136658),
(73, 25, 6, 17, 18, NULL, 0, 23.214214086533),
(74, 26, 2, 6, 7, NULL, 0, 30.970054149628),
(75, 26, 3, 9, 12, NULL, 0, 85.422498941422),
(76, 26, 1, 1, 2, NULL, 0, 30.448047876358),
(77, 26, 4, NULL, NULL, 'dhdfghfdghdf', 0, 25.247476100922),
(78, 26, 5, 14, 15, NULL, 0, 12.664248943329),
(79, 26, 6, 18, 18, NULL, 0, 25.6655189991),
(80, 27, 2, 6, 7, NULL, 0, 20.62442111969),
(81, 28, 2, 5, 7, NULL, 0, 26.074280977249);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id_session` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_posi` varchar(10) NOT NULL DEFAULT '1',
  `ref_user` int(5) unsigned NOT NULL,
  `ref_intervenant` int(5) unsigned DEFAULT NULL,
  `ref_valid_acquis` int(2) unsigned DEFAULT NULL,
  `date_session` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `session_accomplie` tinyint(1) NOT NULL DEFAULT '0',
  `temps_total` double NOT NULL,
  `score_pourcent` int(3) unsigned NOT NULL DEFAULT '0',
  `adresse_ip` varchar(20) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_session`),
  KEY `date_session` (`date_session`),
  KEY `I_FK_session_user` (`ref_user`),
  KEY `I_FK_session_intervenant` (`ref_intervenant`),
  KEY `I_FK_session_acquis` (`ref_valid_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `session`
--

INSERT INTO `session` (`id_session`, `ref_posi`, `ref_user`, `ref_intervenant`, `ref_valid_acquis`, `date_session`, `session_accomplie`, `temps_total`, `score_pourcent`, `adresse_ip`, `user_agent`) VALUES
(1, '1', 1, 1, NULL, '2016-02-15 15:43:42', 1, 144.58358311653, 50, NULL, NULL),
(2, '1', 1, 2, NULL, '2016-02-23 09:26:30', 1, 158.94325089455, 95, NULL, NULL),
(3, '1', 2, 3, NULL, '2016-02-26 15:05:16', 1, 150.84743118286, 92, NULL, NULL),
(4, '1', 3, 4, NULL, '2016-02-29 09:25:13', 1, 108.83548212051, 34, NULL, NULL),
(5, '1', 4, 5, NULL, '2016-02-29 09:29:03', 1, 106.23390030861, 92, NULL, NULL),
(6, '1', 5, 6, NULL, '2016-02-29 09:32:14', 1, 1025.7890529633, 100, NULL, NULL),
(7, '1', 9, 8, NULL, '2016-04-21 11:28:03', 1, 102.00327706337, 92, NULL, NULL),
(8, '1', 10, 9, NULL, '2016-05-03 13:56:32', 1, 295.67468619347, 100, NULL, NULL),
(9, '1', 11, 10, NULL, '2016-05-26 16:34:28', 0, 0, 0, NULL, NULL),
(10, '1', 12, 11, NULL, '2016-05-31 11:35:22', 0, 0, 0, NULL, NULL),
(11, '1', 13, 12, NULL, '2016-05-31 16:02:34', 0, 0, 0, NULL, NULL),
(12, '1', 14, 13, NULL, '2016-06-27 14:22:24', 1, 100.2545440197, 75, NULL, NULL),
(13, '1', 15, 13, NULL, '2016-07-25 13:49:27', 0, 0, 0, NULL, NULL),
(14, '1', 1, 13, NULL, '2016-10-06 11:12:06', 0, 0, 0, NULL, NULL),
(15, '1', 1, 13, NULL, '2016-10-06 12:12:07', 0, 0, 0, NULL, NULL),
(16, '1', 1, 13, NULL, '2016-10-06 12:32:42', 0, 0, 0, NULL, NULL),
(17, '1', 1, 13, NULL, '2016-10-06 13:34:55', 0, 0, 0, NULL, NULL),
(18, '1', 16, 13, NULL, '2016-10-07 11:31:36', 0, 0, 0, NULL, NULL),
(19, '1', 1, 13, NULL, '2016-10-10 10:06:30', 0, 0, 0, NULL, NULL),
(20, '1', 1, 13, NULL, '2016-10-10 10:47:40', 0, 0, 0, NULL, NULL),
(21, '2', 1, 13, NULL, '2016-10-10 11:23:51', 0, 0, 0, NULL, NULL),
(22, '2', 1, 13, NULL, '2016-10-10 11:45:31', 0, 0, 0, NULL, NULL),
(23, '2', 1, 13, NULL, '2016-10-10 13:31:37', 0, 0, 0, NULL, NULL),
(24, '2', 1, 13, NULL, '2016-10-11 10:39:12', 0, 0, 0, NULL, NULL),
(25, '2', 1, 13, NULL, '2016-10-11 10:44:31', 1, 761.05440044403, 17, NULL, NULL),
(26, '2', 1, 13, NULL, '2016-10-11 12:09:39', 1, 210.41784501076, 67, NULL, NULL),
(27, '3', 1, 13, NULL, '2016-10-12 14:30:30', 0, 0, 0, NULL, NULL),
(28, '3', 1, 13, NULL, '2016-10-12 15:59:16', 0, 0, 0, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `ref_niveau`, `nom_user`, `prenom_user`, `date_naiss_user`, `adresse_user`, `code_postal_user`, `ville_user`, `tel_user`, `email_user`, `nbre_sessions_totales`, `nbre_sessions_accomplies`) VALUES
(1, 5, 'BEURION', 'Nicolas', '1974-11-24', NULL, NULL, NULL, NULL, NULL, 16, 4),
(2, 2, 'Durand', 'Jacques', '1969-03-12', NULL, NULL, NULL, NULL, NULL, 1, 1),
(3, 1, 'Lepique', 'Kevin', '1998-04-20', NULL, NULL, NULL, NULL, NULL, 1, 1),
(4, 2, 'Al Zaouiri', 'Djamel', '1995-01-17', NULL, NULL, NULL, NULL, NULL, 1, 1),
(5, 3, 'Delarue', 'Damien', '1990-12-10', NULL, NULL, NULL, NULL, NULL, 1, 1),
(9, 3, 'TEST', 'Test', '1974-11-24', NULL, NULL, NULL, NULL, NULL, 1, 2),
(10, 3, 'RESTITUTION', 'Test', '1995-06-10', NULL, NULL, NULL, NULL, NULL, 1, 1),
(11, 3, 'KJMLQDFJLDFJ', 'sjdfqmljdfj', '2005-02-01', NULL, NULL, NULL, NULL, NULL, 1, 0),
(12, 3, 'TEST', 'image', '2005-07-05', NULL, NULL, NULL, NULL, NULL, 1, 0),
(13, 3, 'TEST', 'ImageCrop', '2000-08-06', NULL, NULL, NULL, NULL, NULL, 1, 0),
(14, 2, 'TEST', 'Video', '1998-07-09', NULL, NULL, NULL, NULL, NULL, 1, 1),
(15, 1, 'VIDEO', 'Test', '2002-02-03', NULL, NULL, NULL, NULL, NULL, 1, 0),
(16, 4, 'TEST', 'Video', '2001-11-03', NULL, NULL, NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `valid_acquis`
--

CREATE TABLE IF NOT EXISTS `valid_acquis` (
  `id_acquis` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_acquis` varchar(100) NOT NULL,
  `descript_acquis` tinytext,
  PRIMARY KEY (`id_acquis`),
  UNIQUE KEY `nom_acquis` (`nom_acquis`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `FK_cat_preco_cat` FOREIGN KEY (`ref_code_cat`) REFERENCES `categorie` (`code_cat`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `FK_preco_parcours_preco` FOREIGN KEY (`ref_parcours`) REFERENCES `parcours_preco` (`id_parcours`) ON DELETE SET NULL;

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
