<?php



// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/categorie_preco_dao.php');
require_once(ROOT.'models/dao/preconisation_dao.php');
require_once(ROOT.'models/dao/type_preco_dao.php');


class ServicesAdminCategorie extends Main
{
	
	private $categorieDAO = null;
	private $questionCatDAO = null;
	private $categoriePrecoDAO = null;
	private $preconisationDAO = null;
	private $typePrecoDAO = null;
	
	
	public function __construct() 
	{

		$this->controllerName = "adminCategorie";

		$this->categorieDAO = new CategorieDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
		$this->categoriePrecoDAO = new CategoriePrecoDAO();
		$this->preconisationDAO = new PreconisationDAO();
		$this->typePrecoDAO = new typePrecoDAO();
	}

	
	


	/*=============================================
	=            Gestion des catégories           =
	=============================================*/


	public function getCategories()
	{
		$resultset = $this->categorieDAO->selectAll();
		
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


	public function getCategoriesByLevel($parentCode, $level)
	{
		$levelCodes = $this->categorieDAO->selectCodesByLevel($parentCode, $level);
		
		if (!$this->filterDataErrors($levelCodes['response']))
		{
			if (!empty($levelCodes['response']['categorie']) && count($levelCodes['response']['categorie']) == 1)
			{ 
				$categorie = $levelCodes['response']['categorie'];
				$levelCodes['response']['categorie'] = array($categorie);
			}

			return $levelCodes;
		}

		return false;
	}
	

	public function getCategorie($codeCat)
	{
		$resultset = $this->categorieDAO->selectByCode($codeCat);

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


	public function getCategorieDetails($codeCat)
	{
		$catDetails = array();
		
		$catDetails['code_cat'] = "";
		$catDetails['nom_cat'] = "";
		$catDetails['descript_cat'] = "";
		$catDetails['ordre_cat'] = "";
		
		$resultset = $this->categorieDAO->selectByCode($codeCat);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			$catDetails['code_cat'] = $resultset['response']['categorie']->getCode();
			$catDetails['nom_cat'] = $resultset['response']['categorie']->getNom();
			$catDetails['descript_cat'] = $resultset['response']['categorie']->getDescription();
			$catDetails['ordre_cat'] = $this->getNumOrdre($catDetails['code_cat']);

			//var_dump($this->getNumOrdre($catDetails['code_cat']));

			$precos = $this->getCategoriePrecos($catDetails['code_cat']);

			if ($precos['response'] && count($precos['response']) > 0)
			{
				//var_dump($precos);
				for ($i = 0; $i < count($precos); $i++) {

					$catDetails['precos'][$i] = $precos['response']['cat_preco'][$i];
				}	
			}

			return $catDetails;
		}

		return false;
	}


	public function filterCategorieData(&$formData, $postData)
	{

		$dataCategorie = array();

		// Formatage du code catégorie
		if (!isset($formData['code_cat']) || empty($formData['code_cat']) || $formData['code_cat'] === null) 
		{
			$formData['code_cat'] = $this->validatePostData($postData['code_cat'], "code_cat", "integer", true, "Aucun code de catégorie n'a été saisi.", "Le code n'est pas correctement saisi.");
		}

		
		/*
		// Il faut vérifier également si le code est au bon format et si il n'existe pas déjà
		if (!empty($formData['code_cat']))
		{
			if (strlen($formData['code_cat']) % 2 != 0)
			{
				$this->registerError("form_valid", "Le code de la catégorie doit être un multiple de 2 (voir l'aide).");
			}
			
			
			$resultsetCode = $this->getCategorie($formData['code_cat']);

			//var_dump($resultsetCode);
			//var_dump($postData);
			//exit();

			if (!empty($resultsetCode['response']) && $resultsetCode !== false && $postData['mode'] != 'edit')
			{
				$this->registerError("form_valid", "Le code de la catégorie existe déjà.");
			}
			//else
			//{
			//	$dataCategorie['code_cat'] = $formData['code_cat'];
			//}
			
		}
		*/
		//$dataCategorie['code_cat'] = $formData['code_cat']; ??????



		// Test catégorie parente existante

		if (!empty($postData['parent_cat_cbox']) && $postData['parent_cat_cbox'] != 'select_cbox')
		{
			$formData['parent_code_cat'] = $this->validatePostData($postData['parent_cat_cbox'], "parent_cat_cbox", "string", false, "Aucune catégorie parente n'a été sélectionnée.", "La catégorie parente n'a été correctement sélectionnée.");
			$dataCategorie['parent_code_cat'] = $formData['parent_code_cat'];
		}
		else
		{
			$formData['parent_code_cat'] = null;
			$dataCategorie['parent_code_cat'] = null;
		}


	

		// Formatage du nom de la catégorie
		$formData['nom_cat'] = $this->validatePostData($_POST['nom_cat'], "nom_cat", "string", true, "Aucun nom de catégorie n'a été saisi", "Le nom n'est pas correctement saisi.");
		$dataCategorie['nom_cat'] = $formData['nom_cat'];
		
		// Formatage de l'intitule de la catégorie 
		$formData['descript_cat'] = $this->validatePostData($_POST['descript_cat'], "descript_cat", "string", false, "Aucune description n'a été saisi", "La description n'a été correctement saisi.");
		$dataCategorie['descript_cat'] = $formData['descript_cat'];
		
		// Récupèration du numero d'ordre de la catégorie
		$formData['ordre_cat'] = (int) $this->validatePostData($postData['ordre_cat'], "ordre_cat", "integer", false, "Aucun numéro d'ordre n'a été saisi.", "Le numéro d'ordre est incorrecte.");
		//$dataCategorie['ordre_cat'] = $formData['ordre_cat'];
		

		// Il faut vérifier également si le code est valable
		if (!empty($formData['code_cat']))
		{
			$error = false;

			if (strlen($formData['code_cat']) % 2 != 0)
			{
				$this->registerError("form_valid", "Le code de la catégorie doit être un multiple de 2 (voir l'aide).");
				$error = false;
			}
			// Si c'est une mise à jour le code est peut-être différent -> on le génère et on compare avec le code actuel
			else if ($dataCategorie['parent_code_cat'] !== null && $postData['mode'] == 'edit' && !empty($formData['ordre_cat']))
			{
				$newCode = $this->generateCode($formData['ordre_cat'], $dataCategorie['parent_code_cat']);

				if ($newCode != $formData['code_cat'])
				{
					// On supprime l'ancienne entrée dans la bdd (effet cascade avec les précos)
					$resultDelete = $this->deleteCategorie($formData['code_cat']);

					if ($resultDelete)
					{
						$formData['code_cat'] = $newCode;
						$formData['mode'] = 'new';
					}
					else
					{
						$this->registerError("form_valid", "La catégorie ne peut pas être mise à jour.");
						$error = false;
					}
				}
			}

			// On vérifie que le code n'existe pas déjà
			else
			{
				$resultsetCode = $this->getCategorie($formData['code_cat']);

				//var_dump($resultsetCode);
				//var_dump($postData);
				//exit();

				if (!empty($resultsetCode['response']) && $resultsetCode !== false)
				{
					$this->registerError("form_valid", "Le code de la catégorie existe déjà.");
					$error = false;
				}
			}


			if (!$error)
			{
				$dataCategorie['code_cat'] = $formData['code_cat'];
			}
		}


		/* Gestion des préconisations */
		if (Config::ALLOW_PRECONISATION) {
			
			if (isset($postData['preco_active']) && is_array($postData['preco_active']) && count($postData['preco_active']) > 0)
			{
				$dataCategorie['data_precos'] = array();
				$dataPrecos = array();
				$errorPreco = false;

				for ($i = 0; $i < count($postData['num_ordre_preco']); $i++)
				{
					if ($postData['preco_active'][$i] == 1)
					{
						$formData['precos'][$i]['preco_active'] = $postData['preco_active'][$i];
					
						$dataPrecos[$i]['code_cat'] = $formData['code_cat'];

						if (isset($postData['ref_preco'][$i]) && !empty($postData['ref_preco'][$i]))
						{
							$formData['precos'][$i]['ref_preco'] = $postData['ref_preco'][$i];
							$dataPrecos[$i]['ref_preco'] = $formData['precos'][$i]['ref_preco'];
							//$formData['precos'][$i]['mode'] = 'update';
						}
						else
						{
							//$formData['precos'][$i]['mode'] = 'insert';
						}

						if (isset($postData['num_ordre_preco'][$i]) && !empty($postData['ref_preco'][$i]))
						{
							$formData['precos'][$i]['num_ordre_preco'] = $postData['num_ordre_preco'][$i];
							$dataPrecos[$i]['num_ordre_preco'] = $formData['precos'][$i]['num_ordre_preco'];
						}
						else
						{
							$errorPreco = true;
						}


						if (isset($postData['preco_min'][$i]) && is_numeric($postData['preco_min'][$i]) && $postData['preco_min'][$i] >= 0)
						{
							$precoMin = $this->filterData($postData['preco_min'][$i], "integer");

							if ($precoMin != "empty" && $precoMin === true && $precoMin !== null)
							{
								$formData['precos'][$i]['preco_min'] = $precoMin;
								$dataPrecos[$i]['preco_min'] = $formData['precos'][$i]['preco_min'];
							}
							else
							{
								$errorPreco = true;
							}		
						}
						else
						{
							$errorPreco = true;
						}

						if (isset($postData['preco_max'][$i]) && is_numeric($postData['preco_max'][$i]) && $postData['preco_max'][$i] >= 0)
						{
							$precoMax = $this->filterData($postData['preco_max'][$i], "integer");

							if ($precoMax != "empty" && $precoMax === true && $precoMax !== null)
							{
								$formData['precos'][$i]['preco_max'] = $precoMax;
								$dataPrecos[$i]['preco_max'] = $formData['precos'][$i]['preco_max'];
							}
							else
							{
								$errorPreco = true;
							}		
						}
						else
						{
							$errorPreco = true;
						}


						if (isset($postData['choix_type_preco_cbox'][$i]) && $postData['choix_type_preco_cbox'][$i] != 'select_cbox' && $postData['choix_type_preco_cbox'][$i] != null)
						{
							$formData['precos'][$i]['ref_type_preco'] = $postData['choix_type_preco_cbox'][$i];
							$dataPrecos[$i]['ref_type_preco'] = $formData['precos'][$i]['ref_type_preco'];
						}
						else
						{
							$errorPreco = true;
						}
					}
				}

				if ($errorPreco)
				{
					$this->registerError("form_valid", "Une préconisation n'est pas correctement renseignée.");
				}

				$dataCategorie['data_precos'] = $dataPrecos;
			}
		}

		if (isset($dataCategorie['data_precos']) && empty($dataCategorie['data_precos']))
		{
			unset($dataCategorie['data_precos']);
		}

		var_dump($dataCategorie);
		//exit();

		return $dataCategorie;
	}


	/*=====  Fin gestion des catégories  ======*/





	/*=============================================
	=            Gestion code catégorie           =
	=============================================*/


	public function getParentCode($code = null) 
	{	
		$parentCode = null;

		if ($code !== null) {

			$parentCodeLength = strlen($code) - 2;

			if ($parentCodeLength >= 2) {

				$parentCode = substr($code, 0, $parentCodeLength);
			}
		}

		return $parentCode;
	}


	public function getLevel($code) {

		$level = null;

		$codeLength = strlen($code);

		if ($codeLength === 0) 
		{
			$level = 1;
		}
		else if ($codeLength % 2 === 0) 
		{
			$level = $codeLength / 2;
		}
		else
		{
			return false;
		}

		return $level;
	}



	private function getEcartCodes($code1, $code2, $ecartMax)
	{
		$ecart = $code2 - $code1;

		if ($ecart >= $ecartMax)
		{
			return ($ecartMax / 2);
		}
		else if ($ecart > 1)
		{
			return ($ecart / 2);
		}

		return false;
	}



	private function getNumOrdre($codeOrdre)
	{
		//var_dump($codeOrdre);

		$level = $this->getLevel($codeOrdre);
		$parentCode = $this->getParentCode($codeOrdre);

		$categories = $this->getCategoriesByLevel($parentCode, $level);

		if (!empty($categories['response']['categorie']) && count($categories['response']['categorie'] > 1))
		{
			$ordre = null;

			for ($i = 0; $i < count($categories['response']['categorie']); $i++) 
			{

				$code = $categories['response']['categorie'][$i]->getCode();

				if (($i - 1) < 0) 
				{
					$previous = 0;
					$next = $code;
				}
				else
				{
					$previous = $categories['response']['categorie'][($i - 1)]->getCode();
					$next = $code;
				}

				//var_dump('previous', $previous, 'next', $next);

				if (($codeOrdre > $previous && $codeOrdre <= $next) || $code == $codeOrdre)
				{
					//var_dump('$i', $i);
					$ordre = $i;
					break;
				}
				//}

				//var_dump($i, $ordre, $previous, $next, $code);
				//exit();
			}

			if ($ordre == 0)
			{
				return 0;
			}
			else if ($ordre != null)
			{
				return $ordre;
			}



			//var_dump('$ordre', $ordre);
			//exit();
		}
		

		return null;
	}



	public function generateCode($orderInput = null, $parentCode = null)
	{
		$level = null;
		$error = false;
		$newCode = null;

		// Déduction du niveau selon le code parent
		if ($parentCode === null) 
		{
			// Pas de parent -> catégorie de premier niveau
			$level = 1;
		}
		else
		{
			$parentlevel = $this->getLevel($parentCode);
			$level = ($parentlevel) ? $parentlevel + 1 : null;
		}

		if ($level === null)
		{
			$error = true;
		}

		// Requête de récupération de la liste des catégories de même niveau
		$codesResultset = $this->categorieDAO->selectCodesByLevel($parentCode, $level);
		
		// Filtrage du résultat
		if (!$this->filterDataErrors($codesResultset['response']))
		{
			if (!empty($codesResultset['response']['categorie']))
			{
				if (count($codesResultset['response']['categorie']) == 1)
				{
					$levelCodes = array($codesResultset['response']['categorie']);
				}
				else
				{
					$levelCodes = $codesResultset['response']['categorie'];
				}
			}
			else
			{
				$levelCodes = null;
				$orderInput = null;
			}
		}
		else
		{
			$error = true;
		}


		if (!$error)
		{
			// Selon l'ordre choisi, récupération des codes précédents et suivants
			$previousCode = null;
			$nextCode = null;

			if ($orderInput !== null)
			{
				if ($levelCodes !== null)
				{
					if ($orderInput > (count($levelCodes) - 1))
					{
						$previousCode = $levelCodes[(count($levelCodes) - 1)]->getCode();
					}
					else
					{
						for ($i = 0; $i < count($levelCodes); $i++) 
						{ 
							if ($i >= $orderInput)
							{
								$nextCode = $levelCodes[$i]->getCode();
								$previousCode = ($i > 0) ? $levelCodes[($i -  1)]->getCode() : null;
								break;
							}
						}
					}
				}
			}
			else
			{
				if ($levelCodes !== null)
				{
					// S'il n'y a pas d'ordre, la catégorie vient se greffer à la fin des catégorie du niveau
					$previousCode = $levelCodes[(count($levelCodes) - 1)]->getCode();
				}
			}

			// Modifier les paramètres
			$newCode = $this->createNewCode($previousCode, $nextCode, $parentCode);

			if ($newCode && is_numeric($newCode) && strlen($newCode) >= 2)
			{
				return $newCode;
			}
			else
			{
				$error = true;
			}
		}


		if ($error)
		{
			// Erreur
			return false;
		}

		return null;
	}


	private function createNewCode($previousCode = null, $nextCode = null, $parentCodePrefix = null)
	{

		$ecartMax = 20;
		$ecart = 0;
		$levelCode = 0;
		$newCode = '';

		if ($previousCode === null && $nextCode === null)
		{
			// Aucun code dans ce niveau
			$levelCode = 10;
		}
		else if ($previousCode === null && $nextCode !== null)
		{
			// Le code devient le premier du niveau
			$previousCode = 0;
			$nextCode = substr($nextCode, strlen($parentCodePrefix));
		}
		else if ($previousCode !== null && $nextCode === null)
		{
			$previousCode = substr($previousCode, strlen($parentCodePrefix));
			// Le code devient le dernier du niveau
			$nextCode = 100;
		}
		else
		{
			$previousCode = substr($previousCode, strlen($parentCodePrefix));
			$nextCode = substr($nextCode, strlen($parentCodePrefix));
		}


		if ($levelCode === 0)
		{
			// Le code se situe entre le code précédent et le suivant
			$levelCode = (int) $this->getEcartCodes($previousCode, $nextCode, $ecartMax);

			if ($levelCode && $previousCode !== null) 
			{
				$levelCode = $previousCode + $levelCode; 
			}
		}
		else
		{
			// Décalage du code précédent ou suivant

		}

		if ($levelCode)
		{
			if ($parentCodePrefix !== null && strlen($parentCodePrefix) > 0)
			{
				$newCode .= $parentCodePrefix;
			}

			if ($levelCode < 10)
			{
				$levelCode = '0' . $levelCode;
			}
			$newCode .= $levelCode;

			return $newCode;
		}

		return false;
	}


	/*=====  Fin gestion du code catégorie  ======*/




	public function setCategorieProperties($previousMode, $dataCategorie, &$formData)
	{
		//var_dump('$previousMode', $previousMode);


		$formData['code_cat'] = null;

		if ($previousMode == "new" || (isset($formData['mode']) && $formData['mode'] == 'new'))
		{
			// Insertion de la catégorie dans la bdd
			$resultsetCategorie = $this->setCategorie("insert", $dataCategorie);

			//var_dump($resultsetCategorie);
			//exit();

			$this->categorieDAO->selectByCode($dataCategorie['code_cat']);
			// Traitement des erreurs de la requête
			if (isset($resultsetCategorie['response']['categorie']) && $dataCategorie['code_cat'])
			{

				//$dataCategorie['code_cat'] = 
				$formData['code_cat'] = $dataCategorie['code_cat'];
				//$dataCategorie['code_cat'] = $formData['code_cat'];
				$this->registerSuccess("La catégorie a été ajoutée.");
			}
			else 
			{
				$this->registerError("form_valid", "L'enregistrement de la catégorie a échouée.");
			}
		}
		else if ($previousMode == "edit" || $previousMode == "save")
		{
			if (isset($dataCategorie['code_cat']) && !empty($dataCategorie['code_cat']))
			{
				$formData['code_cat'] = $dataCategorie['code_cat'];

				// Mise à jour de la catégorie
				$resultsetCategorie = $this->setCategorie("update", $dataCategorie);

				//var_dump($dataCategorie);
				//exit();

				// Traitement des erreurs de la requête
				if ($resultsetCategorie['response'])
				{
					$this->registerSuccess("La catégorie a été mise à jour.");
				}
				else
				{
					$this->registerError("form_valid", "La mise à jour de la catégorie a échoué.");
				}
			}
		}
		else
		{
			header("Location: ".SERVER_URL."erreur/page404");
			exit();
		}


		if (isset($dataCategorie['data_precos']) && is_array($dataCategorie['data_precos']) && count($dataCategorie['data_precos']) > 0)
		{

			//$refsPrecos = array();
			$j = 0;

			if ($formData['code_cat'] != null)
			{
				// On récupère les preconisations qui correspondent à la catégorie
				$existingPrecos = $this->getPreconisations();
				$precoMode = 'insert';

				// On récupère également le mode initial de la catégorie
				if ($formData['code_cat'] == "update")
				{
					$modeCat = "update";
				}
				else
				{
					$modeCat = "insert";
				}

				// Boucle sur chaque préconisation saisies
				
				for ($i = 0; $i < count($dataCategorie['data_precos']); $i++)
				{
					$preco = $dataCategorie['data_precos'][$i];

					if (!empty($existingPrecos))
					{
						for ($k = 0; $k < count($existingPrecos); $k++)
						{
							if (isset($existingPrecos[$k]['id_preco']) && !empty($existingPrecos[$k]['id_preco']) && $existingPrecos[$i]['id_preco'] == $preco['ref_preco'])
							{
								$precoMode = 'update';
								$refPreco = $existingPrecos[$k]['id_preco'];
								break;
							}
						}
					}
					
					if ($precoMode == 'update')
					{
						$resultsetPreco = $this->updatePreconisation($preco);
					}
					else
					{
						$resultsetPreco = $this->insertPreconisation($preco);
					}

					$refPrecoDef = null;
					
					if ($resultsetPreco['response'])
					{
						if ($precoMode == 'insert' && isset($resultsetPreco['response']['preconisation']['last_insert_id']))
						{
							$refPrecoDef = $resultsetPreco['response']['preconisation']['last_insert_id'];
						}
						else if ($precoMode == 'update' && isset($resultsetPreco['response']['preconisation']['row_count'])  && $resultsetPreco['response']['preconisation']['row_count'] > 0)
						{
							$refPrecoDef = $refPreco;
						}

						$resultsetCatPreco = $this->setCategoriePrecos($modeCat, $precoMode, $formData['code_cat'], $refPrecoDef);

						if (!$resultsetCatPreco)
						{
							$this->registerError("form_valid", "L'enregistrement d'une' préconisation a échouée.");
						}
					}
					else 
					{
						$this->registerError("form_valid", "L'enregistrement d'une préconisation a échouée.");
					}
				}
				
			}
			else
			{
				// erreur catégorie
			}
			
		}
	}



	public function setCategorie($modeCategorie, $dataCategorie)
	{

		if (!empty($dataCategorie) && is_array($dataCategorie))
		{
			if (!empty($dataCategorie['code_cat']) && !empty($dataCategorie['nom_cat']))
			{
				unset($dataCategorie['parent_code_cat']);
				unset($dataCategorie['ordre_cat']);

				if ($modeCategorie == "insert")
				{
					$resultset = $this->categorieDAO->insert($dataCategorie);
					
					// Traitement des erreurs de la requête
					if (!$this->filterDataErrors($resultset['response']))
					{
						return $resultset;
					}
					else 
					{
						$this->registerError("form_request", "La catégorie n'a pu être insérée.");
					}
					
				}
				else if ($modeCategorie == "update")
				{ 
					$resultset = $this->categorieDAO->update($dataCategorie);
					//var_dump($resultset);
					//exit();

					// Traitement des erreurs de la requête
					if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['categorie']['row_count']) && !empty($resultset['response']['categorie']['row_count']))
					{
						return $resultset;
					} 
					else 
					{
						$this->registerError("form_request", "La catégorie n'a pu être mise à jour.");
					}
				}
			}
			else 
			{
				$this->registerError("form_request", "Le code ou le nom de la catégorie sont manquants.");
			}
		}
		else 
		{
			$this->registerError("form_request", "Insertion de la catégorie non autorisée");
		}
			
