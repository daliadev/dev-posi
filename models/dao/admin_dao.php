<?php



/**
 * Description of OrganismeDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Organisme
//require_once(ROOT.'models/admin.php');



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
    
    
}

?>
