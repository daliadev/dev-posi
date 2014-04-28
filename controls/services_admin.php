<?php


/**
 * Description of services_admin
 *
 * @author Nicolas Beurion
 */



require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_admin_question.php');
require_once(ROOT.'controls/services_admin_categorie.php');
require_once(ROOT.'controls/services_admin_degre.php');
require_once(ROOT.'controls/services_admin_restitution.php');
require_once(ROOT.'models/dao/menu_admin_dao.php');



class ServicesAdmin extends Main
{
    
    private $servicesGestion = null;
    
    private $servicesQuestion = null;
    private $servicesCategorie = null;
    private $servicesDegre = null;
    private $servicesRestitution = null;
    
    private $menuAdminDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "admin";
        
        $this->servicesGestion = new ServicesAdminGestion();
        
        $this->servicesQuestion = new ServicesAdminQuestion();
        $this->servicesCategorie = new ServicesAdminCategorie();
        $this->servicesDegre = new ServicesAdminDegre();
        $this->servicesRestitution = new ServicesAdminRestitution();
        
        $this->menuAdminDAO = new MenuAdminDAO();
        
        $this->initialize();
    }
     
    
    
    
    public function login()
    {

        $this->initialize();
 
        // Récupération des identifiants s'ils ont été saisi
        if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']))
        {
            $this->formData['login'] = $_POST['login'];
            $this->formData['password'] = $_POST['password'];

            // Vérification du code organisme
            $authAdmin = $this->servicesGestion->authenticateAdmin($this->formData['login'], $this->formData['password']);
            
            if (!empty($authAdmin['response']) && $authAdmin['response']['auth'] && !empty($authAdmin['response']['droit']))
            {
                $this->returnData['nom'] = $authAdmin['response']['nom'];
                $this->returnData['droit'] = $authAdmin['response']['droit'];

                // authentifié
                servicesAuth::login($this->returnData['droit']);
                
                // Redirection vers le menu
                header("Location: ".SERVER_URL."admin/menu/");
                exit();
            }
            else 
            {
                ServicesAuth::logout();
                $this->registerError("form_valid", "Identifiants non valides.");
            }
        }
        else
        {
            ServicesAuth::logout();
        }
        
        var_dump($this->errors);
             
        if (!empty($this->errors) && count($this->errors) > 0)
        {
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        

        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("form_login");
        
    }
    
    
    
    
    
    public function menu()
    {
        $this->initialize();
        
        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
            
        // Requete pour obtenir la liste des questions
        $menuList = $this->menuAdminDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($menuList['response']);
        
        if (!empty($menuList['response']['admin_menu']) && count($menuList['response']['admin_menu']) == 1)
        { 
            $menuElement = $menuList['response']['admin_menu'];
            $menuList['response']['admin_menu'] = array($menuElement);
        }

        $this->setResponse($menuList);
        $this->setTemplate("template_page");
        $this->render("menu"); 
    }
    
    
    
    
    
    public function question($requestParams = array())
    {  

        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/question/";
        
        // Initialisation du tableau des données qui seront inserés ou mis à jour dans la base
        $dataQuestion = array();

        if (Config::DEBUG_MODE)
        {
            echo "\$_POST = ";
            var_dump($_POST);
        }
        
        /*** Définition du mode précédent du formulaire ***/

        if (isset($_POST['mode']) && !empty($_POST['mode']))
        {
            $previousMode = $_POST['mode'];
        }
        else if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $previousMode = "view";
        }
        else 
        {
            $previousMode = "new";
        }
        
        /*** On détermine le mode du formulaire selon le bouton qui a été cliqué dans le formulaire ou bien on le récupère dans le champ caché. ***/
        
        $this->formData['mode'] = $this->servicesGestion->getFormMode($_POST);
        
        
        if (Config::DEBUG_MODE)
        {
            echo "\$this->formData['mode'] = ".$this->formData['mode']."<br/>";
        }
        
        /*** On récupère l'identifiant de la question et on initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "ref_question_cbox" => "select",
            "num_ordre_question" => "text",
            "intitule_question" => "text",
            "type_question" => "text",
            "ref_reponses" => "multi",
            "intitules_reponses" => "multi",
            "correct" => "multi",
            "reponse_champ" => "text",
            "image_question" => "text",
            "audio_question" => "text",
            "code_cat_cbox" => "select",
            "ref_activites" => "multi",
            "ref_degre" => "text",
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);
        
        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['ref_question_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_question'] = $this->formData['ref_question_cbox'];

        /*** Initialisation des boutons ***/
        $this->servicesGestion->switchFormButtons($this->formData, "init");
        
        
        /*-----   Mode "visualisation" et "édition"  -----*/
        
        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        {
            /* view */
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);

            if (!empty($this->formData['ref_question']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "";
                }
                
                $questionDetails = array();
                $questionDetails = $this->servicesQuestion->getQuestionDetails($this->formData['ref_question']);
                
                $this->formData = array_merge($this->formData, $questionDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Cette question n'existe pas");
            }
        }
        
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            // On recherche le numero d'ordre de la dernière question enregistrée
            if (empty($this->formData['ref_question']))
            {
                $this->formData['num_ordre_question'] = 1;
            }
            else 
            {
                // On récupère dans la bdd le numero d'ordre de la question courante
                $resultset = $this->servicesQuestion->getQuestion($this->formData['ref_question']);
                $questionCourante = $resultset['response']['question'];
                
                // On place la question juste après la question courante
                $this->formData['num_ordre_question'] = $questionCourante->getNumeroOrdre() + 1;
            }
        }
        
        
        /*-----   Mode "edition"   -----*/
        /*
        else if ($this->formData['mode'] == "edit")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "edit");

            if (!empty($this->formData['ref_question']))
            {
                $questionDetails = array();
                $questionDetails = $this->servicesQuestion->getQuestionDetails($this->formData['ref_question']);

                $this->formData = array_merge($this->formData, $questionDetails);
            }
            else 
            {
                $this->registerError("form_empty", "Cette question n'existe pas");
            }
        }
        */
        
        /*-----   Mode "enregistrement"   -----*/
        
        else if ($this->formData['mode'] == "save")
        { 
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "save");

            /*** Récupèration de l'id de la question s'il y en a une ***/

            if (!empty($this->formData['ref_question']))
            {
                if ($previousMode == "edit")
                {
                    $dataQuestion['ref_question'] = $this->formData['ref_question'];
                }
            }
            
            /*-----  Traitement des infos saisies   -----*/
            
            $dataQuestion = $this->servicesQuestion->filterQuestionData($this->formData, $_POST);

            /*----- Sauvegarde ou mise à jour des données ***/
            
            // Aucune erreur ne doit être enregistrée
            if (empty($this->servicesQuestion->errors) && empty($this->errors)) 
            {
                $this->servicesQuestion->setQuestionProperties($previousMode, $dataQuestion, $this->formData);
            }

            
            /*** S'il n'y a pas d'erreur, on recharge la page avec l'identifiant récupéré ***/
            
            if (empty($this->servicesQuestion->errors) && empty($this->errors))
            {
                // On recharge la page en mode view
                header("Location: ".$this->url.$this->formData['ref_question']);
            }
            else 
            {
                if ($previousMode == "new")
                {
                    $this->formData['mode'] = "new";
                }
                else
                {
                    $this->formData['mode'] = "edit";
                }
            }
        }
        
        
        /*-----   Mode "suppression"   -----*/
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // On récupère l'id de la question active
            if (!empty($this->formData['ref_question']))
            {
                // Ensuite on récupère les données de la question
                $resultsetQuestion = $this->servicesQuestion->getQuestion($this->formData['ref_question']);
                $this->formData['num_ordre_question'] = $resultsetQuestion['response']['question']->getNumeroOrdre();

                // Enfin, on supprime la question dans la base
                $resultsetQuestion = $this->servicesQuestion->deleteQuestion($this->formData['ref_question']);
                
                // Si la suppression a fonctionnée
                if ($resultsetQuestion)
                {   
                    // On décale l'ordre des questions avec n-1 pour toutes les questions supérieures à la question active 
                    $shiftOrdre = $this->servicesQuestion->shiftNumsOrdre($this->formData['num_ordre_question'], -1);
                    $this->registerSuccess("La question a été supprimée avec succès.");
                }
                else
                {
                    $this->registerError("form_valid", "La question n'a pas pu être completement supprimée.");
                }
            }
            else 
            {
                $this->registerError("form_valid", "La question n'existe pas.");
            }
            
            // On recharge la page
            header("Location: ".$this->url);
            exit();
        }
        
        
        else  
        {
            // Sinon, renvoi vers le template 404
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        
        
        if (Config::DEBUG_MODE)
        {
            echo "\$this->formData = <br/>";
            var_dump($this->formData);
        }
            
            
        
        /*--- Retour des données traitées du formulaire ---*/

        $this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;

        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesQuestion->errors) && count($this->servicesQuestion->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesQuestion->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesQuestion->success) && count($this->servicesQuestion->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesQuestion->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant de récupèrer les données permettant d'afficher les éléments du formulaire (liste déroulante, checkbox, radio, ...). ***/
        
        // Requete pour obtenir la liste des questions
        $listeQuestions = $this->servicesQuestion->getQuestions();
        // On assemble toutes les données
        $this->returnData['response'] = array_merge($listeQuestions['response'], $this->returnData['response']);

        // Requete pour obtenir la liste des réponses pour la question en cours si le type est "qcm"
        if (!empty($this->formData['ref_question']) && !empty($this->formData['type_question']) && $this->formData['type_question'] == "qcm")
        {
            $listeReponses['reponses'] = $this->servicesQuestion->getReponses($this->formData['ref_question']);
            $this->returnData['response'] = array_merge($listeReponses, $this->returnData['response']);
        }
        
        // Requete pour obtenir la liste des degres
        $listeDegres = $this->servicesDegre->getDegresList();
        $this->returnData['response'] = array_merge($listeDegres['response'], $this->returnData['response']);
        
        // Requete pour obtenir la liste des catégories
        $listeCategories = $this->servicesCategorie->getCategories();
        $this->returnData['response'] = array_merge($listeCategories['response'], $this->returnData['response']);
        
        
        
        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_question");
        
    }
    
    
    
    
    /**
     * categorie - Gére la validation du formulaire de gestion des catégories avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     * 
     * @param array Tableau de paramètres passés par url (comme le code de la categorie)
     */
    public function categorie($requestParams = array())
    {
        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/categorie";
        
        // Initialisation du tableau des données qui seront inserés ou mis à jour dans la base
        $dataCategorie = array();


        /*** Définition du mode précédent du formulaire ***/

        if (isset($_POST['mode']) && !empty($_POST['mode']))
        {
            $previousMode = $_POST['mode'];
        }
        else if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $previousMode = "view";
        }
        else 
        {
            $previousMode = "new";
        }

        
        /*** On détermine le mode du formulaire selon le bouton qui a été cliqué dans le formulaire ou bien on le récupère dans le champ caché. ***/
        
        $this->formData['mode'] = $this->servicesGestion->getFormMode($_POST);
        
        
        /*** On récupère le code de la catégorie et on initialise les données qui vont être validées et renvoyées au formulaire***/

        $this->servicesGestion->initializeFormData($this->formData, $_POST, array("code_cat_cbox" => "select", "nom" => "text", "descript_cat" => "text", "actif" => "text"));
        
        if (isset($requestParams[0]) && !empty($requestParams[0]) && $this->formData['code_cat_cbox'] == null)
        {
            $this->formData['code_cat_cbox'] = $requestParams[0];
        }
        $this->formData['code_cat'] = $this->formData['code_cat_cbox'];

        
        /*** Initialisation des données qui vont être validées et renvoyées au formulaire ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
       
        
        /*-----   Mode "visualisation"   -----*/

        if ($this->formData['mode'] == "view")
        { 
            
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "view");
            
            if (!empty($this->formData['code_cat']))
            {
                // Verrouillage des boutons "modifier" et "supprimer"
                $this->formData['edit_disabled'] = "";
                $this->formData['delete_disabled'] = "";
                
                $resultset = $this->servicesCategorie->getCategorie($this->formData['code_cat']);
                
                if (!$resultset)
                {
                    $this->registerError("form_request", "Impossible de visualiser la catégorie.");
                }
                else 
                {
                    $this->formData['nom_cat'] = $resultset['response']['categorie']->getNom();
                    $this->formData['descript_cat'] = $resultset['response']['categorie']->getDescription();
                    $this->formData['type_lien_cat'] = $resultset['response']['categorie']->getTypeLien();
                }
            }
        }
        
        
        /*-----   Mode "nouvelle catégorie"   -----*/
        
        else if ($this->formData['mode'] == "new")
        {   
            
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "new");
            
            $this->formData['code_cat'] = null;
            $this->formData['nom_cat'] = null;
            $this->formData['descript_cat'] = null;
            $this->formData['type_lien_cat'] = null;
        }
        
        
        /*-----   Mode "édition"   -----*/
        
        else if ($this->formData['mode'] == "edit")
        {
            
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "edit");

            if (!empty($this->formData['code_cat']))
            {
                $resultset = $this->servicesCategorie->getCategorie($this->formData['code_cat']);
                
                $this->formData['nom_cat'] = $resultset['response']['categorie']->getNom();
                $this->formData['descript_cat'] = $resultset['response']['categorie']->getDescription();
                $this->formData['type_lien_cat'] = $resultset['response']['categorie']->getTypeLien();
            }
            else 
            {
                $this->registerError("form_empty", "Cette question n'existe pas");
            }
        }
        
        
        /*-----   Mode "enregistrement"   -----*/

        else if ($this->formData['mode'] == "save")
        {
            
            // Vérrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "save");
            
            /*** Validation des infos saisies ***/

            // Récupèration du code catégorie original s'il y en a un ***/
            if (isset($_POST['code']) && !empty($_POST['code']))
            {
                $this->formData['code'] = $_POST['code'];
                $dataCategorie['code'] = $this->formData['code'];
            }
            
            // Formatage du code catégorie
            $this->formData['code_cat'] = $this->validatePostData($_POST['code_cat'], "code_cat", "integer", true, "Aucun code de catégorie n'a été saisi.", "Le code n'est pas correctement saisi.");
            $dataCategorie['code_cat'] = $this->formData['code_cat'];
            
            // Formatage du nom de la catégorie
            $this->formData['nom_cat'] = $this->validatePostData($_POST['nom_cat'], "nom_cat", "string", true, "Aucun nom de catégorie n'a été saisi", "Le nom n'est pas correctement saisi.");
            $dataCategorie['nom_cat'] = $this->formData['nom_cat'];
            
            // Formatage de l'intitule de la catégorie 
            $this->formData['descript_cat'] = $this->validatePostData($_POST['descript_cat'], "descript_cat", "string", false, "Aucune description n'a été saisi", "La description n'a été correctement saisi.");
            $dataCategorie['descript_cat'] = $this->formData['descript_cat'];
            
            // Formatage du type de lien de la catégorie
            if (isset($_POST['type_lien_cat']))
            {
                $this->formData['type_lien_cat'] = "dynamic";
                $dataCategorie['type_lien_cat'] = "dynamic";
            }
            else 
            {
                $this->formData['type_lien_cat'] = "static";
                $dataCategorie['type_lien_cat'] = "static";
            }
            //$this->formData['type_lien_cat'] = $this->validatePostData($_POST['type_lien_cat'], "type_lien_cat", "string", true, "Aucun type n'a été coché", "Le type n'a été correctement saisi.");
            //$dataCategorie['type_lien_cat'] = $this->formData['type_lien_cat'];

            
            /*** Sauvegarde ou mise à jour des données ***/
            
            // Aucune erreur ne doit être enregistrée
            if (empty($this->servicesCategorie->errors) && empty($this->errors)) 
            {
                $modeCategorie = "insert";
                        
                if ($previousMode == "new")
                {
                    // Insertion de la catégorie dans la bdd
                    $resultset = $this->servicesCategorie->setCategorie($modeCategorie, $dataCategorie);
                    
                    if (!$resultset)
                    {
                        $this->registerError("form_data", "L'insertion de la catégorie a échouée.");
                    }
                }
                else if ($previousMode == "edit"  || $previousMode == "save")
                {
                    $modeCategorie = "update";
                    
                    if (isset($dataCategorie['code_cat']) && !empty($dataCategorie['code_cat']))
                    {
                        $this->formData['code_cat'] = $dataCategorie['code_cat'];

                        // Mise à jour de la catégorie
                        $resultset = $this->servicesCategorie->setCategorie($modeCategorie, $dataCategorie);

                        if (!$resultset)
                        {
                            $this->registerError("form_data", "La mise à jour de la catégorie a échouée.");
                        }
                    }
                }
                else
                {
                    // La page n'existe pas
                    header("Location: ".SERVER_URL."erreur/page404");
                    exit();
                }
            }

            
            if (Config::DEBUG_MODE)
            {
                echo "\$dataCategorie = <br/>";
                var_dump($dataCategorie);

                echo "\$previousMode = ".$previousMode."<br/>";
            }
            
            
            /*** S'il n'y a pas d'erreur, on recharge la page avec l'identifiant récupéré ***/
            
            if (empty($this->servicesCategorie->errors) && empty($this->errors))
            {
                // On recharge la page en mode view
                header("Location: ".$this->url."/".$this->formData['code_cat']);
            }
            else 
            {
                if ($previousMode == "new")
                {
                    $this->formData['mode'] = "new";
                }
                else
                {
                    $this->formData['mode'] = "edit";
                }
            } 
        }

        
        /*-----   Mode "suppression"   -----*/

        else if ($this->formData['mode'] == "delete")
        {
            $this->servicesGestion->switchFormButtons($this->formData, "save");
            
            // On récupère le code de la catégorie active
            if (!empty($this->formData['code_cat']))
            {
                // On supprime la catégorie dans la base
                $resultsetCat = $this->servicesCategorie->deleteCategorie($this->formData['code_cat']);

                if ($resultsetCat)
                {   
                    $this->registerSuccess("La catégorie a été supprimée avec succès.");
                }
                else
                {
                    $this->registerError("form_data", "La catégorie n'a pas pu être supprimée.");
                }
            }
            else 
            {
                $this->registerError("form_data", "La catégorie n'existe pas.");
            }
            
            // On recharge la page
            header("Location: ".$this->url);
        }
        else  
        {
            // Sinon, renvoi vers page inconnue (404)
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        
        
        if (Config::DEBUG_MODE)
        {
            echo "\$this->formData = <br/>";
            var_dump($this->formData);
        }
            
            
        /*-----   Retour des données traitées du formulaire   -----*/
        
        $this->returnData['response']['form_data'] = $this->formData;
        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesCategorie->errors) && count($this->servicesCategorie->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesCategorie->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesCategorie->success) && count($this->servicesCategorie->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesCategorie->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant d'afficher les éléments du formulaire (liste déroulante, checkbox). ***/
        
        // Requete pour obtenir la liste des catégories
        $listeCategories = $this->servicesCategorie->getCategories();
        $this->returnData['response'] = array_merge($listeCategories['response'], $this->returnData['response']);
        
        
        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_categorie");    
    }
    
    
    
    
    
    /**
     * degre - Gére la validation du formulaire de gestion des degrés d'aptitude avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (comme la référence du degré)
     */
    public function degre($requestParams = array())
    {        
                
        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/degre";

        
        /*** Initialisation des tableaux des données qui seront inseré ou mis à jour dans la base ***/
        $dataDegre = array();

        if (Config::DEBUG_MODE)
        {
            echo "\$_POST = ";
            var_dump($_POST);
        }


        /*** Définition du mode précédent du formulaire ***/

        if (isset($_POST['mode']) && !empty($_POST['mode']))
        {
            $previousMode = $_POST['mode'];
        }
        else if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $previousMode = "view";
        }
        else 
        {
            $previousMode = "new";
        }
        
        
        /*** On détermine le mode du formulaire selon le bouton qui a été cliqué dans le formulaire ou bien on le récupère dans le champ caché. ***/
        
        $this->formData['mode'] = $this->servicesGestion->getFormMode($_POST);
        
        
        if (Config::DEBUG_MODE)
        {
            echo "\$this->formData['mode'] = ".$this->formData['mode']."<br/>";
        }

        
        /*** On récupère la référence du degré et on initialise les données qui vont être validées et renvoyées au formulaire ***/

        $this->servicesGestion->initializeFormData($this->formData, $_POST, array("ref_degre_cbox" => "select", "nom_degre" => "text", "descript_degre" => "text"));
        
        if (isset($requestParams[0]) && !empty($requestParams[0]) && $this->formData['ref_degre_cbox'] == null)
        {
            $this->formData['ref_degre_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_degre'] = $this->formData['ref_degre_cbox'];
        
        
        /*** Initialisation des données qui vont être validées et renvoyées au formulaire ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
  
        
        
        /*-----   Mode "visualisation" et "edition"   -----*/

        
        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        { 
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);
            
            if (!empty($this->formData['ref_degre']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "";
                }
                
                $degreDetails = array();
                $degreDetails = $this->servicesDegre->getDegreDetails($this->formData['ref_degre']);
                $this->formData = array_merge($this->formData, $degreDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Ce degré n'existe pas");
            }
        }
        
        
        /*-----   Mode "nouveau degré"   -----*/
        
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            $this->formData['nom_degre'] = null;
            $this->formData['descript_degre'] = null;
        }
  
        
        
        /*-----   Mode "enregistrement"   -----*/
        
        
        else if ($this->formData['mode'] == "save")
        {
            
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "save");


            /*** Récupèration de l'id de la question ***/

            if (!empty($this->formData['ref_degre']))
            {
                if ($previousMode == "edit")
                {
                    $dataDegre['ref_degre'] = $this->formData['ref_degre'];
                }
            }
            

            /*-----  Traitement des infos saisies   -----*/
            
            $dataDegre = $this->servicesDegre->filterDegreData($this->formData, $_POST);

            

            /*----- Sauvegarde ou mise à jour des données ***/
            
            // Aucune erreur ne doit être enregistrée
            if (empty($this->servicesDegre->errors) && empty($this->errors)) 
            {
                $this->servicesDegre->setDegreProperties($previousMode, $dataDegre, $this->formData);
            }
            
            /*** S'il n'y a pas d'erreur, on recharge la page avec l'identifiant récupéré ***/
            
            if (empty($this->servicesDegre->errors) && empty($this->errors))
            {
                // On recharge la page en mode view
                header("Location: ".$this->url."/".$this->formData['ref_degre']);
                exit();
            }
            else 
            {
                if ($previousMode == "new")
                {
                    $this->formData['mode'] = "new";
                }
                else
                {
                    $this->formData['mode'] = "edit";
                }
            }
            
        }
        
        
        
        /*-----   Mode "suppression"   -----*/
        
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // On récupère le code du degré actif
            if (!empty($this->formData['ref_degre']))
            {
                // Ensuite on supprime le degré dans la base
                $resultsetDegre = $this->servicesDegre->deleteDegre($this->formData['ref_degre']);

                if ($resultsetDegre)
                {   
                    $this->registerSuccess("Le degré a été supprimé avec succès.");
                }
                else
                {
                    $this->registerError("form_data", "Le degré n'a pas pu être supprimé.");
                }
            }
            else 
            {
                $this->registerError("form_data", "Le degré n'existe pas.");
            }
            
            // On recharge la page
            header("Location: ".$this->url);
            exit();
        }


        else  
        {
            // Sinon, renvoi vers la page inconnue (404)
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        
       
        if (Config::DEBUG_MODE)
        {
            echo "\$this->formData = <br/>";
            var_dump($this->formData);
            //exit();
        }
            
            
        
        
        /*-----   Retour des données traitées du formulaire   -----*/


        $this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;

        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesDegre->errors) && count($this->servicesDegre->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesDegre->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesDegre->success) && count($this->servicesDegre->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesDegre->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant d'afficher les éléments du formulaire (liste déroulante, checkbox). ***/
        
        // Requete pour obtenir la liste des degrés
        $listeDegres = $this->servicesDegre->getDegresList();
        $this->returnData['response'] = array_merge($listeDegres['response'], $this->returnData['response']);

        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_degre");
        
    }
    
    
    
    
    /**
     * activite - Gére la validation du formulaire de gestion des activités avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (comme la référence de l'activité)
     */
    public function activite($requestParams = array())
    {
        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
        
        $this->setTemplate("template_page");
        $this->render("gestion_activite"); 
        
    }
	
	
    /* A supprimer !!! */
    public function restitution()
    {
        
        header("Location: ".SERVER_URL."public/restitution");
        exit();
        
        
        // Authentification de l'admin necessaire
        ServicesAuth::checkAuthentication("admin");
        
	   $this->initialize();
        
        $this->url = SERVER_URL."admin/restitution/";
            
    	if (Config::DEBUG_MODE)
        {
            echo "\$_POST = ";
            var_dump($_POST);
        }
		
	
	   /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
    
        $this->formData['ref_organ_cbox'] = null;
        $this->formData['ref_user_cbox'] = null;
        $this->formData['ref_session_cbox'] = null;
        $this->formData['ref_organ'] = null;
        $this->formData['ref_user'] = null;
        $this->formData['ref_session'] = null;
        
        $this->servicesGestion->initializeFormData($this->formData, $_POST, array(
            "ref_organ_cbox" => "select", 
            "ref_user_cbox" => "select", 
            "ref_session_cbox" => "select"));

        $this->formData['ref_organ'] = $this->formData['ref_organ_cbox'];
        $this->formData['ref_user'] = $this->formData['ref_user_cbox'];
        $this->formData['ref_session'] = $this->formData['ref_session_cbox'];

        
        /*** Initialisation des infos sur le positionnement ***/
  
        
        // On commence par obtenir le nom et l'id de chaque organisme de la table "organisme"
        $organismesList = $this->servicesRestitution->getOrganismesList();
		
        $this->returnData['response'] = array_merge($organismesList['response'], $this->returnData['response']);
        
        $nomOrgan = null;
        foreach ($organismesList['response']['organisme'] as $organisme)
        {
            if ($organisme->getId() == $this->formData['ref_organ'])
            {
                $nomOrgan = $organisme->getNom();
            }
        }
        
        // Pour chaque combo-box sélectionné, on effectue les requetes correspondantes
        
        /*------   Un organisme a été sélectionnée   -------*/
        
        if (!empty($this->formData['ref_organ']) && $this->formData['ref_organ'] != "select_cbox")
        {
            /*** On va chercher tous les utilisateurs qui correspondent à l'organisme ***/
            $resultsetUsers = $this->servicesRestitution->getUsersFromOrganisme($this->formData['ref_organ']);
            
            if (!$resultsetUsers)
            {
                $this->registerError("form_data", "Impossible de visualiser les utilisateurs.");
            }
            else 
            {
                $resultset['response']['utilisateurs'] = $resultsetUsers['response']['utilisateur'];
            }
            $this->returnData['response'] = array_merge($resultset['response'], $this->returnData['response']);
        
            
            /*------   Un utilisateur a été sélectionné   -------*/
            
            if (!empty($this->formData['ref_user']) && $this->formData['ref_user'] != "select_cbox")
            {
                /*** On commence par rechercher les infos sur l'utilisateur ***/
                $this->returnData['response']['infos_user'] = $this->servicesRestitution->getInfosUser($this->formData['ref_user']);
                $this->returnData['response']['infos_user']['nom_organ'] = $nomOrgan;
                

                /*** On va chercher toutes les sessions qui correspondent à l'utilisateur sélectionné ***/
                $resultsetSessions = $this->servicesRestitution->getUserSessions($this->formData['ref_user'], $this->formData['ref_organ']);

                if (empty($resultsetSessions['response']))
                {
                    $this->registerError("form_request", "Cette utilisateur n'a effectué aucun positionnement");
                }
                else 
                {
                    $resultset['response']['sessions'] = $resultsetSessions['response']['session'];
                    
                    $this->returnData['response'] = array_merge($resultset['response'], $this->returnData['response']);
                
                    // Transformation de la date et du temps
                    $date = Tools::toggleDate(substr($resultset['response']['sessions'][0]->getDate(), 0, 10));
                    $timeToSeconds = Tools::timeToSeconds(substr($resultset['response']['sessions'][0]->getDate(), 11, 8), $inputFormat = "h:m:s");
                    $time = Tools::timeToString($timeToSeconds, "h:m");         
                    $this->returnData['response']['infos_user']['date_last_posi'] = "Le ".$date." à ".str_replace(":", "h", $time);


                    /*------   Une session a été sélectionnée   -------*/
                    
                    if (!empty($this->formData['ref_session']) && $this->formData['ref_session'] != "select_cbox")
                    {
                        /*** On va chercher les infos sur la session qui correspondent à la référence de la session sélectionné ***/
                        $resultsetSession = $this->servicesRestitution->getSession($this->formData['ref_session']);
                        $this->returnData['response'] = array_merge($resultsetSession['response'], $this->returnData['response']);

                        /*** On récupère également les infos sur l'intervenant ***/
                        $resultsetIntervenant = $this->servicesRestitution->getIntervenant($resultsetSession['response']['session'][0]->getRefIntervenant());
                        $this->returnData['response']['infos_user']['nom_intervenant'] = $resultsetIntervenant['response']['intervenant']->getNom();
                        $this->returnData['response']['infos_user']['email_intervenant'] = $resultsetIntervenant['response']['intervenant']->getEmail();


                        $refSession = $resultsetSession['response']['session'][0]->getId();
                                
                        $this->returnData['response']['stats'] = array();
                        $this->returnData['response']['stats'] = $this->servicesRestitution->getPosiStats($refSession);
                        
                        
                        /*** Tout d'abord, on recherche toutes les questions ***/
                        $this->returnData['response']['details']['questions'] = array();
                        $this->returnData['response']['details']['questions'] = $this->servicesRestitution->getQuestionsDetails($refSession);         

                    }
                }

            }
        }

        
        /*-----   Retour des données traitées du formulaire   -----*/
        
        $this->returnData['response']['form_data'] = $this->formData;

        // S'il y a des erreurs, on les injecte dans la réponse
        if (!empty($this->errors) && count($this->errors) > 0)
        {
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        
        $this->setResponse($this->returnData);
        
        if (isset($_POST['export_pdf']) && !empty($_POST['export_pdf']))
        {
            $this->renderPDF("restitution_pdf", ROOT."downloads/pdf/essai-doc.pdf", "I");
        }
        else if (isset($_POST['export_xls']) && !empty($_POST['export_xls']))
        {
            $this->render("restitution_xls");
        }
        else
        {
            $this->setTemplate("template_page");
            $this->render("restitution");
        }

    }
    
}


?>