<?php

/**
 * Description of services_positionnement
 *
 * @author Nicolas Beurion
 */

require_once(ROOT.'controls/authentication.php');

require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/question_dao.php');
require_once(ROOT.'models/dao/reponse_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');



class ServicesPositionnement extends Main
{

    private $sessionDAO = null;
    private $utilisateurDAO = null;
    private $questionDAO = null;
    private $reponseDAO = null;
    private $resultatDAO = null;
    private $questionCatDAO = null;
    private $categorieDAO = null;
    
    
    
    public function __construct()
    {
        $this->errors = array();
        $this->controllerName = "positionnement";
        
        $this->sessionDAO = new SessionDAO();
        $this->utilisateurDAO = new UtilisateurDAO();
        $this->questionDAO = new QuestionDAO();
        $this->reponseDAO = new ReponseDAO();
        $this->resultatDAO = new ResultatDAO();
        $this->questionCatDAO = new QuestionCategorieDAO();
        $this->categorieDAO = new CategorieDAO();
    }
    
    
    
    
    
    public function intro()
    {
        /*** Test d'authentification de l'intervenant/utilisateur ***/
        
        ServicesAuth::checkAuthentication("user");
        
        $numPage = ServicesAuth::getSessionData("num_page");
        if ($numPage)
        {
            // Redirection vers la dernière page du positionnement visitée
            header("Location: ".SERVER_URL."positionnement/page");
            exit();
        }
        else 
        {
            ServicesAuth::setSessionData("num_page", 0);
        }
        
        
        $returnData = array();
        $returnData['response'] = array();
        $returnData['response']['errors'] = array();
        
        $url = WEBROOT."positionnement/session";
        $returnData['response'] = array('url' => $url);
        
        
        /*** Il faut récupérer le nombre de questions ***/
        $resultset = $this->questionDAO->selectAll();
            
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            // Le nombre est enregistré dans la session
            $returnData['response']['nbre_questions'] = count($resultset['response']['question']);
        }

