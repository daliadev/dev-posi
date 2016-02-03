<?php


// Inclusion du fichier de la classe ParcoursPreco
require_once(ROOT.'models/parcours_preco.php');



class ParcoursPrecoDAO extends ModelDAO
{

	
	/**
	 * selectAll - Retourne la liste de tous les parcours préconisés.
	 * 
	 * @return array Liste d'objets "ParcoursPreco".
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM parcours_preco ORDER BY nom_parcours ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "parcours_preco", "ParcoursPreco");

		return $this->resultset;
	}

	
	
	/**
	 * selectById - Récupère le parcours préconisé correspondant à l'identifiant.
	 * 
	 * @param int Identifiant du parcours préconisé.
	 * @return array Parcours_preco correspondant à l'identifiant sinon erreurs.
	 */
	public function selectById($idParcours) 
	{
		$this->initialize();
		
		if (!empty($idParcours))
		{
			$request = "SELECT * FROM parcours_preco WHERE id_parcours = ".$idParcours;

			$this->resultset['response'] = $this->executeRequest("select", $request, "parcours_preco", "ParcoursPreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * insert - Insère un parcours préconisé
	 * 
	 * @param array Valeurs du parcours préconisé à inserer
	 * @return bool Vrai si l'insertion a fonctionné
	 */
	public function insert($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			$request = $this->createQueryString("insert", $values, "parcours_preco");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "parcours_preco", "ParcoursPreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour un parcours préconisé
	 * 
	 * @param array Valeurs du parcours préconisé à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			if (isset($values['ref_parcours']) && !empty($values['ref_parcours']) && isset($values['nom_parcours']) && !empty($values['nom_parcours']))
			{
				if (isset($values['descript_parcours']) && $values['descript_parcours'] == null)
				{
					unset($values['descript_parcours']);
				}
				
				$refParcours = $values['ref_parcours'];
				unset($values['ref_parcours']);
				
				$request = $this->createQueryString("update", $values, "parcours_preco", "WHERE id_parcours = ".$refParcours);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "parcours_preco", "ParcoursPreco");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le parcours préconisé à mettre à jour.");
			}
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * delete - Efface un parcours préconisé
	 * 
	 * @param int Identifiant du parcours préconisé
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($refParcours) 
	{
		$this->initialize();
		
		if (!empty($refParcours))
		{
			$request = "DELETE FROM parcours_preco WHERE id_parcours = ".$refParcours;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "parcours_preco", "ParcoursPreco");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du parcours préconisé.");
		}

		return $this->resultset;
	}
	
}

?>
