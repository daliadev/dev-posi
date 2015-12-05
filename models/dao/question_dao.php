<?php



// Inclusion du fichier de la classe Question
require_once(ROOT.'models/question.php');
//require_once(ROOT.'models/degre.php');
//require_once(ROOT.'models/reponse.php');


class QuestionDAO extends ModelDAO
{
   
    
    public function __construct()
    {
        $this->initialize();
    }
    
    
    
    /**
     * selectAll - Retourne la liste de toutes les questions
     * 
     * @return array Liste d'objets "Question"
     */
    public function selectAll() 
    {
        $this->initialize();

        $request = "SELECT * FROM question ORDER BY num_ordre_question ASC";
        
        $this->resultset['response'] = $this->executeRequest("select", $request, "question", "Question");
        
        return $this->resultset;
    }
    
    

    
    
    /**
     * selectById - Récupère la question correspondant à l'identifiant
     * 
     * @param int Identifiant de la question
     * @return array Question correspondant à l'identifiant sinon erreurs
     */
    public function selectById($idQuestion) 
    {
        $this->initialize();
        
        if (!empty($idQuestion))
        {
            $request = "SELECT * FROM question WHERE id_question=".$idQuestion;

            $this->resultset['response'] = $this->executeRequest("select", $request, "question", "Question");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun identifiant pour la question.");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByOrdre - Récupère la question correspondant à l'identifiant
     * 
     * @param int Identifiant de la question
     * @return array Question correspondant à l'identifiant sinon erreurs
     */
    public function selectByOrdre($numOrdre) 
    {
        $this->initialize();
        
        if (!empty($numOrdre))
        {
            $request = "SELECT * FROM question WHERE num_ordre_question = ".$numOrdre;

            $this->resultset['response'] = $this->executeRequest("select", $request, "question", "Question");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucune question correspondante à ce numero d'ordre.");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère une question
     * 
     * @param array Valeurs de la question à inserer
     * @return array Dernier identifiant d'insertion sinon erreurs
     */
    public function insert($values) 
    {   
        $this->initialize();

        if (!empty($values))
        {
            if (isset($values['ref_question']) && !empty($values['ref_question']))
            {
                unset($values['ref_question']);
            }
                
            $request = $this->createQueryString("insert", $values, "question");

            $this->resultset['response'] = $this->executeRequest("insert", $request, "question", "Question");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour une question
     * 
     * @param array Valeurs de l'organisme à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {

        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_question']) && !empty($values['ref_question']))
            {
                $refQuestion = $values['ref_question'];
                unset($values['ref_question']);

                $request = $this->createQueryString("update", $values, "question", "WHERE id_question = ".$refQuestion);

                $this->resultset['response'] = $this->executeRequest("update", $request, "question", "Question");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la question.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    

    
    
    /**
     * delete - Efface une question avec toutes ses dépendances
     * 
     * @param int Identifiant de la question
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($idQuestion) 
    {
        $this->initialize();
        
        if (!empty($idQuestion))
        {
            $request = "DELETE FROM question WHERE id_question = ".$idQuestion;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "question");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la question.");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    public function shiftOrder($numOrdre, $offset, $imageName = null, $audioName = null, $videoName = null) 
    {
        
        $this->resultset['response']['question'] = array();
        
        $offsetOrdre = $numOrdre + $offset;
        /*
        $mediasQueryVars = "";

        if (!empty($imageName)) 
        {
            $mediasQueryVars .= ", image_question = '".$imageName."'";
        }
        if (!empty($audioName)) 
        {
            $mediasQueryVars .= ", audio_question = '".$audioName."'";
        }
        if (!empty($videoName)) 
        {
            $mediasQueryVars .= ", video_question = '".$videoName."'";
        }
        */
        try
        {
            // Connection à la base de données
            $this->connectDB();

            $request = "UPDATE question SET num_ordre_question = " . $offsetOrdre .", image_question = '".$imageName."', audio_question = '".$audioName."', video_question = '".$videoName."' WHERE num_ordre_question = ".$numOrdre;

            // Création de l'appel à la requête préparée
            $this->prepareStatement($request);

            // Execution de la requête préparée
            $this->executeStatement();

            // On récupère le nombre de lignes affectées par la mise  à jour
            $this->resultset['response']['question']['row_count'] = $this->getRowCount();

            // Fermeture de la requête préparée et fermeture de la connection
            $this->closeStatement();
            $this->disconnectDB();
        } 
        catch (PDOException $e)
        {
            // Erreur de connection ou probleme avec la création de la requête préparée
            $this->resultset['response']['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
        }
        
        return $this->resultset;
        
    }
    
}

?>