        $this->setResponse($returnData);
        $this->setTemplate("template_page");
        $this->render("intro");
    }
    
    
    
    public function session()
    {
        
        /*** Test d'authentification de l'intervenant/utilisateur ***/
        ServicesAuth::checkAuthentication("user");
        
        // On test si l'utilisateur est déjà dans une session, c-à-d si il a déjà cliqué sur le bouton suite de la page d'intro
        if (!ServicesAuth::checkUserSession())
        {
            // Si ce n'est pas le cas, on ouvre une session
            ServicesAuth::openUserSession();
            
            // Il faut savoir combien de questions possède le questionnaire
            $resultset = $this->questionDAO->selectAll();
            
            // Traitement des erreurs de la requête
            if (!$this->filterDataErrors($resultset['response']))
            {
                // Le nombre est enregistré dans la session
                $total = count($resultset['response']['question']);
                ServicesAuth::setSessionData("nbre_questions", $total);
            }
            

            /*-----   Enregistrement des infos de départ de la session : ref_user, date, validation  -----*/

            // Récupération des infos necéssaires
            $refUser = ServicesAuth::getSessionData("ref_user");
            $refIntervenant = ServicesAuth::getSessionData("ref_intervenant");
              
            $dateSession = date("Y-m-d H:i:s");
            ServicesAuth::setSessionData("date_session", $dateSession);

            $dataSession = array(
                'ref_user' => $refUser,
                'ref_intervenant' => $refIntervenant,
                'date_session' => $dateSession,
                'session_accomplie' => 0,
                'temps_total' => "0",
                'validation' => 0
            );

            // Insertion dans la table session
            $resultset = $this->sessionDAO->insert($dataSession);
            
            // Traitement des erreurs de la requête
            if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['session']['last_insert_id']) && !empty($resultset['response']['session']['last_insert_id']))
            {
                ServicesAuth::setSessionData("ref_session", $resultset['response']['session']['last_insert_id']);
            }
            else 
            {
                $this->registerError("form_request", "La session n'a pu être insérée.");
            }
            
            
            // Mise à jour du nbre de sessions de l'utilisateur

            $resultsetUser = $this->utilisateurDAO->selectById($refUser);
            
            if (!$this->filterDataErrors($resultsetUser['response']))
            {
                $nbreUserSession = $resultsetUser['response']['utilisateur']->getSessionsTotales();
                $dataUser['nbre_sessions_totales'] = intval($nbreUserSession) + 1;
                $dataUser['ref_user'] = $refUser;
                
                // On met a jour la table "utilisateur"
                $resultset = $this->utilisateurDAO->update($dataUser);
            }
            
        }


        // S'il n'y a aucune erreur
        if (empty($this->errors)) 
        {
            ServicesAuth::setSessionData("num_page", 1);
            ServicesAuth::setSessionData("page_reset", false);
            
            // Redirection vers la première page du positionnement
            header("Location: ".SERVER_URL."positionnement/page");
            exit();
        }
        else 
        {
            // Redirection vers la page d'erreur interne
            header("Location: ".SERVER_URL."erreur/page500");
            exit();
        }

    }
    
    
    
    
    
    
    public function page()
    {
        
        /*** Test d'authentification de l'intervenant/utilisateur ***/ 
        ServicesAuth::checkAuthentication("user");
        
        
        /*** Gestion du temps de réponse de l'utilisateur ***/
        
        $totalTime = 0;
        // On stop le timer (fin du temps de réponse)
        $endTimer = microtime(true);
        
        // On récupère le temps de départ si il existe et on établit le temps total de réponse
        if (isset($_POST['start_timer']) && !empty($_POST['start_timer']))
        {
            $startTimer = $_POST['start_timer'];
            
            // le temps total est arrondi en millisecondes
            $totalTime = $endTimer - $startTimer;
        }
        
        
        /*** Récupération du numero de la page courante ***/
        
        $pageCourante = 1;
        
        if (isset($_POST['num_page']) && !empty($_POST['num_page']))
        {
            $pageCourante = $_POST['num_page'];
        
            if (ServicesAuth::getSessionData("num_page") == $_POST['num_page'])
            {
                $pageCourante++;
                //$numeroOrdre = $pageCourante + 1;
                //ServicesAuth::setSessionData("num_page", $numeroOrdre);
                ServicesAuth::setSessionData("page_reset", false);
            }
            else 
            {
                $pageCourante = ServicesAuth::getSessionData("num_page");
                ServicesAuth::setSessionData("page_reset", true);
            }
        }
        else
        {
            $pageCourante = ServicesAuth::getSessionData("num_page");
        }
        
        $numeroOrdre = $pageCourante;
        
        ServicesAuth::setSessionData("num_page", $numeroOrdre);
        
        //var_dump(ServicesAuth::getSessionData("page_reset"));
        
        
        
        
        
        /*** On récupère le nombre de questions totales ***/
        
        $nbreQuestions = ServicesAuth::getSessionData("nbre_questions");  
            
        
        if (!ServicesAuth::getSessionData("page_reset"))
        {
            
            /*-----   Traitement des données de la réponse à la question qui vient d'être saisie pour insertion dans la base (table résultat)   -----*/

            if (!empty($_POST))
            {
                $dataResultat = array();

                $dataResultat['ref_session'] = ServicesAuth::getSessionData("ref_session");

                // On récupère la référence de la question
                if (isset($_POST['ref_question']) && !empty($_POST['ref_question']))
                {
                    $dataResultat['ref_question'] = $_POST['ref_question'];
                }
                else 
                {
                    $this->registerError("form_data", "La question n'est pas référencée.");
                }

                // On test si la réponse est de type qcm ou champ
                if (isset($_POST['radio_reponse']) && !empty($_POST['radio_reponse']))
                {
                    $dataResultat['ref_reponse_qcm'] = $_POST['radio_reponse'];

                    // On récupère la référence de la bonne réponse
                    if (isset($_POST['ref_reponse_correcte']) && !empty($_POST['ref_reponse_correcte']))
                    {
                        $dataResultat["ref_reponse_qcm_correcte"] = $_POST['ref_reponse_correcte'];
                    }
                }
                else if (isset($_POST['reponse_champ']))
                {
                    if (!empty($_POST['reponse_champ']))
                    {
                        $dataResultat['reponse_champ'] = $_POST['reponse_champ'];
                    }
                    else
                    {
                        $dataResultat['reponse_champ'] = "";
                    }
                }
                else
                {
                    // Erreur enregistrement réponse utilisateur
                    // Redirection vers la page d'erreur interne
                    header("Location: ".SERVER_URL."erreur/page500");
                    exit();
                }

                // La validation du résultat de la réponse n'est pas encore effectué
                $dataResultat['validation_reponse_champ'] = 0;

                // Récupération du temps total
                $dataResultat['temps_reponse'] = $totalTime;

                // Insertion de la réponse de l'utilisateur dans la table "resultat"

                $resultset = $this->resultatDAO->insert($dataResultat);
            }
        }
        
        /*----- Redirection fin de questionnaire vers la page résultat  -----*/
        
        // On évalue l'état d'avancement du questionnaire
        if ($numeroOrdre > $nbreQuestions)
        {
            // Redirection vers la page résultat
            header("Location: ".SERVER_URL."positionnement/resultat");
            exit();
        }
        
        
        /*-----   Chargement des infos de la question courante   -----*/
        
        $dataPage = array();
        $dataPage['response'] = array();

        
        // On va chercher dans la table "question", la question correspondant au numéro d'ordre (la page suivante)
        $resultsetQuestion = $this->questionDAO->selectByOrdre($numeroOrdre);

        
        if (Config::DEBUG_MODE)
        {
            var_dump($_POST);
            //var_dump($resultsetQuestion['response']);
            var_dump($_SESSION);
            //var_dump($dataResultat);
        }
        
                
        // Traitement des erreurs de récupération de la question
        if (!$this->filterDataErrors($resultsetQuestion['response']))
        {

            $dataPage['response'] = array_merge($resultsetQuestion['response'], $dataPage['response']);
            
            // On commence par récupérer la référence de la question
            $refQuestion = $dataPage['response']['question']->getId();
            
            // Ensuite on va chercher les réponses
            if ($dataPage['response']['question']->getType() == "qcm")
            {
                $resultsetReponses = $this->reponseDAO->selectByQuestion($refQuestion);
                
                if (!$this->filterDataErrors($resultsetReponses['response']))
                {
                    if (!empty($resultsetReponses['response']['reponse']) && count($resultsetReponses['response']['reponse']) == 1)
                    { 
                        $reponse = $resultsetReponses['response']['reponse'];
                        $resultsetReponses['response']['reponse'] = array($reponse);
                    }
                    
                    $dataPage['response'] = array_merge($resultsetReponses['response'], $dataPage['response']);
                }
            } 

            // On passe à la page suivante
            if ($dataPage['response']['question']->getNumeroOrdre() <= $nbreQuestions)
            {
                $dataPage['response']['url'] = WEBROOT."positionnement/page";
            }
            else
            {
                // Erreur page inexistante
                header("Location: ".SERVER_URL."erreur/page404");
                exit();
            }
        }
        else
        {
            // Redirection vers la page d'erreur interne
            header("Location: ".SERVER_URL."erreur/page500");
            exit();
        }
      
        
        /*** Affichage de la page ***/
        
        $this->setResponse($dataPage);
        
        $this->setTemplate("template_page");
        $this->render("page");
    }
    
    
    
    
    
    public function resultat()
    {
        /*** Test d'authentification de l'intervenant/utilisateur ***/
        //ServicesAuth::checkAuthentication("user");


        // On commence par récupérer la liste complète des categories.
        $categories = array();
        
        $resultsetCategories = $this->categorieDAO->selectAll();
        
        if (!$this->filterDataErrors($resultsetCategories['response']))
        {
            if (!empty($resultsetCategories['response']['categorie']) && count($resultsetCategories['response']['categorie']) == 1)
            { 
                $categorie = $resultsetCategories['response']['categorie'];
                $resultsetCategories['response']['categorie'] = array($categorie);
            }
            
            $categories = $resultsetCategories['response']['categorie'];
        }

        
        $totalTime = 0;
        
        // On liste l'ensemble des résultats de l'utilisateur pour la correction
        $tabResultats = array();
        
        $refSession = ServicesAuth::getSessionData("ref_session");
        
        // On sélectionne tous les résultats correspondant à la session en cours
        $resultsetResultats = $this->resultatDAO->selectBySession($refSession);
        
        if (!$this->filterDataErrors($resultsetResultats['response']))
        { 
            $i = 0;
            
            foreach($resultsetResultats['response']['resultat'] as $resultat)
            {
                // On ajoute le temps du résultat de l'utilisateur au temps total.
                $totalTime += $resultat->getTempsReponse();
                        
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
        
        
        /*-----   Mise à jour de la table "session"   -----*/
        
        $dataSession = array();
        $dataSession['session_accomplie'] = 1;
        $dataSession['temps_total'] = $totalTime;
        
        $idSession = ServicesAuth::getSessionData("ref_session");
        
        // Mise à jour de la session
        $resultset = $this->sessionDAO->update($dataSession, $idSession);

        // Traitement des erreurs de la requête
        if ($this->filterDataErrors($resultset['response']) || !isset($resultset['response']['session']['row_count']) || empty($resultset['response']['session']['row_count']))
        {
            $this->registerError("form_request", "La session n'a pu être mises à jour.");
        }
        
        
        // Mise à jour du nbre de sessions terminée de l'utilisateur

        $resultsetUser = $this->utilisateurDAO->selectById(ServicesAuth::getSessionData("ref_user"));

        if (!$this->filterDataErrors($resultsetUser['response']))
        {
            $nbreUserSession = $resultsetUser['response']['utilisateur']->getSessionsAccomplies();
            $dataUser['nbre_sessions_accomplies'] = intval($nbreUserSession) + 1;
            $dataUser['ref_user'] = ServicesAuth::getSessionData("ref_user");

            // On met a jour la table "utilisateur"
            $resultset = $this->utilisateurDAO->update($dataUser);
        }
      
        
        /*** Calcul du nombre total de questions par catégories et le nombre de bonnes réponses pour chaque catégorie.  ***/
        
        $tabCorrection = array();
        $totalGlobal = 0;
        $totalCorrectGlobal = 0;
        $percentGlobal = 0;
        $j = 0;
                
        foreach ($categories as $categorie)
        {
            $codeCat = $categorie->getCode();
            
            $tabCorrection[$j]['code_cat'] = $codeCat;
            $tabCorrection[$j]['total'] = 0;
            $tabCorrection[$j]['total_correct'] = 0;
            $tabCorrection[$j]['nom'] = $categorie->getNom();
            $tabCorrection[$j]['description'] = $categorie->getDescription();
            $tabCorrection[$j]['type_lien'] = $categorie->getTypeLien();

            for ($i = 0; $i < count($tabResultats); $i++)
            {
                if ($tabResultats[$i]['code_cat'] == $codeCat)
                {
                    $tabCorrection[$j]['total']++;
                    $totalGlobal++;

                    if ($tabResultats[$i]['correct'])
                    {
                        $tabCorrection[$j]['total_correct']++;
                        $totalCorrectGlobal++;
                    }
                }
                
            }
            
            
            $j++;
        }
        
        
        /*** Intégration du système d'héritage des résultats ***/
        
        for ($i = 0; $i < count($tabCorrection); $i++)
        {
            // On détermine si c'est une categorie principale ou une sous-categorie
            if (strlen($tabCorrection[$i]['code_cat']) == 2)
            {
                // Catégorie parent
                
                if ($tabCorrection[$i]['type_lien'] == "dynamic")
                {
                    $tabCorrection[$i]['parent'] = true;
                    $parentCode = $tabCorrection[$i]['code_cat'];
                    $tabCorrection[$i]['total'] = 0;
                    $tabCorrection[$i]['total_correct'] = 0;
                    $tabCorrection[$i]['children'] = array();

                    for ($j = 0; $j < count($tabCorrection); $j++)
                    {
                        //if (strlen($tabCorrection[$j]['code_cat']) == 2 && $tabCorrection[$j]['code_cat'] == $parentCode)
                        //{
                            
                        //}
                        //else 
                        if (strlen($tabCorrection[$j]['code_cat']) > 2 && substr($tabCorrection[$j]['code_cat'], 0, 2) == $parentCode)
                        {
                            $tabCorrection[$i]['total'] += $tabCorrection[$j]['total'];
                            $tabCorrection[$i]['total_correct'] += $tabCorrection[$j]['total_correct'];
                            $tabCorrection[$i]['children'][] = $tabCorrection[$j];
                        }
                    }
                }
                else if ($tabCorrection[$i]['type_lien'] == "static")
                {
                    $tabCorrection[$i]['parent'] = true;
                    $parentCode = $tabCorrection[$i]['code_cat'];
                    $tabCorrection[$i]['children'] = false;
                }
                
            }
            else 
            {
                $tabCorrection[$i]['parent'] = false;
                $tabCorrection[$i]['children'] = false;
            }
        }
        
        
        /*** Données envoyées à la page de résultat ***/
        
        $dataPage = array();
        $dataPage['response'] = array();
        $dataPage['response']['correction'] = array();
        $k = 0;
        
        foreach ($tabCorrection as $correction)
        {
            $dataPage['response']['correction'][$k]['parent'] = $correction['parent'];
            $dataPage['response']['correction'][$k]['children'] = $correction['children'];
            $dataPage['response']['correction'][$k]['nom_categorie'] = $correction['nom'];
            $dataPage['response']['correction'][$k]['descript_categorie'] = $correction['description'];
            $dataPage['response']['correction'][$k]['total'] = $correction['total'];
            $dataPage['response']['correction'][$k]['total_correct'] = $correction['total_correct'];

            if ($correction['total'] > 0)
            {
                $dataPage['response']['correction'][$k]['percent'] = round(($correction['total_correct'] * 100) / $correction['total']);
            }
            else 
            {
                $dataPage['response']['correction'][$k]['percent'] = 0;
            }
            
            $k++;
        }
        
        
        /*** Gestion du temps ***/
        
        $stringTime = Tools::timeToString($totalTime);
        $dataPage['response']['temps'] = $stringTime;
        
        
        /*** Stats globales ***/
        
        $percentGlobal = round(($totalCorrectGlobal / $totalGlobal) * 100);
        $dataPage['response']['percent_global'] = $percentGlobal;
        $dataPage['response']['total_global'] = $totalGlobal;
        $dataPage['response']['total_correct_global'] = $totalCorrectGlobal;
                
        
        /*** Déconnexion automatique de l'utilisateur ***/
        ServicesAuth::logout();
        
        /*** Gestion des erreurs ***/
        
        if (!empty($this->errors))
        {
            // S'il y a eu des erreurs, on les affiche dans la page "résultat".
            $dataPage['response']['errors'] = $this->errors;
        }
        
        /*** Affichage de la page de résultat ***/
        $this->setResponse($dataPage);
        
        $this->setTemplate("template_page");
        $this->render("resultat");
    }
 
 
}



?>
