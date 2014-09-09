<?php



require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_admin_question.php');
require_once(ROOT.'controls/services_admin_categorie.php');
require_once(ROOT.'controls/services_admin_degre.php');
require_once(ROOT.'controls/services_admin_utilisateur.php');
require_once(ROOT.'controls/services_admin_organisme.php');
require_once(ROOT.'controls/services_admin_restitution.php');
require_once(ROOT.'controls/services_admin_compte.php');
require_once(ROOT.'models/dao/menu_admin_dao.php');




class ServicesAdmin extends Main
{
    
    private $servicesGestion = null;
    
    private $servicesQuestion = null;
    private $servicesCategorie = null;
    private $servicesDegre = null;
    private $servicesUtilisateur = null;
    private $servicesOrganisme = null;
    private $servicesRestitution = null;
    private $servicesCompte = null;
    
    private $menuAdminDAO = null;
    
    
    
    public function __construct() 
    {
        $this->controllerName = "admin";
        
        $this->servicesGestion = new ServicesAdminGestion();
        
        $this->servicesQuestion = new ServicesAdminQuestion();
        $this->servicesCategorie = new ServicesAdminCategorie();
        $this->servicesDegre = new ServicesAdminDegre();
        $this->servicesUtilisateur = new ServicesAdminUtilisateur();
        $this->servicesOrganisme = new ServicesAdminOrganisme();
        $this->servicesRestitution = new ServicesAdminRestitution();
        $this->servicesCompte = new ServicesAdminCompte();
        
        $this->menuAdminDAO = new MenuAdminDAO();
        
        $this->initialize();
    }
     
    
    
    
    public function login()
    {

        ServicesAuth::logout();

        $this->initialize();
 
        // Récupération des identifiants s'ils ont été saisis.
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
        
        // Authentification
        ServicesAuth::checkAuthentication("custom");

        
            
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

        // Authentification
        ServicesAuth::checkAuthentication("admin");

        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/question/";
        
        // Initialisation du tableau des données qui seront inserées ou mises à jour dans la base.
        $dataQuestion = array();

        // Débuggage
        if (Config::DEBUG_MODE)
        {
            // Affichage des données du formulaire.
            echo "\$_POST = ";
            var_dump($_POST);
        }
        

        /*** Définition du mode précédent du formulaire (permet de connaître l'action précédemment choisie par l'utilisateur) ***/

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

        
        /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "ref_question_cbox"  => "select",
            "num_ordre_question" => "text",
            "intitule_question"  => "text",
            "type_question"      => "text",
            "ref_reponses"       => "multi",
            "intitules_reponses" => "multi",
            "correct"            => "multi",
            "reponse_champ"      => "text",
            "image_question"     => "text",
            "audio_question"     => "text",
            "code_cat_cbox"      => "select",
            "ref_activites"      => "multi",
            "ref_degre"          => "multi"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);
        

        /*** Récupération de la question par la méthode GET ***/

        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['ref_question_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_question'] = $this->formData['ref_question_cbox'];


        /*** Initialisation des boutons ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
        
        

        /*-----   Action a effectuée selon le mode soumis par le formulaire  -----*/
        

