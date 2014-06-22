<?php

/**
 * Description of serviceInscriptionOrganisme
 *
 * @author Nicolas Beurion
 */


require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_inscription_gestion.php');
//require_once(ROOT.'controls/services_erreur.php');

        


class ServicesInscription extends Main
{
   
    private $servicesGestion = null;
    private $servicesInscriptGestion = null;

    
    
    public function __construct()
    {

        $this->controllerName = "inscription";
        
        $this->servicesGestion = new ServicesAdminGestion();
        $this->servicesInscriptGestion = new ServicesInscriptionGestion();
        
        //$this->initialize();
    }
    
    
    /**
     * public formulaire ($requestParams = array(), $returnData = array())
     * 
     * Fournit les données d'affichage des formulaires organisme, intervenant et utilisateur
     * 
     * @param array $requestParams Contient le type de formulaire
     * @param array $returnData Retour des données après validation
     * 
     */
    public function formulaire($requestParams = array(), $returnData = array())
    {
        $this->initialize();
        
        if (!isset($requestParams) || empty($requestParams))
        {
            $requestParams = array("organisme");
        }
        
        if (!empty($returnData))
        {
            //$returnData['response'] = array();
            //$returnData['response']['errors'] = array();
            $this->returnData = $returnData;
        }
        
        
       
        /*
        if (!empty($this->errors) && count($this->errors) > 0)
        {
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        */
        
        /*--- Aiguillage vers le formulaire organisme ou utilisateur ---*/
        
        if ($requestParams[0] == "organisme")
        {
            // On déconnecte la session précédente s'il y en a eu une 
            ServicesAuth::logout();
            
            // Requete pour obtenir la liste des organismes
            $listeOrganismes = $this->servicesInscriptGestion->getOrganismes();
            
            
            $this->filterDataErrors($listeOrganismes['response']);

            if (!empty($listeOrganismes['response']['organisme']) && count($listeOrganismes['response']['organisme']) == 1)
            { 
                $organisme = $listeOrganismes['response']['organisme'];
                $listeOrganismes['response']['organisme'] = array($organisme);
            }

            // On agglomère toutes les données
            $this->returnData['response'] = array_merge($listeOrganismes['response'], $this->returnData['response']);
            
            $this->setResponse($this->returnData);
            $this->setTemplate("template_page");
            $this->render("form_organisme");
        }
        
        
        
        else if ($requestParams[0] == "utilisateur")
        {
            // Requete pour obtenir la liste des niveaux d'études
            $listeNiveauxEtudes = $this->servicesInscriptGestion->getNiveauxEtudes();

            // On récupère les erreurs de la requête s'il y en a
            if (isset($listeNiveauxEtudes['response']['errors']) && !empty($listeNiveauxEtudes['response']['errors']) && count($this->errors) > 0)
            {
                foreach($listeNiveauxEtudes['response']['errors'] as $error)
                {
                    $this->returnData['response']['errors'][] = $error;
                }
            }
            
            // On récupère toutes les données
            $this->returnData['response'] = array_merge($listeNiveauxEtudes['response'], $this->returnData['response']);

            $this->setResponse($this->returnData);
            $this->setTemplate("template_page");
            $this->render('form_utilisateur');
        }
        else
        {
            // Si aucun type de formulaire n'est demandé, redirection vers la page 404
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
        
    }
    
    
    
    
    
    /** 
     * validation - Valide les formulaires organisme et utilisateur et affiche les formulaires correspondants.
     * 
     * @param array $requestParams Un tableau contenant le type de formulaire à valider (ex : "organisme" ou "utilisateur")
     * 
     */
    public function validation($requestParams = array())
    {
        
        $this->initialize();
        
       
        /*** Dispatch de la validation selon le formulaire à valider  ***/
        
        if (!isset($requestParams) || (empty($requestParams)))
        {
            $requestParams = array("organisme");
        }

        
        if ($requestParams[0] == "organisme")
        {

            /*------------------------------------------------------------------*/
            /*    Traitements des données reçues par le formulaire organisme    */
            /*------------------------------------------------------------------*/

            
            /*** Initialisation des données qui vont être validées et renvoyées ***/
            
            $this->formData = array(
                //'ref_code_organ' => null,
                'ref_organ_cbox' => null,
                'ref_organ' => null,
                'ref_intervenant' => null,
                'date_inscription' => null,
                'nom_organ' => null,
                'numero_interne' => null,
                'adresse_organ' => null,
                'code_postal_organ' => null,
                'ville_organ' => null,
                'tel_organ' => null,
                'fax_organ' => null,
                'email_organ' => null,
                'nom_intervenant' => null,
                'tel_intervenant' => null,
                'email_intervenant' => null
            );
            
            
            /*** Récupération des champs cachés ***/
            
            // Récupération du champ "ref_organ" si il existe
            if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
            {
                $this->formData['ref_organ'] = $_POST['ref_organ'];
            }
            
            // Récupération du champ "ref_intervenant" si il existe
            if (isset($_POST['ref_intervenant']) && !empty($_POST['ref_intervenant']))
            {
                $this->formData['ref_intervenant'] = $_POST['ref_intervenant'];
            }
            
            // Récupération du champ "date_inscription" si il existe
            /*
            if (isset($_POST['date_inscription']) && !empty($_POST['date_inscription']))
            {
                $this->formData['date_inscription'] = $_POST['date_inscription'];
            }
            */
            
            
            /*-----   Validation de tous les champs de l'organisme   -----*/
            
            /*** Authentification du code organisme ***/
            
            // Récupération du code
            if (!isset($_POST['code_identification']) || empty($_POST['code_identification']))
            {
                $this->registerError("form_empty", "Aucun code organisme n'a été saisi.");
            }
            else 
            {
                //$this->formData['code_identification'] = $_POST['code_identification'];
                

                $code = ServicesAuth::hashPassword($_POST['code_identification']);


                if ($code == Config::getCodeOrganisme())
                {
                    // authentifié
                    ServicesAuth::login("user");
                }
                else
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide.");
                }
                
                // Vérification du code organisme
                /*
                $codeOrganisme = $this->servicesInscriptGestion->authenticateOrganisme($this->formData['code_identification']);
                
                if (!empty($codeOrganisme) && $codeOrganisme['response']['auth'] && !empty($codeOrganisme['response']['ref_code_organisme']))
                {
                    $this->formData['ref_code_organ'] = $codeOrganisme['response']['ref_code_organisme'];
                }
                else 
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide");
                }
                */

                // Vérification du code organisme (version simple, voir fichier config.php)
                /*
                $codeOrganisme = Config::getCodeOrganisme($this->formData['code_identification'])

                if ($codeOrganisme == $this->formData['code_identification'])
                {

                }
                else 
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide");
                }
                */

                // On supprime le code organisme par sécurité (??)
                //unset($$code);
            }

            
            /*** Traitement de la valeur de la liste(combo-box)  ***/
            
            $modeOrganisme = "none";
                    
            // Récupération du nom de l'organisme s'il a été correctement sélectionné ou saisi
            if (!empty($_POST['ref_organ_cbox']))
            {
                $this->formData['ref_organ_cbox'] = $_POST['ref_organ_cbox'];
                        
                if ($_POST['ref_organ_cbox'] == "select_cbox")
                {
                    // Aucun nom n'a été sélectionné ou saisi : erreur
                    $this->registerError("form_empty", "Aucun nom d'organisme n'a été sélectionné.");
                }
                else if ($_POST['ref_organ_cbox'] == "new")
                {
                    // Un nom a été saisi, il faut donc inserer les données de l'organisme
                    $modeOrganisme = "insert";
                }
                else 
                {
                    // Un nom a été sélectionné dans la liste, l'organisme existe déjà, on récupère la référence
                    $this->formData['ref_organ'] = $_POST['ref_organ_cbox'];
                    //$modeOrganisme = "none";
                }
            }
        
            
            /*** Traitement des champs de l'organisme qui seront ensuite inséré dans la base ***/
            
            $dataOrganisme = array();
                
            if ($modeOrganisme == "insert")
            {
                // Génération d'un numero interne de l'organisme qui sert à vérifier l'organisme lors de la restitution par les intervenants
                // on ne garde que les 8 premiers caractères
                $code = substr(dechex(round(microtime(true) * 10000)), 0, 8);
                $this->formData['numero_interne'] = $code;

                // Tableau des champs traites et leurs propriétés 
                $this->formData['nom_organ'] = $this->validatePostData($_POST['nom_organ'], "nom_organ", "string", true, "Le nom de l'organisme est incorrect.", "Le nom de l'organisme n'a pas été saisi.");
                $this->formData['code_postal_organ'] = $this->validatePostData($_POST['code_postal_organ'], "code_postal_organ", "integer", true, "Le code postal est incorrect.", "Le code postal n'a pas été saisi.");
                $this->formData['tel_organ'] = $this->validatePostData($_POST['tel_organ'], "tel_organ", "integer", true, "Le numéro de téléphone est incorrect.", "Le numéro de téléphone n'a pas été saisi.");


                /* Traitement particulier du nom de l'organisme pour l'insertion */
                
                // Si le nom de l'organisme n'est pas vide, il a été saisi et il doit être comparé aux autres noms d'organisme
                if (!empty($this->formData['nom_organ']) && empty($this->formData['ref_organ']))
                {
                    // Selection du nom de l'organisme dans la base
                    $nomOrganisme = $this->servicesInscriptGestion->getOrganisme('nom_organ', $this->formData['nom_organ']);
                    
                    // Si la requête trouve un nom d'organisme correspondant, c'est un doublon !
                    if (!empty($nomOrganisme['response']['organisme']))
                    {
                        $this->registerError("form_valid", "Le nom de l'organisme existe déjà.");
                    }
                }
                else 
                {
                    $this->registerError("form_empty", "Aucun nom n'a été saisi");
                }
                
                // A décommenter pour la version sécurisée
                //$dataOrganisme['ref_code_organ'] = $this->formData['ref_code_organ'];
                $dataOrganisme['nom_organ'] = $this->formData['nom_organ'];
                $dataOrganisme['code_postal_organ'] = $this->formData['code_postal_organ'];
                $dataOrganisme['tel_organ'] = $this->formData['tel_organ'];
                $dataOrganisme['numero_interne'] = $this->formData['numero_interne'];
            }
            else if (isset($this->formData['ref_organ']) && !empty($this->formData['ref_organ']))
            {
                $dataOrganisme['ref_organ'] = $this->formData['ref_organ'];
            }
            
            

            /*-----   Validation de tous les champs de l'intervenant et de la date d'inscription   -----*/

            
            /*** Traitement des champs de l'intervenant qui seront ensuite insérés dans la base ***/
            
            $dataIntervenant = array();
            
            $this->formData['email_intervenant'] = $this->validatePostData($_POST['email_intervenant'], "email_intervenant", "email", true, "L'email n'est pas valide.", "L'email n'a pas été saisi.");
            //$this->formData['date_inscription'] = $this->validatePostData($_POST['date_inscription'], "date_inscription", "date", true, "La date d'inscription n'est pas valide.", "La date d'inscription n'a pas été saisie.");
            $this->formData['date_inscription'] = date("Y-m-d");
            

            /*** Traitement de doublon de l'email de l'intervenant et définition du mode de la requête ***/
            
            $modeIntervenant = "insert";
            
            // Si l'email de l'intervenant existe déja pour cet organisme, on change de mode pour une mise à jour
            $request = $this->servicesInscriptGestion->getIntervenant("email", $this->formData['email_intervenant']);
            
            if (isset($request['response']['intervenant']) && !empty($request['response']['intervenant']))
            {
                $modeIntervenant = "update";

                // On récupère la référence de l'intervenant
                $this->formData['ref_intervenant'] = $request['response']['intervenant']->getId();
                $dataIntervenant['ref_intervenant'] = $this->formData['ref_intervenant'];
            }
            

            /*** Valeurs finales des champs qui seront inserés dans la table intervenant ***/
            
            //$dataIntervenant['nom_intervenant'] = $this->formData['nom_intervenant'],
            //$dataIntervenant['tel_intervenant'] = $this->formData['tel_intervenant'],
            $dataIntervenant['email_intervenant'] = $this->formData['email_intervenant'];
            // La date d'inscription ne fait pas partie de l'intervenant

            
            
            if (Config::DEBUG_MODE)
            {
                echo "\$dataOrganisme  = <br/>";
                var_dump($dataOrganisme);
                echo "\$dataIntervenant  = <br/>";
                var_dump($dataIntervenant);
            }
            
            
            
            /*-----   Insertion ou mise à jour de l'organisme   -----*/
            
            
            // Il ne doit y avoir aucune erreur
            if (empty($this->errors)) 
            {
                // Tous les champs obligatoires de l'organisme doivent être remplis
                if ((empty($dataOrganisme['ref_organ']) && !empty($dataOrganisme['nom_organ']) && !empty($dataOrganisme['code_postal_organ']) && !empty($dataOrganisme['tel_organ'])) || !empty($dataOrganisme['ref_organ']))
                {
                    $resultsetOrganisme = $this->servicesInscriptGestion->setOrganisme($modeOrganisme, $dataOrganisme);
                    
                    // Si la requête d'insertion est correcte, on récupére l'id de l'organisme inseré
                    if ($modeOrganisme == "insert")
                    {
                        if (isset($resultsetOrganisme['response']['organisme']['last_insert_id']) && !empty($resultsetOrganisme['response']['organisme']['last_insert_id']))
                        {
                            $this->formData['ref_organ'] = $resultsetOrganisme['response']['organisme']['last_insert_id'];
                        }
                        else 
                        {
                            $this->registerError("form_request", "Insertion de l'organisme impossible.");
                        }
                    }
                }
                else 
                {
                    $this->registerError("form_request", "Des données de l'organisme sont absentes du formulaire.");
                }
            }
            
            
            
            /*-----   Insertion ou mise à jour de l'intervenant   -----*/
            
            
            // Il ne doit y avoir aucune erreur pour l'insertion de l'intervenant
            if (empty($this->errors)) 
            {
                // L'email de l'intervenant est obligatoire pour l'insertion de l'intervenant ainsi que la référence organisme liée à l'intervenant
                if (!empty($this->formData['email_intervenant']) && isset($this->formData['ref_organ']) && !empty($this->formData['ref_organ']))
                {
                    $dataIntervenant['ref_organ'] = $this->formData['ref_organ'];

                    // Insertion de l'intervenant dans la base
                    $resultsetIntervenant = $this->servicesInscriptGestion->setIntervenant($modeIntervenant, $dataIntervenant);

                    // Si la requête d'insertion est correcte, on récupére l'id de l'organisme inseré
                    if ($modeIntervenant == "insert")
                    {
                        // si la requête d'insertion est correcte, on récupére l'id de l'insertion
                        if (isset($resultsetIntervenant['response']['intervenant']['last_insert_id']) && !empty($resultsetIntervenant['response']['intervenant']['last_insert_id']))
                        {
                            $this->formData['ref_intervenant'] = $resultsetIntervenant['response']['intervenant']['last_insert_id'];
                        }
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Des données de l'intervenant sont absentes du formulaire.");
            }
        }

        
        
        else if ($requestParams[0] == "utilisateur")
        {
            
            // Authentification du visiteur necessaire (code organisme)
            ServicesAuth::checkAuthentication("user");
            
            
            /*--------------------------------------------------------------------*/
            /*    Traitements des données reçues par le formulaire utilisateur    */
            /*--------------------------------------------------------------------*/
            
            
            /*** Initialisation des données qui vont être validées et renvoyées ***/
            
            $this->formData = array(
                'ref_user' => null,
                'ref_intervenant' => null,
                'date_inscription' => null,
                'ref_niveau' => null,
                'ref_niveau_cbox' => null,
                'nom_user' => null,
                'prenom_user' => null,
                'date_naiss_user' => null,
                'adresse_user' => null,
                'code_postal_user' => null,
                'ville_user' => null,
                'tel_user' => null,
                'email_user' => null,
            );
            
            
            /*** Récupération des champs cachés ***/
            
            // Récupération du champ caché "référence utilisateur" si il existe
            if (isset($_POST['ref_user']) && !empty($_POST['ref_user']))
            {
                $this->formData['ref_user'] = $_POST['ref_user'];
            }
            
            // Récupération du champ caché "reférence intervenant" si il existe
            if (isset($_POST['ref_intervenant']) && !empty($_POST['ref_intervenant']))
            {
                $this->formData['ref_intervenant'] = $_POST['ref_intervenant'];
            }
                    
            // Récupération du champ caché "date d'inscription" si il existe
            if (isset($_POST['date_inscription']) && !empty($_POST['date_inscription']))
            {
                $this->formData['date_inscription'] = $_POST['date_inscription'];
            }
 
            
            /*-----   Validation de tous les champs de l'utilisateur   -----*/
            
            
            /*** Traitement de la valeur de la liste(combo-box) niveau d'études ***/
            
            // Récupération de l'id du niveau d'études s'il a été correctement sélectionné ou saisi
            if (!empty($_POST['ref_niveau_cbox']))
            {
                $this->formData['ref_niveau_cbox'] = $_POST['ref_niveau_cbox'];
                        
                if ($_POST['ref_niveau_cbox'] == "select_cbox")
                {
                    // Aucun niveau n'a été sélectionné ou saisi : erreur
                    $this->registerError("form_empty", "Aucun niveau d'études n'a été sélectionné");
                }
                else 
                {
                    // Un niveau a été sélectionné dans la liste
                    $this->formData['ref_niveau'] = $_POST['ref_niveau_cbox'];
                }
            }

            
            /*** Traitement des champs de l'utilisateur ***/
            
            $dataUtilisateur = array();
            
            $this->formData['nom_user'] = $this->validatePostData($_POST['nom_user'], "nom_user", "string", true, "Le nom de l'utilisateur est incorrecte.", "Le nom de l'utilisateur n'a pas été saisi.");
            $this->formData['prenom_user'] = $this->validatePostData($_POST['prenom_user'], "prenom_user", "string", true, "Le prénom de l'utilisateur est incorrecte.", "Le prénom de l'utilisateur n'a pas été saisi.");
            $this->formData['date_naiss_user'] = $this->validatePostData($_POST['date_naiss_user'], "date_naiss_user", "date", true, "La date de naissance de l'utilisateur est incorrecte.", "La date de naissance de l'utilisateur n'a pas été saisi.");
            //$this->formData['adresse_user'] = $this->validatePostData($_POST['adresse_user'], "adresse_user", "string", false, "L'adresse de l'utilisateur est incorrecte.", "L'adresse de l'utilisateur n'a pas été saisi.");
            //$this->formData['code_postal_user'] = $this->validatePostData($_POST['code_postal_user'], "code_postal_user", "integer", false, "Le code postal de l'utilisateurest incorrecte.", "Le code postal de l'utilisateur n'a pas été saisi.");
            //$this->formData['ville_user'] = $this->validatePostData($_POST['ville_user'], "ville_user", "string", false, "La ville de l'utilisateur est incorrecte.", "La ville de l'utilisateur n'a pas été saisi.");
            //$this->formData['tel_user'] = $this->validatePostData($_POST['tel_user'], "tel_user", "string", false, "Le numéro de téléphone de l'utilisateur est incorrecte.", "Le numéro de téléphone de l'utilisateur n'a pas été saisi.");
            //$this->formData['email_user'] = $this->validatePostData($_POST['email_user'], "email_user", "email", false, "L'email de l'utilisateur est incorrecte.", "L'email de l'utilisateur n'a pas été saisi.");


            /*** Traitement de doublon de l'utilisateur et définition du mode de la requête ***/
            
            // Par défaut, l'utilisateur sera inséré dans la base
            $modeUtilisateur = "insert";
            
            $user = $this->servicesInscriptGestion->getUtilisateur('duplicate_user', array('nom_user' => $this->formData['nom_user'], 'prenom_user' => $this->formData['prenom_user'], 'date_naiss_user' => Tools::toggleDate($this->formData['date_naiss_user'], "us")));

            
            // S'il y a déjà une personne similaire, c'est une mise à jour de l'utilisateur dans la base
            if (isset($user['response']['utilisateur']) && !empty($user['response']['utilisateur']))
            {
                $modeUtilisateur = "update";
                // On récupère la référence de l'intervenant
                $this->formData['ref_user'] = $user['response']['utilisateur']->getId();
                $dataUtilisateur['ref_user'] = $this->formData['ref_user'];
            }
            
            
            /*** Valeurs des champs qui seront inserés dans la table utilisateur ***/
 
            $dataUtilisateur['ref_niveau'] = $this->formData['ref_niveau'];
            $dataUtilisateur['nom_user'] = $this->formData['nom_user'];
            $dataUtilisateur['prenom_user'] = $this->formData['prenom_user'];
            $dataUtilisateur['date_naiss_user'] = Tools::toggleDate($this->formData['date_naiss_user'], "us");
            //$dataUtilisateur['adresse_user'] = $this->formData['adresse_user'];
            //$dataUtilisateur['code_postal_user'] = $this->formData['code_postal_user'];
            //$dataUtilisateur['ville_user'] = $this->formData['ville_user'];
            //$dataUtilisateur['tel_user'] = $this->formData['tel_user'];
            //$dataUtilisateur['email_user'] = $this->formData['email_user'];

            
            
            /*-----   Validation de tous les champs de l'inscription   -----*/
            
            
            /*** Traitement des champs de l'inscription qui seront ensuite insérés dans la base ***/

            // Par défaut, l'inscription sera inséré dans la base.
            $modeInscription = "insert";
            
            // Si l'utilisateur est déjà enregistré
            if (!empty($this->formData['ref_user']))
            {
                // On doit vérifié si l'inscription n'a pas déjà été faite avec le même intervenant et le même utilisateur.
                $inscript = $this->servicesInscriptGestion->getInscription('references', array('ref_user' => $this->formData['ref_user'], 'ref_intervenant' => $this->formData['ref_intervenant']));
                
                // Si oui, on ne fait rien
                if (isset($inscript['response']['inscription']) && !empty($inscript['response']['inscription']))
                {
                    if ($inscript['response']['inscription']->getRefUtilisateur() == $this->formData['ref_user'] && $inscript['response']['inscription']->getRefIntervenant() == $this->formData['ref_intervenant'])
                    {
                        $this->formData['ref_inscription'] = $inscript['response']['inscription']->getId();
                        $modeInscription = "none";
                    }
                }
            }
            
            
            /*** Valeurs des champs qui seront inserés dans la table inscription ***/
            
            $dataInscription = array();
            
            if (isset($this->formData['ref_user']) && !empty($this->formData['ref_user']))
            {
                $dataInscription['ref_user'] = $this->formData['ref_user'];
            }
            $dataInscription['ref_intervenant'] = $this->formData['ref_intervenant'];
            
            if (isset($this->formData['date_inscription']) && !empty($this->formData['date_inscription']))
            {
                $dataInscription['date_inscription'] = $this->formData['date_inscription'];
            }
            else 
            {
                $this->registerError("form_request", "Des données sont absentes du formulaire.");
            }
            
            
            
            if (Config::DEBUG_MODE)
            {
                echo "\$modeUtilisateur  = ".$modeUtilisateur.'<br />';
                echo "\$modeInscription  = ".$modeInscription.'<br />';
            }
            
            
            /*-----   Insertion ou mise à jour de l'utilisateur   -----*/
            
            // S'il n'y a aucune erreur
            if (empty($this->errors)) 
            {
                // Tous les champs obligatoires de l'utilisateur doivent être remplis
                if (!empty($dataUtilisateur['ref_niveau']) && !empty($dataUtilisateur['nom_user']) && !empty($dataUtilisateur['prenom_user']) && !empty($dataUtilisateur['date_naiss_user']))
                {
                    /*--- Insertion de l'utilisateur dans la base ---*/
                    
                    $resultsetUtilisateur = $this->servicesInscriptGestion->setUtilisateur($dataUtilisateur, $modeUtilisateur);
                    
                    // Si la requête d'insertion est correcte, on récupére l'id de l'utilisateur inseré
                    if ($modeUtilisateur == "insert")
                    {
                        if (isset($resultsetUtilisateur['response']['utilisateur']['last_insert_id']) && !empty($resultsetUtilisateur['response']['utilisateur']['last_insert_id']))
                        {
                            $this->formData['ref_user'] = $resultsetUtilisateur['response']['utilisateur']['last_insert_id'];
                            $dataInscription['ref_user'] = $this->formData['ref_user'];

                            // On fusionne le retour des données de l'utilisateur aves les données traitées du formulaire
                            //$returnData['response'] = array_merge($requestUtilisateur['response'], $returnData['response']);
                        }
                        else 
                        {
                            $this->registerError("form_request", "Insertion de l'utilisateur impossible");
                        }
                    }
                }
            }
            else 
            {
                $this->registerError("form_request", "Des données sont absentes du formulaire.");
            }

            
            /*-----   Insertion ou mise à jour de l'inscription   -----*/

            // S'il n'y a aucune erreur
            if (empty($this->errors)) 
            {
                // Tous les champs obligatoires de l'utilisateur doivent être remplis
                if (!empty($dataInscription['ref_user']) && !empty($dataInscription['ref_intervenant']) && !empty($dataInscription['date_inscription']))
                {
                    /*** Insertion de l'inscription dans la base ***/
                    
                    $resultsetInscription = $this->servicesInscriptGestion->setInscription($dataInscription, $modeInscription);
                    
                    if ($modeInscription == "insert")
                    {
                        if (isset($resultsetInscription['response']['inscription']['last_insert_id']) || !empty($resultsetInscription['response']['inscription']['last_insert_id']))
                        {
                            $this->formData['ref_inscription'] = $resultsetInscription['response']['inscription']['last_insert_id'];
                            //$returnData['response'] = array_merge($resultsetInscription['response'], $returnData['response']);
                        }
                        else 
                        {
                            $this->registerError("form_request", "Insertion de l'inscription impossible");
                        }
                    }
                }
            }
            else 
            {               
                $this->registerError("form_request", "Des données sont absentes du formulaire.");
            }
            
            if (Config::DEBUG_MODE)
            {
                echo "\$dataUtilisateur  = <br/>";
                var_dump($dataUtilisateur);
                echo "\$dataInscription  = <br/>";
                var_dump($dataInscription);
                
                echo 'erreurs = <br />';
                var_dump($this->errors);
            }
            
        }
        
        
        
        else 
        {
            // Si aucun formulaire, redirection vers la page 404
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
        
        
        /*--- Retour des données traitées du formulaire ---*/

        $this->returnData['response']['form_data'] = $this->formData;
        
        if (!empty($this->errors) && count($this->errors) > 0)
        {
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if (!empty($this->success) && count($this->success) > 0)
        {
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }
      
        /*-----------------------------------------------------------*/
        /*    Redirection selon le résultat du formulaire courant    */
        /*-----------------------------------------------------------*/


        // S'il y a des erreurs on appelle de nouveau le formulaire (la page n'est pas rechargée)
        if (!empty($this->errors))
        {
            $this->formulaire($requestParams, $this->returnData);
        }
        else
        {
            // Sinon redirection vers la page suivante (recharge la page)
            if ($requestParams[0] == "organisme")
            {
                // On doit conserver certaines informations pour le formulaire utilisateur
                $this->returnData['response'] = array();
                $this->returnData['response']['ref_intervenant'] = $this->formData['ref_intervenant'];
                $this->returnData['response']['date_inscription'] = $this->formData['date_inscription'];

                // Redirection vers le formulaire utilisateur
                $this->formulaire(array("utilisateur"), $this->returnData);
            }
            else if ($requestParams[0] == "utilisateur")
            {
                // On doit conserver l'id de l'utilisateur pour le positionnement             
                ServicesAuth::setSessionData('ref_inscription', $this->formData['ref_inscription']);
                ServicesAuth::setSessionData('ref_intervenant', $this->formData['ref_intervenant']);
                ServicesAuth::setSessionData('ref_user', $this->formData['ref_user']);
                
                // Redirection vers la première page du positionnement
                header("Location: ".SERVER_URL."positionnement/intro/");
                exit;
            }
            else 
            {
                header("Location: ".SERVER_URL."erreur/page404");
                exit();
            }
        }
  
    }









    
    public function organisme($requestParams = array())
    {

        $this->initialize();
        
        $this->url = SERVER_URL."inscription/organisme/";

        // Si une authentification est déjà active, on la stoppe
        ServicesAuth::logout();


        /*** Initialisation des données ***/

        // Initialisation des tableaux de données qui seront inserés ou mis à jour dans la base.
        $dataOrganisme = array();
        $dataIntervenant = array();


        // On initialise les données qui vont être validées et renvoyées au formulaire
        
        $initializedData = array(
            'ref_organ_cbox' => "select",
            'ref_organ' => "text",
            'nom_organ' => "text",
            'numero_interne' => "text",
            'adresse_organ' => "text",
            'code_postal_organ' => "text",
            'ville_organ' => "text",
            'tel_organ' => "text",
            'fax_organ' => "text",
            'email_organ' => "text",
            'nbre_posi' => "text",
            'nbre_posi_max' => "text",
            'ref_intervenant' => "text",
            'nom_intervenant' => "text",
            'tel_intervenant' => "text",
            'email_intervenant' => "text"
            //'date_inscription' => "text"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);


        /*** Filtrage et enregistrement des données ***/

        if (!empty($_POST))
        {
            
            /*** Authentification du code organisme ***/
                
            // Récupération du code
            if (isset($_POST['code_identification']) && !empty($_POST['code_identification'])) 
            {
                $code = ServicesAuth::hashPassword($_POST['code_identification']);

                if ($code == Config::getCodeOrganisme())
                {
                    // authentifié
                    ServicesAuth::login("user");
                }
                else
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide");
                }
            }
            else
            {
                $this->registerError("form_empty", "Aucun code organisme n'a été saisi");
            }


            /*** Récupération des données postées ***/

            // Traitement et récupération des infos saisies de l'organisme
            $dataOrganisme = $this->servicesInscriptGestion->filterDataOrganisme($this->formData, $_POST); 

            // Traitement et récupération des infos saisies de l'intervenant
            $dataIntervenant = $this->servicesInscriptGestion->filterDataIntervenant($this->formData, $_POST);



            /*** Sauvegarde des données dans la base ***/

            // Sauvegarde ou mise à jour des données de l'organisme (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesInscriptGestion->errors) && empty($this->errors)) 
            {
                $this->servicesInscriptGestion->setOrganismeProperties($dataOrganisme, $this->formData);
            }

            // Sauvegarde ou mise à jour des données de l'intervenant (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesInscriptGestion->errors) && empty($this->errors)) 
            {
                $this->servicesInscriptGestion->setIntervenantProperties($dataIntervenant, $this->formData);
            }


            /*
            var_dump($this->formData);
            var_dump($dataOrganisme);
            var_dump($dataIntervenant);
            var_dump($this->servicesInscriptGestion->errors);
            var_dump($this->errors);
            exit();
            */
        }



        /*** Retour des données traitées du formulaire ***/

        //$this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;
        $this->returnData['response']['url'] = $this->url;

        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesInscriptGestion->errors) && count($this->servicesInscriptGestion->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesInscriptGestion->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesInscriptGestion->success) && count($this->servicesInscriptGestion->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesInscriptGestion->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }


        /*** Collecte des données pour l'affichage de la page ***/

        $listeOrganismes = $this->servicesInscriptGestion->getOrganismes();


        // Assemblage de toutes les données de la réponse
        if ($listeOrganismes)
        {
            $this->returnData['response'] = array_merge($listeOrganismes['response'], $this->returnData['response']);
        }
        

        /*** Envoi des données et rendu de la vue ***/

        if (empty($this->errors) && !empty($_POST))
        {
            // On doit conserver certaines informations pour le formulaire utilisateur
            ServicesAuth::setSessionData('ref_organ', $this->formData['ref_organ']);
            ServicesAuth::setSessionData('ref_intervenant', $this->formData['ref_intervenant']);

            // Redirection vers le formulaire utilisateurs
            header("Location: ".SERVER_URL."inscription/utilisateur/");
            exit;

        }
        // Si c'est la première visite ou s'il y a des erreurs on affiche le formulaire organisme
        else
        {
            $this->setResponse($this->returnData);
            
            $this->setTemplate("template_page");
            $this->render("organisme");
        }

    }
    

    

    
    public function utilisateur($requestParams = array())
    {

        // Authentification du visiteur necessaire (code organisme)
        ServicesAuth::checkAuthentication("user");
        

        $this->initialize();
        
        $this->url = SERVER_URL."inscription/utilisateur/";


        /*** Initialisation des données ***/

        // Initialisation des tableaux de données qui seront inserés ou mis à jour dans la base.
        $dataInscription = array();
        $dataUtilisateur = array();


        // On initialise les données qui vont être validées et renvoyées au formulaire5
        
        $initializedData = array(
            'ref_user' => "text",
            'nom_user' => "text",
            'prenom_user' => "text",
            'jour_naiss_user_cbox' => "select",
            'jour_naiss_user' => "text",
            'mois_naiss_user_cbox' => "select",
            'mois_naiss_user' => "text",
            'annee_naiss_user_cbox' => "select",
            'annee_naiss_user' => "text",
            'adresse_user' => "text",
            'code_postal_user' => "text",
            'ville_user' => "text",
            'tel_user' => "text",
            'email_user' => "text",
            'ref_niveau_cbox' => "select",
            'ref_niveau' => "text",
            'ref_intervenant' => "text",
            'date_inscription' => "text",
            'name_validation' => "text"
        );
        $this->servicesGestion->initializeFormData($this->formData, $_POST, $initializedData);



        /*** Filtrage et enregistrement des données ***/

        if (!empty($_POST))
        {

            /*** Récupération des données postées ***/

            // Traitement et récupération des infos saisies de l'utilisateur
            $dataUtilisateur = $this->servicesInscriptGestion->filterDataUtilisateur($this->formData, $_POST);

            // Traitement et récupération des infos saisies pour l'inscription
            $dataInscription = $this->servicesInscriptGestion->filterDataInscription($this->formData, $_POST); 


            /*** Sauvegarde des données dans la base ***/

            // Sauvegarde ou mise à jour des données de l'inscription (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesInscriptGestion->errors) && empty($this->errors)) 
            {
                $this->servicesInscriptGestion->setUtilisateurProperties($dataUtilisateur, $this->formData);
            }

            // Sauvegarde ou mise à jour des données de l'utilisateur (aucune erreur ne doit être enregistrée).
            if (empty($this->servicesInscriptGestion->errors) && empty($this->errors)) 
            {
                $this->servicesInscriptGestion->setInscriptionProperties($dataInscription, $this->formData);
            }

            /*
            if (!empty($this->formData['name_validation']) && $this->formData['name_validation'] == "true")
            {
                var_dump($this->formData);
                var_dump($dataUtilisateur);
                var_dump($dataInscription);
                var_dump($this->servicesInscriptGestion->errors);
                var_dump($this->errors);
                exit();
            }
            */
        }


        /*** Retour des données traitées du formulaire ***/

        //$this->returnData['response']['form_data'] = array();
        $this->returnData['response']['form_data'] = $this->formData;
        $this->returnData['response']['url'] = $this->url;

        
        /*** S'il y a des erreurs ou des succès, on les injecte dans la réponse ***/
        
        if ((!empty($this->servicesInscriptGestion->errors) && count($this->servicesInscriptGestion->errors) > 0) || !empty($this->errors))
        {
            $this->errors = array_merge($this->servicesInscriptGestion->errors, $this->errors);
            foreach($this->errors as $error)
            {
                $this->returnData['response']['errors'][] = $error;
            }
        }
        else if ((!empty($this->servicesInscriptGestion->success) && count($this->servicesInscriptGestion->success) > 0) || !empty($this->success))
        {
            $this->success = array_merge($this->servicesInscriptGestion->success, $this->success);
            foreach($this->success as $success)
            {
                $this->returnData['response']['success'][] = $success;
            }
        }



        /*** Collecte des données pour l'affichage de la page ***/

        $listeNiveaux = $this->servicesInscriptGestion->getNiveauxEtudes();

        // Assemblage de toutes les données de la réponse
        if ($listeNiveaux)
        {
            $this->returnData['response'] = array_merge($listeNiveaux['response'], $this->returnData['response']);
        }


        // S'il n'y a aucune erreur, On passe à la page d'intro du positionnement
        if (empty($this->errors) && !empty($_POST))
        {
            // On doit conserver certaines informations du formulaire utilisateur
            ServicesAuth::setSessionData('ref_user', $this->formData['ref_user']);
            ServicesAuth::setSessionData('ref_inscription', $this->formData['ref_inscription']);

            // Redirection vers la page d'intro
            header("Location: ".SERVER_URL."positionnement/intro/");
            exit;

        }
        // Si c'est la première visite ou s'il y a des erreurs on affiche le formulaire utilisateur
        else
        {
            $this->setResponse($this->returnData);
            
            $this->setTemplate("template_page");
            $this->render("utilisateur");
        }
    }
    
}

?>
