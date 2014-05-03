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
        
        // Traitement des erreurs de la requête
        $this->filterDataErrors($resultset['response']);

        if (!empty($resultset['response']['categorie']) && count($resultset['response']['categorie']) == 1)
        { 
            $categorie = $resultset['response']['categorie'];
            $resultset['response']['categorie'] = array($categorie);
        }

        return $resultset;
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
                else 
                {
                    return false;
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
            //$question = $resultsetSelect['response']['categorie'];
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
