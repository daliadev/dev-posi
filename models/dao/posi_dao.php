<?php


// Inclusion du fichier de la classe Degre
require_once(ROOT.'models/positionnement.php');



class PositionnementDAO extends ModelDAO
{

    
    /**
     * selectAll - Retourne la liste de tous les positionnements.
     * 
     * @return array Liste d'objets "Positionnement".
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM positionnement ORDER BY nom_posi ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "positionnement", "Positionnement");

        return $this->resultset;
    }

    
    
    
    
    /**
     * selectById - Récupère le positionnement correspondant à l'identifiant.
     * 
     * @param int Identifiant du positionnement.
     * @return array Positionnement correspondant à l'identifiant sinon erreurs.
     */
    public function selectById($idPosi) 
    {
        $this->initialize();
        
        if (!empty($idPosi))
        {
            $request = "SELECT * FROM positionnement WHERE id_posi = ".$idPosi;

            $this->resultset['response'] = $this->executeRequest("select", $request, "positionnement", "Positionnement");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }


    /**
     * selectByUser - Récupère tous les domaines correspondant à un utilisateur
     * 
     * @param int Référence de l'utilisateur
     * @return array Positionnement(s) correspondant à l'utilisateur
     */
    public function selectByUser($refUser) 
    {
        $this->initialize();
        
        if (!empty($refUser))
        {
            $request = "SELECT * FROM positionnement, session ";
            $request .= "WHERE session.ref_user = ".$refUser." ";
            $request .= "AND session.ref_posi = positionnement.id_posi ";
            $request .= "GROUP BY id_posi ORDER BY nom_posi ASC";
            
            $this->resultset['response'] = $this->executeRequest("select", $request, "positionnement", "Positionnement");

        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
}

?>
