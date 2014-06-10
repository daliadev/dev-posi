<?php


/**
 * 
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'models/dao/model_dao.php');
// Fichiers requis pour le formulaire organisme
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');

// Fichiers requis pour le formulaire utilisateur
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/inscription_dao.php');



class ServicesInscriptValidation extends Main
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
    
    
    /*
    public function authenticateOrganisme($codeOrganisme)
    {
        $code = servicesAuth::hashPassword($codeOrganisme);


        // Version peu sécurisée
        $resultset = array();
        $resultset['response'] = array();

        if ($code == Config::getCodeOrganisme())
        {
            $isAuth = true;
            $resultset['response']['ref_code_organisme'] = 1;
        }
        else
        {
            $resultset['response']['errors'] = array();
            $resultset['response']['errors'][] = array('type' => "login_request", 'message' => "mauvais identifiant");
        }
        
        if ($resultset['response']['auth'] && !empty($resultset['response']['ref_code_organisme']))
        {
            // authentifié
            servicesAuth::login("user");
            
            return $resultset;
        }
        else 
        {
            $this->registerError("form_data", "Le code organisme n'est pas valide");
            return false;
        }
    }
    */
    
    
    public function getOrganismes()
    {
        $resultset = $this->organismeDAO->selectAll();

        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
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
        
        //var_dump($resultset['response']);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    
    public function setOrganisme($modeRequête, $dataOrganisme)
    {
        if (!empty($dataOrganisme) && is_array($dataOrganisme))
        {
            $success = false;
            
            if ($modeRequête == "insert")
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
            else if ($modeRequête == "update")
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
                return true;
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de l'organisme non autorisée");
        }
            
        return false;
    }

    
    
    
    
    public function getIntervenants()
    {
        $resultset = $this->intervenantDAO->selectAll();

        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
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
            
            default :
                break;
        }
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
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

    
    /* requêtes niveaux d'études */
    
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
