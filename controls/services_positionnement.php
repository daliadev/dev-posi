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
		
		
		$returnData = array();
		$returnData['response'] = array();
		$returnData['response']['errors'] = array();
		
		$url = WEBROOT."positionnement/session";
		$returnData['response'] = array('url' => $url);
		
		
		/*** Il faut récupérer le nombre de questions ***/
		$resultset = $this->questionDAO->selectAll();
			
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			// Le nombre est enregistré dans la session
			$returnData['response']['nbre_questions'] = count($resultset['response']['question']);
		}

		$this->setResponse($returnData);
		$this->setTemplate("tpl_inscript");
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
				$dataPage['response']['url'] = WEBROOT."positionnement/page";
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
		
		$this->setTemplate("tpl_page");
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
		 * Last update: 14/12/2015
		 * Author: Dalia Team
		 *
		 * Summary:
		 *
		 *	1. Création du tableau des résultats détaillées 
		 *		- 1.1. Listing des categories
		 *		- 1.2. Récupération des résultats
		 * 		- 1.3. Score global
		 * 		- 1.4. Gestion du temps
		 * 		- 1.5. Assignation des resultats au catégories
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
			$categories = $resultset['response']['categorie'];
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


		// Boucle sur tout les résultats de chaque question de la session pour en obtenir les détails utiles

		$resultsDetails = array();
		$resultatTime = 0;
		$totalReponses = 0;
		$totalReponsesCorrectes = 0;

		$totalTime = 0;
		$totalReponsesGlobal = 0;
		$totalReponsesCorrectesGlobal = 0;
		$scoreGlobal = 0;
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
				$totalReponsesGlobal++;

				// Test si bonne réponse ou non

				if ($resultat->getRefReponseQcm() == $resultat->getRefReponseQcmCorrecte())
				{
					$resultsDetails[$i]['correct'] = true;
					$totalReponsesCorrectes++;
					$totalReponsesCorrectesGlobal++;
				}
				else 
				{
					$resultsDetails[$i]['correct'] = false;
				}
			}
			else if ($resultat->getReponseChamp() !== null && !empty($resultat->getReponseChamp()))
			{
				$totalReponses++;
				$totalReponsesGlobal++;

				$resultsDetails[$i]['correct'] = true;
				$totalReponsesCorrectes++;
				$totalReponsesCorrectesGlobal++;
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
			//var_dump($resultatCats);
			//exit();
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



		/* 1.3. Score global
		   ========================================================================== */

		$scoreGlobal = round(($totalReponsesCorrectesGlobal / $totalReponsesGlobal) * 100);
		$this->returnData['response']['total_reponses'] = $totalReponsesGlobal;
		$this->returnData['response']['total_reponses_correctes'] = $totalReponsesCorrectesGlobal;
		$this->returnData['response']['total_score'] = $scoreGlobal;

		/* Fin score global */



		/* 1.4. Gestion du temps de passation
		   ========================================================================== */
		
		$stringTime = Tools::timeToString($totalTime);
		$this->returnData['response']['temps_total'] = $stringTime;

		/* Fin gestion du temps */



		/* 1.5. Assignation des resultats au catégories
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
						$searchParentCat->addChild($categorie);
					}
				}
			}

			// On attribut à chaque catégories, les réponses et les scores à partir des résultats
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
					}
				}
			}
		}


		// Enfin, on attribue aux resultats les catégories détaillées correspondantes

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


		// On injecte le tout dans la réponse

		$this->returnData['response']['resultats'] = $resultsDetails;

		/* Fin assignation des resultats au catégories */



		/* ===============================================================================
		   2. Mises à jour des tables concernées et collecte des informations pour le mail
		   =============================================================================== */


		/* 2.1. Mise à jour du nbre de sessions accomplies ds la table "utilisateur"
		   ========================================================================== */
		/*
		$dataUser = array();
		$resultsetUser = $this->utilisateurDAO->selectById(ServicesAuth::getSessionData("ref_user"));

		if (!$this->filterDataErrors($resultsetUser['response']))
		{
			$nbreUserSession = $resultsetUser['response']['utilisateur']->getSessionsAccomplies();
			$dataUser['nbre_sessions_accomplies'] = intval($nbreUserSession) + 1;
			$dataUser['ref_user'] = ServicesAuth::getSessionData("ref_user");

			// On met a jour la table "utilisateur"
			$resultset = $this->utilisateurDAO->update($dataUser);
		}
		*/

		$dataUser = array();
		$refUser = ServicesAuth::getSessionData('ref_user');

		if ($refUser)
		{
			// Sélection de l'utilisateur
			$resultset = $this->servicesResultats->getUser($refUser);

			if ($resultset && !empty($resultset['response']['utilisateur']))
			{
				$dataUser['ref_user'] = $refUser;
				$nbrePosiUser = $resultset['response']['utilisateur']->getSessionsAccomplies();
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
		/*
		$dataOrgan = array();
		$resultsetOrgan = $this->organismeDAO->selectById(ServicesAuth::getSessionData('ref_organ'));

		if (!$this->filterDataErrors($resultsetOrgan['response']))
		{
			$nbrePosiTotal = $resultsetOrgan['response']['organisme']->getNbrePosiTotal();
			$dataOrgan['nbre_posi_total'] = intval($nbrePosiTotal) + 1;
			$dataOrgan['ref_organ'] = ServicesAuth::getSessionData('ref_organ');

			// On met a jour la table "organisme"
			$resultset = $this->organismeDAO->update($dataOrgan);
		}
		*/

		$dataOrgan = array();
		$refOrgan = ServicesAuth::getSessionData('ref_organ');

		if ($refOrgan)
		{
			// Sélection de l'organisme
			$resultset = $this->servicesResultats->getOrganisme($refOrgan);

			if ($resultset && !empty($resultset['response']['organisme']))
			{
				$dataOrgan['ref_organ'] = $refOrgan;
				$nbrePosiOrgan = $resultset['response']['organisme']->getNbrePosiTotal();
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
		/*
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
		*/

		/* Configuration du mail */
		/*
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

		if (Config::ENVOI_EMAIL_REFERENT == 1 && isset($response['email_infos']['email_intervenant']) && !empty($response['email_infos']['email_intervenant'])) 
		{
			$destinataires[] = $response['email_infos']['email_intervenant'];
		}

		$from = !empty(Config::$main_email_admin) ? Config::$main_email_admin : "f.rampion@educationetformation.fr";
		$subject = Config::POSI_NAME;

		$mail = new MailSender($destinataires, $from, $subject);
		$mail->setHeader();
		*/

		/* Création du mail */
		/*
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
		$messageBody .= 'Score globale : <strong>'.$dataView['response']['percent_global'].' %</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Score détaillé : <br />';

		//$results = "";
		foreach ($dataView['response']['correction'] as $correction)
		{
			if ($correction['parent'])
			{         
				if ($correction['total'] > 0)
				{
					$messageBody .= '</br>';
					$messageBody .= $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].' questions)';
				}
			}
		}

		$messageBody .= '</p>';
		$messageBody .= '<br />';
		$messageBody .= '<p>';
		$messageBody .= 'Votre accès à la page des résultats : <br />'.$emailInfos['url_restitution'].'<br />';
		$messageBody .= 'Votre accès à la page des statistiques : <br />'.$emailInfos['url_stats'].'<br />';
		$messageBody .= '</p>';

		$style = 'p { font-family: Arial, sans-serif; }';


		$mail->setMessage($messageBody, 'html', Config::POSI_NAME, $style);
		*/

		/*** Envoi du mail ***/

		//$mail->send();



		/* ==========================================================================
		   4. Synthèse des éléments à injecter dans la page
		   ========================================================================== */

		$dataView = array('response');
		
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

		$this->returnData['response'] = array_merge($dataView['response'], $this->returnData['response']);

		/*** Gestion des erreurs ***/
		
		if (!empty($this->errors))
		{
			// S'il y a eu des erreurs, on les affiche dans la page "résultat".
			$this->returnData['response']['errors'] = $this->errors;
		}
		

		/*** Déconnexion automatique de l'utilisateur ***/
		//ServicesAuth::logout();
		

		/*** Affichage de la page de résultat ***/
		$this->setResponse($this->returnData);
		
		$this->setTemplate("tpl_results");
		//$this->setTemplate("tpl_inscript");
		$this->render("resultat");
	}
	







	
	public function resultat_old()
	{
		/*** Test d'authentification de l'intervenant/utilisateur ***/
		//ServicesAuth::checkAuthentication("user");


		// On commence par récupérer la liste complète des categories.
		$categories = array();
		
		$resultsetCategories = $this->categorieDAO->selectAll();
		
		if (!$this->filterDataErrors($resultsetCategories['response']))
		{
			if (!empty($resultsetCategories['response']['categorie']) && count($resultsetCategories['response']['categorie']) == 1)
			{ 
				$categorie = $resultsetCategories['response']['categorie'];
				$resultsetCategories['response']['categorie'] = array($categorie);
			}
			
			$categories = $resultsetCategories['response']['categorie'];
		}

		
		$totalTime = 0;
		
		// On liste l'ensemble des résultats de l'utilisateur pour la correction
		$tabResultats = array();
		
		$refSession = ServicesAuth::getSessionData("ref_session");
		
		// On sélectionne tous les résultats correspondant à la session en cours
		$resultsetResultats = $this->resultatDAO->selectBySession($refSession);
		
		if (!$this->filterDataErrors($resultsetResultats['response']))
		{ 
			$i = 0;
			
			foreach($resultsetResultats['response']['resultat'] as $resultat)
			{
				// On ajoute le temps du résultat de l'utilisateur au temps total.
				$totalTime += $resultat->getTempsReponse();
						
				// On établit si le résultat est correct ou non
				if ($resultat->getRefReponseQcm() && $resultat->getRefReponseQcmCorrecte())
				{
					if ($resultat->getRefReponseQcm() == $resultat->getRefReponseQcmCorrecte())
					{
						$tabResultats[$i]['correct'] = true;
					}
					else 
					{
						$tabResultats[$i]['correct'] = false;
					}
				
					// Ensuite on va chercher les données sur la question correspondant au résultat
					$resultsetQuestion = $this->questionDAO->selectById($resultat->getRefQuestion());

					if (!$this->filterDataErrors($resultsetQuestion['response']))
					{        
						// On va chercher la compétence liée à la question dont dépend le résultat (est-ce clair !)
						$resultsetCatQuestion = $this->questionCatDAO->selectByRefQuestion($resultsetQuestion['response']['question']->getId());

						if (!$this->filterDataErrors($resultsetCatQuestion['response']))
						{
							$tabResultats[$i]['code_cat'] = $resultsetCatQuestion['response']['question_cat']->getCodeCat();
						}
						else 
						{
							$this->registerError("form_request", "Aucune categorie ne correspond à la question.");
						}
					}
					else 
					{
						$this->registerError("form_request", "Aucune question n'a été trouvée.");
					}
					
					$i++;
				}
			}
		}
		
		


		/*-----   Mise à jour de la table "session"   -----*/
		
		$dataSession = array();
		$dataSession['session_accomplie'] = 1;
		$dataSession['temps_total'] = $totalTime;
		
		$idSession = ServicesAuth::getSessionData("ref_session");


		
		// Mise à jour du nbre de sessions terminée de l'utilisateur

		$dataUser = array();
		$resultsetUser = $this->utilisateurDAO->selectById(ServicesAuth::getSessionData("ref_user"));

		if (!$this->filterDataErrors($resultsetUser['response']))
		{
			$nbreUserSession = $resultsetUser['response']['utilisateur']->getSessionsAccomplies();
			$dataUser['nbre_sessions_accomplies'] = intval($nbreUserSession) + 1;
			$dataUser['ref_user'] = ServicesAuth::getSessionData("ref_user");

			// On met a jour la table "utilisateur"
			$resultset = $this->utilisateurDAO->update($dataUser);
		}
		


		// Mise à jour du nbre de positionnements total de l'organisme

		$dataOrgan = array();
		$resultsetOrgan = $this->organismeDAO->selectById(ServicesAuth::getSessionData('ref_organ'));

		if (!$this->filterDataErrors($resultsetOrgan['response']))
		{
			$nbrePosiTotal = $resultsetOrgan['response']['organisme']->getNbrePosiTotal();
			$dataOrgan['nbre_posi_total'] = intval($nbrePosiTotal) + 1;
			$dataOrgan['ref_organ'] = ServicesAuth::getSessionData('ref_organ');

			// On met a jour la table "organisme"
			$resultset = $this->organismeDAO->update($dataOrgan);
		}


		
		/*** Calcul du nombre total de questions par catégories et le nombre de bonnes réponses pour chaque catégorie.  ***/
		
		$tabCorrection = array();
		$totalGlobal = 0;
		$totalCorrectGlobal = 0;
		$percentGlobal = 0;
		$j = 0;
				
		foreach ($categories as $categorie)
		{
			$codeCat = $categorie->getCode();
			
			$tabCorrection[$j]['code_cat'] = $codeCat;
			$tabCorrection[$j]['total'] = 0;
			$tabCorrection[$j]['total_correct'] = 0;
			$tabCorrection[$j]['nom'] = $categorie->getNom();
			$tabCorrection[$j]['description'] = $categorie->getDescription();
			$tabCorrection[$j]['type_lien'] = $categorie->getTypeLien();

			for ($i = 0; $i < count($tabResultats); $i++)
			{
				if ($tabResultats[$i]['code_cat'] == $codeCat)
				{
					$tabCorrection[$j]['total']++;
					$totalGlobal++;

					if ($tabResultats[$i]['correct'])
					{
						$tabCorrection[$j]['total_correct']++;
						$totalCorrectGlobal++;
					}
				}
				
			}
			
			
			$j++;
		}

		
		
		/*** Intégration du système d'héritage des résultats ***/
		
		for ($i = 0; $i < count($tabCorrection); $i++)
		{
			// On détermine si c'est une categorie principale ou une sous-categorie
			if (strlen($tabCorrection[$i]['code_cat']) == 2)
			{
				// Catégorie parent
				
				if ($tabCorrection[$i]['type_lien'] == "dynamic")
				{
					$tabCorrection[$i]['parent'] = true;
					$parentCode = $tabCorrection[$i]['code_cat'];
					$tabCorrection[$i]['total'] = 0;
					$tabCorrection[$i]['total_correct'] = 0;
					$tabCorrection[$i]['children'] = array();

					for ($j = 0; $j < count($tabCorrection); $j++)
					{ 
						if (strlen($tabCorrection[$j]['code_cat']) > 2 && substr($tabCorrection[$j]['code_cat'], 0, 2) == $parentCode)
						{
							$tabCorrection[$i]['total'] += $tabCorrection[$j]['total'];
							$tabCorrection[$i]['total_correct'] += $tabCorrection[$j]['total_correct'];
							$tabCorrection[$i]['children'][] = $tabCorrection[$j];
						}
					}
				}
				else if ($tabCorrection[$i]['type_lien'] == "static")
				{
					$tabCorrection[$i]['parent'] = true;
					$parentCode = $tabCorrection[$i]['code_cat'];
					$tabCorrection[$i]['children'] = false;
				}
				
			}
			else 
			{
				$tabCorrection[$i]['parent'] = false;
				$tabCorrection[$i]['children'] = false;
			}
		}
		
		
		/*** Données envoyées à la page de résultat ***/
		
		$dataView = array();
		$dataView['response'] = array();
		$dataView['response']['correction'] = array();
		$k = 0;
		
		foreach ($tabCorrection as $correction)
		{
			$dataView['response']['correction'][$k]['parent'] = $correction['parent'];
			$dataView['response']['correction'][$k]['children'] = $correction['children'];
			$dataView['response']['correction'][$k]['nom_categorie'] = $correction['nom'];
			$dataView['response']['correction'][$k]['descript_categorie'] = $correction['description'];
			$dataView['response']['correction'][$k]['total'] = $correction['total'];
			$dataView['response']['correction'][$k]['total_correct'] = $correction['total_correct'];

			if ($correction['total'] > 0)
			{
				$dataView['response']['correction'][$k]['percent'] = round(($correction['total_correct'] * 100) / $correction['total']);
			}
			else 
			{
				$dataView['response']['correction'][$k]['percent'] = 0;
			}
			
			$k++;
		}
		
		
		/*** Gestion du temps ***/
		
		$stringTime = Tools::timeToString($totalTime);
		$dataView['response']['temps'] = $stringTime;
		
		
		/*** Injection des stats globales dans la réponse ***/
		
		$percentGlobal = round(($totalCorrectGlobal / $totalGlobal) * 100);
		$dataView['response']['percent_global'] = $percentGlobal;
		$dataView['response']['total_global'] = $totalGlobal;
		$dataView['response']['total_correct_global'] = $totalCorrectGlobal;
		

		/*** Mise à jour de la session  ***/
		$dataSession['score_pourcent'] = $percentGlobal;

		$resultset = $this->sessionDAO->update($dataSession, $idSession);

		// Traitement des erreurs de la requête
		if ($this->filterDataErrors($resultset['response']) || !isset($resultset['response']['session']['row_count']) || empty($resultset['response']['session']['row_count']))
		{
			$this->registerError("form_request", "La session n'a pu être mise à jour.");
		}



		/****************************************/
		/***   Envoi de mails des résultats   ***/
		/****************************************/

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

		if (Config::ENVOI_EMAIL_REFERENT == 1 && isset($response['email_infos']['email_intervenant']) && !empty($response['email_infos']['email_intervenant'])) 
		{
			$destinataires[] = $response['email_infos']['email_intervenant'];
		}

		$from = !empty(Config::$main_email_admin) ? Config::$main_email_admin : "f.rampion@educationetformation.fr";
		$subject = Config::POSI_NAME;

		$mail = new MailSender($destinataires, $from, $subject);
		$mail->setHeader();


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
		$messageBody .= 'Score globale : <strong>'.$dataView['response']['percent_global'].' %</strong>';
		$messageBody .= '</p>';
		$messageBody .= '<p>';
		$messageBody .= 'Score détaillé : <br />';

		//$results = "";
		foreach ($dataView['response']['correction'] as $correction)
		{
			if ($correction['parent'])
			{         
				if ($correction['total'] > 0)
				{
					$messageBody .= '</br>';
					$messageBody .= $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].' questions)';
				}
			}
		}

		$messageBody .= '</p>';
		$messageBody .= '<br />';
		$messageBody .= '<p>';
		$messageBody .= 'Votre accès à la page des résultats : <br />'.$emailInfos['url_restitution'].'<br />';
		$messageBody .= 'Votre accès à la page des statistiques : <br />'.$emailInfos['url_stats'].'<br />';
		$messageBody .= '</p>';

		$style = 'p { font-family: Arial, sans-serif; }';


		$mail->setMessage($messageBody, 'html', Config::POSI_NAME, $style);


		/*** Envoi du mail ***/

		$mail->send();



		/*** Gestion des erreurs ***/
		
		if (!empty($this->errors))
		{
			// S'il y a eu des erreurs, on les affiche dans la page "résultat".
			$dataView['response']['errors'] = $this->errors;
		}
		

		/*** Déconnexion automatique de l'utilisateur ***/
		//ServicesAuth::logout();
		

		/*** Affichage de la page de résultat ***/
		$this->setResponse($dataView);
		
		//$this->setTemplate("tpl_results");
		$this->setTemplate("tpl_inscript");
		$this->render("resultat");
	}
 
 
}



?>
