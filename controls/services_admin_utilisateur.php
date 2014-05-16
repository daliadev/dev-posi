<?php


require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/niveau_etudes_dao.php');



class ServicesAdminUtilisateur extends Main
{

	private $utilisateurDAO = null;
    private $niveauEtudesDAO = null;
    
    
    public function __construct() 
    {
        $this->controllerName = "adminUtilisateur";

        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
    }

    
    
    

    public function getUtilisateurs()
    {
        $resultset = $this->utilisateurDAO->selectAll();
        
        if (!$this->filterDataErrors($resultset['response']))
        {

            if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
            { 
                $utilisateur = $resultset['response']['utilisateur'];
                $resultset['response']['utilisateur'] = array($utilisateur);
            }

            return $resultset;
        }
        
        return false;
    }





    public function getUtilisateur($refUser)
    {
        $resultset = $this->utilisateurDAO->selectById($refUser);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            return $resultset;
        }

        return false;
    }





    public function getUserDetails($refUser)
    {
        $userDetails = array();
        
        //$userDetails['ref_user'] = "";
        $userDetails['nom_user'] = "";
        $userDetails['prenom_user'] = "";
        $userDetails['date_naiss_user'] = "";

        $resultset = $this->utilisateurDAO->selectById($refUser);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            //$userDetails['ref_user'] = $resultset['response']['utilisateur']->getId();
            $userDetails['nom_user'] = $resultset['response']['utilisateur']->getNom();
            $userDetails['prenom_user'] = $resultset['response']['utilisateur']->getPrenom();
            $userDetails['date_naiss_user'] = Tools::toggleDate($resultset['response']['utilisateur']->getDateNaiss(), 0, 10); //$resultset['response']['utilisateur']->getDateNaiss();
            $userDetails['ref_niveau'] = $resultset['response']['utilisateur']->getRefNiveau();
            $userDetails['nbre_sessions_accomplies'] = $resultset['response']['utilisateur']->getSessionsAccomplies();
        }

        return $userDetails;
    }





    /* requêtes niveaux d'études */
    
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





    public function filterUserData(&$formData, $postData)
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
        $formData['date_naiss_user'] = $this->validatePostData($_POST['date_naiss_user'], "date_naiss_user", "string", false, "Aucune date de naissance n'a été saisie", "La date de naissance n'est pas correctement saisie.");
        $dataUser['date_naiss_user'] = $formData['date_naiss_user'];
        


        // Formatage du prénom de l'utilisateur
        //$formData['nbre_sessions_accomplies'] = $this->validatePostData($_POST['nbre_sessions_accomplies'], "nbre_sessions_accomplies", "string", false, "Aucun prénom n'a été saisi", "Le prénom n'est pas correctement saisi.");
        //$dataUser['nbre_sessions_accomplies'] = $formData['nbre_sessions_accomplies'];
        

        /*
        // Formatage du type de lien de la catégorie
        if (isset($_POST['type_lien_cat']))
        {
            $formData['type_lien_cat'] = "dynamic";
            $dataUser['type_lien_cat'] = "dynamic";
        }
        else 
        {
            $formData['type_lien_cat'] = "static";
            $dataUser['type_lien_cat'] = "static";
        }
        */

        return $dataUser;
    }




    /*
    public function setUserProperties($previousMode, $dataCategorie, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion de la catégorie dans la bdd
            $resultsetCategorie = $this->setCategorie("insert", $dataCategorie);
                    
            // Traitement des erreurs de la requête
            if ($resultsetCategorie['response'])
            {
                $formData['code_cat'] = $resultsetCategorie['response']['categorie']['code_cat'];
                $dataCategorie['code_cat'] = $formData['code_cat'];
                $this->registerSuccess("La catégorie a été enregistrée.");
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement de la catégorie a échouée.");
            }
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            if (isset($dataCategorie['code_cat']) && !empty($dataCategorie['code_cat']))
            {
                $formData['code_cat'] = $dataCategorie['code_cat'];

                // Mise à jour de la catégorie
                $resultsetCategorie = $this->setCategorie("update", $dataCategorie);

                // Traitement des erreurs de la requête
                if ($resultsetCategorie['response'])
                {
                    $this->registerSuccess("La catégorie a été mise à jour.");
                }
                else
                {
                    $this->registerError("form_valid", "La mise à jour de la catégorie a échoué.");
                }
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }
    */





    public function deleteUser($refUser)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->utilisateurDAO->selectById($refUser);

        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->utilisateurDAO->delete($refUser);

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