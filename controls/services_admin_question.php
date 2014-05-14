<?php


// Fichiers requis pour le formulaire
require_once('models/dao/question_dao.php');
require_once('models/dao/reponse_dao.php');
require_once('models/dao/categorie_dao.php');
require_once('models/dao/degre_dao.php');

require_once(ROOT.'controls/services_admin_categorie.php');



class ServicesAdminQuestion extends Main
{
    
    private $questionDAO = null;
    private $reponseDAO = null;
    private $categorieDAO = null;
    private $degreDAO = null;
    
    private $servicesCategorie = null;
    
    
    
    public function __construct() 
    {
        //$this->errors = array();
        $this->controllerName = "adminQuestion";
        
        $this->questionDAO = new QuestionDAO();
        $this->reponseDAO = new ReponseDAO();
        $this->categorieDAO = new CategorieDAO();
        $this->degreDAO = new DegreDAO();
        
        $this->servicesCategorie = new ServicesAdminCategorie();
    }

    
    
    
    
    public function getQuestions()
    {
        $resultset = $this->questionDAO->selectAll();
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        if (!empty($resultset['response']['question']) && count($resultset['response']['question']) == 1)
        { 
            $question = $resultset['response']['question'];
            $resultset['response']['question'] = array($question);
            //$resultset['response']['question'] = $question;
        }
        
        return $resultset;
    }
    
    
    
    
    public function getQuestion($id_question)
    {
        $resultset = $this->questionDAO->selectById($id_question);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }

    
    
    
    
