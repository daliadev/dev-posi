
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
		(2, 1, 2, 'qcm', 'Comment s&#39;appelle ce type de document ?', 'img_2.jpg', 'audio_2.mp3'),
		(3, 2, 3, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_3.jpg', 'audio_3.mp3'),
		(4, 3, 4, 'qcm', 'Vous cherchez le chemin le plus court. Sur le plan vous avez 3 propositions. Pour vous, lequel est le plus court ?', 'img_4.jpg', 'audio_4.mp3'),
		(5, 3, 5, 'qcm', 'Vous savez qu&#39;entre votre point de dÃ©part et la croix, vous devez marcher 15 minutes. Pour vous rendre sur votre lieu de travail, combien de temps allez-vous mettre ?', 'img_5.jpg', 'audio_5.mp3'),
		(6, 2, 6, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_6.jpg', 'audio_6.mp3'),
		(7, 2, 7, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_7.jpg', 'audio_7.mp3'),
		(8, 1, 8, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_8.jpg', 'audio_8.mp3'),
		(9, 1, 9, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_9.jpg', 'audio_9.mp3'),
		(10, 1, 10, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_10.jpg', 'audio_10.mp3'),
		(11, 1, 11, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_11.jpg', 'audio_11.mp3'),
		(12, 1, 12, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_12.jpg', 'audio_12.mp3'),
		(13, 2, 13, 'qcm', 'Vous allez entendre un texte de prÃ©sentation du proviseur de votre lycÃ©e. La carte de visite que vous voyez ci-dessus est-elle la bonne ?', 'img_13.jpg', 'audio_13.mp3'),
		(14, 2, 14, 'qcm', 'Vous allez entendre un texte de prÃ©sentation du proviseur de votre lycÃ©e. La carte de visite que vous voyez ci-dessus est-elle la bonne ?', 'img_14.jpg', 'audio_14.mp3'),
		(15, 2, 15, 'qcm', 'Vous allez entendre un texte de prÃ©sentation du proviseur de votre lycÃ©e. La carte de visite que vous voyez ci-dessus est-elle la bonne ?', 'img_15.jpg', 'audio_15.mp3'),
		(16, 3, 16, 'qcm', 'D&#39;aprÃ¨s ce contrat, quel est le poste occupÃ© par l&#39;agent ?', 'img_16.jpg', 'audio_16.mp3'),
		(17, 3, 17, 'qcm', 'Quelle la date de dÃ©marrage du contrat ?', 'img_17.jpg', 'audio_17.mp3'),
		(18, 3, 18, 'qcm', 'Quelle est la durÃ©e de la formation de professionnalisation ?', 'img_18.jpg', 'audio_18.mp3'),
		(19, 3, 19, 'qcm', 'A quelle date se termine le contrat ?', 'img_19.jpg', 'audio_19.mp3'),
		(20, 3, 20, 'qcm', 'Dans cet extrait d&#39;un article de Loi nÂ°83-634 du 13 juillet 1983 portant sur les droits et obligations du fonctionnaire, s&#39;agit-il ?', 'img_20.jpg', 'audio_20.mp3'),
		(21, 3, 21, 'qcm', 'Dans cet extrait d&#39;un article de Loi nÂ°83-634 du 13 juillet 1983 portant sur les droits et obligations du fonctionnaire, s&#39;agit-il ?', 'img_21.jpg', 'audio_21.mp3'),
		(22, 2, 22, 'qcm', 'Voici le planning de votre semaine de travail. A quelle heure commencez-vous Ã  travailler le mardi ?', 'img_22.jpg', 'audio_22.mp3'),
		(23, 2, 23, 'qcm', 'A quelle heure terminez-vous le travail jeudi ?', 'img_23.jpg', 'audio_23.mp3'),
		(24, 2, 24, 'qcm', 'Combien de temps avez-vous pour dÃ©jeuner ?', 'img_24.jpg', 'audio_24.mp3'),
		(25, 2, 25, 'qcm', 'Combien d&#39;heures travaillez-vous le lundi ?', 'img_25.jpg', 'audio_25.mp3'),
		(26, 2, 26, 'qcm', 'Combien d&#39;heures travaillez-vous le mardi ?', 'img_26.jpg', 'audio_26.mp3'),
		(27, 2, 27, 'qcm', 'Quel est le jour de la semaine oÃ¹ vous travaillez le moins d&#39;heure ?', 'img_27.jpg', 'audio_27.mp3'),
		(28, 2, 28, 'qcm', 'Combien d&#39;heure travaillez-vous cette semaine ?', 'img_28.jpg', 'audio_28.mp3'),
		(29, 2, 29, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_29.jpg', 'audio_29.mp3'),
		(30, 2, 30, 'qcm', 'Sachant que vous avez travaillÃ© 140 heures durant le mois et que vous gagnez 9,50 euros de l&#39;heure. Combien allez-vous gagner ?', 'img_30.jpg', 'audio_30.mp3'),
		(31, 2, 31, 'qcm', 'Sachant que vous avez touchÃ© 1520 euros et que vous gagnez 9,50 euros de l&#39;heure. Combien d&#39;heures avez-vous travaillÃ© dans le mois ?', 'img_31.jpg', 'audio_31.mp3'),
		(32, 2, 32, 'qcm', 'Vous avez gagnÃ© au mois de septembre 1369,76 euros. L&#39;entreprise vous paye en chÃ¨que, quelle sera la somme en lettres ?', 'img_32.jpg', 'audio_32.mp3'),
		(33, 3, 33, 'champ_saisie', 'Ecrivez dans le cadre ci-dessous votre courrier qui sera envoyÃ© Ã  M. DUMAS. Il sera joint Ã  la &#34;DÃ©claration interne d&#39;accident du travail&#34;.', 'img_33.jpg', 'audio_33.mp3');
		

		
/* liaison question-catégories */

INSERT INTO question_cat (ref_question, ref_cat) 
VALUES 	(1, '2020'),
		(2, '2010'),
		(3, '4030'),
		(4, '401030'),
		(5, '5020'),
		(6, '501020'),
		(7, '3040'),
		(8, '2010'),
		(9, '2020'),
		(10, '2020'),
		(11, '2020'),
		(12, '2020'),
		(13, '2030'),
		(14, '2030'),
		(15, '2030'),
		(16, '2030'),
		(17, '2040'),
		(18, '2040'),
		(19, '2040'),
		(20, '2040'),
		(21, '2040'),
		(22, '501030'),
		(23, '501030'),
		(24, '501030'),
		(25, '602010'),
		(26, '602010'),
		(27, '6010'),
		(28, '602010'),
		(29, '602020'),
		(30, '602030'),
		(31, '602030'),
		(32, '3010'),
		(33, '3030');

		
/* Réponses liées aux questions */

INSERT INTO reponse (ref_question, num_ordre_reponse, intitule_reponse, est_correct) 
VALUES 	(1, 1, 'Index des rues', 0),
		(1, 2, 'Feuille de route', 0),
		(1, 3, 'Plan de ville', 1),
		(1, 4, 'Carte routiÃ¨re', 0),
		
		(2, 1, 'Index des rues', 1),
		(2, 2, 'Feuille de route', 0),
		(2, 3, 'Plan de ville', 0),
		(2, 4, 'Carte routiÃ¨re', 0),
		
		(3, 1, 'A2', 0),
		(3, 2, 'C3', 0),
		(3, 3, 'B1', 0),
		(3, 4, 'D3', 1),
		
		(4, 1, 'Trajet bleu', 0),
		(4, 2, 'Trajet vert', 1),
		(4, 3, 'Trajet rouge', 0),
		
		(5, 1, '5 minutes', 0),
		(5, 2, '15 minutes', 0),
		(5, 3, '30 minutes', 1),
		
		(6, 1, '7h00', 0),
		(6, 2, '7h15', 1),
		(6, 3, '7h30', 0),
		(6, 4, '7h45', 0),
		
		(8, 1, 'Place handicapÃ©', 1),
		(8, 2, 'Sortie de secours', 0),
		(8, 3, 'Interdiction de fumer', 0),
		(8, 4, 'Travaux', 0),
		
		(9, 1, 'Ascenseur', 0),
		(9, 2, 'Sortie de secours', 1),
		(9, 3, 'Interdiction de fumer', 0),
		(9, 4, 'Travaux', 0),
		
		(10, 1, 'Ascenseur', 0),
		(10, 2, 'Sortie de secours', 0),
		(10, 3, 'Interdiction de fumer', 1),
		(10, 4, 'Travaux', 0),
		
		(11, 1, 'Ascenseur', 1),
		(11, 2, 'Sortie de secours', 0),
		(11, 3, 'Interdiction de fumer', 0),
		(11, 4, 'Travaux', 0),
		
		(12, 1, 'Place handicapÃ©', 0),
		(12, 2, 'Sortie de secours', 0),
		(12, 3, 'Interdiction de fumer', 0),
		(12, 4, 'Travaux', 1),
		
		(13, 1, 'Oui', 0),
		(13, 2, 'Non', 1),
		
		(14, 1, 'Oui', 1),
		(14, 2, 'Non', 0),
		
		(15, 1, 'Oui', 0),
		(15, 2, 'Non', 1),
		
		(16, 1, 'Directeur(trice) gÃ©nÃ©ral(e) ?', 0),
		(16, 2, 'Adjoint administratif', 1),
		(16, 3, 'Directeur(trice) des Ressources Humaines', 0),
		
		(17, 1, '01/01/2013', 1),
		(17, 2, '05/01/2013', 0),

		(18, 1, '3 jours', 1),
		(18, 2, '5 jours', 0),
		(18, 3, '2 ans', 0),
		
		(19, 1, '01/01/2013', 0),
		(19, 2, '05/01/2014', 0),
		(19, 3, '31/12/2013', 1),
		
		(20, 1, 'Droit de fonctionnaire', 1),
		(20, 2, 'Obligation du fonctionnaire', 0),
		
		(21, 1, 'Droit du fonctionnaire', 0),
		(21, 2, 'Obligation du fonctionnaire', 1),
		
		(22, 1, '7h00', 0),
		(22, 2, '7h30', 1),
		(22, 3, '8h00', 0),
		(22, 4, '8h30', 0),
		
		(23, 1, '16h00', 0),
		(23, 2, '17h00', 1),
		(23, 3, '18h00', 0),
		(23, 4, '19h00', 0),
		
		(24, 1, '30min', 0),
		(24, 2, '1h00', 1),
		(24, 3, '1h30', 0),
		(24, 4, '2h00', 0),
		
		(25, 1, '6h00', 0),
		(25, 2, '6h30', 0),
		(25, 3, '7h00', 0),
		(25, 4, '7h30', 1),
		
		(26, 1, '7h00', 0),
		(26, 2, '8h00', 1),
		(26, 3, '9h00', 0),
		(26, 4, '9h30', 0),
		
		(27, 1, 'Lundi', 0),
		(27, 2, 'Mardi', 0),
		(27, 3, 'Mercredi', 0),
		(27, 4, 'Jeudi', 0),
		(27, 5, 'Vendredi', 1),
		
		(28, 1, '30h00', 0),
		(28, 2, '32h00', 0),
		(28, 3, '35h00', 1),
		(28, 4, '39h00', 0),
		
		(29, 1, '95 â‚¬', 0),
		(29, 2, '950 â‚¬', 1),
		(29, 3, '9500 â‚¬', 0),
		
		(30, 1, '1300 â‚¬', 0),
		(30, 2, '1330 â‚¬', 1),
		(30, 3, '1400 â‚¬', 0),
		(30, 4, 'Je ne sais pas', 0),
		
		(31, 1, '140h', 0),
		(31, 2, '150h', 0),
		(31, 3, '160h', 1),
		(31, 4, 'Je ne sais pas', 0),
		
		(32, 1, 'Mille trois cent soixante-neuf euros et quatre-vingt-seize centimes', 0),
		(32, 2, 'Mille trois cent soixante-neuf euros et soixante-seize centimes', 1),
		(32, 3, 'Mille trois cent soixante-dix-neuf euros et soixante-seize centimes', 0),
		(32, 4, 'Mille trois cent soixante-dix-neuf euros et seize centimes', 0);
		
		




