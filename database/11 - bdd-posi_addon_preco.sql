/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  27/10/2015 14:56:18                      */
/* Commanditaire: Uniformation                                  */
/*==============================================================*/


/*==============================================================*/
/* Table : type_preco                                             */
/*==============================================================*/
DROP TABLE IF EXISTS parcours_preco;
CREATE TABLE parcours_preco 
(
	id_parcours INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	volume_parcours INT(10) UNSIGNED NULL,
	nom_parcours VARCHAR(255) NOT NULL UNIQUE,
	descript_parcours TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* INSERT INTO parcours_preco (volume_parcours, nom_parcours)
	VALUES 	(0, "Aucune"); */


/*==============================================================*/
/* Table : preconisation                                        */
/*==============================================================*/
DROP TABLE IF EXISTS preconisation;
CREATE TABLE preconisation 
(
	id_preco INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_parcours INT(5) UNSIGNED NULL,
	nom_preco VARCHAR(255) NOT NULL,
	descript_preco TINYTEXT NULL,
	taux_min INT(10) UNSIGNED NULL,
	taux_max INT(10) UNSIGNED NULL,
	num_ordre int(3) UNSIGNED NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Table : cat_preco                                            */
/*==============================================================*/
DROP TABLE IF EXISTS cat_preco;
CREATE TABLE cat_preco
(
	id_cat_preco INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_code_cat VARCHAR(20) NOT NULL,
	ref_preco INT(5) UNSIGNED NOT NULL
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Indexes                                                      */
/*==============================================================*/
CREATE INDEX I_FK_preco_cat ON cat_preco (ref_code_cat ASC);
CREATE INDEX I_FK_cat_preco ON cat_preco (ref_preco ASC);

CREATE INDEX I_FK_preco_parcours ON preconisation (ref_parcours ASC);


/*==============================================================*/
/* Contraintes relationnelles                                   */
/*==============================================================*/
ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_cat FOREIGN KEY (ref_code_cat) REFERENCES categorie (code_cat) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_preco FOREIGN KEY (ref_preco) REFERENCES preconisation (id_preco) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE preconisation 
	ADD CONSTRAINT FK_preco_parcours_preco FOREIGN KEY (ref_parcours) REFERENCES parcours_preco (id_parcours) ON DELETE SET NULL ON UPDATE RESTRICT;



INSERT INTO parcours_preco (id_parcours, volume_parcours, nom_parcours)
	VALUES 	(1, 0, "Aucune préconisation requise"),
			(2, 10, "10 heures de formations"),
			(3, 20, "20 heures de formations"),
			(4, 30, "30 heures de formations"),
			(5, 40, "40 heures de formations"),
			(6, 50, "50 heures de formations");

