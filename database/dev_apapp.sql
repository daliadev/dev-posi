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
  `num_interne` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
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

INSERT INTO `organisme` (`id_organ`, `num_interne`, `nom_organ`, `adresse_organ`, `code_postal_organ`, `ville_organ`, `tel_organ`, `email_organ`, `nbre_posi_total`, `nbre_posi_max`) VALUES
(1, 'f5be2ca6', 'Dalia', NULL, '76000', 'Petit-Quevilly', '00 00 00 00 00', NULL, 0, 0),
(2, 'f5be2c82', 'apAPP', NULL, '76000', NULL, '00 00 00 00 00', NULL, 0, 0),
(5, '4659', 'kjoiuoiijoijo', '159, ghsghshfghs', '', 'Kqfgqdgf', '0123456789', 'qdfgqdfg@qdfgqdf.fr', 0, 0),
(7, 'edf654655465app', 'ApAPP - Rouen gauche', '15, rue qdfhqdhdhg', '76154', 'Rouen', '0235695874', 'app-rouen@app.fr', 0, 0);

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
(2, '1', NULL, 2, 'qcm', 'Calculez l&#39;opÃ©ration suivante :648 - 315 =', 'img_2_5751515389404.jpg', 'audio_2_5743084023ec3.mp3', NULL),
(3, '1', NULL, 3, 'qcm', 'Calculez l&#39;opÃ©ration suivante :24 X 17 = ', 'img_3_5751516be7a1e.jpg', 'audio_3_5743084021e4d.mp3', NULL),
(4, '1', NULL, 4, 'qcm', 'Calculez l&#39;opÃ©ration suivante :408 : 17 =', 'img_4_5751518311d4b.jpg', 'audio_4_574308401fe15.mp3', NULL),
(5, '1', NULL, 5, 'qcm', 'Combien de personnes sont dans cette salle ?', 'img_5_57502540319c6.png', 'audio_5_574308401d0ba.mp3', NULL),
(6, '1', NULL, 1, 'qcm', 'Calculez l&#39;opÃ©ration suivante : 325 + 132 ', 'img_1_57514ce232127.jpg', 'audio_1_57430840aa758.mp3', NULL),
(7, '1', NULL, 6, 'qcm', 'Voici 4 nombres. Vous devez les ranger dans l&#39;ordre croissant.24,56 - 24,47 - 25,34 - 23,6', 'img_6_575151b42d5f7.jpg', 'audio_6_5745b7748dd26.mp3', NULL),
(8, '1', NULL, 7, 'qcm', 'Sans poser l&#39;opÃ©ration et sans calculette estimez l&#39;ordre de grandeur de cette opÃ©ration :25 + 32 + 48 ', 'img_7_575151c80158d.jpg', 'audio_7_5746b47638ce4.mp3', NULL),
(9, '1', NULL, 8, 'qcm', 'Sans poser l&#39;opÃ©ration et sans calculette estimez l&#39;ordre de grandeur de cette opÃ©ration : 26 x 101. Quel est le nombre le plus proche du rÃ©sultat de cette opÃ©ration :', 'img_8_5767bb0671a0c.jpg', 'audio_8_5745ba8b4ea28.mp3', NULL),
(10, '1', NULL, 9, 'qcm', 'Pour multiplier par 10  le nombre 35,46 sans poser l&#39;opÃ©ration :', 'img_9_575151e17d30e.jpg', 'audio_9_5745c36b4d2d1.mp3', NULL),
(11, '1', NULL, 10, 'qcm', 'Pour parcourir 100 mÃ¨tres, un chariot Ã©lÃ©vateur met environ 40 secondes.A votre avis quelle est sa vitesse :', 'img_10_574eb5e896daf.png', 'audio_10_5745c5e2aa462.mp3', NULL),
(12, '1', NULL, 11, 'qcm', 'Pour poser une terrasse de 30 m2 il vous faut environ 1 m3 de bÃ©ton. La proportion des diffÃ©rents Ã©lÃ©ments est la suivante : 320 kg de ciment 158 litres d&#39;eau, 700 kg de sable, 1300 kg de gravier. Calculez la quantitÃ© nÃ©cessaire de chaque Ã©lÃ©ment pour poser une terrasse de 45 mÂ².  ', 'img_11_5755364f99c1c.jpg', 'audio_11_5745c6c8cc5ca.mp3', NULL),
(13, '1', NULL, 12, 'qcm', 'Pour vous rendre sur votre lieu de travail vous avez utilisÃ© le bus ce matin. Vous Ãªtes montÃ© dans le bus avec deux personnes. Quatre personnes Ã©taient dÃ©jÃ  installÃ©es. A la station suivante trois autres sont montÃ©es. Combien Ãªtes vous de passagers maintenant ?', 'img_12_57500433a869d.jpg', 'audio_12_5745c9cb16c54.mp3', NULL),
(14, '1', NULL, 13, 'qcm', 'AprÃ¨s quelques stations vous Ãªtes maintenant 32 personnes. A la station suivante 8 descendent. Combien Ãªtes-vous ?', 'img_13_575004597ba7e.jpg', 'audio_13_5745ca6401f1f.mp3', NULL),
(15, '1', NULL, 15, 'qcm', '207 personnes ont voyagÃ© ce matin dans le bus. Un tiers Ã©tait des jeunes se rendant au lycÃ©e. Combien Ã©taient-ils ?', 'img_15_575537a9dd174.jpg', 'audio_15_574d3e6ac1c22.mp3', NULL),
(16, '1', NULL, 16, 'qcm', 'A lâ€™Ã©cole de Bonnes,  les enfants mangent des yaourts chaque midi.72 enfants mangent Ã  la cantine le lundi. 6 de moins le mardi. 68 le mercredi. 74 le jeudi. Autant le vendredi. Les yaourts sont vendus en cartons de 8. Cherchez combien la cantiniÃ¨re doit commander de cartons chaque semaine pour les enfants qui mangent Ã  la cantine. ', 'img_16_57553ed697128.jpg', 'audio_16_574d3e6abec5d.mp3', NULL),
(17, '1', NULL, 17, 'qcm', 'La machine sur laquelle vous travaillez produit 7 articles en 20 secondes. Combien en produit-elle en 1 minute ?', 'img_17_575540b1a86b9.jpg', 'audio_17_574d3e6abbb00.mp3', NULL),
(18, '1', NULL, 18, 'qcm', 'Vous avez choisi l&#39;aspirateur ASPIRO. Il est de niveau B pour sa consommation d&#39;Ã©nergie.Sa consommation est de 30,1 kw/an. Si vous aviez choisi l&#39;aspirateur TORNADE de niveau A vous auriez pu faire une Ã©conomie de 8 % sur la consommation. Quelle aurait Ã©tÃ© cette consommation ?', 'img_18_57554473e378d.jpg', 'audio_18_574d3e6ab492f.mp3', NULL),
(19, '1', NULL, 19, 'qcm', 'Pour vous rendre de votre domicile Ã  votre lieu de travail vous mettez en gÃ©nÃ©ral 20 minutes avec le bus et 8 minutes de marche Ã  pied. Pour Ãªtre Ã  votre poste Ã  8h15 Ã  quelle heure devez partir de chez vous au plus tard :', 'img_19_5767c14ebc9c8.jpg', 'audio_19_574d3e6ab17c5.mp3', NULL),
(20, '1', NULL, 20, 'qcm', 'En utilisant le planning de cette semaine, quels sont vos horaires de travail le jeudi :', 'img_20_574eb241db567.jpg', 'audio_20_574d3e6aa9571.mp3', NULL),
(21, '1', NULL, 21, 'qcm', 'Vous avez travaillÃ© chez Madame Plantu le vendredi entre 9H30 et 11H30. OÃ¹ allez vous Ã©crire sur ce planning votre intervention ?', 'img_21_57fdf3bb64e1d.jpg', 'audio_21_574d3e6aa65a5.mp3', NULL),
(22, '1', NULL, 22, 'qcm', 'A l&#39;aide des tableaux ci-dessus convertissez la mesure suivante : 10 centimÃ¨tres en mÃ¨tres', 'img_22_574eb1d36e127.jpg', 'audio_22_574d3e6aa343f.mp3', NULL),
(23, '1', NULL, 23, 'qcm', 'A l&#39;aide des tableaux ci-dessus convertissez la mesure suivante : 2,5 litres en centilitres', 'img_23_574eb1c2b12fd.jpg', 'audio_23_574d3e6aa0439.mp3', NULL),
(24, '1', NULL, 24, 'qcm', 'A l&#39;aide des tableaux ci-dessus convertissez les mesures suivantes : 1,750 kg en grammes', 'img_24_574eb1b16215f.jpg', 'audio_24_574d3e6a83c0c.mp3', NULL),
(25, '1', NULL, 25, 'qcm', 'Parmi ces trois reprÃ©sentations de donnÃ©es laquelle ne reproduit pas les informations du tableau des salariÃ©s.', 'img_25_57556820ecc68.jpg', 'audio_25_574d3e6a80b2b.mp3', NULL),
(26, '1', NULL, 26, 'champ_saisie', 'En recopiant les numÃ©ros de tÃ©lÃ©phone sur le tableau 2, Madame Plantu a fait plusieurs erreurs. Ecrivez les numÃ©ros sur lesquels Madame Plantu a fait des erreurs.', 'img_26_575570e443921.jpg', 'audio_26_574d3e6a7dae0.mp3', NULL),
(27, '1', NULL, 27, 'qcm', 'Quel est le pÃ©rimÃ¨tre de cette piÃ¨ce qui a pour longueur = 4,20 m largeur = 3,50 m ?  ', 'img_27_575547b60be21.jpg', 'audio_27_574d3e6a7a8c7.mp3', NULL),
(28, '1', NULL, 28, 'qcm', 'Quelle est la surface de ce terrain qui a pour longueur 12,50 met largeur 5,70 m ?', 'img_28_575570041f9aa.jpg', NULL, NULL),
(29, '1', NULL, 29, 'qcm', 'Quel est le volume de ce conteneur qui a pour  longueur = 6,30 m, largeur = 2,50 mÃ¨tre et  hauteur = 2,10 mÃ¨tre ?', 'img_29_5755701b6b6af.jpg', 'audio_29_574d3e6a74724.mp3', NULL),
(30, '1', NULL, 30, 'qcm', 'A partir de la Gare Part-Dieu pouvez-vous vous rendre Ã  l&#39;aÃ©roport avec les transports en commun ?', 'img_30_574eb185bcee2.jpg', 'audio_30_574d3e6a7184a.mp3', NULL),
(31, '1', NULL, 31, 'qcm', 'Combien de lignes de mÃ©tro existent sur Lyon ?', 'img_31_574eb16f194fc.jpg', 'audio_31_574d3e6a6a721.mp3', NULL),
(32, '1', NULL, 32, 'champ_saisie', 'Vous Ãªtes Ã  la station RÃ©publique. Vous devez vous rendre station Garibaldi avec les transports en commun.Indiquez prÃ©cisÃ©ment le trajet que vous devez effectuer.', 'img_32_574eb1551676c.jpg', 'audio_32_574d3e6a56abb.mp3', NULL),
(33, '1', NULL, 14, 'qcm', 'Au dÃ©part du bus il y avait 23 peronnes. Au cours de son parcours le nombre de personnes a Ã©tÃ© multipliÃ© par 9. Combien de voyageurs a-t-il transportÃ© ?', 'img_14_57500483cc5c3.jpg', 'audio_14_574d3e6b0e54a.mp3', NULL),
(34, '2', NULL, 1, 'qcm', 'Quel est lâ€™objet de cet appel ? ', 'img_34_5767d78f110a2.jpg', 'audio_34_574da57bc5bc3.mp3', NULL),
(35, '2', NULL, 2, 'champ_saisie', 'Ecrivez un mail (ci-dessous) Ã  Mme Corot pour connaÃ®tre  les disponibilitÃ©s des 2 chauffeurs ? ', 'img_35_5767d7d08b620.jpg', 'audio_35_5750310e0aba8.mp3', NULL),
(36, '2', NULL, 3, 'qcm', 'Parmi ces documents lequel choisissez-vous pour rÃ©pondre Ã  la demande de Camille Corot ?', NULL, 'audio_36_5750382315791.mp3', 'video_36_57504b08b189f.mp4'),
(37, '2', NULL, 4, 'qcm', 'A qui s&#39;adresse cette note de service ?', 'img_37_5776608504d51.png', 'audio_37_57503938cdf6b.mp3', NULL),
(38, '2', NULL, 5, 'champ_saisie', 'Entre le document 1 que vous avez rempli et envoyÃ© et le document 2  que l&#39;on vous a remis vous constatez des erreurs.', NULL, 'audio_38_57503b0103eb4.mp3', 'video_5_57fcb8089d14b.mp4'),
(39, '2', NULL, 6, 'qcm', 'A partir du planning, retrouvez parmi ces propositions laquelle est vrai :', 'img_39_57860ce383d3c.jpg', 'audio_39_57503bd25012b.mp3', NULL),
(40, '2', NULL, 7, 'champ_saisie', 'Le gÃ©rant vous demande de faire un rapide compte-rendu sur le dÃ©roulement de l&#39;organisation du dÃ©part Ã  la retraite de Madame Plantu. Il souhaite en particulier savoir pourquoi vous avez besoin d&#39;un vÃ©hicule, dans quelle conditions et qui est concernÃ©.', 'img_40_5767d861a4963.jpg', 'audio_40_57503c5cad6a1.mp3', NULL),
(41, '2', NULL, 8, 'qcm', 'Que devez vous inscrire dans le champ EmployÃ© ?', 'img_8_57ff99d97b015.jpg', 'audio_41_57503de43ca8b.mp3', NULL),
(42, '2', NULL, 9, 'champ_saisie', 'DÃ©crivez la situation prÃ©sentÃ©e sur cette image avec le maximum de prÃ©cision.', 'img_42_577e4920aa0cb.jpg', 'audio_42_57503e0e6fffe.mp3', NULL),
(47, '4', NULL, 1, 'qcm', 'Trouvez la signification qui correspond au pictogramme :', 'img_1_57e5322e529fd.jpg', 'audio_1_57e5322e7d9c6.mp3', NULL),
(48, '4', NULL, 2, 'qcm', 'Trouvez la signification qui correspond au pictogramme :', 'img_2_57e532c008e58.jpg', 'audio_2_57e532c034632.mp3', NULL),
(49, '4', NULL, 3, 'qcm', 'Sur ce plan d&#39;Ã©vacuation, quel numÃ©ro porte le point de rassemblement ?', 'img_3_57e5338d6ecba.jpg', 'audio_3_57e5338d9ab86.mp3', NULL),
(50, '4', NULL, 4, 'qcm', 'Parmi ces consignes d&#39;Ã©vacuation, trouvez celle qui n&#39;est pas adaptÃ©e ?', 'img_4_57e534686123c.jpg', 'audio_4_57e534688bae2.mp3', NULL),
(51, '4', NULL, 5, 'qcm', 'Pierre doit descendre un carton du rayonnage. Remettez les 4 images dans l&#39;ordre ', 'img_5_57ed13906ed1d.jpg', 'audio_5_57e53defb190a.mp3', NULL),
(52, '4', NULL, 6, 'qcm', 'Parmi ces 4 illustrations, quelles postures sont dÃ©conseillÃ©es ?', 'img_6_57ebc9621aabb.jpg', 'audio_6_57e53e60efa01.mp3', NULL),
(53, '4', NULL, 7, 'qcm', 'Dans cette scÃ¨ne, quel dysfonctionnement faudrait-il signaler ?', 'img_7_57f7a84ee8900.jpg', 'audio_7_57e53eaf53be4.mp3', NULL),
(54, '4', NULL, 8, 'qcm', 'La machine Ã  cÃ´tÃ© de votre poste de travail vient subitement  de sâ€™arrÃªter. Que faites vous ?', 'img_8_57e53f2e0cca8.jpg', 'audio_8_57e53f2e3ef8d.mp3', NULL),
(55, '4', NULL, 9, 'qcm', 'Vous observez votre collÃ¨gue sur le chariot Ã©lÃ©vateur. Qu&#39;allez vous lui signaler ?', 'img_9_57e53fa2eca49.jpg', 'audio_9_57e53fa32f819.mp3', NULL),
(56, '4', NULL, 10, 'qcm', 'En cas d&#39;incendie, qui prÃ©venez vous ?', 'img_10_57f7ad5ed07b3.jpg', 'audio_10_57e53ff81d6f8.mp3', NULL),
(57, '4', NULL, 11, 'qcm', 'Quels dÃ©chets peut-on recycler dans ce type de poubelle ?', 'img_11_57e5405d9e190.jpg', 'audio_11_57e5405dc9edd.mp3', NULL),
(58, '4', NULL, 12, 'qcm', 'Vous partez une semaine en congÃ©s. En partant, vous vÃ©rifiez :', 'img_12_57e540df8928f.jpg', 'audio_12_57e540b88f734.mp3', NULL),
(60, '4', NULL, 13, 'qcm', 'Pour Ã©conomiser le papier, le meilleur comportement :', 'img_13_57e5419946c7d.jpg', 'audio_13_57e541997541a.mp3', NULL),
(61, '3', NULL, 1, 'qcm', 'Sur cette image, l&#39;Ã©lÃ©ment entourÃ© reprÃ©sente :', 'img_1_57e926668fe87.jpg', 'audio_1_57e92666c2930.mp3', NULL),
(62, '3', NULL, 2, 'champ_saisie', 'A partir du traitement de texte de l&#39;ordinateur, Reproduisez ce texte et sa mise en forme', 'img_2_57e926cf96646.png', 'audio_2_57e926cfbffca.mp3', NULL),
(63, '3', NULL, 3, 'champ_saisie', 'Enregistrez-le sur le bureau et imprimer le.', 'img_3_57e92f212a41b.jpg', 'audio_3_57e92f21459dc.mp3', NULL),
(64, '3', NULL, 4, 'qcm', 'Parmi ces logos, lequel est celui d&#39;un navigateur Internet ?', 'img_4_57e9325920805.jpg', 'audio_4_57e932594ea62.mp3', NULL),
(65, '3', NULL, 5, 'qcm', 'Avec votre navigateur habituel, Pour aller sur le site de la ville de Rouen, vous tapez www.rouen.fr, dans :', 'img_5_57e93412d2366.jpg', 'audio_5_57e93412eee9e.mp3', NULL),
(66, '3', NULL, 6, 'champ_saisie', 'Ouvrez un nouvel onglet, Allez sur le site www.pole-emploi.fr, retrouvez le site pole emploi de votre rÃ©gion.', 'img_6_57e93473c2884.jpg', 'audio_6_57e93473ddcf9.mp3', NULL),
(67, '3', NULL, 7, 'champ_saisie', 'Retrouvez ce mÃªme site, Ã  partir d&#39;un moteur de recherche.', 'img_7_57e934a264363.jpg', 'audio_7_57e934a27f78f.mp3', NULL),
(68, '3', NULL, 8, 'qcm', 'Vous diriez que ce site est un site :', 'img_8_57e935045273e.jpg', 'audio_8_57e935047f5c5.mp3', NULL),
(69, '3', NULL, 4, 'champ_saisie', 'A partir de votre messagerie, envoyez-le Ã  votre Ã©valuateur/trice ?', 'img_4_57e9359fcc27b.jpg', 'audio_4_57e9359fe7792.mp3', NULL),
(70, '5', NULL, 1, 'champ_saisie', 'En arrivant ce matin sur votre lieu de travail, vous constatez qu&#39;il n&#39;y a plus dâ€™Ã©lectricitÃ© dans  le vestiaire qui fait office de local de dÃ©tente. Il fait froid, la lumiÃ¨re ne s&#39;allume pas, le frigo est arrÃªtÃ©.Selon vous quel est le problÃ¨me ? RÃ©digez votre avis.', 'img_1_5800e3e956d71.png', 'audio_1_57ece06defee3.mp3', NULL),
(71, '5', NULL, 2, 'qcm', 'Face Ã   cette situation, que faÃ®tes-vous ?', 'img_2_5800e40c6b7ae.png', 'audio_2_57ece11199536.mp3', NULL),
(72, '5', NULL, 3, 'qcm', 'Vous vous apercevez que le disjoncteur a sautÃ©.Vous avez essayÃ© de remettre en marche mais Ã§a ne fonctionne pas. L&#39;interrupteur gÃ©nÃ©ral ne veut pas s&#39;enclencher.', 'img_3_5800ea84f0143.png', 'audio_3_57ece2f812c77.mp3', NULL),
(73, '5', NULL, 4, 'champ_saisie', 'Vous dÃ©cidez finalement de joindre l&#39;Ã©lectricien chargÃ© de l&#39;entretien des batiments. Il est absent pour l&#39;instant et vous ne pouvez pas le joindre sur son tÃ©lÃ©phone. Vous lui laissez un message SMS. Ecrivez ce texte.', 'img_4_5804a30f94467.png', 'audio_4_57ece3947d280.mp3', NULL),
(74, '5', NULL, 5, 'qcm', 'En attendant que l&#39;Ã©lectricien vous rappelle vous dÃ©cidez d&#39;essayer de trouver d&#39;oÃ¹ vient le problÃ¨me par vous-mÃªme. Parmi ces  propositions laquelle vous semble la plus adaptÃ©e Ã  la situation', 'img_5_5800ed3663e66.png', 'audio_5_57ece59509259.mp3', NULL),
(75, '5', NULL, 6, 'champ_saisie', 'AprÃ¨s plusieurs essais, vous constatez que le radiateur d&#39;appoint est la cause du problÃ¨me. Son cÃ¢ble qui le relie Ã  la prise a Ã©tÃ© Ã©crasÃ© et fait un court-circuit. L&#39;Ã©lectricien vous rappelle enfin.Vous lui expliquez la situation.  Il vous demande de remplir le cahier des incidents. Vous rÃ©digez ce rapport et faites des propositions  pour Ã©viter pour Ã©viter que cela ne se reproduise.', 'img_6_5800ef6241783.png', 'audio_6_57ece676f1d1f.mp3', NULL);

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
(2, 2, '101010'),
(3, 3, '101010'),
(4, 4, '101010'),
(5, 5, '101020'),
(6, 6, '101010'),
(7, 7, '101030'),
(8, 8, '101040'),
(9, 9, '101040'),
(10, 10, '101050'),
(11, 11, '101060'),
(12, 12, '101070'),
(13, 13, '102010'),
(14, 14, '102010'),
(15, 15, '102010'),
(16, 16, '102020'),
(17, 17, '102030'),
(18, 18, '102040'),
(19, 19, '103010'),
(20, 20, '103020'),
(21, 21, '103030'),
(22, 22, '103040'),
(23, 23, '103040'),
(24, 24, '103040'),
(25, 25, '103050'),
(26, 26, '103060'),
(27, 27, '103070'),
(28, 28, '103070'),
(29, 29, '103070'),
(30, 30, '104010'),
(31, 31, '104010'),
(32, 32, '104010'),
(33, 33, '102010'),
(34, 34, '201010'),
(35, 35, '201020'),
(36, 36, '203010'),
(37, 37, '203020'),
(38, 38, '203030'),
(39, 39, '203040'),
(40, 40, '204010'),
(41, 41, '204020'),
(42, 42, '204060'),
(47, 47, '401010'),
(48, 48, '401010'),
(49, 49, '401020'),
(50, 50, '401030'),
(51, 51, '402010'),
(52, 52, '402020'),
(53, 53, '402050'),
(54, 54, '402060'),
(55, 55, '403020'),
(56, 56, '403030'),
(57, 57, '404010'),
(58, 58, '404020'),
(60, 60, '404030'),
(61, 61, '301010'),
(62, 62, '302010'),
(63, 63, '302020'),
(64, 64, '303010'),
(65, 65, '303010'),
(66, 66, '303020'),
(67, 67, '303030'),
(68, 68, '303050'),
(69, 69, '304030'),
(70, 70, '501010'),
(71, 71, '503020'),
(72, 72, '501020'),
(73, 73, '501030'),
(74, 74, '502030'),
(75, 75, '502060');

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
(4, 2, 1, '327', 0),
(5, 2, 2, '323', 0),
(6, 2, 3, '333', 1),
(7, 3, 1, '432', 0),
(8, 3, 2, '408', 1),
(9, 3, 3, '404', 0),
(10, 4, 1, '25', 0),
(11, 4, 2, '24', 1),
(12, 4, 3, '18', 0),
(13, 5, 1, '30', 0),
(14, 5, 2, '29', 0),
(15, 5, 3, '26', 1),
(16, 5, 4, '24', 0),
(17, 6, 1, '457', 1),
(18, 6, 2, '447', 0),
(19, 6, 3, '467', 0),
(20, 7, 1, ' 23,6 - 24,47 - 24,56 - 25,34 ', 1),
(21, 7, 2, '23,6 - 24,56 - 24,47 - 25,34 ', 0),
(22, 7, 3, '25,34 - 24,56 - 24,47 - 23,6', 0),
(23, 7, 4, ' 24,47 - 24,56 - 25,34 - 23,6', 0),
(24, 8, 1, '90', 0),
(25, 8, 2, '125', 0),
(26, 8, 3, '100', 0),
(27, 8, 4, '105', 1),
(28, 9, 1, '260', 0),
(29, 9, 2, '27000', 0),
(30, 9, 3, '2600', 1),
(31, 10, 1, ' Je rajoute un zÃ©ro ', 0),
(32, 10, 2, 'Je dÃ©place la virgule d&#39;un rang vers la gauche', 0),
(33, 10, 3, 'Je dÃ©place la virgule d&#39;un rang vers la droite', 1),
(34, 11, 1, '9 km/h', 1),
(35, 11, 2, '36 km/h', 0),
(36, 11, 3, '45 km/h', 0),
(37, 12, 1, '480 kg de ciment, 237 litres d&#39;eau, 1050 kg de sable, 1950 kg de gravier', 1),
(38, 12, 2, ' 160 kg de ciment, 79 litres d&#39;eau, 350 kg de sable, 650 kg de gravier', 0),
(39, 12, 3, ' 620 kg de ciment, 316 litres d&#39;eau, 1400 kg de sable, 2600 kg de gravier', 0),
(40, 13, 1, '8', 0),
(41, 13, 2, '9', 0),
(42, 13, 3, '10', 1),
(43, 13, 4, '11', 0),
(44, 14, 1, '24', 1),
(45, 14, 2, '25', 0),
(46, 14, 3, '23', 0),
(47, 14, 4, '26', 0),
(48, 15, 1, '67', 0),
(49, 15, 2, '69', 1),
(50, 15, 3, '71', 0),
(51, 15, 4, '73', 0),
(52, 16, 1, '43', 0),
(53, 16, 2, '44', 0),
(54, 16, 3, '45', 1),
(55, 17, 1, '21', 1),
(56, 17, 2, '28', 0),
(57, 17, 3, '14', 0),
(58, 18, 1, '29,8  kw/an', 0),
(59, 18, 2, '27,7 kw/an', 1),
(60, 18, 3, ' 31,2  kw/an', 0),
(61, 19, 1, '7h47', 1),
(62, 19, 2, '7h48', 0),
(63, 19, 3, '7h57', 0),
(64, 20, 1, 'de 8H30 Ã  11H30 et de 14H30 Ã  17H30', 0),
(65, 20, 2, ' de 8H30 Ã  12H30 et de 13H30 Ã  17H30', 0),
(66, 20, 3, ' de 8H30 Ã  11H30 et de 13H30 Ã  17H30', 1),
(67, 20, 4, 'de 9H30 Ã  11H30 et de 13H30 Ã  17H30', 0),
(68, 21, 1, 'Plage A', 1),
(69, 21, 2, 'Plage B', 0),
(70, 21, 3, 'Plage C', 0),
(71, 22, 1, '0,1 mÃ¨tre', 1),
(72, 22, 2, '0,01 mÃ¨tre', 0),
(73, 22, 3, '100 mÃ¨tres', 0),
(74, 22, 4, '10 mÃ¨tres', 0),
(75, 23, 1, '25 cl', 0),
(76, 23, 2, '250 cl', 1),
(77, 23, 3, '2500 cl', 0),
(78, 24, 1, '17,50 grammes', 0),
(79, 24, 2, '17500 grammes', 0),
(80, 24, 3, '1750 grammes', 1),
(81, 25, 1, '1', 0),
(82, 25, 2, '2', 0),
(83, 25, 3, '3', 1),
(84, 27, 1, '15,40 m', 1),
(85, 27, 2, '15,40 mÂ²', 0),
(86, 27, 3, ' 14,70 m', 0),
(87, 27, 4, '14,70 mÂ²', 0),
(89, 28, 1, '65,00 mÂ²', 0),
(90, 28, 2, '36,40 mÂ²', 0),
(91, 28, 3, '18,20 mÂ²', 0),
(92, 28, 4, '71,25 mÂ²', 1),
(93, 29, 1, '18,4800 m3', 0),
(94, 29, 2, '33,0750 m3', 1),
(95, 29, 3, '10,9000 m3', 0),
(96, 30, 1, 'Oui', 1),
(97, 30, 2, 'Non', 0),
(98, 31, 1, '2', 0),
(99, 31, 2, '3', 0),
(100, 31, 3, '4', 1),
(101, 31, 4, '5', 0),
(102, 33, 1, '204', 0),
(103, 33, 2, '184', 0),
(104, 33, 3, '207', 1),
(105, 33, 4, '198', 0),
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
(122, 41, 2, 'Vous', 1),
(128, 47, 1, 'Port du casque obligatoire  ', 1),
(129, 47, 2, 'Se couvrir la tÃªte', 0),
(130, 47, 3, 'Port du casque conseillÃ©', 0),
(131, 48, 1, 'Manipulation dangereuse ', 0),
(132, 48, 2, 'Gants de protection obligatoire', 1),
(133, 48, 3, 'Protection contre le froid ', 0),
(134, 49, 1, '1', 0),
(135, 49, 2, '2', 1),
(136, 49, 3, '3', 0),
(137, 50, 1, 'Evacuez les locaux dÃ¨s que vous entendez lâ€™alerte', 0),
(138, 50, 2, 'Rassemblez toutes vos affaires avant d&#39;Ã©vacuez', 1),
(139, 50, 3, 'Nâ€™utilisez jamais les ascenseurs', 0),
(140, 50, 4, 'Fermez portes et fenÃªtres derriÃ¨re vous', 0),
(141, 51, 1, '4-2-3-1', 1),
(142, 51, 2, '3-2-1-4', 0),
(143, 52, 1, '1-2', 1),
(144, 52, 2, '3-4', 0),
(145, 53, 1, 'Compteur Ã©lÃ©ctrique ouvert', 1),
(146, 53, 2, 'Cartons au sol', 0),
(147, 54, 1, 'Vous prÃ©venez le responsable d&#39;atelier', 1),
(148, 54, 2, 'Vous appelez votre directeur', 0),
(149, 54, 3, 'Vous attendez que votre collÃ¨gue rejoigne son poste', 0),
(150, 54, 4, 'Vous coupez l&#39;alarme', 0),
(151, 55, 1, ' Tu roulais en sens inverse', 0),
(152, 55, 2, 'Tu roulais trop vite', 0),
(153, 55, 3, 'Tu roulais dans la zone piÃ©tonne', 1),
(154, 56, 1, 'Les pompiers', 0),
(155, 56, 2, 'Albert Jean', 1),
(156, 56, 3, 'Christine Castro', 0),
(157, 57, 1, 'Verre', 0),
(158, 57, 2, 'DÃ©chets verts', 0),
(159, 57, 3, 'Papier ', 1),
(160, 57, 4, 'Ordures mÃ©nagÃ¨res', 0),
(161, 58, 1, 'Je dÃ©branche la prise Ã©lÃ©ctrique', 0),
(162, 58, 2, 'J&#39;eteins mon ordinateur', 1),
(163, 58, 3, 'Je dÃ©branche la prise Ã©lÃ©ctrique', 0),
(167, 60, 1, 'Imprimer en recto/verso', 1),
(168, 60, 2, 'Imprimer ses mails', 0),
(169, 60, 3, 'Imprimer sur fond noir', 0),
(170, 61, 1, 'le moniteur', 0),
(171, 61, 2, 'La souris', 1),
(172, 61, 3, 'L&#39;unitÃ© centrale', 0),
(173, 61, 4, 'le clavier', 0),
(174, 64, 1, '1', 1),
(175, 64, 2, '2', 0),
(176, 64, 3, '3', 0),
(177, 64, 4, '4', 0),
(178, 65, 1, 'la barre de recherche', 0),
(179, 65, 2, 'la barre d&#39;adresse', 1),
(180, 65, 3, 'la barre des favoris', 0),
(181, 68, 1, 'Commercial', 0),
(182, 68, 2, 'Personnel', 0),
(183, 68, 3, 'Institutionnel', 1),
(184, 52, 3, '1-3', 0),
(185, 52, 4, '2-4', 0),
(186, 71, 1, 'Je rentre chez moi ', 0),
(187, 71, 2, 'Je vais voir le tableau Ã©lectrique', 0),
(188, 71, 3, 'J&#39;appelle un collÃ¨gue ', 1),
(189, 72, 1, 'Je rentre chez moi ', 0),
(190, 72, 2, ' J&#39;appelle un collÃ¨gue ', 0),
(191, 72, 3, 'J&#39;appelle la personne chargÃ©e de l&#39;entretien', 1),
(192, 74, 1, 'Je vÃ©rifie l&#39;Ã©tat des ampoules.', 0),
(193, 74, 2, 'Je dÃ©branche tous les appareils et je remet en marche le disjoncteur.', 0),
(194, 74, 3, 'Je dÃ©branche les appareils un par un et je remet Ã  chaque fois le disjoncteur en marche.', 0),
(195, 74, 4, 'Je dÃ©branche chaque fils dans le tableau Ã©lectrique.', 0),
(196, 74, 5, 'Finalement j&#39;attends l&#39;Ã©lectricien sans rien faire.', 1),
(197, 51, 3, '1-3-2-4', 0),
(198, 53, 3, 'Bidon d&#39;essence non rangÃ©', 0),
(199, 53, 4, 'Le casier est ouvert', 0);

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
