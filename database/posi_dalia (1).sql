
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
  `droits` enum('user','custom','admin') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `nom_admin` (`nom_admin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `administrateur`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `degre`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=192 ;

--
-- Contenu de la table `inscription`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=150 ;



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
  `nbre_posi_total` int(8) unsigned NOT NULL DEFAULT '0',
  `nbre_posi_max` int(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_organ`),
  UNIQUE KEY `nom_organ` (`nom_organ`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `organisme`
--

-- --------------------------------------------------------

--
-- Structure de la table `preconisation`
--

CREATE TABLE IF NOT EXISTS `preconisation` (
  `id_preco` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `ref_type` int(5) unsigned DEFAULT NULL,
  `nom_preco` varchar(255) NOT NULL,
  `descript_preco` tinytext,
  `taux_min` int(10) unsigned DEFAULT NULL,
  `taux_max` int(10) unsigned DEFAULT NULL,
  `num_ordre` int(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_preco`),
  UNIQUE KEY `nom_preco` (`nom_preco`),
  KEY `I_FK_preco_type` (`ref_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;



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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=907 ;



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
  `score_pourcent` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_session`),
  KEY `date_session` (`date_session`),
  KEY `I_FK_session_user` (`ref_user`),
  KEY `I_FK_session_intervenant` (`ref_intervenant`),
  KEY `I_FK_session_acquis` (`ref_valid_acquis`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=223 ;

-- --------------------------------------------------------

--
-- Structure de la table `type_preco`
--

CREATE TABLE IF NOT EXISTS `type_preco` (
  `id_type` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `nom_type` varchar(255) NOT NULL,
  `descript_type` tinytext,
  PRIMARY KEY (`id_type`),
  UNIQUE KEY `nom_type` (`nom_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=187 ;



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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;





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


