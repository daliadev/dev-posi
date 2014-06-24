<?php

require_once(ROOT.'models/dao/utilisateur_dao.php');
require_once(ROOT.'models/dao/niveau_etudes_dao.php');
require_once(ROOT.'models/dao/organisme_dao.php');
require_once(ROOT.'models/dao/session_dao.php');
require_once(ROOT.'models/dao/resultat_dao.php');
require_once(ROOT.'models/dao/degre_dao.php');
require_once(ROOT.'models/dao/categorie_dao.php');



class ServicesAdminStat extends Main
{
    
    private $utilisateurDAO = null;
    private $niveauEtudesDAO = null;
    private $organismeDAO = null;
    private $sessionDAO = null;
    private $degreDAO = null;
    private $resultatDAO = null;
    private $categorieDAO = null;
    
    
    
    public function __construct() 
    {
        $this->errors = array();
        $this->controllerName = "adminStat";

        $this->utilisateurDAO = new UtilisateurDAO();
        $this->niveauEtudesDAO = new NiveauEtudesDAO();
        $this->organismeDAO = new OrganismeDAO();
        $this->sessionDAO = new SessionDAO();
        $this->degreDAO = new DegreDAO();
        $this->resultatDAO = new ResultatDAO();
        $this->categorieDAO = new CategorieDAO();
    }

    


