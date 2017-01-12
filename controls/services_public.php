<?php

/**
 * 
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_admin_restitution.php');
require_once(ROOT.'controls/services_admin_stat.php');
//require_once(ROOT.'models/dao/organisme_dao.php');



class ServicesPublic extends Main
{

	
	private $servicesRestitution = null;
	private $servicesAdminStat = null;

	private $organismeDAO = null;
	
	private $servicesGestion = null;
	
	

	public function __construct() 
	{
		$this->controllerName = "public";
		
		$this->servicesGestion = new ServicesAdminGestion();
		
		$this->servicesRestitution = new ServicesAdminRestitution();
		$this->servicesAdminStat = new ServicesAdminStat();

		//$this->organismeDAO = new OrganismeDAO();
	}
	


	

	
	/**
	 * restitution - Gére la validation du formulaire de gestion des degrés d'aptitude avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
	 *
	 * @param array Tableau de paramètres passés par url (le code d'identification de l'organisme)
	 */
	public function restitution($requestParams = array())
	{

		$this->initialize();

		$this->servicesRestitution->initialize();


			

		/**
		 * Description: Gestion des filtres et génération de la page restitution.
		 * Create: 20/12/2016
		 * Last update: 20/12/2016
		 * Author: Dalia Team
		 *
		 * Summary:
		 *
		 *	1. Gestion du filtrage avant sélection
		 *		- 1.1. Récupération des paramètres d'URL
		 *  	- 1.2. Création de la liste des régions
		 *		- 1.3. Assignation des droits sur les éléments de chaque filtre
		 * 		- 1.4. Requêtes instantanées sur les filtres
		 * 
		 *	2. Requête globale après sélection
		 *		- 2.1. Récupération des valeurs des filtres ou présélections selon droits
		 *		- 2.2. Création des listes visualisables
		 *  	- 2.3.
		 * 		- 2.4. Gestion de la validation des questions ouvertes
		 * 
		 * 	3. Génération des vues
		 *  	- 3.1. Création des différentes listes
		 *   	- 3.2. Export PDF
		 * 		- 3.3. Export PDF
		 * 		- 3.4. Export CSV - Excel
		 * 		- 3.5. Génération de la restitution globale et finale
		 * 
		 */
		


		/* ==========================================
		   1. Gestion du filtrage avant sélection
		   ========================================== */
		

		/* 1.1. Récupération des paramètres d'URL
		   ========================================================================== */
		
		//$refRegion = null;
		$numOrgan = null;
		$refUser = null;
		$refPosi = null;
		$refSession = null;

		if ($requestParams != null && is_array($requestParams)) 
		{
			if (isset($requestParams[0]) && preg_match("`^[a-zA-Z0-9]*$`", $requestParams[0]) && !empty($requestParams[0])) {
				$numOrgan = $requestParams[0];
			}
			if (isset($requestParams[1]) && preg_match("`^[0-9]*$`", $requestParams[1]) && !empty($requestParams[1])) {
				$refUser = $requestParams[1];
			}
			if (isset($requestParams[2]) && preg_match("`^[0-9]*$`", $requestParams[2]) && !empty($requestParams[2])) {
				$refPosi = $requestParams[2];
			}
			if (isset($requestParams[3]) && preg_match("`^[0-9]*$`", $requestParams[3]) && !empty($requestParams[3])) {
				$refSession = $requestParams[3];
			}

		}

		/* Fin Récupération des paramètres d'URL */



		/* 1.2. Création de la liste des régions
		   ========================================================================== */

		$regions = null;

		$hasRegions = $this->servicesAdminStat->createRegionsList(Config::ANNEE_REGION);

		if ($hasRegions)
		{
			$regionsList['response'] = array();
			$regionsList['response']['regions'] = $this->servicesAdminStat->getRegionsList();
		}

		if (isset($regionsList['response']['regions']) && !empty($regionsList['response']['regions'])) 
		{
			$regions = $regionsList['response']['regions'];
		}

		/* Fin Création de la liste des régions */



		/* 1.3. Assignation des droits sur les éléments de chaque filtre
		   ========================================================================== */

		$preSelectOrgan = null;
		$preSelectUser = null;
		$preSelectPosi = null;
		$preSelectSession = null;

		$loggedAsAdmin = false;
		$loggedAsOrganViewer = false;
		$loggedAsUserViewer = false;
		$loggedAsPosiViewer = false;
		$loggedAsSessionViewer = false;

		$this->url = SERVER_URL."public/restitution/";

		// On détermine si l'utilisateur est un admin ou non
		if (ServicesAuth::checkAuthentication('custom-admin') || ServicesAuth::checkAuthentication('custom-public'))
		{
			$loggedAsAdmin = true;
		}


		if (!$loggedAsAdmin) {
		

			/* 1.3.1. Filtre "organisme" 
			   ----------------------------------------------*/

			if ($numOrgan !== null) {

				// On va chercher l'organisme correspondant au code
				$selectOrgan = $this->servicesRestitution->getOrganByCode($numOrgan);

				if ($selectOrgan['response']['organisme'] && count($selectOrgan['response']['organisme']) > 0) {
					$loggedAsOrganViewer = true;
					$preSelectOrgan = $selectUser['response']['organisme'][0];

					$this->url .= $preSelectOrgan->getId()."/";
				}
				else
				{
					// Redirection vers une page d'erreur non autorisé
					header("Location: ".SERVER_URL."erreur/page503");
					exit();
				}
			}


			/* 1.3.2. Filtre "utilisateur" 
			   ----------------------------------------------*/

			if ($refUser !== null) {

				// On va chercher l'utilisateur
				$selectUser = $this->servicesRestitution->getUser($refUser);

				if ($selectUser['response']['utilisateur'] && count($selectUser['response']['utilisateur']) > 0) 
				{
					$loggedAsUserViewer = true;
					$preSelectUser = $selectUser['response']['utilisateur'][0];

					if ($preSelectOrgan !== null) 
					{
						$this->url .= $preSelectUser->getId()."/";
					}
				}
				else
				{
					// Redirection vers une page d'erreur non autorisé
					header("Location: ".SERVER_URL."erreur/page503");
					exit();
				}
			}


			/* 1.3.3. Filtre "Positionnement/Domaine" 
			   ----------------------------------------------*/

			if ($refPosi !== null) {

				// On va chercher l'utilisateur
				$selectPosi = $this->servicesRestitution->getPositionnement($refPosi);

				if ($selectPosi['response']['positionnement'] && count($selectUser['response']['positionnement']) > 0) 
				{
					$loggedAsPosiViewer = true;
					$preSelectPosi = $selectPosi['response']['positionnement'][0];

					if ($preSelectUser !== null) 
					{
						$this->url .= $preSelectPosi->getId()."/";
					}
				}
				else
				{
					// Redirection vers une page d'erreur non autorisé
					header("Location: ".SERVER_URL."erreur/page503");
					exit();
				}
			}


			/* 1.3.4. Filtre "Session" 
			   ----------------------------------------------*/

			if ($refSession !== null) {

				// On va chercher l'utilisateur
				$selectSession = $this->servicesRestitution->getSession($refSession);

				if ($selectSession['response']['session'] && count($selectSession['response']['session']) > 0) 
				{
					$loggedAsSessionViewer = true;
					$preSelectSession = $selectSession['response']['session'][0];

					if ($preSelectPosi !== null) 
					{
						$this->url .= $preSelectSession->getId()."/";
					}
				}
				else
				{
					// Redirection vers une page d'erreur non autorisé
					header("Location: ".SERVER_URL."erreur/page503");
					exit();
				}
			}
		}

		/* Fin Assignation des droits */



		/* 1.4. Requête instantanée sur les filtres
		   ========================================================================== */
		
		if (isset($_POST['filter']))
		{
			$results = false;

			$refRegionFilter = null;
			$refOrganFilter = null;
			$refUserFilter = null;
			$refPosiFilter = null;
			//$dateSession = null;
			$refSessionFilter = null;


			if (isset($_POST['ref_region']) && !empty($_POST['ref_region']) && $_POST['ref_region'] != 'select_cbox' && preg_match("`^[0-9]*$`", $_POST['ref_region']))
			{
				$refRegionFilter = $_POST['ref_region'];
			}

			if ($loggedAsOrganViewer) 
			{
				$refOrganFilter = $preSelectOrgan;
			}
			else if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']) && $_POST['ref_organ'] != 'select_cbox' && preg_match("`^[0-9]*$`", $_POST['ref_organ']))
			{
				$refOrganFilter = $_POST['ref_organ'];
			}


			if ($refOrganFilter !== null)
			{
				if ($loggedAsUserViewer) 
				{
					$refUserFilter = $preSelectUser;
				}
				else if (isset($_POST['ref_user']) && !empty($_POST['ref_user']) && $_POST['ref_user'] != 'select_cbox' && preg_match("`^[0-9]*$`", $_POST['ref_user']))
				{
					$refUserFilter = $_POST['ref_user'];
				}

				if ($refUserFilter !== null)
				{
					if ($loggedAsPosiViewer) 
					{
						$refPosiFilter = $preSelectPosi;
					}
					else if (isset($_POST['ref_posi']) && !empty($_POST['ref_posi']) && $_POST['ref_posi'] != 'select_cbox' && preg_match("`^[0-9]*$`", $_POST['ref_posi']))
					{
						$refPosiFilter = $_POST['ref_posi'];
					}

					
					if ($refPosiFilter !== null)
					{
						if ($loggedAsSessionViewer) 
						{
							$refSessionFilter = $preSelectSession;
						}
						else if (isset($_POST['ref_session']) && !empty($_POST['ref_session']) && $_POST['ref_session'] != 'select_cbox' && preg_match("`^[0-9]*$`", $_POST['ref_session']))
						{
							$refSessionFilter = $_POST['ref_session'];
						}
					}
					
				}
			}

			//var_dump($refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter);


			// if (isset($_POST['date_session']) && !empty($_POST['date_session']))
			// {
			// 	$dateSession = $_POST['date_session'];
			// }
			
			
			//var_dump($refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter);


			if ($refRegionFilter != null || $refOrganFilter != null || $refUserFilter != null || $refPosiFilter != null) // || $dateSession != null)
			{

				//var_dump($refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter);


				if ($refOrganFilter != null && $refUserFilter != null && $refPosiFilter != null) 
				{
					$searchResults = $this->servicesRestitution->search(false, $regions, $refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter);
				}
				else 
				{
					$searchResults = $this->servicesRestitution->search(true, $regions, $refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter); // params : $regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $date = null, $codeOrgan = null, $ref_inter = null
				}

				// Recherche des éléments de listes et de champs de filtrage
				if ($searchResults)
				{
					if (isset($searchResults['response']['restitution']) && !empty($searchResults['response']['restitution'])) 
					{
						$results = array('error' => false, 'results' => $searchResults['response']['restitution']);
					}
					else
					{
						$results = array('error' => false, 'results' => null);
					}
				}
				else
				{
					$results = array('error' => "Aucun résultat avec les identifiants fournis.");
				}
			
			}
			else
			{
				// Select all
				$searchResults = $this->servicesRestitution->search(true, $regions); // params : $regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $date = null, $codeOrgan = null, $ref_inter = null

				
				if (isset($searchResults['response']) && !empty($searchResults['response']))
				{
					$results = array('error' => false, 'results' => $searchResults['response']['restitution']);
				}
				else
				{
					$results = array('error' => "Aucune données dans la sélection globale.");
				}
			}

			echo json_encode($results);
			exit();
		}

		/* Fin Requête instantannée sur les filtres */


		/*=====  Fin Gestion du filtrage  ======*/
		




		
		





		/*
		$codeOrgan = "";
		$loggedAsViewer = false;
		$loggedAsAdmin = false;
		$preSelectOrganisme = null;

		
		// on vérifie s'il y a un code dans les parametres url
		if (isset($requestParams[0]) && !empty($requestParams[0]))
		{   
			if (preg_match("`^[a-zA-Z0-9]*$`", $requestParams[0]))
			{
				// On récupère le code
				$codeOrgan = $requestParams[0];
				
				// On va chercher l'organisme correspondant
				$preSelectOrganisme = $this->organismeDAO->selectByCodeInterne($codeOrgan);
				
				if (!$this->filterDataErrors($preSelectOrganisme['response']))
				{
					if (!empty($preSelectOrganisme['response']['organisme']) && count($preSelectOrganisme['response']['organisme']) == 1)
					{ 
						$organ = $preSelectOrganisme['response']['organisme'];
						$preSelectOrganisme['response']['organisme'] = array($organ);
					}
					$loggedAsViewer = true;
				}
				else 
				{
					// Redirection vers une page d'erreur interne
					header("Location: ".SERVER_URL."erreur/page500");
					exit();
				}
			}
			else 
			{
				// Redirection vers une page d'erreur non autorisé
				header("Location: ".SERVER_URL."erreur/page503");
				exit();
			}
		}
		else 
		{

			// Authentification automatique provisoire
			ServicesAuth::login("admin");

			// Sinon, authentification necessaire
			//ServicesAuth::checkAuthentication("custom");
			$loggedAsAdmin = true;
		}
		
		
		$this->servicesRestitution->initialize();
		
		$this->url = SERVER_URL."public/restitution/".$codeOrgan;
		


		/*** Requêtes ajax de filtrage et de validation ***/

		if (Config::ALLOW_AJAX)
		{

		/*-------------------- Requêtes de filtrage des positionnements --------------------*/
			
			//if ($loggedAsViewer || $loggedAsAdmin)
			//{
				/*
				if (isset($_POST['filter']))
				{
					$results = false;

					$refRegion = null;
					$refOrgan = null;
					$refUser = null;
					$refPosi = null;
					//$dateSession = null;
					$refSession = null;

					if (isset($_POST['ref_region']) && !empty($_POST['ref_region']) && $_POST['ref_region'] != 'select_cbox')
					{
						$refRegion = $_POST['ref_region'];
					}
					if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']) && $_POST['ref_organ'] != 'select_cbox')
					{
						$refOrgan = $_POST['ref_organ'];
					}
					if (isset($_POST['ref_user']) && !empty($_POST['ref_user']) && $_POST['ref_user'] != 'select_cbox')
					{
						$refUser = $_POST['ref_user'];
					}
					if (isset($_POST['ref_posi']) && !empty($_POST['ref_posi']) && $_POST['ref_posi'] != 'select_cbox')
					{
						$refPosi = $_POST['ref_posi'];
					}

					// if (isset($_POST['date_session']) && !empty($_POST['date_session']))
					// {
					// 	$dateSession = $_POST['date_session'];
					// }
					if (isset($_POST['ref_session']) && !empty($_POST['ref_session']) && $_POST['ref_session'] != 'select_cbox')
					{
						$refSession = $_POST['ref_session'];
					}

					//var_dump($refRegion, $refOrgan, $refUser, $dateSession);
					//exit();

					if ($refRegion != null || $refOrgan != null || $refUser != null || $refPosi != null) // || $dateSession != null)
					{
						$searchResults = $this->servicesRestitution->search($regions, $refRegion, $refOrgan, $refUser, $refPosi); // params : $regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $date = null, $codeOrgan = null, $ref_inter = null
						
						// Recherche des éléments de listes et de champs de filtrage

						if ($searchResults)
						{
							if (isset($searchResults['response']['restitution']) && !empty($searchResults['response']['restitution'])) 
							{
								$results = array('error' => false, 'results' => $searchResults['response']['restitution']); //, 'query' => $searchResults['response']['query']);
							}
							else
							{
								$results = array('error' => false, 'results' => null);
							}

							//$results = array('error' => false, 'results' => $searchResults['response']['restitution']);
						}
						else
						{
							$results = array('error' => "Error filter = false");
						}
					}
					else
					{
						// Select all
						$searchResults = $this->servicesRestitution->search($regions); // params : $regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $date = null, $codeOrgan = null, $ref_inter = null
						
						//var_dump($searchResults);
						//exit();
						
						if (isset($searchResults['response']) && !empty($searchResults['response']))
						{
							$results = array('error' => false, 'results' => $searchResults['response']['restitution']); //, 'query' => $searchResults['response']['query']);
						}
						else
						{
							$results = array('error' => "Error, no filter attribute");
						}
						
						//$results = array('error' => "error no filter attribute");
					}

					//var_dump($results);
					//exit();

					echo json_encode($results);
					exit();
				}
				*/
				/*
				else if (isset($_POST['validate_search'])) 
				{
					if (!empty($this->formData['ref_session']) && $this->formData['ref_session'] != "select_cbox")
					{
						
						$resultsetSession = $this->servicesRestitution->getSession($this->formData['ref_session']);
						$this->returnData['response'] = array_merge($resultsetSession['response'], $this->returnData['response']);

						$resultsetIntervenant = $this->servicesRestitution->getIntervenant($resultsetSession['response']['session'][0]->getRefIntervenant());
						$this->returnData['response']['infos_user']['nom_intervenant'] = $resultsetIntervenant['response']['intervenant'][0]->getNom();
						$this->returnData['response']['infos_user']['email_intervenant'] = $resultsetIntervenant['response']['intervenant'][0]->getEmail();

						$refSession = $resultsetSession['response']['session'][0]->getId();
						$this->returnData['response']['infos_user']['ref_selected_session'] = $refSession;
						$this->returnData['response']['infos_user']['ref_valid_acquis'] = $resultsetSession['response']['session'][0]->getRefValidAcquis();
						
						$this->returnData['response']['stats'] = array();
						$this->returnData['response']['stats'] = $this->servicesRestitution->getPosiStats($refSession);
					}
				}
				*/
				/*
				if (isset($_POST['sort']) && !empty($_POST['sort']))
				{

					if ($_POST['sort'] == "user")
					{
						if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
						{
							$utilisateurs = $this->servicesRestitution->getUsersFromOrganisme($_POST['ref_organ']);
							
							if ($utilisateurs)
							{
								$response = array('error' => false, 'results' => $utilisateurs['response']);
							}
							else
							{
								$response = array('error' => "Il n'existe pas d'utilisateur qui correspond à l'organisme.");
							}
						}
						else
						{
							$response = array('error' => "Vous n'avez pas sélectionné d'organisme.");
						}
					}
					else if ($_POST['sort'] == "session")
					{

						if ((isset($_POST['ref_organ']) && !empty($_POST['ref_organ'])) && (isset($_POST['ref_user']) && !empty($_POST['ref_user'])))
						{
							$sessions = $this->servicesRestitution->getUserSessions($_POST['ref_user'], $_POST['ref_organ']);
							
							if ($sessions)
							{
								$i = 0;

								foreach($sessions['response']['session'] as $session)
								{
									$id = $session->getId();
									$date = Tools::toggleDate(substr($session->getDate(), 0, 10));
									$timeToSeconds = Tools::timeToSeconds(substr($session->getDate(), 11, 8), $inputFormat = "h:m:s");
									$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));

									$sessions['response']['session'][$i] = array();
									$sessions['response']['session'][$i]['id'] = $id;
									$sessions['response']['session'][$i]['date'] = $date;
									$sessions['response']['session'][$i]['time'] = $time;

									$i++;
								}

								$response = array('error' => false, 'results' => $sessions['response']);
							}
							else
							{
								$response = array('error' => "Il n'existe pas de positionnement qui correspond à l'utilisateur.");
							}
						}
						else
						{
							$response = array('error' => "Vous n'avez pas sélectionné d'utilisateur.");
						}
					}
					else
					{
						$response = array('error' => "Le type n'a pas été trouvé.");
					}

					echo json_encode($response);
					exit();
				}
				*/


			/*-------------------- Validation des questions ouvertes (requête) --------------------*/

				if (isset($_POST['validation']))
				{

					$response = array('error' => false, 'result' => $_POST);
					
					if (isset($_POST['ref_question']) && !empty($_POST['ref_question']) && isset($_POST['ref_session']) && !empty($_POST['ref_session'])) {

						$refQuestion = $_POST['ref_question'];
						$refSession = $_POST['ref_session'];
						$valid = intval($_POST['validation']);

						$validation = $this->servicesRestitution->updateValidResultat($refQuestion, $refSession, $valid);
						
						if ($validation)
						{
							$response = array('error' => false, 'result' => $validation);
						}
						else
						{
							$response = array('error' => "La validation de la question n'a pas été prise en compte.");
						}
					}
					else {
					
				
						$response = array('error' => "Identifiant de session ou de résultat attendu.");
					}

					echo json_encode($response);
					exit();
				}
			//}
		}

		/*** Fin requêtes ajax ***/


		

		/* ==========================================
		   2. Requête globale après sélection
		   ========================================== */

			/*		- 2.1. Récupération des valeurs des filtres ou présélections selon droits
			 *   	- 2.2. Création des listes visualisables
			 * 		- 3.3. Export PDF
			 * 		- 3.4. Export CSV - Excel
			 * 		- 3.5. Génération de la restitution globale et finale
			 */



		/* 2.1. Récupération des valeurs des filtres ou présélection selon droits
		   ========================================================================== */
		
		// Iinitialisation les données qui vont être validées et renvoyées au formulaire
		
		$this->formData['ref_region_cbox'] = null;
		$this->formData['ref_organ_cbox'] = null;
		$this->formData['ref_user_cbox'] = null;
		$this->formData['ref_posi_cbox'] = null;
		$this->formData['ref_session_cbox'] = null;
		$this->formData['ref_region'] = null;
		$this->formData['ref_organ'] = null;
		$this->formData['ref_user'] = null;
		$this->formData['ref_posi'] = null;
		$this->formData['ref_session'] = null;
		
		$this->servicesGestion->initializeFormData($this->formData, $_POST, array(
			"ref_region_cbox" => "select",
			"ref_organ_cbox" => "select", 
			"ref_user_cbox" => "select",
			"ref_posi_cbox" => "select",
			"ref_session_cbox" => "select",
			"select_trigger" => "text"));
		
		// On récupère les differents identifiants de la zone de sélection 
		$this->formData['ref_region'] = $this->formData['ref_region_cbox'];
		$this->formData['ref_organ'] = $this->formData['ref_organ_cbox'];
		$this->formData['ref_user'] = $this->formData['ref_user_cbox'];
		$this->formData['ref_posi'] = $this->formData['ref_posi_cbox'];
		$this->formData['ref_session'] = $this->formData['ref_session_cbox'];
		
		if (!isset($_POST['select_trigger']) || $_POST['select_trigger'] == null) 
		{
			$this->formData['select_trigger'] = null;
		}
		else
		{
			$this->formData['select_trigger'] = "true";
		}

		// Si les ids sont spécifiés dans l'url, on remplace les valeurs sélectionnées


		if (!$loggedAsAdmin) {

			if ($loggedAsOrganViewer)
			{
				$this->formData['ref_organ'] = $preSelectOrgan->getId();
			}

			if ($loggedAsUserViewer)
			{
				$this->formData['ref_user'] = $preSelectUser->getId();
			}

			if ($loggedAsPosiViewer)
			{
				$this->formData['ref_posi'] = $preSelectPosi->getId();
			}

			if ($loggedAsSessionViewer)
			{
				$this->formData['ref_session'] = $preSelectSession->getId();
			}

		}

		/* Fin Récupération des valeurs des filtres */




		/* 2.2. Initialisation des informations sur la restitution affichable
		   ============================================================================= */


		$this->returnData['response']['infos_user']['nom_organ'] = "";
		$this->returnData['response']['infos_user']['code_organ'] = "";
		$this->returnData['response']['infos_user']['nom'] = "";
		$this->returnData['response']['infos_user']['prenom'] = "";
		$this->returnData['response']['infos_user']['date_naiss'] = "";
		$this->returnData['response']['infos_user']['nom_niveau'] = "";
		$this->returnData['response']['infos_user']['descript_niveau'] = "";
		$this->returnData['response']['infos_user']['nbre_positionnements'] = "";

		$this->returnData['response']['infos_user']['date_last_posi'] = "";
		$this->returnData['response']['infos_user']['ref_selected_session'] = "";
		
		$this->returnData['response']['infos_user']['nom_intervenant'] = "";
		$this->returnData['response']['infos_user']['email_intervenant'] = "";

		$nomOrgan = "";
		$codeOrgan = "";
		$nomUser = "";
		$prenomUser = "";
		$dateNaissUser = "";
		$nbrePassTotales = "";
		$nbrePassAccomplies = "";
		$nomNiveau = "";
		$descriptNiveau = "";
		$nomInter = "";
		$emailInter = "";


		/* Fin Initialisation des informations sur la restitution */




		/* 2.3. Création des listes visualisables (restrictions des listes selon droits)
		   ============================================================================= */

		// Incorporation de la liste des régions (toujours complète)

		if ($regionsList['response']['regions']) 
		{
			$this->returnData['response'] = array_merge($regionsList['response'], $this->returnData['response']);
		}


		// Initialisation des listes
		
		$list = array(
			'organismes' => array(),
			'utilisateurs' => array(),
			'domaines' => array(),
			'sessions' => array()
		);


		// Obtention des valeurs listables selon les filtres sélectionnés

		if ($this->formData['ref_region'] != null || $this->formData['ref_organ'] != null || $this->formData['ref_user'] != null || $this->formData['ref_posi'] != null)
		{
			//$resultsListings = $this->servicesRestitution->search(false, $regions, $this->formData['ref_region'], $this->formData['ref_organ'], $this->formData['ref_user'], $this->formData['ref_posi'], $this->formData['ref_session']);
			

			//if ($this->formData['ref_organ'] != null && $this->formData['ref_user'] != null && $this->formData['ref_posi'] != null) 
			//{
				//$searchResults = $this->servicesRestitution->search(false, $regions, $refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter);
				$resultsListings = $this->servicesRestitution->search(true, $regions, $this->formData['ref_region'], $this->formData['ref_organ'], $this->formData['ref_user'], $this->formData['ref_posi']);
			//}
			//else 
			//{
				//$searchResults = $this->servicesRestitution->search(true, $regions, $refRegionFilter, $refOrganFilter, $refUserFilter, $refPosiFilter); // params : $regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $date = null, $codeOrgan = null, $ref_inter = null
				//$resultsListings = $this->servicesRestitution->search(true, $regions, $this->formData['ref_region'], $this->formData['ref_organ'], $this->formData['ref_user'], $this->formData['ref_posi']);
			//}
		}
		else
		{
			$resultsListings = $this->servicesRestitution->search(true, $regions);
		}

		//var_dump($resultsListings);
		//exit();

		if (isset($resultsListings['response']['restitution']) && !empty($resultsListings['response']['restitution']))
		{
			$listings = $resultsListings['response']['restitution'];

			//var_dump($listings);
			//exit();

			$i = 0;

			foreach ($listings as $entity) {

				foreach ($entity as $key => $value) {
				
					switch ($key) {

						case 'id_organ':
							$list['organismes'][$i]['id_organ'] = $value;
							break;

						// case 'nom_organ':
						// 	$list['organismes'][$i]['nom_organ'] = $value;
						// 	break;

						case 'id_user':
							$list['utilisateurs'][$i]['id_user'] = $value;
							break;

						// case 'nom_user':
						// 	$list['utilisateurs'][$i]['nom_user'] = $value;
						// 	break;

						// case 'prenom_user':
						// 	$list['utilisateurs'][$i]['prenom_user'] = $value;
						// 	break;
						case 'id_posi':
							$list['domaines'][$i]['id_posi'] = $value;
							break;

						case 'id_session':
							$list['sessions'][$i]['id_session'] = $value;
							break;

						// case 'date_session':
						// 	$list['sessions'][$i]['date_session'] = $value;
						// 	break;

						default :
							break;
					}
				}

				$i++;
			}
			
		}



		// Création de la liste des organismes par défaut

		$nomOrgan = null;
		$codeOrgan = null;

		$organismes = array(
			'response' => array(
				'organisme' => array()
			)
		);

		if ($loggedAsAdmin)
		{
			$organismesList = $this->servicesRestitution->getOrganismesList(); 
		}
		else if ($loggedAsOrganViewer)
		{
			$organismesList = $preSelectOrgan;
		}
		 

		// Si la liste des organismes est disponible, uniquement les valeurs autorisées sont sélectionnées
		if (!$organismesList)
		{
			$this->registerError("form_empty", "Aucun organisme existant.");
		}
		else 
		{
			foreach ($organismesList['response']['organisme'] as $organisme)
			{
				$j = 0;
				$exists = false;

				foreach ($list['organismes'] as $organ)
				{
					if ($organisme->getId() == $organ['id_organ'] && !$exists) 
					{
						$organismes['response']['organisme'][$j] = $organisme;
						$exists = true;
					}

					$j++;
				}

				if ($organisme->getId() == $this->formData['ref_organ'])
				{
					$nomOrgan = $organisme->getNom();
					$codeOrgan = $organisme->getNumeroInterne();
				}
			}

			$this->returnData['response'] = array_merge($organismes['response'], $this->returnData['response']);
		}



		// Pour chaque combo-box sélectionné, on effectue les requetes correspondantes
		
		// Un organisme a été sélectionnée
		
		if ($this->formData['ref_organ'] !== null && $this->formData['ref_organ'] != "select_cbox")
		{
			// Assignation des infos disponibles sur la restitution
			$this->returnData['response']['infos_user']['nom_organ'] = $nomOrgan;
			$this->returnData['response']['infos_user']['code_organ'] = $codeOrgan;
			$this->returnData['response']['infos_user']['nom_intervenant'] = "";
			$this->returnData['response']['infos_user']['email_intervenant'] = "";
			$this->returnData['response']['infos_user']['nom'] = "";
			$this->returnData['response']['infos_user']['prenom'] = "";
			$this->returnData['response']['infos_user']['date_naiss'] = "";
			$this->returnData['response']['infos_user']['nom_niveau'] = "";
			$this->returnData['response']['infos_user']['descript_niveau'] = "";
			$this->returnData['response']['infos_user']['nbre_positionnements'] = "";
			$this->returnData['response']['infos_user']['date_last_posi'] = "";
			$this->returnData['response']['infos_user']['ref_selected_session'] = "";


			$users = array(
				'response' => array(
					'utilisateur' => array()
				)
			);


			if ($loggedAsAdmin)
			{
				$usersList = $this->servicesRestitution->getUsersFromOrganisme($this->formData['ref_organ']); 
			}
			else if ($loggedAsUserViewer)
			{
				$usersList = $preSelectUser;
			}


			if (!$usersList)
			{
				$this->registerError("form_empty", "Impossible de visualiser les utilisateurs.");
			}
			else 
			{

				foreach ($usersList['response']['utilisateur'] as $utilisateur)
				{
					$j = 0;
					$exists = false;

					foreach ($list['utilisateurs'] as $user)
					{
						if ($utilisateur->getId() == $user['id_user'] && !$exists) 
						{
							$users['response']['utilisateur'][$j] = $utilisateur;
							$exists = true;
						}

						$j++;
					}
					
					if ($utilisateur->getId() == $this->formData['ref_user'])
					{
						$numDossier = $utilisateur->getNumeroDossier();
						$nomUser = $utilisateur->getNom();
						$prenomUser = $utilisateur->getPrenom();
						$dateNaissUser = $utilisateur->getDateNaiss();
						$nbrePassTotales = $utilisateur->getSessionsTotales();
						$nbrePassAccomplies = $utilisateur->getSessionsAccomplies();

						$resultsetNiveau = $this->servicesRestitution->getNiveau($utilisateur->getRefNiveau());

						if ($resultsetNiveau)
						{
							$nomNiveau = $resultsetNiveau['response']['niveau_etudes'][0]->getNom();
							$descriptNiveau = $resultsetNiveau['response']['niveau_etudes'][0]->getDescription();
						}
					}
					
				}

				$this->returnData['response'] = array_merge($users['response'], $this->returnData['response']);
			}
			
			

			/*------   Un utilisateur a été sélectionné   -------*/
			
			if ($this->formData['ref_user'] !== null && $this->formData['ref_user'] != "select_cbox")
			{
				
				$this->returnData['response']['infos_user']['nom'] = $nomUser;
				$this->returnData['response']['infos_user']['prenom'] = $prenomUser;
				$this->returnData['response']['infos_user']['date_naiss'] = $dateNaissUser;
				$this->returnData['response']['infos_user']['nom_niveau'] = $nomNiveau;
				$this->returnData['response']['infos_user']['descript_niveau'] = $descriptNiveau;

				
				$posis = array(
					'response' => array(
						'positionnement' => array()
					)
				);

				if ($loggedAsAdmin)
				{
					$domsList = $this->servicesRestitution->getPosisFromUser($this->formData['ref_user']); 
				}
				else if ($loggedAsPosiViewer)
				{
					$domsList = $preSelectPosi;
				}
				 

				if (!$domsList)
				{
					$this->registerError("form_empty", "Impossible de visualiser les domaines.");
				}
				else 
				{
					foreach ($domsList['response']['positionnement'] as $positionnement)
					{
						$j = 0;
						$exists = false;

						foreach ($list['domaines'] as $dom)
						{
							if ($positionnement->getId() == $dom['id_posi'] && !$exists) 
							{
								$posis['response']['positionnement'][$j] = $positionnement;
								$exists = true;
							}

							$j++;
						}

						if ($positionnement->getId() == $this->formData['ref_user'])
						{
							$nomPosi = $positionnement->getNom();
							$descriptPosi = $positionnement->getDescription();
						}
					}

					$this->returnData['response'] = array_merge($posis['response'], $this->returnData['response']);
				}




				/*------   Un domaine/positionnement a été sélectionné   -------*/

				if ($this->formData['ref_posi'] !== null && $this->formData['ref_posi'] != "select_cbox") 
				{
					//$this->returnData['response']['infos_user']['nom_intervenant'] = "";
					//$this->returnData['response']['infos_user']['email_intervenant'] = "";
					/*
					$resultsetDomaines = $this->servicesRestitution->getPosisFromUser($this->formData['ref_user']);

					$doms = array(
						'response' => array(
							'positionnement' => array()
						)
					);

					if (!$resultsetDomaines)
					{
						$this->registerError("form_empty", "Impossible de visualiser les domaines.");
					}
					else 
					{
						foreach ($resultsetDomaines['response']['positionnement'] as $positionnement)
						{
							foreach ($list['domaines'] as $dom)
							{
								if ($positionnement->getId() == $dom['id_posi']) 
								{
									$doms['response']['positionnement'][] = $positionnement;
								}
							}
						}
					
						$this->returnData['response'] = array_merge($doms['response'], $this->returnData['response']);
					}
					*/
				
					$sess = array(
						'response' => array(
							'session' => array()
						)
					);

					

					if ($loggedAsAdmin)
					{
						$sessionsList = $this->servicesRestitution->getUserSessions($this->formData['ref_user'], $this->formData['ref_organ'], $this->formData['ref_posi']); 
					}
					else if ($loggedAsSessionViewer)
					{
						$sessionsList = $preSelectSession;
					}
					 

					if (!$sessionsList)
					{
						$this->registerError("form_empty", "Aucun positionnement n'a été effectué par l'utilisateur sélectionné.");
					}
					else 
					{
						foreach ($sessionsList['response']['session'] as $session)
						{
							$j = 0;
							$exists = false;

							foreach ($list['sessions'] as $pass)
							{
								if ($session->getId() == $pass['id_session'] && !$exists) 
								{
									$sess['response']['session'][$j] = $session;
									$exists = true;
								}

								$j++;
							}

							if ($session->getId() == $this->formData['ref_session'])
							{
								$resultsetInter = $this->servicesRestitution->getIntervenant($session->getRefIntervenant());

								if ($resultsetInter)
								{
									$nomInter = $resultsetInter['response']['intervenant'][0]->getNom();
									$emailInter = $resultsetInter['response']['intervenant'][0]->getEmail();
								}
							}
						}
					
						$this->returnData['response'] = array_merge($sess['response'], $this->returnData['response']);
					}
				} 

				/*** On va chercher toutes les sessions qui correspondent à l'utilisateur sélectionné ***/
				/*
				$resultsetSessions = $this->servicesRestitution->getUserSessions($this->formData['ref_user'], $this->formData['ref_organ'], $this->formData['ref_posi']);


				$sessions = array('response' => array('sessions'));

				if (!$resultsetSessions)
				{
					$this->registerError("form_empty", "Aucun positionnement n'a été effectué par l'utilisateur sélectionné.");
				}
				else 
				{
					foreach ($resultsetSessions['response']['session'] as $session)
					{
						foreach ($list['sessions'] as $sess)
						{
							if ($session->getId() == $sess['id_session']) 
							{
								$sessions['response']['sessions'][] = $session;
							}
						}
					}
				
					$this->returnData['response'] = array_merge($sessions['response'], $this->returnData['response']);
				}
				*/



				/*
				if (empty($resultsetSessions['response']))
				{
					$this->registerError("form_empty", "Aucun positionnement n'a été effectué par cet utilisateur.");
				}
				else 
				{
					$resultset['response']['sessions'] = $resultsetSessions['response']['session'];
					
					//$this->returnData['response'] = array_merge($resultset['response'], $this->returnData['response']);
					*/
				// Transformation de la date et du temps
					/*
				$date = Tools::toggleDate(substr($resultset['response']['sessions'][0]->getDate(), 0, 10));
				$timeToSeconds = Tools::timeToSeconds(substr($resultset['response']['sessions'][0]->getDate(), 11, 8), $inputFormat = "h:m:s");
				$time = Tools::timeToString($timeToSeconds, "h:m");         
				$this->returnData['response']['infos_user']['date_last_posi'] = "Le ".$date." à ".str_replace(":", "h", $time);
				*/


				/*------   Une session a été sélectionnée   -------*/
				
				if (!empty($this->formData['ref_session']) && $this->formData['ref_session'] != "select_cbox")
				{
					/*** On va chercher les infos sur la session qui correspondent à la référence de la session sélectionné ***/
					$resultsetSession = $this->servicesRestitution->getSession($this->formData['ref_session']);
					$this->returnData['response'] = array_merge($resultsetSession['response'], $this->returnData['response']);

					
					/*** On récupère également les infos sur l'intervenant ***/
					$resultsetIntervenant = $this->servicesRestitution->getIntervenant($resultsetSession['response']['session'][0]->getRefIntervenant());
					$this->returnData['response']['infos_user']['nom_intervenant'] = $resultsetIntervenant['response']['intervenant'][0]->getNom();
					$this->returnData['response']['infos_user']['email_intervenant'] = $resultsetIntervenant['response']['intervenant'][0]->getEmail();

					$refSession = $resultsetSession['response']['session'][0]->getId();
					$this->returnData['response']['infos_user']['ref_selected_session'] = $refSession;
					$this->returnData['response']['infos_user']['ref_valid_acquis'] = $resultsetSession['response']['session'][0]->getRefValidAcquis();
					
					/*--------- Statistiques par catégories(temps, score...)-------------*/	
					$this->returnData['response']['stats'] = array();
					$this->returnData['response']['stats'] = $this->servicesRestitution->getPosiStats($this->formData['ref_session'], $this->formData['ref_posi']);
					
					

					/*------ Validation des acquis -------*/

					$refValidAcquis = '';

					if (isset($_POST['ref_valid_cbox']) && !empty($_POST['ref_valid_cbox'])) 
					{
						$refValidAcquis = $_POST['ref_valid_cbox'];
					}

					if ($refValidAcquis == 'select_cbox') 
					{
						$refValidAcquis = 'NULL';
					}
					
					if (!empty($refValidAcquis) && $refValidAcquis != $this->returnData['response']['infos_user']['ref_valid_acquis'])
					{
						 // Sauvegarde du niveau des acquis sélectionné par l'utilisateur
						$validRequest = $this->servicesRestitution->setValidAcquis($refValidAcquis, $this->formData['ref_session']);

						if ($validRequest) 
						{
							if ($refValidAcquis != 'NULL') {
								$this->returnData['response']['infos_user']['ref_valid_acquis'] = $refValidAcquis;
							}
							else
							{
								$this->returnData['response']['infos_user']['ref_valid_acquis'] = '';   
							}  
						}
					}
					
					
					/*** On recherche toutes les questions ***/
					$this->returnData['response']['details']['questions'] = array();
					$this->returnData['response']['details']['questions'] = $this->servicesRestitution->getQuestionsDetails($this->formData['ref_session'], $this->formData['ref_posi']);

					//var_dump($this->returnData['response']['details']['questions']);
					//exit();

				}

			}
		}


		/* Fin Création des listes */

		/*=====  Fin Requête globale après sélection  ======*/






		/* ==========================================
		   3. Génération des vues
		   ========================================== */

			/*		- 3.1. Création des différentes listes
			 *   	- 3.2. Retour des données traitées
			 * 		- 3.3. Export PDF
			 * 		- 3.4. Export CSV - Excel
			 * 		- 3.5. Génération de la restitution globale et finale
			 */

		/* 3.1. Création des différentes listes
		   ========================================================================== */
		
		/* Fin Création des listes */


		/* 3.2. Retour des données traitées
		   ========================================================================== */
		/*
		$this->returnData['response']['form_data'] = $this->formData;
		$this->returnData['response']['url'] = $this->url;

		// S'il y a des erreurs, on les injecte dans la réponse
		if (!empty($this->errors) && count($this->errors) > 0)
		{
			foreach($this->errors as $error)
			{
				$this->returnData['response']['errors'][] = $error;
			}
		}
		
		$this->setResponse($this->returnData);
		*/
	

		/* Fin Retour des données traitées */


		/*=====  Fin Génération des vues  ======*/





		
		


















		
		/*** Initialisation des infos sur le positionnement ***/
		/*
		$list = array(
			'organismes' => array(),
			'utilisateurs' => array(),
			'domaines' => array(),
			'sessions' => array()
		);
		
		// On commence par obtenir le nom et l'id de chaque organisme de la table "organisme" en fonction de la region

		$resultsListings = $this->servicesRestitution->search($regions, $this->formData['ref_region'], $this->formData['ref_organ'], $this->formData['ref_user'], $this->formData['ref_posi']);
		*/
		//var_dump($resultsListings);
		//exit();
		/*
		if (isset($resultsListings['response']['restitution']) && !empty($resultsListings['response']['restitution']))
		{

			$listings = $resultsListings['response']['restitution'];


			$i = 0;

			foreach ($listings as $entity) {

				foreach ($entity as $key => $value) {
				
					switch ($key) {

						case 'id_organ':
							$list['organismes'][$i]['id_organ'] = $value;
							break;

						// case 'nom_organ':
						// 	$list['organismes'][$i]['nom_organ'] = $value;
						// 	break;

						case 'id_user':
							$list['utilisateurs'][$i]['id_user'] = $value;
							break;

						// case 'nom_user':
						// 	$list['utilisateurs'][$i]['nom_user'] = $value;
						// 	break;

						// case 'prenom_user':
						// 	$list['utilisateurs'][$i]['prenom_user'] = $value;
						// 	break;
						case 'id_posi':
							$list['domaines'][$i]['id_posi'] = $value;
							break;

						case 'id_session':
							$list['sessions'][$i]['id_session'] = $value;
							break;

						// case 'date_session':
						// 	$list['sessions'][$i]['date_session'] = $value;
						// 	break;

						default :
							break;
					}
				}

				$i++;
			}
			
		}
		*/


		/*
		if ($loggedAsViewer)
		{
			$organismesList = $preSelectOrganisme;
		}
		else if ($loggedAsAdmin)
		{
			$organismesList = $this->servicesRestitution->getOrganismesList(); 
		}


		
		
		$nomOrgan = null;
		$codeOrgan = null;
		//$organismes = array();
		//$organismes['response']['organisme'] = array();
		$organismes = array(
			'response' => array(
				'organisme' => array()
			)
		);


		if (!$organismesList)
		{
			$this->registerError("form_empty", "Aucun organisme n'a été trouvé.");
		}
		else 
		{
			
			foreach ($organismesList['response']['organisme'] as $organisme)
			{
				$j = 0;

				$exists = false;

				foreach ($list['organismes'] as $organ)
				{
					
					if ($organisme->getId() == $organ['id_organ'] && !$exists) 
					{
						$organismes['response']['organisme'][$j] = $organisme;
						
						$exists = true;
					}

					$j++;
				}

				if ($organisme->getId() == $this->formData['ref_organ'])
				{
					$nomOrgan = $organisme->getNom();
					$codeOrgan = $organisme->getNumeroInterne();
				}
			}


			$this->returnData['response'] = array_merge($organismes['response'], $this->returnData['response']);
		}
		*/





		// Liste des domaines pour le combo-box
		
		// $domainesList = array();
		// $domainesList = $this->servicesRestitution->getPositionnementsList();
		// $this->returnData['response'] = array_merge($domainesList['response'], $this->returnData['response']);
		

		/*** On va chercher les infos pour créer la liste de validation des acquis ***/
		$valid_acquis = array();
		$valid_acquis = $this->servicesRestitution->getValidAcquis();
		$this->returnData['response'] = array_merge($valid_acquis['response'], $this->returnData['response']);

		//var_dump($this->returnData['response']['infos_user']['ref_valid_acquis']);
		//exit();


		

		
		/*-----   Retour des données traitées du formulaire   -----*/
		
		$this->returnData['response']['form_data'] = $this->formData;
		$this->returnData['response']['url'] = $this->url;

		// S'il y a des erreurs, on les injecte dans la réponse
		if (!empty($this->errors) && count($this->errors) > 0)
		{
			foreach($this->errors as $error)
			{
				$this->returnData['response']['errors'][] = $error;
			}
		}
		
		
		$this->setResponse($this->returnData);
		

		if (isset($_POST['export_pdf']) && !empty($_POST['export_pdf']))
		{

			if ($this->returnData['response']['infos_user']['nom'] && $this->returnData['response']['infos_user']['prenom'])
			{
				$dateSession = Tools::toggleDate(substr($this->returnData['response']['session'][0]->getDate(), 0, 10));
				$timeToSeconds = Tools::timeToSeconds(substr($this->returnData['response']['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); 
				$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); 
				
				$file = $this->returnData['response']['infos_user']['nom']."_".$this->returnData['response']['infos_user']['prenom']."_".$dateSession."_".$time.".pdf";
				$this->renderPDF("restitution_pdf", $file, "D");
			}
			else 
			{
				$this->returnData['response']['errors'][] = array('type' => "form_valid", 'message' => "Le PDF n'a pu être généré. Veuillez réessayer ultérieurement.");
			}

		}
		else if (isset($_POST['export_xls']) && !empty($_POST['export_xls']))
		{
			$this->setTemplate("tpl_empty");
			$this->render("restitution_xls");
		}
		else
		{
			$this->setTemplate("tpl_public");
			//$this->setTemplate("tpl_old_page");
			$this->render("restitution");
			//$this->setTemplate("tpl_basic_page");
			//$this->setHeader("header_admin_large");
			//$this->setFooter("footer");


			//$this->enqueueScript("lightbox-2.6.min");
			//$this->enqueueScript("jquery.tablesorter");

			//$this->enqueueScript("pages/restitution");

			//$this->render("restitution_new");
		}

	}











	/**
	 * statistique - Gére la validation du formulaire de gestion des degrés d'aptitude avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
	 *
	 * @param array Tableau de paramètres passés par url (le code d'identification de l'organisme)
	 */
	public function statistique($requestParams = array())
	{

		$this->initialize();
		$this->servicesRestitution->initialize();

		$codeOrgan = "";
		$loggedAsViewer = false;
		$loggedAsAdmin = false;
		$preSelectOrganisme = null;


		// Lecture du intégration du fichier 'régions'
		$regions = null;
		$hasRegions = $this->servicesAdminStat->createRegionsList(Config::ANNEE_REGION);

		if ($hasRegions)
		{
			$regionsList['response'] = array();
			$regionsList['response']['regions'] = $this->servicesAdminStat->getRegionsList();
		}

		if (isset($regionsList['response']['regions']) && !empty($regionsList['response']['regions'])) 
		{
			$regions = $regionsList['response']['regions'];
		}


		// on vérifie s'il y a un code dans les parametres url
		if (isset($requestParams[0]) && !empty($requestParams[0]))
		{   
			if (preg_match("`^[a-zA-Z0-9]*$`", $requestParams[0]))
			{
				// On récupère le code
				$codeOrgan = $requestParams[0];
				
				// On va chercher le code organisme correspondant
				$preSelectOrganisme = $this->organismeDAO->selectByCodeInterne($codeOrgan);
				
				if (!$this->filterDataErrors($preSelectOrganisme['response']))
				{
					if (!empty($preSelectOrganisme['response']['organisme']) && count($preSelectOrganisme['response']['organisme']) == 1)
					{ 
						$organ = $preSelectOrganisme['response']['organisme'];
						$preSelectOrganisme['response']['organisme'] = array($organ);
					}
					$loggedAsViewer = true;
				}
				else 
				{
					// Redirection vers une page d'erreur interne
					header("Location: ".SERVER_URL."erreur/page500");
					exit();
				}
			}
			else 
			{
				// Redirection vers une page d'erreur non autorisé
				header("Location: ".SERVER_URL."erreur/page503");
				exit();
			}
		}
		else 
		{
			// Sinon, authentification necessaire
			ServicesAuth::login("custom-public");
			//ServicesAuth::checkAuthentication("custom");
			$loggedAsAdmin = true;
		}

		$this->url = SERVER_URL."public/statistique/".$codeOrgan;



		/*** Requêtes ajax pour avoir les organismes en fonction de la région choisie ***/
		
		if (Config::ALLOW_AJAX)
		{
			//if ($loggedAsViewer || $loggedAsAdmin)
			//{
				if (isset($_POST['ajax_request']) && !empty($_POST['ajax_request']))
				{

					if (isset($_POST['ref_region']) && !empty($_POST['ref_region']))
					{
						$resultsetOrgan = $this->servicesAdminStat->getOrganismesByRegion($_POST['ref_region']);

						if (isset($resultsetOrgan['response']['organisme']) && !empty($resultsetOrgan['response']['organisme']))
						{
							/*
							$results = array();
							$k = 0;

							foreach ($resultsetOrgan['response']['organisme'] as $organisme) 
							{
								$results[$k]['ref'] = $region;
								$results[$k]['nom'] = $region;
								$k++;
							}
							*/
							$response = array('error' => false, 'results' => $resultsetOrgan['response']);
						}
						else
						{
							$response = array('error' => "Aucun organisme n'est localisé dans cette région.");
						}

					}	
					else if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
					{
							
						
						
					}
					else
					{
						//$response = array('error' => "Vous n'avez pas sélectionné d'organisme.");
					}


					echo json_encode($response);
					exit();
				}
				
			//}
		}
		
		/*** Fin requêtes ajax ***/




		/*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
		$this->formData['ref_organ'] = null;

		$initializedData = array(
			"ref_region_cbox" => "select", 
			"ref_organ_cbox"  => "select", 
			"date_debut"      => "text", 
			"date_fin"        => "text"
		);
		$this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);

		// On récupère les differents identifiants de la zone de sélection

		$this->formData['ref_region'] = $this->formData['ref_region_cbox'];

		$this->formData['ref_organ'] = $this->formData['ref_organ_cbox'];

		if (isset($_POST['date_debut']) && !empty($_POST['date_debut']))
		{
			$this->formData['date_debut'] = $_POST['date_debut'];
		}
		
		if (isset($_POST['date_fin']) && !empty($_POST['date_fin']))
		{
			$this->formData['date_fin'] = $_POST['date_fin'];
		}

		// Sauf si l'organisme est déjà connu
		if ($loggedAsViewer)
		{
			$this->formData['ref_organ'] = $preSelectOrganisme['response']['organisme'][0]->getId();
		}
		

		

		$filters = array();
		$filters['start_date'] = false;
		$filters['end_date'] = false;

		if (!empty($this->formData['date_debut']))
		{
			if (preg_match("`^[0-3][0-9]\/[0-1][0-9]\/[0-9][0-9][0-9][0-9]$`", $this->formData['date_debut']))
			{
				$filters['start_date'] = Tools::toggleDate($this->formData['date_debut'], "us")." 00:00:00";
			}
			else
			{
				$this->registerError("form_valid", "La date de début n'est pas valide.");
			}
		}

		if (!empty($this->formData['date_fin']))
		{
			if (preg_match("`^[0-3][0-9]\/[0-1][0-9]\/[0-9][0-9][0-9][0-9]$`", $this->formData['date_fin']))
			{
				$filters['end_date'] = Tools::toggleDate($this->formData['date_fin'], "us")." 23:59:59";
			}
			else
			{
				$this->registerError("form_valid", "La date de fin n'est pas valide.");
			}
		}
		
		

		$this->returnData['response']['stats'] = $this->servicesAdminStat->getCustomStats($this->formData['ref_region'], $this->formData['ref_organ'], $filters['start_date'], $filters['end_date']);



		/*-----   Retour des données traitées du formulaire   -----*/
		
		$this->returnData['response']['form_data'] = $this->formData;
		$this->returnData['response']['url'] = $this->url;

		// S'il y a des erreurs, on les injecte dans la réponse
		if (!empty($this->errors) && count($this->errors) > 0)
		{
			foreach($this->errors as $error)
			{
				$this->returnData['response']['errors'][] = $error;
			}
		}


		// Liste des régions pour le combo-box

		//$regionsList['response'] = $this->servicesAdminStat->getRegionsList('2015');

		if ($regionsList['response']['regions']) 
		{
			$this->returnData['response'] = array_merge($regionsList['response'], $this->returnData['response']);
		}
		
		

		// Liste des organismes pour le combo-box
		if ($loggedAsViewer)
		{
			$organismesList = $preSelectOrganisme;
		}
		else if ($loggedAsAdmin)
		{
			$organismesList = $this->servicesRestitution->getOrganismesList(); 
		}
		//$this->returnData['response'] = array_merge($organismesList['response'], $this->returnData['response']);
		




		/*
		$nomOrgan = null;
		$codeOrgan = null;
		foreach ($organismesList['response']['organisme'] as $organisme)
		{
			if ($organisme->getId() == $this->formData['ref_organ'])
			{
				$nomOrgan = $organisme->getNom();
				$codeOrgan = $organisme->getNumeroInterne();
			}
		}
		*/

		// Liste des organismes pour le combo-box
		//$organismesList = $this->servicesRestitution->getOrganismesList(); 
		$this->returnData['response'] = array_merge($organismesList['response'], $this->returnData['response']);

		// On envoie les infos de la page à la vue
		$this->setResponse($this->returnData);

		
		
		// Si l'utilisateur a cliqué sur un des boutons d'export, on génère le fichier excel au format CSV
		if (isset($_POST['export_total_organisme']) && !empty($_POST['export_total_organisme']))
		{
			$this->setTemplate("tpl_empty");
			$this->render("statistique_posi_organ_xls");
		}
		else if (isset($_POST['export_niveau_nombre']) && !empty($_POST['export_niveau_nombre']))
		{
			$this->setTemplate("tpl_empty");
			$this->render("statistique_niveau_xls");
		}
		else if (isset($_POST['export_score_competences']) && !empty($_POST['export_score_competences']))
		{
			$this->setTemplate("tpl_empty");
			$this->render("statistique_competences_xls");
		}
		else if (isset($_POST['export_acquis']) && !empty($_POST['export_acquis']))
		{
			$this->setTemplate("tpl_empty");
			$this->render("statistique_repartition_degre_xls");
		}
		
		else
		{
			// Sinon on affiche la page normalement
			$this->setTemplate("tpl_public");
			$this->render("statistique");
		}
	}





	/*
	private function getCategoriesHierarchy($parent, $level, $categoriesArray)
	{
		$list = '';
		$previous_level = 0;

		if ($level <= 0 && $previous_level <= 0) 
		{
			$list .= '<ul>';
		}

		foreach ($categoriesArray as $categorieNode) {
			
			if ($parent == $categorieNode->getParent()) {

				if ($previous_level < $level) {
					$list .= '<ul>';
				}

				$list .= '<li>'.$categorieNode->getNom;
				$previous_level = $level;

				$list .= getCategoriesHierarchy($categorieNode->getCode(), ($level + 1), $categoriesArray);
			}
		}

		if ($previous_level == $level && $previous_level != 0) {
			$list .= '</ul></li>';
		}
		else if ($previous_level == $level) {
			$list .= '</ul>';
		}
		else {
			$list .= '</li>';
		}

		return $list;
	}
	*/
	
}


?>
