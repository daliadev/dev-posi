<?php


/**
 * Description of IntervenantDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Inscription
require_once(ROOT.'models/inscription.php');


class InscriptionDAO extends ModelDAO
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
    /*
    public function initialize()
    {
        $this->resultset['response'] = array();
        $this->resultset['response']['errors'] = array();
    }
    */
    
    
    
    
    /**
     * selectByReferences - Récupère l'inscription correspondant à la référence de l'utilisateur et de l'intervenant passés en paramètres
     * 
     * @param int Référence de l'utilisateur
     * @param int Référence de l'intervenant
     * @return array Inscription correspondant aux références sinon erreurs
     */
    public function selectByReferences($refUtilisateur, $refIntervenant) 
    {
        $this->initialize();
        
        if(!empty($refUtilisateur) && !empty($refIntervenant))
        {
            $request = "SELECT * FROM inscription WHERE ref_user = ".$refUtilisateur." AND ref_intervenant = ".$refIntervenant;

            $this->resultset['response'] = $this->executeRequest("select", $request, "inscription", "Inscription");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByUser -  Récupère l'inscription correspondant à la référence de l'utilisateur passée en paramètre
     * 
     * @param int Référence de l'utilisateur
     * @return array Inscription correspondant à la référence sinon erreurs
     */
    public function selectByUser($refUtilisateur, $refIntervenant) 
    {
        $this->initialize();
        
        if(!empty($refUtilisateur) && !empty($refIntervenant))
        {
            $request = "SELECT * FROM inscription WHERE ref_user = ".$refUtilisateur;

            $this->resultset['response'] = $this->executeRequest("select", $request, "inscription", "Inscription");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByIntervenant - Récupère l'inscription correspondant à la référence de l'intervenant passée en paramètre
     * 
     * @param int Référence de l'intervenant
     * @return array Inscription correspondant à la référence sinon erreurs
     */
    public function selectByIntervenant($refIntervenant) 
    {
        $this->initialize();
        
        if(!empty($refIntervenant))
        {
            $request = "SELECT * FROM inscription WHERE ref_intervenant = ".$refIntervenant;

            $this->resultset['response'] = $this->executeRequest("select", $request, "inscription", "Inscription");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère une inscription
     * 
     * @param array Valeurs de l'inscription à inserer
     * @return array Dernier identifiant d'insertion sinon erreurs
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("insert", $values, "inscription");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "inscription", "Inscription");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour une inscription
     * 
     * @param array Valeurs de l'inscription à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("update", $values, "inscription");
            
            $this->resultset['response'] = $this->executeRequest("update", $request, "inscription", "Inscription");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
  
    
}

?>