    public function getCustomStats($startDate = null, $endDate = null, $ref_organ = null)
    {
        $stats = array();

        $stats['global'] = array();
        $stats['global']['nbre_sessions'] = 0;
        $stats['global']['nbre_users'] = 0;
        $stats['global']['temps_total'] = 0;
        $stats['global']['moyenne_temps_session'] = 0;
        $stats['global']['score_total'] = 0;
        $stats['global']['moyenne_score_session'] = 0;
        $stats['global']['age_total'] = 0;
        $stats['global']['age_moyen'] = 0;

        $stats['global']['niveaux'] = array();
        $stats['global']['categories'] = array();
        $stats['global']['organismes'] = array();



        // On récupère toutes les sessions terminées (comprises entre les dates si elles sont indiqués et la ref. de l'organisme, sinon sélectionne toutes les sessions)
        $resultsetSessions = $this->getSessionsDetails($startDate, $endDate, null, $ref_organ);


        
       
        /*****   Calcul des statistiques générales  *****/

        $sessionsList = array();
        $tabRefsUsers = array();

        if ($resultsetSessions)
        {
            $sessionsList = $resultsetSessions['response']['session'];
            $stats['sessions'] = $sessionsList;

            $stats['global']['nbre_sessions'] = count($sessionsList);

            foreach ($sessionsList as $session)
            {
                $stats['global']['temps_total'] += $session->getTempsTotal();
                $stats['global']['score_total'] += $session->getScorePourcent();

                $userId = $session->getRefUser();

                if (count($tabRefsUsers) > 0)
                {
                    $isToken = false;

                    foreach($tabRefsUsers as $refUser)
                    {
                        if ($refUser == $userId)
                        {
                            $isToken = true;
                            break;
                        }
                    }

                    if (!$isToken)
                    {
                        $tabRefsUsers[] = $userId;
                        $stats['global']['nbre_users']++;
                    }
                }
                else
                {
                    $tabRefsUsers[] = $userId;
                    $stats['global']['nbre_users']++;
                }    
            }
        }



        $usersList = array();
        $resultsetUsers = $this->getUsers();

        if ($resultsetUsers)
        {
            foreach($tabRefsUsers as $refUser)
            {
                foreach ($resultsetUsers['response']['utilisateur'] as $user) 
                {
                    
                    if ($refUser == $user->getId())
                    {
                        $today = date("Y-m-d");
                        $dateNaissance = $user->getDateNaiss();

                        $timestampAge = strtotime($today) - strtotime($dateNaissance);

                        $age = floor((($timestampAge / 3600) / 24) / 365.24219879);
                        
                        $stats['global']['age_total'] += $age;

                        $usersList[] = $user;
                        break;
                    }
                }
            }
        }

        $stats['global']['moyenne_temps_session'] = Tools::timeToString(round($stats['global']['temps_total'] / $stats['global']['nbre_sessions']));
        $stats['global']['temps_total'] = str_replace(":", " h ", Tools::timeToString(round($stats['global']['temps_total']), "h:m"))." min";

        $stats['global']['moyenne_score_session'] = round($stats['global']['score_total'] / $stats['global']['nbre_sessions']);
        
        $stats['global']['age_moyen'] = round($stats['global']['age_total'] / $stats['global']['nbre_users']);





        /*****   Calcul des stats par organismes (Prendre les stats de base pour un organisme si un seul sélectionné)   *****/


        if (!empty($ref_organ))
        {
            $resultsetOrgan = $this->getOrganisme($ref_organ);

            if ($resultsetOrgan)
            {
                $stats['global']['organismes'][0]['ref_organ'] = $resultsetOrgan['response']['organisme'][0]->getId();
                $stats['global']['organismes'][0]['nom_organ'] = $resultsetOrgan['response']['organisme'][0]->getNom();
            }

        }
        else
        {
            // Récupération de tous les organismes
            $organsInfos = array();

            $resultsetOrgan = $this->getOrganismes();


            if ($resultsetOrgan && count($resultsetOrgan) > 0)
            {
                $organList = $resultsetOrgan['response']['organisme'];

                $i = 0;

                foreach ($organList as $organ)
                {
                    $refOrgan = $organ->getId();
                    
                    $organsInfos[$i]['ref_organ'] = $organ->getId();
                    $organsInfos[$i]['nom_organ'] = $organ->getNom();

                    $organsInfos[$i]['nbre_sessions'] = 0;
                    $organsInfos[$i]['nbre_users'] = 0;
                    $organsInfos[$i]['temps_total'] = 0;
                    $organsInfos[$i]['moyenne_temps_session'] = 0;
                    $organsInfos[$i]['score_total'] = 0;
                    $organsInfos[$i]['moyenne_score_session'] = 0;
                    $organsInfos[$i]['age_total'] = 0;
                    $organsInfos[$i]['age_moyen'] = 0;

                    $tabRefsUsers = array();

                    //$organsInfos[$i]['total_users'] = count($usersList);
                    reset($sessionsList);

                    foreach ($sessionsList as $session)
                    {
                        if ($session->getRefOrgan() == $refOrgan)
                        {
                            // $organsInfos[$i]['nbre_users']++;
                            $organsInfos[$i]['nbre_sessions']++;

                            $organsInfos[$i]['temps_total'] += $session->getTempsTotal();
                            $organsInfos[$i]['score_total'] += $session->getScorePourcent();


                            $userId = $session->getRefUser();

                            if (count($tabRefsUsers) > 0)
                            {
                                $isToken = false;

                                foreach($tabRefsUsers as $refUser)
                                {
                                    if ($refUser == $userId)
                                    {
                                        $isToken = true;
                                        break;
                                    }
                                }

                                if (!$isToken)
                                {
                                    $tabRefsUsers[] = $userId;
                                    $organsInfos[$i]['nbre_users']++;
                                }
                            }
                            else
                            {
                                $tabRefsUsers[] = $userId;
                                $organsInfos[$i]['nbre_users']++;
                            }    



                        }


                    }
                    

                    //reset($resultsetUsers);
                    if (count($resultsetUsers['response']['utilisateur']) > 0 && count($tabRefsUsers) > 0)
                    {
                        foreach($tabRefsUsers as $refUser)
                        {
                            foreach ($resultsetUsers['response']['utilisateur'] as $user) 
                            {
                                if ($refUser == $user->getId())
                                {
                                    $today = date("Y-m-d");
                                    $dateNaissance = $user->getDateNaiss();

                                    $timestampAge = strtotime($today) - strtotime($dateNaissance);

                                    $age = floor((($timestampAge / 3600) / 24) / 365.24219879);
                                    
                                    $organsInfos[$i]['age_total'] += $age;

                                    break;
                                }
                            }
                        }
                    }



                    $organsInfos[$i]['moyenne_temps_session'] = Tools::timeToString(round($organsInfos[$i]['temps_total'] / $organsInfos[$i]['nbre_sessions']));
                    $organsInfos[$i]['temps_total'] = str_replace(":", " h ", Tools::timeToString(round($organsInfos[$i]['temps_total']), "h:m"))." min";

                    $organsInfos[$i]['moyenne_score_session'] = round($organsInfos[$i]['score_total'] / $organsInfos[$i]['nbre_sessions']);
                    
                    $organsInfos[$i]['age_moyen'] = round($organsInfos[$i]['age_total'] / $organsInfos[$i]['nbre_users']);


                    $i++;
                }

            }
            else
            {
                //erreur pas d'organismes
                $this->registerError('form_valid', "Il n'y a aucun organisme pour effectuer des statistiques.");
            }

            $stats['global']['organismes'] = $organsInfos;

        }






        /*****   Calcul du nombre d'utilisateurs par niveaux d'etudes   *****/

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
                $niveauxInfos[$i]['total_users'] = 0;
                $niveauxInfos[$i]['pourcent'] = 0;
                
                if (count($usersList) > 0)
                {
                    $niveauxInfos[$i]['total_users'] = count($usersList);

                    foreach ($usersList as $user)
                    {
                        if($user->getRefNiveau() == $refNiveau)
                        {
                            $niveauxInfos[$i]['nbre_users']++;

                        }
                    }

                    $niveauxInfos[$i]['pourcent'] = ($niveauxInfos[$i]['nbre_users'] / $niveauxInfos[$i]['total_users']) * 100;
                }

                $i++;
            }
        }

        $stats['global']['niveaux'] = $niveauxInfos;


        


        /*****   Calcul des scores moyen par catégories/compétences   *****/

        

        // Récupèration de la liste des categories
        
        $resultsetcategories = $this->getCategories();

        if ($resultsetcategories)
        {
            $categoriesList = $resultsetcategories['response']['categorie'];
        }

        


        // Récupération des résultats, et de la catégorie associée, correspondants aux sessions sélectionnées

        $tabCatSession = array();

        $i = 0;

        foreach ($sessionsList as $session)
        {
            $resultatsSession = $this->getCategoriesFromSession($session->getId());

            if ($resultatsSession)
            {
                foreach ($resultatsSession['response']['resultat'] as $result) 
                {
                    if (!$result->getReponseChamp())
                    {
                        $correct = false;

                        if ($result->getRefReponseQcm() == $result->getRefReponseQcmCorrecte()) 
                        {
                            $correct = true;
                        }

                        $tabCatSession[$i]['correct'] = $correct;
                        $tabCatSession[$i]['code_cat'] = $result->getRefCat();

                        $i++;
                    }
                }
            }
        }

        
        // On répercute les résultats dans chaque categories
        $tabCategoriesStats = array();

        $i = 0;

        foreach ($categoriesList as $categorie)
        {
            if (strlen($categorie->getCode()) == 2)
            {
                $valuable = true;

                $codeCat = $categorie->getCode();

                $tabCategoriesStats[$i]['code_cat'] = $codeCat;
                $tabCategoriesStats[$i]['nom'] = $categorie->getNom();
                $tabCategoriesStats[$i]['description'] = $categorie->getDescription();
                $tabCategoriesStats[$i]['type_lien'] = $categorie->getTypeLien();
                $tabCategoriesStats[$i]['total'] = 0;
                $tabCategoriesStats[$i]['total_correct'] = 0;

                foreach ($tabCatSession as $catSession)
                {
                    if (substr($catSession['code_cat'], 0, 2) == $codeCat)
                    {
                       // Le nombre de réponses s'incrémentent.
                       $tabCategoriesStats[$i]['total']++;
                       
                       if ($catSession['correct'])
                       {
                           $tabCategoriesStats[$i]['total_correct']++;
                       }
                    }
                }

                // Calcul du poucentage de réussite dans cette catégorie
                
                $tabCategoriesStats[$i]['pourcent'] = 0;

                if ($tabCategoriesStats[$i]['total'] > 0)
                {
                    $tabCategoriesStats[$i]['pourcent'] = round(($tabCategoriesStats[$i]['total_correct'] / $tabCategoriesStats[$i]['total']) * 100);
                }
                else
                {
                    $valuable = false;
                }

                if ($valuable)
                {
                    $i++;
                }
            }
        }


        $stats['global']['categories'] = $tabCategoriesStats;



        return $stats;

    }





    private function getOrganismes()
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




    private function getOrganisme($refOrgan)
    {
        $resultset = $this->organismeDAO->selectById($refOrgan);
        
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






    private function getNiveaux()
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

    
    
    


    private function getCategories()
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
    

    
    

    private function getUsers()
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


    


    private function getSessionsDetails($startDate, $endDate, $refUser, $ref_organ)
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
    




    private function getCategoriesFromSession($refSession)
    {
        // On sélectionne tous les résultats correspondant à l'utilisateur
        $resultsetResultatsCat = $this->resultatDAO->selectBySessionAndCategories($refSession, null);

        // Filtrage des erreurs de la requête
        if (!$this->filterDataErrors($resultsetResultatsCat['response']))
        {
            // Si le résultat est unique
            if (!empty($resultsetResultatsCat['response']['resultat']) && count($resultsetResultatsCat['response']['resultat']) == 1)
            { 
                $ResultatsCat = $resultsetResultatsCat['response']['resultat'];
                $resultsetResultatsCat['response']['resultat'] = array($ResultatsCat);
            }

            return $resultsetResultatsCat;
        }
        
        return false;
    }


}


?>