        /*** Mode "visualisation" et "édition" ***/

        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);

            // Avec la référence, on va chercher toutes les infos sur la question 
            if (!empty($this->formData['ref_question']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "disabled";
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
        

        /*** Mode "nouvelle question" ***/
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            // On recherche le numero d'ordre de la dernière question enregistrée.
            if (empty($this->formData['ref_question']))
            {
                $this->formData['num_ordre_question'] = 1;
            }
            else 
            {
                // On récupère dans la bdd le numero d'ordre de la question courante.
                $resultset = $this->servicesQuestion->getQuestion($this->formData['ref_question']);
                $questionCourante = $resultset['response']['question'];
                
                // On place la question juste après la question courante.
                $this->formData['num_ordre_question'] = $questionCourante->getNumeroOrdre() + 1;
            }
        }

        
        /*** Mode "enregistrement" ***/
        
        else if ($this->formData['mode'] == "save")
        { 
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "save");

            // Récupèration de l'id de la question s'il y en a une.
            if (!empty($this->formData['ref_question']))
            {
                if ($previousMode == "edit")
                {
                    $dataQuestion['ref_question'] = $this->formData['ref_question'];
                }
            }
            
            // Traitement des infos saisies.
            $dataQuestion = $this->servicesQuestion->filterQuestionData($this->formData, $_POST);
            
            
            // Sauvegarde ou mise à jour des données (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesQuestion->errors) && empty($this->errors)) 
            {
                $this->servicesQuestion->setQuestionProperties($previousMode, $dataQuestion, $this->formData);
            }


            // Rechargement de la page avec l'identifiant récupéré (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesQuestion->errors) && empty($this->errors))
            {
                // On recharge la page en mode visualisation.
                header("Location: ".$this->url.$this->formData['ref_question']);
            }
            else 
            {
                // Sinon mode nouveau ou édition.
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
        
        
        /*** Mode "suppression" ***/
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // Si l'id de la question active existe :
            if (!empty($this->formData['ref_question']))
            {
                // On récupère les données de la question.
                //$resultsetQuestion = $this->servicesQuestion->getQuestion($this->formData['ref_question']);

                // Enfin, on supprime la question dans la base.
                $resultsetQuestion = $this->servicesQuestion->deleteQuestion($this->formData['ref_question']);
                
                // Si la suppression a fonctionné
                if ($resultsetQuestion)
                {   
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
            

            // On recharge la page (sans aucune information).
            //header("Location: ".$this->url);
            //exit();
        }
        

        /*** Erreur : aucun mode ***/

        else  
        {
            // Renvoi vers le template 404 (page inconnue).
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        
           
        
        /*** Retour des données traitées du formulaire ***/

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
        
        
        /*** Ensemble des requêtes permettant de récupèrer les données pour l'affichage des éléments du formulaire (liste déroulante, checkbox, radio, ...). ***/
        
        // Requete pour obtenir la liste des questions.
        $listeQuestions = $this->servicesQuestion->getQuestions();
        

        // Requete pour obtenir la liste des réponses pour la question en cours si le type est "qcm".
        $listeReponses = array();
        if (!empty($this->formData['ref_question']) && !empty($this->formData['type_question']) && $this->formData['type_question'] == "qcm")
        {
            $listeReponses['reponses'] = $this->servicesQuestion->getReponses($this->formData['ref_question']);
            //$this->returnData['response'] = array_merge($listeReponses, $this->returnData['response']);
        }
        
        // Requete pour obtenir la liste des degres.
        $listeDegres = $this->servicesDegre->getDegresList();
        
        // Requete pour obtenir la liste des catégories.
        $listeCategories = $this->servicesCategorie->getCategories();
        
        // Assemblage de toutes les données de la réponse
        $this->returnData['response'] = array_merge($listeQuestions['response'], $this->returnData['response']);
        $this->returnData['response'] = array_merge($listeReponses, $this->returnData['response']);
        $this->returnData['response'] = array_merge($listeDegres['response'], $this->returnData['response']);
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

        // Authentification
        ServicesAuth::checkAuthentication("admin");

        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/categorie/";
        
        // Initialisation du tableau des données qui seront inserées ou mises à jour dans la base.
        $dataCategorie = array();


        /*** Définition du mode précédent du formulaire (permet de connaître l'action précédemment choisie par l'utilisateur) ***/

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


        /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "code_cat_cbox" => "select", 
            "nom"           => "text", 
            "descript_cat"  => "text", 
            "actif"         => "text"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);
        

        /*** Récupération du code de la catégorie par la méthode GET ***/

        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['code_cat_cbox'] = $requestParams[0];
        }
        
        $this->formData['code_cat'] = $this->formData['code_cat_cbox'];


        /*** Initialisation des boutons ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
       



        /*-----   Action a effectuée selon le mode soumis par le formulaire  -----*/
        

        /*** Mode "visualisation" et "édition" ***/

        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);

            // Avec la référence, on va chercher toutes les infos sur la question 
            if (!empty($this->formData['code_cat']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "disabled";
                }
                
                $catDetails = array();
                $catDetails = $this->servicesCategorie->getCategorieDetails($this->formData['code_cat']);
                
                $this->formData = array_merge($this->formData, $catDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Cette categorie n'existe pas.");
            }
        }


        /*** Mode "nouvelle question" ***/
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            $this->formData['code_cat'] = null;
            $this->formData['nom_cat'] = null;
            $this->formData['descript_cat'] = null;
            $this->formData['type_lien_cat'] = null;
        }


        /*** Mode "enregistrement" ***/
        
        else if ($this->formData['mode'] == "save")
        { 
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "save");

            // Récupèration de l'id de la question s'il y en a une.
            if (!empty($this->formData['code_cat']))
            {
                if ($previousMode == "edit")
                {
                    $dataCategorie['code_cat'] = $this->formData['code_cat'];
                }
            }

            // Traitement des infos saisies.
            $dataCategorie = $this->servicesCategorie->filterCategorieData($this->formData, $_POST);


            // Sauvegarde ou mise à jour des données (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesCategorie->errors) && empty($this->errors)) 
            {
                $this->servicesCategorie->setCategorieProperties($previousMode, $dataCategorie, $this->formData);
            }

            
            // Rechargement de la page avec l'identifiant récupéré (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesCategorie->errors) && empty($this->errors))
            {
                // On recharge la page en mode visualisation.
                header("Location: ".$this->url.$this->formData['code_cat']);
            }
            else 
            {
                // Sinon mode nouveau ou édition.
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
 
        
        /*** Mode "suppression" ***/
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // Si le code de la catégorie active existe :
            if (!empty($this->formData['code_cat']))
            {
                // On supprime la catégorie dans la base.
                $resultsetCat = $this->servicesCategorie->deleteCategorie($this->formData['code_cat']);
                
                // Si la suppression a fonctionnée :
                if ($resultsetCat)
                {   
                    $this->registerSuccess("La catégorie a été supprimée avec succès.");
                }
                else
                {
                    // Sinon on renvoi une erreur.
                    $this->registerError("form_valid", "La catégorie n'a pas pu être supprimée.");
                }
            }
            else 
            {
                $this->registerError("form_valid", "La catégorie n'existe pas.");
            }
            
            // On recharge la page (sans aucune information).
            //header("Location: ".$this->url);
            //exit();
        }


        /*** Erreur : aucun mode ***/

        else  
        {
            // Renvoi vers le template 404 (page inconnue).
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
            
            
        
        /*** Retour des données traitées du formulaire ***/

        $this->returnData['response']['form_data'] = array();
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
        
        // Assemblage de toutes les données de la réponse
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
                
        // Authentification
        ServicesAuth::checkAuthentication("admin");

        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/degre/";

        
        /*** Initialisation des tableaux des données qui seront inseré ou mis à jour dans la base ***/
        $dataDegre = array();


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

        
        /*** On récupère la référence du degré et on initialise les données qui vont être validées et renvoyées au formulaire ***/

        $this->servicesGestion->initializeFormData($this->formData, $_POST, array("ref_degre_cbox" => "select", "nom_degre" => "text", "descript_degre" => "text"));
        
        if (isset($requestParams[0]) && !empty($requestParams[0]) && $this->formData['ref_degre_cbox'] != null)
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
                    $this->formData['delete_disabled'] = "disabled";
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

            $this->formData['ref_degre'] = null;
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
                header("Location: ".$this->url.$this->formData['ref_degre']);
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
            
            // On recharge la page (sans aucune information).
            //header("Location: ".$this->url);
            //exit();
        }


        else  
        {
            // Sinon, renvoi vers la page inconnue (404)
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
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
     * organisme - Gestion des organismes avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (comme la référence de l'organisme)
     */
    public function organisme($requestParams = array())
    {
        
        // Authentification
        ServicesAuth::checkAuthentication("custom");

        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/organisme/";
        
        // Initialisation du tableau des données qui seront inserées ou mises à jour dans la base.
        $dataOrgan = array();


        /*** Définition du mode précédent du formulaire (permet de connaître l'action précédemment choisie par l'utilisateur) ***/

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


        /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "ref_organ_cbox"    => "select", 
            "nom_organ"         => "text",
            "numero_interne"    => "text", 
            "code_postal_organ" => "text", 
            "tel_organ"         => "text",
            "nbre_posi_max"     => "text"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);


        /*** Récupération du code de la catégorie par la méthode GET ***/

        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['ref_organ_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_organ'] = $this->formData['ref_organ_cbox'];


        /*** Initialisation des boutons ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");

        /*-----   Action a effectuée selon le mode soumis par le formulaire  -----*/
        

        /*** Mode "visualisation" et "édition" ***/

        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);

            // Avec la référence, on va chercher toutes les infos sur la question 
            if (!empty($this->formData['ref_organ']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "disabled";
                }
                
                $organDetails = array();
                $organDetails = $this->servicesOrganisme->getOrganDetails($this->formData['ref_organ']);
                
                $this->formData = array_merge($this->formData, $organDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Cet organisme n'existe pas.");
            }
        }
        

        /*** Mode "nouvelle question" ***/
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            $this->formData['ref_organ'] = null;
            $this->formData['nom_organ'] = null;
            $this->formData['numero_interne'] = null;
            $this->formData['code_postal_organ'] = null;
            $this->formData['tel_organ'] = null;
            $this->formData['nbre_posi_max'] = null;
        }


        /*** Mode "enregistrement" ***/
        
        else if ($this->formData['mode'] == "save")
        { 
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "save");

            // Récupèration de l'id de l'organisme s'il y en a un.
            if (!empty($this->formData['ref_organ']))
            {
                if ($previousMode == "edit")
                {
                    $dataOrgan['ref_organ'] = $this->formData['ref_organ'];
                }
            }

            // Traitement des infos saisies.
            $dataOrgan = $this->servicesOrganisme->filterOrganData($this->formData, $_POST);


            // Sauvegarde ou mise à jour des données (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesOrganisme->errors) && empty($this->errors)) 
            {
               $this->servicesOrganisme->setOrganProperties($previousMode, $dataOrgan, $this->formData);
            }

            // Rechargement de la page avec l'identifiant récupéré (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesOrganisme->errors) && empty($this->errors))
            {
                // On recharge la page en mode visualisation.
                header("Location: ".$this->url.$this->formData['ref_organ']);
            }
            else 
            {
                // Sinon mode nouveau ou édition.
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
 
        
        /*** Mode "suppression" ***/
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // Si la référence de l'organisme actif existe :
            if (!empty($this->formData['ref_organ']))
            {
                // On supprime l'organisme dans la base.
                $resultsetUser = $this->servicesOrganisme->deleteOrganisme($this->formData['ref_organ']);
                
                // Si la suppression a fonctionnée :
                if ($resultsetUser)
                {   
                    $this->registerSuccess("L'organisme a été supprimé avec succès.");
                }
                else
                {
                    // Sinon on renvoi une erreur.
                    $this->registerError("form_valid", "L'organisme n'a pas pu être supprimé.");
                }
            }
            else 
            {
                $this->registerError("form_valid", "L'organisme n'existe pas.");
            }
            
            
            // On recharge la page (sans aucune information).
            //header("Location: ".$this->url);
            //exit();
        }


        /*** Erreur : aucun mode ***/

        else  
        {
            // Renvoi vers le template 404 (page inconnue).
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        

        
        /*** Retour des données traitées du formulaire ***/

        $this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;


        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesOrganisme->errors) && count($this->servicesOrganisme->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesOrganisme->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesOrganisme->success) && count($this->servicesOrganisme->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesOrganisme->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant d'afficher les éléments du formulaire (liste déroulante, checkbox). ***/
        
        // Requete pour obtenir la liste des utilisateurs
        $listeOrgans = $this->servicesOrganisme->getOrganismes();
        
        // Assemblage de toutes les données de la réponse
        $this->returnData['response'] = array_merge($listeOrgans['response'], $this->returnData['response']);


        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_organisme"); 
		
    }





    /**
     * utilisateur - Gestion des utilisateurs avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (comme la référence de l'utilisateur)
     */
    public function utilisateur($requestParams = array())
    {

        // Authentification
        ServicesAuth::checkAuthentication("custom");


        $this->initialize();
        
        $this->url = SERVER_URL."admin/utilisateur/";
        
        // Initialisation du tableau des données qui seront inserées ou mises à jour dans la base.
        $dataUser = array();


        /*** Définition du mode précédent du formulaire (permet de connaître l'action précédemment choisie par l'utilisateur) ***/

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


        /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "ref_user_cbox"   => "select", 
            "nom_user"        => "text", 
            "prenom_user"     => "text", 
            "date_naiss_user" => "text",
            "ref_niveau_cbox" => "select"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);
        

        /*** Récupération du code de la catégorie par la méthode GET ***/

        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['ref_user_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_user'] = $this->formData['ref_user_cbox'];


        /*** Initialisation des boutons ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
       



        /*-----   Action a effectuée selon le mode soumis par le formulaire  -----*/
        

        /*** Mode "visualisation" et "édition" ***/

        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        {
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);

            // Avec la référence, on va chercher toutes les infos sur la question 
            if (!empty($this->formData['ref_user']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "disabled";
                }
                
                $userDetails = array();
                $userDetails = $this->servicesUtilisateur->getUserDetails($this->formData['ref_user']);
                
                $this->formData = array_merge($this->formData, $userDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Cet utilisateur n'existe pas.");
            }
        }
        

        /*** Mode "nouvelle question" ***/
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            $this->formData['ref_user'] = null;
            $this->formData['nom_user'] = null;
            $this->formData['prenom_user'] = null;
            $this->formData['date_naiss_user'] = null;
            $this->formData['ref_niveau'] = null;
        }


        /*** Mode "enregistrement" ***/
        
        else if ($this->formData['mode'] == "save")
        { 
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "save");

            // Récupèration de l'id de l'utilisateur s'il y en a un.
            if (!empty($this->formData['ref_user']))
            {
                if ($previousMode == "edit")
                {
                    $dataUser['ref_user'] = $this->formData['ref_user'];
                }
            }

            // Traitement des infos saisies.
            $dataUser = $this->servicesUtilisateur->filterUserData($this->formData, $_POST);


            // Sauvegarde ou mise à jour des données (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesUtilisateur->errors) && empty($this->errors)) 
            {
               $this->servicesUtilisateur->setUserProperties($previousMode, $dataUser, $this->formData);
            }


            // Rechargement de la page avec l'identifiant récupéré (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesUtilisateur->errors) && empty($this->errors))
            {
                // On recharge la page en mode visualisation.
                header("Location: ".$this->url.$this->formData['ref_user']);
            }
            else 
            {
                // Sinon mode nouveau ou édition.
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
 
        
        /*** Mode "suppression" ***/
        
        else if ($this->formData['mode'] == "delete")
        {
            // Verrouillage des boutons.
            $this->servicesGestion->switchFormButtons($this->formData, "delete");
            
            // Si la référence de l'utilisateur active existe :
            if (!empty($this->formData['ref_user']))
            {
                // On supprime la catégorie dans la base.
                $resultsetUser = $this->servicesUtilisateur->deleteUser($this->formData['ref_user']);
                
                // Si la suppression a fonctionnée :
                if ($resultsetUser)
                {   
                    $this->registerSuccess("L'utilisateur a été supprimé avec succès.");
                }
                else
                {
                    // Sinon on renvoi une erreur.
                    $this->registerError("form_valid", "L'utilisateur n'a pas pu être supprimé.");
                }
            }
            else 
            {
                $this->registerError("form_valid", "L'utilisateur n'existe pas.");
            }
            
            
            // On recharge la page (sans aucune information).
            //header("Location: ".$this->url);
            //exit();
        }


        /*** Erreur : aucun mode ***/

        else  
        {
            // Renvoi vers le template 404 (page inconnue).
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
        


        /*** Retour des données traitées du formulaire ***/

        $this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;


        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesUtilisateur->errors) && count($this->servicesUtilisateur->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesUtilisateur->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesUtilisateur->success) && count($this->servicesUtilisateur->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesUtilisateur->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant d'afficher les éléments du formulaire (liste déroulante, checkbox). ***/

        $listeUsers['organ'] = array();
        

        // Requete pour obtenir la liste des organismes
        $listeOrgan = $this->servicesRestitution->getOrganismesList();


        if (!$this->filterDataErrors($listeOrgan['response']))
        {
            $i = 0;

            foreach($listeOrgan['response']['organisme'] as $organisme)
            {
                $listeUsers['organ'][$i]['ref_organ'] = $organisme->getId();
                $listeUsers['organ'][$i]['nom_organ'] = $organisme->getNom();
                $listeUsers['organ'][$i]['user'] = array();

                /*** On va chercher tous les utilisateurs qui correspondent à l'organisme ***/
                
                $resultsetUsers = $this->servicesRestitution->getUsersFromOrganisme($organisme->getId());

                if (!$this->filterDataErrors($resultsetUsers['response']))
                {
                    $j = 0;

                    foreach($resultsetUsers['response']['utilisateur'] as $user)
                    {
                        $listeUsers['organ'][$i]['user'][$j]['ref_user'] = $user->getId();
                        $listeUsers['organ'][$i]['user'][$j]['nom_user'] = $user->getNom();
                        $listeUsers['organ'][$i]['user'][$j]['prenom_user'] = $user->getPrenom();
                        $j++;
                    }
                }   

                $i++;
            }
        }



        // Requete pour obtenir la liste des niveaux d'études
        $listeNiveauxEtudes = $this->servicesUtilisateur->getNiveauxEtudes();
        
        // Assemblage de toutes les données de la réponse
        $this->returnData['response'] = array_merge($listeUsers, $this->returnData['response']);
        $this->returnData['response'] = array_merge($listeNiveauxEtudes['response'], $this->returnData['response']);

        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_utilisateur"); 
        
    }

    



    /**
     * activite - Gére la validation du formulaire de gestion des activités avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (comme la référence de l'activité)
     */
    public function activite($requestParams = array())
    {
        
        // Authentification
        ServicesAuth::checkAuthentication("admin");
        
        $this->setTemplate("template_page");
        $this->render("gestion_activite"); 
        
    }
	




    /**
     * categorie - Gére la validation du formulaire de gestion des catégories avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     * 
     * @param array Tableau de paramètres passés par url (comme le code de la categorie)
     */
    public function compte($requestParams = array())
    {

        // Authentification
        ServicesAuth::checkAuthentication("admin");

        
        $this->initialize();
        
        $this->url = SERVER_URL."admin/compte/";
        
        // Initialisation du tableau des données qui seront inserées ou mises à jour dans la base.
        $dataAccount = array();


        /*** Définition du mode précédent du formulaire (permet de connaître l'action précédemment choisie par l'utilisateur) ***/

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


        /*** On initialise les données qui vont être validées et renvoyées au formulaire ***/
        
        $initializedData = array(
            "ref_account_cbox" => "select", 
            "nom_admin"           => "text",  
            "pass_admin"          => "text",
            "pass_admin_verif"    => "text",
            "droits_cbox"         => "select"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);
        
    
        /*** Récupération du code de la catégorie par la méthode GET ***/
        
        if (isset($requestParams[0]) && !empty($requestParams[0]) && is_numeric($requestParams[0]))
        {
            $this->formData['ref_account_cbox'] = $requestParams[0];
        }
        
        $this->formData['ref_account'] = $this->formData['ref_account_cbox'];
        

        /*** Initialisation des boutons ***/

        $this->servicesGestion->switchFormButtons($this->formData, "init");
       



        /*-----   Action a effectuée selon le mode soumis par le formulaire  -----*/
        

        /*-----   Mode "visualisation" et "edition"   -----*/

        
        if ($this->formData['mode'] == "view" || $this->formData['mode'] == "edit")
        { 
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, $this->formData['mode']);
            
            if (!empty($this->formData['ref_account']))
            {
                if ($this->formData['mode'] == "view")
                {
                    // Déverrouillage des boutons "modifier" et "supprimer"
                    $this->formData['edit_disabled'] = "";
                    $this->formData['delete_disabled'] = "disabled";
                }
                
                $accountDetails = array();
                $accountDetails = $this->servicesCompte->getAccountDetails($this->formData['ref_account']);

                $this->formData = array_merge($this->formData, $accountDetails);
            }
            else if ($this->formData['mode'] == "edit")
            {
                $this->registerError("form_empty", "Ce compte n'existe pas");
            }
        }
        
        
        /*-----   Mode "nouveau compte"   -----*/
        
        
        else if ($this->formData['mode'] == "new")
        {      
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "new");

            $this->formData['ref_account'] = null;
            $this->formData['nom_admin'] = null;
            $this->formData['pass_admin'] = null;
            $this->formData['pass_admin_verif'] = null;
            $this->formData['droits'] = null;
        }
  
        
        
        /*-----   Mode "enregistrement"   -----*/
        
        
        else if ($this->formData['mode'] == "save")
        {
            
            // Verrouillage des boutons
            $this->servicesGestion->switchFormButtons($this->formData, "save");


            /*** Récupèration de l'id de la question ***/

            if (!empty($this->formData['ref_account']))
            {
                if ($previousMode == "edit")
                {
                    $dataAccount['ref_account'] = $this->formData['ref_account'];
                }
            }
            
            
            /*-----  Traitement des infos saisies   -----*/
            
            $dataAccount = $this->servicesCompte->filterAccountData($this->formData, $_POST);

            
            /*----- Sauvegarde ou mise à jour des données ***/
            
            // Aucune erreur ne doit être enregistrée
            if (empty($this->servicesCompte->errors) && empty($this->errors)) 
            {
                $this->servicesCompte->saveAccountData($previousMode, $dataAccount, $this->formData);
            }
            
            /*** S'il n'y a pas d'erreur, on recharge la page avec l'identifiant récupéré ***/
            
            if (empty($this->servicesCompte->errors) && empty($this->errors))
            {
                // On recharge la page en mode view
                header("Location: ".$this->url.$this->formData['ref_account']);
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
            
            // On récupère le code du compte actif
            if (!empty($this->formData['ref_account']))
            {
                // Ensuite on supprime le compte dans la base
                $resultsetCompte = $this->servicesCompte->deleteAccount($this->formData['ref_account']);

                if ($resultsetCompte)
                {   
                    $this->registerSuccess("Le compte a été supprimé avec succès.");
                }
                else
                {
                    $this->registerError("form_data", "Le compte n'a pas pu être supprimé.");
                }
            }
            else 
            {
                $this->registerError("form_data", "Le compte n'existe pas.");
            }
            
            // On recharge la page
            //header("Location: ".$this->url);
            //exit();
        }


        else  
        {
            // Sinon, renvoi vers la page inconnue (404)
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }   
          
        
        
        /*-----   Retour des données traitées du formulaire   -----*/


        $this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;

        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesCompte->errors) && count($this->servicesCompte->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesCompte->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesCompte->success) && count($this->servicesCompte->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesCompte->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
        
        
        /*** Ensemble des requêtes permettant d'afficher les éléments du formulaire (liste déroulante, checkbox). ***/
        
        // Requete pour obtenir la liste des comptes
        $listeComptes = $this->servicesCompte->getAccountsList();

        $this->returnData['response'] = array_merge($listeComptes['response'], $this->returnData['response']);



        /*** Envoi des données et rendu de la vue ***/
        
        $this->setResponse($this->returnData);
        $this->setTemplate("template_page");
        $this->render("gestion_compte");    
    }




	
    /* Redirection de la page "admin/restitution" vers la page "public/restitution" */
    public function restitution()
    {
        
        // Authentification
        //ServicesAuth::checkAuthentication("custom");

        header("Location: ".SERVER_URL."public/restitution");
        exit();
    }
	



    /* Redirection de la page "admin/statistique" vers la page "public/statistique" */
	public function statistique()
    {

        // Authentification
        //ServicesAuth::checkAuthentication("custom");
        
        header("Location: ".SERVER_URL."public/statistique");
        exit();
	}
    
}


?>
