<?php


// Inclusion du fichier de la classe Parcours
require_once(ROOT.'models/parcours.php');



class ParcoursDAO extends ModelDAO
{

	
	/**
	 * selectAll - Retourne la liste de tous les parcours.
	 * 
	 * @return array Liste d'objets "Parcours".
	 */
	public function selectAll() 
	{
		$this->initialize();

		$request = "SELECT * FROM parcours ORDER BY nom_parcours ASC";
		
		$this->resultset['response'] = $this->executeRequest("select", $request, "parcours", "Parcours");

		return $this->resultset;
	}

	
	
	
	
	/**
	 * selectById - Récupère le parcours correspondant à l'identifiant.
	 * 
	 * @param int Identifiant du parcours.
	 * @return array parcours correspondant à l'identifiant sinon erreurs.
	 */
	public function selectById($idParcours) 
	{
		$this->initialize();
		
		if (!empty($idParcours))
		{
			$request = "SELECT * FROM parcours WHERE id_parcours = ".$idParcours;

			$this->resultset['response'] = $this->executeRequest("select", $request, "parcours", "Parcours");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * insert - Insère un parcours
	 * 
	 * @param array Valeurs du parcours à inserer
	 * @return bool Vrai si l'insertion a fonctionné
	 */
	public function insert($values) 
	{
	   $this->initialize();
		
		if (!empty($values))
		{       
			$request = $this->createQueryString("insert", $values, "parcours");
			
			$this->resultset['response'] = $this->executeRequest("insert", $request, "parcours", "Parcours");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
		}
		
		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * update - Met à jour un parcours
	 * 
	 * @param array Valeurs du parcours à mettre à jour
	 * @return array Nbre de lignes mises à jour sinon erreurs
	 */
	public function update($values) 
	{
		$this->initialize();
		
		if (!empty($values))
		{
			if (isset($values['ref_parcours']) && !empty($values['ref_parcours']))
			{
				$refParcours = $values['ref_parcours'];
				unset($values['ref_parcours']);
				
				$request = $this->createQueryString("update", $values, "parcours", "WHERE id_parcours = ".$refParcours);
				
				$this->resultset['response'] = $this->executeRequest("update", $request, "parcours", "Parcours");
			}
			else
			{
				$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le parcours à mettre à jour.");
			}
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
		}

		return $this->resultset;
	}
	
	
	
	
	
	/**
	 * delete - Efface un parcours
	 * 
	 * @param int Identifiant du parcours
	 * @return array Nbre de lignes effacées sinon erreurs
	 */
	public function delete($refParcours) 
	{
		$this->initialize();
		
		if (!empty($refParcours))
		{
			$request = "DELETE FROM parcours WHERE id_parcours = ".$refParcours;

			$this->resultset['response'] = $this->executeRequest("delete", $request, "parcours", "Parcours");
		}
		else
		{
			$this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du parcours.");
		}

		return $this->resultset;
	}
	
}

?>
