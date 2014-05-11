<?php


/**
 * Description de CategorieDAO
 *
 * @author Nicolas Beurion
 */



// Inclusion du fichier de la classe Categorie
require_once(ROOT.'models/question_cat.php');



class QuestionCategorieDAO extends ModelDAO
{

    
    
    /**
     * selectAll - Retourne la liste de toutes les catégories
     * 
     * @return array Liste d'objets "Categorie"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM question_cat ORDER BY code_cat ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "question_cat", "QuestionCategorie");

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByCode - Récupère la catégorie correspondant au code.
     * 
     * @param string Code de la catégorie
     * @return array Catégorie correspondant au code sinon erreurs
     */
    public function selectByCodeCat($codeCat) 
    {
        $this->initialize();
        
        if (!empty($codeCat))
        {
            $request = "SELECT * FROM question_cat WHERE ref_cat = ".$codeCat;

            $this->resultset['response'] = $this->executeRequest("select", $request, "question_cat", "QuestionCategorie");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
        }
        
        return $this->resultset;
    }
    
    
    public function selectByRefQuestion($refQuestion) 
    {
        $this->initialize();
        
        if (!empty($refQuestion))
        {
            $request = "SELECT * FROM question_cat WHERE ref_question = ".$refQuestion;

            $this->resultset['response'] = $this->executeRequest("select", $request, "question_cat", "QuestionCategorie");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun code pour la catégorie recherchée.");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère une catégorie
     * 
     * @param array Valeurs de la catégorie à inserer
     * @return bool Vrai si l'insertion a fonctionné
     */
    public function insert($values) 
    { 
        $this->initialize();
        
        if (isset($values['ref_question']) && !empty($values['ref_question']) && isset($values['ref_cat']) && !empty($values['ref_cat']))
        {
            $request = "INSERT INTO question_cat (ref_question, ref_cat) VALUES (".$values['ref_question'].", '".$values['ref_cat']."')";

            $this->resultset['response'] = $this->executeRequest("insert", $request, "question_cat", "QuestionCategorie");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
            
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour une catégorie
     * 
     * @param array Valeurs de la catégorie à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
       
        
        if (!empty($values))
        {
            if (isset($values['ref_question']) && !empty($values['ref_question']) && isset($values['ref_cat']) && !empty($values['ref_cat']))
            {
                $refQuestion = $values['ref_question'];
                unset($values['ref_question']);
                
                $request = $this->createQueryString("update", $values, "question_cat", "WHERE ref_question = ".$refQuestion);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "question_cat", "QuestionCategorie");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la question et la catégorie à mettre à jour.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface une catégorie
     * 
     * @param int Identifiant de la question
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refQuestion) 
    {
        $this->initialize();
        
        if (!empty($refQuestion))
        {
            $request = "DELETE FROM question_cat WHERE ref_question = ".$refQuestion;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "question_cat", "QuestionCategorie");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la catégorie.");
        }

        return $this->resultset;
    }
    

}

?>
