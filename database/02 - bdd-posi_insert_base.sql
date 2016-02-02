
/*--- Insertion automatique des données récurrentes ---*/


/* USE positionnement; */


/* Insertion de la liste des niveaux d'études */

INSERT INTO niveau_etudes (nom_niveau, descript_niveau)
	VALUES 	("Niveau VI et Vbis : abandon CAP - BEP - 3e", "Sorties en cours de 1er cycle de l'enseignement secondaire (6Ã¨me Ã  3Ã©me) ou abandons en cours de CAP ou BEP avant l'annÃ©e terminale."),
			("Niveau V : CAP - BEP - 2e cycle", "Sorties aprÃ¨s l'annÃ©e terminale de CAP ou BEP ou sorties de 2nd cycle gÃ©nÃ©ral et technologique avant l'annÃ©e terminale (seconde, premiÃ¨re ou terminale)."),
			("Niveau IV : Bac", "Sorties des classes de terminale de l'enseignement secondaire (avec le baccalaurÃ©at). Abandons des Ã©tudes supÃ©rieures."),
			("Niveau III : Bac+2", "Sorties avec un diplÃ´me de niveau Bac + 2 ans (DUT, BTS, DEUG, Ã©coles des formations sanitaires ou sociales, etc...)."),
			("Niveau II : Bac+3, bac+4", "Sorties avec un diplÃ´me de niveau bac+3 Ã  bac+4 (licence, maÃ®trise, master I)."),
			("Niveau I : Bac+5 et plus", "Sorties avec un diplôme de niveau bac+5 et + (master II, DEA, DESS, doctorat, diplÃ´me de grande Ã©cole).");
  
  
/* Insertion de la liste des degrés de compétences */

INSERT INTO degre (nom_degre, descript_degre)
	VALUES 	("1", "Degré 1"),
			("2", "Degré 2"),
			("3", "Degré 3");
