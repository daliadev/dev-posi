<?php


require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/degre_dao.php');
require_once(ROOT.'models/dao/reponse_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');



class ServicesAdminRestitution extends Main
{
    
    private $organismeDAO = null;
    private $utilisateurDAO = null;
    private $niveauEtudesDAO = null;
    private $sessionDAO = null;
    private $intervenantDAO = null;
    private $resultatDAO = null;
    private $questionDAO = null;
    private $degreDAO = null;
    private $reponseDAO = null;
    private $questionCatDAO = null;
    private $categorieDAO = null;
    
    
    
    public function __construct() 
    {
        $this->errors = array();
        $this->controllerName = "adminRestitution";

        $this->organismeDAO = new OrganismeDAO();
        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
        $this->sessionDAO = new SessionDAO();
        $this->intervenantDAO = new IntervenantDAO();
        $this->questionDAO = new QuestionDAO();
        $this->degreDAO = new DegreDAO();
        $this->reponseDAO = new ReponseDAO();
        $this->resultatDAO = new ResultatDAO();
        $this->questionCatDAO = new QuestionCategorieDAO();
        $this->categorieDAO = new CategorieDAO();
    }

    
    
    
    
    public function getOrganismesList()
    {
        $resultset = $this->organismeDAO->selectAll();

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
            { 
                $organisme = $resultset['response']['organisme'];
                $resultset['response']['organisme'] = array($organisme);
            }

            return $resultset;
        }

