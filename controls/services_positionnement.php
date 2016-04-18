<?php


require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_resultats.php');
require_once(ROOT.'controls/services_admin_categorie.php');

require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/reponse_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
//require_once(ROOT.'models/dao/question_cat_dao.php');
//require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/organisme_dao.php');

require_once(ROOT.'utils/mailsender.php');



/*** Attention : logout() désactivé ***/

class ServicesPositionnement extends Main
{

	private $sessionDAO = null;
	private $utilisateurDAO = null;
	private $intervenantDAO = null;
	private $questionDAO = null;
	private $reponseDAO = null;
	private $resultatDAO = null;
	//private $questionCatDAO = null;
	//private $categorieDAO = null;
	private $organismeDAO = null;

	private $servicesResultats = null;
	private $servicesCategories = null;


	private $sendMail = null;
	
	
	
	public function __construct()
	{
		$this->initialize();
		//$this->errors = array();
		$this->controllerName = "positionnement";
		
		$this->sessionDAO = new SessionDAO();
		$this->utilisateurDAO = new UtilisateurDAO();
		$this->intervenantDAO = new IntervenantDAO();
		$this->questionDAO = new QuestionDAO();
		$this->reponseDAO = new ReponseDAO();
		$this->resultatDAO = new ResultatDAO();
		//$this->questionCatDAO = new QuestionCategorieDAO();
		//$this->categorieDAO = new CategorieDAO();
		$this->organismeDAO = new OrganismeDAO();

		$this->servicesResultats = new ServicesPosiResultats();
		$this->servicesCategories = new ServicesAdminCategorie();
	}
	
	
	
	
	
	public function intro()
	{
		/*** Test d'authentification de l'intervenant/utilisateur ***/
		
		ServicesAuth::checkAuthentication("user");
		
		$numPage = ServicesAuth::getSessionData("num_page");
		if ($numPage)
		{
			// Redirection vers la dernière page du positionnement visitée
			header("Location: ".SERVER_URL."positionnement/page");
			exit();
		}
		else 
		{
			ServicesAuth::setSessionData("num_page", 0);
		}
		
		
		// $returnData = array();
		// $returnData['response'] = array();
		// $returnData['response']['errors'] = array();
		
		$this->url = SERVER_URL."positionnement/session";
		//$returnData['response'] = array('url' => $url);
		
		
		/*** Il faut récupérer le nombre de questions ***/
		$resultset = $this->questionDAO->selectAll();
			
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			// Le nombre est enregistré dans la session
			$this->returnData['response']['nbre_questions'] = count($resultset['response']['question']);
		}

		$this->returnData['response']['url'] = $this->url;
		//$this->setResponse($returnData);
		//$this->setTemplate("tpl_inscript");
		//$this->render("intro");

		$this->setResponse($this->returnData);
			
		$this->setTemplate("tpl_basic_page");
		$this->setHeader("header_form_small");
		$this->setFooter("footer");

		$this->enqueueScript("flash_detect");
		$this->enqueueScript("navigator-agent");
		$this->enqueueScript("swfobject");
		$this->enqueueScript("audio-player");
		$this->enqueueScript("pages/intro");
		
