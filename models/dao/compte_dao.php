<?php



/**
 * Description of AdminDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/compte.php');



class CompteDAO extends ModelDAO
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



    
    
    
    /**
     * insert - Insère un compte
     * 
     * @param array Valeurs du compte à inserer
     * @return bool Vrai si l'insertion a fonctionné
     */
    public function insert($values) 
    {

        $this->initialize();
        
        if (!empty($values))
        {       
            $request = $this->createQueryString("insert", $values, "administrateur");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "compte", "Compte");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour un compte
     * 
     * @param array Valeurs du compte à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_account']) && !empty($values['ref_account']))
            {
                $refAccount = $values['ref_account'];
                unset($values['ref_account']);
                
                $request = $this->createQueryString("update", $values, "administrateur", "WHERE id_admin = ".$refAccount);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "compte", "Compte");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour le compte à mettre à jour.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface un compte
     * 
     * @param int Identifiant du compte
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refAccount) 
    {
        $this->initialize();
        
        if (!empty($refAccount))
        {
            $request = "DELETE FROM administrateur WHERE id_admin = ".$refAccount;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "compte", "Compte");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression du compte.");
        }

        return $this->resultset;
    }
}

?>
