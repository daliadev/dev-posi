<?php


require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/session_dao.php');



class ServicesPosiResultats extends Main
{
	
	private $categorieDAO = null;
	private $questionCatDAO = null;
	private $resultatDAO = null;
	private $utilisateurDAO = null;
	private $organismeDAO = null;
	private $sessionDAO = null;
	
	
	public function __construct() 
	{
		$this->controllerName = "positionnementResults";

		$this->categorieDAO = new CategorieDAO();
		$this->questionCatDAO = new QuestionCategorieDAO();
		$this->resultatDAO = new ResultatDAO();
		$this->utilisateurDAO = new UtilisateurDAO();
		$this->organismeDAO = new OrganismeDAO();
		$this->sessionDAO = new SessionDAO();
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



	public function getUser($refUser)
	{
		$resultset = $this->utilisateurDAO->selectById($refUser);
		
		//return $this->utilisateurDAO->selectById($refUser);
		
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


	public function updateUser($dataUser)
	{
		//return $this->utilisateurDAO->update($dataUser);

		$resultset = $this->utilisateurDAO->update($dataUser);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['utilisateur']['row_count']) && !empty($resultset['response']['utilisateur']['row_count']))
		{
			return $resultset;
		} 
		else 
		{
			$this->registerError("form_request", "L'utilisateur n'a pas été mis à jour.");
		}
		
		return false;
	}



	public function getOrganisme($refOrgan)
	{
		//return $this->organismeDAO->selectById($refOrgan);

		$resultset = $this->organismeDAO->selectById($refOrgan);

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

	public function updateOrganisme($dataOrgan)
	{
		//return $this->organismeDAO->update($dataOrgan);

		$resultset = $this->organismeDAO->update($dataOrgan);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['organisme']['row_count']) && !empty($resultset['response']['organisme']['row_count']))
		{
			return $resultset;
		} 
		else 
		{
			$this->registerError("form_request", "L'organisme n'a pas été mis à jour.");
		}
		
		return false;
	}



	public function getSession($refSession)
	{
		//return $this->sessionDAO->selectById($refSession);
		
		$resultset = $this->sessionDAO->selectById($refSession);
		
		if (!$this->filterDataErrors($resultset['response']))
		{
			if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
			{ 
				$session = $resultset['response']['session'];
				$resultset['response']['session'] = array($session);
			}

			return $resultset;
		}
		
		return false;
		
	}


	public function updateSession($dataSession, $refSession)
	{
		//return $this->sessionDAO->update($dataSession, $refSession);

		$resultset = $this->sessionDAO->update($dataSession, $refSession);

		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['session']['row_count']) && !empty($resultset['response']['session']['row_count']))
		{
			return $resultset;
		} 
		else 
		{
			$this->registerError("form_request", "La session n'a pas été mis à jour.");
		}
		
		return false;
	}





	public function getRecursiveCategoriesResults($level, $categories)
	{

		$countLevelParent = 0;
		$totalPercent = 0;

		foreach ($categories as $categorie) 
		{	
			$levelCat = strlen($categorie->getCode()) / 2;

			if ($levelCat == $level && $level > 1 && $categorie->getHasResult() && $categorie->getParent() !== null)  
			{
				$parentCat = $categorie->getParent();

				$countLevelParent++;

				// Calcul du nombre de réponses totales
				$nbreReponses = ($categorie->getTotalReponses() !== null) ? $categorie->getTotalReponses() : 0;
				$nbreReponsesParent = ($parentCat->getTotalReponses() !== null) ? $parentCat->getTotalReponses() : 0;
				$nbreReponsesParent += $nbreReponses;
				$parentCat->setTotalReponses($nbreReponsesParent);

				// Calcul du nombre de réponses correctes
				$nbreReponsesCorrectes = ($categorie->getTotalReponsesCorrectes() !== null) ? $categorie->getTotalReponsesCorrectes() : 0;
				$nbreReponsesCorrectesParent = ($parentCat->getTotalReponsesCorrectes() !== null) ? $parentCat->getTotalReponsesCorrectes() : 0;
				$nbreReponsesCorrectesParent += $nbreReponsesCorrectes;
				$parentCat->setTotalReponsesCorrectes($nbreReponsesCorrectesParent);

				// Calcul du score en faisant la moyenne entre le score parent et le score enfant

				$scorePercent = $categorie->getScorePercent();
				$scorePercentParent = $parentCat->getScorePercent();
				if (($scorePercent + $scorePercentParent) > 0 && $parentCat->getHasResult())
				{
					$scorePercentParent = ($scorePercent + $scorePercentParent) / ($nbreReponses + 1);
				}
				else
				{
					$scorePercentParent = $scorePercent;
				}
				//$scorePercentParent = (($scorePercent + $scorePercentParent) > 0 && $parentCat->getHasResult()) ? ($scorePercent + $scorePercentParent) / 2 : 0;
				var_dump($nbreReponses.' - '.$categorie->getCode().'('.$scorePercent.') < '.$parentCat->getCode().'('.$scorePercentParent.') = '.$scorePercentParent);

				$parentCat->setScorePercent($scorePercentParent);

				/*
				// Calcul du temps de réponse moyen par question
				$temps = ($categorie->getTemps() !== null) ? $categorie->getTemps() : 0;
				$tempsParent = ($parentCat->getTemps() !== null) ? $parentCat->getTemps() : 0;
				$tempsParent = (($temps + $tempsParent) > 0) ? ($temps + $tempsParent) / 2 : 0;
				//$tempsParent += $nbreReponses;
				$parentCat->setTemps($tempsParent);
				*/
				
				$parentCat->setHasResult(true);

				/*
				// Calcul du score
				if ($nbreReponsesCorrectesParent != 0)
				{	
					$scoreParentCat = round(($nbreReponsesCorrectesParent / $nbreReponsesParent) * 100);
					$parentCat->setScorePercent($scoreParentCat);
				}
				else
				{
					$parentCat->setScorePercent(0);
				}
				*/
			}	

		}

		$level--;

		if ($level > 1) 
		{
			$this->getRecursiveCategoriesResults($level, $categories);
		}

		return $categories;
	}


}


?>
