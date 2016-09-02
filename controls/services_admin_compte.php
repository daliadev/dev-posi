<?php


require_once(ROOT.'models/dao/compte_dao.php');



class ServicesAdminCompte extends Main
{
    

	private $compteDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "adminCompte";

        $this->compteDAO = new CompteDAO();
    }




    public function getAccountsList()
    {
        $resultset = $this->compteDAO->selectAll();

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
        $accountDetails['pass_admin'] = "";
        $accountDetails['droits'] = "";
        
        $resultsetCompte = $this->compteDAO->selectById($refCompte);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetCompte['response']))
        {
            $accountDetails['nom_admin'] = $resultsetCompte['response']['compte']->getNom();
            $accountDetails['droits'] = $resultsetCompte['response']['compte']->getDroits();
        }

        return $accountDetails;
    }




    public function filterAccountData(&$formData, $postData)
    {
        $dataAccount = array();
        
        /*** Récupération de la référence du compte ***/
        
        if (isset($formData['ref_account']) && !empty($formData['ref_account']))
        {
            $dataAccount['ref_account'] = $formData['ref_account'];
        }

        /*** Récupèration du nom du compte ***/

        $formData['nom_admin'] = $this->validatePostData($postData['nom_admin'], "nom_admin", "string", true, "Aucun nom n'a été saisi.", "Le nom n'est pas correctement saisi.");
        $dataAccount['nom_admin'] = $formData['nom_admin'];


        /*** Récupèration des droits du compte ***/

        if (!empty($postData['droits_cbox']))
        {
            $formData['droits_cbox'] = $postData['droits_cbox'];
                    
            if ($postData['droits_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun type de droits n'a été sélectionné");
            }
            else 
            {
                $formData['droits'] = $postData['droits_cbox'];
            }
        }
        $dataAccount['droits'] = $formData['droits'];




        /*** Récupèration du mot de passe compte ***/
        $formData['pass_admin'] = $this->validatePostData($postData['pass_admin'], "pass_admin", "string", false, "Aucun mot de passe n'a été saisi.", "Le mot de passe est incorrect.");

        $formData['pass_admin_verif'] = $this->validatePostData($postData['pass_admin_verif'], "pass_admin_verif", "string", false, "Vous n'avez pas saisi de mot de passe de confirmation.", "Le mot de passe de confirmation est incorrect.");;


        /*** Comparaison avec le mot de passe de confirmation et hashage du mot de passe ***/

        if (!empty($formData['pass_admin']) && !empty($formData['pass_admin_verif']) && $formData['pass_admin'] === $formData['pass_admin_verif'])
        {
            $dataAccount['pass_admin'] = ServicesAuth::hashPassword($formData['pass_admin']);
        }
        else 
        {
            $this->registerError("form_valid", "Le mot de passe de confirmation ne correspond pas au mot de passe saisi.");
        }


        return $dataAccount;
    }

    


    public function saveAccountData($previousMode, $dataAccount, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion du compte dans la bdd
            $resultsetAccount = $this->setAccountProperties("insert", $dataAccount);

            if (isset($resultsetAccount['response']['compte']['last_insert_id']) && !empty($resultsetAccount['response']['compte']['last_insert_id']))
            {
                $formData['ref_account'] = $resultsetAccount['response']['compte']['last_insert_id'];
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement du compte a échoué.");
            }
 
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            if (isset($dataAccount['ref_account']) && !empty($dataAccount['ref_account']))
            {
                $formData['ref_account'] = $dataAccount['ref_account'];

                // Mise à jour du compte
                $resultsetAccount = $this->setAccountProperties("update", $dataAccount);

                if (!$resultsetAccount)
                {
                    $this->registerError("form_valid", "La mise à jour du compte a échoué ou les informations n'ont pas changé.");
                }
            }
            else
            {
                $this->registerError("form_valid", "La mise à jour du compte a échoué.");
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }



    private function setAccountProperties($modeAccount, $dataAccount)
    {

        if (!empty($dataAccount) && is_array($dataAccount))
        {
            if (!empty($dataAccount['nom_admin']) && !empty($dataAccount['pass_admin']) && !empty($dataAccount['droits']))
            {
                if ($modeAccount == "insert")
                {
                    $resultset = $this->compteDAO->insert($dataAccount);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "Le compte n'a pu être inséré.");
                    }
                    
                }
                else if ($modeAccount == "update")
                { 
                    $resultset = $this->compteDAO->update($dataAccount);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['compte']['row_count']) && !empty($resultset['response']['compte']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "Le compte n'a pu être mis à jour.");
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Le nom du compte est manquant.");
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion du compte non autorisée.");
        }
            
        return false;
    }





    public function deleteAccount($refAccount)
    {
        // On commence par sélectionner le compte
        $resultsetSelect = $this->compteDAO->selectById($refAccount);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        {
            // S'il existe on le supprime
            $resultsetDelete = $this->compteDAO->delete($refAccount);
        
            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "Le compte n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Ce compte n'existe pas."); 
        }

        return false;
    }

}


?>