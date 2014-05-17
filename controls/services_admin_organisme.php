<?php


require_once(ROOT.'models/dao/organisme_dao.php');
// require_once(ROOT.'models/dao/niveau_etudes_dao.php');



class ServicesAdminUtilisateur extends Main
{

	private $organismeDAO = null;
    // private $niveauEtudesDAO = null;
    
    
    public function __construct() 
    {
        $this->controllerName = "adminUtilisateur";

        $this->organismeDAO = new OrganismeDAO();
        // $this->niveauEtudesDAO = new NiveauEtudesDAO();
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

        $resultset = $this->organismeDAO->selectById($organDetails);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            //$organDetails['ref_user'] = $resultset['response']['organisme']->getId();
            $organDetails['nom_organ'] = $resultset['response']['organisme']->getNom();
            $organDetails['code_postal_organ'] = $resultset['response']['organisme']->getCodePostal();
            $organDetails['tel_organ'] = $resultset['response']['organisme']->getTelephone();
        }

        return $organDetails;
    }





    /* requêtes niveaux d'études */
    /*
    public function getNiveauxEtudes()
    {
        $resultset = $this->niveauEtudesDAO->selectAll();
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            return $resultset;
        }

        return false;
    }
    */




    public function filterOrganData(&$formData, $postData)
    {
        $dataUser = array();
        
        /*** Récupération de la référence de l'utilisateur ***/
        
        if (isset($formData['ref_user']) && !empty($formData['ref_user']))
        {
            $dataUser['ref_user'] = $formData['ref_user'];
        }
        
        // Formatage du nom de l'utilisateur
        $formData['nom_user'] = $this->validatePostData($_POST['nom_user'], "nom_user", "string", true, "Aucun nom n'a été saisi", "Le nom n'est pas correctement saisi.");
        $dataUser['nom_user'] = $formData['nom_user'];
        
        // Formatage du prénom de l'utilisateur
        $formData['prenom_user'] = $this->validatePostData($_POST['prenom_user'], "prenom_user", "string", false, "Aucun prénom n'a été saisi", "Le prénom n'est pas correctement saisi.");
        $dataUser['prenom_user'] = $formData['prenom_user'];
        
        // Formatage du prénom de l'utilisateur
        $formData['date_naiss_user'] = $this->validatePostData($_POST['date_naiss_user'], "date_naiss_user", "date", false, "Aucune date de naissance n'a été saisie", "La date de naissance n'est pas correctement saisie.");
        $dataUser['date_naiss_user'] = Tools::toggleDate($formData['date_naiss_user'], "us");

        //$this->formData['date_naiss_user'] = $this->validatePostData($_POST['date_naiss_user'], "date_naiss_user", "date", true, "La date de naissance de l'utilisateur est incorrecte.", "La date de naissance de l'utilisateur n'a pas été saisi.");

        // Formatage de la sélection du niveau d'études de l'utilisateur
        if (!empty($_POST['ref_niveau_cbox']))
        {
            $this->formData['ref_niveau_cbox'] = $_POST['ref_niveau_cbox'];
                    
            if ($_POST['ref_niveau_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun niveau d'études n'a été sélectionné");
            }
            else 
            {
                $this->formData['ref_niveau'] = $_POST['ref_niveau_cbox'];
            }
        }
        $dataUser['ref_niveau'] = $this->formData['ref_niveau'];


        return $dataUser;
    }




    
    public function setOrganProperties($previousMode, $dataOrgan, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion de l'utilisateur dans la bdd
            $resultsetUser = $this->setUtilisateur("insert", $dataUser);

            // Traitement des erreurs de la requête et récupération de la référence
            if ($resultsetUser && isset($resultsetUser['response']['utilisateur']['last_insert_id']) && !empty($resultsetUser['response']['utilisateur']['last_insert_id']))
            {
                $formData['ref_user'] = $resultsetUser['response']['utilisateur']['last_insert_id'];
                //$dataUser['ref_user'] = $formData['ref_user'];
                $this->registerSuccess("L'utilisateur a été enregistrée.");
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement de l'utilisateur a échoué.");
            }
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            
            if (isset($dataUser['ref_user']) && !empty($dataUser['ref_user']))
            {
                $formData['ref_user'] = $dataUser['ref_user'];

                // Mise à jour de la l'utilisateur
                $resultsetUser = $this->setUtilisateur("update", $dataUser);

                // Traitement des erreurs de la requête
                if ($resultsetUser['response'])
                {
                    $this->registerSuccess("L'utilisateur a été mise à jour.");
                }
                else
                {
                    $this->registerError("form_valid", "La mise à jour de l'utilisateur a échoué.");
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
        if (!empty($dataUser) && is_array($dataUser))
        {
            if (!empty($dataUser['nom_user']) && !empty($dataUser['prenom_user']) && !empty($dataUser['date_naiss_user']))
            {
                if ($modeUser == "insert")
                {
                    $resultset = $this->organismeDAO->insert($dataUser);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "L'utilisateur n'a pu être inséré.");
                    }
                    
                }
                else if ($modeUser == "update")
                {
                    $resultset = $this->organismeDAO->update($dataUser);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['utilisateur']['row_count']) && !empty($resultset['response']['utilisateur']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "L'utilisateur n'a pu être mis à jour.");
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
            $this->registerError("form_request", "Insertion de l'utilisateur non autorisée.");
        }
            
        return false;
    }






    public function deleteOrganisme($refOrgan)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->organismeDAO->selectById($refUser);

        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->organismeDAO->delete($refUser);

            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "L'utilisateur n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Cet utilisateur n'existe pas."); 
        }

        return false;
    }
}


?>