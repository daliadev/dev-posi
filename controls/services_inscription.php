<?php


require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_inscription_gestion.php');

        


class ServicesInscription extends Main
{
   
    private $servicesGestion = null;
    private $servicesInscriptGestion = null;

    
    
    public function __construct()
    {

        $this->controllerName = "inscription";
        
        $this->servicesGestion = new ServicesAdminGestion();
        $this->servicesInscriptGestion = new ServicesInscriptionGestion();
    }
    
  



    
    public function organisme($requestParams = array())
    {

        $this->initialize();
        
        $this->url = SERVER_URL."inscription/organisme/";

        // Si une authentification est déjà active, on la stoppe
        ServicesAuth::logout();


        /*** Requêtes ajax ***/

        if (Config::ALLOW_AJAX)
        {   

            $error = "";

            $towns = null;
            $searchInter = "";

            $searchResults = array();
            $resultString = "";

            if (isset($_POST['search_interv']) && !empty($_POST['search_interv']) && isset($_POST['ref_organ']) && !empty($_POST['ref_organ'])) {

                $searchInter = $_POST['search_interv'];
                $refOrgan = $_POST['ref_organ'];

                /*** On récupère également les infos sur l'intervenant ***/
                $resultsetIntervenant = $this->servicesRestitution->getIntervenants($resultsetSession['response']['session'][0]->getRefIntervenant());
                $this->returnData['response']['infos_user']['nom_intervenant'] = $resultsetIntervenant['response']['intervenant'][0]->getNom();
                $this->returnData['response']['infos_user']['search_interv'] = $resultsetIntervenant['response']['intervenant'][0]->getEmail();
            }


            if ($file !== 0) {

                $towns = unserialize($file);

                if (!is_array($towns)) {

                    $towns = array();
                    $error = "La liste est vide.";
                }

                foreach ($towns as $key => $townName) {

                    if (stripos($townName, $searchString) === 0) {
                        
                        array_push($searchResults, $townName);
                    }
                }

                sort($searchResults);
            }
            else {

                $error = "Impossible de lire le fichier de données.";
            }


            $resultString = implode("|", $searchResults);


            if (empty($error)) {

                echo $resultString;
            }
    


            $searchInter = "";

            if (isset($_POST['email_intervenant']) && !empty($_POST['email_intervenant']))
            {
                $searchInter = $_POST['email_intervenant']

            }
                if ($_POST['sort'] == "user")
                {
                    if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
                    {
                        $utilisateurs = $this->servicesRestitution->getUsersFromOrganisme($_POST['ref_organ']);
                        
                        if ($utilisateurs)
                        {
                            $response = array('error' => false, 'results' => $utilisateurs['response']);
                        }
                        else
                        {
                            $response = array('error' => "Il n'existe pas d'utilisateur qui correspond à l'organisme.");
                        }
                    }
                    else
                    {
                        $response = array('error' => "Vous n'avez pas sélectionné d'organisme.");
                    }
                }
                else if ($_POST['sort'] == "session")
                {

                    if ((isset($_POST['ref_organ']) && !empty($_POST['ref_organ'])) && (isset($_POST['ref_user']) && !empty($_POST['ref_user'])))
                    {
                        $sessions = $this->servicesRestitution->getUserSessions($_POST['ref_user'], $_POST['ref_organ']);
                        
                        if ($sessions)
                        {
                            $i = 0;

                            foreach($sessions['response']['session'] as $session)
                            {
                                $id = $session->getId();
                                $date = Tools::toggleDate(substr($session->getDate(), 0, 10));
                                $timeToSeconds = Tools::timeToSeconds(substr($session->getDate(), 11, 8), $inputFormat = "h:m:s");
                                $time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));

                                $sessions['response']['session'][$i] = array();
                                $sessions['response']['session'][$i]['id'] = $id;
                                $sessions['response']['session'][$i]['date'] = $date;
                                $sessions['response']['session'][$i]['time'] = $time;

                                $i++;
                            }

                            $response = array('error' => false, 'results' => $sessions['response']);
                        }
                        else
                        {
                            $response = array('error' => "Il n'existe pas de positionnement qui correspond à l'utilisateur.");
                        }
                    }
                    else
                    {
                        $response = array('error' => "Vous n'avez pas sélectionné d'utilisateur.");
                    }
                }
                else
                {
                    $response = array('error' => "Le type n'a pas été trouvé.");
                }

                echo json_encode($response);
                exit();
            }
        }

        /*** Fin requêtes ajax ***/


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
            'ref_inter_cbox' => "select",
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
                $mdpUser = ServicesAuth::hashPassword($_POST['code_identification']);
                $codes = Config::getCodeOrganisme();

                $isAuth = false;

                foreach ($codes as $code)
                {
                    if ($mdpUser === $code)
                    {
                        $isAuth = true;
                    }
                }

                if ($isAuth)
                {
                    // authentifié
                    ServicesAuth::login("user");
                }
                else
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide");
                }
                /*
                if ($code == Config::getCodeOrganisme())
                {
                    // authentifié
                    ServicesAuth::login("user");
                }
                else
                {
                    $this->registerError("form_valid", "Le code organisme n'est pas valide");
                }
                */
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
            
            $this->setTemplate("tpl_inscript");
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
            
            $this->setTemplate("tpl_inscript");
            $this->render("utilisateur");
        }
    }
    
}

?>
