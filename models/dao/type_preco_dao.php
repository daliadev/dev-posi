<?php


// Inclusion du fichier de la classe TypePreco
require_once(ROOT.'models/type_preco.php');



class TypePrecoDAO extends ModelDAO
{

	
	/**
	 * selectAll - Retourne la liste de tous les types de préconisations.
	 * 
	 * @return array Liste d'objets "TypePreco".
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM type_preco ORDER BY nom_type ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "type_preco", "TypePreco");

		return $this->resultset;
	}

	
	
	/**
	 * selectById - Récupère le type de préconisation correspondant à l'identifiant.
	 * 
	 * @param int Identifiant du type de préconisation.
	 * @return array Type_preco correspondant à l'identifiant sinon erreurs.
	 */
	public function selectById($idType) 
	{
		$this->initialize();
		
		if (!empty($idType))
		{
			$request = "SELECT * FROM type_preco WHERE id_type = ".$idType;

			$this->resultset['response'] = $this->executeRequest("select", $request, "type_preco", "TypePreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * insert - Insère un type de préconisation
	 * 
	 * @param array Valeurs du type de préconisation à inserer
	 * @return bool Vrai si l'insertion a fonctionné
	 */
	public function insert($values) 
	{
	   $this->initialize();
		
		if (!empty($values))
		{       
			$request = $this->createQueryString("insert", $values, "type_preco");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "type_preco", "TypePreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour un type de préconisation
	 * 
	 * @param array Valeurs du type de préconisation à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			if (isset($values['ref_type_preco']) && !empty($values['ref_type_preco']) && isset($values['nom_preco']) && !empty($values['nom_preco']))
			{
				if (isset($values['descript_type']) && $values['descript_type'] == null)
				{
					unset($values['descript_type']);
				}
				
				$refType = $values['ref_type_preco'];
				unset($values['ref_type_preco']);
				
				$request = $this->createQueryString("update", $values, "type_preco", "WHERE id_type = ".$refType);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "type_preco", "TypePreco");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le type de préconisation à mettre à jour.");
			}
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * delete - Efface un type de préconisation
	 * 
	 * @param int Identifiant du type de préconisation
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($refType) 
	{
		$this->initialize();
		
		if (!empty($refType))
		{
			$request = "DELETE FROM type_preco WHERE id_type = ".$refType;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "type_preco", "TypePreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du type de préconisation.");
		}

		return $this->resultset;
	}
	
}

?>
