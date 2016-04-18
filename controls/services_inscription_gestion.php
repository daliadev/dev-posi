<?php


require_once(ROOT.'controls/authentication.php');


// Fichiers requis pour le formulaire organisme
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');

// Fichiers requis pour le formulaire utilisateur
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/inscription_dao.php');



class ServicesInscriptionGestion extends Main
{
    
    private $organismeDAO = null;
    private $intervenantDAO = null;
    private $utilisateurDAO = null;
    private $niveauEtudesDAO = null;
    private $inscriptionDAO = null;
    
    
    
    public function __construct() 
    {
        $this->organismeDAO = new OrganismeDAO();
        $this->intervenantDAO = new IntervenantDAO();
        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
        $this->inscriptionDAO = new InscriptionDAO();
    }
    



    public function getOrganismes()
    {
        $resultset = $this->organismeDAO->selectAll();
        

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
            { 
                $organisme = $resultset['response']['organisme'];
                $resultset['response']['organisme'] = array($organisme);
            }

            for ($i = 0; $i < count($resultset['response']['organisme']); $i++)
            {
                $organToUpper = mb_strtoupper($resultset['response']['organisme'][$i]->nom_organ, 'UTF-8');
                $resultset['response']['organisme'][$i]->nom_organ = $organToUpper;
            }

            return $resultset;
        }
        
