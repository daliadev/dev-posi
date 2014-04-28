<?php

/**
 * 
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'controls/services_admin_gestion.php');
require_once(ROOT.'controls/services_admin_restitution.php');
require_once(ROOT.'models/dao/organisme_dao.php');



class ServicesPublic extends Main
{

    
    private $servicesRestitution = null;
    private $organismeDAO = null;
    
    private $servicesGestion = null;
    
    
    public function __construct() 
    {
        $this->controllerName = "public";
        
        $this->servicesGestion = new ServicesAdminGestion();
        
        $this->servicesRestitution = new ServicesAdminRestitution();
        $this->organismeDAO = new OrganismeDAO();
    }
    
    

    
    /**
     * restitution - Gére la validation du formulaire de gestion des degrés d'aptitude avec insertion et mises à jour des données du formulaire et renvoie les données vers la vue.
     *
     * @param array Tableau de paramètres passés par url (le code d'identification de l'organisme)
     */
    public function restitution($requestParams = array())
    {

        $this->initialize();

        $codeOrgan = "";
        $loggedAsViewer = false;
        $loggedAsAdmin = false;
        $preSelectOrganisme = null;

        
        // on vérifie s'il y a un code dans les parametres url
        if (isset($requestParams[0]) && !empty($requestParams[0]))
        {   
            if (preg_match("`^[a-zA-Z0-9]*$`", $requestParams[0]) && strlen($requestParams[0]) == 8)
            {
                // On récupère le code
                $codeOrgan = $requestParams[0];
                
                // On va chercher le code organisme correspondant
                $preSelectOrganisme = $this->organismeDAO->selectByCodeInterne($codeOrgan);
                
                if (!$this->filterDataErrors($preSelectOrganisme['response']))
                {
                    if (!empty($preSelectOrganisme['response']['organisme']) && count($preSelectOrganisme['response']['organisme']) == 1)
                    { 
                        $organ = $preSelectOrganisme['response']['organisme'];
                        $preSelectOrganisme['response']['organisme'] = array($organ);
                    }
                    $loggedAsViewer = true;
                }
                else 
                {
                    // Redirection vers une page d'erreur interne
                    header("Location: ".SERVER_URL."erreur/page500");
                    exit();
                }
            }
            else 
            {
                // Redirection vers une page d'erreur non autorisé
                header("Location: ".SERVER_URL."erreur/page503");
                exit();
            }
        }
        else 
        {
            // Sinon, authentification de l'admin necessaire
            ServicesAuth::checkAuthentication("admin");
            $loggedAsAdmin = true;
        }
        
        
        $this->servicesRestitution->initialize();
        
        $this->url = SERVER_URL."public/restitution/".$codeOrgan;

        
        if (Config::DEBUG_MODE)
        {
            echo "\$_POST = ";
            var_dump($_POST);
        }

        

        /*** Requêtes ajax ***/


        if (Config::ALLOW_AJAX)
        {
            if ($loggedAsViewer || $loggedAsAdmin)
            {
                if (isset($_POST['sort']) && !empty($_POST['sort']))
                {

                    if ($_POST['sort'] == "user")
                    {
                        if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
                        {
                            $utilisateurs = $this->servicesRestitution->getUsersFromOrganisme($_POST['ref_organ']);
                            
                            if ($utilisateurs['response']['utilisateur'])
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
                            
                            if ($sessions['response']['session'])
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


                        /*
                        if (empty($_POST['ref']))
                        {
                            $response = array('error' => "Vous n'avez pas sélectionné de positionnement.");
                        }
                        else
                        {
                            $session = $this->servicesRestitution->getUserSessions($_POST['ref_user'], $_POST['ref_organ']);
                            
                            if ($session)
                            {
                                $response = array('error' => false, 'results' => $session);
                            }
                            else
                            {
                                $response = array('error' => "Il n'existe pas de positionnement qui correspond à l'utilisateur.");
                            }
                        }
                        */
                    }
                    else
                    {
                        $response = array('error' => "Le type n'a pas été trouvé.");
                    }

                    echo json_encode($response);
                    exit();
                }
            }
        }

        /*** Fin requêtes ajax ***/


	       
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
        
        // On récupère les differents identifiants de la zone de sélection 
        $this->formData['ref_organ'] = $this->formData['ref_organ_cbox'];
        $this->formData['ref_user'] = $this->formData['ref_user_cbox'];
        $this->formData['ref_session'] = $this->formData['ref_session_cbox'];
        
        // Sauf si c'est un intervenant auquel cas l'organisme est déjà connu
        if ($loggedAsViewer)
        {
            $this->formData['ref_organ'] = $preSelectOrganisme['response']['organisme'][0]->getId();
        }
        
        
        /*** Initialisation des infos sur le positionnement ***/
  
        
        // On commence par obtenir le nom et l'id de chaque organisme de la table "organisme"
        if ($loggedAsViewer)
        {
            $organismesList = $preSelectOrganisme;
        }
        else if ($loggedAsAdmin)
        {
            $organismesList = $this->servicesRestitution->getOrganismesList(); 
        }
        $this->returnData['response'] = array_merge($organismesList['response'], $this->returnData['response']);
        
        $nomOrgan = null;
        $codeOrgan = null;
        foreach ($organismesList['response']['organisme'] as $organisme)
        {
            if ($organisme->getId() == $this->formData['ref_organ'])
            {
                $nomOrgan = $organisme->getNom();
                $codeOrgan = $organisme->getNumeroInterne();
            }
        }
        
        // Pour chaque combo-box sélectionné, on effectue les requetes correspondantes
        
        /*------   Un organisme a été sélectionnée   -------*/
        
        if (!empty($this->formData['ref_organ']) && $this->formData['ref_organ'] != "select_cbox")
        {
            // Initialisation des infos principales
            $this->returnData['response']['infos_user']['nom_organ'] = $nomOrgan;
            $this->returnData['response']['infos_user']['code_organ'] = $codeOrgan;
            $this->returnData['response']['infos_user']['nom_intervenant'] = "";
            $this->returnData['response']['infos_user']['email_intervenant'] = "";
            $this->returnData['response']['infos_user']['nom'] = "";
            $this->returnData['response']['infos_user']['prenom'] = "";
            $this->returnData['response']['infos_user']['date_naiss'] = "";
            $this->returnData['response']['infos_user']['nom_niveau'] = "";
            $this->returnData['response']['infos_user']['descript_niveau'] = "";
            $this->returnData['response']['infos_user']['nbre_positionnements'] = "";
            $this->returnData['response']['infos_user']['date_last_posi'] = "";
            
            
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
                $this->returnData['response']['infos_user']['code_organ'] = $codeOrgan;
                

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
        $this->returnData['response']['url'] = $this->url;

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

            if ($this->returnData['response']['infos_user']['nom'] && $this->returnData['response']['infos_user']['prenom'])
            {
                $dateSession = Tools::toggleDate(substr($this->returnData['response']['session'][0]->getDate(), 0, 10));
                $timeToSeconds = Tools::timeToSeconds(substr($this->returnData['response']['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); 
                $time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); 
                
                $file = $this->returnData['response']['infos_user']['nom']."_".$this->returnData['response']['infos_user']['prenom']."_".$dateSession."_".$time.".pdf";
                $this->renderPDF("restitution_pdf", $file, "D");
            }
            else 
            {
                $this->returnData['response']['errors'][] = array('type' => "form_valid", 'message' => "Le PDF n'a pu être générer. Veuillez réessayer ultérieurement.");
            }

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