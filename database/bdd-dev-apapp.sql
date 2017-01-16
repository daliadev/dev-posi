-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 28 Novembre 2016 à 17:00
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `dev_apapp`
--

-- --------------------------------------------------------

--
-- Structure de la table `acces`
--

CREATE TABLE IF NOT EXISTS `acces` (
  `id_acces` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_role` int(10) unsigned DEFAULT NULL,
  `ref_module` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_acces`),
  KEY `acces_ref_role_foreign` (`ref_role`),
  KEY `acces_ref_module_foreign` (`ref_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Contenu de la table `acces`
--

INSERT INTO `acces` (`id_acces`, `ref_role`, `ref_module`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(2, 1, 2, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(3, 1, 3, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(4, 1, 4, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(5, 1, 5, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(6, 1, 6, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(7, 2, 2, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(8, 2, 3, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(9, 2, 4, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(10, 2, 5, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(11, 2, 6, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(12, 3, 4, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(13, 3, 5, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(14, 3, 6, '2016-09-30 11:41:23', '2016-09-30 11:41:23');

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
-- Structure de la table `attribution`
--

CREATE TABLE IF NOT EXISTS `attribution` (
  `id_attribution` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_attributeur` int(10) unsigned NOT NULL,
  `ref_attribue` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_attribution`),
  KEY `attribution_ref_attributeur_foreign` (`ref_attributeur`),
  KEY `attribution_ref_attribue_foreign` (`ref_attribue`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `attribution`
--

INSERT INTO `attribution` (`id_attribution`, `ref_attributeur`, `ref_attribue`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(2, 2, 1, '2016-09-30 11:41:23', '2016-09-30 11:41:23');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `code_cat` varchar(20) NOT NULL,
  `ref_posi` varchar(10) NOT NULL DEFAULT '1',
  `nom_cat` varchar(255) NOT NULL,
  `descript_cat` text,
  PRIMARY KEY (`code_cat`),
  UNIQUE KEY `nom_cat` (`nom_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`code_cat`, `ref_posi`, `nom_cat`, `descript_cat`) VALUES
