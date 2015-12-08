
/*--- Création de la base de données---*/


/* Nom de la base de données à utiliser pour le script */

/* USE nom_de_la_bdd; */



/*--- Création des tables simples ---*/

DROP TABLE IF EXISTS administrateur;
CREATE TABLE administrateur 
(
	id_admin INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_admin VARCHAR(100) NOT NULL UNIQUE,
	pass_admin VARCHAR(50) NOT NULL,
	droits ENUM('user','custom','admin') NOT NULL DEFAULT 'user'
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS niveau_etudes;
CREATE TABLE niveau_etudes 
(
	id_niveau INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_niveau VARCHAR(100) NOT NULL UNIQUE,
	descript_niveau TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS degre;
CREATE TABLE degre 
(
	id_degre INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_degre VARCHAR(100) NOT NULL UNIQUE,
	descript_degre TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS categorie;
CREATE TABLE categorie 
(
	code_cat VARCHAR(20) NOT NULL PRIMARY KEY,
	nom_cat VARCHAR(255) NOT NULL UNIQUE,
	descript_cat TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS activite;
CREATE TABLE activite 
(
	id_activite INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_activite VARCHAR(255) NOT NULL UNIQUE,
	theme_activite VARCHAR(255) NULL,
	descript_activite TINYTEXT NULL 
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS valid_acquis;
CREATE TABLE valid_acquis 
(
	id_acquis INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_acquis VARCHAR(100) NOT NULL UNIQUE,
	descript_acquis TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*--- Création des tables relationnelles ---*/

DROP TABLE IF EXISTS organisme;
CREATE TABLE organisme 
(
	id_organ INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	numero_interne VARCHAR(50) NULL,
	nom_organ VARCHAR(100) NOT NULL UNIQUE DEFAULT "",
	adresse_organ TINYTEXT NULL,
	code_postal_organ CHAR(5) NULL,
	ville_organ VARCHAR(200) NULL,
	tel_organ CHAR(10) NULL,
	fax_organ CHAR(10) NULL,
	email_organ VARCHAR(100) NULL,
	nbre_posi_total INT(8) UNSIGNED NOT NULL DEFAULT 0,
	nbre_posi_max INT(8) UNSIGNED NOT NULL DEFAULT 0
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS intervenant;
CREATE TABLE intervenant 
(
	id_intervenant INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_organ INT(5) UNSIGNED NULL,
	nom_intervenant VARCHAR(200) NULL,
	email_intervenant VARCHAR(100) NOT NULL DEFAULT "",
	tel_intervenant VARCHAR(100) NULL,
	KEY email_intervenant (email_intervenant)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS utilisateur;
CREATE TABLE utilisateur 
(
	id_user INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_niveau INT(2) UNSIGNED NULL,
	nom_user VARCHAR(100) NOT NULL,
	prenom_user VARCHAR(100) NOT NULL,
	date_naiss_user DATE NOT NULL DEFAULT "0000-00-00",
	adresse_user TINYTEXT NULL,
	code_postal_user CHAR(5) NULL,
	ville_user VARCHAR(100) NULL,
	tel_user CHAR(10) NULL,
	email_user VARCHAR(100) NULL,
	nbre_sessions_totales INT(5) UNSIGNED NOT NULL DEFAULT 0,
	nbre_sessions_accomplies INT(5) UNSIGNED NOT NULL DEFAULT 0,
	KEY nom_user (nom_user),
	KEY prenom_user (prenom_user),
	KEY date_naiss_user (date_naiss_user)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS inscription;
CREATE TABLE inscription 
(
	id_inscription INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_user INT(5) UNSIGNED NOT NULL,
	ref_intervenant INT(5) UNSIGNED NULL,
	date_inscription DATE NOT NULL DEFAULT "0000-00-00",
	KEY date_inscription (date_inscription)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS session;
CREATE TABLE session 
(
	id_session INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_user INT(5) UNSIGNED NOT NULL,
	ref_intervenant INT(5) UNSIGNED NULL,
	ref_valid_acquis INT(2) UNSIGNED NULL,
	date_session DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00",
	session_accomplie TINYINT(1) NOT NULL DEFAULT 0,
	temps_total DOUBLE NOT NULL,
	score_pourcent INT(3) UNSIGNED NOT NULL DEFAULT 0,
	KEY date_session (date_session)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS question;
CREATE TABLE question 
(
	id_question INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_degre INT(2) UNSIGNED NULL,
	num_ordre_question INT(3) NOT NULL UNIQUE,
	type_question ENUM('qcm','champ_saisie') NOT NULL DEFAULT 'qcm',
	intitule_question TINYTEXT NOT NULL,
	image_question VARCHAR(255) NULL,
	audio_question VARCHAR(255) NULL,
	video_question VARCHAR(255) NULL,
	KEY type_question (type_question)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS reponse;
CREATE TABLE reponse 
(
	id_reponse INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_question INT(5) UNSIGNED NOT NULL,
	num_ordre_reponse TINYINT(3) UNSIGNED NOT NULL,
	intitule_reponse TINYTEXT NOT NULL DEFAULT "",
	est_correct TINYINT(1) NOT NULL DEFAULT 0,
	KEY num_ordre_reponse (num_ordre_reponse)
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS resultat;
CREATE TABLE resultat 
(
	id_result INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_session INT(10) UNSIGNED NOT NULL,
	ref_question INT(5) UNSIGNED NOT NULL,
	ref_reponse_qcm INT(5) UNSIGNED NULL,
	ref_reponse_qcm_correcte INT(5) UNSIGNED NULL,
	reponse_champ TEXT NULL,
	validation_reponse_champ TINYINT(1) NULL,
	temps_reponse DOUBLE NOT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS question_cat;
CREATE TABLE question_cat
(
	ref_question INT(5) UNSIGNED NOT NULL,
	ref_cat VARCHAR(20) NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS cat_activite;
CREATE TABLE cat_activite 
(
	ref_cat VARCHAR(20) NOT NULL,
	ref_activite INT(5) UNSIGNED NOT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;





/*--- Définitions des index des clés étrangères ---*/

CREATE INDEX I_FK_intervenant_organ ON intervenant (ref_organ ASC);

CREATE INDEX I_FK_inscript_intervenant ON inscription (ref_intervenant ASC);
CREATE INDEX I_FK_inscript_utilisateur ON inscription (ref_user ASC);

CREATE INDEX I_FK_util_niveau ON utilisateur (ref_niveau ASC);

CREATE INDEX I_FK_session_user ON session (ref_user ASC);
CREATE INDEX I_FK_session_intervenant ON session (ref_intervenant ASC);
CREATE INDEX I_FK_session_acquis ON session (ref_valid_acquis ASC);

CREATE INDEX I_FK_result_session ON resultat (ref_session ASC);
CREATE INDEX I_FK_result_question ON resultat (ref_question ASC);

CREATE INDEX I_FK_question_degre ON question (ref_degre ASC);

CREATE INDEX I_FK_reponse_question ON reponse (ref_question ASC);

CREATE INDEX I_FK_question_cat ON question_cat (ref_question ASC);
CREATE INDEX I_FK_cat_question ON question_cat (ref_cat ASC);

CREATE INDEX I_FK_cat_activite ON cat_activite (ref_cat ASC);
CREATE INDEX I_FK_activite_cat ON cat_activite (ref_activite ASC);



/*--- Affectations des contraintes relationnelles entre tables ---*/

ALTER TABLE intervenant 
	ADD CONSTRAINT FK_intervenant_organ FOREIGN KEY (ref_organ) REFERENCES organisme (id_organ) ON DELETE SET NULL ON UPDATE RESTRICT;

ALTER TABLE inscription 
	ADD CONSTRAINT FK_inscript_intervenant FOREIGN KEY (ref_intervenant) REFERENCES intervenant (id_intervenant) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE inscription 
	ADD CONSTRAINT FK_inscript_util FOREIGN KEY (ref_user) REFERENCES utilisateur (id_user) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE utilisateur
	ADD CONSTRAINT FK_user_niveau FOREIGN KEY (ref_niveau) REFERENCES niveau_etudes (id_niveau) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE session 
	ADD CONSTRAINT FK_session_user FOREIGN KEY (ref_user) REFERENCES utilisateur (id_user) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE session 
	ADD CONSTRAINT FK_session_intervenant FOREIGN KEY (ref_intervenant) REFERENCES intervenant (id_intervenant) ON DELETE SET NULL ON UPDATE RESTRICT;
ALTER TABLE session 
	ADD CONSTRAINT FK_session_acquis FOREIGN KEY (ref_valid_acquis) REFERENCES valid_acquis (id_acquis) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE resultat 
	ADD CONSTRAINT FK_result_session FOREIGN KEY (ref_session) REFERENCES session (id_session) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE resultat 
	ADD CONSTRAINT FK_result_question FOREIGN KEY (ref_question) REFERENCES question (id_question) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE question 
	ADD CONSTRAINT FK_question_degre FOREIGN KEY (ref_degre) REFERENCES degre (id_degre) ON DELETE SET NULL ON UPDATE RESTRICT;

ALTER TABLE reponse 
	ADD CONSTRAINT FK_reponse_question FOREIGN KEY (ref_question) REFERENCES question (id_question) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE question_cat 
	ADD CONSTRAINT FK_question_cat_quest FOREIGN KEY (ref_question) REFERENCES question (id_question) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE question_cat 
	ADD CONSTRAINT FK_question_cat_cat FOREIGN KEY (ref_cat) REFERENCES categorie (code_cat) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE cat_activite 
	ADD CONSTRAINT FK_cat_activite_cat FOREIGN KEY (ref_cat) REFERENCES categorie (code_cat) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE cat_activite 
	ADD CONSTRAINT FK_cat_activite_activ FOREIGN KEY (ref_activite) REFERENCES activite (id_activite);



