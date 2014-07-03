<?php


/**
 * Description of IntervenantDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Utilisateur
require_once(ROOT.'models/utilisateur.php');

require_once(ROOT.'models/inscription.php');



class UtilisateurDAO extends ModelDAO
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
     * selectAll - Retourne la liste de tous les utilisateurs
     * 
     * @return array Liste d'objets "Utilisateur"
     */
    public function selectAll() 
    {
        $this->initialize();
        
        $request = "SELECT * FROM utilisateur";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");

        return $this->resultset;
    }
 
    
    
    
    
    /**
     * selectById - Récupère l'utilisateur correspondant à l'identifiant
     * 
     * @param int Identifiant utilisateur
     * @return array Utilisateur correspondant à l'identifiant sinon erreurs
     */
    public function selectById($idUtilisateur) 
    {
        $this->initialize();
        
        if (!empty($idUtilisateur))
        {
            $request = "SELECT * FROM utilisateur WHERE id_user = ".$idUtilisateur;

            $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }

    
    
    
    
    /**
     * selectByName - Récupère l'utilisateur grâce à son nom
     * 
     * @param int Nom de l'utilisateur
     * @return array Utilisateur correspondant au nom sinon erreurs
     */
    public function selectByName($nomUtilisateur) 
    {
        $this->initialize();
        
        if(!empty($nomUtilisateur))
        {
            $request = "SELECT * FROM utilisateur WHERE nom_user = '".$nomUtilisateur."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }

    /**
     * selectByDateNaissance - Récupère l'utilisateur grâce à sa date de naissance
     * 
     * @param int Date de naissance de l'utilisateur
     * @return array Utilisateur correspondant sinon erreurs
     */
    public function selectByDateNaissance($dateNaiss) 
    {
        $this->initialize();
        
        if(!empty($dateNaiss))
        {
            $request = "SELECT * FROM utilisateur ";
            $request .= "WHERE date_naiss_user = '".$dateNaiss."' ";

            $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByOrganisme - Récupère tous les utilisateurs correspondant à un organisme
     * 
     * @param int Référence de l'organisme
     * @return array Utilisateur(s) correspondant à l'organisme
     */
    public function selectByOrganisme($refOrganisme) 
    {
        $this->initialize();
        
        if (!empty($refOrganisme))
        {
            $request = "SELECT id_user, nom_user, prenom_user, nbre_sessions_accomplies FROM intervenant, inscription, utilisateur ";
            $request .= "WHERE intervenant.ref_organ = ".$refOrganisme." ";
            $request .= "AND inscription.ref_intervenant = intervenant.id_intervenant ";
            $request .= "AND inscription.ref_user = utilisateur.id_user ";
            $request .= "GROUP BY id_user ORDER BY nom_user ASC";
            
            $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");

        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    

    /**
     * selectByUser - Récupère l'utilisateur par homonyme et date de naissance pour permettre d'identifier un unique utilisateur
     * 
     * @param string Nom de l'utilisateur
     * @param string Prenom de l'utilisateur
     * @param date Date de naissance de l'utilisateur
     * @return array Utilisateur correspondant au nom, prénom et date de naissance sinon erreurs
     */
    public function selectByUser($nomUser, $prenomUser, $dateNaissUser) 
    {
        $this->initialize();
        
        if (!empty($nomUser) && !empty($prenomUser) && !empty($dateNaissUser))
        {            
            $nomUser = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $nomUser));
            $prenomUser = strtoupper(preg_replace("`(\s|-|_|\/)*`", "", $prenomUser));
                
            $request = "SELECT * FROM utilisateur ";
            $request .= "WHERE REPLACE(UPPER(nom_user),' ','') LIKE '".$nomUser."' ";
            $request .= "AND REPLACE(UPPER(prenom_user),' ','') LIKE '".$prenomUser."' ";
            $request .= "AND date_naiss_user = '".$dateNaissUser."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère un utilisateur
     * 
     * @param array Valeurs de l'utilisateur à inserer
     * @return array Dernier identifiant d'insertion sinon erreurs
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("insert", $values, "utilisateur");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }


    
    
    
    /**
     * update - Met à jour un utilisateur
     * 
     * @param array Valeurs de l'utilisateur à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        

        if (!empty($values) && isset($values['ref_user']) && !empty($values['ref_user']))
        {
            $refUser = $values['ref_user'];
            unset($values['ref_user']);
            
            $request = $this->createQueryString("update", $values, "utilisateur", "WHERE id_user = ".$refUser);
            
            $this->resultset['response'] = $this->executeRequest("update", $request, "utilisateur", "Utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }   
        
        return $this->resultset;
    }





    /**
     * delete - Efface un utilisateur avec toutes ses dépendances (inscription, sessions, résultats)
     * 
     * @param int Identifiant de l'utilisateur
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refUser) 
    {
        $this->initialize();
        
        if (!empty($refUser))
        {
            $request = "DELETE FROM utilisateur WHERE id_user = ".$refUser;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "utilisateur");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de l'utilisateur'.");
        }
        
        return $this->resultset;
    }
    
}

?>
