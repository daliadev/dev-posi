<?php


require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');



class ServicesPosiResultats extends Main
{
	
	private $validAcquisDAO = null;
	private $questionCatDAO = null;
	
	
	public function __construct() 
	{
		$this->controllerName = "positionnementResults";

		$this->validAcquisDAO = new ValidAcquisDAO();
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



	public function getResultats($refSession)
	{

		$resultset = $this->resultatDAO->selectBySession($refSession);
		

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



	public function getCategorieByQuestion($refQuestion)
	{

		$resultset = $this->questionCatDAO->selectByRefQuestion($refQuestion);
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['question_cat']) && count($resultset['response']['question_cat']) == 1)
			{ 
				$questionCat = $resultset['response']['question_cat'];
				$resultset['response']['question_cat'] = array($questionCat);
			}

			return $resultset;
		}
		
		return false;
	}



	
	
	/*
	public function getValidDetails($refValidAcquis)
	{
		$validDetails = array();
		
		$validDetails['nom_acquis'] = "";
		$validDetails['descript_acquis'] = "";
		
		$resultsetValidAcquis = $this->validAcquisDAO->selectById($refValidAcquis);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultsetValidAcquis['response']))
		{
			$validDetails['nom_acquis'] = $resultsetValidAcquis['response']['valid_acquis']->getNom();
			$validDetails['descript_acquis'] = $resultsetValidAcquis['response']['valid_acquis']->getDescription();
		}

		return $validDetails;
	}
	*/



	public function filterValidData(&$formData, $postData)
	{
		$dataValidAcquis = array();
		
		/*** Récupération de la référence du degre ***/
		
		if (isset($formData['ref_valid']) && !empty($formData['ref_valid']))
		{
			$dataValidAcquis['ref_valid'] = $formData['ref_valid'];
		}

		/*** Récupèration du nom du degre ***/

		$formData['nom_acquis'] = $this->validatePostData($postData['nom_acquis'], "nom_acquis", "string", true, "Aucun nom n'a été saisi.", "Le nom n'est pas correctement saisi.");
		$dataValidAcquis['nom_acquis'] = $formData['nom_acquis'];


		/*** Récupèration de la description du niveau ***/

		$formData['descript_acquis'] = $this->validatePostData($postData['descript_acquis'], "descript_acquis", "string", false, "Aucun description n'a été saisi.", "La description est incorrecte.");
		$dataValidAcquis['descript_acquis'] = $formData['descript_acquis'];

		
		return $dataValidAcquis;
	}

	

	/*
	public function setValidProperties($previousMode, $dataValidAcquis, &$formData)
	{

		if ($previousMode == "new")
		{
			// Insertion du niveau dans la bdd
			$resultsetValidAcquis = $this->setValid("insert", $dataValidAcquis);

			if (isset($resultsetValidAcquis['response']['valid_acquis']['last_insert_id']) && !empty($resultsetValidAcquis['response']['valid_acquis']['last_insert_id']))
			{
				$formData['ref_valid'] = $resultsetValidAcquis['response']['valid_acquis']['last_insert_id'];
				$dataValidAcquis['ref_valid'] = $formData['ref_valid'];
			}
			else 
			{
				$this->registerError("form_valid", "L'enregistrement du niveau a échoué.");
			}
 
		}
		else if ($previousMode == "edit"  || $previousMode == "save")
		{
			

			if (isset($dataValidAcquis['ref_valid']) && !empty($dataValidAcquis['ref_valid']))
			{
				$formData['ref_valid'] = $dataValidAcquis['ref_valid'];

				// Mise à jour du niveau
				$resultsetValidAcquis = $this->setValid("update", $dataValidAcquis);

				if (!$resultsetValidAcquis)
				{
					$this->registerError("form_valid", "La mise à jour du niveau a échouée.");
				}
			}
			else
			{
				$this->registerError("form_valid", "La mise à jour du niveau a échouée.");
			}
		}
		else
		{
			header("Location: ".SERVER_URL."erreur/page404");
			exit();
		}
	}
	*/


	/*
	public function setValid($modeValidAcquis, $dataValidAcquis)
	{

		if (!empty($dataValidAcquis) && is_array($dataValidAcquis))
		{
			if (!empty($dataValidAcquis['nom_acquis']))
			{
				if ($modeValidAcquis == "insert")
				{
					$resultset = $this->validAcquisDAO->insert($dataValidAcquis);
					
					// Traitement des erreurs de la requête
					if (!$this->filterDataErrors($resultset['response']))
					{
						return $resultset;
					}
					else 
					{
						$this->registerError("form_request", "Le niveau n'a pu être inséré.");
					}
					
				}
				else if ($modeValidAcquis == "update")
				{ 
					$resultset = $this->validAcquisDAO->update($dataValidAcquis);

					// Traitement des erreurs de la requête
					if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['valid_acquis']['row_count']) && !empty($resultset['response']['valid_acquis']['row_count']))
					{
						return $resultset;
					} 
					else 
					{
						$this->registerError("form_request", "Le niveau n'a pu être mis à jour.");
					}
				}
			}
			else 
			{
				$this->registerError("form_request", "Le nom du niveau est manquant.");
			}
		}
		else 
		{
			$this->registerError("form_request", "Insertion du niveau non autorisée.");
		}
			
		return false;
	}
	*/
	
	/*
	public function deleteValid($refValidAcquis)
	{
		// On commence par sélectionner le degré
		$resultsetSelect = $this->validAcquisDAO->selectById($refValidAcquis);
		
		if (!$this->filterDataErrors($resultsetSelect['response']))
		{ 
			$resultsetDelete = $this->validAcquisDAO->delete($refValidAcquis);
		
			if (!$this->filterDataErrors($resultsetDelete['response']))
			{
				return true;
			}
			else 
			{
				$this->registerError("form_request", "Le niveau n'a pas pu être supprimée.");
			}
		}
		else
		{
		   $this->registerError("form_request", "Ce niveau n'existe pas."); 
		}

		return false;
	}
	*/

}


?>
