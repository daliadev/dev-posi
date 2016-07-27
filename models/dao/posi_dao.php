<?php


// Inclusion du fichier de la classe Degre
require_once(ROOT.'models/positionnement.php');



class PositionnementDAO extends ModelDAO
{

    
    /**
     * selectAll - Retourne la liste de tous les positionnnements.
     * 
     * @return array Liste d'objets "Positionnnement".
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM positionnnement ORDER BY nom_posi ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "positionnnement", "Positionnnement");

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
            $request = "SELECT * FROM positionnnement WHERE id_posi = ".$idPosi;

            $this->resultset['response'] = $this->executeRequest("select", $request, "positionnnement", "Positionnnement");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
}

?>
