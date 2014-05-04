<?php



// Fichiers requis pour le formulaire
require_once(ROOT.'models/dao/categorie_dao.php');
require_once(ROOT.'models/dao/question_cat_dao.php');


class ServicesAdminCategorie extends Main
{
    
    private $categorieDAO = null;
    private $questionCatDAO = null;
    
    
    
    public function __construct() 
    {
        //$this->errors = array();
        $this->controllerName = "adminCategorie";

        $this->categorieDAO = new CategorieDAO();
        $this->questionCatDAO = new QuestionCategorieDAO();
    }

    
    
    
    public function getCategories()
    {
        $resultset = $this->categorieDAO->selectAll();
        
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
    
    

    public function getCategorie($codeCat)
    {
        $resultset = $this->categorieDAO->selectByCode($codeCat);
        
        if (!$this->filterDataErrors($resultset['response']))
        {
            return $resultset;
        }

        return false;
    }



    public function getCategorieDetails($codeCat)
    {
        $catDetails = array();
        
        $catDetails['code_cat'] = "";
        $catDetails['nom_cat'] = "";
        $catDetails['descript_cat'] = "";
        $catDetails['type_lien_cat'] = "";

        
        $resultset = $this->categorieDAO->selectByCode($codeCat);
        
        // Traitement des erreurs de la requête
        if (!$this->filterDataErrors($resultset['response']))
        {
            $catDetails['code_cat'] = $resultset['response']['categorie']->getCode();
            $catDetails['nom_cat'] = $resultset['response']['categorie']->getNom();
            $catDetails['descript_cat'] = $resultset['response']['categorie']->getDescription();
            $catDetails['type_lien_cat'] = $resultset['response']['categorie']->getTypeLien();
        }

        return $catDetails;
    }



    public function filterCategorieData(&$formData, $postData)
    {
        $dataCategorie = array();
        

        // Récupèration du code catégorie original s'il y en a un
        if (isset($postData['code']) && !empty($postData['code']))
        {
            $formData['code'] = $postData['code'];
            $dataCategorie['code'] = $formData['code'];
        }
        
        // Formatage du code catégorie
        $formData['code_cat'] = $this->validatePostData($_POST['code_cat'], "code_cat", "integer", true, "Aucun code de catégorie n'a été saisi.", "Le code n'est pas correctement saisi.");
        $dataCategorie['code_cat'] = $formData['code_cat'];
        
        // Formatage du nom de la catégorie
        $formData['nom_cat'] = $this->validatePostData($_POST['nom_cat'], "nom_cat", "string", true, "Aucun nom de catégorie n'a été saisi", "Le nom n'est pas correctement saisi.");
        $dataCategorie['nom_cat'] = $formData['nom_cat'];
        
        // Formatage de l'intitule de la catégorie 
        $formData['descript_cat'] = $this->validatePostData($_POST['descript_cat'], "descript_cat", "string", false, "Aucune description n'a été saisi", "La description n'a été correctement saisi.");
        $dataCategorie['descript_cat'] = $formData['descript_cat'];
        

        // Formatage du type de lien de la catégorie
        if (isset($_POST['type_lien_cat']))
        {
            $formData['type_lien_cat'] = "dynamic";
            $dataCategorie['type_lien_cat'] = "dynamic";
        }
        else 
        {
            $formData['type_lien_cat'] = "static";
            $dataCategorie['type_lien_cat'] = "static";
        }

        return $dataCategorie;
    }




    public function setCategorieProperties($previousMode, $dataCategorie, &$formData)
    {

        if ($previousMode == "new")
        {
            // Insertion de la catégorie dans la bdd
            $resultsetCategorie = $this->setCategorie("insert", $dataCategorie);
                    
            // Traitement des erreurs de la requête
            if ($resultsetCategorie['response'])
            {
                $formData['code_cat'] = $resultsetCategorie['response']['categorie']['code_cat'];
                $dataCategorie['code_cat'] = $formData['code_cat'];
                $this->registerSuccess("La catégorie a été enregistrée.");
            }
            else 
            {
                $this->registerError("form_valid", "L'enregistrement de la catégorie a échouée.");
            }
        }
        else if ($previousMode == "edit"  || $previousMode == "save")
        {
            if (isset($dataCategorie['code_cat']) && !empty($dataCategorie['code_cat']))
            {
                $formData['code_cat'] = $dataCategorie['code_cat'];

                // Mise à jour de la catégorie
                $resultsetCategorie = $this->setCategorie("update", $dataCategorie);

                // Traitement des erreurs de la requête
                if ($resultsetCategorie['response'])
                {
                    $this->registerSuccess("La catégorie a été mise à jour.");
                }
                else
                {
                    $this->registerError("form_valid", "La mise à jour de la catégorie a échoué.");
                }
            }
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page404");
            exit();
        }
    }



    public function setCategorie($modeCategorie, $dataCategorie)
    {
        if (!empty($dataCategorie) && is_array($dataCategorie))
        {
            if (!empty($dataCategorie['code_cat']) && !empty($dataCategorie['nom_cat']))
            {
                if ($modeCategorie == "insert")
                {
                    $resultset = $this->categorieDAO->insert($dataCategorie);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']))
                    {
                        return $resultset;
                    }
                    else 
                    {
                        $this->registerError("form_request", "La catégorie n'a pu être insérée.");
                    }
                    
                }
                else if ($modeCategorie == "update")
                { 
                    $resultset = $this->categorieDAO->update($dataCategorie);

                    // Traitement des erreurs de la requête
                    if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['categorie']['row_count']) && !empty($resultset['response']['categorie']['row_count']))
                    {
                        return $resultset;
                    } 
                    else 
                    {
                        $this->registerError("form_request", "La catégorie n'a pu être mise à jour.");
                    }
                }
                
            }
            else 
            {
                $this->registerError("form_request", "Le code ou le nom de la catégorie sont manquants.");
            }
        }
        else 
        {
            $this->registerError("form_request", "Insertion de la catégorie non autorisée");
        }
            
