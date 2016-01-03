<?php



// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/organisme.php');



class OrganismeDAO extends ModelDAO
{
   
	
	
	public function __construct()
	{
		 $this->initialize();
	}
	

	
	
	
	
	/**
	 * selectAll - Retourne la liste de tous les organismes
	 * 
	 * @return array Liste d'objets "Organisme"
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM organisme ORDER BY nom_organ ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");

		return $this->resultset;

	}
	
	
	
	
	
	/**
	 * selectById - Récupère l'organisme correspondant à l'identifiant
	 * 
	 * @param int Identifiant organisme
	 * @return array Organisme correspondant à l'identifiant sinon renvoie faux
	 */
	public function selectById($refOrganisme) 
	{
		$this->initialize();
		
		if (!empty($refOrganisme) && $refOrganisme !== null)
		{
			$request = "SELECT * FROM organisme WHERE id_organ = ".$refOrganisme;

			$this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
		//return $this->filterResultToArray($this->resultset, 'organisme');
	}
	
	
	
	
	
	/**
	 * selectByName - Récupère l'organisme grâce à son nom
	 * 
	 * @param int Nom de l'organisme
	 * @return array Organisme correspondant au nom sinon erreurs
	 */
	public function selectByName($nameOrganisme) 
	{
		$this->initialize();
		
		if(!empty($nameOrganisme))
		{
			$nameOrganisme = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $nameOrganisme));
			
			$request = "SELECT * FROM organisme 
				WHERE nom_organ = '".$nameOrganisme."'";

			$this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	/**
	 * selectByCodeInterne - Récupère l'organisme grâce à son code interne
	 * 
	 * @param string Chaîne de caractères correcpondant au numéro interne
	 * @return array Organisme correspondant sinon erreurs
	 */
	public function selectByCodeInterne($numOrganisme) 
	{
		$this->initialize();
		
		if(!empty($numOrganisme))
		{
			$request = "SELECT * FROM organisme WHERE numero_interne = '".$numOrganisme."'";

			$this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}


	
	
	
   /**
	 * insert - Insère un organisme
	 * 
	 * @param array Valeurs de l'organisme à inserer
	 * @return array Dernier identifiant d'insertion sinon erreurs
	 */
	public function insert($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			$request = $this->createQueryString("insert", $values, "organisme");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour un organisme
	 * 
	 * @param array Valeurs de l'organisme à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values, $refOrganisme = null) 
	{
		$this->initialize();
		
		if (!empty($values) && isset($values['ref_organ']) && !empty($values['ref_organ']))
		{
			$refOrgan = $values['ref_organ'];
			unset($values['ref_organ']);

			$request = $this->createQueryString("update", $values, "organisme", "WHERE id_organ = ".$refOrgan);

			$this->resultset['response'] = $this->executeRequest("update", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}

		return $this->resultset;
		//return $this->filterResultToArray($this->resultset, 'organisme');
	}

	
	
	
	
	/**
	 * delete - Efface un organisme
	 * 
	 * @param int Identifiant de l'organisme
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($idOrganisme) 
	{
		$this->initialize();
		
		if (!empty($idOrganisme))
		{
			$request = "DELETE FROM organisme WHERE id_organ = ".$idOrganisme;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "organisme", "Organisme");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
}

?>
