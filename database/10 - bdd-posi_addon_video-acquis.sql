
/* Ajout du champ video dans la table question */

ALTER TABLE question
	ADD video_question VARCHAR(255) NULL AFTER audio_question;



/* Ajout de la gestion de la validation des acquis */

DROP TABLE IF EXISTS valid_acquis;
CREATE TABLE valid_acquis 
(
	id_acquis INT(2) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	nom_acquis VARCHAR(100) NOT NULL UNIQUE,
	descript_acquis TINYTEXT NULL
) 
ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE session
	ADD ref_valid_acquis INT(2) UNSIGNED NULL AFTER ref_intervenant;

CREATE INDEX I_FK_session_acquis ON session (ref_valid_acquis ASC);

ALTER TABLE session 
	ADD CONSTRAINT FK_session_acquis FOREIGN KEY (ref_valid_acquis) REFERENCES valid_acquis (id_acquis) ON DELETE SET NULL ON UPDATE CASCADE;

INSERT INTO valid_acquis (nom_acquis, descript_acquis)
	VALUES 	("DegrÃ© 1", ""),
			("DegrÃ© 2", ""),
			("DegrÃ© 3", ""),
			("DegrÃ© 4", "");
