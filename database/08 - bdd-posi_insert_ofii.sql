
/* Catégories */

INSERT INTO categorie (code_cat, nom_cat, descript_cat, type_lien_cat) 
VALUES	('10', 'ReconnaÃ®tre des pictogrammes et des logos', '', 'static'),
		('15', 'Comprendre la correspondance', '', 'static'),
		('20', 'ReconnaÃ®tre un mÃªme mot Ã©crit en majsucule, minuscule, attachÃ©', '', 'static'),
		('30', 'ReconnaÃ®tre des papiers d&#39;identitÃ©', '', 'static'),
		('40', 'ReconnaÃ®tre les donnÃ©es chiffrÃ©es et les recopier', '', 'static'),
		('50', 'Ecrire son nom et prÃ©nom', '', 'static'),
		('60', 'ComplÃ©ter un formulaire simple', '', 'static'),
		('70', 'ReconnaÃ®tre le vocabulaire du quotidien', '', 'static'),
		('80', 'Comprendre des instructions simples', '', 'static'),
		('90', 'Comprendre des informations chiffrÃ©es', '', 'static');
		
		
/* Questions */

INSERT INTO question (id_question, ref_degre, num_ordre_question, type_question, intitule_question, image_question, audio_question)
VALUES	(1, NULL, 1, 'qcm', 'DANS LA LISTE, RETROUVEZ LE MOT QUI CORRESPOND A L&#39;IMAGE.', NULL, NULL),
		(2, NULL, 2, 'qcm', 'DANS LA LISTE, RETROUVEZ LE MOT QUI CORRESPOND A L&#39;IMAGE.', NULL, NULL),
		(3, NULL, 3, 'qcm', 'DANS LA LISTE, RETROUVEZ LE MOT QUI CORRESPOND A L&#39;IMAGE.', NULL, NULL),
		(4, NULL, 4, 'qcm', 'DANS LA LISTE, RETROUVEZ LE MOT QUI CORRESPOND A L&#39;IMAGE.', NULL, NULL),
		(5, NULL, 5, 'qcm', 'DANS LA LISTE, RETROUVEZ LE MOT &#34;QUEL&#34; ECRIT DIFFÃ‰REMMENT.', NULL, NULL),
		(6, NULL, 6, 'qcm', 'RETROUVEZ LE MOT &#34;VOTRE&#34; ECRIT EN MINUSCULES', NULL, NULL),
		(7, NULL, 7, 'qcm', 'CHOISISSEZ LE MOT CORRESPONDANT A L&#39;IMAGE', NULL, NULL),
		(8, NULL, 8, 'qcm', 'CHOISISSEZ LE MOT QUI CORRESPOND A L&#39;IMAGE.', NULL, NULL),
		(9, NULL, 9, 'qcm', 'CHOISISSEZ LE MOT CORRESPONDANT A L&#39;IMAGE.', NULL, NULL),
		(10, NULL, 10, 'qcm', 'DANS LA LISTE, QUEL EST LE NUMÃ‰RO QUI CORRESPOND LE MIEUX A L&#39;IMAGE ?', NULL, NULL),
		(11, NULL, 11, 'qcm', 'DANS LA LISTE, QUEL EST LE NUMÃ‰RO QUI CORRESPOND LE MIEUX A L&#39;IMAGE ?', NULL, NULL),
		(12, NULL, 12, 'qcm', 'DANS LA LISTE, QUEL EST LE NUMÃ‰RO QUI CORRESPOND LE MIEUX A L&#39;IMAGE ?', NULL, NULL),
		(13, NULL, 13, 'qcm', 'DANS LA LISTE, QUEL EST LE NUMÃ‰RO QUI CORRESPOND LE MIEUX A L&#39;IMAGE ?', NULL, NULL),
		(14, NULL, 14, 'champ_saisie', 'Pour remplir une fiche d&#39;inscription, vous avez besoin d&#39;indiquer votre NOM, PRÃ‰NOM, ADRESSE, DATE DE NAISSANCE et LIEU DE NAISSANCE. Ecrivez ces informations dans la zone blanche ci-dessous.', NULL, NULL),
		(15, NULL, 15, 'qcm', 'VOUS ALLEZ ENTENDRE UNE PHRASE. ASSOCIEZ ENSUITE L&#39;IMAGE EN RETROUVANT LE NUMÃ‰RO.', NULL, NULL),
		(16, NULL, 16, 'qcm', 'VOUS ALLEZ ENTENDRE UNE PHRASE. ASSOCIEZ ENSUITE L&#39;IMAGE EN RETROUVANT LE NUMÃ‰RO.', NULL, NULL),
		(17, NULL, 17, 'qcm', 'VOUS ALLEZ ENTENDRE UNE PHRASE. ASSOCIEZ ENSUITE L&#39;IMAGE EN RETROUVANT LE NUMÃ‰RO.', NULL, NULL),
		(18, NULL, 18, 'qcm', 'VOUS ALLEZ ENTENDRE UNE PHRASE. ASSOCIEZ ENSUITE L&#39;IMAGE EN RETROUVANT LE NUMÃ‰RO.', NULL, NULL),
		(19, NULL, 19, 'qcm', 'VOUS ALLEZ ENTENDRE UNE PHRASE. ASSOCIEZ ENSUITE L&#39;IMAGE EN RETROUVANT LE NUMÃ‰RO.', NULL, NULL),
		(20, NULL, 20, 'qcm', 'LISEZ CETTE PHRASE ET ASSOCIEZ L&#39;IMAGE QUI CORRESPOND A L&#39;AIDE DE SON NUMÃ‰RO : &#34;C&#39;est une piÃ¨ce de MoliÃ¨re ce soir ?&#34;', NULL, NULL),
		(21, NULL, 21, 'qcm', 'LISEZ CETTE PHRASE ET ASSOCIEZ L&#39;IMAGE QUI CORRESPOND A L&#39;AIDE DE SON NUMÃ‰RO : &#34;Vous venez pour votre carte de sÃ©jour ? Le bureau est au fond Ã  droite.&#34;', NULL, NULL),
		(22, NULL, 22, 'qcm', 'LISEZ CETTE PHRASE ET ASSOCIEZ L&#39;IMAGE QUI CORRESPOND A L&#39;AIDE DE SON NUMÃ‰RO : &#34; Voici la clÃ© de votre chambre, c&#39;est la 26&#34;', NULL, NULL),
		(23, NULL, 23, 'qcm', 'LISEZ CETTE PHRASE ET ASSOCIEZ L&#39;IMAGE QUI CORRESPOND A L&#39;AIDE DE SON NUMÃ‰RO : &#34; C&#39;est le 06 58 32 14 96&#34;', NULL, NULL),
		(24, NULL, 24, 'qcm', 'LISEZ CETTE PHRASE ET ASSOCIEZ L&#39;IMAGE QUI CORRESPOND A L&#39;AIDE DE SON NUMÃ‰RO : &#34;Est-ce qu&#39;il y a un distributeur dans cette rue ?&#34;', NULL, NULL),
		(25, NULL, 25, 'qcm', 'RETROUVEZ LES 4 Ã‰TAPES DE LA RECETTE POUR UNE OMELETTE : CASSER - BATTRE - VERSER - CUIRE', NULL, NULL),
		(26, NULL, 26, 'qcm', 'ECOUTEZ BIEN LE DIALOGUE QUI VA SUIVRE ET RÃ‰PONDEZ A CETTE QUESTION : QUELLE EST LA PÃ‰RIODE DES VACANCES DE MANON ?', NULL, NULL),
		(27, NULL, 27, 'qcm', 'LISEZ CE COURRIER ELECTRONIQUE PUIS RÃ‰PONDEZ A CETTE QUESTION : QUI A ECRIT CE COURRIER ?', NULL, NULL),
		(28, NULL, 28, 'qcm', 'LISEZ CE COURRIER ELECTRONIQUE PUIS RÃ‰PONDEZ A CETTE QUESTION : A QUI ECRIT ROSE ?', NULL, NULL),
		(29, NULL, 29, 'qcm', 'LISEZ CE COURRIER ELECTRONIQUE PUIS RÃ‰PONDEZ A CETTE QUESTION : QUI EST PHILIPPE ?', NULL, NULL);


