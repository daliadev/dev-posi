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
/* Table : type_preco                                             */
/*==============================================================*/
DROP TABLE IF EXISTS type_preco;
CREATE TABLE type_preco 
(
	id_type INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_type VARCHAR(255) NOT NULL UNIQUE,
	descript_type TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Table : preconisation                                        */
/*==============================================================*/
DROP TABLE IF EXISTS preconisation;
CREATE TABLE preconisation 
(
	id_preco INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_type INT(5) UNSIGNED NULL,
	nom_preco VARCHAR(255) NOT NULL,
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

CREATE INDEX I_FK_preco_type ON preconisation (ref_type ASC);


/*==============================================================*/
/* Contraintes relationnelles                                   */
/*==============================================================*/
ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_cat FOREIGN KEY (ref_code_cat) REFERENCES categorie (code_cat) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_preco FOREIGN KEY (ref_preco) REFERENCES preconisation (id_preco) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE preconisation 
	ADD CONSTRAINT FK_type_preco_preco FOREIGN KEY (ref_type) REFERENCES type_preco (id_type) ON DELETE SET NULL ON UPDATE RESTRICT;

