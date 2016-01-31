<?php



// Inclusion du fichier de la classe Preconisation
require_once(ROOT.'models/preconisation.php');



class preconisationDAO extends ModelDAO
{

		
	/**
	 * selectAll - Retourne la liste de toutes les préconisations.
	 * 
	 * @return array Liste d'objets "Preconisation".
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM preconisation ORDER BY taux_min ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "preconisation", "Preconisation");

		return $this->resultset;
	}

	
	
	/**
	 * selectById - Récupère la préconisation correspondant à l'identifiant.
	 * 
	 * @param int Identifiant de la préconisation.
	 * @return array Préconisation correspondant à l'identifiant sinon erreurs.
	 */
	public function selectById($idPreco) 
	{
		$this->initialize();
		
		if (!empty($idPreco))
		{
			$request = "SELECT * FROM preconisation WHERE id_preco = ".$idPreco;

			$this->resultset['response'] = $this->executeRequest("select", $request, "preconisation", "Preconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}



	/**
	 * selectByCode - Récupère la catégorie correspondant au code.
	 * 
	 * @param string Code de la catégorie
	 * @return array Catégorie correspondant au code sinon erreurs
	 */
	public function selectByCodeCat($codeCat) 
	{
		$this->initialize();
		
		if (!empty($codeCat))
		{   
			$request = "SELECT * FROM preconisation, cat_preco WHERE cat_preco.ref_code_cat = '".$codeCat."' AND cat_preco.ref_preco = preconisation.id_preco ORDER BY preconisation.num_ordre ASC";

			$this->resultset['response'] = $this->executeRequest("select", $request, "preconisation", "Preconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * insert - Insère une préconisation
	 * 
	 * @param array Valeurs de la préconisation à inserer
	 * @return bool Vrai si l'insertion a fonctionné
	 */
	public function insert($values) 
	{
	   $this->initialize();
		
		if (!empty($values))
		{     
			$request = $this->createQueryString("insert", $values, "preconisation");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "preconisation", "Preconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour une préconisation
	 * 
	 * @param array Valeurs de la préconisation à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			if (isset($values['ref_preco']) && !empty($values['ref_preco']))
			{
				$refPreco = $values['ref_preco'];
				unset($values['ref_preco']);
				
				$request = $this->createQueryString("update", $values, "preconisation", "WHERE id_preco = ".$refPreco);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "preconisation", "Preconisation");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la préconisation à mettre à jour.");
			}
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * delete - Efface une  préconisation
	 * 
	 * @param int Identifiant de la préconisation
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($refPreco) 
	{
		$this->initialize();
		
		if (!empty($refPreco))
		{
			$request = "DELETE FROM preconisation WHERE id_preco = ".$refPreco;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "preconisation", "Preconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la préconisation.");
		}

		return $this->resultset;
	}
	

}

?>
