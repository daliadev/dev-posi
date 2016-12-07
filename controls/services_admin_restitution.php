<?php


require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/posi_dao.php');
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/degre_dao.php');
require_once(ROOT.'models/dao/reponse_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/preconisation_dao.php');
require_once(ROOT.'models/dao/parcours_preco_dao.php');
require_once(ROOT.'models/dao/valid_acquis_dao.php');

require_once(ROOT.'models/dao/custom_dao.php');


require_once(ROOT.'controls/services_admin_categorie.php');
require_once(ROOT.'controls/services_resultats.php');


class ServicesAdminRestitution extends Main
{
	
	private $organismeDAO = null;
	private $utilisateurDAO = null;
	private $positionnementDAO = null;
	private $niveauEtudesDAO = null;
	private $sessionDAO = null;
	private $intervenantDAO = null;
	private $resultatDAO = null;
	private $questionDAO = null;
	private $degreDAO = null;
	private $reponseDAO = null;
	private $questionCatDAO = null;
	private $categorieDAO = null;
	private $preconisationDAO = null;
	private $parcoursPrecoDAO = null;
	private $validAcquisDAO = null;

	private $customDAO = null;

	private $servicesCategories = null;
	private $servicesResultats = null;
	
	
	
	public function __construct() 
	{
		$this->errors = array();
		$this->controllerName = "adminRestitution";

		$this->organismeDAO = new OrganismeDAO();
		$this->utilisateurDAO = new UtilisateurDAO();
		$this->positionnementDAO = new PositionnementDAO();
		$this->niveauEtudesDAO = new NiveauEtudesDAO();
		$this->sessionDAO = new SessionDAO();
		$this->intervenantDAO = new IntervenantDAO();
		$this->questionDAO = new QuestionDAO();
		$this->degreDAO = new DegreDAO();
		$this->reponseDAO = new ReponseDAO();
		$this->resultatDAO = new ResultatDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
		$this->categorieDAO = new CategorieDAO();
		$this->preconisationDAO = new PreconisationDAO();
		$this->parcoursPrecoDAO = new ParcoursPrecoDAO();
		$this->validAcquisDAO = new ValidAcquisDAO();

		$this->customDAO = new CustomDAO();

		$this->servicesResultats = new ServicesPosiResultats();
	}

	
	
	
	
	public function getOrganismesList()
	{
		$resultset = $this->organismeDAO->selectAll();

		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
			{ 
				$organisme = $resultset['response']['organisme'];
				$resultset['response']['organisme'] = array($organisme);
			}

			return $resultset;
		}

