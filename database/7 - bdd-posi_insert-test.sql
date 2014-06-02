
/* Référenciel catégories / compétences Chantier Ecole */
		
INSERT INTO categorie (code_cat, nom_cat, descript_cat, type_lien_cat) 
VALUES 	('10', 'Comprendre et communiquer Ã  l&#39;oral', '', 'dynamic'),
		('1010', 'Comprendre un Ã©noncÃ© oral', '', 'static'),
		('1020', 'Identifier les caractÃ©ristiques, les attentes du ou des interlocuteurs', '', 'static'),
		('1030', 'Adopter le registre de langue adaptÃ© aux destinataires et Ã  la situation', '', 'static'),
		('1040', 'Avoir une rÃ©action pertinente Ã  un Ã©noncÃ© oral : rÃ©ponse, action, reformulation', '', 'static'),
		('20', 'Lire et comprendre l&#39;Ã©crit', '', 'dynamic'),
		('2010', 'Lire et comprendre un Ã©crit simple de langage courant', '', 'static'),
		('2020', 'ReconnaÃ®tre les diffÃ©rents types d&#39;Ã©crits', '', 'static'),
		('2030', 'Lire et interprÃ©ter les diffÃ©rentes reprÃ©sentations graphiques : tableaux, graphiques, logos, sigles, pictogrammes...', '', 'static'),
		('2040', 'DÃ©duire les actions, rÃ©ponses, solutions possibles suite Ã  la lecture d&#39;un Ã©noncÃ©', '', 'static'),
		('30', 'Communiquer par Ã©crit', '', 'dynamic'),
		('3010', 'ReprÃ©senter par Ã©crit, de faÃ§on lisible, tous les signes de l&#39;Ã©criture en franÃ§ais', '', 'static'),
		('3020', 'Reproduire les mots du franÃ§ais usuel et/ou du domaine professionnel', '', 'static'),
		('3030', 'Construire des Ã©noncÃ©s cohÃ©rents dans leur forme gÃ©nÃ©rale (ordre des mots, des idÃ©es...)', '', 'static'),
		('3040', 'RÃ©aliser diffÃ©rentes formes d&#39;Ã©crits (notes, compte-rendu, rÃ©sumÃ©, consigne...)', '', 'static'),
		('40', 'ApprÃ©hender l&#39;espace', '', 'dynamic'),
		('4010', 'Se situer et siter des objets dans l&#39;espace', '', 'static'),
		('401010', 'Situer les Ã©lements les uns par rapport aux autres', '', 'static'),
		('401020', 'Distinguer, relever des repÃ¨res dans l&#39;espace rÃ©el et les nommer', '', 'static'),
		('401030', 'ApprÃ©cier, estimer des grandeurs, des distances, des directions', '', 'static'),
		('4020', 'ReconnaÃ®tre et comprendre les principales reprÃ©sentations graphiques d&#39;un espace ou d&#39;un objet', '', 'static'),
		('402010', 'ReconnaÃ®tre et comprendre un plan', '', 'static'),
		('4030', 'Se repÃ©rer et s&#39;orienter sur un plan simple', '', 'static'),
		('50', 'ApprÃ©hender le temps', '', 'dynamic'),
		('5010', 'Se situer dans le temps', '', 'static'),
		('501010', 'Reproduire et contrÃ´ler des rythmes variÃ©s et changeants', '', 'static'),
		('501020', 'Planifier des actions chronologiques Ã  court, moyen et long terme', '', 'static'),
		('501030', 'Se repÃ©rer dans le dÃ©coupage du temps et son vocabulaire : horaire, journalier, mensuel, annuel, millÃ©naire...', '', 'static'),
		('5020', 'Combiner le temps avec d&#39;autres donnÃ©es', '', 'static'),
		('5030', 'Effectuer des actions en respectant des consignes temporelles', '', 'static'),
		('60', 'Utiliser les mathÃ©matiques en situation professionnelle', '', 'dynamic'),
		('6010', 'Lire et Ã©crire des grandeurs avec des chiffres et des nombres, entiers et dÃ©cimaux', '', 'static'),
		('6020', 'Appliquer les techniques d&#39;opÃ©rations Ã©lÃ©mentaires sur des nombres, entiers et dÃ©cimaux', '', 'static'),
		('602010', 'Appliquer des additions', '', 'static'),
		('602020', 'Appliquer des multiplications', '', 'static'),
		('602030', 'Appliquer des divisions', '', 'static'),
		('6030', 'ProblÃ©matiser des situations', '', 'static'),
		('6040', 'Appliquer les opÃ©rations pertinentes Ã  la rÃ©solution d&#39;un problÃ¨me', '', 'static');
		
		
/* Question posi */

INSERT INTO question (id_question, ref_degre, num_ordre_question, type_question, intitule_question, image_question, audio_question) 
VALUES 	(1, 1, 1, 'qcm', 'Comment s&#39;appelle ce type de document ?', 'img_1.jpg', 'audio_1.mp3'),
		(2, 2, 2, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_2.jpg', 'audio_2.mp3'),
		(3, 2, 3, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_3.jpg', 'audio_3.mp3'),
		(4, 2, 4, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_4.jpg', 'audio_4.mp3'),
		(5, 1, 5, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_5.jpg', 'audio_5.mp3'),
		(6, 3, 6, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_6.jpg', 'audio_6.mp3');
		

		
/* liaison question-catégories */

INSERT INTO question_cat (ref_question, ref_cat) 
VALUES 	(1, '2020'),
		(2, '4030'),
		(3, '501020'),
		(4, '3040'),
		(5, '2020'),
		(6, '602020');

		
/* Réponses liées aux questions */

INSERT INTO reponse (ref_question, num_ordre_reponse, intitule_reponse, est_correct) 
VALUES 	(1, 1, 'Index des rues', 0),
		(1, 2, 'Feuille de route', 0),
		(1, 3, 'Plan de ville', 1),
		(1, 4, 'Carte routiÃ¨re', 0),
		
		(2, 1, 'A2', 0),
		(2, 2, 'C3', 0),
		(2, 3, 'B1', 0),
		(2, 4, 'D3', 1),

		(3, 1, '7h00', 0),
		(3, 2, '7h15', 1),
		(3, 3, '7h30', 0),
		(3, 4, '7h45', 0),

		(5, 1, 'Ascenseur', 0),
		(5, 2, 'Sortie de secours', 0),
		(5, 3, 'Interdiction de fumer', 1),
		(5, 4, 'Travaux', 0),

		(6, 1, '95 â‚¬', 0),
		(6, 2, '950 â‚¬', 1),
		(6, 3, '9500 â‚¬', 0);
		
		




