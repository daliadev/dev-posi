<?php


require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/intervenant_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/degre_dao.php');

// require_once(ROOT.'models/dao/reponse_dao.php');

require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');



class ServicesAdminStat extends Main
{
    
    private $organismeDAO = null;
    private $utilisateurDAO = null;
    private $niveauEtudesDAO = null;
    private $sessionDAO = null;
    private $intervenantDAO = null;

    private $resultatDAO = null;

    private $questionDAO = null;
    private $degreDAO = null;
    // private $reponseDAO = null;

    private $questionCatDAO = null;
    private $categorieDAO = null;
    
    
    
    public function __construct() 
    {
        $this->errors = array();
        $this->controllerName = "adminStat";

        $this->organismeDAO = new OrganismeDAO();
        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
        $this->sessionDAO = new SessionDAO();
        $this->intervenantDAO = new IntervenantDAO();
        $this->questionDAO = new QuestionDAO();
        $this->degreDAO = new DegreDAO();

        // $this->reponseDAO = new ReponseDAO();

        $this->resultatDAO = new ResultatDAO();
        $this->questionCatDAO = new QuestionCategorieDAO();
        $this->categorieDAO = new CategorieDAO();
    }

    
    
    
    
    public function getNiveaux()
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
    
    
    
    public function getCategories()
    {
        $resultset = $this->categorieDAO->selectAll();
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
            { 
                $categorie = $resultset['response']['categorie'];
                $resultset['response']['categorie'] = array($categorie);
            }

            return $resultset;
        }
        