('10', '1', 'UTILISER LES REGLES DE BASE DE CALCUL ET DU RAISONNEMENT MATHEMATIQUE', ''),
('1010', '1', 'Se repÃ©rer dans l&#39;univers des nombres', ''),
('101010', '1', 'RÃ©aliser un calcul simple', ''),
('101020', '1', 'Compter, dÃ©nombrer', ''),
('101030', '1', 'Comparer, classer, sÃ©rier', ''),
('101040', '1', 'Evaluer un ordre de grandeur', ''),
('101050', '1', 'Utiliser les techniques Ã©lÃ©mentaires du calcul mental', ''),
('101060', '1', 'ContrÃ´ler la cohÃ©rence des rÃ©sultats obtenus', ''),
('101070', '1', 'RÃ©aliser un calcul proportionnel simple', ''),
('1020', '1', 'RÃ©soudre un problÃ¨me mettant en jeu une ou plusieurs opÃ©rations', ''),
('102010', '1', 'RÃ©soudre des problÃ¨mes en utilisant, indiffÃ©remment les 4 opÃ©rations.', ''),
('102020', '1', 'RÃ©soudreÂ desÂ problÃ¨mesÂ enÂ combinantÂ lesÂ opÃ©rations', ''),
('102030', '1', 'RÃ©soudreÂ desÂ problÃ¨mesÂ enÂ utilisantÂ laÂ rÃ¨gleÂ deÂ 3', ''),
('102040', '1', 'ComprendreÂ etÂ utiliserÂ lesÂ pourcentages', ''),
('1030', '1', 'Lire et calculer les unitÃ©s de mesures', ''),
('103010', '1', 'UtiliserÂ lesÂ unitÃ©sÂ deÂ temps.Â  ', ''),
('103020', '1', 'LireÂ etÂ comprendreÂ unÂ planningÂ deÂ travail.', ''),
('103030', '1', 'RenseignerÂ correctementÂ lesÂ horaires.Â ', ''),
('103040', '1', 'UtiliserÂ lesÂ unitÃ©sÂ deÂ mesuresÂ ainsiÂ queÂ lesÂ instrumentsÂ deÂ mesure. ', ''),
('103050', '1', 'Utiliser et comprendre des tableaux, des diagrammes, des graphiques.', ''),
('103060', '1', 'IdentifierÂ lesÂ erreurs.Â ', ''),
('103070', '1', 'EffectuerÂ desÂ calculsÂ simplesÂ deÂ pÃ©rimÃ¨tres,Â surfacesÂ etÂ volumes.Â ', ''),
('1040', '1', 'Se repÃ©rer dans l&#39;espace', ''),
('104010', '1', 'Lire un plan, une carte, unÂ schÃ©ma et en extraire des informations utiles.Â  Â ', ''),
('20', '2', 'COMMUNIQUER EN FRANCAIS', ''),
('2010', '2', 'Ecouter et Comprendre', ''),
('201010', '2', 'Porter attention aux propos tenus', ''),
('201020', '2', 'Savoir poser une question pour comprendre', ''),
('2020', '2', 'S&#39;exprimer Ã  l&#39;oral', ''),
('202010', '2', 'Exprimer un propos en utilisant le lexique professionnel appropriÃ©.', ''),
('202020', '2', 'RÃ©pondre Ã  une question Ã  partir dâ€™un exposÃ© simple.', ''),
('202030', '2', 'Argumenter son point de vue et dÃ©battre de maniÃ¨re constructive.', ''),
('2030', '2', 'Lire', ''),
('203010', '2', 'Lire et comprendre un document usuel professionnel (lettres, consignes, notices...).', ''),
('203020', '2', 'Identifier la nature et la fonction dâ€™un document.', ''),
('203030', '2', 'VÃ©rifier lâ€™authenticitÃ© des informations dâ€™un document par comparaison avec le document original.', ''),
('203040', '2', 'Utiliser les informations dâ€™un tableau Ã  double entrÃ©e.', ''),
('2040', '2', 'Ecrire', ''),
('204010', '2', 'Produire un message en respectant la construction dâ€™une phrase simple.', ''),
('204020', '2', 'Rendre compte par eÌcrit conformeÌment aÌ€ lâ€™objectif viseÌ (renseigner un formulaire simple...).', ''),
('204030', '2', 'Lister par eÌcrit des anomalies dans un document professionnel.', ''),
('204040', '2', 'ReÌcupeÌrer lâ€™essentiel dâ€™un message en prise de notes', ''),
('204050', '2', 'EÌcrire un message en utilisant le vocabulaire professionnel.', ''),
('204060', '2', 'Indiquer par eÌcrit une situation professionnelle, un objet, un probleÌ€me.', ''),
('2050', '2', 'DÃ©crire et formuler', ''),
('205010', '2', 'Transmettre une information, une consigne avec le vocabulaire approprieÌ.', ''),
('205020', '2', 'DeÌcrire par oral une situation professionnelle, un objet, un probleÌ€me.', ''),
('205030', '2', 'Reformuler des informations et consignes.', ''),
('30', '3', 'UTILISER LES TECHNIQUES USUELLES DE Lâ€™INFORMATION ET DE LA COMMUNICATION NUMERIQUE', ''),
('3010', '3', 'Connaitre son environnement et les fonctions de base pour utiliser un ordinateur', ''),
('301010', '3', 'RepeÌrer et nommer dans son environnement de travail', ''),
('301020', '3', 'Mettre un ordinateur en marche, utiliser un clavier, une souris.', ''),
('301030', '3', 'AcceÌder aux fonctions de base : traitement de texte, messagerie eÌlectronique, navigation internet.', ''),
('3020', '3', 'Saisir et mettre en forme du texte - GeÌrer des documents', ''),
('302010', '3', 'Saisir et modifier un texte simple', ''),
('302020', '3', 'CreÌer, enregistrer, deÌplacer des fichiers simples', ''),
('302030', '3', 'Renseigner un formulaire numÃ©rique', ''),
('302040', '3', 'Savoir imprimer un document', ''),
('302050', '3', 'Comprendre la structure du document. ', ''),
('3030', '3', 'Se repeÌrer dans l&#39;environnement internet et effectuer une recherche sur le web', ''),
('303010', '3', 'Utiliser un navigateur pour acceÌder aÌ€ Internet. .', ''),
('303020', '3', 'Se repeÌrer dans une page web', ''),
('303030', '3', 'Utiliser un moteur de recherche.', ''),
('303040', '3', 'Effectuer une requeÌ‚te.', ''),
('303050', '3', 'Analyser la nature des sites proposeÌs par le moteur de recherche.', ''),
('303060', '3', 'Enregistrer les informations.', ''),
('303070', '3', 'Savoir trouver des services en ligne.', ''),
('303080', '3', 'Identifier les sites pratiques ou d&#39;information, lieÌs aÌ€ lâ€™environnement professionnel.', ''),
('3040', '3', 'Utiliser la fonction de messagerie ', ''),
('304010', '3', 'Utiliser et geÌrer une messagerie et un fichier contacts.', ''),
('304020', '3', 'Ouvrir et fermer un courriel ou un document attacheÌ', ''),
('304030', '3', 'CreÌer, eÌcrire un courriel et lâ€™envoyer.', ''),
('304040', '3', 'Ouvrir, inseÌrer une pieÌ€ce jointe', ''),
('40', '4', 'MAITRISER LES GESTES ET POSTURES, ET RESPECTER DES REGLES Dâ€™HYGIENE, DE SECURITE ET ENVIRONNEMENTALES ELEMENTAIRES', ''),
('4010', '4', 'Respecter un reÌ€glement seÌcuriteÌ, hygieÌ€ne, environnement, une proceÌdure qualiteÌ', ''),
('401010', '4', 'Connaitre et expliciter les consignes et pictogrammes de seÌcuriteÌ.', ''),
('401020', '4', 'Appliquer un reÌ€glement, une proceÌdure en matieÌ€re dâ€™hygieÌ€ne, de seÌcuriteÌ, de qualiteÌ et dâ€™environnement.', ''),
('401030', '4', 'Appliquer les reÌ€gles de seÌcuriteÌ dans toute intervention.', ''),
('4020', '4', 'Avoir les bons gestes et reflexes afin dâ€™eÌviter les risques', ''),
('402010', '4', 'MaiÌ‚triser les automatismes gestuels du meÌtier.', ''),
('402020', '4', 'Adopter les gestes et postures adapteÌs aux diffeÌrentes situations afin dâ€™eÌviter les douleurs et meÌnager son corps.', ''),
('402030', '4', 'Se proteÌger avec les eÌquipements adeÌquats et selon les reÌ€gles transmises.', ''),
('402040', '4', 'Connaitre et appliquer les reÌ€gles de deÌplacement de charges.', ''),
('402050', '4', 'Identifier un dysfonctionnement dans son peÌrimeÌ€tre dâ€™activiteÌ ainsi que les risques associeÌs sâ€™il y a lieu.', ''),
('402060', '4', 'Alerter les interlocuteurs concerneÌs par les dysfonction- nements et les risques constateÌ', ''),
('4030', '4', 'EÌ‚tre capable dâ€™appliquer les gestes de premier secours', ''),
('403010', '4', 'MaÃ®triser les gestes de premiers secours', ''),
('403020', '4', 'ReÌagir de manieÌ€re adapteÌe aÌ€ une situation dangereuse.', ''),
('403030', '4', 'Identifier le bon interlocuteur aÌ€ alerter selon les situations les plus courantes', ''),
('4040', '4', 'Contribuer Ã  la prÃ©servation de lâ€™environnement et aux Ã©conomies dâ€™Ã©nergie', ''),
('404010', '4', 'Appliquer les reÌ€gles de gestion des deÌchets. Respecter les reÌ€gles eÌleÌmentaires de recyclage.', ''),
('404020', '4', 'Faire un usage optimal des installations et des eÌquipements en termes dâ€™eÌconomie dâ€™eÌnergie.', ''),
('404030', '4', 'Choisir et utiliser de manieÌ€re adapteÌe les produits dâ€™usage courant (papeterie, entretien...).', ''),
('404040', '4', 'Proposer des actions de nature aÌ€ favoriser le deÌveloppement durable', ''),
('50', '5', 'TRAVAILLER EN AUTONOMIE ET REALISER UN OBJECTIF INDIVIDUEL', ''),
('5010', '5', 'Comprendre son environnement de travail', ''),
('501010', '5', 'Analyser des situations simples, des relations, son environnement de travail.', ''),
('501020', '5', 'Solliciter une assistance.', ''),
('501030', '5', 'Rechercher, traiter, transmettre des informations techniques simples', ''),
('5020', '5', 'ReÌaliser des objectifs individuels dans le cadre dâ€™une action simple ou dâ€™un projet', ''),
('502010', '5', 'Mettre en Å“uvre une action', ''),
('502020', '5', 'Organiser son temps et planifier lâ€™action', ''),
('502030', '5', ' Identifier les principales eÌtapes, les meÌthodes de travail adapteÌes, aÌ€ utiliser ', ''),
('502040', '5', 'Identifier les principales prioritÃ©s, contraintes et difficultÃ©s', ''),
('502050', '5', 'Consulter les personnes ressources.', ''),
('502060', '5', 'PreÌsenter les reÌsultats de lâ€™action.', ''),
('5030', '5', 'Prendre des initiatives et eÌ‚tre force de proposition', ''),
('503010', '5', 'Aller chercher des informations', ''),
('503020', '5', 'Faire face aÌ€ un aleÌa courant', ''),
('503030', '5', 'Proposer des ameÌliorations dans son champ dâ€™activiteÌ', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `degre`
--

CREATE TABLE IF NOT EXISTS `degre` (
  `id_degre` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_degre` varchar(100) NOT NULL,
  `descript_degre` text,
  PRIMARY KEY (`id_degre`),
  UNIQUE KEY `nom_degre` (`nom_degre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
  `id_inscription` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_user` int(10) unsigned NOT NULL,
  `ref_intervenant` int(10) unsigned DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_inscription`),
  KEY `inscription_ref_user_foreign` (`ref_user`),
  KEY `inscription_ref_intervenant_foreign` (`ref_intervenant`),
  KEY `inscription_date_inscription_index` (`date_inscription`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Structure de la table `intervenant`
--

CREATE TABLE IF NOT EXISTS `intervenant` (
  `id_intervenant` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_organ` int(10) unsigned DEFAULT NULL,
  `ref_role` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `forname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom_intervenant` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_intervenant` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel_intervenant` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_intervenant`),
  UNIQUE KEY `intervenant_email_intervenant_unique` (`email_intervenant`),
  KEY `intervenant_ref_role_foreign` (`ref_role`),
  KEY `intervenant_ref_organ_foreign` (`ref_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `intervenant`
--

INSERT INTO `intervenant` (`id_intervenant`, `ref_organ`, `ref_role`, `name`, `forname`, `password`, `nom_intervenant`, `email_intervenant`, `tel_intervenant`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Lacomblez', 'Marc', '$2y$10$x7WxnQC6JRE38Ln0LUmVyu34RmVkQuuPIF32jplWKugxifsIQiDKu', NULL, 'marc.lacomblez@viacesi.fr', NULL, NULL, '2016-09-30 11:41:22', '2016-09-30 11:41:22'),
(2, 1, 1, 'Beurion', 'Nicolas', '$2y$10$vAaUkMKYQbQUk8SSKsXdWeepXPH9S5NNVj1Vnka2rr9zZ8UQ7JabS', NULL, 'n.beurion@education-et-formation.fr', NULL, NULL, '2016-09-30 11:41:22', '2016-09-30 11:41:22'),
(3, 2, 2, 'Administrateur type', 'ApAPP', '$2y$10$yKC/YS2IUUq5kDZpJTthKuNpM560EJlZh1BoxduIjH8KK6n/.3Opm', NULL, 'admin@app.fr', NULL, NULL, '2016-09-30 11:41:23', '2016-09-30 11:41:23'),
(4, 2, 3, 'Evaluateur type', 'ApAPP', '$2y$10$KHQnC2Yfct3pJiW8QlKbTuiXSvjf/RWdk2mBv24BuEdCCPfA5fbjK', NULL, 'eval@app.fr', NULL, NULL, '2016-09-30 11:41:23', '2016-09-30 11:41:23');

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id_module` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_module` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icone_module` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `niveau_role_requis` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_module`),
  UNIQUE KEY `module_nom_module_unique` (`nom_module`),
  UNIQUE KEY `module_url_module_unique` (`url_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `module`
--

INSERT INTO `module` (`id_module`, `nom_module`, `url_module`, `icone_module`, `niveau_role_requis`) VALUES
(1, 'Gestion des comptes', 'admin/comptes', 'ic_account.svg', 120),
(2, 'Gestion des organismes', 'admin/organismes/liste', 'ic_chat.svg', 100),
(3, 'Gestion des évaluateurs', 'admin/comptes_eval/liste', 'ic_supervisor.svg', 100),
(4, 'Evaluation', 'eval/inscription', 'ic_desktop.svg', 50),
(5, 'Restitution', 'http://positionnement.educationetformation.fr/clea/gestion/public/restitution', 'ic_timeline.svg', 100),
(6, 'Statistiques', 'http://positionnement.educationetformation.fr/clea/gestion/public/statistique', 'ic_trending.svg', 100),
(7, 'Restitution par évaluateur', 'admin/rest_eval', 'ic_view.svg', 50);

-- --------------------------------------------------------

--
-- Structure de la table `niveau_etudes`
--

CREATE TABLE IF NOT EXISTS `niveau_etudes` (
  `id_niveau` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_niveau` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descript_niveau` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_niveau`),
  UNIQUE KEY `niveau_etudes_nom_niveau_unique` (`nom_niveau`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `niveau_etudes`
--

INSERT INTO `niveau_etudes` (`id_niveau`, `nom_niveau`, `descript_niveau`) VALUES
(1, 'Niveau VI et Vbis : abandon CAP - BEP - 3e', 'Sorties en cours de 1er cycle de l''enseignement secondaire (6ème à 3ème) ou abandons en cours de CAP ou BEP avant l''année terminale.'),
(2, 'Niveau V : CAP - BEP - 2e cycle', 'Sorties après l''année terminale de CAP ou BEP ou sorties de 2nd cycle général et technologique avant l''année terminale (seconde, première ou terminale).'),
(3, 'Niveau IV : Bac', 'Sorties des classes de terminale de l''enseignement secondaire (avec le baccalauréat). Abandons des études supérieures.'),
(4, 'Niveau III : Bac+2', 'Sorties avec un diplôme de niveau Bac + 2 ans (DUT, BTS, DEUG, écoles des formations sanitaires ou sociales, etc...).'),
(5, 'Niveau II : Bac+3, bac+4', 'Sorties avec un diplôme de niveau bac+3 à bac+4 (licence, maîtrise, master I).'),
(6, 'Niveau I : Bac+5 et plus', 'Sorties avec un diplôme de niveau bac+5 et + (master II, DEA, DESS, doctorat, diplôme de grande école).');

-- --------------------------------------------------------

--
-- Structure de la table `organisme`
--

CREATE TABLE IF NOT EXISTS `organisme` (
  `id_organ` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `numero_interne` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_organ` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_organ` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_postal_organ` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ville_organ` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_organ` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_organ` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nbre_posi_total` int(10) unsigned NOT NULL DEFAULT '0',
  `nbre_posi_max` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_organ`),
  UNIQUE KEY `organisme_nom_organ_unique` (`nom_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `organisme`
--

INSERT INTO `organisme` (`id_organ`, `numero_interne`, `nom_organ`, `adresse_organ`, `code_postal_organ`, `ville_organ`, `tel_organ`, `email_organ`, `nbre_posi_total`, `nbre_posi_max`) VALUES
(1, 'f5be2ca6', 'Dalia', NULL, '76000', 'Petit-Quevilly', '00 00 00 00 00', NULL, 0, 0),
(2, 'f5be2c82', 'apAPP', NULL, '76000', NULL, '00 00 00 00 00', NULL, 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `parcours_preco`
--

INSERT INTO `parcours_preco` (`id_parcours`, `volume_parcours`, `nom_parcours`, `descript_parcours`) VALUES
(1, 0, 'Aucune préconisation requise', NULL),
(2, 10, '10 heures de formations', NULL),
(3, 20, '20 heures de formations', NULL),
(4, 30, '30 heures de formations', NULL),
(5, 40, '40 heures de formations', NULL),
(6, 50, '50 heures de formations', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `positionnement`
--

CREATE TABLE IF NOT EXISTS `positionnement` (
  `id_posi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_posi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lien_posi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descript_posi` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id_posi`),
  UNIQUE KEY `positionnement_nom_posi_unique` (`nom_posi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `positionnement`
--

INSERT INTO `positionnement` (`id_posi`, `nom_posi`, `lien_posi`, `descript_posi`) VALUES
(1, 'Domaine 1', 'http://positionnement.educationetformation.fr/clea/domaine1/', 'Utiliser les règles de base de calcul et du raisonnement mathématique'),
(2, 'Domaine 2', 'http://positionnement.educationetformation.fr/clea/domaine2/', 'Communiquer en français'),
(3, 'Domaine 3', 'http://positionnement.educationetformation.fr/clea/domaine3/', 'Utiliser les techniques usuelles de l''information et de la communication numérique'),
(4, 'Domaine 7', 'http://positionnement.educationetformation.fr/clea/domaine7/', 'Maîtriser les gestes et postures, et respecter des régles d''hygiène, de sécurité et environnementales élémentaires'),
(5, 'Domaine 5', 'http://positionnement.educationetformation.fr/clea/domaine5/', 'Travailler en autonomie et réaliser un objectif individuel');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  `intitule_question` text NOT NULL,
  `image_question` varchar(255) DEFAULT NULL,
  `audio_question` varchar(255) DEFAULT NULL,
  `video_question` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_question`),
  KEY `type_question` (`type_question`),
  KEY `I_FK_question_degre` (`ref_degre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id_question`, `ref_posi`, `ref_degre`, `num_ordre_question`, `type_question`, `intitule_question`, `image_question`, `audio_question`, `video_question`) VALUES
(34, '2', NULL, 1, 'qcm', 'Quel est lâ€™objet de cet appel ? ', 'img_34_5767d78f110a2.jpg', 'audio_34_574da57bc5bc3.mp3', NULL),
(35, '2', NULL, 2, 'champ_saisie', 'Ecrivez un mail (ci-dessous) Ã  Mme Corot pour connaÃ®tre  les disponibilitÃ©s des 2 chauffeurs ? ', 'img_35_5767d7d08b620.jpg', 'audio_35_5750310e0aba8.mp3', NULL),
(36, '2', NULL, 3, 'qcm', 'Parmi ces documents lequel choisissez-vous pour rÃ©pondre Ã  la demande de Camille Corot ?', NULL, 'audio_36_5750382315791.mp3', 'video_36_57504b08b189f.mp4'),
(37, '2', NULL, 4, 'qcm', 'A qui s&#39;adresse cette note de service ?', 'img_37_5776608504d51.png', 'audio_37_57503938cdf6b.mp3', NULL),
(38, '2', NULL, 5, 'champ_saisie', 'Entre le document 1 que vous avez rempli et envoyÃ© et le document 2  que l&#39;on vous a remis vous constatez des erreurs.', NULL, 'audio_38_57503b0103eb4.mp3', 'video_5_57fcb8089d14b.mp4'),
(39, '2', NULL, 6, 'qcm', 'A partir du planning, retrouvez parmi ces propositions laquelle est vrai :', 'img_39_57860ce383d3c.jpg', 'audio_39_57503bd25012b.mp3', NULL),
(40, '2', NULL, 7, 'champ_saisie', 'Le gÃ©rant vous demande de faire un rapide compte-rendu sur le dÃ©roulement de l&#39;organisation du dÃ©part Ã  la retraite de Madame Plantu. Il souhaite en particulier savoir pourquoi vous avez besoin d&#39;un vÃ©hicule, dans quelle conditions et qui est concernÃ©.', 'img_40_5767d861a4963.jpg', 'audio_40_57503c5cad6a1.mp3', NULL),
(41, '2', NULL, 8, 'qcm', 'Que devez vous inscrire dans le champ EmployÃ© ?', 'img_8_57ff99d97b015.jpg', 'audio_41_57503de43ca8b.mp3', NULL),
(42, '2', NULL, 9, 'champ_saisie', 'DÃ©crivez la situation prÃ©sentÃ©e sur cette image avec le maximum de prÃ©cision.', 'img_42_577e4920aa0cb.jpg', 'audio_42_57503e0e6fffe.mp3', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Contenu de la table `question_cat`
--

INSERT INTO `question_cat` (`id_question_cat`, `ref_question`, `ref_cat`) VALUES
(34, 34, '201010'),
(35, 35, '201020'),
(36, 36, '203010'),
(37, 37, '203020'),
(38, 38, '203030'),
(39, 39, '203040'),
(40, 40, '204010'),
(41, 41, '204020'),
(42, 42, '204060');

-- --------------------------------------------------------

--
-- Structure de la table `reponse`
--

CREATE TABLE IF NOT EXISTS `reponse` (
  `id_reponse` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_question` int(5) unsigned NOT NULL,
  `num_ordre_reponse` tinyint(3) unsigned NOT NULL,
  `intitule_reponse` text NOT NULL,
  `est_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_reponse`),
  KEY `num_ordre_reponse` (`num_ordre_reponse`),
  KEY `I_FK_reponse_question` (`ref_question`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=200 ;

--
-- Contenu de la table `reponse`
--

INSERT INTO `reponse` (`id_reponse`, `ref_question`, `num_ordre_reponse`, `intitule_reponse`, `est_correct`) VALUES
(106, 34, 1, 'la mise Ã  disposition de matÃ©riel', 0),
(107, 34, 2, 'le dÃ©chargement du matÃ©riel', 0),
(108, 34, 3, 'l&#39;utilisation d&#39;un vÃ©hicule', 1),
(109, 36, 1, 'Document 1', 0),
(110, 36, 2, 'Document 2', 1),
(111, 36, 3, 'Document 3', 0),
(112, 36, 4, 'Document 4', 0),
(113, 37, 1, 'Marianna Gomis', 0),
(114, 37, 2, 'Responsable Production et Logistique', 0),
(115, 37, 3, 'L&#39;ensemble des services ', 1),
(116, 37, 4, 'Le service rÃ©servation de vÃ©hicules', 0),
(117, 39, 1, 'Michel finit Ã  17h30 le mardi', 0),
(118, 39, 2, 'Michel et Isabelle ne travaillent par le mercredi', 0),
(119, 39, 3, 'Isabelle commence tous les jours Ã  8 heures', 1),
(120, 39, 4, 'Michel et Isabelle travaillent le jeudi', 0),
(121, 41, 1, 'Camille Corot', 0),
(122, 41, 2, 'Vous', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `niveau_role` int(10) unsigned NOT NULL DEFAULT '0',
  `desc_role` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `role_nom_role_unique` (`nom_role`),
  UNIQUE KEY `role_slug_role_unique` (`slug_role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `role`
--

INSERT INTO `role` (`id_role`, `nom_role`, `slug_role`, `niveau_role`, `desc_role`) VALUES
(1, 'Super administrateur', 'SUPER', 120, 'Role des administrateur de l''application'),
(2, 'Administrateur', 'ADMIN', 100, 'Role des administrateurs certifiés'),
(3, 'Evaluateur', 'EVAL', 50, 'Role des evaluateurs certifiés'),
(4, 'Essai', 'ESSAI', 5, 'Role provisoire à l''enregistrement'),
(5, 'Invalidé', 'NULL', 0, 'Role spéficiant la paralysie du compte');

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id_session` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_user` int(10) unsigned NOT NULL,
  `ref_intervenant` int(10) unsigned DEFAULT NULL,
  `ref_posi` int(10) unsigned DEFAULT NULL,
  `ref_valid_acquis` int(10) unsigned DEFAULT NULL,
  `date_session` datetime NOT NULL,
  `session_accomplie` tinyint(1) NOT NULL,
  `temps_total` double unsigned NOT NULL,
  `score_pourcent` int(10) unsigned NOT NULL DEFAULT '0',
  `adresse_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_session`),
  KEY `session_ref_user_foreign` (`ref_user`),
  KEY `session_ref_intervenant_foreign` (`ref_intervenant`),
  KEY `session_ref_posi_foreign` (`ref_posi`),
  KEY `I_FK_session_acquis` (`ref_valid_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ref_niveau` int(10) unsigned DEFAULT NULL,
  `num_dossier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nom_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom_user` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_naiss_user` date NOT NULL,
  `adresse_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_postal_user` char(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ville_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tel_user` char(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_user` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nbre_sessions_totales` int(10) unsigned NOT NULL DEFAULT '0',
  `nbre_sessions_accomplies` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `utilisateur_num_dossier_unique` (`num_dossier`),
  KEY `utilisateur_ref_niveau_foreign` (`ref_niveau`),
  KEY `utilisateur_nom_user_index` (`nom_user`),
  KEY `utilisateur_prenom_user_index` (`prenom_user`),
  KEY `utilisateur_date_naiss_user_index` (`date_naiss_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `ref_niveau`, `num_dossier`, `nom_user`, `prenom_user`, `date_naiss_user`, `adresse_user`, `code_postal_user`, `ville_user`, `tel_user`, `email_user`, `nbre_sessions_totales`, `nbre_sessions_accomplies`, `created_at`, `updated_at`) VALUES
(1, 3, '1996BC', 'Farret', 'Xavier', '1995-10-05', NULL, NULL, NULL, NULL, NULL, 0, 0, '2016-09-30 11:41:22', '2016-09-30 11:41:22'),
(2, 4, '1964AG', 'Deher', 'Jean', '1995-12-06', NULL, NULL, NULL, NULL, NULL, 0, 0, '2016-09-30 11:41:22', '2016-09-30 11:41:22');

-- --------------------------------------------------------

--
-- Structure de la table `valid_acquis`
--

CREATE TABLE IF NOT EXISTS `valid_acquis` (
  `id_acquis` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nom_acquis` varchar(100) NOT NULL,
  `descript_acquis` text,
  PRIMARY KEY (`id_acquis`),
  UNIQUE KEY `nom_acquis` (`nom_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
-- Contraintes pour la table `acces`
--
ALTER TABLE `acces`
  ADD CONSTRAINT `acces_ref_module_foreign` FOREIGN KEY (`ref_module`) REFERENCES `module` (`id_module`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `acces_ref_role_foreign` FOREIGN KEY (`ref_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `attribution`
--
ALTER TABLE `attribution`
  ADD CONSTRAINT `attribution_ref_attribue_foreign` FOREIGN KEY (`ref_attribue`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attribution_ref_attributeur_foreign` FOREIGN KEY (`ref_attributeur`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `inscription_ref_intervenant_foreign` FOREIGN KEY (`ref_intervenant`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE SET NULL,
  ADD CONSTRAINT `inscription_ref_user_foreign` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `intervenant`
--
ALTER TABLE `intervenant`
  ADD CONSTRAINT `intervenant_ref_organ_foreign` FOREIGN KEY (`ref_organ`) REFERENCES `organisme` (`id_organ`) ON DELETE SET NULL,
  ADD CONSTRAINT `intervenant_ref_role_foreign` FOREIGN KEY (`ref_role`) REFERENCES `role` (`id_role`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `session_ref_intervenant_foreign` FOREIGN KEY (`ref_intervenant`) REFERENCES `intervenant` (`id_intervenant`) ON DELETE SET NULL,
  ADD CONSTRAINT `session_ref_posi_foreign` FOREIGN KEY (`ref_posi`) REFERENCES `positionnement` (`id_posi`) ON DELETE SET NULL,
  ADD CONSTRAINT `session_ref_user_foreign` FOREIGN KEY (`ref_user`) REFERENCES `utilisateur` (`id_user`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ref_niveau_foreign` FOREIGN KEY (`ref_niveau`) REFERENCES `niveau_etudes` (`id_niveau`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
