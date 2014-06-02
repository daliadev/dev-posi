<?php

/**
 * 
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_admin_stat.php');




class ServicesStatistique extends Main
{

    /*
    private $servicesAdminStat = null;
    //private $organismeDAO = null;
    

    
    
    public function __construct() 
    {
        $this->controllerName = "statistique";
        
        $this->servicesAdminStat = new ServicesAdminStat();
    }
    */
    

    
    /**
     * statistique - Gére la validation du formulaire de gestion des degrés d'aptitude avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (le code d'identification de l'organisme)
     */

    /*
    public function stat($requestParams = array())
    {

        ServicesAuth::checkAuthentication("admin");
		
        $this->initialize();

			$this->url = SERVER_URL."statistique/stat";
            $this->setTemplate("template_page");
            $this->render("stat");
        

    }
    */
    
}


?>
