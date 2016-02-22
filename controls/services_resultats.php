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






	public function getRecursiveCategoriesResults($level, $categories, $parentCat = null, $totalScore = 0, $count = 0, $currentCatId = null)
	{

		$countChildren = $count;
		$totalPercent = $totalScore;
		$currentParent = $parentCat;
		//$percentChildren = 0;
		$scoreMoyenne = 0;
		//$parentScore = 0;

		//var_dump('');
		//var_dump('FUNCTION - $currentParent parameter = ' . $currentParent->getCode());

		foreach ($categories as $categorie) 
		{	
			$levelCat = strlen($categorie->getCode()) / 2;

			if ($levelCat == $level && $level > 1 && $categorie->getHasResult() && $categorie->getParent() !== null)  
			{
				$parentCat = $categorie->getParent();
				$parentCat->setHasResult(true);
				/* new */
				
				/*
				var_dump('*----------------------------------');
				var_dump('1 - $categorie->getCode() = '.$categorie->getCode());
				var_dump('1 - $parentCat->getCode() = '.$parentCat->getCode());
				var_dump('1 - $countChildren = '.$countChildren);
				var_dump('1 - $categorie->getScorePercent() = '.$categorie->getScorePercent());
				var_dump('1 - $totalPercent = '.$totalPercent);
				//var_dump('1 - $currentParent->getCode() : '.$currentParent->getCode());
				*/
				$catScore = $categorie->getScorePercent();

				
				if ($currentParent !== null)
				{

					if ($currentParent->getCode() == $parentCat->getCode())
					{
						// nouvelle categorie du parent courant
						//var_dump('-- nouvelle categorie du parent courant');
						if ($currentCatId != null && $currentCatId != $categorie->getCode()) 
						{
							$countChildren++;
							$totalPercent += $catScore;
						}
						
						if ($parentCat->getHasResult()) 
						{
							$totalPercent += $parentCat->getScorePercent();
							$countChildren++;
							//$scoreMoyenne = ($parentCatScore + $totalPercent) / ($countChildren + 1);
						}
						/*
						else
						{
							
						}
						*/
						
						
					}
					else
					{

						// Les scores des enfants deviennent la moyenne de leur total et sont attribués au parent
						if ($totalPercent > 0 && $countChildren > 0)
						{
							$scoreMoyenne = $totalPercent / $countChildren;
						}
						else
						{
							$scoreMoyenne = 0;
						}

						// Les parents ont changés, on assigne le score à l'ancien parent
						$currentParent->setScorePercent($scoreMoyenne);
						$currentParent->setHasResult(true);
						

						// 1er categorie du parent courant
						//var_dump('-- 1ere categorie du parent courant');

						// Nouvelles valeurs
						$countChildren = 1;
						$totalPercent = $catScore;
						$currentParent = $parentCat;
						//$currentCatId = $categorie->getCode();
					}
				}
				else
				{
					// 1er categorie du premier parent
					//var_dump('- 1ere categorie du premier parent');
					$countChildren = 1;
					$totalPercent = $catScore;
					$currentParent = $parentCat;
					
				}
				
				//$parentCat->setHasResult(true);
				//$parentCat->setScorePercent($totalPercent);
				
				/*
				//var_dump('');
				//var_dump('2 - Code cat en cours : '.$categorie->getCode());
				//var_dump('2 - Remonte vers '.$parentCat->getCode());
				var_dump('2 - $countChildren = '.$countChildren);
				var_dump('2 - $categorie->getScorePercent() = '.$categorie->getScorePercent());
				var_dump('2 - $totalPercent = '.$totalPercent);
				var_dump('2 - $currentParent->getCode() = '.$currentParent->getCode());
				var_dump('2 - $parentCat->getHasResult() = '.$parentCat->getHasResult());
				*/
				$currentCatId = $categorie->getCode();
				
				/* Fin new */


				
				
				
				
				//if ($currentParent->getCode() !== null)
				//{
					/*
					// Si le parent a changé
					if ($parentCat->getCode() != $currentParentCode) 
					{	
						$percentChildren = 0;

						if ($totalPercent > 0 && $countChildren > 0)
						{
							$percentChildren = $totalPercent / $countChildren;
							//var_dump('Moyenne enfant = '.$percentChildren);

							if ($parentCat->getHasResult())
							{
								$percentChildren += $parentCat->getScorePercent();
								//var_dump('Moyenne enfant + parent = '.$percentChildren);
							}
						}

						$currentParentCode = $parentCat->getCode();
						
						$totalPercent = 0;
						$countChildren = 0;
					}
					else
					{
						//var_dump('$parentCat->getCode() == $currentParentCode');
					}
					*/
					/*
					if ($percentChildren > 0)
					{
						$parentScore = $percentChildren / 2;
					}
					else
					{
						$parentScore = 0;
					}

					$parentCat->setScorePercent($parentScore);
					*/
					/*
					$currentParentCode = null;
					$countChildren = 0;
					$totalPercent = 0;
					*/
				//}
				//else
				//{
					//$currentParentCode = $parentCat->getCode();

					/*
					// Calcul du score en faisant la moyenne entre le score parent et le score enfant
					$scorePercent = $categorie->getScorePercent();
					//$scorePercentParent = $parentCat->getScorePercent();


					//if ($scorePercentParent > 0 && $parentCat->getHasResult())
					//{
						//$totalParentPercent += $scorePercentParent + $scorePercent;
					//}
					//else
					//{
						$totalPercent += $scorePercent;
					//}
					
					$countChildren++;
					*/
				//}
				
				// Calcul du score en faisant la moyenne entre le score parent et le score enfant
				//$scorePercent = $categorie->getScorePercent();
				//$scorePercentParent = $parentCat->getScorePercent();



				//if ($scorePercentParent > 0 && $parentCat->getHasResult())
				//{
					//$totalParentPercent += $scorePercentParent + $scorePercent;
				//}
				//else
				//{
					//$totalPercent += $scorePercent;
				//}
				
				//$countChildren++;
				
				//var_dump('$scorePercent = '.$scorePercent.' - $countChildren = '.$countChildren.' - $totalPercent = '.$totalPercent);
				
				
				
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


				/*
				// Calcul du temps de réponse moyen par question
				$temps = ($categorie->getTemps() !== null) ? $categorie->getTemps() : 0;
				$tempsParent = ($parentCat->getTemps() !== null) ? $parentCat->getTemps() : 0;
				$tempsParent = (($temps + $tempsParent) > 0) ? ($temps + $tempsParent) / 2 : 0;
				//$tempsParent += $nbreReponses;
				$parentCat->setTemps($tempsParent);
				*/

			}
			else
			{

			}	

		}

		$countLevelParent = 0;

		$level--;

		if ($level > 1) 
		{
			$this->getRecursiveCategoriesResults($level, $categories, $currentParent, $totalPercent, $countChildren, $currentCatId);
		}
		else if ($countChildren > 0)
		{
			if ($totalPercent > 0) {

				$scoreMoyenne = $totalPercent / $countChildren;
			}
			else
			{
				$scoreMoyenne = 0;
			}

			$currentParent->setScorePercent($scoreMoyenne);
			$currentParent->setHasResult(true);
		}
		return $categories;
	}


}


?>
