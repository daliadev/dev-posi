
/* Référenciel catégories / compétences Chantier Ecole */
		
INSERT INTO categorie (code_cat, nom_cat, descript_cat) VALUES 	
('10', 'Comprendre et communiquer Ã  l&#39;oral', ''),
('1010', 'Comprendre un Ã©noncÃ© oral', ''),
('1020', 'Identifier les caractÃ©ristiques, les attentes du ou des interlocuteurs', ''),
('1030', 'Adopter le registre de langue adaptÃ© aux destinataires et Ã  la situation', ''),
('1045', 'Avoir une rÃ©action pertinente Ã  un Ã©noncÃ© oral : rÃ©ponse, action, reformulation', ''),
('104510', 'RÃ©pondre Ã  un interlocuteur', ''),
('20', 'Lire et comprendre l&#39;Ã©crit', ''),
('2010', 'Lire et comprendre un Ã©crit simple de langage courant', ''),
('2020', 'ReconnaÃ®tre les diffÃ©rents types d&#39;Ã©crits', ''),
('2030', 'Lire et interprÃ©ter les diffÃ©rentes reprÃ©sentations graphiques : tableaux, graphiques, logos, sigles, pictogrammes...', ''),
('2040', 'DÃ©duire les actions, rÃ©ponses, solutions possibles suite Ã  la lecture d&#39;un Ã©noncÃ©', ''),
('30', 'Communiquer par Ã©crit', ''),
('3010', 'ReprÃ©senter par Ã©crit, de faÃ§on lisible, tous les signes de l&#39;Ã©criture en franÃ§ais', ''),
('3020', 'Reproduire les mots du franÃ§ais usuel et/ou du domaine professionnel', ''),
('3030', 'Construire des Ã©noncÃ©s cohÃ©rents dans leur forme gÃ©nÃ©rale (ordre des mots, des idÃ©es...)', ''),
('3040', 'RÃ©aliser diffÃ©rentes formes d&#39;Ã©crits (notes, compte-rendu, rÃ©sumÃ©, consigne...)', ''),
('40', 'ApprÃ©hender l&#39;espace', ''),
('4010', 'Se situer et siter des objets dans l&#39;espace', ''),
('401010', 'Situer les Ã©lements les uns par rapport aux autres', ''),
('401020', 'Distinguer, relever des repÃ¨res dans l&#39;espace rÃ©el et les nommer', ''),
('401030', 'ApprÃ©cier, estimer des grandeurs, des distances, des directions', ''),
('4020', 'ReconnaÃ®tre et comprendre les principales reprÃ©sentations graphiques d&#39;un espace ou d&#39;un objet', ''),
('402010', 'ReconnaÃ®tre et comprendre un plan', ''),
('4030', 'Se repÃ©rer et s&#39;orienter sur un plan simple', ''),
('50', 'ApprÃ©hender le temps', ''),
('5010', 'Se situer dans le temps', ''),
('501010', 'Reproduire et contrÃ´ler des rythmes variÃ©s et changeants', ''),
('501020', 'Planifier des actions chronologiques Ã  court, moyen et long terme', ''),
('501030', 'Se repÃ©rer dans le dÃ©coupage du temps et son vocabulaire : horaire, journalier, mensuel, annuel, millÃ©naire...', ''),
('5020', 'Combiner le temps avec d&#39;autres donnÃ©es', ''),
('5030', 'Effectuer des actions en respectant des consignes temporelles', ''),
('60', 'Utiliser les mathÃ©matiques en situation professionnelle', ''),
('6010', 'Lire et Ã©crire des grandeurs avec des chiffres et des nombres, entiers et dÃ©cimaux', ''),
('6020', 'Appliquer les techniques d&#39;opÃ©rations Ã©lÃ©mentaires sur des nombres, entiers et dÃ©cimaux', ''),
('602010', 'Appliquer des additions', ''),
('602020', 'Appliquer des multiplications', ''),
('602030', 'Appliquer des divisions', ''),
('6030', 'ProblÃ©matiser des situations', ''),
('6040', 'Appliquer les opÃ©rations pertinentes Ã  la rÃ©solution d&#39;un problÃ¨me', '');
		
		
/* Question posi */

INSERT INTO `question` (`id_question`, `ref_posi`, `ref_degre`, `num_ordre_question`, `type_question`, `intitule_question`, `image_question`, `audio_question`, `video_question`) VALUES
(1, NULL, NULL, 3, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_3_5669755257020.jpg', 'audio_3_5669755257bd8.mp3', NULL),
(2, NULL, 1, 1, 'qcm', 'Comment s&#39;appelle ce document ?', 'img_1_5669723605024.jpg', 'audio_1_5669723605bdc.mp3', NULL),
(3, NULL, NULL, 2, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_2_566972360c93f.jpg', 'audio_2_566972360d4f7.mp3', NULL),
(4, NULL, 2, 4, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã Â  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_4_56af663187a2d.jpg', 'audio_4_56af6631b0696.mp3', NULL),
(5, NULL, 3, 5, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_5_56af67df9a0a3.jpg', 'audio_5_56af67dfb3ebe.mp3', NULL),
(6, NULL, 1, 6, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_6_56af687db3830.jpg', 'audio_6_56af687dd052c.mp3', NULL);
		

		
/* liaison question-catégories */

INSERT INTO question_cat (ref_question, ref_cat) VALUES 
(1, '10'),
(2, '1010'),
(3, '1010'),
(4, '1030'),
(5, '104510'),
(6, '2010');

		
/* Réponses liées aux questions */

INSERT INTO `reponse` (`id_reponse`, `ref_question`, `num_ordre_reponse`, `intitule_reponse`, `est_correct`) VALUES
(1, 1, 1, '7h00', 0),
(2, 1, 2, '7h15', 1),
(3, 1, 3, '7h30', 0),
(4, 1, 4, '7h45', 0),
(5, 2, 1, 'Index des rues', 0),
(6, 2, 2, 'Feuille de route', 0),
(7, 2, 3, 'Plan de ville', 1),
(8, 2, 4, 'Carte routiÃ¨re', 0),
(9, 3, 1, 'A2', 0),
(10, 3, 2, 'C3', 0),
(11, 3, 3, 'B1', 0),
(12, 3, 4, 'D3', 1),
(13, 5, 1, 'Place handicapÃ©', 0),
(14, 5, 2, 'Sortie de secours', 0),
(15, 5, 3, 'Interdiction de fumer', 1),
(16, 5, 4, 'Travaux', 0),
(17, 6, 1, '95 euros', 0),
(18, 6, 2, '950 euros', 1),
(19, 6, 3, '9500 euros', 0);