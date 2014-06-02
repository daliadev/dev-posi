


INSERT INTO categorie (code_cat, nom_cat)
	VALUES 	("10", "Oral"),
			("20", "Ecrit"),
			("30", "Calcul"),
			("40", "Espace-temps"),
			("50", "Geste - Posture - Observation");

			
INSERT INTO question (id_question, ref_degre, num_ordre_question, type_question, intitule_question, image_question, audio_question) 
VALUES 	(1, 1, 1, 'qcm', 'Comment s&#39;appelle ce type de document ?', '531dcabea1ec4.jpg', '5321afc001d55.mp3'),
		(2, 1, 2, 'qcm', 'Comment s&#39;appelle ce type de document ?', '531dcb74d3cf0.jpg', '5321b2281c8d3.mp3'),
		(3, 2, 3, 'qcm', 'Retrouvez votre point d&#39;arrivée sur le plan.', '531dd3f224c14.jpg', '5321b23af3995.mp3'),
		(4, 3, 4, 'qcm', 'Vous cherchez le chemin le plus court. Sur le plan vous avez 3 propositions. Pour vous, lequel est le plus court ?', '531dd477501dd.jpg', '5321b1e25d5c2.mp3'),
		(5, 3, 5, 'qcm', 'Vous savez qu&#39;entre votre point de départ et la croix, vous devez marcher 15 minutes. Pour vous rendre sur votre lieu de travail, combien de temps allez-vous mettre ?', '531dd52a67fb4.jpg', '5321b2029d8a3.mp3'),
		(6, 2, 6, 'qcm', 'Vous commencez le travail à 8 heures.Pour être sûr d&#39;arriver à l&#39;heure, vous partez 45 minutes plus tôt. A quelle heure partez-vous ?', '531dd73cb0481.jpg', '5321b49743e4c.mp3'),
		(7, 2, 7, 'champ_saisie', 'Avant de partir de chez vous, vous écrivez un message à votre conjoint (e) pour lui demander d&#39;acheter du pain et lui dire à quelle heure vous rentrerez ce soir. Ecrivez votre message ci-dessous :', '531dd7d29fe8b.jpg', '5321b4b0d1371.mp3'),
		(8, 1, 8, 'qcm', 'Vous êtes arrivé au lycée dans lequel vous travaillez. Que signifie ce panneau ?', '531dd8c60e67a.jpg', '5321b4d45f090.mp3'),
		(9, 1, 9, 'qcm', 'Vous êtes arrivé au lycée dans lequel vous travaillez. Que signifie ce panneau ?', '531dda3d949f1.jpg', '5321b4ed7549f.mp3'),
		(10, 1, 10, 'qcm', 'Vous êtes arrivé au lycée dans lequel vous travaillez. Que signifie ce panneau ?', '531ddad5c7679.jpg', '5321b4ff1e29b.mp3'),
		(11, 1, 11, 'qcm', 'Vous êtes arrivé au lycée dans lequel vous travaillez. Que signifie ce panneau ?', '531ddb80b1665.jpg', '5321b511187bd.mp3'),
		(12, 1, 12, 'qcm', 'Vous êtes arrivé au lycée dans lequel vous travaillez. Que signifie ce panneau ?', '531ddbd10e150.jpg', '5321b5296fdfd.mp3'),
		(13, 2, 13, 'qcm', 'Vous allez entendre un texte de présentation du proviseur de votre lycée. La carte de visite que vous voyez ci-dessous est-elle la bonne ?', '531ddcbb537df.jpg', '5321b5926321a.mp3'),
		(14, 2, 14, 'qcm', 'Vous allez entendre un texte de présentation du proviseur de votre lycée. La carte de visite que vous voyez ci-dessous est-elle la bonne ?', '531ddd00a967a.jpg', '5321b5a1da895.mp3'),
		(15, 2, 15, 'qcm', 'Vous allez entendre un texte de présentation du proviseur de votre lycée. La carte de visite que vous voyez ci-dessous est-elle la bonne ?', '531ddd7ba35dc.jpg', '5321b5bc66a5f.mp3'),
		(16, 3, 16, 'qcm', 'D&#39;après ce contrat, quel est le poste occupé par l&#39;agent ?', '531dde3d33dd6.jpg', '5321b5d584d61.mp3'),
		(17, 3, 17, 'qcm', 'Quelle la date de démarrage du contrat ?', '531ddebf82613.jpg', '5321b5f285e94.mp3'),
		(18, 3, 18, 'qcm', 'Quelle est la durée de la formation de professionnalisation ?', '531ddf3f21262.jpg', '5321b60bad943.mp3'),
		(19, 3, 19, 'qcm', 'A quelle date se termine le contrat ?', '531ddfb56df0b.jpg', '5321b65fe59d3.mp3'),
		(20, 3, 20, 'qcm', 'Dans cet article de Loi n° 83-634 du 13 juillet 1983 portant sur les droits et obligations du fonctionnaire, s&#39;agit-il ?', '531de0813bfc1.jpg', '5321b655a0ba5.mp3'),
		(21, 3, 21, 'qcm', 'Dans cet extrait d&#39;un article de Loi n°83-634 du 13 juillet 1983 portant sur les droits et obligations du fonctionnaire, s&#39;agit-il ?', '531de10f64cc3.jpg', '5321b68c059b9.mp3'),
		(22, 2, 22, 'qcm', 'Voici le planning de votre semaine de travail.A quelle heure commencez-vous à travailler le mardi ?', '531de381e25ba.jpg', '5321b6a16c615.mp3'),
		(23, 2, 23, 'qcm', 'A quelle heure terminez-vous le travail jeudi ?', '531de35e35e95.jpg', '5321b6fbc9414.mp3'),
		(24, 2, 24, 'qcm', 'Combien de temps avez-vous pour déjeuner ?', '531de28dcccd0.jpg', '5321b714700f8.mp3'),
		(25, 2, 25, 'qcm', 'Combien d&#39;heures travaillez-vous le lundi ?', '531de32daf1b5.jpg', '5321b6ef8c200.mp3'),
		(26, 2, 26, 'qcm', 'Combien d&#39;heures travaillez-vous le mardi ?', '531de3dc49da0.jpg', '5321b72bdc3b9.mp3'),
		(27, 2, 27, 'qcm', 'Quel est le jour de la semaine où vous travaillez le moins d&#39;heure ?', '531de489d1e28.jpg', '5321b743d6911.mp3'),
		(28, 2, 28, 'qcm', 'Combien d&#39;heure travaillez-vous cette semaine ?', '531de517231c0.jpg', '5321b75b77de1.mp3'),
		(29, 2, 29, 'qcm', 'Vous gagnez 9.50€ de l&#39;heure. Vous avez travaillé 100 heures dans le mois. Combien allez-vous gagner ?', '531de5ae74aa6.jpg', '5321b7748629e.mp3'),
		(30, 2, 30, 'qcm', 'Sachant que vous avez travaillé 140 heures durant le mois et que vous gagnez 9.50€ de l&#39;heure. Combien allez-vous gagner ?', '531de620b0b7b.jpg', '5321b7877ec8b.mp3'),
		(31, 2, 31, 'qcm', 'Sachant que vous avez touché 1520€  et que vous gagnez 9.50€ de l&#39;heure. Combien d&#39;heures avez-vous travaillé dans le mois ?', '531de6a8f3135.jpg', '5321b79c55abc.mp3'),
		(32, 2, 32, 'qcm', 'Vous avez gagné au mois de septembre 1369.76€. L&#39;entretrise vous paye en chèque, quelle sera la somme en lettres ?', '531de7e3334d3.jpg', '5321b7bbb4e0f.mp3'),
		(33, 3, 33, 'champ_saisie', 'Ecrivez dans le cadre ci-dessous votre courrier qui sera envoyé à M. DUMAS. Il sera joint à la &#34;Déclaration interne d&#39;accident du travail&#34;.', '531dec7d251f0.jpg', '5321b7e9065b2.mp3');