    public function getQuestionDetails($idQuestion)
    {
        $questionDetails = array();
        
        $questionDetails['num_ordre_question'] = "";
        $questionDetails['intitule_question'] = "";
        $questionDetails['type_question'] = "";
        $questionDetails['image_question'] = "";
        $questionDetails['audio_question'] = "";
        $questionDetails['ref_degre'] = "";
        
        $resultsetQuestion = $this->questionDAO->selectById($idQuestion);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetQuestion['response']))
        {
            $questionDetails['num_ordre_question'] = $resultsetQuestion['response']['question']->getNumeroOrdre();
            $questionDetails['intitule_question'] = $resultsetQuestion['response']['question']->getIntitule();
            $questionDetails['type_question'] = $resultsetQuestion['response']['question']->getType();
            $questionDetails['image_question'] = $resultsetQuestion['response']['question']->getImage();
            $questionDetails['audio_question'] = $resultsetQuestion['response']['question']->getSon();
            $questionDetails['ref_degre'] = $resultsetQuestion['response']['question']->getRefDegre();

            $categories = $this->getQuestionCategories($idQuestion);
            $questionDetails['categories'] = $categories;
            $questionDetails['code_cat'] = $categories[0]['code_cat'];
            
            if ($questionDetails['type_question'] == "qcm")
            {
                // On récupére le tableau des réponses correspondant à la question
                $reponses = $this->getReponses($idQuestion);
                if ($reponses)
                {
                    $questionDetails['reponses'] = $reponses;
                }
                else 
                {
                    $this->registerError("form_empty", "Il n'y a pas de réponses pour cette question.");
                }
            }
        }

        return $questionDetails;
    }
    
    
    
    
    
    public function getQuestionCategories($idQuestion)
    {
        $categories = array();
        //$categories['code_cat'] = "";
        //$categories['nom_cat'] = "";
        //$categories['descript_cat'] = "";
        //$categories['type_lien_cat'] = "";
        
        $resultsetCategories = $this->categorieDAO->selectByQuestion($idQuestion);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetCategories['response']) && !empty($resultsetCategories['response']['categorie']))
        {
            if (!empty($resultsetCategories['response']['categorie']) && count($resultsetCategories['response']['categorie']) == 1)
            { 
                $categorie = $resultsetCategories['response']['categorie'];
                $resultsetCategories['response']['categorie'] = array($categorie);
            }
            
            $i = 0;
            foreach($resultsetCategories['response']['categorie'] as $cat)
            {
                $categories[$i] = array();
                $categories[$i]['code_cat'] = $cat->getCode();
                $categories[$i]['nom_cat'] = $cat->getNom();
                $categories[$i]['descript_cat'] = $cat->getDescription();
                $categories[$i]['type_lien_cat'] = $cat->getTypeLien();
                
                $i++;
            }
        }
        
        return $categories;
    }
    
    
    
    
    
    public function filterQuestionData(&$formData, $postData)
    {
        $dataQuestion = array();
        $dataReponses = array();
        
        /*** Récupération de la référence de la question ***/
        
        if (isset($formData['ref_question']) && !empty($formData['ref_question']))
        {
            $dataQuestion['ref_question'] = $formData['ref_question'];
        }
        
        
        /*** Récupèration de la référence du degré d'aptitude ***/
            
        if (isset($postData['ref_degre']) && !empty($postData['ref_degre']) && $postData['ref_degre'] != "aucun")
        {
            $formData['ref_degre'] = $postData['ref_degre'];
            $dataQuestion['ref_degre'] = $formData['ref_degre'];
        }
        else
        {
            $formData['ref_degre'] = null;
            $dataQuestion['ref_degre'] = null;
        }


        /*** Récupèration de la categorie ***/

        $formData['code_cat'] = $this->validatePostData($postData['code_cat_cbox'], "code_cat_cbox", "integer", true, "Aucune catégorie n'a été sélectionnée.", "La catégorie n'est pas correctement sélectionnée.");
        $dataQuestion['code_cat'] = $formData['code_cat'];


        /*** Récupèration de l'intitule de la question ***/

        $formData['intitule_question'] = $this->validatePostData($postData['intitule_question'], "intitule_question", "string", true, "Aucun intitulé n'a été saisi.", "L'intitulé n'a été correctement saisi.");
        $dataQuestion['intitule_question'] = $formData['intitule_question'];


        /*** Récupèration du numero d'ordre de la question ***/

        $formData['num_ordre_question'] = $this->validatePostData($postData['num_ordre_question'], "num_ordre_question", "integer", true, "Aucun numéro d'ordre n'a été saisi.", "Le numéro d'ordre est incorrecte.");
        $dataQuestion['num_ordre_question'] = $formData['num_ordre_question'];


        /*** Traitement du type de question ***/

        // Test pour savoir quel est le type de question
        if (isset($postData['type_question']) && !empty($postData['type_question']))
        {
            if ($postData['type_question'] == "qcm")
            {
                $formData['type_question'] = "qcm";
            }
            else if ($postData['type_question'] == "champ_saisie")
            {
                $formData['type_question'] = "champ_saisie"; 
            }

            $dataQuestion['type_question'] = $formData['type_question'];
        }
        else 
        {
            $this->registerError("form_empty", "Aucun type de question n'a été saisi.");
        }


        /*** Traitement des réponses du type qcm ***/

        if ($formData['type_question'] == "qcm")
        {
            if (isset($postData['intitules_reponses']) && is_array($postData['intitules_reponses']) && count($postData['intitules_reponses']) > 0)
            {
                $estCorrect = 0;
                $dataReponses = array();
                
                for ($i = 0; $i < count($postData['intitules_reponses']); $i++)
                {
                    $dataReponses[$i]['ref_question'] = $formData['ref_question'];

                    $formData['reponses'][$i]['num_ordre_reponse'] = $i + 1;
                    $dataReponses[$i]['num_ordre_reponse'] = $formData['reponses'][$i]['num_ordre_reponse'];

                    if (isset($postData['ref_reponses'][$i]) && !empty($postData['ref_reponses'][$i]))
                    {
                        $formData['reponses'][$i]['ref_reponse'] = $postData['ref_reponses'][$i];
                        $dataReponses[$i]['ref_reponse'] = $formData['reponses'][$i]['ref_reponse'];
                    }

                    if (isset($postData['intitules_reponses'][$i]))
                    {
                        $intituleQuestion = $this->filterData($postData['intitules_reponses'][$i], "string");
                        if ($intituleQuestion != "empty" && $intituleQuestion != false)
                        {
                            $formData['reponses'][$i]['intitule_reponse'] = $postData['intitules_reponses'][$i];
                        }
                        else 
                        {
                            $formData['reponses'][$i]['intitule_reponse'] = "";
                        }
                        $dataReponses[$i]['intitule_reponse'] = $formData['reponses'][$i]['intitule_reponse'];
                    }

                    if (isset($postData['correct']) && $postData['correct'] == $dataReponses[$i]['num_ordre_reponse'])
                    {
                        $estCorrect = 1;
                        $formData['reponses'][$i]['est_correct'] = 1;
                        $dataReponses[$i]['est_correct'] = $formData['reponses'][$i]['est_correct'];
                    }
                    else 
                    {
                        $formData['reponses'][$i]['est_correct'] = 0;
                        $dataReponses[$i]['est_correct'] = 0;
                    }
                }
                
                $dataQuestion['data_reponses'] = $dataReponses;
                        
                if ($estCorrect === 0)
                {
                    $this->registerError("form_empty", "Vous n'avez pas sélectionné la bonne réponse.");
                }
            }
            else 
            {
                $this->registerError("form_empty", "Vous devez saisir au moins 1 réponse.");
            }
        }


        /*** Traitement de l'image ***/
        
        //$imageName = "";

        if (isset($_FILES['image_file']['name']) && !empty($_FILES['image_file']['name']))
        {
            $mimeType = str_replace("image/", "", $_FILES['image_file']['type']);

            if ($mimeType == "jpeg" || $mimeType == "jpg") 
            {
                $formData['image_upload'] = true;
                $formData['image_question'] = "";
                $dataQuestion['image_question'] = null;
            }
            else
            {
                $this->registerError("form_valid", "Le format de l'image est incorrect.");
            }
        }
        else if (isset($postData['image_question']) && !empty($postData['image_question']))
        {

            $formData['image_upload'] = false;
            $formData['image_question'] = $postData['image_question'];
            $dataQuestion['image_question'] = $formData['image_question'];
        }
        else 
        {
            //$this->registerError("form_empty", "Aucune image n'a été sélectionnée.");
            $formData['image_upload'] = false;
            $formData['image_question'] = "";
            $dataQuestion['image_question'] = null;
        }



        /*** Traitement du son ***/

        if (isset($_FILES['audio_file']['name']) && !empty($_FILES['audio_file']['name']))
        {
            $mimeType = str_replace("audio/", "", $_FILES['audio_file']['type']);

            if ($mimeType == "mp3" || $mimeType == "mpeg" || $mimeType == "mpeg3")
            {
                $formData['audio_upload'] = true;
                $formData['audio_question'] = "";
                $dataQuestion['audio_question'] = null;
            }
            else
            {
                $this->registerError("form_empty", "Le format du son est incorrect.");
            }
        }
        else if (isset($postData['audio_question']) && !empty($postData['audio_question']))
        {
            $formData['audio_upload'] = false;
            $formData['audio_question'] = $postData['audio_question'];
            $dataQuestion['audio_question'] = $formData['audio_question'];
        }
        else 
        {
            //$this->registerError("form_empty", "Aucun son n'a été sélectionné.");
            $formData['audio_upload'] = false;
            $formData['audio_question'] = "";
            $dataQuestion['audio_question'] = null;
        }
        
        return $dataQuestion;

    }
    
    
    
    
    
    public function setQuestionProperties($previousMode, $dataQuestion, &$formData)
    {

        $dataReponses = array();

        // On commence par extraire les réponses (si elles existent) des données de la question
        if (isset($dataQuestion['data_reponses']) && !empty($dataQuestion['data_reponses']))
        {
            $dataReponses = $dataQuestion['data_reponses'];
            unset($dataQuestion['data_reponses']);
        }
        
        
        if ($previousMode == "new")
        {
            // On test pour savoir si le numéro d'ordre de la question à enregistrer existe déjà
            $numsOrdreList = $this->getNumsOrdreList();

            $questionExist = false;
            $shiftOrdre = false;

            for ($i = 0; $i < count($numsOrdreList); $i++) 
            {
                if ($numsOrdreList[$i] == $formData['num_ordre_question'])
                {
                    // S'il est réservé, on décale les numéros d'ordre avec n+1 pour toutes les questions supérieures à la question active (shift = décaler);
                    $shiftOrdre = $this->shiftNumsOrdre($formData['num_ordre_question'], 1);
                    
                    $questionExist = true;

                    break;
                }
            }
            
            if ($shiftOrdre || !$questionExist)
            {
                // Insertion des médias
                if ($formData['image_upload'])
                {
                    $formData['image_question'] = $this->setMedia("image", $_FILES, "img_".$formData['num_ordre_question'].".jpg");
                    $dataQuestion['image_question'] = $formData['image_question'];
                }
                
                if ($formData['audio_upload'])
                {
                    $formData['audio_question'] = $this->setMedia("audio", $_FILES, "audio_".$formData['num_ordre_question'].".mp3");
                    $dataQuestion['audio_question'] = $formData['audio_question'];
                }


                // Insertion de la question dans la bdd
                $resultsetQuestion = $this->setQuestion("insert", $dataQuestion);


                if (isset($resultsetQuestion['response']['question']['last_insert_id']) && !empty($resultsetQuestion['response']['question']['last_insert_id']))
                {
                    // Insertion des réponses si le type est QCM
                    $formData['ref_question'] = $resultsetQuestion['response']['question']['last_insert_id'];
                    $dataQuestion['ref_question'] = $formData['ref_question'];

                    if ($formData['type_question'] == "qcm")
                    {
                        if (!empty($dataReponses) && count($dataReponses) > 0)
                        {
                            for ($i = 0; $i < count($dataReponses); $i++)
                            {
                                $dataReponses[$i]['ref_question'] = $formData['ref_question'];
                            }

                            $resultsetReponses = $this->setReponses($dataReponses, $formData['ref_question']);

                        }
                        else
                        {
                            $this->registerError("form_valid", "Vous devez saisir au moins 1 réponse.");
                        }
                    }

                    // Insertion de la catégorie
                    $resultsetCategorie = $this->servicesCategorie->setQuestionCategorie("insert", $dataQuestion['ref_question'], $dataQuestion['code_cat']);

                    if (!$resultsetCategorie)
                    {
                        $this->registerError("form_request", "L'insertion de la catégorie liée à la question a échouée.");
                    }
                }
                else 
                {
                    $this->registerError("form_valid", "L'enregistrement de la question a échouée.");
                }
                
                /*
                // En cas de probleme, on supprime les médias
                if (!empty($this->errors)) 
                {
                    $imageFile = ROOT.IMG_PATH.$formData['image_question'];
                    $thumbFile = ROOT.THUMBS_PATH."thumb_".$formData['image_question'];
                    $soundFile = ROOT.AUDIO_PATH.$formData['audio_question'];
                    
                    if (file_exists($imageFile)) : unlink($imageFile); endif;
                    if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                    if (file_exists($soundFile)) : unlink($soundFile); endif;   
                }
                */
            }
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {

            if (isset($dataQuestion['ref_question']) && !empty($dataQuestion['ref_question']))
            {
                $formData['ref_question'] = $dataQuestion['ref_question'];

                // Insertion des médias
                if ($formData['image_upload'])
                {
                    $formData['image_question'] = $this->setMedia("image", $_FILES, "img_".$formData['num_ordre_question'].".jpg");
                    $dataQuestion['image_question'] = $formData['image_question'];
                }
                
                if ($formData['audio_upload'])
                {
                    $formData['audio_question'] = $this->setMedia("audio", $_FILES, "audio_".$formData['num_ordre_question'].".mp3");
                    $dataQuestion['audio_question'] = $formData['audio_question'];
                }

                // Mise à jour de la question
                $resultsetQuestion = $this->setQuestion("update", $dataQuestion);

                if ($resultsetQuestion)
                {
                    // Mises à jour des réponses
                    if ($formData['type_question'] == "qcm")
                    {
                        if (!empty($dataReponses) && count($dataReponses) > 0)
                        {
                            $resultsetReponses = $this->setReponses($dataReponses, $formData['ref_question']);
                        }
                        else
                        {
                            $this->registerError("form_empty", "Vous devez saisir au moins 1 réponse.");
                        }
                    }
                    else if ($formData['type_question'] == "champ_saisie")
                    {
                        // On efface les réponses s'il y en a
                        $this->deleteReponses($formData['ref_question']);
                    }

                    
                    // Mise à jour de la catégorie
                    $resultsetCategorie = $this->servicesCategorie->setQuestionCategorie("update", $dataQuestion['ref_question'], $dataQuestion['code_cat']);

                    if (!$resultsetCategorie)
                    {
                        $this->registerError("form_valid", "La mise à jour de la catégorie a échouée.");
                    }
                }
                else
                {
                    $this->registerError("form_valid", "La mise à jour de la question a échouée.");
                }
                /*
                if (!empty($this->errors)) 
                {
                    $imageFile = ROOT.IMG_PATH.$formData['image_question'];
                    $thumbFile = ROOT.THUMBS_PATH."thumb_".$formData['image_question'];
                    $soundFile = ROOT.AUDIO_PATH.$formData['audio_question'];
                    
                    if (file_exists($imageFile)) : unlink($imageFile); endif;
                    if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                    if (file_exists($soundFile)) : unlink($soundFile); endif;   
                }
                */
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }


        //var_dump($this->errors);
        //exit();

        /*** Traitement de l'image ***/
        
        /*
        if (empty($this->errors))
        {
            if ($formData['image_upload'] && isset($_FILES['image_file']['name']) && !empty($_FILES['image_file']['name']))
            {
                $imageFile = ROOT.IMG_PATH.$formData['image_question'];
                $thumbFile = ROOT.THUMBS_PATH."thumb_".$formData['image_question'];

                if (file_exists($imageFile)) : unlink($imageFile); endif;
                if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                
                $this->uploadMedia($_FILES['image_file'], "image", array("jpg"), ROOT.IMG_PATH, $formData['image_question']);

                if (!empty($this->errors))
                {
                    $this->registerError("form_valid", "L'image n'a pas pu être enregistrée.");
                    if (file_exists($imageFile)) : unlink($imageFile); endif;
                    if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                }
                else
                {
                    // On intégre le média à la base
                }
            }
        }
        else
        {
            $this->registerError("form_valid", "L'image n'a pas été enregistrée.");
        }
        */
        

        /*** Traitement du son ***/
        /*
        if (empty($this->errors))
        {
            if ($formData['audio_upload'] && isset($_FILES['audio_file']['name']) && !empty($_FILES['audio_file']['name']))
            {
                $soundFile = ROOT.AUDIO_PATH.$formData['audio_question'];

                if (file_exists($soundFile)) : unlink($soundFile); endif;

                $this->uploadMedia($_FILES['audio_file'], "son", array("mp3"), ROOT.AUDIO_PATH, $formData['audio_question']);

                if (!empty($this->errors)) 
                {
                    $this->registerError("form_valid", "Le son n'a pas pu être enregistré.");
                    if (file_exists($soundFile)) : unlink($soundFile); endif;
                }
            }
        }
        else
        {
            $this->registerError("form_valid", "Le son n'a pas été enregistré.");
        }
        */
    }
    
    
    
    
    
    public function setQuestion($modeRequete, $dataQuestion)
    {

        if (!empty($dataQuestion) && is_array($dataQuestion))
        {
            $success = false;
            
            if ($modeRequete == "insert")
            {
                if (isset($dataQuestion['code_cat']) && !empty($dataQuestion['code_cat']))
                {
                    unset($dataQuestion['code_cat']);
                }
                $resultset = $this->questionDAO->insert($dataQuestion);

                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question']['last_insert_id']) && !empty($resultset['response']['question']['last_insert_id']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "La question n'a pu être insérée.");
                }
            }
            else if ($modeRequete == "update")
            {

                if (!empty($dataQuestion['ref_question']))
                {
                    if (isset($dataQuestion['code_cat']) && !empty($dataQuestion['code_cat']))
                    {
                        unset($dataQuestion['code_cat']);
                    }

                    $resultset = $this->questionDAO->update($dataQuestion);
                    
                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "La question n'a pu être mise à jour.");
                    }
                }
                else 
                {
                    $this->registerError("form_request", "L'identifiant de la question est manquant.");
                }
            }
            else 
            {
                return false;
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de la question non autorisée.");
        }
            
        return false;
    }
    
    
    
    
    
    public function deleteQuestion($refQuestion)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->questionDAO->selectById($refQuestion);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $question = $resultsetSelect['response']['question'];
            $resultsetDelete = $this->questionDAO->delete($refQuestion);

            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                // On supprime les fichiers médias   
                if ($question->getImage() && $question->getSon())
                {
                    $imageFile = ROOT.IMG_PATH.$question->getImage();
                    $thumbFile = ROOT.THUMBS_PATH."thumb_".$question->getImage();
                    $soundFile = ROOT.AUDIO_PATH.$question->getSon();
                    
                    if (file_exists($imageFile)) : unlink($imageFile); endif;
                    if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                    if (file_exists($soundFile)) : unlink($soundFile); endif;   
                }

                // On décale toutes les questions qui suivent d'un cran
                $shiftOrdre = $this->shiftNumsOrdre($question->getNumeroOrdre(), -1);
                
                if ($shiftOrdre)
                {
                    return true;
                }
                else
                {
                    $this->registerError("form_request", "Le décalage des questions a échoué.");
                }
            }
            else 
            {
                $this->registerError("form_request", "Impossible de supprimer la question.");
            }
        }
        else
        {
           $this->registerError("form_request", "Cette question n'existe pas."); 
        }

        return false;
    }
    
    


    public function setMedia($type, &$files, $mediaName)
    {
        if (isset($files['image_file']['name']) && !empty($files['image_file']['name']))
        {

            if ($type == "image")
            {
                $imageFile = ROOT.IMG_PATH.$mediaName;
                $thumbFile = ROOT.THUMBS_PATH."thumb_".$mediaName;

                if (file_exists($imageFile)) : unlink($imageFile); endif;
                if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                
                $this->uploadMedia($files['image_file'], "image", array("jpg"), ROOT.IMG_PATH, $mediaName);

                if (!empty($this->errors))
                {
                    $this->registerError("form_valid", "L'image n'a pas pu être enregistrée.");
                    if (file_exists($imageFile)) : unlink($imageFile); endif;
                    if (file_exists($thumbFile)) : unlink($thumbFile); endif;
                }
            }
            else if ($type == "audio")
            {
                $soundFile = ROOT.AUDIO_PATH.$mediaName;

                if (file_exists($soundFile)) : unlink($soundFile); endif;

                $this->uploadMedia($_FILES['audio_file'], "son", array("mp3"), ROOT.AUDIO_PATH, $mediaName);

                if (!empty($this->errors)) 
                {
                    $this->registerError("form_valid", "Le son n'a pas pu être enregistré.");
                    if (file_exists($soundFile)) : unlink($soundFile); endif;
                }
            }
        }

        return $mediaName;
    }



    public function deleteMedia($path, $mediaName)
    {
        if (file_exists($path.$mediaName)) : unlink($path.$mediaName); endif;
    }
    
    


    
    public function getReponses($refQuestion)
    {
        $reponses = array();
        
        $resultsetReponses = $this->reponseDAO->selectByQuestion($refQuestion);

        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultsetReponses['response']))
        {
            if (isset($resultsetReponses['response']['reponse']) && !empty($resultsetReponses['response']['reponse']))
            {
                if (count($resultsetReponses['response']['reponse']) == 1)
                {
                    $reponse = $resultsetReponses['response']['reponse'];
                    $resultsetReponses['response']['reponse'] = array($reponse);
                }

                $i = 0;
                foreach($resultsetReponses['response']['reponse'] as $reponse)
                {
                    $reponses[$i] = array();
                    $reponses[$i]['ref_reponse'] = $reponse->getId();
                    $reponses[$i]['ref_question'] = $reponse->getRefQuestion();
                    $reponses[$i]['num_ordre_reponse'] = $reponse->getNumeroOrdre();
                    $reponses[$i]['intitule_reponse'] = $reponse->getIntitule();
                    $reponses[$i]['est_correct'] = $reponse->getEstCorrect();
                    
                    $i++;
                }
                
                return $reponses;
            }

        }
        
        return false;
    }
    
    
    
    
    public function setReponses($dataReponses, $refQuestion)
    {
                
        if (!empty($dataReponses) && is_array($dataReponses) && count($dataReponses) > 0)
        {
                
            // on commence par chercher les réponses déjà existantes et on les supprime
            $existReponses = $this->getReponses($refQuestion);
            
            if ($existReponses)
            {
                for ($i = 0; $i < count($existReponses); $i++)
                {
                    if (!isset($dataReponses[$i]['intitule_reponse']) || empty($dataReponses[$i]['intitule_reponse']))
                    {
                        $this->deleteReponse($existReponses[$i]['ref_reponse']);
                        
                    }
                }
            }


            $successCount = 0;
            $countReponses = 0;
            
            for ($i = 0; $i < count($dataReponses); $i++)
            {
                if (isset($dataReponses[$i]) && !empty($dataReponses[$i]))
                {
                    $dataReponse = $dataReponses[$i];

                    if (!empty($dataReponse['intitule_reponse']) && strlen($dataReponse['intitule_reponse']) > 0 && !empty($dataReponse['num_ordre_reponse']) && (!empty($dataReponse['est_correct']) || $dataReponse['est_correct'] == 0))
                    {
                        //var_dump($dataReponse['intitule_reponse']);
                        $countReponses++;

                        if (empty($dataReponse['ref_reponse']))
                        {
                            // Insertion de la réponse
                            $resultset = $this->reponseDAO->insert($dataReponse);

                            // Traitement des erreurs de la requête
                            if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']['last_insert_id']) && !empty($resultset['response']['reponse']['last_insert_id']))
                            {
                                $successCount++;
                            }
                        }
                        else
                        {
                            // Mise à jour de la réponse
                            $resultset = $this->reponseDAO->update($dataReponse);

                            // Traitement des erreurs de la requête
                            if (!$this->filterDataErrors($resultset['response']))
                            {
                                $successCount++;
                            }  
                        }
                    }
                    else
                    {
                        unset($dataReponses[$i]);
                    }
                }
            } 
            
            /*
            var_dump($countReponses);
            var_dump($successCount);
            var_dump($dataReponses);
            exit();
            */

            if ($successCount == $countReponses)
            {
                return true;
            }
            else if ($successCount > 0)
            {
                $this->registerError("form_valid", "Toutes les réponses n'ont pas pu être sauvegardées.");
            }
            else
            {
                $this->registerError("form_valid", "Aucune réponse n'a pu être sauvegardée.");
            }
            
        }
        
        
        return false;
    }
    
    
    /*
    public function setReponses($modeRequete, $dataReponses)
    {     
        
        if (!empty($dataReponses) && is_array($dataReponses) && count($dataReponses) > 0)
        {
            if (count($dataReponses) > 2)
            {
                $successCount = 0;

                foreach ($dataReponses as $dataReponse)
                {   
                    if (!empty($dataReponse['intitule_reponse']) && !empty($dataReponse['num_ordre_reponse']) && (!empty($dataReponse['est_correct']) || $dataReponse['est_correct'] == 0))
                    {
                        if ($modeRequete == "insert")
                        {
                            if (empty($dataReponse['ref_reponse']))
                            {
                                $resultset = $this->reponseDAO->insert($dataReponse);

                                // Traitement des erreurs de la requête
                                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']['last_insert_id']) && !empty($resultset['response']['reponse']['last_insert_id']))
                                {
                                    $successCount++;
                                    //return $resultset;
                                }
                                else 
                                {
                                    $this->registerError("form_request", "La réponse n'a pu être insérée.");
                                }
                            }
                        }
                        else if ($modeRequete == "update")
                        {
                            if (!empty($dataReponse['ref_reponse']))
                            {
                                $resultset = $this->reponseDAO->update($dataReponse);

                                // Traitement des erreurs de la requête
                                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']['row_count']) && !empty($resultset['response']['reponse']['row_count']))
                                {
                                    $successCount++;
                                    //return $resultset;
                                } 
                                else 
                                {
                                    $this->registerError("form_request", "La réponse n'a pu être mise à jour.");
                                }
                            }
                            else 
                            {
                                $this->registerError("form_request", "L'identifiant de la réponse est manquant.");
                            }
                        }
                        else 
                        {
                            return true;
                        }
                    }
                    else 
                    {
                        $this->registerError("form_request", "Insertion de la réponse non autorisée.");
                    }
                }
            }
            else 
            {
                $this->registerError("form_data", "La question doit contenir au moins 2 réponses.");
            }
            
            if ($successCount == count($dataReponses))
            {
                return true;
            }
            
        }
        
        return false;

    }
    */
    
    public function deleteReponses($refQuestion)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultset = $this->reponseDAO->selectByQuestion($refQuestion);

        $success = 0;
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['reponse']) && !empty($resultset['response']['reponse']))
        {  
            $reponses = $resultset['response']['reponse'];
            
            if (is_array($reponses) && count($reponses) > 0)
            {
                foreach ($reponses as $reponse)
                {
                    $refReponse = $reponse->getId();
                    $resultsetDelete = $this->reponseDAO->delete($refReponse);

                    if (!$this->filterDataErrors($resultsetDelete['response']))
                    {
                        $success++;
                    }
                }
            }
            else 
            {
                $refReponse = $reponses->getId();
                $resultsetDelete = $this->reponseDAO->delete($refReponse);
                 
                if (!$this->filterDataErrors($resultsetDelete['response']))
                {
                   $success++;
                }
            }

            if ($success == count($reponses))
            {
                return true;
            }
        }
        
        return false;
    }
    
    
    public function deleteReponse($refReponse)
    {
        
        $resultsetDelete = $this->reponseDAO->delete($refReponse);
        if (!$this->filterDataErrors($resultsetDelete['response']))
        {
            return true;
        }
        
        return false;
    }

    
    
    
    
    
    public function getNumsOrdreList()
    {
        $resultset = $this->questionDAO->selectAll();
        
        $numsOrdreList = array();
        
        if (!empty( $resultset['response']['question']))
        {
            if (!$this->filterDataErrors($resultset['response']))
            {
                if (is_array($resultset['response']['question']))
                {
                    $questions = $resultset['response']['question'];
                    foreach ($questions as $question)
                    {
                        $numsOrdreList[] = $question->getNumeroOrdre();
                    }
                }
                else 
                {
                    $numsOrdreList[] = $resultset['response']['question']->getNumeroOrdre();
                }
                
                
                return $numsOrdreList;
            }
        }

        return false;
    }
    
    
    
    
    
    public function getLastNumOrdre()
    {
        $resultset = $this->questionDAO->selectAll();
        
        if (!empty( $resultset['response']['question']))
        {
            $this->filterDataErrors($resultset['response']);

            $questions = $resultset['response']['question'];
            
            $i = 0;
            $lastNum = 0;
                
            if (is_array($resultset['response']['question']))
            {
                while ($i < count($questions))
                {
                    $lastNum = $questions[$i]->getNumeroOrdre();
                    $i++;
                }
            } 
            else 
            {
                $lastNum = $questions->getNumeroOrdre();
            }
        }
        else 
        {
            $lastNum = 0;
        }

        return $lastNum;
    }

    
    
    
    
    /**
     * Créer un décalage de $offset de toutes les questions à partir de la question sélectionnée jusqu'à la dernière.
     * 
     * @param int $numOrdre Position dans la série des numéros
     * 
     */
    public function shiftNumsOrdre($numOrdre, $offset)
    {
        $lastNum = $this->getLastNumOrdre();
        
        if (Config::DEBUG_MODE)
        {
            echo "\$lastNum = ".$lastNum."<br/>";
            echo "\$numOrdre = ".$numOrdre."<br/>";
            echo "\$offset = ".$offset."<br/>";
            exit();
        }
        
        $erreur = false;

        if ($offset > 0)
        {
            for ($i = $lastNum; $i >= $numOrdre; $i--)
            {
                $newNumOrdre = $i + $offset;

                $oldImageName = "img_".$i.".jpg";
                $oldThumbName = "thumb_"."img_".$i.".jpg";
                $oldAudioName = "audio_".$i.".mp3";

                $newImageName = "img_".$newNumOrdre.".jpg";
                $newThumbName = "thumb_"."img_".$newNumOrdre.".jpg";
                $newAudioName = "audio_".$newNumOrdre.".mp3";

                rename(ROOT.IMG_PATH.$oldImageName, ROOT.IMG_PATH.$newImageName);
                rename(ROOT.THUMBS_PATH.$oldThumbName, ROOT.THUMBS_PATH.$newThumbName);
                rename(ROOT.AUDIO_PATH.$oldAudioName, ROOT.AUDIO_PATH.$newAudioName);

                $resultset = $this->questionDAO->shiftOrder($i, $offset, $newImageName, $newAudioName);
                
                if ($this->filterDataErrors($resultset['response']) || empty($resultset['response']['question']['row_count']))
                {
                    $erreur = true;
                    break;
                }
            }
        }
        else if ($offset < 0)
        {
            for ($i = ($numOrdre + 1); $i <= $lastNum; $i++)
            {
                $newNumOrdre = $i + $offset;

                $oldImageName = "img_".$i.".jpg";
                $oldThumbName = "thumb_"."img_".$i.".jpg";
                $oldAudioName = "audio_".$i.".mp3";

                $newImageName = "img_".$newNumOrdre.".jpg";
                $newThumbName = "thumb_"."img_".$newNumOrdre.".jpg";
                $newAudioName = "audio_".$newNumOrdre.".mp3";

                rename(ROOT.IMG_PATH.$oldImageName, ROOT.IMG_PATH.$newImageName);
                rename(ROOT.THUMBS_PATH.$oldThumbName, ROOT.THUMBS_PATH.$newThumbName);
                rename(ROOT.AUDIO_PATH.$oldAudioName, ROOT.AUDIO_PATH.$newAudioName);

                $resultset = $this->questionDAO->shiftOrder($i, $offset, $newImageName, $newAudioName);

                if ($this->filterDataErrors($resultset['response']) || empty($resultset['response']['question']['row_count']))
                {
                    $erreur = true;
                    break;
                }
            }
        }
        
        if ($erreur)
        {
            $this->registerError("form_request", "Erreur lors du décalage des médias.");
            return false;
        }
        else 
        {
            return true;
        }
    }


    
    
    public function uploadMedia($file, $mediaType, $allowFormat, $path, $name)
    {
        $media_question = null;
                
        if ($file['error'] == 0)
        {
            // Récupération du suffix du fichier
            $ext = strtolower(substr($file['name'], -3));

            if (in_array($ext, $allowFormat))
            {
                //$name = uniqid();

                // Déplacement du fichier de sa position temp vers sa destination finale
                if (move_uploaded_file($file['tmp_name'], $path.$name))
                {
                    if ($mediaType == "image")
                    {
                        require_once(ROOT."utils/image_uploader.php");
                        
                        // On recréé l'image au bon format
                        ImageUploader::create($path.$name, $path, $name, $ext, true, 750, 420);

                        // On créé la vignette de l'image
                        ImageUploader::create($path.$name, ROOT.THUMBS_PATH, "thumb_".$name, $ext, false, 112, 70);
                    }

                    $media_question = $name;

                }
                else 
                {
                    $this->registerError("form_data", "Aucun média ".$mediaType." n'a pu être chargée");
                }
            }
            else 
            {
                $this->registerError("form_data", "Le format du fichier n'est pas autorisé ou n'est pas de type ".$mediaType.".");
            }
        }
        else 
        {
            $this->registerError("form_empty", "Prise en charge impossible du fichier ".$mediaType.".");
        }
        
        return $media_question;
    }
    
    
    
    
    
}


?>
