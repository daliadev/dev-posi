<?php



// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');


class ServicesAdminCategorie extends Main
{
	
	private $categorieDAO = null;
	private $questionCatDAO = null;
	
	
	
	public function __construct() 
	{

		$this->controllerName = "adminCategorie";

		$this->categorieDAO = new CategorieDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
	}

	
	
	
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
		$catDetails['type_lien_cat'] = "";

		
		$resultset = $this->categorieDAO->selectByCode($codeCat);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			$catDetails['code_cat'] = $resultset['response']['categorie']->getCode();
			$catDetails['nom_cat'] = $resultset['response']['categorie']->getNom();
			$catDetails['descript_cat'] = $resultset['response']['categorie']->getDescription();
			$catDetails['type_lien_cat'] = $resultset['response']['categorie']->getTypeLien();
		}

		return $catDetails;
	}




	public function filterCategorieData(&$formData, $postData)
	{

		$dataCategorie = array();

		// Formatage du code catégorie
		if (!isset($formData['code_cat']) || empty($formData['code_cat']) || $formData['code_cat'] === null) 
		{
			$formData['code_cat'] = $this->validatePostData($postData['code_cat'], "code_cat", "integer", true, "Aucun code de catégorie n'a été saisi.", "Le code n'est pas correctement saisi.");
		}

		$dataCategorie['code_cat'] = $formData['code_cat'];

		
		// Il faut vérifier si le code est au bon format et si il n'existe pas déjà
		if (!empty($formData['code_cat']))
		{
			if (strlen($formData['code_cat']) % 2 != 0)
			{
				$this->registerError("form_valid", "Le code de la catégorie doit être un multiple de 2 (voir schéma explicatif).");
			}
			
			$resultsetCode = $this->getCategorie($formData['code_cat']);

			if (!empty($resultsetCode['response']) && $resultsetCode !== false)
			{
				$this->registerError("form_valid", "Le code de la catégorie existe déjà.");
			}
		}


		// Formatage du nom de la catégorie
		$formData['nom_cat'] = $this->validatePostData($_POST['nom_cat'], "nom_cat", "string", true, "Aucun nom de catégorie n'a été saisi", "Le nom n'est pas correctement saisi.");
		$dataCategorie['nom_cat'] = $formData['nom_cat'];
		
		// Formatage de l'intitule de la catégorie 
		$formData['descript_cat'] = $this->validatePostData($_POST['descript_cat'], "descript_cat", "string", false, "Aucune description n'a été saisi", "La description n'a été correctement saisi.");
		$dataCategorie['descript_cat'] = $formData['descript_cat'];
		
		// Formatage du code catégorie
		if (!isset($formData['type_lien_cat']) || empty($formData['type_lien_cat']) || $formData['type_lien_cat'] === null) 
		{
			$formData['type_lien_cat'] = $this->validatePostData($postData['type_lien_cat'], "type_lien_cat", "integer", true, "Aucun type d'héritage des scores n'a été sélectionné.", "Le type d'héritage des scores n'est pas correctement saisi.");
		}

		$dataCategorie['type_lien_cat'] = $formData['type_lien_cat'];

		// Formatage du type de lien de la catégorie
		/*
		if (isset($_POST['type_lien_cat']))
		{
			$formData['type_lien_cat'] = "dynamic";
			$dataCategorie['type_lien_cat'] = "dynamic";
		}
		else 
		{
			$formData['type_lien_cat'] = "static";
			$dataCategorie['type_lien_cat'] = "static";
		}
		*/

		return $dataCategorie;
	}





	/* Gestion du code catégorie */

	public function getParentCode($code = null) 
	{	
		$parentCode = null;

		if ($code !== null) {

			$parentCodeLength = strlen($code) - 2;

			if ($parentCodeLength > 2) {

				$parentCode = substr($code, 0, $parentCodeLength);

				return $parentCode;
			}
		}

		return false;
	}


	private function generateCode($parent, $level, $order) {

		$key = null;



		return $key;
	}



	public function generateCategorieCode($code = null, $parentCode = null, $order = null) 
	{
		$generatedCode = null;
		$previousCode = null;
		$nextCode = null;
		$level = 0;

		// Mode edit
		if ($code !== null) {

			$level = strlen($code / 2);
			$currentCode = $this->categorieDAO->selectByCode($parentCode, $level);

			if ($parentCode !== null)
			{
				$parentLevel = strlen($code / 2);
				$level = strlen($code / 2) + 1;
				$nextCode = $this->categorieDAO->selectByCode($parentCode, $level);
				//$code = $this->servicesCategorie->generateCategorieCode($code, $parentCode);
			}
			else
			{
				$level = 1;
				$parentCode = $this->getParentCode($code);
				//$code = $this->servicesCategorie->generateCategorieCode($code);
			}
		}

		// Mode new
		else
		{
			if ($parentCode !== null) 
			{
				$level = strlen($parentCode / 2) + 1;

				$resultset = $this->categorieDAO->findCategorieCode($parentCode, $level, $order);

				//var_dump($resultset);
				//exit();

				if (!$this->filterDataErrors($resultset['response']))
				{
					if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
					{ 
						$categorie = $resultset['response']['categorie'];
						$resultset['response']['categorie'] = array($categorie);
					}
					else if (!isset($resultset['response']['categorie']) || empty($resultset['response']['categorie'])) 
					{
						$code = $parentCode . '10';
						return $code;
					}

					$codes = $resultset['response']['categorie'];

					//echo 'mode new - parentcode = '.$parentCode.' - child codes = ';
					//var_dump($codes);

					if ($order === null) 
					{
						$lastCodeIndex = count($codes) - 1;
						$code = $codes[$lastCodeIndex]->getCode();
						$previousCode = ($lastCodeIndex - 1) >= 0 ? $codes[($lastCodeIndex - 1)]->getCode() : -1;

						$last_num = substr($code, -2, 2);
						$last_num_previous = strlen($previousCode) >= 2 ? substr($previousCode, -2, 2) : -1;
						$increment = 10;

						if ($last_num_previous >= 0 && $last_num >= 50) 
						{
							$increment = $last_num - $last_num_previous;
						}


						if (($last_num + $increment) >= (100 - $increment) || floor($increment / 2) <= 0)
						{
							$this->registerError("form_valid", "Le nombre de catégories a atteint son maximum dans ce niveau hiérarchique.");
						}
						else
						{
							$increment = $increment >= 10 ? 10 : floor($increment / 2);
							$code += $increment;
						}
						
					}
					else 
					{
						/**
						*
						*	TODO:
						*	- les ordres correspondent à chaque code (code1 = ordre1, code2 = ordre2, ..., $codemax = ordre_n). 
						*	- L'ajout/insertion d'une catégorie entre deux autres incrémente les codes d'une position en augmentant l'ordre de chaque codes
						* 	- La suppression d'une catégorie réduit l'ordre d'un cran vers le bas
						*	- Les ordres doivent s'afficher selon l'ordre et le nombre de code + 1
						* 	- ATTENTION : Le code doit rester identique pour une même catégorie 
						**/
						
						$nextCode = $codes[$order] <= count($codes) - 1 ? $codes[count($codes) - 1] : null;
						//$code = $codes[$lastCodeIndex]->getCode();
						$previousCode = ($order - 2) >= 0 ? $codes[($order - 2)] : null;

						$increment = 10;


						if ($previousCode === null || $nextCode === null) 
						{
							if ($previousCode === null)
							{
								$index = 0;
							}
						}


						$range = $nextcode - $previousCode;

						$increment = $range >= 10 ? 10 : $range / 2;




						/*
						$ordres = array();

						for ($i = 0; $i < count($codes); $i++) 
						{ 
							if ($i == $order) 
							{
								++
							}

							if ($codes[$i] ) 
							{

							}

							$ordres[$i] = $i;
						}
						*/
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				$level = 1;
				$resultset = $this->categorieDAO->findCategorieCode(null, $level);

				if (!$this->filterDataErrors($resultset['response']))
				{
					if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
					{ 
						$categorie = $resultset['response']['categorie'];
						$resultset['response']['categorie'] = array($categorie);
					}

					$codes = $resultset['response']['categorie'];

					echo 'mode new - level one codes = ';
					//var_dump($codes);
					//exit();
					//$codes = $this->generateKey(null, 1);
					//$code = $this->servicesCategorie->generateCategorieCode();

					if ($order === null) 
					{

						$lastCodeIndex = count($codes) - 1;
						$code = $codes[$lastCodeIndex]->getCode();
						$previousCode = ($lastCodeIndex - 1) >= 0 ? $codes[($lastCodeIndex - 1)]->getCode() : -1;

						$last_num = substr($code, -2, 2);
						$last_num_previous = strlen($previousCode) >= 2 ? substr($previousCode, -2, 2) : -1;
						$increment = 10;

						if ($last_num_previous >= 0 && $last_num >= 75) 
						{
							$increment = $last_num - $last_num_previous;
						}


						if ($last_num + $increment >= 100 - $increment || floor($increment / 2) <= 0)
						{
							$this->registerError("form_valid", "Le nombre de catégories a atteint son maximum dans ce niveau hiérarchique.");
						}
						else
						{
							$increment = $increment >= 10 ? 10 : floor($increment / 2);
							$code += $increment;
						}

					}
					else 
					{

						/*
							TODO:
							- $ordre : compris entre 0 et 50
							SI $ordre vaut 0 ALORS
								$code compris entre 0 et premier code
							SINONSI  $ordre > longueur tab $codes ALORS
								$code compris entre dernier $code et dernier $code + $incrément
							SINON
								$code compris dernier $code + $incrément
							FINSI
						*/

						$code = 10;

						if ($order > count($codes)) 
						{
							$order = count($codes) + 1;
							$code = count($codes);
						}
						else
						{	

						}

						$ordres = array();

						for ($i = 0; $i < count($codes); $i++) 
						{
							$ordres[$i] = $i;

							if ($i == $order) 
							{
								$orderCode = $codes[$i];
								$previous = $codes[($i - 1)];
								$next = $codes[($i + 1)];
								//++
							}

							if ($codes[$i]) 
							{

							}

						}
					}
				}
				else
				{
					return false;
				}
			}
		}

		//var_dump($code);

		//exit();

		return $code;
	}



	public function createCodesArray($code = null, $parentCode = null, $order = null) {

		$selectedCode = null;
		$levelCodesArray = array();
		$allCodesArray = array();
		

		// Récupération du code parent (si existant)

		if ($code !== null)
		{	
			// Stockage du code sélectionné
			$selectedCode = $code;

			if ($parentCode === null)
			{
				$parentCode = $this->getParentCode($code);
			}

			//$parentCode = $this->getParentCode($code);
		}
		else
		{
			//erreur
			$parentCode = null;
		}
		

		// Détermination du niveau hiérarchique dans lequel doit être inséré l'element
		if ($parentCode !== false && $parentCode !== null)
		{
			$level = $this->getLevel($parentCode);
		}
		else 
		{
			$level = 0;
		}


		// Gestion de l'ordre et des codes de même niveau

		// Création d'un tableau comportant la liste des codes du niveau
		$levelCodesArray = $this->createLevelCodes($parentCode, $level, $order);

		// Création d'un nouveau tableau comportant le nouveau code - l'ancien code - et l'ordre correspondant
		$allCodesArray = $this->generateCodes($levelCodesArray, $selectedCode);

		return $allCodesArray;
	}

	

 




	public function setCategorieProperties($previousMode, $dataCategorie, &$formData)
	{

		if ($previousMode == "new")
		{
			
			// Insertion de la catégorie dans la bdd
			$resultsetCategorie = $this->setCategorie("insert", $dataCategorie);

			//var_dump($resultsetCategorie);
			//exit();

			// Traitement des erreurs de la requête
			if ($resultsetCategorie['response'])
			{
				$this->registerSuccess("La catégorie a été enregistrée.");
			}
			else 
			{
				$this->registerError("form_valid", "L'enregistrement de la catégorie a échouée.");
			}
		}
		else if ($previousMode == "edit"  || $previousMode == "save")
		{
			if (isset($dataCategorie['code_cat']) && !empty($dataCategorie['code_cat']))
			{
				$formData['code_cat'] = $dataCategorie['code_cat'];

				// Mise à jour de la catégorie
				$resultsetCategorie = $this->setCategorie("update", $dataCategorie);

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
	}



	public function setCategorie($modeCategorie, $dataCategorie)
	{

		if (!empty($dataCategorie) && is_array($dataCategorie))
		{
			if (!empty($dataCategorie['code_cat']) && !empty($dataCategorie['nom_cat']))
			{
				if ($modeCategorie == "insert")
				{
					$resultset = $this->categorieDAO->insert($dataCategorie);
					//var_dump($resultset);

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
	
}


?>
