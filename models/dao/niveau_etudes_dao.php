<?php


/**
 * Description of NiveauEtudesDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe NiveauEtudes
require_once(ROOT.'models/niveau_etudes.php');


class NiveauEtudesDAO extends ModelDAO
{

    private $resultset = array();
   
    
    
    public function __construct()
    {
         $this->initialize();
    }
    
    
    
    /**
     * initialize - Initialise le tableau dans lequel se trouvent les résultats des requêtes et les erreurs
     * 
     */
    public function initialize()
    {
        $this->resultset['response'] = array();
        $this->resultset['response']['errors'] = array();
    }
    
    
    
    
    
    /**
     * selectAll - Retourne la liste de tous les niveaux d'études
     * 
     * @return array Liste d'objets "NiveauEtudes"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM niveau_etudes";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "niveau_etudes", "NiveauEtudes");

        return $this->resultset;

    }
    
    
    
    
    
    /**
     * selectById - Récupère le niveau d'études correspondant à l'identifiant spécifié.
     * 
     * @param int Identifiant du niveau d'études.
     * @return object Le niveau d'études correspondant à l'identifiant.
     */
    public function selectById($idNiveau) 
    {
        $this->initialize();
        
        if (!empty($idNiveau))
        {
            $request = "SELECT * FROM niveau_etudes WHERE id_niveau = ".$idNiveau;

            $this->resultset['response'] = $this->executeRequest("select", $request, "niveau_etudes", "NiveauEtudes");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }

}

?>
