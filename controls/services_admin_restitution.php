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
        $this->filterDataErrors($resultset['response']);

        if (!empty($resultset['response']['organisme']) && count($resultset['response']['organisme']) == 1)
        { 
            $organisme = $resultset['response']['organisme'];
            $resultset['response']['organisme'] = array($organisme);
        }

        return $resultset;
    }
    
    
    
    
    
    public function getUsersFromOrganisme($refOrganisme)
    {
        $resultset = $this->utilisateurDAO->selectByOrganisme($refOrganisme);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        // Si l'utilisateur est unique
        if (!empty($resultset['response']['utilisateur']) && count($resultset['response']['utilisateur']) == 1)
        { 
            $utilisateur = $resultset['response']['utilisateur'];
            $resultset['response']['utilisateur'] = array($utilisateur);
        }
        
        return $resultset;
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
                if ($resultset['response']['session'][$i]->getSessionAccomplie() == 1)
                {
                    unset($resultset['response']['session'][$i]);
                }
            }

            return $resultset;
        }
        
        return false;
    }
    
    /*
    public function getSession($refSession)
    {
        $resultset = $this->sessionDAO->selectById($refSession);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        // Si la session est unique
        
        if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
        { 
            $session = $resultset['response']['session'];
            $resultset['response']['session'] = array($session);
        }
        
        return $resultset;
    }
    */
    
    public function getIntervenant($refIntervenant)
    {
        $resultset = $this->intervenantDAO->selectById($refIntervenant);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        return $resultset;
    }
    
    /*
    public function getUser($refUser)
    {
        $resultset = $this->utilisateurDAO->selectById($refUser);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    */
    
    
    /*
    public function getNiveau($refNiveau)
    {
        $resultset = $this->niveauEtudesDAO->selectById($refNiveau);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        return $resultset;
    }
    */
    
    
    public function getCategories()
    {
        $resultset = $this->categorieDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
        { 
            $categorie = $resultset['response']['categorie'];
            $resultset['response']['categorie'] = array($categorie);
        }
        
        return $resultset;
    }

    
    
    
 
    public function getResultatsByCategories($refSession)
    {
        $tabResultats = array();
        
        // On sélectionne tous les résultats correspondant à la session en cours
        $resultsetResultats = $this->resultatDAO->selectBySession($refSession);
        
        if (!$this->filterDataErrors($resultsetResultats['response']))
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
                    $resultsetQuestion = $this->questionDAO->selectById($resultat->getRefQuestion());

                    if (!$this->filterDataErrors($resultsetQuestion['response']))
                    {        
                        // On va chercher la compétence liée à la question dont dépend le résultat (est-ce clair !)
                        $resultsetCatQuestion = $this->questionCatDAO->selectByRefQuestion($resultsetQuestion['response']['question']->getId());

                        if (!$this->filterDataErrors($resultsetCatQuestion['response']))
                        {
                            $tabResultats[$i]['code_cat'] = $resultsetCatQuestion['response']['question_cat']->getCodeCat();
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
        
        return $tabResultats;
        
    }
    
    
    
    /*
    public function getResultatsBySession($refSession)
    {
        $resultset = $this->sessionDAO->selectById($refSession);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        // Si la session est unique
        
        if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
        { 
            $session = $resultset['response']['session'];
            $resultset['response']['session'] = array($session);
        }
        
        return $resultset;
    }
    */
    
    /*
    public function getQuestions()
    {
        $resultset = $this->questionDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        // Si la session est unique
        
        if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
        { 
            $question = $resultset['response']['question'];
            $resultset['response']['question'] = array($question);
        }
        
        return $resultset;
    }
    */
    
    
    
    

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
        
        $resultsetUser = $this->utilisateurDAO->selectById($refUser);
        
        if (!$this->filterDataErrors($resultsetUser['response']))
        {
            if (!empty($resultsetUser['response']['utilisateur']) && count($resultsetUser['response']['utilisateur']) == 1)
            { 
                $user = $resultsetUser['response']['utilisateur'];
                $resultsetUser['response']['utilisateur'] = array($user);
                
                if (!empty($resultsetUser['response']['utilisateur']))
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
                    
                    $resultsetNiveau = $this->niveauEtudesDAO->selectById($utilisateur->getRefNiveau());
                    $this->filterDataErrors($resultsetNiveau['response']);
                    $userInfos['nom_niveau'] = $resultsetNiveau['response']['niveau_etudes']->getNom();
                    $userInfos['descript_niveau'] = $resultsetNiveau['response']['niveau_etudes']->getDescription();
                }
                
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
                        //if (strlen($tabStats[$j]['code_cat']) == 2 && $tabStats[$j]['code_cat'] == $parentCode)
                        //{
                            
                        //}
                        //else 
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
        
        //$dataPage = array();
        //$posiStats = array();
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

        
        
        /*
        $posiStats['nb_reponses'] = $totalGlobal;
        $posiStats['nb_rep_correctes'] = $totalCorrectGlobal;
        if ($totalGlobal > 0 && $totalCorrectGlobal > 0)
        {
            $posiStats['percent_total'] = round(($totalCorrectGlobal * 100) / $totalGlobal);
        }
        */
        /*
        if ($countValidCategories > 0)
        {
            $posiStats['percent_total'] = round($percentGlobal / $countValidCategories);
        }
        else 
        {
            $posiStats['percent_total'] =  0;
        }
        */
        
        return  $posiStats;
    }
    
    
    
    
    
    public function getQuestionsDetails($refSession)
    {
        $questionsDetails = array();
                
        $resultsetQuestions = $this->questionDAO->selectAll();
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetQuestions['response']))
        {
            if (!empty($resultsetQuestions['response']['question']) && count($resultsetQuestions['response']['question']) == 1)
            { 
                $question = $resultsetQuestions['response']['question'];
                $resultsetQuestions['response']['question'] = array($question);
            }
            
            $i = 0;
            foreach ($resultsetQuestions['response']['question'] as $question)
            {
                 /*** Les informations suivantes qui concernent les résultats sont initialisées ***/
                
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
                    
                $questionsDetails[$i]['reponse_user_qcm'] = "-";
                $questionsDetails[$i]['reponse_qcm_correcte'] = "-";
                $questionsDetails[$i]['reponse_user_champ'] = "-";
                $questionsDetails[$i]['intitule_reponse_user'] = "";
                $questionsDetails[$i]['intitule_reponse_correcte'] = "-";
                $questionsDetails[$i]['temps'] = "indisponible";
                $questionsDetails[$i]['reussite'] = "-";
   
                
                /*** Degré ***/
                $refDegre = $question->getRefDegre();
                if (!empty($refDegre))
                {
                    $resultsetDegre = $this->degreDAO->selectById($question->getRefDegre());
                    $this->filterDataErrors($resultsetDegre['response']);
                    $questionsDetails[$i]['nom_degre'] = $resultsetDegre['response']['degre']->getNom();
                    $questionsDetails[$i]['descript_degre'] = $resultsetDegre['response']['degre']->getDescription();
                }
                
                
                /*** Catégories ***/
                
                $resultsetCategories = $this->categorieDAO->selectByQuestion($question->getId());
                $this->filterDataErrors($resultsetCategories['response']);
                if (!empty($resultsetCategories['response']['categorie']) && count($resultsetCategories['response']['categorie']) == 1)
                { 
                    $categorie = $resultsetCategories['response']['categorie'];
                    $resultsetCategories['response']['categorie'] = array($categorie);
                }

                $categories = array();
                $j = 0;
                foreach ($resultsetCategories['response']['categorie'] as $cat)
                {
                    $categories[$j] = array();
                    
                    $code_cat = $cat->getCode();
                    if (strlen($code_cat) > 2)
                    {
                        $parentCode = substr($code_cat, 0, 2);
                        $resultCatParent = $this->categorieDAO->selectByCode($parentCode);
                        //var_dump($resultCatParent['response']);
                        //exit();
                        if (!$this->filterDataErrors($resultCatParent['response']))
                        {
                            $categories[$j]['nom_cat_parent'] = $resultCatParent['response']['categorie']->getNom();
                            $categories[$j]['descript_cat_parent'] = $resultCatParent['response']['categorie']->getDescription();
                        }
                        
                    }
                    
                    
                    $categories[$j]['nom_cat'] = $cat->getNom();
                    $categories[$j]['descript_cat'] = $cat->getDescription();
                    $j++;
                }
                $questionsDetails[$i]['categories'] = $categories;

                
                /*** Réponses ***/
                if ($question->getType() == "qcm")
                {
                    $resultsetReponses = $this->reponseDAO->selectByQuestion($question->getId());
                    $this->filterDataErrors($resultsetReponses['response']);
                    if (!empty($resultsetReponses['response']['reponse']) && count($resultsetReponses['response']['reponse']) == 1)
                    { 
                        $reponse = $resultsetReponses['response']['reponse'];
                        $resultsetReponses['response']['reponse'] = array($reponse);
                    }
                    
                    $reponses = array();
                    $j = 0;
                    foreach ($resultsetReponses['response']['reponse'] as $rep)
                    {
                        $reponses[$j] = array();
                        $reponses[$j]['ref_reponse'] = $rep->getId();
                        $reponses[$j]['num_ordre_reponse'] = $rep->getNumeroOrdre();
                        $reponses[$j]['intitule_reponse'] = $rep->getIntitule();
                        $reponses[$j]['est_correcte'] = $rep->getEstCorrect();
                        $j++;
                    }
                    $questionsDetails[$i]['reponses'] = $reponses;
                }

                $i++;
            }
        }

        
        $resultsetResultats = $this->resultatDAO->selectBySession($refSession);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetResultats['response']))
        {
            if (!empty($resultsetResultats['response']['resultat']) && count($resultsetResultats['response']['resultat']) == 1)
            { 
                $resultat = $resultsetResultats['response']['resultat'];
                $resultsetResultats['response']['resultat'] = array($resultat);
            }
            
            
            $resultatUser = array();
            $i = 0;
            foreach ($resultsetResultats['response']['resultat'] as $result)
            {
                $resultatUser[$i] = array();
                $resultatUser[$i]['ref_resultat'] = $result->getId();
                $resultatUser[$i]['ref_reponse_qcm'] = $result->getRefReponseQcm();
                $resultatUser[$i]['ref_reponse_qcm_correcte'] = $result->getRefReponseQcmCorrecte();
                $resultatUser[$i]['reponse_champ'] = $result->getReponseChamp();
                $resultatUser[$i]['temps_reponse'] = $result->getTempsReponse();
                
                
                if (!empty($resultatUser[$i]['reponse_champ']))
                {
                    $questionsDetails[$i]['reponse_user_champ'] =  $resultatUser[$i]['reponse_champ'];
                }
                else if (!empty($resultatUser[$i]['ref_reponse_qcm']) && !empty($resultatUser[$i]['ref_reponse_qcm_correcte']))
                {
                    for ($j = 0; $j < count($questionsDetails[$i]['reponses']); $j++)
                    {
                        if (!empty($questionsDetails[$i]['reponses'][$j]))
                        {
                            if (!empty($resultatUser[$i]['ref_reponse_qcm']) && $questionsDetails[$i]['reponses'][$j]['ref_reponse'] == $resultatUser[$i]['ref_reponse_qcm'])
                            {
                                $questionsDetails[$i]['reponse_user_qcm'] = $questionsDetails[$i]['reponses'][$j]['num_ordre_reponse'];
                                $questionsDetails[$i]['intitule_reponse_user'] = $questionsDetails[$i]['reponses'][$j]['intitule_reponse'];
                            }
                            if (!empty($resultatUser[$i]['ref_reponse_qcm_correcte']) && $questionsDetails[$i]['reponses'][$j]['ref_reponse'] == $resultatUser[$i]['ref_reponse_qcm_correcte'])
                            {
                                $questionsDetails[$i]['reponse_qcm_correcte'] = $questionsDetails[$i]['reponses'][$j]['num_ordre_reponse'];
                                $questionsDetails[$i]['intitule_reponse_correcte'] = $questionsDetails[$i]['reponses'][$j]['intitule_reponse'];
                            }
                        }
                        else {
                            $questionsDetails[$i]['reponse_user_qcm'] = "-";
                            $questionsDetails[$i]['intitule_reponse_user'] = "-";
                        }
                    }
                    
                    if ($resultatUser[$i]['ref_reponse_qcm'] != "-" || $resultatUser[$i]['ref_reponse_qcm_correcte'] != "-")
                    {
                        if ($resultatUser[$i]['ref_reponse_qcm'] == $resultatUser[$i]['ref_reponse_qcm_correcte'])
                        {
                            $questionsDetails[$i]['reussite'] = 1;
                        }
                        else 
                        {
                            $questionsDetails[$i]['reussite'] = 0;
                        }
                    }
                    else 
                    {
                        $questionsDetails[$i]['reussite'] = "-";
                    }
                }
               
                if (!empty($resultatUser[$i]['temps_reponse']))
                {
                    $questionsDetails[$i]['temps'] = $resultatUser[$i]['temps_reponse'];
                }

                $i++;
            }
        }
        
        return $questionsDetails;
    }

}


?>