		return false;
	}
	

	public function getPositionnementsList()
	{
		$resultset = $this->positionnementDAO->selectAll();

		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['positionnement']) && count($resultset['response']['positionnement']) == 1)
			{ 
				$positionnement = $resultset['response']['positionnement'];
				$resultset['response']['positionnement'] = array($positionnement);
			}

			return $resultset;
		}

		return false;
	}

	

	public function getUsersFromOrganisme($refOrganisme)
	{
		$resultset = $this->utilisateurDAO->selectByOrganisme($refOrganisme);

		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
			{ 
				$utilisateur = $resultset['response']['utilisateur'];
				$resultset['response']['utilisateur'] = array($utilisateur);
			}
			
			for ($i = 0; $i < count($resultset['response']['utilisateur']); $i++)
			{
				$sessionsAccomplies = $resultset['response']['utilisateur'][$i]->getSessionsAccomplies();
				
				if (intval($sessionsAccomplies) === 0)
				{
					unset($resultset['response']['utilisateur'][$i]);
				}
			}
			
			return $resultset;
		}

		return false;
	}


	public function getPosisFromUser($refUser)
	{
		$resultset = $this->positionnementDAO->selectByUser($refUser);

		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['positionnement']) && count($resultset['response']['positionnement']) == 1)
			{ 
				$positionnement = $resultset['response']['positionnement'];
				$resultset['response']['positionnement'] = array($positionnement);
			}
			
			return $resultset;
		}

		return false;
	}
	
	



	public function getUserSessions($refUser, $refOrganisme, $refPosi)
	{
		$resultset = $this->sessionDAO->selectByUser($refUser, $refOrganisme, $refPosi);
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
			{ 
				$session = $resultset['response']['session'];
				$resultset['response']['session'] = array($session);
			}
			
			for ($i = 0; $i < count($resultset['response']['session']); $i++)
			{
				$sessionsAccomplie = $resultset['response']['session'][$i]->getSessionAccomplie();
				if (intval($sessionsAccomplie) === 0)
				{
					unset($resultset['response']['session'][$i]);
				}
			}

			return $resultset;
		}
		
		return false;
	}


	


	public function getSession($refSession)
	{
		$resultset = $this->sessionDAO->selectById($refSession);
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
			{ 
				$session = $resultset['response']['session'];
				$resultset['response']['session'] = array($session);
			}
		}

		return $resultset;
	}





	public function getIntervenant($refIntervenant)
	{
		$resultset = $this->intervenantDAO->selectById($refIntervenant);

		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['intervenant']) && count($resultset['response']['intervenant']) == 1)
			{ 
				$intervenant = $resultset['response']['intervenant'];
				$resultset['response']['intervenant'] = array($intervenant);
			}

			return $resultset;
		}

		return false;
	}




	public function search($regionsList, $refRegion = null, $refOrgan = null, $refUser = null, $refPosi = null, $date = null, $codeOrgan = null, $ref_inter = null)
	{

		// code postaux
		
		$codePostalRequest = "";

		if (!empty($refRegion))
		{
			foreach ($regionsList as $region)
			{
				if ($refRegion == $region['ref']) {

					$departements = $region['departements'];

					$k = 0;

					foreach ($departements as $departmnt)
					{
						if ($k > 0) 
						{
							$codePostalRequest.= "OR ";
						}
						else
						{
							$codePostalRequest.= "AND ";
						}

						$codePostalRequest .= "org.code_postal_organ LIKE '".$departmnt['numero']."___' ";

						$k++;
					}

					break;
				}
			}
		}


		$query = "SELECT org.id_organ, org.nom_organ, sess.id_session, sess.date_session, user.id_user, user.nom_user, user.prenom_user, dom.id_posi, dom.nom_posi ";
		$query .= "FROM organisme AS org ";
		$query .= "INNER JOIN intervenant AS inter ";
		$query .= "ON org.id_organ = inter.ref_organ ";
		$query .= "INNER JOIN session AS sess ";
		$query .= "ON inter.id_intervenant = sess.ref_intervenant ";
		$query .= "INNER JOIN utilisateur AS user ";
		$query .= "ON user.id_user = sess.ref_user ";
		$query .= "INNER JOIN positionnement AS dom ";
		$query .= "ON dom.id_posi = sess.ref_posi ";
		$query .= "WHERE sess.session_accomplie = 1 ";

		if ($refOrgan) 
		{
			$query .= "AND org.id_organ = ".$refOrgan." ";
		}
		else
		{
			$query .= $codePostalRequest;
		}

		if ($codeOrgan) 
		{
			$query .= "AND org.numero_interne LIKE '".$codeOrgan."' ";
		}
		/*
		if ($date) 
		{
			$date = new DateTime($date);
			$date->format('Y-m-d H:i:s');
			
			$query .= "AND sess.date_session >= '".$date."' AND sess.date_session < DATE_ADD('".$date."', INTERVAL 1 DAY) ";
		}
		*/
		if ($refUser) 
		{
			$query .= "AND user.id_user = ".$refUser." ";
		}

		if ($refPosi) 
		{
			$query .= "AND sess.ref_posi = ".$refPosi." ";
			$query .= "AND dom.id_posi = ".$refPosi." ";
		}
		//$query .= "GROUP BY user.id_user ";
		$query .= "GROUP BY dom.id_posi, user.id_user, org.id_organ ORDER BY org.nom_organ, user.nom_user, dom.nom_posi, sess.date_session ASC";

		//return $query;
		//var_dump($query);
		//exit();


		$resultset = $this->customDAO->read($query, 'restitution');

		//var_dump($resultset);
		//exit();

		if (!$this->filterDataErrors($resultset['response']))
		{
			return $resultset;
		}

		return false;
	}
	
	

	public function getInfosUser($refUser)
	{
		$userInfos = array();
		
		$userInfos['nom_organ'] = null;
		$userInfos['nom_intervenant'] = null;
		$userInfos['email_intervenant'] = null;
		$userInfos['nom'] = null;
		$userInfos['prenom'] = null;
		$userInfos['date_naiss'] = null;
		$userInfos['adresse'] = null;
		$userInfos['code_postal'] = null;
		$userInfos['ville'] = null;
		$userInfos['email'] = null;
		$userInfos['tel'] = null;
		$userInfos['nom_niveau'] = null;
		$userInfos['descript_niveau'] = null;
		$userInfos['nbre_positionnements'] = null;
		$userInfos['date_last_posi'] = null;


		$resultsetUser = $this->getUser($refUser);

		if ($resultsetUser)
		{
			$utilisateur = $resultsetUser['response']['utilisateur'][0];
			
			$userInfos['nom'] = $utilisateur->getNom();
			$userInfos['prenom'] = $utilisateur->getPrenom();
			$userInfos['date_naiss'] = Tools::toggleDate($utilisateur->getDateNaiss());
			$userInfos['adresse'] = $utilisateur->getAdresse();
			$userInfos['code_postal'] = $utilisateur->getCodePostal();
			$userInfos['ville'] = $utilisateur->getVille();
			$userInfos['email'] = $utilisateur->getEmail();
			$userInfos['tel'] = $utilisateur->getTel();
			$userInfos['nbre_positionnements'] = $utilisateur->getSessionsAccomplies();
			
			$resultsetNiveau = $this->getNiveau($utilisateur->getRefNiveau());

			if ($resultsetNiveau)
			{
				$userInfos['nom_niveau'] = $resultsetNiveau['response']['niveau_etudes'][0]->getNom();
				$userInfos['descript_niveau'] = $resultsetNiveau['response']['niveau_etudes'][0]->getDescription();
			}
		}
		
		return $userInfos;
	}
	
	



	public function getValidAcquis()
	{
		$resultset = $this->validAcquisDAO->selectAll();

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['valid_acquis']) && count($resultset['response']['valid_acquis']) == 1)
			{ 
				$question = $resultset['response']['valid_acquis'];
				$resultset['response']['valid_acquis'] = array($question);
			}

			return $resultset;
		}

		return false;
	}


	public function setValidAcquis($refValidAcquis, $idSession)
	{
		$resultset = $this->sessionDAO->updateValidAcquis($refValidAcquis, $idSession);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['session']['row_count']) && !empty($resultset['response']['session']['row_count']))
		{
			return true;
		} 
		else 
		{
			//$this->registerError("form_request", "Le degré n'a pu être mis à jour.");

		}

		return false;
	}


	
	public function updateValidResultat($refQuestion, $refSession, $isValid)
	{
		$resultset = $this->resultatDAO->updateValidation($refQuestion, $refSession, $isValid);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['resultat']['row_count']) && !empty($resultset['response']['resultat']['row_count']))
		{
			return true;
		}

		return false;
	}

	
	
	
	public function getPosiStats($refSession, $refPosi = null)
	{


		$posiStats = array();
		$posiStats['categories'] = array();
		
		
		/*** On récupère la liste des categories ***/
		
		$resultsetcategories = $this->getCategories($refPosi);

		$categoriesList = $resultsetcategories['response']['categorie'];
		

		
		// On sélectionne tous les résultats correspondant à la session en cours
		$resultats = $this->getResultatsByCategories($refSession, $refPosi);

		// Liste des parcours de formation
		$parcoursPreco = $this->parcoursPrecoDAO->selectAll();


		
		$tempsGlobal = 0;
		$totalGlobal = 0;
		$totalCorrectGlobal = 0;
		$percentGlobal = 0;
		$countPrimeCategories = 0;
		$maxLevel = 0;

		
		foreach ($categoriesList as $categorie)
		{
			$tempsCat = 0;
			$totalCategorie = 0;
			$totalCorrectCategorie = 0;
			$percentCategorie = 0;
			$hasResults = false;


			// On récupére le niveau de la catégorie dans la hiérarchie
			$level = $categorie->getLevel();

			if ($level > $maxLevel)
			{
				$maxLevel = $level;
			}


			// Test d'existence d'une catégorie parente
			
			if ($level > 1)
			{
				foreach ($categoriesList as $searchParentCat)
				{
					if ($searchParentCat->getCode() == $categorie->getParentCode())
					{
						$categorie->setParent($searchParentCat);
					}
				}
			}
			else if ($level == 1)
			{
				// Catégorie principale -> pas de parent
				//$countPrimeCategories++;
			}
			
			
			// On attribut à chaque catégorie, les réponses et les scores à partir des résultats
			
			for ($j = 0; $j < count($resultats); $j++)  
			{
				if ($resultats[$j]->getRefCat() == $categorie->getCode())
				{
					$totalCategorie++;
					$totalGlobal++;
				   	
				   	$tempsGlobal += $resultats[$j]->getTempsReponse();
					$tempsCat += $resultats[$j]->getTempsReponse();

					//var_dump($resultats[$j]->getRefReponseQcm(), $resultats[$j]->getRefReponseQcmCorrecte(), $resultats[$j]->getReponseChamp(), $resultats[$j]->getValidationReponseChamp());

					if ((!empty($resultats[$j]->getRefReponseQcm()) && $resultats[$j]->getRefReponseQcm() !== null && $resultats[$j]->getRefReponseQcm() == $resultats[$j]->getRefReponseQcmCorrecte()) 
						|| (!empty($resultats[$j]->getReponseChamp()) && $resultats[$j]->getReponseChamp() !== null && !empty($resultats[$j]->getValidationReponseChamp()) && $resultats[$j]->getValidationReponseChamp() !== null && $resultats[$j]->getValidationReponseChamp() == 1))
					{
						$totalCorrectCategorie++;
						$totalCorrectGlobal++;
					}

					$hasResults = true;
				}

			}
			
			//exit();

			// Calcul du score en pourcentage

			if ($totalCategorie > 0 && $hasResults)
			{	
				$percentCategorie = round(($totalCorrectCategorie / $totalCategorie) * 100);
			}

			$categorie->setTemps($tempsCat);
			$categorie->setTotalReponses($totalCategorie);
			$categorie->setTotalReponsesCorrectes($totalCorrectCategorie);
			$categorie->setScorePercent($percentCategorie);
			$categorie->setHasResult($hasResults);


			$posiStats['categories'][] = $categorie;

		}

		$categories = $this->servicesResultats->getRecursiveCategoriesResults($maxLevel, $posiStats['categories'], null, 0);


		foreach ($categories as $categorie)
		{
		
			if (strlen($categorie->getCode()) == 2 && $categorie->getHasResult())
			{
				$countPrimeCategories++;

				$percentGlobal += $categorie->getScorePercent();
			}


			// Préconisations
			$volumePrecoCat = 0;

			
			if (strlen($categorie->getCode()) == 2 && isset($parcoursPreco['response']['parcours_preco']) && !empty($parcoursPreco['response']['parcours_preco']))
			{
				// Calcul du score global

				$precos = $this->preconisationDAO->selectByCodeCat($categorie->getCode());
				
				$scoreCat = $categorie->getScorePercent();
				
				if (isset($precos['response']['preconisation']) && !empty($precos['response']['preconisation']))
				{
					foreach ($precos['response']['preconisation'] as $preco) 
					{
						$refPreco = $preco->getId();
						$refParcours = $preco->getRefParcours();
						$tauxMin = $preco->getTauxMin();
						$tauxMax = $preco->getTauxMax();

						if ($scoreCat >= $tauxMin && $scoreCat <= $tauxMax) 
						{
							foreach ($parcoursPreco['response']['parcours_preco'] as $parcours) 
							{
								if ($parcours->getId() == $refParcours)
								{
									$volumePrecoCat += $parcours->getVolume();
								}
							}
						}
					}
				}
			}

			$categorie->setVolumePreconisations($volumePrecoCat);
		}

		$percentTotal = 0;

		if ($percentGlobal > 0 && $countPrimeCategories > 0)
		{
			$percentTotal = round($percentGlobal / $countPrimeCategories);
		}

		
		$posiStats['percent_global'] = $percentTotal;

		$posiStats['total_correct_global'] = $totalCorrectGlobal;
		$posiStats['total_global'] = $totalGlobal;

		$posiStats['categories'] = $categories;
		

		foreach ($categories as $categorie)
		{
			//$level = $this->servicesCategories->getLevel($categorie->getCode());
			$level = $categorie->getLevel();

			if ($level > $maxLevel)
			{
				$maxLevel = $level;
			}
		}



		return  $posiStats;
	}
	
	
	
	
	
	public function getQuestionsDetails($refSession, $refPosi = null)
	{
		
		// Etape  1 : Regroupement des données sur toutes les questions du positionnement
		$questionsDetails = array();
				
		$resultsetQuestions = $this->getQuestions($refPosi);

		if ($resultsetQuestions)
		{
			$i = 0;

			// Pour chaque question
			foreach ($resultsetQuestions['response']['question'] as $question)
			{

				// Initialisation des données récupérée de la question    
				$questionsDetails[$i] = array();
				$questionsDetails[$i]['ref_question'] = $question->getId();
				if (strlen($question->getNumeroOrdre()) == 1)
				{
					$questionsDetails[$i]['num_ordre'] = "0".$question->getNumeroOrdre();
				}
				else
				{
					$questionsDetails[$i]['num_ordre'] = $question->getNumeroOrdre();
				}
				
				$questionsDetails[$i]['type'] = $question->getType();
				$questionsDetails[$i]['intitule'] = $question->getIntitule();
				$questionsDetails[$i]['image'] = $question->getImage();

				$questionsDetails[$i]['nom_degre'] = "-";
				$questionsDetails[$i]['descript_degre'] = "";
				
				$questionsDetails[$i]['categories'] = array();

				$questionsDetails[$i]['reponses'] = array();

				$questionsDetails[$i]['reponse_user_qcm'] = "-";
				$questionsDetails[$i]['reponse_qcm_correcte'] = "-";
				$questionsDetails[$i]['reponse_user_champ'] = "-";
				$questionsDetails[$i]['intitule_reponse_user'] = "";
				$questionsDetails[$i]['intitule_reponse_correcte'] = "";
				$questionsDetails[$i]['temps'] = "";
				$questionsDetails[$i]['reussite'] = "-";
				$questionsDetails[$i]['validation'] = null;
   
				
				/*** Degré ***/

				$refDegre = $question->getRefDegre();

				if (!empty($refDegre))
				{
					$resultsetDegre = $this->getDegre($refDegre);

					if ($resultsetDegre)
					{
						$questionsDetails[$i]['nom_degre'] = $resultsetDegre['response']['degre'][0]->getNom();
						$questionsDetails[$i]['descript_degre'] = $resultsetDegre['response']['degre'][0]->getDescription();
					}
				}
				
				
				/*** Catégories ***/
				
				$resultsetCategories = $this->getCategoriesByQuestion($question->getId());

				$categories = array();

				if ($resultsetCategories) 
				{
					$j = 0;

					foreach ($resultsetCategories['response']['categorie'] as $categorie)
					{
						$codeCat = $categorie->getCode();

						if (strlen($codeCat) > 2)
						{
							$parentCode = substr($codeCat, 0, 2);
							$resultsetCat = $this->getCategorie($parentCode);

							if ($resultsetCat)
							{
								$categories[$j]['nom_cat_parent'] = $resultsetCat['response']['categorie'][0]->getNom();
								$categories[$j]['descript_cat_parent'] = $resultsetCat['response']['categorie'][0]->getDescription();
							}  
						}
						
						$categories[$j]['nom_cat'] = $categorie->getNom();
						$categories[$j]['descript_cat'] = $categorie->getDescription();

						$j++;
					}
				}

				$questionsDetails[$i]['categories'] = $categories;

				
				/*** Réponses ***/

				if ($question->getType() == "qcm")
				{
					$reponses = array();

					$resultsetReponses = $this->getReponsesByQuestion($question->getId());

					if ($resultsetReponses)
					{ 
						$j = 0;
						foreach ($resultsetReponses['response']['reponse'] as $reponse)
						{
							$reponses[$j] = array();
							$reponses[$j]['ref_reponse'] = $reponse->getId();
							$reponses[$j]['num_ordre_reponse'] = $reponse->getNumeroOrdre();
							$reponses[$j]['intitule_reponse'] = $reponse->getIntitule();
							$reponses[$j]['est_correcte'] = $reponse->getEstCorrect();

							$j++;
						}
					}    
				}
				/*
				else if ($question->getType() == "champ_saisie")
				{
					
				}
				*/
				$questionsDetails[$i]['reponses'] = $reponses;


				$i++;
			}
		}

		// Fin de la récupération des infos de chaque question


		// Etape 2 : Regroupement de toutes les infos sur l'utilisateur sélectionné

		$resultatsUser = array();

		$resultsetResultats = $this->getResultatsBySession($refSession);
		
		if ($resultsetResultats)
		{
			$i = 0;

			foreach ($resultsetResultats['response']['resultat'] as $result)
			{

				$resultatsUser[$i] = array();
				$resultatsUser[$i]['ref_resultat'] = $result->getId();
				$resultatsUser[$i]['ref_question'] = $result->getRefQuestion();
				$resultatsUser[$i]['ref_reponse_qcm'] = $result->getRefReponseQcm();
				$resultatsUser[$i]['ref_reponse_qcm_correcte'] = $result->getRefReponseQcmCorrecte();
				$resultatsUser[$i]['reponse_champ'] = $result->getReponseChamp();
				$resultatsUser[$i]['validation_reponse_champ'] = $result->getValidationReponseChamp();
				$resultatsUser[$i]['temps_reponse'] = $result->getTempsReponse();
  
				$i++;
			}
		}


		// Etape 3 : Recoupement entre les infos des questions et des résultats associés à chaque question
		
		for ($i = 0; $i < count($questionsDetails); $i++)
		{
			for ($j = 0; $j < count($resultatsUser); $j++)
			{
				if ($questionsDetails[$i]['ref_question'] == $resultatsUser[$j]['ref_question'])
				{

					$questionsDetails[$i]['reponse_user_qcm'] = "-";
					$questionsDetails[$i]['intitule_reponse_user'] = "-";
					$questionsDetails[$i]['reponse_qcm_correcte'] = "-";
					$questionsDetails[$i]['intitule_reponse_correcte'] = "-";
					$questionsDetails[$i]['temps'] = "-";
					$questionsDetails[$i]['reussite'] = "-";   

					if (!empty($resultatsUser[$j]['reponse_champ']))
					{
						if ($resultatsUser[$i]['validation_reponse_champ'] !== NULL)  {

							$questionsDetails[$i]['validation'] = $resultatsUser[$i]['validation_reponse_champ'];
						}
						else
						{
							$questionsDetails[$i]['validation'] = "null";
						}

						$questionsDetails[$i]['reponse_user_champ'] = $resultatsUser[$j]['reponse_champ'];
					}
					else if (!empty($resultatsUser[$j]['ref_reponse_qcm']) && !empty($resultatsUser[$j]['ref_reponse_qcm_correcte']))
					{
						for ($k = 0; $k < count($questionsDetails[$i]['reponses']); $k++)
						{
							if (!empty($questionsDetails[$i]['reponses'][$k]))
							{   
								$reponse = $questionsDetails[$i]['reponses'][$k];

								if (!empty($resultatsUser[$j]['ref_reponse_qcm_correcte']) && $reponse['ref_reponse'] == $resultatsUser[$j]['ref_reponse_qcm'])
								{
									$questionsDetails[$i]['reponse_user_qcm'] = $reponse['num_ordre_reponse'];
									$questionsDetails[$i]['intitule_reponse_user'] = $reponse['intitule_reponse'];
								}

								if (!empty($resultatsUser[$j]['ref_reponse_qcm_correcte']) && $reponse['ref_reponse'] == $resultatsUser[$j]['ref_reponse_qcm_correcte'])
								{
									$questionsDetails[$i]['reponse_qcm_correcte'] = $reponse['num_ordre_reponse'];
									$questionsDetails[$i]['intitule_reponse_correcte'] = $reponse['intitule_reponse'];
								}
							}
						}

						$questionsDetails[$i]['reussite'] = "-";

						if ($questionsDetails[$i]['reponse_user_qcm'] != "-" || $questionsDetails[$i]['reponse_qcm_correcte'] != "-")
						{
							if ($questionsDetails[$i]['reponse_user_qcm'] == $questionsDetails[$i]['reponse_qcm_correcte'])
							{
								$questionsDetails[$i]['reussite'] = 1;
							}
							else 
							{
								$questionsDetails[$i]['reussite'] = 0;
							}
						}
					}
				   
					if (!empty($resultatsUser[$j]['temps_reponse']))
					{
						$questionsDetails[$i]['temps'] = $resultatsUser[$j]['temps_reponse'];
					}

					break;
				}
			}
		}


		return $questionsDetails;
	}




   
	private function getUser($refUser)
	{
		$resultset = $this->utilisateurDAO->selectById($refUser);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
			{ 
				$utilisateur = $resultset['response']['utilisateur'];
				$resultset['response']['utilisateur'] = array($utilisateur);
			}

			return $resultset;
		}

		return false;
	}
	
	
	

	private function getNiveau($refNiveau)
	{
		$resultset = $this->niveauEtudesDAO->selectById($refNiveau);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['niveau_etudes']) && count($resultset['response']['niveau_etudes']) == 1)
			{ 
				$niveauEtudes = $resultset['response']['niveau_etudes'];
				$resultset['response']['niveau_etudes'] = array($niveauEtudes);
			}

			return $resultset;
		}

		return false;
	}


	private function getDegre($refDegre)
	{
		$resultset = $this->degreDAO->selectById($refDegre);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['degre']) && count($resultset['response']['degre']) == 1)
			{ 
				$degre = $resultset['response']['degre'];
				$resultset['response']['degre'] = array($degre);
			}

			return $resultset;
		}

		return false;
	}


	
	
	private function getCategories($refPosi = null)
	{
		$resultset = $this->categorieDAO->selectAll($refPosi);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
			{ 
				$categorie = $resultset['response']['categorie'];
				$resultset['response']['categorie'] = array($categorie);
			}

			foreach ($resultset['response']['categorie'] as $categorie) {
				
				$categorie->setLevel($this->categorieDAO->getLevel($categorie->getCode()));
			}

			return $resultset;
		}

		return false;
	}



	private function getCategorie($codeCat)
	{
		$resultset = $this->categorieDAO->selectByCode($codeCat);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
			{ 
				$categorie = $resultset['response']['categorie'];
				$resultset['response']['categorie'] = array($categorie);
			}

			foreach ($resultset['response']['categorie'] as $categorie) {
				
				$categorie->setLevel($this->categorieDAO->getLevel($categorie->getCode()));
			}

			return $resultset;
		}

		return false;
	}




	private function getCategoriesByQuestion($refQuestion)
	{
		$resultset = $this->categorieDAO->selectByQuestion($refQuestion);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
			{ 
				$categorie = $resultset['response']['categorie'];
				$resultset['response']['categorie'] = array($categorie);
			}

			return $resultset;
		}

		return false;
	}





	private function getResultatsBySession($refSession)
	{
		$resultset = $this->resultatDAO->selectBySession($refSession);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['resultat']) && count($resultset['response']['resultat']) == 1)
			{ 
				$resultat = $resultset['response']['resultat'];
				$resultset['response']['resultat'] = array($resultat);
			}

			return $resultset;
		}

		return false;
	}
	




	private function getQuestions($refPosi = null)
	{
		$resultset = $this->questionDAO->selectAll($refPosi);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
			{ 
				$question = $resultset['response']['question'];
				$resultset['response']['question'] = array($question);
			}

			return $resultset;
		}

		return false;
	}



	

	private function getQuestion($refQuestion)
	{
		$resultset = $this->questionDAO->selectById($refQuestion);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
			{ 
				$question = $resultset['response']['question'];
				$resultset['response']['question'] = array($question);
			}

			return $resultset;
		}

		return false;
	}




	private function getQuestionCategorie($refQuestion)
	{
		$resultset = $this->questionCatDAO->selectByRefQuestion($refQuestion);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['question_cat']) && count($resultset['response']['question_cat']) == 1)
			{ 
				$question_cat = $resultset['response']['question_cat'];
				$resultset['response']['question_cat'] = array($question_cat);
			}

			return $resultset;
		}

		return false;
	}





	private function getReponsesByQuestion($refQuestion)
	{
		$resultset = $this->reponseDAO->selectByQuestion($refQuestion);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['reponse']) && count($resultset['response']['reponse']) == 1)
			{ 
				$reponse = $resultset['response']['reponse'];
				$resultset['response']['reponse'] = array($reponse);
			}

			return $resultset;
		}

		return false;
	}




 
	private function getResultatsByCategories($refSession)
	{
		$tabResultats = array();
		
		// On sélectionne tous les résultats correspondant à la session en cours
		$resultsetResultats = $this->getResultatsBySession($refSession);

		//var_dump($resultsetResultats);
		//exit();

		if ($resultsetResultats)
		{
			$i = 0;
			
			foreach ($resultsetResultats['response']['resultat'] as $resultat)
			{     

				$tabResultats[$i] = $resultat;

				// On établit si le résultat est correct ou non
				/*
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
					*/
					//var_dump($resultat->getRefCat());
					
					//$resultsetCategories = $this->getCatFromQuestion($resultat->getRefCat());


					/*
					// Ensuite on va chercher les données sur la question correspondant au résultat
					$resultsetQuestion = $this->getQuestion($resultat->getRefQuestion());

					if ($resultsetQuestion)
					{        
						// On va chercher la compétence liée à la question dont dépend le résultat (est-ce clair !)
						$resultsetCatQuestion = $this->getQuestionCategorie($resultsetQuestion['response']['question'][0]->getId());

						if ($resultsetCatQuestion)
						{
							$tabResultats[$i]['code_cat'] = $resultsetCatQuestion['response']['question_cat'][0]->getCodeCat();
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
					*/

					$i++;
				//}
			}
		}
		else
		{
			$this->registerError("form_request", "Aucun resultat n'a été trouvé.");
		}
		
		//var_dump($tabResultats);
		//exit();
		
		return $tabResultats;
		
	}
	

}


?>
