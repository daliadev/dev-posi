
--
-- Contenu de la table `question`
--

INSERT INTO `question` (`id_question`, `ref_posi`, `ref_degre`, `num_ordre_question`, `type_question`, `intitule_question`, `image_question`, `audio_question`, `video_question`) VALUES
(1, NULL, NULL, 3, 'qcm', 'Vous commencez le travail Ã  8 heures. Pour Ãªtre sÃ»r de ne pas Ãªtre en retard, vous partez 45 minutes plus tÃ´t. A quelle heure partez-vous ?', 'img_3_5669755257020.jpg', 'audio_3_5669755257bd8.mp3', NULL),
(2, NULL, 1, 1, 'qcm', 'Comment s&#39;appelle ce document ?', 'img_1_5669723605024.jpg', 'audio_1_5669723605bdc.mp3', NULL),
(3, NULL, NULL, 2, 'qcm', 'Retrouvez votre point d&#39;arrivÃ©e sur le plan.', 'img_2_566972360c93f.jpg', 'audio_2_566972360d4f7.mp3', NULL),
(4, NULL, 2, 4, 'champ_saisie', 'Avant de partir de chez vous, vous Ã©crivez un message Ã  votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire Ã Â  quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', 'img_4_56af663187a2d.jpg', 'audio_4_56af6631b0696.mp3', NULL),
(5, NULL, 3, 5, 'qcm', 'Vous Ãªtes arrivÃ© au lycÃ©e dans lequel vous travaillez. Que signifie ce panneau ?', 'img_5_56af67df9a0a3.jpg', 'audio_5_56af67dfb3ebe.mp3', NULL),
(6, NULL, 1, 6, 'qcm', 'Vous gagnez 9,50 euros de l&#39;heure. Vous avez travaillÃ© 100 heures dans le mois. Combien allez-vous gagner ?', 'img_6_56af687db3830.jpg', 'audio_6_56af687dd052c.mp3', NULL);


INSERT INTO question_cat (ref_question, ref_cat) VALUES 	
(1, '10'),
(2, '1010'),
(3, '1030'),
(4, '1010'),
(5, '1040'),
(6, '2010');



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