        return false;
    }

    /*
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
    */
    

    
    /*
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
    */
    
    
    
    
    /*
    public function getUserSessions($refUser, $refOrganisme)
    {
        $resultset = $this->sessionDAO->selectByUser($refUser, $refOrganisme);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        // Si la session est unique
        if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
        { 
            $session = $resultset['response']['session'];
            $resultset['response']['session'] = array($session);
        }
        
        for ($i = 0; $i < count($resultset['response']['session']); $i++)
        {
            if (!$resultset['response']['session'][$i]->getSessionAccomplie())
            {
                unset($resultset['response']['session'][$i]);
            }
        }
        
        return $resultset;
    }
    */
    

    public function getUserSessionsByOrganisme($refUser, $refOrganisme)
    {
        $resultset = $this->sessionDAO->selectByUser($refUser, $refOrganisme);
        
        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
        
            // Si le résultat est unique
            if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
            { 
                $session = $resultset['response']['session'];
                $resultset['response']['session'] = array($session);
            }
            
            for ($i = 0; $i < count($resultset['response']['session']); $i++)
            {
                if (!$resultset['response']['session'][$i]->getSessionAccomplie())
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

    /*
    public function getIntervenant($refIntervenant)
    {
        $resultset = $this->intervenantDAO->selectById($refIntervenant);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        return $resultset;
    }
    */

    /*
    public function getUser($refUser)
    {
        $resultset = $this->utilisateurDAO->selectById($refUser);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    */
    
    
    
    

    public function getResultatsByUser($refUser)
    {
        // On sélectionne tous les résultats correspondant à l'utilisateur
        $resultsetResultats = $this->resultatDAO->selectByUser($refUser);

        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultsetResultats['response']))
        {
            // Si le résultat est unique
            if (!empty($resultsetResultats['response']['resultat']) && count($resultsetResultats['response']['resultat']) == 1)
            { 
                $resultat = $resultsetResultats['response']['resultat'];
                $resultsetResultats['response']['resultat'] = array($resultat);
            }

            return $resultsetResultats;
        }
        
        return false;

        
    }

    
    
    
    /*
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
                $tabResultats[$i]['correct'] = false;

                if ($resultat->getRefReponseQcm() && $resultat->getRefReponseQcmCorrecte())
                {
                    if ($resultat->getRefReponseQcm() == $resultat->getRefReponseQcmCorrecte())
                    {
                        $tabResultats[$i]['correct'] = true;
                    }
                    
                    // Ensuite on va chercher les données sur la question correspondant au résultat
                    $resultsetQuestion = $this->questionDAO->selectById($resultat->getRefQuestion());

                    if (!$this->filterDataErrors($resultsetQuestion['response']))
                    {        
                        // On va chercher la compétence liée à la question dont dépend le résultat (est-ce clair ?)
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
    */
    


    /*
    public function getSessions()
    {
        $resultset = $this->sessionDAO->selectAll();
        
        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['session']) && count($resultset['response']['session']) == 1)
            { 
                $session = $resultset['response']['session'];
                $resultset['response']['session'] = array($session);
            }

            return $resultset;
        }
        
        return false;
    }
    */


    public function getUsers()
    {
        $resultset = $this->utilisateurDAO->selectAll();
        
        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
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


    


    public function getSessionsDetails($startDate, $endDate, $refUser, $ref_organ)
    {

        $resultsetSessions = $this->sessionDAO->selectByDatesUserOrgan($startDate, $endDate, $refUser, $ref_organ);

        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultsetSessions['response']))
        {
            // Si le résultat est unique
            if (!empty($resultsetSessions['response']['session']) && count($resultsetSessions['response']['session']) == 1)
            { 
                $session = $resultsetSessions['response']['session'];
                $resultsetSessions['response']['session'] = array($session);
            }

            return $resultsetSessions;
        }
        
        return false;
    }
    




    public function getUserOrganismes($refIntervenant)
    {
        $resultset = $this->intervenantDAO->selectById($refIntervenant);

        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Si le résultat est unique
            if (!empty($resultset['response']['intervenant']) && count($resultset['response']['intervenant']) == 1)
            { 
                $intervenant = $resultset['response']['intervenant'];
                $resultset['response']['intervenant'] = array($intervenant);
            }

            if (isset($resultset['response']['intervenant']) && !empty($resultset['response']['intervenant']))
            {
                return $resultset['response']['intervenant'][0]->getRefOrganisme();
            }

        }
 
        return false;
    }
    



    public function getUserStats($refUser, $startDate = false, $endDate = false)
    {
        $userStats = array();
        $userStats['nbre_sessions'] = 0;
        $userStats['temps_total'] = 0;
        $userStats['score_total'] = 0;
        //$userStats['moyenne_temps_sessions'] = 0;
        //$userStats['moyenne_score_sessions'] = 0;

        // On établit la liste des sessions (positionnements) de l'utilisateur
        $userSessionsList = array();
        $resultsetSessions = $this->getUserSessions($refUser, $startDate, $endDate);

        if ($resultsetSessions)
        {
            if (isset($resultsetSessions['response']['session']) && !empty($resultsetSessions['response']['session']))
            {
                $userSessionsList = $resultsetSessions['response']['session'];

                $refsOrganismes = array();

                // On procède au comptage par sessions
                foreach ($userSessionsList as $userSession) 
                {
                    if ($userSession->getSessionAccomplie()) 
                    {
                        $userStats['nbre_sessions']++;
                        $userStats['temps_total'] += $userSession->getTempsTotal();
                        $userStats['score_total'] += $userSession->getScorePourcent();

                        $refOrgan = $this->getUserOrganismes($userSession->getRefIntervenant());
                        
                        $isToken = false;


                        for ($i = 0; $i < count($refsOrganismes); $i++) 
                        {
                            if ($refsOrganismes[$i] == $refOrgan)
                            {
                                $isToken = true;
                                break;
                            }
                        }

                        if (!$isToken)
                        {
                            $refsOrganismes[] = $refOrgan;
                        }
                     
                    }
                }
                
                $userStats['refs_organismes'] = $refsOrganismes;
            }

            return $userStats;
        }


        return false;
    }


    /*
    public function getCategoriesFromResult($result)
    {
        //$categorie = array();

        $refQuestion = $result->getRefQuestion();

        $resultsetCategories = $this->categorieDAO->selectByQuestion($refQuestion);

        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultsetCategories['response']))
        {
            // Si le résultat est unique
            if (!empty($resultsetCategories['response']['categorie']) && count($resultsetCategories['response']['categorie']) == 1)
            { 
                $categorie = $resultsetCategories['response']['categorie'];
                $resultsetCategories['response']['categorie'] = array($categorie);
            }

            return $resultsetCategories;
        }
        
        return false;
    }
    */


    public function getSessionCategoriesStats($refSession)
    {
      
    }


    public function getUserCategoriesStats($refUser)
    {
        $tabResultats = array();

        $tabCategories = array();
        
        //$tabSessionCategories
        //$tabCategories['ref_user'] = $refUser;
        
        /*** On récupère la liste des categories ***/
        $resultsetcategories = $this->getCategories();
        $categoriesList = $resultsetcategories['response']['categorie'];
        
        

        /*** On va chercher tous les résultats classés par utilisateurs ***/

        $resultats = $this->getResultatsByUser($refUser);
        
        if ($resultats)
        {
            $i = 0;

            $tabResultats[$i] = array();
            $tabCategories[$i] = array();

            foreach ($resultats['response']['resultat'] as $result) 
            {
                $tabResultats[$i]['resultat'] = array();
                $tabResultats[$i]['resultat']['ref_session'] = $result->getRefSession();
                $tabResultats[$i]['resultat']['total'] = 0;
                $tabResultats[$i]['resultat']['total_correct'] = 0;

                $refReponseQcm = $result->getRefReponseQcm();

                if (!empty($refReponseQcm) && $refReponseQcm != null)
                {
                    $tabResultats[$i]['resultat']['total']++;

                    if ($result->getRefReponseQcm() == $result->getRefReponseQcmCorrecte())
                    {
                        $tabResultats[$i]['resultat']['total_correct']++;
                    }

                    $tabResultats[$i]['resultat']['categories'] = array();

                    $resultCategories = $this->getCategoriesFromResult($result);

                    if ($resultCategories)
                    {
                        $j = 0;

                        foreach ($resultCategories['response']['categorie'] as $categorie) 
                        {
                            $tabResultats[$i]['resultat']['categories'][$j] = array();  
                            $tabResultats[$i]['resultat']['categories'][$j]['code_cat'] = $categorie->getCode();
                            $tabResultats[$i]['resultat']['categories'][$j]['nom_cat'] = $categorie->getNom();
                            $tabResultats[$i]['resultat']['categories'][$j]['descript_cat'] = $categorie->getDescription();
                            $tabResultats[$i]['resultat']['categories'][$j]['type_lien'] = $categorie->getTypeLien();

                            $j++;
                        }
                    }
                    else
                    {
                        $this->registerError("form_request", "Il n'y a aucune categorie correspondante au résultat");
                    }
                }

                //var_dump($tabResultats[$i]['resultat']);

                $i++;
            }
        }
        else
        {
            $this->registerError("form_request", "Il n'y a pas de résultats pour cet utilisateur");
        }

        /*
        $tabCategories = array();

        for ($i = 0; $i < $tabResultats; $i++) 
        { 
            
            $categories = $tabResultats[$i]['resultat']['categories'];

            for ($i = 0; $i < $categories; $i++) 
            { 
                if ()
            }
        }
        
        exit();
        */


        // On sélectionne tous les résultats correspondant à la session en cours
        //$resultats = $this->getResultatsByCategories($refSession);
        /*
        $tabStatsCat = array();

        $totalGlobal = 0;
        $totalCorrectGlobal = 0;
        $percentGlobal = 0;
        $countValidCategories = 0;
        $j = 0;
        

        foreach ($categoriesList as $categorie)
        {
            $codeCat = $categorie->getCode();

            $tabStatsCat[$j]['code_cat'] = $codeCat;
            $tabStatsCat[$j]['nom'] = $categorie->getNom();
            $tabStatsCat[$j]['description'] = $categorie->getDescription();
            $tabStatsCat[$j]['type_lien'] = $categorie->getTypeLien();
            $tabStatsCat[$j]['total'] = 0;
            $tabStatsCat[$j]['total_correct'] = 0;
            
            // Pour chaque resultat attaché à la catégorie.
            for ($i = 0; $i < count($resultats); $i++)
            {
                if ($resultats[$i]['code_cat'] == $codeCat)
                {
                   // Le nombre de réponses s'incrémentent.
                   $tabStatsCat[$j]['total']++;
                   $totalGlobal++;
                   
                   if ($resultats[$i]['correct'])
                   {
                       $tabStatsCat[$j]['total_correct']++;
                       $totalCorrectGlobal++;
                   }
                }  
            }

            // Calcul du poucentage de réussite dans cette catégorie
            
            if ($tabStatsCat[$j]['total'] > 0)
            {
                $tabStatsCat[$j]['percent'] = round(($tabStatsCat[$j]['total_correct'] * 100) / $tabStatsCat[$j]['total']);
                $countValidCategories++;
            }
            else 
            {
                $tabStatsCat[$j]['percent'] = 0;
            }

            $j++;
        }
        */
        
        /*** Intégration du système d'héritage des résultats ***/
        /*
        for ($i = 0; $i < count($tabStatsCat); $i++)
        {
            // On détermine si c'est une categorie principale ou une sous-categorie
            if (strlen($tabStatsCat[$i]['code_cat']) == 2)
            {
                // Catégorie parent

                if ($tabStatsCat[$i]['type_lien'] == "dynamic")
                {
                    $tabStatsCat[$i]['parent'] = true;
                    $parentCode = $tabStatsCat[$i]['code_cat'];
                    $tabStatsCat[$i]['total'] = 0;
                    $tabStatsCat[$i]['total_correct'] = 0;
                    $tabStatsCat[$i]['children'] = array();

                    for ($j = 0; $j < count($tabStatsCat); $j++)
                    {
                        if (strlen($tabStatsCat[$j]['code_cat']) > 2 && substr($tabStatsCat[$j]['code_cat'], 0, 2) == $parentCode)
                        {
                            $tabStatsCat[$i]['total'] += $tabStatsCat[$j]['total'];
                            $tabStatsCat[$i]['total_correct'] += $tabStatsCat[$j]['total_correct'];
                            $tabStatsCat[$i]['children'][] = $tabStatsCat[$j];
                        }
                    }
                }
                else if ($tabStatsCat[$i]['type_lien'] == "static")
                {
                    $tabStatsCat[$i]['parent'] = true;
                    $parentCode = $tabStatsCat[$i]['code_cat'];
                    $tabStatsCat[$i]['children'] = false;
                }
            }
            else 
            {
                $tabStatsCat[$i]['parent'] = false;
                $tabStatsCat[$i]['children'] = false;
            }

        }
        */
        
        /*** Données envoyées à la page de résultat ***/
        /*
        $posiStats['categories'] = array();
        $k = 0;
        
        foreach ($tabStatsCat as $stat)
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
        */

        /*** Stats globales ***/
        /*
        $percentGlobal = round(($totalCorrectGlobal / $totalGlobal) * 100);
        $posiStats['percent_global'] = $percentGlobal;
        $posiStats['total_global'] = $totalGlobal;
        $posiStats['total_correct_global'] = $totalCorrectGlobal;
        */

        return  $resultats;
    }


    


    public function getCustomStats($startDate = null, $endDate = null, $ref_organ = null)
    {

        



        $globalStats = array();
        $globalStats['nbre_sessions'] = 0;
        $globalStats['nbre_users'] = 0;
        $globalStats['temps_total'] = 0;
        $globalStats['moyenne_temps_session'] = 0;
        $globalStats['score_total'] = 0;
        $globalStats['moyenne_score_session'] = 0;

        $organStats = array();
        $organStats['nbre_sessions'] = 0;
        $organStats['nbre_users'] = 0;
        $organStats['temps_total'] = 0;
        $organStats['nbre_sessions'] = 0;
        $organStats['moyenne_temps_session'] = 0;
        $organStats['score_total'] = 0;
        $organStats['moyenne_score_session'] = 0;

    
        // On récupère toutes les sessions terminées (comprises entre les dates si elles sont indiqués et la ref de l'organisme, sinon sélectionne toutes les sessions)
        $sessions = $this->getSessionsDetails($startDate, $endDate, null, $ref_organ);

        //var_dump($sessions);
        //exit();

        return $sessions;


        /*****   Calcul de la moyenne du temps passé sur un positionnement  *****/

        // => moyenne du temps de chaque utilisateur par positionnement / nbre d'utilisateurs



        // On établit les stats de bases
        /*
        $usersList = array();
        $userStats = array();
        $resultsetUsers = $this->getUsers();

        if ($resultsetUsers)
        {
            $usersList = $resultsetUsers['response']['utilisateur'];


            foreach($usersList as $user)
            {
                $userStats = $this->getUserStats($user->getId());

                
                if ($userStats)
                {
                    $globalStats['nbre_users']++;
                    $globalStats['nbre_sessions'] += $userStats['nbre_sessions'];
                    $globalStats['temps_total'] += $userStats['temps_total'];
                    $globalStats['score_total'] += $userStats['score_total'];
                }

                //$userCats = $this->getUserCategoriesStats($user->getId());


                //var_dump($userResults['response']);

            }
        }

        $globalStats['moyenne_temps_session'] = Tools::timeToString(round($globalStats['temps_total'] / $globalStats['nbre_sessions']));
        $globalStats['temps_total'] = str_replace(":", " h ", Tools::timeToString(round($globalStats['temps_total']), "h:m"))." min";

        $globalStats['moyenne_score_session'] = round($globalStats['score_total'] / $globalStats['nbre_sessions']);
        */


        //exit();
        /*
        $userStats['nbre_sessions'] = 0;
        $userStats['temps_total'] = 0;
        $userStats['moyenne_temps_sessions'] = 0;
        $userStats['moyenne_score_sessions'] = 0;
        */

        // A partir de chaque utilisateur on établit les stats globales des sessions et des utilisateurs




        /*****   Calcul du nombre d'utilisateurs par niveaux d'etudes   *****/

        /*
        $niveauxInfos = array();

        $resultsetNiveaux = $this->getNiveaux();

        if ($resultsetNiveaux)
        {
            $niveauxList = $resultsetNiveaux['response']['niveau_etudes'];

            $i = 0;

            foreach ($niveauxList as $niveau)
            {
                $refNiveau = $niveau->getId();

                $niveauxInfos[$i]['nom_niveau'] = $niveau->getNom();
                $niveauxInfos[$i]['descript_niveau'] = $niveau->getDescription();

                $niveauxInfos[$i]['nbre_users'] = 0;

                if (count($usersList) > 0)
                {
                    //$niveauxInfos[$i]['nbre_users'] = 0;

                    foreach ($usersList as $user)
                    {
                        if($user->getRefNiveau() == $refNiveau)
                        {
                            $niveauxInfos[$i]['nbre_users']++;
                        }
                    }
                }

                $i++;
            }
        }
        
        //var_dump($niveauxInfos);
        //exit();

        $globalStats['niveaux'] = $niveauxInfos;

        */


        


        /*****   Calcul des scores moyen par catégories/compétences   *****/

        

        // Récupèration la liste des categories
        /*
        $resultsetcategories = $this->getCategories();

        if ($resultsetcategories)
        {
            $categoriesList = $resultsetcategories['response']['categorie'];
        }

        */


        /*** On va chercher tous les résultats classés par categories ***/
        
        // On sélectionne tous les résultats correspondant à la session en cours

        /*
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
        */

        return $globalStats;


    }


    
    private function getFilteredSessions() 
    {

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