        return false;
    }
    
    
    
    
    public function deleteCategorie($codeCat)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->categorieDAO->selectByCode($codeCat);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->categorieDAO->delete($codeCat);
        
            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "La catégorie n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Cette catégorie n'existe pas."); 
        }

        return false;
    }
    



    
    public function getQuestionCategorie($refQuestion)
    {
        $resultset = $this->questionCatDAO->selectByRefQuestion($refQuestion);
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);
        
        return $resultset;
    }
    
    
    
    public function setQuestionCategorie($modeCategorie, $refQuestion, $codeCat)
    {
        if (!empty($refQuestion) && !empty($codeCat))
        {
            if ($modeCategorie == "insert")
            {
                $resultset = $this->questionCatDAO->insert(array('ref_question' => $refQuestion, 'ref_cat' => $codeCat));
                
                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']))
                {
                    return $resultset;
                }
                else 
                {
                    $this->registerError("form_request", "La catégorie liée à la question n'a pas pu être insérée.");
                }
            }
            else if ($modeCategorie == "update")
            { 
                $resultset = $this->questionCatDAO->update(array('ref_question' => $refQuestion, 'ref_cat' => $codeCat));

                // Traitement des erreurs de la requête
                if (!$this->filterDataErrors($resultset['response']) && isset($resultset['response']['question_cat']['row_count']))
                {
                    return $resultset;
                } 
                else 
                {
                    $this->registerError("form_request", "La catégorie liée à la question n'a pu être mise à jour.");
                }
            }
            else 
            {
                return true;
            }
        }
        else 
        {
            $this->registerError("form_request", "Le code categorie ou la reférence de la question manquants.");
        }

        return false;
    }
    
    
    
    
    public function deleteQuestionCategorie($refQuestion)
    {
        // On commence par sélectionner les réponses associèes à la question
        $resultsetSelect = $this->questionCatDAO->selectByRefQuestion($refQuestion);
        
        if (!$this->filterDataErrors($resultsetSelect['response']))
        { 
            $resultsetDelete = $this->questionCatDAO->delete($refQuestion);
        
            if (!$this->filterDataErrors($resultsetDelete['response']))
            {
                return true;
            }
            else 
            {
                $this->registerError("form_request", "La catégorie n'a pas pu être supprimée.");
            }
        }
        else
        {
           $this->registerError("form_request", "Cette catégorie n'existe pas."); 
        }

        return false;
    }
    
}


?>
