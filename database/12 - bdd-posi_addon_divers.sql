/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  02/02/2016 15:17:00                      */
/*==============================================================*/


/* Ajout des champs ip et type de navigateur dans la table session */

ALTER TABLE session
	ADD ip_address VARCHAR(20) NULL;
ALTER TABLE session
	ADD user_agent VARCHAR(255) NULL;


/* Suppression des champs inutiles (anciennes versions) */

ALTER TABLE session
	DROP validation;
# Uniquement si version > 0.19.05
ALTER TABLE categorie
	DROP type_lien_cat;


/* Ajout table posi pour le multipositionnement */

CREATE TABLE positionnement 
(
	id_posi INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_posi VARCHAR(100) NOT NULL UNIQUE,
	descript_posi TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/* Ajout champ ref_posi dans session, categories et question pour le multipositionnement */

ALTER TABLE session
	ADD ref_posi VARCHAR(10) NULL AFTER id_session;
ALTER TABLE categorie
	ADD ref_posi VARCHAR(10) NULL AFTER code_cat;
ALTER TABLE question
	ADD ref_posi VARCHAR(10) NULL AFTER id_question;


/* Ajout rôle admin intermédiaire pour accès à la régionalisation */
ALTER TABLE administrateur
	CHANGE droits droits ENUM('user','custom','custom-admin','admin') NOT NULL DEFAULT 'user';
