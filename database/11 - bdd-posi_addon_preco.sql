/*==============================================================*/
/* Nom de SGBD :  MySQL 5.0                                     */
/* Date de cr�ation :  27/10/2015 14:56:18                      */
/*==============================================================*/



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
DROP TABLE IF EXISTS action_preco;
CREATE TABLE action_preco 
(
	id_action INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	volume_action INT(10) UNSIGNED NULL,
	nom_action VARCHAR(255) NOT NULL UNIQUE,
	descript_action TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*==============================================================*/
/* Table : preconisation                                        */
/*==============================================================*/
DROP TABLE IF EXISTS preconisation;
CREATE TABLE preconisation 
(
	id_preco INT(5) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref_action INT(5) UNSIGNED NULL,
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

CREATE INDEX I_FK_preco_action ON preconisation (ref_action ASC);


/*==============================================================*/
/* Contraintes relationnelles                                   */
/*==============================================================*/
ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_cat FOREIGN KEY (ref_code_cat) REFERENCES categorie (code_cat) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE cat_preco 
	ADD CONSTRAINT FK_cat_preco_preco FOREIGN KEY (ref_preco) REFERENCES preconisation (id_preco) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE preconisation 
	ADD CONSTRAINT FK_action_preco_preco FOREIGN KEY (ref_action) REFERENCES action_preco (id_action) ON DELETE SET NULL ON UPDATE RESTRICT;
