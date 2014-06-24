<?php


require_once(ROOT.'models/dao/organisme_dao.php');



class ServicesAdminOrganisme extends Main
{

	private $organismeDAO = null;
    
    
    public function __construct() 
    {
        $this->controllerName = "adminOrganisme";

        $this->organismeDAO = new OrganismeDAO();
    }

    
    
    

    public function getOrganismes()
    {
        $resultset = $this->organismeDAO->selectAll();
        
        if (!$this->filterDataErrors($resultset['response']))
        {

            if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
            { 
                $organisme = $resultset['response']['organisme'];
                $resultset['response']['organisme'] = array($organisme);
            }

            return $resultset;
        }
        
        return false;
    }





    public function getOrganisme($refOrgan)
    {
        $resultset = $this->organismeDAO->selectById($refOrgan);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
            { 
                $organisme = $resultset['response']['organisme'];
                $resultset['response']['organisme'] = array($organisme);
            }

            return $resultset;
        }
        
        return false;

    }





    public function getOrganDetails($refOrgan)
    {
        $organDetails = array();
        
        $organDetails['nom_organ'] = "";
        $organDetails['code_postal_organ'] = "";
        $organDetails['tel_organ'] = "";
        $organDetails['nbre_posi_total'] = "";
        $organDetails['nbre_posi_max'] = "";

        $resultset = $this->organismeDAO->selectById($refOrgan);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            $organDetails['nom_organ'] = $resultset['response']['organisme']->getNom();
            $organDetails['code_postal_organ'] = $resultset['response']['organisme']->getCodePostal();
            $organDetails['tel_organ'] = $resultset['response']['organisme']->getTelephone();
            $organDetails['nbre_posi_total'] = $resultset['response']['organisme']->getNbrePosiTotal();
            $organDetails['nbre_posi_max'] = $resultset['response']['organisme']->getNbrePosiMax();
        }

        return $organDetails;
    }




    public function filterOrganData(&$formData, $postData)
    {
        $dataOrgan = array();
        
        /*** Récupération de la référence de l'organisme ***/
        
        if (isset($formData['ref_organ']) && !empty($formData['ref_organ']))
        {
            $dataOrgan['ref_organ'] = $formData['ref_organ'];
        }
        
        // Formatage du nom de l'organisme
        $formData['nom_organ'] = $this->validatePostData($_POST['nom_organ'], "nom_organ", "string", true, "Aucun nom n'a été saisi", "Le nom n'est pas correctement saisi.");
        $dataOrgan['nom_organ'] = $formData['nom_organ'];
        
        // Formatage du code postal de l'organisme
        $formData['code_postal_organ'] = $this->validatePostData($_POST['code_postal_organ'], "code_postal_organ", "integer", true, "Aucun code postal n'est saisi", "Le code postal n'est pas correctement saisi.");
        $dataOrgan['code_postal_organ'] = $formData['code_postal_organ'];
        
        // Formatage du téléphone de l'organisme
        $formData['tel_organ'] = $this->validatePostData($_POST['tel_organ'], "tel_organ", "integer", true, "Aucun numéro de téléphone n'a été saisi", "Le numéro de téléphone n'est pas correctement saisi.");
        $dataOrgan['tel_organ'] = $formData['tel_organ'];

        // Formatage du nombre de positionnements maximum
        $formData['nbre_posi_max'] = $this->validatePostData($_POST['nbre_posi_max'], "nbre_posi_max", "integer", false, "Aucun nombre de positionnements n'a été saisi", "Le nombre de positionnements n'est pas correctement saisi.");
        $dataOrgan['nbre_posi_max'] = $formData['nbre_posi_max'];


        return $dataOrgan;
    }




    
    public function setOrganProperties($previousMode, $dataOrgan, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion de l'organisme dans la bdd
            $resultsetOrgan = $this->setOrganisme("insert", $dataOrgan);

            // Traitement des erreurs de la requête et récupération de la référence
            if ($resultsetOrgan && isset($resultsetOrgan['response']['organisme']['last_insert_id']) && !empty($resultsetOrgan['response']['organisme']['last_insert_id']))
            {
                $formData['ref_organ'] = $resultsetOrgan['response']['organisme']['last_insert_id'];

                $this->registerSuccess("L'organisme a été enregistré.");
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement de l'organisme a échoué.");
            }
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            
            if (isset($dataOrgan['ref_organ']) && !empty($dataOrgan['ref_organ']))
            {
                $formData['ref_organ'] = $dataOrgan['ref_organ'];

                // Mise à jour de la l'organisme
                $resultsetOrgan = $this->setOrganisme("update", $dataOrgan);

                // Traitement des erreurs de la requête
                if ($resultsetOrgan['response'])
                {
                    $this->registerSuccess("L'organisme a été mise à jour.");
                }
                else
                {
                    $this->registerError("form_valid", "La mise à jour de l'organisme a échoué.");
                }
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }





    public function setOrganisme($modeOrgan, $dataOrgan)
    {
        if (!empty($dataOrgan) && is_array($dataOrgan))
        {
            if (!empty($dataOrgan['nom_organ']) && !empty($dataOrgan['code_postal_organ']) && !empty($dataOrgan['tel_organ']))
            {
                if ($modeOrgan == "insert")
                {
                    $resultset = $this->organismeDAO->insert($dataOrgan);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "L'organisme n'a pu être inséré.");
                    }
                    
                }
                else if ($modeOrgan == "update")
                {
                    $resultset = $this->organismeDAO->update($dataOrgan);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['organisme']['row_count']) && !empty($resultset['response']['organisme']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "L'organisme n'a pu être mis à jour.");
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Les valeurs obligatoires des champs sont manquantes.");
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'organisme non autorisée.");
        }
            
        return false;
    }






    public function deleteOrganisme($refOrgan)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->organismeDAO->selectById($refOrgan);

        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->organismeDAO->delete($refOrgan);

            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "L'organisme n'a pas pu être supprimé.");
            }
        }
        else
        {
           $this->registerError("form_request", "Cet organisme n'existe pas."); 
        }

        return false;
    }
}


?>