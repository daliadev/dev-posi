<?php


require_once(ROOT.'controls/authentication.php');
require_once(ROOT.'models/dao/compte_dao.php');
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'utils/region.php');

class ServicesAdminGestion extends Main
{
	
	private $compteDAO = null;
	private $organismeDAO = null;
	
	
	public function initializeFormData(&$formData, $postData, $initializedData)
	{
		foreach($initializedData as $key => $type)
		{
			switch ($type)
			{
				case "select" :
			
					if (isset($postData[$key]) && !empty($postData[$key]) && $postData[$key] != "select_cbox")
					{
						$formData[$key] = $postData[$key];
					}
					else
					{
						// Il n'y a pas de donnée prédéfini
						$formData[$key] = null;
					}
					break;
					
				case "text" :
					$formData[$key] = null;
					break;
				
				case "multi" :
					$formData[$key] = array();
					break;
				
				default :
					break;
			}
		}
		
	}
	
	
	
	
	public function getFormMode($postData)
	{     
		if (isset($postData['save']) && !empty($postData['save']))
		{
			$mode = "save";
		}
		else if (isset($postData['edit']) && !empty($postData['edit']))
		{
			$mode = "edit";
		}
		else if ((isset($postData['delete']) && !empty($postData['delete']) && $postData['delete'] == "true") || (isset($postData['del']) && !empty($postData['del'])))
		{
			$mode = "delete";
		}
		else
		{
			if (isset($postData['add']) && !empty($postData['add']))
			{
				$mode = "new";
			}
			else 
			{
				$mode = "view";
			}
		}
		
		return $mode;
	}
	
	
	
	
	public function switchFormButtons(&$formData, $mode)
	{
		switch ($mode)
		{
			case "init":
				$formData['disabled'] = "";
				$formData['save_disabled'] = "";
				$formData['edit_disabled'] = "";
				$formData['delete_disabled'] = "";
				$formData['add_disabled'] = "";
				break;
			
			case "view":
				$formData['disabled'] = "disabled";
				$formData['save_disabled'] = "disabled";
				$formData['edit_disabled'] = "disabled";
				$formData['delete_disabled'] = "disabled";
				$formData['add_disabled'] = "";
				break;
			
			case "new":
				$formData['disabled'] = "";
				$formData['save_disabled'] = "";
				$formData['edit_disabled'] = "disabled";
				$formData['delete_disabled'] = "";
				$formData['add_disabled'] = "disabled";
				break;
			
			case "edit":
				$formData['disabled'] = "";
				$formData['save_disabled'] = "";
				$formData['edit_disabled'] = "disabled";
				$formData['delete_disabled'] = "";
				$formData['add_disabled'] = "disabled";
				break;
			
			case "save":
				$formData['disabled'] = "";
				$formData['save_disabled'] = "";
				$formData['edit_disabled'] = "disabled";
				$formData['delete_disabled'] = "";
				$formData['add_disabled'] = "disabled";
				break;
			
			case "delete":
				$formData['disabled'] = "disabled";
				$formData['save_disabled'] = "disabled";
				$formData['edit_disabled'] = "disabled";
				$formData['delete_disabled'] = "disabled";
				$formData['add_disabled'] = "disabled";
				break;
			
			default :
				break;
				
		}
	}
	
	
	
	public function authenticateAdmin($login, $pass)
	{
		$this->compteDAO = new CompteDAO();
		
		$mdp = Config::hashPassword($pass);
		
		$resultset = $this->compteDAO->authenticate($login, $mdp);
		
		// Traitement des erreurs de la requête
		if (!$this->filterDataErrors($resultset['response']) && $resultset['response']['auth'])
		{
			return $resultset;
		}
		
		return false;
	}



	public function authenticatePublic($login, $pass)
	{
		// On compare le login aux noms des organismes
		$loginOk = false;
		$passOK = false;

		$organPass = array();
		$departementOk = false;

		$this->organismeDAO = new OrganismeDAO();
		$listOrgan = $this->organismeDAO->selectAll();

		if (isset($listOrgan['response']['organisme']) && !empty($listOrgan['response']['organisme']))
		{
			if (!is_array($listOrgan['response']['organisme']))
			{
				$organisme = $listOrgan['response']['organisme'];
				$listOrgan['response']['organisme'] = array($organisme);
			}

			$j = 0;

			for ($i = 0; $i < count($listOrgan['response']['organisme']); $i++) 
			{
				$organ = strtoupper($listOrgan['response']['organisme'][$i]->getNom());

				if (strtoupper($login) == $organ || strpos($organ, strtoupper($login)) !== false) 
				{
					$loginOk = true;
					$organPass[$j]['nom'] = substr($organ, 0, 3);
					$organPass[$j]['numDep'] = substr($listOrgan['response']['organisme'][$i]->getCodePostal(), 0, 2);

					$j++;
				}
			}
		}
		
		
		// Récupération et comparaison du numéro de la région

		if ($loginOk) 
		{
			$regions = new Region(ROOT.'database/regions/region'.Config::ANNEE_REGION.'.txt');
			$regionsList = $regions->getList();

			$organNums = array();

			for ($i = 0; $i < count($regionsList); $i++) { 
				
				$departements = $regionsList[$i];

				for ($j = 0; $j < count($regionsList[$i]['departements']); $j++) { 
					
					$numDep = $regionsList[$i]['departements'][$j]['numero'];

					$k = 0;

					foreach ($organPass as $organ) 
					{
						if ($organ['numDep'] == $numDep)
						{
							$organNums[$k]['num'] = $organ['numDep'];
							$organNums[$k]['nom'] = $organ['nom'];

							$k++;
						}
					}

					if (count($organNums) > 0)
					{
						$departementOk = true;
					}
				}
			}

			var_dump($organNums);
			exit();

			
			if ($organPass && $depOrganPass && $departementOk)
			{
				// Mot de passe constité des 3 premières lettres de l'organisme, suivi de son numero de département et de l'année courante.
				$year = date('Y');

				foreach ($organNums as $organNum) 
				{
					$mdp = $organNum['nom'].$organNum['num'].$year;

					if ($pass === $mdp) 
					{
						$passOK = true;
						break;
					}
				}
			}
		}
		
		
		if ($loginOk && $passOK) 
		{
			

			return true;
		}

		return false;
	}
	
}

?>
