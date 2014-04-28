<?php


require_once(ROOT.'models/dao/degre_dao.php');



class ServicesAdminDegre extends Main
{
    
    private $degreDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "adminDegre";

        $this->degreDAO = new DegreDAO();
    }

    
    
    public function getDegresList()
    {
        $resultset = $this->degreDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        if (!empty($resultset['response']['degre']) && count($resultset['response']['degre']) == 1)
        { 
            $degre = $resultset['response']['degre'];
            $resultset['response']['degre'] = array($degre);
        }

        return $resultset;
    }
    
    /*
    public function getDegre($refDegre)
    {
        $resultset = $this->degreDAO->selectById($refDegre);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    */
    
    public function getDegreDetails($refDegre)
    {
        $degreDetails = array();
        
        $degreDetails['nom_degre'] = "";
        $degreDetails['descript_degre'] = "";
        
        $resultsetDegre = $this->degreDAO->selectById($refDegre);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetDegre['response']))
        {
            $degreDetails['nom_degre'] = $resultsetDegre['response']['degre']->getNom();
            $degreDetails['descript_degre'] = $resultsetDegre['response']['degre']->getDescription();
        }

        return $degreDetails;
    }




    public function filterDegreData(&$formData, $postData)
    {
        $dataDegre = array();
        
        /*** Récupération de la référence de la question ***/
        
        if (isset($formData['ref_degre']) && !empty($formData['ref_degre']))
        {
            $dataDegre['ref_degre'] = $formData['ref_degre'];
        }

        /*** Récupèration du nom du degre ***/

        $formData['nom_degre'] = $this->validatePostData($postData['nom_degre'], "nom_degre", "string", true, "Aucun nom n'a été saisi.", "Le nom n'est pas correctement saisi.");
        $dataDegre['nom_degre'] = $formData['nom_degre'];


        /*** Récupèration de la description du degre ***/

        $formData['descript_degre'] = $this->validatePostData($postData['descript_degre'], "descript_degre", "string", false, "Aucun description n'a été saisi.", "La description est incorrecte.");
        $dataDegre['descript_degre'] = $formData['descript_degre'];

        
        return $dataDegre;
    }

    


    public function setDegreProperties($previousMode, $dataDegre, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion du degre dans la bdd
            $resultsetDegre = $this->setDegre("insert", $dataDegre);

            if (isset($resultsetDegre['response']['degre']['last_insert_id']) && !empty($resultsetDegre['response']['degre']['last_insert_id']))
            {
                $formData['ref_degre'] = $resultsetDegre['response']['degre']['last_insert_id'];
                $dataDegre['ref_degre'] = $formData['ref_degre'];
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement du degré a échoué.");
            }
 
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            if (isset($dataDegre['ref_degre']) && !empty($dataDegre['ref_degre']))
            {
                $formData['ref_degre'] = $dataDegre['ref_degre'];

                // Mise à jour du degré
                $resultsetDegre = $this->setDegre("update", $dataDegre);

                if (!$resultsetDegre)
                {
                    $this->registerError("form_valid", "La mise à jour du degré a échouée.");
                }
            }
            else
            {
                $this->registerError("form_valid", "La mise à jour du degré a échouée.");
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }




    public function setDegre($modeDegre, $dataDegre)
    {
        if (!empty($dataDegre) && is_array($dataDegre))
        {
            if (!empty($dataDegre['nom_degre']))
            {
                if ($modeDegre == "insert")
                {
                    $resultset = $this->degreDAO->insert($dataDegre);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "Le degré n'a pu être inséré.");
                    }
                    
                }
                else if ($modeDegre == "update")
                { 
                    $resultset = $this->degreDAO->update($dataDegre);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['degre']['row_count']) && !empty($resultset['response']['degre']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "Le degré n'a pu être mis à jour.");
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Le nom du degre est manquant.");
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion du degré non autorisée.");
        }
            
        return false;
    }
    
    
    
    public function deleteDegre($refDegre)
    {
        // On commence par sélectionner le degré
        $resultsetSelect = $this->degreDAO->selectById($refDegre);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->degreDAO->delete($refDegre);
        
            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "Le degré n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Ce degré n'existe pas."); 
        }

        return false;
    }

}


?>
