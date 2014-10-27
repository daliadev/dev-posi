<?php



// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/organisme.php');



class OrganismeDAO extends ModelDAO
{
   
    
    
    public function __construct()
    {
         $this->initialize();
    }
    
    

    /**
     * Identification du code organisme
     * 
     * @param string $codeOrganisme: le code organisme encrypté à authentifier.
     * 
     * @return boolean $codeOrganisme est correct ou non 
     */
    /*
    public function authenticate($codeOrganisme)
    {
        $this->initialize();
        
        $this->resultset['response']['auth'] = false;
        
        if (!empty($codeOrganisme))
        {           
            $this->resultset['response']['ref_code_organisme'] = array();
            
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement("SELECT * FROM code_organisme WHERE code_organ = '".$codeOrganisme."'");
            
                // Execution de la requête préparée
                $this->executeStatement();

                // resultat de l'organisme trouvé
                if ($this->getRowCount() > 0)
                {
                    $this->resultset['response']['auth'] = true;
                    
                    // Selection du code organisme correspondant
                    $tabChamps = $this->getStatementFetch();

                    $this->resultset['response']['ref_code_organisme'] = $tabChamps['id_code_organ'];
                    //$this->resultset['code_organisme'] = $tabChamps['code_organ'];
                }
                
                // Fermeture de la requête préparée et fermeture de la connection
                $this->closeStatement();
                $this->disconnectDB();
            } 
            catch (PDOException $e)
            {
                // Erreur de connection ou probleme avec la requête préparée
                $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
            }
        }
        
        return $this->resultset;


    }
    */
    
    
    
    
    /**
     * selectAll - Retourne la liste de tous les organismes
     * 
     * @return array Liste d'objets "Organisme"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM organisme ORDER BY nom_organ ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");

        return $this->resultset;

    }
    
    
    
    
    
    /**
     * selectById - Récupère l'organisme correspondant à l'identifiant
     * 
     * @param int Identifiant organisme
     * @return array Organisme correspondant à l'identifiant sinon erreurs
     */
    public function selectById($id_organisme) 
    {
        $this->initialize();
        
        if (!empty($id_organisme))
        {
            $request = "SELECT * FROM organisme WHERE id_organ = ".$id_organisme;

            $this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByName - Récupère l'organisme grâce à son nom
     * 
     * @param int Nom de l'organisme
     * @return array Organisme correspondant au nom sinon erreurs
     */
    public function selectByName($nameOrganisme) 
    {
        $this->initialize();
        
        if(!empty($nameOrganisme))
        {
            $nameOrganisme = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $nameOrganisme));
            
            $request = "SELECT * FROM organisme 
                WHERE nom_organ = '".$nameOrganisme."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    /**
     * selectByCodeInterne - Récupère l'organisme grâce à son code interne
     * 
     * @param string Chaîne de caractères correcpondant au numéro interne
     * @return array Organisme correspondant sinon erreurs
     */
    public function selectByCodeInterne($numOrganisme) 
    {
        $this->initialize();
        
        if(!empty($numOrganisme))
        {
            $request = "SELECT * FROM organisme WHERE numero_interne = '".$numOrganisme."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "organisme", "Organisme");
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
     * @return array Dernier identifiant d'insertion sinon erreurs
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("insert", $values, "organisme");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "organisme", "Organisme");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour un organisme
     * 
     * @param array Valeurs de l'organisme à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values) && isset($values['ref_organ']) && !empty($values['ref_organ']))
        {
            $refOrgan = $values['ref_organ'];
            unset($values['ref_organ']);

            $request = $this->createQueryString("update", $values, "organisme", "WHERE id_organ = ".$refOrgan);

            $this->resultset['response'] = $this->executeRequest("update", $request, "organisme", "Organisme");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }

    
    
    
    
    /**
     * delete - Efface un organisme
     * 
     * @param int Identifiant de l'organisme
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($idOrganisme) 
    {
        $this->initialize();
        
        if (!empty($idOrganisme))
        {
            $request = "DELETE FROM organisme WHERE id_organ = ".$idOrganisme;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "organisme", "Organisme");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
}

?>