/* Liaison question-catégorie */

INSERT INTO question_cat (ref_question, ref_cat) 
VALUES 	(1, '10'),
		(2, '10'),
		(3, '10'),
		(4, '10'),
		(5, '20'),
		(6, '20'),
		(7, '30'),
		(8, '30'),
		(9, '30'),
		(10, '40'),
		(11, '40'),
		(12, '40'),
		(13, '40'),
		(14, '50'),
		(15, '70'),
		(16, '70'),
		(17, '70'),
		(18, '70'),
		(19, '70'),
		(20, '70'),
		(21, '70'),
		(22, '70'),
		(23, '70'),
		(24, '70'),
		(25, '80'),
		(26, '90'),
		(27, '80'),
		(28, '15'),
		(29, '15');

		
/* Réponses liées aux questions */

INSERT INTO reponse (ref_question, num_ordre_reponse, intitule_reponse, est_correct) 
VALUES 	(1, 1, 'INFORMATION', 0),
		(1, 2, 'INTERDIT DE TÃ‰LÃ‰PHONER', 0),
		(1, 3, 'TOXIQUE', 0),
		(1, 4, 'INTERDIT DE FUMER', 1),

		(2, 1, 'INFORMATION', 1),
		(2, 2, 'INTERDIT DE TELEPHONER', 0),
		(2, 3, 'TOXIQUE', 0),
		(2, 4, 'INTERDIT DE FUMER', 0),

		(3, 1, 'INFORMATION', 0),
		(3, 2, 'INTERDIT DE TELEPHONER', 0),
		(3, 3, 'TOXIQUE', 1),
		(3, 4, 'INTERDIT DE FUMER', 0),

		(4, 1, 'INFORMATION', 0),
		(4, 2, 'INTERDIT DE TELEPHONER', 1),
		(4, 3, 'TOXIQUE', 0),
		(4, 4, 'INTERDIT DE FUMER', 0),

		(5, 1, 'VOTRE', 0),
		(5, 2, 'nom ', 0),
		(5, 3, 'EST', 0),
		(5, 4, 'quel', 1),

		(6, 1, 'nom', 0),
		(6, 2, 'votre', 1),
		(6, 3, 'est', 0),
		(6, 4, 'quel', 0),

		(7, 1, 'UNE CARTE DE PAIEMENT', 0),
		(7, 2, 'UNE CARTE DE SEJOUR', 0),

		(8, 1, 'UN AEROPORT', 0),
		(8, 2, 'UN PASSEPORT', 1),
		(8, 3, 'UN TRANSPORT', 0),

		(9, 1, 'UNE VOITURE', 0),
		(9, 2, 'UN FACTEUR', 0),
		(9, 3, 'UNE FACTURE', 1),

		(10, 1, '06.21.76.66.77', 0),
		(10, 2, '03/12/2015', 1),
		(10, 3, '2 53 07 75 076 004 83', 0),
		(10, 4, 'BA-354-KP 76', 0),

		(11, 1, '06.21.46.66.77', 0),
		(11, 2, '03/12/2015', 0),
		(11, 3, '2 53 07 75 073 004 83', 0),
		(11, 4, 'BA-354-KP 76', 1),

		(12, 1, '06.21.46.66.77', 1),
		(12, 2, '03/12/2015', 0),
		(12, 3, '2 53 07 75 073 004 83', 0),
		(12, 4, 'BA-354-KP 76', 0),

		(13, 1, '06.21.46.66.77', 0),
		(13, 2, '03/12/2015', 0),
		(13, 3, '2 53 07 75 073 004 83', 1),
		(13, 4, 'BA-354-KP 76', 0),

		(15, 1, '1', 0),
		(15, 2, '2', 1),
		(15, 3, '3', 0),
		(15, 4, '4', 0),
		(15, 5, '5', 0),

		(16, 1, '1', 1),
		(16, 2, '2', 0),
		(16, 3, '3', 0),
		(16, 4, '4', 0),
		(16, 5, '5', 0),

		(17, 1, '1', 0),
		(17, 2, '2', 0),
		(17, 3, '3', 1),
		(17, 4, '4', 0),
		(17, 5, '5', 0),

		(18, 1, '1', 0),
		(18, 2, '2', 0),
		(18, 3, '3', 0),
		(18, 4, '4', 0),
		(18, 5, '5', 1),

		(19, 1, '1', 0),
		(19, 2, '2', 0),
		(19, 3, '3', 0),
		(19, 4, '4', 1),
		(19, 5, '5', 0),

		(20, 1, '1', 0),
		(20, 2, '2', 0),
		(20, 3, '3', 0),
		(20, 4, '4', 1),
		(20, 5, '5', 0),

		(21, 1, '1', 1),
		(21, 2, '2', 0),
		(21, 3, '3', 0),
		(21, 4, '4', 0),
		(21, 5, '5', 0),

		(22, 1, '1', 0),
		(22, 2, '2', 1),
		(22, 3, '3', 0),
		(22, 4, '4', 0),
		(22, 5, '5', 0),

		(23, 1, '1', 0),
		(23, 2, '2', 0),
		(23, 3, '3', 1),
		(23, 4, '4', 0),
		(23, 5, '5', 0),

		(24, 1, '1', 0),
		(24, 2, '2', 0),
		(24, 3, '3', 0),
		(24, 4, '4', 0),
		(24, 5, '5', 1),

		(25, 1, '1-2-3-4', 0),
		(25, 2, '1-3-2-4', 0),
		(25, 3, '2-3-1-4', 1),
		(25, 4, '2-3-4-1', 0),

		(26, 1, '19 avril au 4 mai', 0),
		(26, 2, '13 avril au 27 avril', 1),
		(26, 3, '26 avril au 11 mai', 0),

		(27, 1, 'Kathy', 0),
		(27, 2, 'Rose', 1),
		(27, 3, 'Philippe', 0),
		(27, 4, 'Paul', 0),

		(28, 1, 'PAUL', 0),
		(28, 2, 'PHILIPPE', 0),
		(28, 3, 'ROSE', 0),
		(28, 4, 'KATHY', 1),

		(29, 1, 'LE MARI DE KATHY', 0),
		(29, 2, 'LE FILS DE ROSE', 0),
		(29, 3, 'LE MARI DE ROSE', 1);




		