<?php


require_once(ROOT.'models/dao/admin_dao.php');



class ServicesAdminCompte extends Main
{
    

	private $adminDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "adminCompte";

        $this->adminDAO = new AdminDAO();
    }




    public function getAccountsList()
    {
        $resultset = $this->adminDAO->selectAll();

        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        if (!empty($resultset['response']['compte']) && count($resultset['response']['compte']) == 1)
        { 
            $compte = $resultset['response']['compte'];
            $resultset['response']['compte'] = array($compte);
        }

        return $resultset;
    }


    public function getAccountDetails($refCompte)
    {
        $accountDetails = array();
        
        $accountDetails['nom_admin'] = "";
        //$accountDetails['pass_admin'] = "";
        $accountDetails['droits'] = "";
        
        $resultsetCompte = $this->adminDAO->selectById($refCompte);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetCompte['response']))
        {
            $accountDetails['nom_admin'] = $resultsetCompte['response']['compte']->getNom();
            $accountDetails['droits'] = $resultsetCompte['response']['compte']->getDroits();
        }

        return $accountDetails;
    }

}


?>