<?php



// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/preconisation_dao.php');
require_once(ROOT.'models/dao/type_preco_dao.php');


class ServicesAdminCategorie extends Main
{
	
	private $categorieDAO = null;
	private $questionCatDAO = null;
	private $preconisationDAO = null;
	private $typePrecoDAO = null;
	
	public function __construct() 
	{

		$this->controllerName = "adminCategorie";

		$this->categorieDAO = new CategorieDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
		$this->preconisationDAO = new PreconisationDAO();
		$this->typePrecoDAO = new typePrecoDAO();
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

	/*
	public function getCategoriesHierarchy()
	{

		
	}
	*/


	public function getCategoriesByLevel($code, $level)
	{
		$levelCodes = $this->categorieDAO->findCodesByLevel($code, $level);

		return $levelCodes['response'];
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
		//$catDetails['type_lien_cat'] = "";

		
		$resultset = $this->categorieDAO->selectByCode($codeCat);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']))
		{
			$catDetails['code_cat'] = $resultset['response']['categorie']->getCode();
			$catDetails['nom_cat'] = $resultset['response']['categorie']->getNom();
			$catDetails['descript_cat'] = $resultset['response']['categorie']->getDescription();

			$precos = $this->getCategoriePrecos($catDetails['code_cat']);

            if ($precos)
            {
                $catDetails['precos'] = $precos;
            }

			return $catDetails;
		}

		return false;
	}



	public function getCategoriePrecos($refCat)
	{
		$preconisations = array();
		
		$resultsetPreconisations = $this->preconisationDAO->selectByCategorie($refCat);
		
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



	
	public function getPreconisations($codeCat)
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
			else
			{
				$dataCategorie['code_cat'] = $formData['code_cat'];
			}
		}


		// Test catégorie parente existante

		if (!empty($postData['parent_cat_cbox']) && $postData['parent_cat_cbox'] != 'aucun')
		{
			$formData['parent_code_cat'] = $this->validatePostData($postData['parent_cat_cbox'], "parent_cat_cbox", "integer", false, "Aucune catégorie parent n'a été sélectionnée.", "La catégorie parente n'a étécorrectement sélectionnée.");
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
		$formData['ordre_cat'] = $this->validatePostData($postData['ordre_cat'], "ordre_cat", "integer", true, "Aucun numéro d'ordre n'a été saisi.", "Le numéro d'ordre est incorrecte.");
		$dataCategorie['ordre_cat'] = $formData['ordre_cat'];
		

		/* Gestion des préconisations */

		$dataCategorie['data_precos'] = array();

		if (isset($postData['num_ordre_preco']) && is_array($postData['num_ordre_preco']) && count($postData['num_ordre_preco']) > 0)
		{
			$dataPrecos = array();
			$errorPreco = false;

			for ($i = 0; $i < count($postData['num_ordre_preco']); $i++)
			{
				$dataPrecos[$i]['code_cat'] = $formData['code_cat'];

				if (isset($postData['ref_preco'][$i]) && !empty($postData['ref_preco'][$i]))
				{
					$formData['precos'][$i]['ref_preco'] = $postData['ref_preco'][$i];
					$dataPrecos[$i]['ref_preco'] = $formData['precos'][$i]['ref_preco'];
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


				if (isset($postData['preco_min'][$i]))
				{
					if ($postData['preco_min'][$i] != 0) 
					{
						$precoMin = $this->filterData($postData['preco_min'][$i], "integer");
					}
					else
					{
						$precoMin = 0;
					}


					if ($precoMin != "empty" && $precoMin !== false && $precoMin != 0)
					{
						$formData['precos'][$i]['preco_min'] = $precoMin;
					}
					else 
					{
						$formData['precos'][$i]['preco_min'] = 0;
					}
					$dataPrecos[$i]['preco_min'] = $formData['precos'][$i]['preco_min'];
				}
				else
				{
					$errorPreco = true;
				}

				if (isset($postData['preco_max'][$i]))
				{
					if ($postData['preco_max'][$i] != 0) 
					{
						$precoMax = $this->filterData($postData['preco_max'][$i], "integer");
					}
					else
					{
						$precoMax = 0;
					}

					if ($precoMax != "empty" && $precoMax !== false)
					{
						$formData['precos'][$i]['preco_max'] = $precoMax;
					}
					else 
					{
						$errorPreco = true;
						$formData['precos'][$i]['preco_max'] = 0;
					}
					$dataPrecos[$i]['preco_max'] = $formData['precos'][$i]['preco_max'];
				}
				else
				{
					$errorPreco = true;
				}


				if (isset($postData['type_preco_cbox'][$i]) && $postData['type_preco_cbox'][$i] != 'select_cbox' && $postData['type_preco_cbox'][$i] != null)
				{
					$formData['precos'][$i]['ref_type_preco'] = $postData['type_preco_cbox'][$i];
					$dataPrecos[$i]['ref_type_preco'] = $formData['precos'][$i]['ref_type_preco'];
				}
				else
				{
					$errorPreco = true;
				}
			}

			if ($errorPreco)
			{
				$this->registerError("form_valid", "Une préconisation n'est pas correctement renseignée.");
			}
			//else
			//{
				$dataCategorie['data_precos'] = $dataPrecos;
			//}
		}

		return $dataCategorie;
	}





	/*=============================================
	=            Gestion code catégorie           =
	=============================================*/


	public function getParentCode($code = null) 
	{	
		$parentCode = null;

		if ($code !== null) {

			$parentCodeLength = strlen($code) - 2;

			if ($parentCodeLength > 2) {

				$parentCode = substr($code, 0, $parentCodeLength);

				//return $parentCode;
			}
		}

		return $parentCode;
	}


	private function getLevel($code) {

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


	/**
	 * 
	 *	TODO:
	 * 	------
	 * 	Contexte : La saisie est correcte et le mode est établi 
	 * 
	 * 	1) Récupération du numéro d'ordre
	 * 
	 *	2) Récupération du code parent
	 * 		1a) Aucune catégorie parente -> level = 1
	 * 	3) Trouver level selon longueur du code parent
	 * 	3) Récupération de la liste des catégories correspondant au level
	 * 	4) Recherche des codes suivants et précédents selon le numéro d'ordres
	 * 
	 * 	4b) Création d'un nouveau code
	 * 		4b1) Le code existe déjà
	 * 		4b2) Décalage à effectuer sur la catégorie suivante
	 * 	4c) Mise à jour de la catégorie à décaler
	 *  
	 * 	4) Si mode = 'new' -> insert
	 * 	5) Si mode = 'edit -> update
	 * 		
	 */

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
			//$parentCode = $this->getParentCode($currentCode);
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
		if (!$this->filterDataErrors($codesResultset['response']) && !empty($codesResultset['response']['categorie']))
		{
			if (!empty($codesResultset['response']['categorie']) && count($codesResultset['response']['categorie']) == 1)
			{ 
				$levelCodes = $codesResultset['response']['categorie'];
				$codesResultset['response']['categorie'] = array($levelCodes);
			}
			else
			{
				$levelCodes = $codesResultset['response']['categorie'];
			}
		}
		else
		{
			$error = true;
		}

		//var_dump('error :', $error);
		//var_dump('level :', $level);

		if (!$error)
		{
			// Selon l'ordre choisi, récupération des codes précédents et suivants
			$previousCode = null;
			$nextCode = null;

			if ($orderInput !== null)
			{
				if ($orderInput > (count($levelCodes) - 1))
				{
					//$nextCode = $levelCodes[$i]->getCode();
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
			else
			{
				// S'il n'y a pas d'ordre, la catégorie vient se greffer à la fin des catégorie du niveau
				$previousCode = $levelCodes[(count($levelCodes) - 1)]->getCode();
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

		//var_dump('error :', $error);

		if ($error)
		{
			// Erreur
			return false;
		}

		return null;
	}


	private function createNewCode($previousCode = null, $nextCode = null, $parentCodePrefix = null)
	{
		//var_dump('previousCode :', $previousCode);
		//var_dump('nextCode :', $nextCode);

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

		//var_dump($previousCode, $nextCode);


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


	


	/*
	private function generateNewCodes($previousIndex, $nextIndex, $codes)
	{
		//$codePrefix = strlen($codes[0]) - 2;
		$newCode = null;
		$increment = 10;
		$previousCodeSuffix = null;
		$nextCodeSuffix = null;

		// Morceaux de code précédent le niveau de hiérarchie correspondant au code à insérer (le même pour tous les codes du niveau)
		$codePrefix = substr($codes[0], 0, strlen($codes[0]));


		if ($previousIndex !== null) 
		{
			$previousCodeSuffix = substr($codes[$previousIndex], 0, strlen($codes[$previousIndex]));
		}
		else
		{
			// Tout est décalé d'un cran à partir du début
			//$newCode = 0;
		}


		if ($nextCode !== null) 
		{
			// Ajout du code à la fin
			$nextCodeSuffix = substr($codes[$nextIndex], 0, strlen($codes[$nextIndex]));
		}
		else
		{
			// $nextCode = (int) $codes[$nextIndex]
		}

		$increment = (int) $nextCodeSuffix - (int) $previousCodeSuffix;

		if ($increment >= 15) 
		{
			$increment = 10;
		}
		else if ($increment >= 10)
		{
			$increment = 5;
		}
		else if ($increment <= 0) 
		{
			// error
		}

		$newCodeSuffix = (int) $codes[$previousIndex] + $increment;
		$newCode = $codePrefix . $newCodeSuffix; 

		var_dump($newCode);
		//exit();
		if ($newCode !== null)
		{
			for ($i = 0; $i < count($codes); $i++) 
			{ 
				$currenCode = (int) $codes[$i];


				if ($newCode > $codes[$i])
				{

				}
			}
		}
		


		return $newCode;
	}
	*/


	/*
	private function decayOldCodes($oldCodes, $index, $increment) {

	}
	*/

	/*
	private function sortCodesByOrder($oldCodes, $parentCode, $insertOrder = null)
	{
		*/
		/**
		 *
		 *	TODO:
		 *  - Création d'un nouveau tableau comportant l'ensemble des codes avec les numéros d'ordre correspondant
		 * 
		 * 	Pour chaque clé du tableau
		 *  	Trouver la clé précédent l'ordre
		 * 		Trouver la clé correspondant ou suivant l'ordre
		 * 	FinPour
		 * 
		 * 	Calculer l'écart entre les codes précédents et suivants
		 * 	Si écart est plus grand ou égal à 10
		 * 		Générer un code compris entre le code précédent et le suivant
		 * 	Sinon
		 * 		Calculer incrément idéal
		 * 		Décaler tous les codes suivants en ajoutant l'incrément
		 * 	FinSi
		 * 
		 * 	Faire tableau contenant les nouveau codes, les anciens codes et l'ordre correspondant (clé du tableau)
		 */

		/*
		var_dump('sort', $insertOrder);
		//var_dump($oldCodes);
		//var_dump($parentCode);
		
		$previousIndex = null;
		$nextIndex = null;
		$previousCode = null;
		$nextCode = null;
		//$newOrderedCodes = array();
		//$finalCodes = array();
		
		$count = 1;

		for ($i = 0; $i < count($oldCodes); $i++) 
		{
			$code = substr($oldCodes[$i], strlen($parentCode));

			if (strlen($code) == 2)
			{
				if (($insertOrder - 1) >= 0 && $count == ($insertOrder - 1)) 
				{
					$previousIndex = $i;
				}
				else if ($insertOrder == $count) 
				{
					$nextIndex = $i;
				}

				$count++;
			}
		}
		
		
		if ($previousIndex !== null)
		{
			$previousCode = $oldCodes[$previousIndex];
		}

		if ($nextIndex !== null)
		{
			$nextCode = $oldCodes[$nextIndex];
		}
		
		$decay = false;

		$increment = round(($nextCode - $previousCode) / 2);

		if ($increment < 2)
		{
			$decay = true;
		}

		var_dump("prevIndex = ", $previousIndex, "nextIndex = ", $nextIndex, "prevCode = ", $previousCode, "nextCode = ", $nextCode);

		if ($decay)
		{

		}
		else
		{
			$newCode = $previousCode + $increment;
			var_dump("newCode = ", $newCode);
		}

		//$newCode = $this->generateCode($previousIndex, $nextIndex, $oldCodes);
	}
	*/

	/*
	public function createCodes($currentCode = null, $parentCode = null, $orderInput = null) {

		$selectedCode = null;
		//$levelCodes = array();
		//$allCodesArray = array();
		$level = 1;
		

		// Récupération du code parent (si existant)

		if ($currentCode !== null)
		{	
			// Stockage du code sélectionné
			$selectedCode = $currentCode;

			if ($parentCode === null) 
			{
				$parentCode = $this->getParentCode($currentCode);
			}
		}

		// Détermination du niveau hiérarchique dans lequel doit être inséré l'élément
		if ($currentCode !== false && $currentCode !== null)
		{
			$level = $this->getLevel($currentCode);

			if ($level === null)
			{
				$this->registerError("form_valid", "Le code de la catégorie est érroné.");
			}
		}
		else 
		{
			if ($parentCode !== null) 
			{
				$level = $this->getLevel($parentCode) + 1;
			}
			else
			{
				$this->registerError("form_valid", "Le code parent de la catégorie est érroné.");
			}
		}

		//var_dump($parentCode, $level);


		// Gestion de l'ordre et des codes de même niveau
		if ($orderInput !== null) 
		{
			$order = $orderInput;
		}


		// Création d'un tableau comportant la liste des codes du niveau
		$levelCodes = array();
		$oldCodes = array();

		$levelCodes = $this->categorieDAO->findCodesByLevel($parentCode, $level); // $parentCode à la place de $currentCode

		//var_dump($currentCode, $parentCode, $level, $levelCodes);
		//exit();

		if (isset($levelCodes['response']['errors']) && count($levelCodes['response']['errors']) > 0)
		{
			$this->filterDataErrors($levelCodes['response']);
		}
		else if (!empty($levelCodes['response']['categorie']) && count($levelCodes['response']['categorie']) > 0)
		{
			if (count($levelCodes['response']['categorie']) == 1)
			{ 
				$categorie = $levelCodes['response']['categorie'];
				$levelCodes['response']['categorie'] = array($categorie);
			}

			foreach ($levelCodes['response']['categorie'] as $categorie)
			{


				//$oldCodes[] = $categorie->getCode();
			}
		}
		else
		{
			$this->registerError("form_valid", "La catégorie n'existe pas.");
		}

		var_dump($currentCode, $parentCode, $oldCodes, $level);
		
		
		//$this->sortCodesByOrder($oldCodes, $parentCode, $orderInput);

		exit();

		// Création d'un nouveau tableau comportant le nouveau code - l'ancien code - et l'ordre correspondant
		//$allCodesArray = $this->generateCodes($levelCodesArray, $selectedCode);

		return null; //$allCodesArray;
	}
	*/




	/*=====  End of gestion code catégorie  ======*/

	

 




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



	
	
	public function insertTypePreco($nomType)
	{
		if (!empty($nomType) && $nomType !== null)
		{
			// Insertion du type dans la bdd
			$resultset = $this->typePrecoDAO->insert(array('nom_type' => $nomType));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']))
			{
				return true;
			}
		}

		return false;
	}

	public function updateTypePreco($refType, $nomType)
	{
		if (!empty($nomType) && $nomType !== null)
		{
			// Insertion du type dans la bdd
			$resultset = $this->typePrecoDAO->update(array('ref_type_preco' => $refType, 'nom_type' => $nomType));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']))
			{
				return true;
			}
		}

		return false;
	}
	

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
