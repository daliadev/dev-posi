<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(ROOT.'controls/authentication.php');


require_once(ROOT.'models/dao/compte_dao.php');


/**
 * Description of ServicesAdminGestion
 *
 * @author Nicolas Beurion
 */
class ServicesAdminGestion extends Main
{
    
    private $compteDAO = null;
    
    
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
    
    
    
    
    public function switchFormButtons(&$formData, $mode)
    {
        switch ($mode)
        {
            case "init":
                $formData['disabled'] = "";
                $formData['save_disabled'] = "";
                $formData['edit_disabled'] = "";
                $formData['delete_disabled'] = "";
                $formData['add_disabled'] = "";
                break;
            
            case "view":
                $formData['disabled'] = "disabled";
                $formData['save_disabled'] = "disabled";
                $formData['edit_disabled'] = "disabled";
                $formData['delete_disabled'] = "disabled";
                $formData['add_disabled'] = "";
                break;
            
            case "new":
                $formData['disabled'] = "";
                $formData['save_disabled'] = "";
                $formData['edit_disabled'] = "disabled";
                $formData['delete_disabled'] = "disabled";
                $formData['add_disabled'] = "disabled";
                break;
            
            case "edit":
                $formData['disabled'] = "";
                $formData['save_disabled'] = "";
                $formData['edit_disabled'] = "disabled";
                $formData['delete_disabled'] = "";
                $formData['add_disabled'] = "disabled";
                break;
            
            case "save":
                $formData['disabled'] = "";
                $formData['save_disabled'] = "";
                $formData['edit_disabled'] = "disabled";
                $formData['delete_disabled'] = "";
                $formData['add_disabled'] = "disabled";
                break;
            
            case "delete":
                $formData['disabled'] = "disabled";
                $formData['save_disabled'] = "disabled";
                $formData['edit_disabled'] = "disabled";
                $formData['delete_disabled'] = "disabled";
                $formData['add_disabled'] = "disabled";
                break;
            
            default :
                break;
                
        }
    }
    
    
    
    public function authenticateAdmin($login, $pass)
    {
        $this->compteDAO = new CompteDAO();
        
        $mdp = Config::hashPassword($pass);
        
        $resultset = $this->compteDAO->authenticate($login, $mdp);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']) && $resultset['response']['auth'])
        {
            return $resultset;
        }
        
        return false;
    }
    
}

?>
