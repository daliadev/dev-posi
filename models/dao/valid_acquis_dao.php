<?php



// Inclusion du fichier de la classe NiveauEtudes
require_once(ROOT.'models/valid_acquis.php');


class ValidAcquisDAO extends ModelDAO
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
     * selectAll - Retourne la liste de tous les niveaux de validation des acquis
     * 
     * @return array Liste d'objets "ValidationAcquis"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM valid_acquis";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "valid_acquis", "ValidationAcquis");

        return $this->resultset;

    }
    
    
    
    
    
    /**
     * selectById - Récupère le niveau de validation des acquis correspondant à l'identifiant spécifié.
     * 
     * @param int Identifiant du niveau de validation des acquis.
     * @return object Le niveau de validation des acquis correspondant à l'identifiant.
     */
    public function selectById($refValidAcquis) 
    {
        $this->initialize();
        
        if (!empty($refValidAcquis))
        {
            $request = "SELECT * FROM valid_acquis WHERE id_acquis = ".$refValidAcquis;

            $this->resultset['response'] = $this->executeRequest("select", $request, "valid_acquis", "ValidationAcquis");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }





    /**
     * insert - Insère un niveau de validation
     * 
     * @param array Valeurs du niveau de validation à inserer
     * @return bool Vrai si l'insertion a fonctionné
     */
    public function insert($values) 
    {

        $this->initialize();
        
        if (!empty($values))
        {       
            $request = $this->createQueryString("insert", $values, "valid_acquis");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "valid_acquis", "ValidationAcquis");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour un niveau de validation
     * 
     * @param array Valeurs du niveau de validation à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['id_acquis']) && !empty($values['id_acquis']))
            {
                $refValidAcquis = $values['id_acquis'];
                unset($values['id_acquis']);
                
                $request = $this->createQueryString("update", $values, "valid_acquis", "WHERE id_acquis = ".$refValidAcquis);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "valid_acquis", "ValidationAcquis");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le niveau d'acquisition à mettre à jour.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface un niveau de validation
     * 
     * @param int Identifiant du niveau de validation
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refValidAcquis) 
    {
        $this->initialize();
        
        if (!empty($refValidAcquis))
        {
            $request = "DELETE FROM valid_acquis WHERE id_acquis = ".$refValidAcquis;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "valid_acquis", "ValidationAcquis");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du degré.");
        }

        return $this->resultset;
    }
    
}

?>
