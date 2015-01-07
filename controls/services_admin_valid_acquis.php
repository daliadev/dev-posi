<?php


require_once(ROOT.'models/dao/valid_acquis_dao.php');



class ServicesAdminValidAcquis extends Main
{
    
    private $validAcquisDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "adminValidAcquis";

        $this->validAcquisDAO = new ValidAcquisDAO();
    }

    
    
    public function getValidList()
    {
        $resultset = $this->validAcquisDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        if (!empty($resultset['response']['valid_acquis']) && count($resultset['response']['valid_acquis']) == 1)
        { 
            $validAcquis = $resultset['response']['valid_acquis'];
            $resultset['response']['valid_acquis'] = array($validAcquis);
        }

        return $resultset;
    }
    
    
    
    public function getValidDetails($refValidAcquis)
    {
        $validDetails = array();
        
        $validDetails['nom_degre'] = "";
        $validDetails['descript_degre'] = "";
        
        $resultsetValidAcquis = $this->validAcquisDAO->selectById($refValidAcquis);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetValidAcquis['response']))
        {
            $validDetails['nom_valid_acquis'] = $resultsetValidAcquis['response']['valid_acquis']->getNom();
            $validDetails['descript_valid_acquis'] = $resultsetValidAcquis['response']['valid_acquis']->getDescription();
        }

        return $validDetails;
    }




    public function filterValidData(&$formData, $postData)
    {
        $dataValidAcquis = array();
        
        /*** Récupération de la référence du degre ***/
        
        if (isset($formData['ref_valid_acquis']) && !empty($formData['ref_valid_acquis']))
        {
            $dataValidAcquis['ref_valid_acquis'] = $formData['ref_valid_acquis'];
        }

        /*** Récupèration du nom du degre ***/

        $formData['nom_valid_acquis'] = $this->validatePostData($postData['nom_valid_acquis'], "nom_valid_acquis", "string", true, "Aucun nom n'a été saisi.", "Le nom n'est pas correctement saisi.");
        $dataValidAcquis['nom_valid_acquis'] = $formData['nom_valid_acquis'];


        /*** Récupèration de la description du degre ***/

        $formData['descript_valid_acquis'] = $this->validatePostData($postData['descript_valid_acquis'], "descript_valid_acquis", "string", false, "Aucun description n'a été saisi.", "La description est incorrecte.");
        $dataValidAcquis['descript_valid_acquis'] = $formData['descript_valid_acquis'];

        
        return $dataValidAcquis;
    }

    


    public function setValidProperties($previousMode, $dataValidAcquis, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion du degre dans la bdd
            $resultsetValidAcquis = $this->setValid("insert", $dataValidAcquis);

            if (isset($resultsetValidAcquis['response']['valid_acquis']['last_insert_id']) && !empty($resultsetValidAcquis['response']['valid_acquis']['last_insert_id']))
            {
                $formData['ref_valid_acquis'] = $resultsetValidAcquis['response']['valid_acquis']['last_insert_id'];
                $dataValidAcquis['ref_valid_acquis'] = $formData['ref_valid_acquis'];
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement du niveau a échoué.");
            }
 
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            if (isset($dataValidAcquis['ref_valid_acquis']) && !empty($dataValidAcquis['ref_valid_acquis']))
            {
                $formData['ref_valid_acquis'] = $dataValidAcquis['ref_valid_acquis'];

                // Mise à jour du degré
                $resultsetValidAcquis = $this->setValid("update", $dataValidAcquis);

                if (!$resultsetValidAcquis)
                {
                    $this->registerError("form_valid", "La mise à jour du niveau a échouée.");
                }
            }
            else
            {
                $this->registerError("form_valid", "La mise à jour du niveau a échouée.");
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }




    public function setValid($modeValidAcquis, $dataValidAcquis)
    {

        if (!empty($dataValidAcquis) && is_array($dataValidAcquis))
        {
            if (!empty($dataValidAcquis['nom_degre']))
            {
                if ($modeValidAcquis == "insert")
                {
                    $resultset = $this->validAcquisDAO->insert($dataValidAcquis);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "Le niveau n'a pu être inséré.");
                    }
                    
                }
                else if ($modeValidAcquis == "update")
                { 
                    $resultset = $this->validAcquisDAO->update($dataValidAcquis);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['valid_acquis']['row_count']) && !empty($resultset['response']['valid_acquis']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "Le niveau n'a pu être mis à jour.");
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Le nom du niveau est manquant.");
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion du niveau non autorisée.");
        }
            
        return false;
    }
    
    
    
    public function deleteValid($refValidAcquis)
    {
        // On commence par sélectionner le degré
        $resultsetSelect = $this->validAcquisDAO->selectById($refValidAcquis);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->validAcquisDAO->delete($refValidAcquis);
        
            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "Le niveau n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Ce niveau n'existe pas."); 
        }

        return false;
    }

}


?>
