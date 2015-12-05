<?php



// Inclusion du fichier de la classe Categorie
require_once(ROOT.'models/preconisation.php');



class preconisationDAO extends ModelDAO
{

	
	
	/**
	 * selectAll - Retourne la liste de toutes les catégories
	 * 
	 * @return array Liste d'objets "Categorie"
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM question_cat ORDER BY code_cat ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "question_cat", "QuestionCategorie");

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
			$request = "SELECT * FROM question_cat WHERE ref_cat = ".$codeCat;

			$this->resultset['response'] = $this->executeRequest("select", $request, "question_cat", "QuestionCategorie");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
		}
		
		return $this->resultset;
	}
	
	



	/**
	 * selectByCategorie - Récupère la préconisation correspondant à la référence de la catégorie.
	 * 
	 * @param string référence de la catégorie
	 * @return array Catégorie correspondant au code sinon erreurs
	 */
	public function selectByCategorie($refCat) 
	{
		$this->initialize();
		
		if (!empty($refCat))
		{   
			$request = "SELECT id_preco, ref_type, nom_preco, descript_preco, taux_min, taux_max, num_ordre FROM cat_preco, preconisation ";
			$request .= "WHERE cat_preco.ref_code_cat = ".$refCat." AND preconisation.id_preco = cat_preco.ref_preco ORDER BY num_ordre ASC";
			$this->resultset['response'] = $this->executeRequest("select", $request, "preconisation", "Preconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucune code pour la catégorie recherchée.");
		}
		return $this->resultset;
	}
	
	
	
/**
	 * insert - Insère une catégorie
	 * 
	 * @param array Valeurs de la catégorie à inserer
	 * @return bool Vrai si l'insertion a fonctionné
	 */
	public function insert($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{       
			$request = $this->createQueryString("insert", $values, "categorie");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "categorie", "Categorie");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour une catégorie
	 * 
	 * @param array Valeurs de la catégorie à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			if (isset($values['code_cat']) && !empty($values['code_cat']))
			{
				$codeCat = $values['code'];
				unset($values['code']);
				
				$request = $this->createQueryString("update", $values, "categorie", "WHERE code_cat = ".$codeCat);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "categorie", "Categorie");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la catégorie à mettre à jour.");
			}
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * delete - Efface une catégorie
	 * 
	 * @param int Identifiant de la catégorie
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($codeCat) 
	{
		$this->initialize();
		
		if (!empty($codeCat))
		{
			$request = "DELETE FROM categorie WHERE code_cat = ".$codeCat;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "categorie", "Categorie");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la catégorie.");
		}

		return $this->resultset;
	}
	

}

?>
