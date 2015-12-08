<?php



// Inclusion du fichier de la classe CategoriePreconisation
require_once(ROOT.'models/categorie_preco.php');



class CategoriePrecoDAO extends ModelDAO
{

	
	
	/**
	 * selectAll - Retourne la liste de toutes les catégorie-preconisations
	 * 
	 * @return array Liste d'objets "Categorie"
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM cat_preco ORDER BY ref_code_cat ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "cat_preco", "CategoriePreconisation");

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * selectByCode - Récupère la catégorie correspondant au code.
	 * 
	 * @param string Code de la catégorie
	 * @return array Catégorie correspondant au code sinon erreurs
	 */
	public function selectByRefCodeCat($codeCat) 
	{
		$this->initialize();
		
		if (!empty($codeCat))
		{
			$request = "SELECT * FROM cat_preco WHERE ref_code_cat = ".$codeCat;

			$this->resultset['response'] = $this->executeRequest("select", $request, "cat_preco", "CategoriePreconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
		}
		
		return $this->resultset;
	}
	
	
	public function selectByRefPreco($refPreco) 
	{
		$this->initialize();
		
		if (!empty($refPreco))
		{
			$request = "SELECT * FROM cat_preco WHERE ref_preco = ".$refPreco;

			$this->resultset['response'] = $this->executeRequest("select", $request, "cat_preco", "CategoriePreconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
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
		
		if (isset($values['ref_code_cat']) && !empty($values['ref_code_cat']) && isset($values['ref_preco']) && !empty($values['ref_preco']))
		{
			$request = "INSERT INTO cat_preco (ref_code_cat, ref_preco) VALUES (".$values['ref_code_cat'].", '".$values['ref_preco']."')";

			$this->resultset['response'] = $this->executeRequest("insert", $request, "cat_preco", "CategoriePreconisation");
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
			if (isset($values['ref_code_cat']) && !empty($values['ref_code_cat']) && isset($values['ref_preco']) && !empty($values['ref_preco']))
			{
				$refCodeCat = $values['ref_code_cat'];
				unset($values['ref_code_cat']);
				
				$request = $this->createQueryString("update", $values, "cat_preco", "WHERE ref_code_cat = ".$refCodeCat);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "cat_preco", "CategoriePreconisation");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la catégorie et la préconisation à mettre à jour.");
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
	 * @param int Identifiant de la question
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($refCodeCat) 
	{
		$this->initialize();
		
		if (!empty($refCodeCat))
		{
			$request = "DELETE FROM cat_preco WHERE ref_code_cat = ".$refCodeCat;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "cat_preco", "CategoriePreconisation");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la préconisation.");
		}

		return $this->resultset;
	}
	

}

?>
