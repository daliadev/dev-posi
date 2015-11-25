/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de création :  27/10/2015 14:56:18                      */
/*==============================================================*/




/*==============================================================*/
/* Table : categorie                                            */
/*==============================================================*/
/*
DROP TABLE IF EXISTS categorie;
CREATE TABLE categorie 
(
	code_cat VARCHAR(10) NOT NULL PRIMARY KEY,
	nom_cat VARCHAR(255) NOT NULL UNIQUE,
	descript_cat TINYTEXT NULL,
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

/*==============================================================*/
/* Table : cat_preco                                            */
/*==============================================================*/
DROP TABLE IF EXISTS cat_preco;
CREATE TABLE cat_preco
(
   ref_code_cat VARCHAR(20) NOT NULL,
   ref_preco INT(5) UNSIGNED NOT NULL
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;



/*==============================================================*/
/* Table : intervalle                                           */
/*==============================================================*/
/*
DROP TABLE IF EXISTS intervalle;
CREATE TABLE intervalle 
(
	id_intervalle INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	taux_min INT(5) UNSIGNED NOT NULL,
	taux_max INT(5) UNSIGNED NOT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

/*==============================================================*/
/* Table : parcours                                             */
/*==============================================================*/
DROP TABLE IF EXISTS parcours;
CREATE TABLE parcours 
(
	id_parcours INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_parcours VARCHAR(255) NOT NULL UNIQUE,
	descript_parcours TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Table : preconisation                                        */
/*==============================================================*/
DROP TABLE IF EXISTS preconisation;
CREATE TABLE preconisation 
(
	id_preco INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_parcours INT(5) UNSIGNED NOT NULL,
	/*ref_intervalle INT(5) UNSIGNED NOT NULL,*/
	nom_preco VARCHAR(255) NOT NULL UNIQUE,
	descript_preco TINYTEXT NULL,
	taux_min INT(10) UNSIGNED NULL,
	taux_max INT(10) UNSIGNED NULL,
	num_ordre int(3) UNSIGNED NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Indexes                                                      */
/*==============================================================*/
CREATE INDEX I_FK_preco_cat ON cat_preco (ref_code_cat ASC);
CREATE INDEX I_FK_cat_preco ON cat_preco (ref_preco ASC);

CREATE INDEX I_FK_preco_parcours ON preconisation (ref_parcours ASC);
/*CREATE INDEX I_FK_preco_fraction ON preconisation (ref_intervalle ASC);*/


/*==============================================================*/
/* Contraintes relationnelles                                   */
/*==============================================================*/
ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_cat FOREIGN KEY (ref_code_cat) REFERENCES categorie (code_cat) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_preco FOREIGN KEY (ref_preco) REFERENCES preconisation (id_preco) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE preconisation 
	ADD CONSTRAINT FK_preco_parcours FOREIGN KEY (ref_parcours) REFERENCES parcours (id_parcours) ON DELETE RESTRICT ON UPDATE RESTRICT;
/*
ALTER TABLE preconisation 
	ADD CONSTRAINT FK_preco_intervalle FOREIGN KEY (ref_intervalle) REFERENCES intervalle (id_intervalle) ON DELETE RESTRICT ON UPDATE RESTRICT;
*/
