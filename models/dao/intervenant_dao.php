<?php



// Inclusion du fichier de la classe Intervenant
require_once(ROOT.'models/intervenant.php');



class IntervenantDAO extends ModelDAO
{

    private $resultset = array();
    
    
    public function __construct()
    {
         $this->initialize();
    }
    
    
    public function initialize()
    {
        $this->resultset['response'] = array();
        $this->resultset['response']['errors'] = array();
    }
    
    
    
    
    
    /**
     * selectAll - Retourne la liste de tous les intervenants
     * 
     * @return array Liste d'objets "Intervenant"
     */
    public function selectAll() 
    {
        $this->initialize();
        
        $request = "SELECT * FROM intervenant";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "intervenant", "Intervenant");

        return $this->resultset;
    }

    
    
    
    
    /**
     * selectById - Récupère l'intervenant correspondant à l'identifiant
     * 
     * @param int Identifiant intervenant
     * @return array Objet "Intervenant" correspondant à l'identifiant sinon erreurs
     */
    public function selectById($idIntervenant) 
    {
        $this->initialize();
        
        if (!empty($idIntervenant))
        {
            $request = "SELECT * FROM intervenant WHERE id_intervenant = '".$idIntervenant."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "intervenant", "Intervenant");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByEmail - Récupère l'intervenant correspondant à l'email
     * 
     * @param string Email intervenant
     * @return array Objet "Intervenant" correspondant à l'email sinon erreurs
     */
    public function selectByEmail($emailIntervenant) 
    {
        $this->initialize();
        
        if (!empty($emailIntervenant))
        {
            $request = "SELECT * FROM intervenant WHERE LOWER(email_intervenant) = '".strtolower($emailIntervenant)."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "intervenant", "Intervenant");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère un organisme
     * 
     * @param array Valeurs de l'organisme à inserer
     * @return array Dernier identifiant d'insertion de l'intervenant sinon erreurs
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("insert", $values, "intervenant");

            $this->resultset['response'] = $this->executeRequest("insert", $request, "intervenant", "Intervenant");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour un intervenant
     * 
     * @param array Valeurs de l'intervenant à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $idInter = $values['ref_intervenant'];
            unset($values['ref_intervenant']);

            $request = $this->createQueryString("update", $values, "intervenant", "WHERE id_intervenant = ".$idInter);
            var_dump($request);
            $this->resultset['response'] = $this->executeRequest("update", $request, "intervenant", "Intervenant");
            var_dump($this->resultset['response']);
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface un intervenant
     * 
     * @param int Identifiant de l'intervenant
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($idIntervenant) 
    {
        $this->initialize();
        
        if (!empty($idIntervenant))
        {
            $request = "DELETE FROM organisme WHERE id_organ = ".$idIntervenant;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "intervenant", "Intervenant");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
}

?>
