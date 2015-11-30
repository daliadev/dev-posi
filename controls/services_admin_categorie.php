<?php



// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/preconisation_dao.php');
require_once(ROOT.'models/dao/parcours_dao.php');


class ServicesAdminCategorie extends Main
{
	
	private $categorieDAO = null;
	private $questionCatDAO = null;
	private $preconisationDAO = null;
	private $parcoursDAO = null;
	
	public function __construct() 
	{

		$this->controllerName = "adminCategorie";

		$this->categorieDAO = new CategorieDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
		$this->preconisationDAO = new PreconisationDAO();
		$this->parcoursDAO = new ParcoursDAO();
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
			// $catDetails['type_lien_cat'] = $resultset['response']['categorie']->getTypeLien();

			return $catDetails;
		}

		return false;
	}

	/*
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
	*/

	public function getParcoursList()
	{
		$resultset = $this->parcoursDAO->selectAll();
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['parcours']) && count($resultset['response']['parcours']) == 1)
			{ 
				$parcours = $resultset['response']['parcours'];
				$resultset['response']['parcours'] = array($parcours);
			}

			return $resultset;
		}
		
		return false;
	}


	public function getParcoursDetails($idParcours)
	{
		$parcoursDetails = array();
		
		$parcoursDetails['id_parcours'] = '';
		$parcoursDetails['nom_parcours'] = '';
		$parcoursDetails['desc_parcours'] = '';

		$resultset = $this->parcoursDAO->selectById($idParcours);

		if (!$this->filterDataErrors($resultset['response']))
		{
			$parcoursDetails['id_parcours'] = $resultset['response']['parcours']->getId();
			$parcoursDetails['nom_parcours'] = $resultset['response']['parcours']->getNom();
			$parcoursDetails['desc_parcours'] = $resultset['response']['parcours']->getDescription();

			return $parcoursDetails;
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



		/* !!!! A vérifier !!! */

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


		// Test catégorie parente existante

        if (!empty($postData['parent_cat_cbox']) && $postData['parent_cat_cbox'] != 'aucun')
        {
        	$formData['parent_code_cat'] = $this->validatePostData($postData['parent_cat_cbox'], "parent_cat_cbox", "integer", false, "Aucune catégorie parent n'a été sélectionnée.", "La catégorie parente n'a étécorrectement sélectionnée.");
        	$dataCategorie['parent_code_cat'] = $formData['parent_code_cat'];
        }
        else
        {
        	$formData['parent_code_cat'] = 0;
            $dataQuestion['parent_code_cat'] = 0;
        }


		// Formatage du nom de la catégorie
		$formData['nom_cat'] = $this->validatePostData($_POST['nom_cat'], "nom_cat", "string", true, "Aucun nom de catégorie n'a été saisi", "Le nom n'est pas correctement saisi.");
		$dataCategorie['nom_cat'] = $formData['nom_cat'];
		
		// Formatage de l'intitule de la catégorie 
		$formData['descript_cat'] = $this->validatePostData($_POST['descript_cat'], "descript_cat", "string", false, "Aucune description n'a été saisi", "La description n'a été correctement saisi.");
		$dataCategorie['descript_cat'] = $formData['descript_cat'];
		
		// Récupèration du numero d'ordre de la catégorie
        $formData['ordre_cat'] = $this->validatePostData($postData['ordre_cat'], "ordre_cat", "integer", true, "Aucun numéro d'ordre n'a été saisi.", "Le numéro d'ordre est incorrecte.");
        $dataQuestion['ordre_cat'] = $formData['ordre_cat'];
		
		// Formatage du code catégorie
		/*
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

		return $level;
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
	 * 	3) Récupération de la liste des catégories
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

	public function generateCode($orderInput = null, $parentCode = null, $currentCode = null)
	{
		// Déduction du niveau selon le code parent
		if ($parentCode === null) 
		{
			// Pas de parent ->catégorie de premier niveau
			$level = 1;
			//$parentCode = $this->getParentCode($currentCode);
		}
		else
		{
			$level = getLevel($parentCode) + 1;
		}

		// Requête de récupération de la liste des catégories de même niveau
		$levelCategories = $this->categorieDAO->findCodesByLevel($parentCode, $level);

		// Selon l'ordre choisi, récupération des codes précédents et suivants
		$previousCode = null;
		$nextCode = null;

		if ($orderInput != null)
		{
			for ($i = 0; $i < count($levelCategories['response']); $i++) 
			{ 
				if ($i >= $orderInput)
				{
					$nextCode = $levelCategories['response'][$i]->getCode();
					$previousCode = $levelCategories['response'][($i -  1)]->getCode();
					break;
				}
			}
		}
		else
		{
			// S'il n'y a pas d'ordre, la catégorie vient se greffer à la fin des catégorie du niveau
			$previousCode = $levelCategories['response'][(count($levelCategories['response']) - 1)]->getCode();
		}


		$this->createNewCode($previousCode, $nextCode, $parentCode);
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
			
		}
		else if ($previousCode !== null && $nextCode === null)
		{
			// Le code devient le dernier du niveau
			$nextCode = 100;	
		}

		// Le code se situe entre le code précédent et le suivant
		$levelCode = $this->getEcartCode($previousCode, $nextCode, $ecartMax);


		if (!$levelCode || $levelCode == 0)
		{
			// Décalage du code précédent ou suivant

		}

		if ($parentCodePrefix !== null && strlen($parentCodePrefix) > 0)
		{
			$newCode .= $parentCodePrefix;
		}
		$newCode .= $levelCode;

		return $newCode;
	}


	private function getEcartCode($code1, $code2, $ecartMax)
	{
		$ecart = $code1 - $code2;

		if ($ecart => $ecartMax)
		{
			return ($ecartMax / 2);
		}
		else if ($ecart > 1)
		{
			return ($ecart / 2);
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
		/*
		else
		{
			if ($parentCode !== null) 
			{

			}
		}
		*/

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
			/*
			foreach ($levelCodes['response']['errors'] as $key => $value) {

				$this->registerError($levelCodes['response']['errors'][$key]['type'], $levelCodes['response']['errors'][$key]['message']);
			}
			*/
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
				/*
				$code = substr($categorie->getCode(), strlen($parentCode), strlen($categorie->getCode()));

				if (strlen($code) !== 0) 
				{
					$oldCodes[] = $code;
				}
				*/

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


	public function getCategorieOrder($code) 
	{

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
	
	
	public function insertParcours($nomParcours)
	{
		if (!empty($nomParcours) && $nomParcours !== null)
		{
			// Insertion de la parcours dans la bdd
			$resultset = $this->parcoursDAO->insert(array('nom_parcours' => $nomParcours));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']))
			{
				return true;
			}
		}

		return false;
	}

	public function updateParcours($refParcours, $nomParcours)
	{
		if (!empty($nomParcours) && $nomParcours !== null)
		{
			// Insertion de la parcours dans la bdd
			$resultset = $this->parcoursDAO->update(array('ref_parcours' => $refParcours, 'nom_parcours' => $nomParcours));
				
			// Traitement des erreurs de la requête
			if (!$this->filterDataErrors($resultset['response']))
			{
				return true;
			}
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
