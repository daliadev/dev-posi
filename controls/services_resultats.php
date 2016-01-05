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





	public function getCategoriesResults($level, $categories)
	{
		//$list = '';
		//$previous_level = 0;
		//$isMainListOpen = false;
		//$isListOpen = false;
		
		/*
		if ($level > 0) 
		{
			//$list .= '<ul>';
		}
		*/

		//var_dump($level);

		foreach ($categories as $categorie) 
		{	
			$levelCat = strlen($categorie->getCode()) / 2;
			//$catLevel = $categorie->getCode;

			if ($levelCat == $level && $levelCat > 1)
			{
				if ($categorie->getHasResult(true) && $categorie->getParent() !== null)  
				{
					$parentCat = $categorie->getParent();
					$parentCat->setHasResult(true);

					$nbreReponses = ($categorie->getTotalReponses() !== null) ? $categorie->getTotalReponses() : 0;
					$nbreReponsesParent = ($parentCat->getTotalReponses() !== null) ? $parentCat->getTotalReponses() : 0;
					$nbreReponsesParent += $nbreReponses;
					$parentCat->setTotalReponses($nbreReponsesParent);

					$nbreReponsesCorrectes = ($categorie->getTotalReponsesCorrectes() !== null) ? $categorie->getTotalReponsesCorrectes() : 0;
					$nbreReponsesCorrectesParent = ($parentCat->getTotalReponsesCorrectes() !== null) ? $parentCat->getTotalReponsesCorrectes() : 0;
					$nbreReponsesCorrectesParent += $nbreReponsesCorrectes;
					$parentCat->setTotalReponsesCorrectes($nbreReponsesCorrectesParent);

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
					/*
					if ($previous_level < $level) 
					{
						$list .= '<ul>';
					}

					if ($level == 0)
					{
						$list .= '<li>';
						$list .= $cat->getDescription().' / '.$cat->getNom().' / '.$cat->getScorePercent().'%';
						//$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'"><h3><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></h3></div>';
						//$list .= '<div class="progress">';
						//$list .= getProgressBar($cat->getScorePercent());
						//$list .= '</div>';

						$list .= '<g class="cat-bar">';
							$list .= '<line class="cat-line" x1="1" y1="0" x2="1" y2="56"/>';
							$list .= '<text class="cat-text" x="9" y="0">Ecrit</text>';
							$list .= '<text class="reponses" x="505" y="0">14/24</text>';
							$list .= '<rect class="back" x="9" y="24" width="701" height="32" />';
							$list .= '<rect class="front" x="9" y="24" width="500" height="32" />';
							$list .= '<text class="percent-cat" x="497" y="41">72<tspan class="percent">%<tspan></text>';
						$list .= '</g>';

						$isMainListOpen = true;
					}
					else
					{
						if ($isListOpen) 
						{
							$list .= '</li>';
						}
						$list .= '<li>';
						$list .= $cat->getDescription().' / '.$cat->getNom().' / '.$cat->getScorePercent().'%';
						//$list .= '<div class="progress-title" title="'.$cat->getDescription().'"><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></div>';
						//$list .= '<div class="progress">';
						//$list .= getProgressBar($cat->getScorePercent());
						//$list .= '</div>';

						$isListOpen = true;
					}

					$previous_level = $level;

					*/
				}

				$level--;

				if ($level > 1) 
				{
					$categories = $this->getCategoriesResults($level, $categories);
				}
			}

				
		}
		/*
		if ($previous_level == $level && $previous_level != 0) 
		{
			if ($isMainListOpen || $isListOpen)
			{
				$list .= '</li>';
			}
			$list .= '</ul>';
		}
		*/
		return $categories;
	}


}


?>
