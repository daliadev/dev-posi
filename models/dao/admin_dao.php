<?php



/**
 * Description of AdminDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/compte.php');



class AdminDAO extends ModelDAO
{
   
    
    
    public function __construct()
    {
         $this->initialize();
    }
    

    
    public function authenticate($login, $mdp)
    {
        $this->initialize();
        
        $this->resultset['response']['auth'] = false;
        
        if (!empty($login) && !empty($mdp))
        {           
            //$this->resultset['response']['ref_code_organisme'] = array();
            
            try
            {
                // Connection à la base de données
                $this->connectDB();

                // Création de l'appel à la requête préparée
                $this->prepareStatement("SELECT * FROM administrateur WHERE nom_admin = '".$login."' AND pass_admin = '".$mdp."'");
            
                // Execution de la requête préparée
                $this->executeStatement();

                // resultat de l'organisme trouvé
                if ($this->getRowCount() > 0)
                {
                    $this->resultset['response']['auth'] = true;
                    
                    // Selection du code organisme correspondant
                    $tabChamps = $this->getStatementFetch();
                    
                    $this->resultset['response']['nom'] = $tabChamps['nom_admin'];
                    $this->resultset['response']['droit'] = $tabChamps['droits'];
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
    



    /**
     * selectAll - Retourne la liste de tous les comptes administrateur.
     * 
     * @return array Liste d'objets "Compte".
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT id_admin, nom_admin, droits FROM administrateur ORDER BY id_admin ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "compte", "Compte");

        return $this->resultset;
    }
    


    /**
     * selectById - Récupère le compte administrateur correspondant à l'identifiant.
     * 
     * @param int Identifiant du compte.
     * @return array Compte correspondant à l'identifiant sinon erreurs.
     */
    public function selectById($idCompte) 
    {
        $this->initialize();
        
        if (!empty($idCompte))
        {
            $request = "SELECT * FROM administrateur WHERE id_admin = ".$idCompte;

            $this->resultset['response'] = $this->executeRequest("select", $request, "compte", "Compte");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }


}

?>
