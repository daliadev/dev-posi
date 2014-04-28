<?php


// Inclusion du fichier de la classe Degre
require_once(ROOT.'models/degre.php');



class DegreDAO extends ModelDAO
{

    
    /**
     * selectAll - Retourne la liste de tous les degrés.
     * 
     * @return array Liste d'objets "Degre".
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM degre ORDER BY nom_degre ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "degre", "Degre");

        return $this->resultset;
    }

    
    
    
    
    /**
     * selectById - Récupère le degré correspondant à l'identifiant.
     * 
     * @param int Identifiant du degré.
     * @return array Degré correspondant à l'identifiant sinon erreurs.
     */
    public function selectById($idDegre) 
    {
        $this->initialize();
        
        if (!empty($idDegre))
        {
            $request = "SELECT * FROM degre WHERE id_degre = ".$idDegre;

            $this->resultset['response'] = $this->executeRequest("select", $request, "degre", "Degre");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère un degré
     * 
     * @param array Valeurs du degré à inserer
     * @return bool Vrai si l'insertion a fonctionné
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {       
            $request = $this->createQueryString("insert", $values, "degre");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "degre", "Degre");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour un degré
     * 
     * @param array Valeurs du degré à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_degre']) && !empty($values['ref_degre']))
            {
                $refDegre = $values['ref_degre'];
                unset($values['ref_degre']);
                
                $request = $this->createQueryString("update", $values, "degre", "WHERE id_degre = ".$refDegre);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "degre", "Degre");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le degré à mettre à jour.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface un degré
     * 
     * @param int Identifiant du degré
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refDegre) 
    {
        $this->initialize();
        
        if (!empty($refDegre))
        {
            $request = "DELETE FROM degre WHERE id_degre = ".$refDegre;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "degre", "Degre");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du degré.");
        }

        return $this->resultset;
    }
    
}

?>
