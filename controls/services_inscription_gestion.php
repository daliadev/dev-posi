<?php


/**
 * 
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

//require_once(ROOT.'models/dao/model_dao.php');

// Fichiers requis pour le formulaire organisme
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');

// Fichiers requis pour le formulaire utilisateur
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/inscription_dao.php');



class ServicesInscriptionGestion extends Main
{
    
    //private $servicesAuth = null;
    
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
    




    public function filterDataOrganisme(&$formData, $postData)
    {
        $dataOrganisme = array();
        $formData['mode_organ'] = "none";
        $formData['ref_organ'] = null;

        /*** Récupération du champ caché "ref_organ" si il existe ***/
           
        if (isset($_POST['ref_organ']) && !empty($_POST['ref_organ']))
        {
            $formData['ref_organ'] = $_POST['ref_organ'];
        }
        

        /*** Récupèration de la référence de l'organisme dans le combo-box ***/

        if (!empty($_POST['ref_organ_cbox']))
        {
            $formData['ref_organ_cbox'] = $_POST['ref_organ_cbox'];
                    
            if ($_POST['ref_organ_cbox'] == "select_cbox")
            {
                $this->registerError("form_empty", "Aucun organisme n'a été sélectionné.");
            }
            else if ($_POST['ref_organ_cbox'] == "new")
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
                $formData['ref_organ'] = $_POST['ref_organ_cbox'];
            }
        }

        if ($formData['ref_organ'])
        {
            $dataOrganisme['ref_organ'] = $formData['ref_organ'];
        }
        

        if ($formData['mode_organ'] == "insert")
        {
            /*** Traitement particulier de doublon du nom de l'organisme ***/

            // Si le nom de l'organisme n'est pas vide, il a été saisi et il doit être comparé aux autres noms d'organisme.
            if (!empty($_POST['nom_organ']))
            {
                $duplicateNomOrgan = false;
                //$similarNomOrgan = "";
                //$formData['suggest_name'] = "";

                // On enlève les espaces, les caractères spéciaux et on met le nom saisi tout en majuscules.
                $cleanNom = Tools::stripSpecialCharsFromString($_POST['nom_organ']);
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
                        /*
                        else if (strstr($securNomOrgan, $nomOrganisme) || strstr($securNomOrgan, $nomOrganisme))
                        {
                            $similarNomOrgan = $nomOrgan;
                            break;
                        }
                        */
                    }
                }

                if ($duplicateNomOrgan)
                {
                    $this->registerError("form_valid", "Le nom de l'organisme existe déjà.");
                }
                /*
                else if (!empty($similarNomOrgan))
                {
                    $formData['suggest_name'] = $similarNomOrgan;
                }
                */
                /*
                else
                {
                    $dataOrganisme['nom_organ'] = $formData['nom_organ'];
                }
                */
            }

            //else
            //{
                // Récupèration du nom de l'organisme
                $formData['nom_organ'] = $this->validatePostData($postData['nom_organ'], "nom_organ", "string", true, "Aucun nom d'organisme n'a été sélectionné.", "Le nom de l'organisme n'est pas correctement sélectionné.");
                $dataOrganisme['nom_organ'] = $formData['nom_organ'];
            //}

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

        // Récupération du champ caché "ref_intervenant" si il existe
        
        if (isset($_POST['ref_intervenant']) && !empty($_POST['ref_intervenant']))
        {
            $formData['ref_intervenant'] = $_POST['ref_intervenant'];
            $dataIntervenant['ref_intervenant'] = $formData['ref_intervenant'];
        }
        
        if (isset($formData['ref_organ']) && !empty($formData['ref_organ']))
        {
            $dataIntervenant['ref_organ'] = $formData['ref_organ'];
        }

        //$dataOrganisme['ref_intervenant'] = $formData['ref_intervenant'];


        // Récupèration du nom de l'intervenant
        //$formData['nom_intervenant'] = $this->validatePostData($postData['nom_intervenant'], "nom_intervenant", "string", true, "Aucun nom n'a été saisi.", "Le nom de l'intervenant n'est pas correctement saisi.");
        //$dataIntervenant['nom_intervenant'] = $formData['nom_intervenant'];
        
        // Récupèration du téléphone de l'intervenant
        //$formData['tel_intervenant'] = $this->validatePostData($postData['tel_intervenant'], "tel_intervenant", "integer", true, "Aucun numéro de téléphone n'a été saisi.", "Le numéro de téléphone de l'intervenant n'est pas correctement saisi.");
        //$dataIntervenant['tel_intervenant'] = $formData['tel_intervenant'];

        // Récupèration de l'email de l'intervenant
        $formData['email_intervenant'] = $this->validatePostData($postData['email_intervenant'], "email_intervenant", "email", false, "Aucun email n'a été saisi.", "L'email de l'intervenant n'est pas correctement saisi.");
        $dataIntervenant['email_intervenant'] = $formData['email_intervenant'];



        //$this->formData['date_inscription'] = date("Y-m-d");
        //$dataIntervenant['date_inscription'] = $formData['date_inscription'];

        /*** Traitement de doublon de l'email de l'intervenant et définition du mode de la requête ***/
            
        //------------------
        
        //$modeIntervenant = "insert";
        
        // Si l'email de l'intervenant existe déja pour cet organisme, on change de mode pour une mise à jour
        $resultsetInter = $this->getIntervenant("email", $formData['email_intervenant']);
        
        if (isset($resultsetInter['response']['intervenant']) && !empty($resultsetInter['response']['intervenant']))
        {
            $idOrgan = $resultsetInter['response']['intervenant'][0]->getRefOrganisme();

            if (isset($formData['ref_organ']) && !empty($formData['ref_organ']) && $idOrgan == $formData['ref_organ'])
            {
                $formData['mode_inter'] = "update";
                $formData['ref_intervenant'] = $resultsetInter['response']['intervenant'][0]->getId();
                $dataIntervenant['ref_intervenant'] = $formData['ref_intervenant'];
            }
            else
            {
                $formData['mode_inter'] = "insert";
                
            }
            
            // On récupère la référence de l'organisme
            //$formData['ref_organ'] = $resultsetInter['response']['intervenant'][0]->getId();
        }
        else
        {
            $formData['mode_inter'] = "insert";
        }

        return $dataIntervenant;
    }





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

        //var_dump($formData['ref_organ']);

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
        else
        {

            $this->registerError("form_empty", "Des données sont manquantes.");
        }
    }






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
                    //$dataIntervenant['ref_intervenant'] = $dataIntervenant['ref_intervenant'];
                    //unset($dataIntervenant['ref_intervenant']);

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

            return $resultset;
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

    
    
    
    /*
    public function getIntervenants()
    {
        $resultset = $this->intervenantDAO->selectAll();

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
    */
    



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
    




    /*
    public function setIntervenant($modeRequête, $dataIntervenant)
    {
        if (!empty($dataIntervenant) && is_array($dataIntervenant))
        {
            if ($modeRequête == "insert")
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
            else if ($modeRequête == "update")
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
                return true;
            }  
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'intervenant non autorisée");
        }
        
        return false;
    }
    */
    
    /* requêtes niveaux d'études */
    /*
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
    */
    public function getNiveauxEtudes()
    {
        $resultset = $this->niveauEtudesDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    public function getUtilisateurs()
    {
        $resultset = $this->utilisateurDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
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
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    
    public function setUtilisateur($dataUtilisateur, $modeRequête)
    {
        if (!empty($dataUtilisateur) && is_array($dataUtilisateur))
        {
            if ($modeRequête == "insert")
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
            else if ($modeRequête == "update")
            {
                $resultset = $this->utilisateurDAO->update($dataUtilisateur);

                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['utilisateur']['row_count']) && !empty($resultset['response']['utilisateur']['row_count']))
                {
                    return $resultset;
                } 
                else 
                {
                    $this->registerError("form_request", "L'utilisateur n'a pas été mis à jour.");
                }
            }
            else 
            {
                return true;
            } 
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'utilisateur non autorisée");
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
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    
    public function setInscription($dataInscription, $modeRequête = "insert")
    {
        if (!empty($dataInscription) && is_array($dataInscription))
        {
            if ($modeRequête == "insert")
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
            else if ($modeRequête == "update")
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
                return true;
            }  
        }
        else 
        {
            $this->registerError("form_request", "L'inscription n'est pas autorisée.");
        }
            
        return false;
    
    }
}


?>