        return false;
    }




    public function getNiveauxEtudes()
    {
        $resultset = $this->niveauEtudesDAO->selectAll();
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['niveau_etudes']) && count($resultset['response']['niveau_etudes']) == 1)
            { 
                $niveau = $resultset['response']['niveau_etudes'];
                $resultset['response']['niveau_etudes'] = array($niveau);
            }

            return $resultset;
        }
        
        return false;
    }







    /***   Filtrage et traitement des données des formulaires   ***/


    public function filterDataOrganisme(&$formData, $postData)
    {
        $dataOrganisme = array();
        $formData['mode_organ'] = "none";
        $formData['ref_organ'] = null;

        /*** Récupération du champ caché "ref_organ" si il existe ***/
           
        if (isset($postData['ref_organ']) && !empty($postData['ref_organ']))
        {
            $formData['ref_organ'] = $postData['ref_organ'];
        }
        

        /*** Récupèration de la référence de l'organisme dans le combo-box ***/

        if (!empty($postData['ref_organ_cbox']))
        {
            $formData['ref_organ_cbox'] = $postData['ref_organ_cbox'];
                    
            if ($postData['ref_organ_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun organisme n'a été sélectionné.");
            }
            else if ($postData['ref_organ_cbox'] == "new")
            {
                // Un nom a été saisi, il faut donc inserer les données de l'organisme
                $formData['ref_organ'] = null;
                $formData['mode_organ'] = "insert";

                // Génération d'un numero interne de l'organisme qui sert à vérifier l'organisme lors de la restitution par les intervenants
                // on ne garde que les 8 premiers caractères
                $code = substr(dechex(round(microtime(true) * 10000)), 0, 8);
                $formData['numero_interne'] = $code;
                $dataOrganisme['numero_interne'] = $formData['numero_interne'];
            }
            else 
            {
                $formData['ref_organ'] = $postData['ref_organ_cbox'];
            }
        }

        if ($formData['ref_organ'])
        {
            $dataOrganisme['ref_organ'] = $formData['ref_organ'];


            $resultsetOrgan = $this->getOrganisme('id_organ', $formData['ref_organ']);

            if ($resultsetOrgan)
            {
                $nbrePosiTotal = $resultsetOrgan['response']['organisme'][0]->getNbrePosiTotal();
                $nbrePosiMax = $resultsetOrgan['response']['organisme'][0]->getNbrePosiMax();

                if ($nbrePosiMax > 0 && $nbrePosiTotal >= $nbrePosiMax)
                {
                    $this->registerError("form_valid", "Il n'est plus possible d'effectuer de positionnements avec cet organisme.");
                }
            }
        }
        

        if ($formData['mode_organ'] == "insert")
        {
            /*** Traitement particulier de doublon du nom de l'organisme ***/

            // Si le nom de l'organisme n'est pas vide, il a été saisi et il doit être comparé aux autres noms d'organisme.
            if (!empty($postData['nom_organ']))
            {
                $duplicateNomOrgan = false;

                // On enlève les espaces, les caractères spéciaux et on met le nom saisi tout en majuscules.
                $cleanNom = Tools::stripSpecialCharsFromString($postData['nom_organ']);
                $nomOrganisme = preg_replace("#[^A-Z]#", "", strtoupper($cleanNom));

                // On va chercher tous les noms d'organismes dans la base.
                $resultsetOrgan = $this->getOrganismes();

                if ($resultsetOrgan)
                {
                    foreach ($resultsetOrgan['response']['organisme'] as $organ)
                    {
                        $nomOrgan = $organ->getNom();

                        $cleanNomOrgan = Tools::stripSpecialCharsFromString($nomOrgan);

                        // On enlève les espaces, les caractères spéciaux et on met le tout en majuscules.
                        $securNomOrgan = preg_replace("#[^A-Z]#", "", strtoupper($cleanNomOrgan));

                        // Si les 2 noms sont similaires, on envoie une erreur.
                        if ($securNomOrgan == $nomOrganisme)
                        {
                            $duplicateNomOrgan = true;
                            break;
                        }
                    }
                }

                if ($duplicateNomOrgan)
                {
                    $this->registerError("form_valid", "Le nom de l'organisme existe déjà.");
                }
            }

            // Récupèration du nom de l'organisme
            $formData['nom_organ'] = $this->validatePostData($postData['nom_organ'], "nom_organ", "string", true, "Aucun nom d'organisme n'a été sélectionné.", "Le nom de l'organisme n'est pas correctement sélectionné.");
            $dataOrganisme['nom_organ'] = $formData['nom_organ'];

            // Récupèration de l'adresse de l'organisme
            //$formData['adresse_organ'] = $this->validatePostData($postData['adresse_organ'], "adresse_organ", "string", false, "Aucune adresse n'a été saisie.", "L'adresse l'organisme n'est pas correctement saisie.");
            //$dataOrganisme['adresse_organ'] = $formData['adresse_organ'];

            // Récupèration du code postal de l'organisme
            $formData['code_postal_organ'] = $this->validatePostData($postData['code_postal_organ'], "code_postal_organ", "integer", true, "Aucun code postal n'a été saisi.", "Le code postal de l'organisme n'est pas correctement saisi.");
            $dataOrganisme['code_postal_organ'] = $formData['code_postal_organ'];

            // Récupèration de la ville de l'organisme
            //$formData['ville_organ'] = $this->validatePostData($postData['ville_organ'], "ville_organ", "string", false, "Aucune ville n'a été saisie.", "La ville de l'organisme n'est pas correctement saisie.");
            //$dataOrganisme['ville_organ'] = $formData['ville_organ'];

            // Récupèration du téléphone de l'organisme
            $formData['tel_organ'] = $this->validatePostData($postData['tel_organ'], "tel_organ", "integer", true, "Aucun numéro de téléphone n'a été saisi.", "Le numéro de téléphone de l'organisme n'est pas correctement saisi.");
            $dataOrganisme['tel_organ'] = $formData['tel_organ'];

            // Récupèration du fax de l'organisme
            //$formData['fax_organ'] = $this->validatePostData($postData['fax_organ'], "fax_organ", "string", false, "Aucun numéro de fax n'a été saisi.", "Le numéro de fax de l'organisme n'est pas correctement saisi.");
            //$dataOrganisme['fax_organ'] = $formData['fax_organ'];

            // Récupèration de l'email de l'organisme
            //$formData['email_organ'] = $this->validatePostData($postData['email_organ'], "email_organ", "email", false, "Aucun email n'a été saisi.", "L'email de l'organisme n'est pas correctement saisi.");
            //$dataOrganisme['email_organ'] = $formData['email_organ'];
            
        }
        
        return $dataOrganisme;
    }

    



    public function filterDataIntervenant(&$formData, $postData)
    {
        $dataIntervenant = array();
        $formData['mode_inter'] = "none";
        $formData['ref_intervenant'] = null;
        $formData['ref_intervenant'] = null;

        // Récupération du champ caché "ref_intervenant" si il existe
        
        if (isset($postData['ref_intervenant']) && !empty($postData['ref_intervenant']))
        {
            $formData['ref_intervenant'] = $postData['ref_intervenant'];
            $dataIntervenant['ref_intervenant'] = $formData['ref_intervenant'];
        }
        
        if (isset($formData['ref_organ']) && !empty($formData['ref_organ']))
        {
            $dataIntervenant['ref_organ'] = $formData['ref_organ'];
        }



        // Récupèration du nom de l'intervenant
        //$formData['nom_intervenant'] = $this->validatePostData($postData['nom_intervenant'], "nom_intervenant", "string", true, "Aucun nom n'a été saisi.", "Le nom de l'intervenant n'est pas correctement saisi.");
        //$dataIntervenant['nom_intervenant'] = $formData['nom_intervenant'];
        
        // Récupèration du téléphone de l'intervenant
        //$formData['tel_intervenant'] = $this->validatePostData($postData['tel_intervenant'], "tel_intervenant", "integer", true, "Aucun numéro de téléphone n'a été saisi.", "Le numéro de téléphone de l'intervenant n'est pas correctement saisi.");
        //$dataIntervenant['tel_intervenant'] = $formData['tel_intervenant'];

        // Récupèration de l'email de l'intervenant
        if (Config::ALLOW_REFERENT_INPUT == 1)
        {
            $formData['email_intervenant'] = $this->validatePostData($postData['email_intervenant'], "email_intervenant", "email", false, "Aucun email n'a été saisi.", "L'email de l'intervenant n'est pas correctement saisi.");
            $dataIntervenant['email_intervenant'] = $formData['email_intervenant'];
        }
        else 
        {
            if (!empty($postData['ref_inter_cbox']))
            {
                $formData['ref_inter_cbox'] = $postData['ref_inter_cbox'];
                        
                if ($postData['ref_inter_cbox'] == "select_cbox" && isset(Config::$emails_referent) && is_array(Config::$emails_referent) && count(Config::$emails_referent) > 0)
                {
                    $this->registerError("form_empty", "Aucun formateur n'a été sélectionné.");
                }
                else 
                {
                    $formData['email_intervenant'] = $postData['ref_inter_cbox'];
                    $dataIntervenant['email_intervenant'] = $formData['email_intervenant'];
                }
            }
        }

        //$this->formData['date_inscription'] = date("Y-m-d");
        //$dataIntervenant['date_inscription'] = $formData['date_inscription'];

        /*** Traitement de doublon de l'email de l'intervenant et définition du mode de la requête ***/
        
        $formData['mode_inter'] = "insert";
        
        // Si l'email de l'intervenant existe déja pour cet organisme, on change de mode pour une mise à jour
        $resultsetInter = $this->getIntervenant("email", $formData['email_intervenant']);
        
        if (isset($resultsetInter['response']['intervenant']) && !empty($resultsetInter['response']['intervenant']))
        {
            $refOrgan = $resultsetInter['response']['intervenant'][0]->getRefOrganisme();

            if (isset($formData['ref_organ']) && !empty($formData['ref_organ']) && $refOrgan == $formData['ref_organ'])
            {
                $formData['mode_inter'] = "none";
                $formData['ref_intervenant'] = $resultsetInter['response']['intervenant'][0]->getId();
            }
            else if (Config::ALLOW_REFERENT_FOR_MULTI_ORGAN == 0)
            {
                $this->registerError("form_valid", "L'email a déjà été saisi pour un autre organisme.");
            }
        }


        return $dataIntervenant;
    }





    public function filterDataUtilisateur(&$formData, $postData)
    {

        $dataUtilisateur = array();

        $formData['mode_user'] = "insert";
        $formData['ref_user'] = null;


        /*** Récupération du champ caché "référence utilisateur" si il existe ***/
        if (isset($postData['ref_user']) && !empty($postData['ref_user']))
        {
            $formData['ref_user'] = $postData['ref_user'];
        }

        /*** Récupération du champ caché "validation du nom" si il existe ***/

        $formData['name_validation'] = "none";

        if (isset($postData['name_validation']) && !empty($postData['name_validation']))
        {
            $formData['name_validation'] = $postData['name_validation'];
        }
        

        /*** Traitement de la valeur de la liste(combo-box) niveau d'études ***/
         
        // Récupération de l'id du niveau d'études s'il a été correctement sélectionné ou saisi
        if (!empty($postData['ref_niveau_cbox']))
        {
            $formData['ref_niveau_cbox'] = $postData['ref_niveau_cbox'];
                    
            if ($postData['ref_niveau_cbox'] == "select_cbox")
            {
                // Aucun niveau n'a été sélectionné ou saisi : erreur
                $this->registerError("form_empty", "Aucun niveau d'études n'a été sélectionné.");
            }
            else 
            {
                // Un niveau a été sélectionné dans la liste
                $formData['ref_niveau'] = $postData['ref_niveau_cbox'];
            }
        }
        
        if ($formData['ref_niveau'])
        {
            $dataUtilisateur['ref_niveau'] = $formData['ref_niveau'];
        }
        


        /*** Traitement des valeur des listes(combo-box) de la date de naissance ***/

        // Récupération du jour de naissance
        if (!empty($postData['jour_naiss_user_cbox']))
        {
            $formData['jour_naiss_user_cbox'] = $postData['jour_naiss_user_cbox'];
                    
            if ($postData['jour_naiss_user_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun jour de naissance n'a été sélectionné.");
            }
            else 
            {
                $formData['jour_naiss_user'] = $postData['jour_naiss_user_cbox'];
            }
        }

        // Récupération du mois de naissance
        if (!empty($postData['mois_naiss_user_cbox']))
        {
            $formData['mois_naiss_user_cbox'] = $postData['mois_naiss_user_cbox'];
                    
            if ($postData['mois_naiss_user_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun mois de naissance n'a été sélectionné.");
            }
            else 
            {
                $formData['mois_naiss_user'] = $postData['mois_naiss_user_cbox'];
            }
        }

        // Récupération de l'année de naissance
        if (!empty($postData['annee_naiss_user_cbox']))
        {
            $formData['annee_naiss_user_cbox'] = $postData['annee_naiss_user_cbox'];
                    
            if ($postData['annee_naiss_user_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucune année de naissance n'a été sélectionné.");
            }
            else 
            {
                $formData['annee_naiss_user'] = $postData['annee_naiss_user_cbox'];
            }
        }

        // On en déduit la date de naissance, et on crée la date au format us pour la sauvegarde des données
        if ($formData['jour_naiss_user'] && $formData['mois_naiss_user'] && $formData['annee_naiss_user'])
        {
            $formData['date_naiss_user'] = $formData['jour_naiss_user']."/".$formData['mois_naiss_user']."/".$formData['annee_naiss_user'];
            $dataUtilisateur['date_naiss_user'] = $formData['annee_naiss_user']."-".$formData['mois_naiss_user']."-".$formData['jour_naiss_user'];
        }
        else
        {
            $this->registerError("form_data", "La date de naissance n'a pas été sélectionnée correctement.");
        }



        /*** Récupèration du nom et du prénom de l'utilisateur ***/

        $formData['nom_user'] = $this->validatePostData($postData['nom_user'], "nom_user", "string", true, "Aucun nom n'a été saisi.", "Le nom de l'utilisateur n'est pas correctement saisi.");
        $formData['prenom_user'] = $this->validatePostData($postData['prenom_user'], "prenom_user", "string", true, "Aucun prénom n'a été saisi.", "Le nom de l'utilisateur n'est pas correctement saisi.");

        $formData['nom_user'] = strtoupper($formData['nom_user']);

        /*** Traitement de l'identification d'un utilisateur similaire à la saisie ***/

        $duplicateNomsUser = false;


        // On assaini d'abord le nom et le prenom saisi
        $cleanPrenomSaisi = Tools::stripSpecialCharsFromString($formData['prenom_user']);
        $cleanNomSaisi = Tools::stripSpecialCharsFromString($formData['nom_user']);

        $prenomUserSaisi = preg_replace("#[^A-Z]#", "", strtoupper($cleanPrenomSaisi));
        $nomUserSaisi = preg_replace("#[^A-Z]#", "", strtoupper($cleanNomSaisi));

        //echo "Noms saisis = ".$prenomUserSaisi." ".$nomUserSaisi."<br/>";

        
        
        // On va chercher tous les utilisateurs qui ont la même date de naissance que la saisie
        $resultsetUser = $this->getUtilisateur("date_naissance", Tools::toggleDate($formData['date_naiss_user'], "us"));
        
        if ($resultsetUser)
        {
            foreach ($resultsetUser['response']['utilisateur'] as $user)
            {
                $prenomUser = $user->getPrenom();
                $nomUser = $user->getNom();

                // On enlève les espaces, les caractères spéciaux et on met le nom saisi tout en majuscules.
                $cleanPrenom = Tools::stripSpecialCharsFromString($prenomUser);
                $cleanNom = Tools::stripSpecialCharsFromString($nomUser);

                $prenomUser = preg_replace("#[^A-Z]#", "", strtoupper($cleanPrenom));
                $nomUser = preg_replace("#[^A-Z]#", "", strtoupper($cleanNom));

                $duplicatePrenom = (strpos($prenomUser, $prenomUserSaisi) !== false) ? true : false;
                $duplicatePrenomInv = (strpos($prenomUserSaisi, $prenomUser) !== false) ? true : false;

                $duplicateNom = (strpos($nomUser, $nomUserSaisi) !== false) ? true : false;
                $duplicateNomInv = (strpos($nomUserSaisi, $nomUser) !== false) ? true : false;

                /*
                echo ('prenomUser = ' . $prenomUser.' - prenomUserSaisi = ' . $prenomUserSaisi . '<br>');
                echo ('strpos prenomUser = ' . $duplicatePrenom . '<br>');
                echo ('strpos prenomUser inverse = ' . $duplicatePrenomInv . '<br>');

                echo ('nomUser = ' . $nomUser.' - nomUserSaisi = ' . $nomUserSaisi . '<br>');
                echo ('strpos nomUser = ' . $duplicateNom . '<br>');
                echo ('strpos nomUser inverse = ' . $duplicateNomInv . '<br>');

                exit();
                */

                if (($duplicatePrenom || $duplicatePrenomInv) && ($duplicateNom || $duplicateNomInv))
                {
                    $formData['ref_user'] = $user->getId();

                    if (intval($formData['ref_niveau']) === intval($user->getRefNiveau()))
                    {
                        $formData['mode_user'] = "none";
                    }
                    else
                    {
                        $formData['mode_user'] = "update";
                    }

                    $duplicateNomsUser = true;
                    break;
                }
                
            }
        }

        if ($duplicateNomsUser)
        {
            $dataUtilisateur['ref_user'] = $formData['ref_user'];

            if (!empty($formData['name_validation']))
            {
                if ($formData['name_validation'] == "false" || $formData['name_validation'] == "none")
                {
                    $formData['name_validation'] = "false";
                    $this->registerError("form_valid", "Le nom de l'utilisateur existe déjà.");
                }
            }


        }

        // Traitement du nom et prénom de l'utilisateur pour l'insertion en base de données
        $dataUtilisateur['nom_user'] = $formData['nom_user'];
        $dataUtilisateur['prenom_user'] = $formData['prenom_user'];


        /*** Récupèration des autres champs de l'utilisateur ***/

        // Récupèration de l'adresse de l'organisme
        //$formData['adresse_user'] = $this->validatePostData($postData['adresse_user'], "adresse_user", "string", false, "Aucune adresse n'a été saisie.", "L'adresse l'utilisateur n'est pas correctement saisie.");
        //$dataUtilisateur['adresse_user'] = $formData['adresse_user'];

        // Récupèration du code postal de l'organisme
        //$formData['code_postal_user'] = $this->validatePostData($postData['code_postal_user'], "code_postal_user", "integer", false, "Aucun code postal n'a été saisi.", "Le code postal de l'utilisateur n'est pas correctement saisi.");
        //$dataUtilisateur['code_postal_user'] = $formData['code_postal_user'];

        // Récupèration de la ville de l'organisme
        //$formData['ville_user'] = $this->validatePostData($postData['ville_user'], "ville_user", "string", false, "Aucune ville n'a été saisie.", "La ville de l'utilisateur n'est pas correctement saisie.");
        //$dataUtilisateur['ville_user'] = $formData['ville_user'];

        // Récupèration du téléphone de l'organisme
        //$formData['tel_user'] = $this->validatePostData($postData['tel_user'], "tel_user", "integer", false, "Aucun numéro de téléphone n'a été saisi.", "Le numéro de téléphone de l'utilisateur n'est pas correctement saisi.");
        //$dataUtilisateur['tel_user'] = $formData['tel_user'];

        // Récupèration de l'email de l'organisme
        //$formData['email_user'] = $this->validatePostData($postData['email_user'], "email_user", "email", false, "Aucun email n'a été saisi.", "L'email de l'utilisateur n'est pas correctement saisi.");
        //$dataUtilisateur['email_user'] = $formData['email_user'];
        
        return $dataUtilisateur;
    }





    public function filterDataInscription(&$formData, $postData)
    {

        $dataInscription = array();

        $formData['mode_inscript'] = "insert";
        $formData['ref_inscription'] = null;


        /*** Récupération du champ caché "référence utilisateur" si il existe. ***/

        if (isset($postData['ref_user']) && !empty($postData['ref_user']))
        {
            $formData['ref_user'] = $postData['ref_user'];
            $dataInscription['ref_user'] = $formData['ref_user'];
        }


        /*** Récupération du champ caché "référence inscription" si il existe. ***/

        if (isset($postData['ref_inscription']) && !empty($postData['ref_inscription']))
        {
            $formData['ref_inscription'] = $postData['ref_inscription'];
            $dataInscription['ref_inscription'] = $formData['ref_inscription'];
        }


        /*** Récupération de la référence de l'intervenant dans les variables de session. ***/

        $formData['ref_intervenant'] = ServicesAuth::getSessionData('ref_intervenant');
        $dataInscription['ref_intervenant'] = $formData['ref_intervenant'];


        /*** Création de la date d'inscription qui correspond à la date du jour. ***/

        $dataInscription['date_inscription'] = date("Y-m-d");


        // Si l'utilisateur a été enregistré
        if ($formData['ref_user'] && $formData['ref_intervenant'])
        {
            // On doit vérifié si l'inscription n'a pas déjà été faite avec le même intervenant et le même utilisateur.
            $inscript = $this->getInscription('references', array('ref_user' => $formData['ref_user'], 'ref_intervenant' => $formData['ref_intervenant']));
            
            // Si oui, on ne fait rien
            if ($inscript)
            {
                if ($inscript['response']['inscription'][0]->getRefUtilisateur() == $formData['ref_user'] && $inscript['response']['inscription'][0]->getRefIntervenant() == $formData['ref_intervenant'])
                {
                    $formData['ref_inscription'] = $inscript['response']['inscription'][0]->getId();
                    $formData['mode_inscript'] = "none";
                }
            }
        }
        

        return $dataInscription;
    }





    /***   Insertions et mises à jour dans la base avec les données des formulaires et traitements des résultats   ***/


    public function setOrganismeProperties($dataOrganisme, &$formData)
    {
        $resultsetOrgan = false;
        
        $mode = $formData['mode_organ'];

        if ((empty($dataOrganisme['ref_organ']) && !empty($dataOrganisme['nom_organ']) && !empty($dataOrganisme['code_postal_organ']) && !empty($dataOrganisme['tel_organ'])) || !empty($dataOrganisme['ref_organ']))
        {
            /*** Insertion du nouvel organisme ***/

            if ($mode == "insert")
            {
                $resultsetOrgan = $this->setOrganisme("insert", $dataOrganisme);

                if (!$resultsetOrgan)
                {
                    $this->registerError("form_valid", "L'organisme n'a pu être enregistré.");
                }
                else
                {
                    $formData['ref_organ'] = $resultsetOrgan['response']['organisme']['last_insert_id'];
                }
            }

            /*** Mise à jour de l'organisme ***/

            else if ($mode == "update")
            {
                $formData['ref_organ'] = $dataOrganisme['ref_organ'];

                $resultsetOrgan = $this->setOrganisme("update", $dataOrganisme);

                if (!$resultsetOrgan)
                {
                    $this->registerError("form_valid", "L'organisme n'a pu être mis à jour.");
                }
            }
        }
        else
        {

            $this->registerError("form_empty", "Des données sont manquantes.");
        }
    }





    
    public function setIntervenantProperties($dataIntervenant, &$formData)
    {

        
        $resultsetInter = false;

        $mode = $formData['mode_inter'];


        if (!empty($dataIntervenant['email_intervenant']) && isset($formData['ref_organ']) && !empty($formData['ref_organ']))
        {
            $dataIntervenant['ref_organ'] = $formData['ref_organ'];

            /*** Insertion du nouvel intervenant ***/

            if ($mode == "insert")
            {
                $resultsetInter = $this->setIntervenant("insert", $dataIntervenant);

                

                if (!$resultsetInter)
                {
                    $this->registerError("form_valid", "L'intervenant n'a pu être enregistré.");
                }
                else
                {
                    $formData['ref_intervenant'] = $resultsetInter['response']['intervenant']['last_insert_id'];
                }
            }

            /*** Mise à jour de l'intervenant ***/

            else if ($mode == "update")
            {
                $formData['ref_intervenant'] = $dataIntervenant['ref_intervenant'];

                $resultsetInter = $this->setIntervenant("update", $dataIntervenant);

                if (!$resultsetInter)
                {
                    $this->registerError("form_valid", "L'intervenant n'a pu être mis à jour.");
                }
            }
        }
        else if (Config::ALLOW_REFERENT_INPUT == 1)
        {
            $this->registerError("form_empty", "Des données sont manquantes.");
        }
    }






    public function setUtilisateurProperties($dataUtilisateur, &$formData)
    {
        $resultsetUser = false;
        
        $mode = $formData['mode_user'];

        
        if ((empty($dataUtilisateur['ref_user']) && !empty($dataUtilisateur['nom_user']) && !empty($dataUtilisateur['prenom_user']) && !empty($dataUtilisateur['date_naiss_user']) && !empty($dataUtilisateur['ref_niveau'])) || !empty($dataUtilisateur['ref_user']))
        {
            /*** Insertion du nouvel utilisateur ***/

            if ($mode == "insert")
            {
                $resultsetUser = $this->setUtilisateur("insert", $dataUtilisateur);

                if (!$resultsetUser)
                {
                    $this->registerError("form_valid", "L'utilisateur n'a pu être enregistré.");
                }
                else
                {
                    $formData['ref_user'] = $resultsetUser['response']['utilisateur']['last_insert_id'];
                }
            }

            /*** Mise à jour de l'utilisateur ***/

            else if ($mode == "update")
            {
                $formData['ref_user'] = $dataUtilisateur['ref_user'];

                $resultsetUser = $this->setUtilisateur("update", $dataUtilisateur);

                if (!$resultsetUser)
                {
                    $this->registerError("form_valid", "L'utilisateur n'a pu être mis à jour.");
                }
            }
        }
        else
        {
            $this->registerError("form_empty", "Des données sont manquantes.");
        }
    }





    public function setInscriptionProperties($dataInscription, &$formData)
    {

        $resultsetInscript = false;

        $mode = $formData['mode_inscript'];


        if (!empty($dataInscription['ref_intervenant']) && !empty($dataInscription['date_inscription']) && isset($formData['ref_user']) && !empty($formData['ref_user']))
        {
            $dataInscription['ref_user'] = $formData['ref_user'];

            /*** Insertion du nouvel inscription ***/

            if ($mode == "insert")
            {
                $resultsetInscript = $this->setInscription("insert", $dataInscription);

                if (!$resultsetInscript)
                {
                    $this->registerError("form_valid", "L'inscription n'a pu être enregistré.");
                }
                else
                {
                    $formData['ref_inscription'] = $resultsetInscript['response']['inscription']['last_insert_id'];
                }
            }

            /*** Mise à jour de l'inscription ***/

            else if ($mode == "update")
            {
                $formData['ref_inscription'] = $dataInscription['ref_inscription'];

                $resultsetInscript = $this->setInscription("update", $dataInscription);

                if (!$resultsetInscript)
                {
                    $this->registerError("form_valid", "L'inscription n'a pu être mise à jour.");
                }
            }
        }
        else 
        {
            $this->registerError("form_empty", "Des données sont manquantes.");
        }
    }








    /***   Insertions et mises à jour dans la base   ***/


    public function setOrganisme($mode, $dataOrganisme)
    {
        
        if (!empty($dataOrganisme) && is_array($dataOrganisme))
        {
            if ($mode == "insert")
            {
                $resultset = $this->organismeDAO->insert($dataOrganisme);
                
                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['organisme']['last_insert_id']) && !empty($resultset['response']['organisme']['last_insert_id']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "L'organisme n'a pu être inséré.");
                }
            }
            else if ($mode == "update")
            {
                if (!empty($dataOrganisme['ref_organ']))
                {
                    $resultset = $this->organismeDAO->update($dataOrganisme);

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
                else 
                {
                    $this->registerError("form_request", "L'identifiant de l'organisme est manquant.");
                }
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'organisme non autorisée.");
        }
            
        return false;
    }





    public function setIntervenant($mode, $dataIntervenant)
    {
        if (!empty($dataIntervenant) && is_array($dataIntervenant))
        {
            if ($mode == "insert")
            {
                $resultset = $this->intervenantDAO->insert($dataIntervenant);
                
                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['intervenant']['last_insert_id']) && !empty($resultset['response']['intervenant']['last_insert_id']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "L'intervenant n'a pu être inséré.");
                }
            }
            else if ($mode == "update")
            {
                if (!empty($dataIntervenant['ref_intervenant']))
                {

                    $resultset = $this->intervenantDAO->update($dataIntervenant);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['intervenant']['row_count']) && !empty($resultset['response']['intervenant']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "L'intervenant n'a pas été mis à jour.");
                    }
                }
                else 
                {
                    $this->registerError("form_request", "L'identifiant de l'intervenant est manquant.");
                }
            }  
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'intervenant non autorisée.");
        }
        
        return false;
    }
    




    public function setUtilisateur($mode, $dataUtilisateur)
    {
        
        if (!empty($dataUtilisateur) && is_array($dataUtilisateur))
        {
            if ($mode == "insert")
            {
                $resultset = $this->utilisateurDAO->insert($dataUtilisateur);
                
                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['utilisateur']['last_insert_id']) && !empty($resultset['response']['utilisateur']['last_insert_id']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "L'utilisateur n'a pu être inséré.");
                }
            }
            else if ($mode == "update")
            {
                if (!empty($dataUtilisateur['ref_user']))
                {
                    $resultset = $this->utilisateurDAO->update($dataUtilisateur);

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
                else 
                {
                    $this->registerError("form_request", "L'identifiant de l'utilisateur est manquant.");
                }
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'utilisateur non autorisée.");
        }
            
        return false;
    }





    public function setInscription($mode, $dataInscription)
    {
        if (!empty($dataInscription) && is_array($dataInscription))
        {
            if ($mode == "insert")
            {
                $resultset = $this->inscriptionDAO->insert($dataInscription);
                
                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['inscription']['last_insert_id']) && !empty($resultset['response']['inscription']['last_insert_id']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "L'inscription n'a pas pu être insérée.");
                }
            }
            else if ($mode == "update")
            {
                if (!empty($dataUtilisateur['ref_inscription']))
                {
                    $resultset = $this->inscriptionDAO->update($dataInscription);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['inscription']['row_count']) && !empty($resultset['response']['inscription']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "L'inscription n'a pas été mis à jour.");
                    }
                }
                else 
                {
                    $this->registerError("form_request", "L'identifiant de l'inscription est manquant.");
                }
            } 
        }
        else 
        {
            $this->registerError("form_request", "L'inscription n'est pas autorisée.");
        }
            
        return false;
    
    }





    
    public function getOrganisme($fieldName, $value)
    {
        switch($fieldName) 
        {
            case 'id_organ':
                $resultset = $this->organismeDAO->selectById($value);
                break;
            
            case 'nom_organ':
                $resultset = $this->organismeDAO->selectByName($value);
                break;
            
             case 'code_postal_organ':
                break;
            
            default :
                break;
        }

        // Traitement des erreurs de la requête
        if ($resultset['response'] && !$this->filterDataErrors($resultset['response']))
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

    



    public function getIntervenant($fieldName, $fieldValue)
    {
        switch($fieldName) 
        {
            case "id_intervenant":
                $resultset = $this->intervenantDAO->selectById($fieldValue);
                break;
            
            case "email":
                $resultset = $this->intervenantDAO->selectByEmail($fieldValue);
                break;

            case "organ":
                $resultset = $this->intervenantDAO->selectByOrgan($fieldValue);
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['intervenant']) && count($resultset['response']['intervenant']) == 1)
            { 
                $intervenant = $resultset['response']['intervenant'];
                $resultset['response']['intervenant'] = array($intervenant);
            }

            return $resultset;
        }
        
        return false;
    }





    public function searchIntervenants($searchValue, $refOrgan = null) {

        $resultset = $this->intervenantDAO->selectSearchByEmail($searchValue, $refOrgan);
        
        //var_dump($resultset);
        //exit();


        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['intervenant']) && count($resultset['response']['intervenant']) == 1)
            { 
                $intervenant = $resultset['response']['intervenant'];
                $resultset['response']['intervenant'] = array($intervenant);
            }

            return $resultset;
        }
        
        return false;
    }
    

    
    
    public function getUtilisateur($fieldName, $fieldValue)
    {
        switch($fieldName) 
        {
            case 'id_user':
                
                $resultset = $this->utilisateurDAO->selectById($fieldValue);
                break;
            
            case 'nom_user':
                
                $resultset = $this->utilisateurDAO->selectByName($fieldValue);
                break;

            case 'date_naissance':
                
                $resultset = $this->utilisateurDAO->selectByDateNaissance($fieldValue);
                break;
            
            case 'duplicate_user':
                
                if (!empty($fieldValue['nom_user']) && !empty($fieldValue['prenom_user']) && !empty($fieldValue['date_naiss_user']))
                {
                    $resultset = $this->utilisateurDAO->selectByUser($fieldValue['nom_user'], $fieldValue['prenom_user'], $fieldValue['date_naiss_user']);
                }
                else 
                {
                    $this->registerError("form_request", "Pas assez d'informations sur l'utilisateur.");
                }
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        if ($resultset['response'] && !$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
            { 
                $utilisateur = $resultset['response']['utilisateur'];
                $resultset['response']['utilisateur'] = array($utilisateur);
            }

            return $resultset;
        }
        
        return false;
    }
    


    public function getInscription($fieldName, $fieldValues)
    {
        switch($fieldName) 
        {
            case 'references':
                $resultset = $this->inscriptionDAO->selectByReferences($fieldValues['ref_user'], $fieldValues['ref_intervenant']);
                break;
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        if ($resultset['response'] && !$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['inscription']) && count($resultset['response']['inscription']) == 1)
            { 
                $inscription = $resultset['response']['inscription'];
                $resultset['response']['inscription'] = array($inscription);
            }

            return $resultset;
        }
        
        return false;
    }
    

    
    

    
}


?>