        return false;
    }
    


    

    public function getUsersFromOrganisme($refOrganisme)
    {
        $resultset = $this->utilisateurDAO->selectByOrganisme($refOrganisme);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
            { 
                $utilisateur = $resultset['response']['utilisateur'];
                $resultset['response']['utilisateur'] = array($utilisateur);
            }
            
            for ($i = 0; $i < count($resultset['response']['utilisateur']); $i++)
            {
                if (intval($resultset['response']['utilisateur'][$i]->getSessionsAccomplies()) === 0)
                {
                    unset($resultset['response']['utilisateur'][$i]);
                }
            }
            
            return $resultset;
        }

        return false;
    }
    
    



    public function getUserSessions($refUser, $refOrganisme)
    {
        $resultset = $this->sessionDAO->selectByUser($refUser, $refOrganisme);
        
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
            { 
                $session = $resultset['response']['session'];
                $resultset['response']['session'] = array($session);
            }
            
            for ($i = 0; $i < count($resultset['response']['session']); $i++)
            {
                if (intval($resultset['response']['session'][$i]->getSessionAccomplie()) === 0)
                {
                    unset($resultset['response']['session'][$i]);
                }
            }

            return $resultset;
        }
        
        return false;
    }


    


    public function getSession($refSession)
    {
        $resultset = $this->sessionDAO->selectById($refSession);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
            { 
                $session = $resultset['response']['session'];
                $resultset['response']['session'] = array($session);
            }
        }

        return $resultset;
    }





    public function getIntervenant($refIntervenant)
    {
        $resultset = $this->intervenantDAO->selectById($refIntervenant);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['intervenant']) && count($resultset['response']['intervenant']) == 1)
            { 
                $intervenant = $resultset['response']['intervenant'];
                $resultset['response']['intervenant'] = array($intervenant);
            }

            return $resultset;
        }

        return false;
    }


    
    

    public function getInfosUser($refUser)
    {
        $userInfos = array();
        
        $userInfos['nom_organ'] = null;
        $userInfos['nom_intervenant'] = null;
        $userInfos['email_intervenant'] = null;
        $userInfos['nom'] = null;
        $userInfos['prenom'] = null;
        $userInfos['date_naiss'] = null;
        $userInfos['adresse'] = null;
        $userInfos['code_postal'] = null;
        $userInfos['ville'] = null;
        $userInfos['email'] = null;
        $userInfos['tel'] = null;
        $userInfos['nom_niveau'] = null;
        $userInfos['descript_niveau'] = null;
        $userInfos['nbre_positionnements'] = null;
        $userInfos['date_last_posi'] = null;


        $resultsetUser = $this->getUser($refUser);

        if ($resultsetUser)
        {
            $utilisateur = $resultsetUser['response']['utilisateur'][0];
            
            $userInfos['nom'] = $utilisateur->getNom();
            $userInfos['prenom'] = $utilisateur->getPrenom();
            $userInfos['date_naiss'] = Tools::toggleDate($utilisateur->getDateNaiss());
            $userInfos['adresse'] = $utilisateur->getAdresse();
            $userInfos['code_postal'] = $utilisateur->getCodePostal();
            $userInfos['ville'] = $utilisateur->getVille();
            $userInfos['email'] = $utilisateur->getEmail();
            $userInfos['tel'] = $utilisateur->getTel();
            $userInfos['nbre_positionnements'] = $utilisateur->getSessionsAccomplies();
            
            $resultsetNiveau = $this->getNiveau($utilisateur->getRefNiveau());

            if ($resultsetNiveau)
            {
                $userInfos['nom_niveau'] = $resultsetNiveau['response']['niveau_etudes'][0]->getNom();
                $userInfos['descript_niveau'] = $resultsetNiveau['response']['niveau_etudes'][0]->getDescription();
            }
        }
        
        return $userInfos;
    }
    
    
    
    
    
    public function getPosiStats($refSession)
    {
        $posiStats = array();
        
        //$posiStats['date'] = null;
        //$posiStats['temps_total'] = null;
        $posiStats['percent_global'] = null;
        $posiStats['categories'] = array();
        
        
        /*** On récupère la liste des categories ***/
        
        $resultsetcategories = $this->getCategories();
        $categoriesList = $resultsetcategories['response']['categorie'];
        
        
        /*** On va chercher tous les résultats classés par categories ***/
        
        // On sélectionne tous les résultats correspondant à la session en cours
        $resultats = $this->getResultatsByCategories($refSession);
        
        $tabStats = array();
        $totalGlobal = 0;
        $totalCorrectGlobal = 0;
        $percentGlobal = 0;
        $countValidCategories = 0;
        $j = 0;
                        
        foreach ($categoriesList as $categorie)
        {
            $codeCat = $categorie->getCode();

            $tabStats[$j]['code_cat'] = $codeCat;
            $tabStats[$j]['nom'] = $categorie->getNom();
            $tabStats[$j]['description'] = $categorie->getDescription();
            $tabStats[$j]['type_lien'] = $categorie->getTypeLien();
            $tabStats[$j]['total'] = 0;
            $tabStats[$j]['total_correct'] = 0;
            
            // Pour chaque resultat attaché à la catégorie.
            for ($i = 0; $i < count($resultats); $i++)
            {
                if ($resultats[$i]['code_cat'] == $codeCat)
                {
                   // Le nombre de réponses s'incrémentent.
                   $tabStats[$j]['total']++;
                   $totalGlobal++;
                   
                   if ($resultats[$i]['correct'])
                   {
                       $tabStats[$j]['total_correct']++;
                       $totalCorrectGlobal++;
                   }
                }  
            }
            
            
            /*** Calcul des pourcentage selon le type de la catégorie. ***/
            /*
            if ($tabStats[$j]['type_lien'] == "unique")
            {
                // Cette catégorie possède son propre pourcentage indépendament de ses enfants.
                
            }
            else if ($tabStats[$j]['type_lien'] == "static") 
            {
                // Le pourcentage de cette categorie est la somme de celui de ses enfants.
                
            }
            else if ($tabStats[$j]['type_lien'] == "dynamic") 
            {
                // Le pourcentage de cette categorie est la somme de celui de ses enfants, de plus il a son propre pourcentage.
                
            }
            else
            {
                // Cette categorie n'a pas de pourcentage propre à elle.
                
            }
            */

            
            // Calcul du poucentage de réussite dans cette catégorie
            
            if ($tabStats[$j]['total'] > 0)
            {
                $tabStats[$j]['percent'] = round(($tabStats[$j]['total_correct'] * 100) / $tabStats[$j]['total']);
                $countValidCategories++;
            }
            else 
            {
                $tabStats[$j]['percent'] = 0;
            }

            $j++;
        }
        
        
        /*** Intégration du système d'héritage des résultats ***/
        
        for ($i = 0; $i < count($tabStats); $i++)
        {
            // On détermine si c'est une categorie principale ou une sous-categorie
            if (strlen($tabStats[$i]['code_cat']) == 2)
            {
                // Catégorie parent

                if ($tabStats[$i]['type_lien'] == "dynamic")
                {
                    $tabStats[$i]['parent'] = true;
                    $parentCode = $tabStats[$i]['code_cat'];
                    $tabStats[$i]['total'] = 0;
                    $tabStats[$i]['total_correct'] = 0;
                    $tabStats[$i]['children'] = array();

                    for ($j = 0; $j < count($tabStats); $j++)
                    {
                        if (strlen($tabStats[$j]['code_cat']) > 2 && substr($tabStats[$j]['code_cat'], 0, 2) == $parentCode)
                        {
                            $tabStats[$i]['total'] += $tabStats[$j]['total'];
                            $tabStats[$i]['total_correct'] += $tabStats[$j]['total_correct'];
                            $tabStats[$i]['children'][] = $tabStats[$j];
                        }
                    }
                }
                else if ($tabStats[$i]['type_lien'] == "static")
                {
                    $tabStats[$i]['parent'] = true;
                    $parentCode = $tabStats[$i]['code_cat'];
                    $tabStats[$i]['children'] = false;
                }
            }
            else 
            {
                $tabStats[$i]['parent'] = false;
                $tabStats[$i]['children'] = false;
            }

        }
        
        
        /*** Données envoyées à la page de résultat ***/

        $posiStats['categories'] = array();
        $k = 0;
        
        foreach ($tabStats as $stat)
        {
            $posiStats['categories'][$k]['parent'] = $stat['parent'];
            $posiStats['categories'][$k]['children'] = $stat['children'];
            $posiStats['categories'][$k]['nom_categorie'] = $stat['nom'];
            $posiStats['categories'][$k]['descript_categorie'] = $stat['description'];
            $posiStats['categories'][$k]['total'] = $stat['total'];
            $posiStats['categories'][$k]['total_correct'] = $stat['total_correct'];

            if ($stat['total'] > 0)
            {
                $posiStats['categories'][$k]['percent'] = round(($stat['total_correct'] * 100) / $stat['total']);
            }
            else 
            {
                $posiStats['categories'][$k]['percent'] = 0;
            }
            
            $k++;
        }
        
        
        /*** Gestion du temps ***/
        
        //$stringTime = Tools::timeToString($totalTime);
        //$posiStats['temps'] = $stringTime;
        
        
        /*** Stats globales ***/
        
        $percentGlobal = round(($totalCorrectGlobal / $totalGlobal) * 100);
        $posiStats['percent_global'] = $percentGlobal;
        $posiStats['total_global'] = $totalGlobal;
        $posiStats['total_correct_global'] = $totalCorrectGlobal;
        

        return  $posiStats;
    }
    
    
    
    
    
    public function getQuestionsDetails($refSession)
    {

        // Etape  1 : Regroupement des données sur toutes les questions du positionnement
        $questionsDetails = array();
                
        $resultsetQuestions = $this->getQuestions();

        if ($resultsetQuestions)
        {
            $i = 0;

            // Pour chaque question
            foreach ($resultsetQuestions['response']['question'] as $question)
            {

                // Initialisation des données récupérée de la question    
                $questionsDetails[$i] = array();
                $questionsDetails[$i]['ref_question'] = $question->getId();
                if (strlen($question->getNumeroOrdre()) == 1)
                {
                    $questionsDetails[$i]['num_ordre'] = "0".$question->getNumeroOrdre();
                }
                else
                {
                    $questionsDetails[$i]['num_ordre'] = $question->getNumeroOrdre();
                }
                
                $questionsDetails[$i]['type'] = $question->getType();
                $questionsDetails[$i]['intitule'] = $question->getIntitule();
                $questionsDetails[$i]['image'] = $question->getImage();

                $questionsDetails[$i]['nom_degre'] = "-";
                $questionsDetails[$i]['descript_degre'] = "";
                
                $questionsDetails[$i]['categories'] = array();

                $questionsDetails[$i]['reponses'] = array();

                $questionsDetails[$i]['reponse_user_qcm'] = "-";
                $questionsDetails[$i]['reponse_qcm_correcte'] = "-";
                $questionsDetails[$i]['reponse_user_champ'] = "-";
                $questionsDetails[$i]['intitule_reponse_user'] = "";
                $questionsDetails[$i]['intitule_reponse_correcte'] = "";
                $questionsDetails[$i]['temps'] = "";
                $questionsDetails[$i]['reussite'] = "-";
   
                
                /*** Degré ***/

                $refDegre = $question->getRefDegre();

                if (!empty($refDegre))
                {
                    $resultsetDegre = $this->getDegre($refDegre);

                    if ($resultsetDegre)
                    {
                        $questionsDetails[$i]['nom_degre'] = $resultsetDegre['response']['degre']->getNom();
                        $questionsDetails[$i]['descript_degre'] = $resultsetDegre['response']['degre']->getDescription();
                    }
                }
                
                
                /*** Catégories ***/
                
                $resultsetCategories = $this->getCategoriesByQuestion($question->getId());

                $categories = array();

                if ($resultsetCategories) 
                {
                    $j = 0;

                    foreach ($resultsetCategories['response']['categorie'] as $categorie)
                    {
                        $codeCat = $categorie->getCode();

                        if (strlen($codeCat) > 2)
                        {
                            $parentCode = substr($codeCat, 0, 2);
                            $resultsetCat = $this->getCategorie($parentCode);

                            if ($resultsetCat)
                            {
                                $categories[$j]['nom_cat_parent'] = $resultsetCat['response']['categorie'][0]->getNom();
                                $categories[$j]['descript_cat_parent'] = $resultsetCat['response']['categorie'][0]->getDescription();
                            }  
                        }
                        
                        $categories[$j]['nom_cat'] = $categorie->getNom();
                        $categories[$j]['descript_cat'] = $categorie->getDescription();

                        $j++;
                    }
                }

                $questionsDetails[$i]['categories'] = $categories;

                
                /*** Réponses ***/

                if ($question->getType() == "qcm")
                {
                    $reponses = array();

                    $resultsetReponses = $this->getReponsesByQuestion($question->getId());

                    if ($resultsetReponses)
                    { 
                        $j = 0;
                        foreach ($resultsetReponses['response']['reponse'] as $reponse)
                        {
                            $reponses[$j] = array();
                            $reponses[$j]['ref_reponse'] = $reponse->getId();
                            $reponses[$j]['num_ordre_reponse'] = $reponse->getNumeroOrdre();
                            $reponses[$j]['intitule_reponse'] = $reponse->getIntitule();
                            $reponses[$j]['est_correcte'] = $reponse->getEstCorrect();

                            $j++;
                        }
                    }    
                }

                $questionsDetails[$i]['reponses'] = $reponses;


                $i++;
            }
        }

        // Fin de la récupération des infos de chaque question

        

        // Etape 2 : Regroupement de toutes les infos sur l'utilisateur sélectionné

        $resultatsUser = array();

        $resultsetResultats = $this->getResultatsBySession($refSession);
        
        if ($resultsetResultats)
        {
            $i = 0;

            foreach ($resultsetResultats['response']['resultat'] as $result)
            {

                $resultatsUser[$i] = array();
                $resultatsUser[$i]['ref_resultat'] = $result->getId();
                $resultatsUser[$i]['ref_question'] = $result->getRefQuestion();
                $resultatsUser[$i]['ref_reponse_qcm'] = $result->getRefReponseQcm();
                $resultatsUser[$i]['ref_reponse_qcm_correcte'] = $result->getRefReponseQcmCorrecte();
                $resultatsUser[$i]['reponse_champ'] = $result->getReponseChamp();
                $resultatsUser[$i]['temps_reponse'] = $result->getTempsReponse();
  
                $i++;
            }
        }


        // Etape 3 : Recoupement entre les infos des questions et des résultats associés à chaque question
        
        for ($i = 0; $i < count($questionsDetails); $i++)
        {
            for ($j = 0; $j < count($resultatsUser); $j++)
            {
                if ($questionsDetails[$i]['ref_question'] == $resultatsUser[$j]['ref_question'])
                {

                    $questionsDetails[$i]['reponse_user_qcm'] = "-";
                    $questionsDetails[$i]['intitule_reponse_user'] = "-";
                    $questionsDetails[$i]['reponse_qcm_correcte'] = "-";
                    $questionsDetails[$i]['intitule_reponse_correcte'] = "-";
                    $questionsDetails[$i]['temps'] = "-";
                    $questionsDetails[$i]['reussite'] = "-";   

                    if (!empty($resultatsUser[$j]['reponse_champ']))
                    {
                        $questionsDetails[$i]['reponse_user_champ'] =  $resultatsUser[$j]['reponse_champ'];
                    }
                    else if (!empty($resultatsUser[$j]['ref_reponse_qcm']) && !empty($resultatsUser[$j]['ref_reponse_qcm_correcte']))
                    {
                        for ($k = 0; $k < count($questionsDetails[$i]['reponses']); $k++)
                        {
                            if (!empty($questionsDetails[$i]['reponses'][$k]))
                            {   
                                $reponse = $questionsDetails[$i]['reponses'][$k];

                                if (!empty($resultatsUser[$j]['ref_reponse_qcm_correcte']) && $reponse['ref_reponse'] == $resultatsUser[$j]['ref_reponse_qcm'])
                                {
                                    $questionsDetails[$i]['reponse_user_qcm'] = $reponse['num_ordre_reponse'];
                                    $questionsDetails[$i]['intitule_reponse_user'] = $reponse['intitule_reponse'];
                                }

                                if (!empty($resultatsUser[$j]['ref_reponse_qcm_correcte']) && $reponse['ref_reponse'] == $resultatsUser[$j]['ref_reponse_qcm_correcte'])
                                {
                                    $questionsDetails[$i]['reponse_qcm_correcte'] = $reponse['num_ordre_reponse'];
                                    $questionsDetails[$i]['intitule_reponse_correcte'] = $reponse['intitule_reponse'];
                                }
                            }
                        }

                        $questionsDetails[$i]['reussite'] = "-";

                        if ($questionsDetails[$i]['reponse_user_qcm'] != "-" || $questionsDetails[$i]['reponse_qcm_correcte'] != "-")
                        {
                            if ($questionsDetails[$i]['reponse_user_qcm'] == $questionsDetails[$i]['reponse_qcm_correcte'])
                            {
                                $questionsDetails[$i]['reussite'] = 1;
                            }
                            else 
                            {
                                $questionsDetails[$i]['reussite'] = 0;
                            }
                        }
                    }
                   
                    if (!empty($resultatsUser[$j]['temps_reponse']))
                    {
                        $questionsDetails[$i]['temps'] = $resultatsUser[$j]['temps_reponse'];
                    }

                    break;
                }
            }
        }

        // var_dump($questionsDetails);
        // exit();

        return $questionsDetails;
    }




   
    private function getUser($refUser)
    {
        $resultset = $this->utilisateurDAO->selectById($refUser);

        // Traitement des erreurs de la requête
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
    
    
    

    private function getNiveau($refNiveau)
    {
        $resultset = $this->niveauEtudesDAO->selectById($refNiveau);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['niveau_etudes']) && count($resultset['response']['niveau_etudes']) == 1)
            { 
                $niveauEtudes = $resultset['response']['niveau_etudes'];
                $resultset['response']['niveau_etudes'] = array($niveauEtudes);
            }

            return $resultset;
        }

        return false;
    }


    private function getDegre($refDegre)
    {
        $resultset = $this->degreDAO->selectById($refDegre);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['degre']) && count($resultset['response']['degre']) == 1)
            { 
                $degre = $resultset['response']['degre'];
                $resultset['response']['degre'] = array($degre);
            }

            return $resultset;
        }

        return false;
    }


    
    
    private function getCategories()
    {
        $resultset = $this->categorieDAO->selectAll();

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
            { 
                $categorie = $resultset['response']['categorie'];
                $resultset['response']['categorie'] = array($categorie);
            }

            return $resultset;
        }

        return false;
    }



    private function getCategorie($codeCat)
    {
        $resultset = $this->categorieDAO->selectByCode($codeCat);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
            { 
                $categorie = $resultset['response']['categorie'];
                $resultset['response']['categorie'] = array($categorie);
            }

            return $resultset;
        }

        return false;
    }




    private function getCategoriesByQuestion($refQuestion)
    {
        $resultset = $this->categorieDAO->selectByQuestion($refQuestion);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
            { 
                $categorie = $resultset['response']['categorie'];
                $resultset['response']['categorie'] = array($categorie);
            }

            return $resultset;
        }

        return false;
    }





    private function getResultatsBySession($refSession)
    {
        $resultset = $this->resultatDAO->selectBySession($refSession);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['resultat']) && count($resultset['response']['resultat']) == 1)
            { 
                $resultat = $resultset['response']['resultat'];
                $resultset['response']['resultat'] = array($resultat);
            }

            return $resultset;
        }

        return false;
    }
    




    private function getQuestions()
    {
        $resultset = $this->questionDAO->selectAll();

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
            { 
                $question = $resultset['response']['question'];
                $resultset['response']['question'] = array($question);
            }

            return $resultset;
        }

        return false;
    }



    

    private function getQuestion($refQuestion)
    {
        $resultset = $this->questionDAO->selectById($refQuestion);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
            { 
                $question = $resultset['response']['question'];
                $resultset['response']['question'] = array($question);
            }

            return $resultset;
        }

        return false;
    }




    private function getQuestionCategorie($refQuestion)
    {
        $resultset = $this->questionCatDAO->selectByRefQuestion($refQuestion);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['question_cat']) && count($resultset['response']['question_cat']) == 1)
            { 
                $question_cat = $resultset['response']['question_cat'];
                $resultset['response']['question_cat'] = array($question_cat);
            }

            return $resultset;
        }

        return false;
    }





    private function getReponsesByQuestion($refQuestion)
    {
        $resultset = $this->reponseDAO->selectByQuestion($refQuestion);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            if (!empty($resultset['response']['reponse']) && count($resultset['response']['reponse']) == 1)
            { 
                $reponse = $resultset['response']['reponse'];
                $resultset['response']['reponse'] = array($reponse);
            }

            return $resultset;
        }

        return false;
    }




 
    private function getResultatsByCategories($refSession)
    {
        $tabResultats = array();
        
        // On sélectionne tous les résultats correspondant à la session en cours
        $resultsetResultats = $this->getResultatsBySession($refSession);
        
        if ($resultsetResultats)
        {
            $i = 0;
            
            foreach ($resultsetResultats['response']['resultat'] as $resultat)
            {      
                // On établit si le résultat est correct ou non
                if ($resultat->getRefReponseQcm() && $resultat->getRefReponseQcmCorrecte())
                {
                    if ($resultat->getRefReponseQcm() == $resultat->getRefReponseQcmCorrecte())
                    {
                        $tabResultats[$i]['correct'] = true;
                    }
                    else 
                    {
                        $tabResultats[$i]['correct'] = false;
                    }
                    
                    // Ensuite on va chercher les données sur la question correspondant au résultat
                    $resultsetQuestion = $this->getQuestion($resultat->getRefQuestion());

                    if ($resultsetQuestion)
                    {        
                        // On va chercher la compétence liée à la question dont dépend le résultat (est-ce clair !)
                        $resultsetCatQuestion = $this->getQuestionCategorie($resultsetQuestion['response']['question'][0]->getId());

                        if ($resultsetCatQuestion)
                        {
                            $tabResultats[$i]['code_cat'] = $resultsetCatQuestion['response']['question_cat'][0]->getCodeCat();
                        }
                        else 
                        {
                            $this->registerError("form_request", "Aucune categorie ne correspond à la question.");
                        }
                    }
                    else 
                    {
                        $this->registerError("form_request", "Aucune question n'a été trouvée.");
                    }
                    
                    $i++;
                }
            }
        }
        else
        {
            $this->registerError("form_request", "Aucun resultat n'a été trouvé.");
        }
        
        return $tabResultats;
        
    }
     

}


?>