		return false;
	}



	public function deleteCategorie($codeCat)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->categorieDAO->selectByCode($codeCat);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->categorieDAO->delete($codeCat);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "La catégorie n'a pas pu être supprimée.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Cette catégorie n'existe pas."); 
		}

		return false;
	}


	


	
	/*=============================================================
	=           Gestion des liaisons question-catégories          =
	=============================================================*/
	

	public function getQuestionCategorie($refQuestion)
	{
		$resultset = $this->questionCatDAO->selectByRefQuestion($refQuestion);
		
		// Traitement des erreurs de la requête
		$this->filterDataErrors($resultset['response']);
		
		return $resultset;
	}
	
	
	
	public function setQuestionCategorie($modeCategorie, $refQuestion, $codeCat)
	{
		if (!empty($refQuestion) && !empty($codeCat))
		{
			if ($modeCategorie == "insert")
			{
				$resultset = $this->questionCatDAO->insert(array('ref_question' => $refQuestion, 'ref_cat' => $codeCat));
				
				// Traitement des erreurs de la requête
				if (!$this->filterDataErrors($resultset['response']))
				{
					return $resultset;
				}
				else 
				{
					$this->registerError("form_request", "La catégorie liée à la question n'a pas pu être insérée.");
				}
			}
			else if ($modeCategorie == "update")
			{ 
				$resultset = $this->questionCatDAO->update(array('ref_question' => $refQuestion, 'ref_cat' => $codeCat));

				// Traitement des erreurs de la requête
				if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question_cat']['row_count']))
				{
					return $resultset;
				} 
				else 
				{
					$this->registerError("form_request", "La catégorie liée à la question n'a pu être mise à jour.");
				}
			}
		}
		else 
		{
			$this->registerError("form_request", "Le code categorie ou la reférence de la question sont manquants.");
		}

		return false;
	}
	
	
	
	
	public function deleteQuestionCategorie($refQuestion)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->questionCatDAO->selectByRefQuestion($refQuestion);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->questionCatDAO->delete($refQuestion);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "La catégorie n'a pas pu être supprimée.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Cette catégorie n'existe pas."); 
		}

		return false;
	}
	

	/*=====  Gestion des liaisons question-catégories  ======*/
	




	/*=============================================================
	=            Gestion des liaisons preco-catégories           =
	=============================================================*/
	

	/* ok */
	public function getCategoriePrecos($refCodeCat)
	{
		$resultset = $this->categoriePrecoDAO->selectByRefCodeCat($refCodeCat);
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['cat_preco']) && count($resultset['response']['cat_preco']) == 1)
			{ 
				$catPreco = $resultset['response']['cat_preco'];
				$resultset['response']['cat_preco'] = array($catPreco);
			}

			return $resultset;
		}
		
		return false;
	}
	

	/* Ok */
	public function setCategoriePrecos($modeCat, $modePreco, $refCodeCat, $refPreco, $oldRefPreco = null)
	{
		if (!empty($refCodeCat) && !empty($refPreco))
		{
			if ($mode == "insert")
			{
				$resultset = $this->categoriePrecoDAO->insert(array('ref_code_cat' => $refCodeCat, 'ref_preco' => $refPreco));
				
				// Traitement des erreurs de la requête
				if (!$this->filterDataErrors($resultset['response']))
				{
					return $resultset;
				}
				else 
				{
					$this->registerError("form_request", "La préconisation liée à la catégorie n'a pas pu être insérée.");
				}
			}
			else if ($mode == "update")
			{ 
				$resultset = $this->categoriePrecoDAO->update(array('ref_code_cat' => $refCodeCat, 'ref_preco' => $oldRefPreco, 'id_preco' => $refPreco));

				// Traitement des erreurs de la requête
				if (!$this->filterDataErrors($resultset['response']))
				{
					return $resultset;
				} 
				else 
				{
					$this->registerError("form_request", "La préconisation liée à la catégorie n'a pu être mise à jour.");
				}
			}
		}
		else 
		{
			$this->registerError("form_request", "Les références des préconisations ou le code catégorie sont manquants.");
		}

		return false;
	}
	

	/* Ok */
	public function deleteCategoriePrecos($refCodeCat)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->categoriePrecoDAO->selectByRefCodeCat($refCodeCat);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->categoriePrecoDAO->delete($refCodeCat);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "Les préconisations attachées à la catégorie n'ont pas pu être supprimées.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Les préconisations attachées à la catégorie n'existent pas."); 
		}

		return false;
	}


	/*=====  Fin gestion des liaisons preco-catégories  ======*/





	/*=============================================================
	=                  Gestion des préconisations                 =
	=============================================================*/
	
	/* Ok */
	public function getPreconisations($refCat)
	{
		$preconisations = array();
		
		$resultsetPreconisations = $this->preconisationDAO->selectByRefCodeCat($refCat);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultsetPreconisations['response']) && !empty($resultsetPreconisations['response']['preconisation']))
		{
			if (!empty($resultsetPreconisations['response']['preconisation']) && count($resultsetPreconisations['response']['preconisation']) == 1)
			{ 
				$preconisation = $resultsetPreconisations['response']['preconisation'];
				$resultsetPreconisations['response']['preconisation'] = array($preconisation);
			}
			
			$i = 0;
			foreach($resultsetPreconisations['response']['preconisation'] as $preco)
			{
				$preconisations[$i] = array();
				$preconisations[$i]['id_preco'] = $preco->getId();
				$preconisations[$i]['ref_type'] = $preco->getRefType();
				$preconisations[$i]['nom_preco'] = $preco->getNom();
				$preconisations[$i]['descript_preco'] = $preco->getDescription();
				$preconisations[$i]['taux_min'] = $preco->getTauxMin();
				$preconisations[$i]['taux_max'] = $preco->getTauxMax();
				$preconisations[$i]['num_ordre'] = $preco->getNumOrdre();

				$i++;
			}
		}
		
		return $preconisations;
	}

	public function getCategoriePreco($refPreco)
	{

	}
	/*
	public function getPreconisationsList()
	{
		$resultset = $this->preconisationDAO->selectAll();
		
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
	
	public function getPreconisation($codeCat)
	{
		$resultset = $this->preconisationDAO->selectByCodeCat();
		
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
	*/

	/* Ok */
	public function insertPreconisation($dataPreco)
	{
		if (!empty($dataPreco) && is_array($dataPreco))
		{
			// Insertion du type dans la bdd
			$resultset = $this->preconisationDAO->insert($dataPreco);
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['preconisation']['last_insert_id']))
			{
				return $resultset;
			} 
			else 
			{
				$this->registerError("form_request", "La préconisation n'a pas pu être insérée.");
			}
		}

		return false;
	}

	/* Ok */
	public function updatePreconisation($dataPreco)
	{
		if (!empty($dataPreco) && is_array($dataPreco))
		{
			// Insertion du type dans la bdd
			$resultset = $this->preconisationDAO->update($dataPreco);
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['preconisation']['row_count']))
			{
				return $resultset;
			} 
			else 
			{
				$this->registerError("form_request", "La préconisation n'a pas pu être mise à jour.");
			}
		}

		return false;
	}
	

	/* Ok */
	public function deletePreconisation($refPreco)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->preconisationDAO->selectById($refPreco);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->preconisationDAO->delete($refPreco);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "La préconisation n'a pas pu être supprimée.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Cette préconisation n'existe pas."); 
		}

		return false;
	}


	/*=====  Fin gestion des préconisations  ======*/





	/*=============================================================
	=              Gestion des types de préconisation             =
	=============================================================*/


	/* Ok */
	public function getTypePrecoList()
	{
		$resultset = $this->typePrecoDAO->selectAll();
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['type_preco']) && count($resultset['response']['type_preco']) == 1)
			{ 
				$type = $resultset['response']['type_preco'];
				$resultset['response']['type_preco'] = array($type);
			}

			return $resultset;
		}
		
		return false;
	}

	/* Ok */
	public function getTypeDetails($idType)
	{
		$parcoursDetails = array();
		
		$parcoursDetails['id_type'] = '';
		$parcoursDetails['nom_type'] = '';
		$parcoursDetails['desc_type'] = '';

		$resultset = $this->typePrecoDAO->selectById($idType);

		if (!$this->filterDataErrors($resultset['response']))
		{
			$parcoursDetails['id_type'] = $resultset['response']['type_preco']->getId();
			$parcoursDetails['nom_type'] = $resultset['response']['type_preco']->getNom();
			$parcoursDetails['desc_type'] = $resultset['response']['type_preco']->getDescription();

			return $typeDetails;
		}

		return false;
	}

	/* Ok */
	public function insertTypePreco($nomType, $description = null)
	{
		if (!empty($nomType) && $nomType !== null)
		{
			// Insertion du type dans la bdd
			$resultset = $this->typePrecoDAO->insert(array('nom_type' => $nomType, 'descript_type' => $description));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['type_preco']['last_insert_id']))
			{
				return $resultset;
			} 
			else 
			{
				$this->registerError("form_request", "Le type de la préconisation n'a pu être inséré.");
			}
		}

		return false;
	}

	/* Ok */
	public function updateTypePreco($refType, $nomType, $description = null)
	{
		if (!empty($nomType) && $nomType !== null)
		{
			// Insertion du type dans la bdd
			$resultset = $this->typePrecoDAO->update(array('ref_type_preco' => $refType, 'nom_type' => $nomType, 'descript_type' => $description));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['type_preco']['row_count']))
			{
				return $resultset;
			} 
			else 
			{
				$this->registerError("form_request", "Le type de la préconisation n'a pu être mis à jour.");
			}
		}

		return false;
	}
	
	/* Ok */
	public function deleteTypePreco($refType)
	{
		// On commence par sélectionner les réponses associèes à la question
		$resultsetSelect = $this->typePrecoDAO->selectById($refType);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->typePrecoDAO->delete($refType);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "Le type de préconisation n'a pas pu être supprimée.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Ce type de préconisation n'existe pas."); 
		}

		return false;
	}


	/*=====  Fin gestion des types de préconisations  ======*/
	

}


?>
