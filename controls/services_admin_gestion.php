<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(ROOT.'controls/authentication.php');


require_once(ROOT.'models/dao/admin_dao.php');


/**
 * Description of ServicesAdminGestion
 *
 * @author Nicolas Beurion
 */
class ServicesAdminGestion extends Main
{
    
    private $adminDAO = null;
    
    
    public function initializeFormData(&$formData, $postData, $initializedData)
    {
        foreach($initializedData as $key => $type)
        {
            switch ($type)
            {
                case "select" :
            
                    if (isset($postData[$key]) && !empty($postData[$key]) && $postData[$key] != "select_cbox")
                    {
                        $formData[$key] = $postData[$key];
                    }
                    else
                    {
                        // Il n'y a pas de donnée prédéfini
                        $formData[$key] = null;
                    }
                    break;
                    
                case "text" :
                    $formData[$key] = null;
                    break;
                
                case "multi" :
                    $formData[$key] = array();
                    break;
                
                default :
                    break;
            }
        }
        
    }
    
    
    
    
    public function getFormMode($postData)
    {     
        if (isset($postData['save']) && !empty($postData['save']))
        {
            $mode = "save";
        }
        else if (isset($postData['edit']) && !empty($postData['edit']))
        {
            $mode = "edit";
        }
        else if ((isset($postData['delete']) && !empty($postData['delete']) && $postData['delete'] == "true") || (isset($postData['del']) && !empty($postData['del'])))
        {
            $mode = "delete";
        }
        else
        {
            if (isset($postData['add']) && !empty($postData['add']))
            {
                $mode = "new";
            }
            else 
            {
                $mode = "view";
            }
        }
        
        return $mode;
    }
    
    
    
    
    public function switchFormButtons(&$formButtons, $mode)
    {
        switch ($mode)
        {
            case "init":
                $formButtons['disabled'] = "";
                $formButtons['save_disabled'] = "";
                $formButtons['edit_disabled'] = "";
                $formButtons['delete_disabled'] = "";
                $formButtons['add_disabled'] = "";
                break;
            
            case "view":
                $formButtons['disabled'] = "disabled";
                $formButtons['save_disabled'] = "disabled";
                $formButtons['edit_disabled'] = "disabled";
                $formButtons['delete_disabled'] = "disabled";
                $formButtons['add_disabled'] = "";
                break;
            
            case "new":
                $formButtons['disabled'] = "";
                $formButtons['save_disabled'] = "";
                $formButtons['edit_disabled'] = "disabled";
                $formButtons['delete_disabled'] = "";
                $formButtons['add_disabled'] = "disabled";
                break;
            
            case "edit":
                $formButtons['disabled'] = "";
                $formButtons['save_disabled'] = "";
                $formButtons['edit_disabled'] = "disabled";
                $formButtons['delete_disabled'] = "";
                $formButtons['add_disabled'] = "disabled";
                break;
            
            case "save":
                $formButtons['disabled'] = "";
                $formButtons['save_disabled'] = "";
                $formButtons['edit_disabled'] = "disabled";
                $formButtons['delete_disabled'] = "";
                $formButtons['add_disabled'] = "disabled";
                break;
            
            case "delete":
                $formButtons['disabled'] = "disabled";
                $formButtons['save_disabled'] = "disabled";
                $formButtons['edit_disabled'] = "disabled";
                $formButtons['delete_disabled'] = "disabled";
                $formButtons['add_disabled'] = "disabled";
                break;
            
            default :
                break;
                
        }
    }
    
    
    
    public function authenticateAdmin($login, $pass)
    {
        $this->adminDAO = new AdminDAO();
        
        $mdp = servicesAuth::hashPassword($pass);
        
        $resultset = $this->adminDAO->authenticate($login, $mdp);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']) && $resultset['response']['auth'])
        {
            return $resultset;
        }
        
        return false;
    }
    
}

?>
