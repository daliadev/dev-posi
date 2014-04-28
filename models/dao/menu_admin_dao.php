<?php


/**
 * Description de CompetenceDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Degre
require_once(ROOT.'models/menu_admin.php');



class MenuAdminDAO extends ModelDAO
{

    
    
    /**
     * selectAll - Retourne la liste de toutes les éléments du menu
     * 
     * @return array Liste d'objets "Competence"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM admin_menu ORDER BY code_menu ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "admin_menu", "MenuAdmin");
        
        return $this->resultset;
    }
    
    
}

?>