		$this->render("intro");
	}
	
	
	
	public function session()
	{
		
		/*** Test d'authentification de l'intervenant/utilisateur ***/
		ServicesAuth::checkAuthentication("user");
		
		// On test si l'utilisateur est déjà dans une session, c-à-d si il a déjà cliqué sur le bouton suite de la page d'intro
		if (!ServicesAuth::checkUserSession())
		{
			// Si ce n'est pas le cas, on ouvre une session
			ServicesAuth::openUserSession();
			
			// Il faut savoir combien de questions possède le questionnaire
			$resultset = $this->questionDAO->selectAll();
			
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']))
			{
				// Le nombre est enregistré dans la session
				$total = count($resultset['response']['question']);
				ServicesAuth::setSessionData("nbre_questions", $total);
			}
			
			
			/*-----   Enregistrement des infos de départ de la session : ref_user, date, validation  -----*/

			// Récupération des infos necéssaires
			$refUser = ServicesAuth::getSessionData("ref_user");
			$refIntervenant = ServicesAuth::getSessionData("ref_intervenant");
			  
			$dateSession = date("Y-m-d H:i:s");
			ServicesAuth::setSessionData("date_session", $dateSession);

			$dataSession = array(
				'ref_user' => $refUser,
				'ref_intervenant' => $refIntervenant,
				'date_session' => $dateSession,
				'session_accomplie' => 0,
				'temps_total' => "0"
			);


			// Insertion dans la table session
			$resultset = $this->sessionDAO->insert($dataSession);


			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['session']['last_insert_id']) && !empty($resultset['response']['session']['last_insert_id']))
			{
				ServicesAuth::setSessionData("ref_session", $resultset['response']['session']['last_insert_id']);
			}
			else 
			{
				$this->registerError("form_request", "La session n'a pu être insérée.");
			}
			
			
			// Mise à jour du nbre de sessions de l'utilisateur


			$resultsetUser = $this->utilisateurDAO->selectById($refUser);
			
			if (!$this->filterDataErrors($resultsetUser['response']))
			{	
				$nbreUserSession = $resultsetUser['response']['utilisateur']->getSessionsTotales();
				$dataUser['nbre_sessions_totales'] = intval($nbreUserSession) + 1;
				$dataUser['ref_user'] = $refUser;
				
				// On met a jour la table "utilisateur"
				$resultset = $this->utilisateurDAO->update($dataUser);
			}
			
		}


		// S'il n'y a aucune erreur
		if (empty($this->errors)) 
		{
			ServicesAuth::setSessionData("num_page", 1);
			ServicesAuth::setSessionData("page_reset", false);
			
			// Redirection vers la première page du positionnement
			header("Location: ".SERVER_URL."positionnement/page");
			exit();
		}
		else 
		{
			// Redirection vers la page d'erreur interne
			header("Location: ".SERVER_URL."erreur/page500");
			exit();
		}

	}
	
	
	
	
	
	
	public function page()
	{
		
		/*** Test d'authentification de l'intervenant/utilisateur ***/ 
		ServicesAuth::checkAuthentication("user");
		
		
		/*** Gestion du temps de réponse de l'utilisateur ***/
		
		$totalTime = 0;
		// On stop le timer (fin du temps de réponse)
		$endTimer = microtime(true);
		
		// On récupère le temps de départ si il existe et on établit le temps total de réponse
		if (isset($_POST['start_timer']) && !empty($_POST['start_timer']))
		{
			$startTimer = $_POST['start_timer'];
			
			// le temps total est arrondi en millisecondes
			$totalTime = $endTimer - $startTimer;
		}
		
		
		/*** Récupération du numero de la page courante ***/
		
		$pageCourante = 1;
		
		if (isset($_POST['num_page']) && !empty($_POST['num_page']))
		{
			$pageCourante = $_POST['num_page'];
		
			if (ServicesAuth::getSessionData("num_page") == $_POST['num_page'])
			{
				$pageCourante++;
				ServicesAuth::setSessionData("page_reset", false);
			}
			else 
			{
				$pageCourante = ServicesAuth::getSessionData("num_page");
				ServicesAuth::setSessionData("page_reset", true);
			}
		}
		else
		{
			$pageCourante = ServicesAuth::getSessionData("num_page");
		}
		
		$numeroOrdre = $pageCourante;
		
		ServicesAuth::setSessionData("num_page", $numeroOrdre);

		
		
		/*** On récupère le nombre de questions totales ***/
		
		$nbreQuestions = ServicesAuth::getSessionData("nbre_questions");  
			
		
		if (!ServicesAuth::getSessionData("page_reset"))
		{
			
			/*-----   Traitement des données de la réponse à la question qui vient d'être saisie pour insertion dans la base (table résultat)   -----*/

			if (!empty($_POST))
			{
				$dataResultat = array();

				$dataResultat['ref_session'] = ServicesAuth::getSessionData("ref_session");

				// On récupère la référence de la question
				if (isset($_POST['ref_question']) && !empty($_POST['ref_question']))
				{
					$dataResultat['ref_question'] = $_POST['ref_question'];
				}
				else 
				{
					$this->registerError("form_data", "La question n'est pas référencée.");
				}

				// On test si la réponse est de type qcm ou champ
				if (isset($_POST['reponse_qcm']) && !empty($_POST['reponse_qcm']))
				{
					$dataResultat['ref_reponse_qcm'] = $_POST['reponse_qcm'];

					// On récupère la référence de la bonne réponse
					if (isset($_POST['ref_reponse_correcte']) && !empty($_POST['ref_reponse_correcte']))
					{
						$dataResultat["ref_reponse_qcm_correcte"] = $_POST['ref_reponse_correcte'];
					}
				}
				else if (isset($_POST['reponse_champ']))
				{
					if (!empty($_POST['reponse_champ']))
					{
						$dataResultat['reponse_champ'] = $this->filterData($_POST['reponse_champ'], "string");
					}
					else
					{
						$dataResultat['reponse_champ'] = "";
					}
				}
				else
				{
					// Erreur enregistrement réponse utilisateur
					// Redirection vers la page d'erreur interne
					header("Location: ".SERVER_URL."erreur/page500");
					exit();
				}

				// La validation du résultat de la réponse n'est pas encore effectué
				$dataResultat['validation_reponse_champ'] = 0;

				// Récupération du temps total
				$dataResultat['temps_reponse'] = $totalTime;

				// Insertion de la réponse de l'utilisateur dans la table "resultat"

				$resultset = $this->resultatDAO->insert($dataResultat);
			}
		}
		
		/*----- Redirection fin de questionnaire vers la page résultat  -----*/
		
		// On évalue l'état d'avancement du questionnaire
		if ($numeroOrdre > $nbreQuestions)
		{
			// Redirection vers la page résultat
			header("Location: ".SERVER_URL."positionnement/resultat");
			exit();
		}
		
		
		/*-----   Chargement des infos de la question courante   -----*/
		
		$dataPage = array();
		$dataPage['response'] = array();

		
		// On va chercher dans la table "question", la question correspondant au numéro d'ordre (la page suivante)
		$resultsetQuestion = $this->questionDAO->selectByOrdre($numeroOrdre);
		
				
		// Traitement des erreurs de récupération de la question
		if (!$this->filterDataErrors($resultsetQuestion['response']))
		{

			$dataPage['response'] = array_merge($resultsetQuestion['response'], $dataPage['response']);
			
			// On commence par récupérer la référence de la question
			$refQuestion = $dataPage['response']['question']->getId();
			
			// Ensuite on va chercher les réponses
			if ($dataPage['response']['question']->getType() == "qcm")
			{
				$resultsetReponses = $this->reponseDAO->selectByQuestion($refQuestion);
				
				if (!$this->filterDataErrors($resultsetReponses['response']))
				{
					if (!empty($resultsetReponses['response']['reponse']) && count($resultsetReponses['response']['reponse']) == 1)
					{ 
						$reponse = $resultsetReponses['response']['reponse'];
						$resultsetReponses['response']['reponse'] = array($reponse);
					}
					
					$dataPage['response'] = array_merge($resultsetReponses['response'], $dataPage['response']);
				}
			} 

			// On passe à la page suivante
			if ($dataPage['response']['question']->getNumeroOrdre() <= $nbreQuestions)
			{
				$dataPage['response']['url'] = SERVER_URL."positionnement/page";
			}
			else
			{
				// Erreur page inexistante
				header("Location: ".SERVER_URL."erreur/page404");
				exit();
			}
		}
		else
		{
			// Redirection vers la page d'erreur interne

			header("Location: ".SERVER_URL."erreur/page500");
			exit();
		}
	  
		
		/*** Affichage de la page ***/
		
		$this->setResponse($dataPage);
		
		//$this->setTemplate("tpl_page");
		//$this->render("page");

		$this->setTemplate("tpl_basic_page");
		$this->setHeader("header_form");
		$this->setFooter("footer");

		$this->addStyleSheet("projekktor-dalia.style", SERVER_URL."media/projekktor/themes/dalia");

		// Outils
		//$this->enqueueScript("placeholders.min");
		$this->enqueueScript("flash_detect");
		$this->enqueueScript("navigator-agent");

		// Medias
		$this->enqueueScript("swfobject");
		$this->enqueueScript("projekktor-1.3.09.min", SERVER_URL."media/projekktor");
		$this->enqueueScript("image-controller");
		$this->enqueueScript("audio-player");

		$this->enqueueScript("pages/page");

		$this->render("page");
	}
	
	
	



	/**
	 * resultat - Traite et formate l'ensemble des données des résultats concernant le positionnement de la session en cours.
	 * Renvoie la page résultat et envoie un email avec les résultats à l'intervenant.
	 */

	public function resultat()
	{

		/*** Test d'authentification de l'utilisateur ***/
		//ServicesAuth::checkAuthentication("user");

		

		/**
		 * Description: Gestion et génération de la page résultat et de l'envoi d'email à l'intervenant.
		 * Last update: 07/01/2016
		 * Author: Dalia Team
		 *
		 * Summary:
		 *
		 *	1. Création du tableau des résultats détaillées 
		 *		- 1.1. Listing des categories
		 *		- 1.2. Récupération des résultats
		 * 		- 1.3. Gestion du temps
		 * 		- 1.4. Assignation des resultats au catégories
		 * 		- 1.5. Score global à partir des catégories
		 * 
		 *	2. Mises à jour des tables concernées
		 *		- 2.1. Mise à jour de la table "utilisateur"
		 *		- 2.2. Mise à jour de la table "organisme"
		 * 		- 2.3. Mise à jour de la table "session"
		 * 
		 * 	3. Création et envoi du mail des résultats
		 * 		- 3.1. Collecte des informations
		 * 		- 3.2. Création du mail avec le template
		 * 		- 3.3. Envoi du mail
		 * 
		 * 	4. Synthèse des éléments à injecter dans la page
		 * 		- 4.1. Gestion des erreurs
		 * 		- 4.2. Assemblage des données
		 * 		- 4.3. Génération et affichage de la page résultat
		 * 
		 */



		/* ========================================================================================================
		   1. Création du tableau des résultats détaillées et attribution de ces résultats au catégories concernées
		   ======================================================================================================== */


		/* 1.1. Listing des categories
		   ========================================================================== */

		$categories = array();

		$resultset = $this->servicesResultats->getCategories();

		if ($resultset && !empty($resultset['response']['categorie']))
		{
			//$categoriesList = $resultset['response']['categorie'];
			$categories = $resultset['response']['categorie'];
			
			//$this->returnData['response'] = array_merge($resultset['response'], $this->returnData['response']);
			$this->returnData['response']['categorie'] = $categories;
		}


		/* Fin listing des categories */


		/* 1.2. Récupération du détail des résultats pour la session en cours
		   ========================================================================== */
		
		$resultats = array();
		
		$refSession = ServicesAuth::getSessionData('ref_session');

		if ($refSession)
		{
			$resultset = $this->servicesResultats->getResultats($refSession);

			if ($resultset && !empty($resultset['response']['resultat']))
			{
				$resultats = $resultset['response']['resultat'];
			}
		}


		// Boucle sur tous les résultats de chaque question de la session pour en obtenir les détails utiles

		$resultsDetails = array();
		$resultatTime = 0;
		$totalReponses = 0;
		$totalReponsesCorrectes = 0;

		$totalTime = 0;
		$i = 0;


		foreach ($resultats as $resultat)
		{ 

			// On ajoute le temps du résultat au temps total.
			$resultatTime = $resultat->getTempsReponse();
			$totalTime += $resultatTime;


			// Test type de question -> enregistrement des réponses correctes
			if ($resultat->getRefReponseQcm() !== null && $resultat->getRefReponseQcmCorrecte() !== null)
			{
				$totalReponses++;
				//$totalReponsesGlobal++;

				// Test si bonne réponse ou non

				if ($resultat->getRefReponseQcm() == $resultat->getRefReponseQcmCorrecte())
				{
					$resultsDetails[$i]['correct'] = true;
					$totalReponsesCorrectes++;
					//$totalReponsesCorrectesGlobal++;
				}
				else 
				{
					$resultsDetails[$i]['correct'] = false;
				}
			}
			else if ($resultat->getReponseChamp() !== null)
			{
				$totalReponses++;
				//$totalReponsesGlobal++;

				$resultsDetails[$i]['correct'] = true;
				$totalReponsesCorrectes++;
				//$totalReponsesCorrectesGlobal++;
			}
			else
			{
				$resultsDetails[$i] = null;
			}

			// Ensuite on va chercher les données sur la question correspondant au résultat

			$resultatCats = null;
			$resultsDetails[$i]['codes_cat'] = array();

			$resultset = $this->servicesResultats->getCategorieByQuestion($resultat->getRefQuestion());

			if ($resultset && !empty($resultset['response']['question_cat']))
			{
				$resultatCats = $resultset['response']['question_cat'];
			}
			
			foreach ($resultatCats as $resultatCat) 
			{
				if ($resultatCat->getCodeCat() !== null)
				{
					$resultsDetails[$i]['codes_cat'][] = $resultatCat->getCodeCat();
				}
			}

			$i++;
		}


		/* Fin récupération du détail des résultats */



		/* 1.3. Gestion du temps de passation
		   ========================================================================== */


		$stringTime = Tools::timeToString($totalTime);
		$this->returnData['response']['temps_total'] = $stringTime;


		/* Fin gestion du temps */



		/* 1.4. Assignation des resultats au catégories
		   ========================================================================== */


		/*** Calcul du nombre total de questions par catégories et déduction du nombre de bonnes réponses pour chaque catégorie.  ***/

		foreach ($categories as $categorie)
		{
			// On récupére le niveau de la catégorie dans la hiérarchie
			$level = $this->servicesCategories->getLevel($categorie->getCode());
			
			// Test d'existence d'une catégorie parente
			
			if ($level > 1)
			{
				foreach ($categories as $searchParentCat)
				{
					if ($searchParentCat->getCode() == $categorie->getParentCode())
					{
						// Si catégorie parente, la courante devient son enfant
						//$searchParentCat->addChild($categorie);

						$categorie->setParent($searchParentCat);
					}
				}
			}
			
			
			// On attribut à chaque catégorie, les réponses et les scores à partir des résultats
			
			for ($i = 0; $i < count($resultsDetails); $i++)  
			{
				foreach ($resultsDetails[$i]['codes_cat'] as $code_cat) 
				{
					if ($code_cat == $categorie->getCode())
					{
						// Attribution du nombre de réponses et de réponses correctes par ajouts successifs sur l'objet 'categorie'
						$nbreReponses = ($categorie->getTotalReponses() !== null) ? $categorie->getTotalReponses() : 0;
						$nbreReponsesCorrectes = ($categorie->getTotalReponsesCorrectes() !== null) ? $categorie->getTotalReponsesCorrectes() : 0;

						$nbreReponses++;
						$categorie->setTotalReponses($nbreReponses);

						if ($resultsDetails[$i]['correct'])
						{
							$nbreReponsesCorrectes++;
						}
						$categorie->setTotalReponsesCorrectes($nbreReponsesCorrectes);

						// Calcul du score
						if ($nbreReponsesCorrectes != 0)
						{	
							$scoreCat = round(($nbreReponsesCorrectes / $nbreReponses) * 100);
							$categorie->setScorePercent($scoreCat);
						}
						else
						{
							$categorie->setScorePercent(0);
						}

						$categorie->setHasResult(true);
					}
					
				}
			}
			
			//unset($categorie);
		}
		// var_dump($categories);
		// exit();

		// Cette fois-ci, il s'agit de répercuter les résultats de chaque catégorie sur sa propre catégorie parente

		//end($categories);
		/*
		foreach ($categories as $categorie)
		{
			if ($categorie->getHasResult(true) && $categorie->getParent() !== null) 
			{
				$parentCat = $categorie->getParent();
				$parentCat->setHasResult(true);

				$nbreReponses = ($categorie->getTotalReponses() !== null) ? $categorie->getTotalReponses() : 0;
				$nbreReponsesParent = ($parentCat->getTotalReponses() !== null) ? $parentCat->getTotalReponses() : 0;
				$nbreReponsesParent += $nbreReponses;
				$parentCat->setTotalReponses($nbreReponsesParent);

				$nbreReponsesCorrectes = ($categorie->getTotalReponsesCorrectes() !== null) ? $categorie->getTotalReponsesCorrectes() : 0;
				$nbreReponsesCorrectesParent = ($parentCat->getTotalReponsesCorrectes() !== null) ? $parentCat->getTotalReponsesCorrectes() : 0;
				$nbreReponsesCorrectesParent += $nbreReponsesCorrectes;
				$parentCat->setTotalReponsesCorrectes($nbreReponsesCorrectesParent);

				// Calcul du score
				if ($nbreReponsesCorrectesParent != 0)
				{	
					$scoreParentCat = round(($nbreReponsesCorrectesParent / $nbreReponsesParent) * 100);
					$parentCat->setScorePercent($scoreParentCat);
				}
				else
				{
					$categorie->setScorePercent(0);
				}
			}
		}
		*/
		$maxLevel = 0;

		foreach ($categories as $categorie)
		{
			$level = $this->servicesCategories->getLevel($categorie->getCode());

			if ($level > $maxLevel)
			{
				$maxLevel = $level;
			}
		}

		$recursiveCategoriesResults = $this->servicesResultats->getRecursiveCategoriesResults($maxLevel, $categories);

		//var_dump($recursiveCategoriesResults);
		//exit();


		// Enfin, on attribue aux resultats les catégories détaillées correspondantes
		/*
		for ($i = 0; $i < count($resultsDetails); $i++)  
		{
			$resultsDetails[$i]['categories'] = array();

			for ($j = 0; $j < count($resultsDetails[$i]['codes_cat']); $j++)  
			{
				foreach ($categories as $categorie)
				{
					if ($resultsDetails[$i]['codes_cat'][$j] == $categorie->getCode()) 
					{	
						array_push($resultsDetails[$i]['categories'], $categorie);
					}
				}

				unset($resultsDetails[$i]['codes_cat'][$j]);
				
			}
			unset($resultsDetails[$i]['codes_cat']);
		}
		*/
		//var_dump($resultsDetails);
		//exit();


		// On injecte le tout dans la réponse

		//$this->returnData['response']['resultats'] = $resultsDetails;

		/* Fin assignation des resultats au catégories */



		/* 1.5. Score global à partir des catégories
		   ========================================================================== */


		$totalReponsesGlobal = 0;
		$totalReponsesCorrectesGlobal = 0;
		$scoreGlobal = 0;
		$scoreCats = 0;

		$nbCats = 0;

		foreach ($categories as $categorie)
		{
			$level = $this->servicesCategories->getLevel($categorie->getCode());

			if ($level == 1 && $categorie->getHasResult())
			{
				$scoreCats += $categorie->getScorePercent();
				$totalReponsesGlobal += $categorie->getTotalReponses();
				$totalReponsesCorrectesGlobal += $categorie->getTotalReponsesCorrectes();

				$nbCats++;
			}

		}

		$scoreGlobal = $scoreCats / $nbCats;

		$this->returnData['response']['total_reponses'] = $totalReponsesGlobal;
		$this->returnData['response']['total_reponses_correctes'] = $totalReponsesCorrectesGlobal;
		$this->returnData['response']['total_score'] = round($scoreGlobal);


		/* Fin score global */





		/* ===============================================================================
		   2. Mises à jour des tables concernées et collecte des informations pour le mail
		   =============================================================================== */


		/* 2.1. Mise à jour du nbre de sessions accomplies ds la table "utilisateur"
		   ========================================================================== */


		$dataUser = array();
		$refUser = ServicesAuth::getSessionData('ref_user');

		if ($refUser)
		{
			// Sélection de l'utilisateur
			$resultset = $this->servicesResultats->getUser($refUser);

			if ($resultset && !empty($resultset['response']['utilisateur']))
			{
				$dataUser['ref_user'] = $refUser;
				$nbrePosiUser = $resultset['response']['utilisateur'][0]->getSessionsAccomplies();
				$dataUser['nbre_sessions_accomplies'] = intval($nbrePosiUser) + 1;

				// Sauvegarde de l'utilisateur pour l'envoi du mail
				$infosUser = $resultset['response']['utilisateur'];

				// On met a jour la table "utilisateur"
				$resultsetUpdate = $this->servicesResultats->updateUser($dataUser);

				if (!$resultsetUpdate)
				{
					$this->registerError("form_request", "Erreur lors de la mise à jour de l'utilisateur.");
				}
			}
		}

		/* Fin mise à jour utilisateur */



		/* 2.2. Mise à jour du nbre de sessions accomplies ds la table "organisme"
		   ========================================================================== */
		

		$dataOrgan = array();
		$refOrgan = ServicesAuth::getSessionData('ref_organ');

		if ($refOrgan)
		{
			// Sélection de l'organisme
			$resultset = $this->servicesResultats->getOrganisme($refOrgan);

			if ($resultset && !empty($resultset['response']['organisme']))
			{
				$dataOrgan['ref_organ'] = $refOrgan;
				$nbrePosiOrgan = $resultset['response']['organisme'][0]->getNbrePosiTotal();
				$dataOrgan['nbre_posi_total'] = intval($nbrePosiOrgan) + 1;

				// Sauvegarde de l'organisme pour l'envoi du mail
				$infosOrgan = $resultset['response']['organisme'];

				// On met a jour la table "organisme"
				$resultsetUpdate = $this->servicesResultats->updateOrganisme($dataOrgan);

				if (!$resultsetUpdate)
				{
					$this->registerError("form_request", "Erreur lors de la mise à jour de l'organisme.");
				}
			}
		}

		/* Fin mise à jour de l'organisme */

		

		/* 2.3. Mise à jour de la table "session"
		   ========================================================================== */

		$dataSession = array();
		//$dataSession['ref_session'] = $refSession;
		$dataSession['session_accomplie'] = 1;
		$dataSession['temps_total'] = $totalTime;
		$dataSession['score_pourcent'] = $scoreGlobal;

		if ($refSession)
		{
			// Sélection de l'organisme
			$resultset = $this->servicesResultats->getSession($refSession);

			if ($resultset && !empty($resultset['response']['session']))
			{

				// Sauvegarde de la session pour l'envoi du mail
				$infosSession = $resultset['response']['session'];

				$resultsetUpdate = $this->servicesResultats->updateSession($dataSession, $refSession);

				if (!$resultsetUpdate)
				{
					$this->registerError("form_request", "Erreur lors de la mise à jour de la session.");
				}
			}
		}

		/* Fin mise à jour de la session */



		/* ==========================================================================
		   3. Création et envoi du mail des résultats
		   ========================================================================== */


		/*** On va chercher toutes les infos pour l'envoi d'emails au référent du positionnement et à l'équipe admin ***/
		
		$emailInfos = array();

		$emailInfos['nom_organ'] = "";
		$codeOrgan = "";
		$emailInfos['url_restitution'] = "";
		$emailInfos['url_stats'] = "";
		$emailInfos['code_postal_organ'] = "";
		$emailInfos['tel_organ'] = "";

		$emailInfos['nom_user'] = "";
		$emailInfos['prenom_user'] = "";

		$emailInfos['email_intervenant'] = "";

		$emailInfos['date_posi'] = "";
		$emailInfos['temps_posi'] = "";


		// Email -> infos organisme

		$refOrgan = ServicesAuth::getSessionData('ref_organ');
		$resultsetOrgan = $this->organismeDAO->selectById($refOrgan);

		if (!$this->filterDataErrors($resultsetOrgan['response']))
		{
			// Si le résultat est unique
			if (!empty($resultsetOrgan['response']['organisme']) && count($resultsetOrgan['response']['organisme']) == 1)
			{
				$organisme = $resultsetOrgan['response']['organisme'];
				$resultsetOrgan['response']['organisme'] = array($organisme);
			}

			$emailInfos['nom_organ'] = $resultsetOrgan['response']['organisme'][0]->getNom();

			$codeOrgan = $resultsetOrgan['response']['organisme'][0]->getNumeroInterne();
			$emailInfos['url_restitution'] = SERVER_URL."public/restitution/".$codeOrgan;
			$emailInfos['url_stats'] = SERVER_URL."public/statistique/".$codeOrgan;

			$emailInfos['code_postal_organ'] = $resultsetOrgan['response']['organisme'][0]->getCodePostal();
			$emailInfos['tel_organ'] = $resultsetOrgan['response']['organisme'][0]->getTelephone();
		}


		// Email -> infos utilisateur

		$refUser = ServicesAuth::getSessionData('ref_user');
		$resultsetUser = $this->utilisateurDAO->selectById($refUser);

		if (!$this->filterDataErrors($resultsetUser['response']))
		{
			// Si le résultat est unique
			if (!empty($resultsetUser['response']['utilisateur']) && count($resultsetUser['response']['utilisateur']) == 1)
			{ 
				$utilisateur = $resultsetUser['response']['utilisateur'];
				$resultsetUser['response']['utilisateur'] = array($utilisateur);
			}

			$emailInfos['nom_user'] = $resultsetUser['response']['utilisateur'][0]->getNom();
			$emailInfos['prenom_user'] = $resultsetUser['response']['utilisateur'][0]->getPrenom();
		}


		// Email -> infos intervenant

		$emailInfos['email_intervenant'] = "";
		$refInter = ServicesAuth::getSessionData('ref_intervenant');
		$resultsetInter = $this->intervenantDAO->selectById($refInter);

		if (!$this->filterDataErrors($resultsetInter['response']))
		{
			// Si le résultat est unique
			if (!empty($resultsetInter['response']['intervenant']) && count($resultsetInter['response']['intervenant']) == 1)
			{ 
				$intervenant = $resultsetInter['response']['intervenant'];
				$resultsetInter['response']['intervenant'] = array($intervenant);
			}

			$emailInfos['email_intervenant'] = $resultsetInter['response']['intervenant'][0]->getEmail();
		}


		// Email -> infos détails du positionnement
		
		$date_posi = substr(ServicesAuth::getSessionData('date_session'), 0, 10);
		$time_posi = substr(ServicesAuth::getSessionData('date_session'), 10);
		$emailInfos['date_posi'] = Tools::toggleDate($date_posi, 'fr').' '.$time_posi;
		$emailInfos['temps_posi'] = $stringTime;


		//$dataView['response']['email_infos'] = $emailInfos;
		

		/* Configuration du mail */
		
		$destinataires = array();

		if (!empty(Config::$main_email_admin)) 
		{
			$destinataires[] = Config::$main_email_admin;
		}
			
		foreach (Config::$emails_admin as $email_admin) 
		{
			if (!empty(Config::$main_email_admin) && Config::$main_email_admin == $email_admin) 
			{
				// Ne rien faire
			}
			else
			{
				$destinataires[] = $email_admin;
			}
		}

		if (Config::ENVOI_EMAIL_REFERENT == 1 && isset($emailInfos['email_intervenant']) && !empty($emailInfos['email_intervenant'])) 
		{
			$destinataires[] = $emailInfos['email_intervenant'];
		}

		$from = !empty(Config::$main_email_admin) ? Config::$main_email_admin : "f.rampion@educationetformation.fr";
		$subject = Config::POSI_NAME.' '.Config::CLIENT_NAME_LONG;



		/* Création du mail */
		
		$messageBody = '';
		$messageBody .= '<p>';
		$messageBody .= 'Date du positionnement : <strong>'.$emailInfos['date_posi'].'</strong><br />';
		$messageBody .= 'Organisme : <strong>'.$emailInfos['nom_organ'].'</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Email intervenant : <strong>'.$emailInfos['email_intervenant'].'</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Nom : <strong>'.$emailInfos['nom_user'].'</strong><br />';
		$messageBody .= 'Prénom : <strong>'.$emailInfos['prenom_user'].'</strong>';
		//$messageBody .= 'Email intervenant : <strong>'.$emailInfos['email_intervenant'].'</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Temps : <strong>'.$emailInfos['temps_posi'].'</strong><br />';
		$messageBody .= 'Score globale : <strong>'.round($scoreGlobal).' %</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Score détaillé : <br />';

		foreach ($categories as $categorie)
		{	
			if (strlen($categorie->getCode()) == 2 && $categorie->getHasResult())
			{
				$messageBody .= '</br>';
				$messageBody .= $categorie->getNom().' / <strong>'.$categorie->getScorePercent().'</strong>% ('.$categorie->getTotalReponsesCorrectes().'/'.$categorie->getTotalReponses().' questions)';
			}
		}
		$messageBody .= '</p>';
		$messageBody .= '<br />';
		$messageBody .= '<p>';
		$messageBody .= 'Votre accès à la page des résultats : <br />'.$emailInfos['url_restitution'].'<br />';
		$messageBody .= 'Votre accès à la page des statistiques : <br />'.$emailInfos['url_stats'].'<br />';
		$messageBody .= '</p>';

		$style = 'p { font-family: Arial, sans-serif; }';




		$this->sendMail = new MailSender($destinataires, $from, $subject);
		$this->sendMail->setHeader('1.0', 'text/html', 'utf-8');
		$this->sendMail->setMessage($messageBody, 'html', Config::POSI_NAME.' '.Config::CLIENT_NAME_LONG, $style);
		

		/*** Envoi du mail ***/

		$sending = $this->sendMail->send();

		//var_dump($this->sendMail->getIsSend());
		//var_dump($sending);
		//exit();


		/* ==========================================================================
		   4. Synthèse des éléments à injecter dans la page
		   ========================================================================== */

		//$dataView = array('response');
		
		/*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
		/*
		if ((!empty($this->servicesQuestion->errors) && count($this->servicesQuestion->errors) > 0) || !empty($this->errors))
		{
			$this->errors = array_merge($this->servicesQuestion->errors, $this->errors);
			foreach($this->errors as $error)
			{
				$this->returnData['response']['errors'][] = $error;
			}
		}
		else if ((!empty($this->servicesQuestion->success) && count($this->servicesQuestion->success) > 0) || !empty($this->success))
		{
			$this->success = array_merge($this->servicesQuestion->success, $this->success);
			foreach($this->success as $success)
			{
				$this->returnData['response']['success'][] = $success;
			}
		}
		*/

		// 
		/* Assemblage des données de la réponse à envoyer à la page de résultats
		   ========================================================================== */

		//$this->returnData['response'] = array_merge($dataView['response'], $this->returnData['response']);

		/*** Gestion des erreurs ***/		

		if (!empty($this->errors))
		{
			// S'il y a eu des erreurs, on les affiche dans la page "résultat".
			$this->returnData['response']['errors'] = $this->errors;
		}
		

		/*** Déconnexion automatique de l'utilisateur ***/
		//ServicesAuth::logout();
		

		/*** Affichage de la page de résultat ***/
		//$this->setResponse($this->returnData);
		
		//$this->setTemplate("tpl_results");
		//$this->render("resultat");


		$this->setResponse($this->returnData);
		
		//$this->setTemplate("tpl_page");
		//$this->render("page");

		$this->setTemplate("tpl_basic_page");
		$this->setHeader("header_form");
		$this->setFooter("footer");

		// Outils
		//$this->enqueueScript("navigator-agent");

		$this->enqueueScript("pages/resultat");

		$this->render("resultat");
	}

}

?>