<?php


/**
 * Description of ReponseDAO
 *
 * @author Nicolas Beurion
 */

// Inclusion du fichier de la classe Reponse
require_once(ROOT.'models/reponse.php');


class ReponseDAO extends ModelDAO
{
    
    
    public function __construct()
    {
        $this->initialize();
    }
    
    
    
    
    /**
     * selectByQuestion - Récupère les réponses correspondant à l'identifiant de la question.
     * 
     * @param int Identifiant de la question.
     * @return array Réponses correspondant à l'identifiant sinon erreurs.
     */
    public function selectByQuestion($refQuestion) 
    {
        $this->initialize();
        
        if (!empty($refQuestion))
        {
            $request = "SELECT * FROM reponse WHERE ref_question = ".$refQuestion." ORDER BY num_ordre_reponse ASC";

            $this->resultset['response'] = $this->executeRequest("select", $request, "reponse", "Reponse");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
     /**
     * selectByQuestion - Récupère la réponse correspondant à l'identifiant si elle existe.
     * 
     * @param int Identifiant de la réponse.
     * @return array Réponse correspondant à l'identifiant sinon erreurs.
     */
    public function selectByReponse($refReponse) 
    {
        $this->initialize();
        
        if (!empty($refQuestion))
        {
            $request = "SELECT * FROM reponse WHERE id_reponse = ".$refReponse;

            $this->resultset['response'] = $this->executeRequest("select", $request, "reponse", "Reponse");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * insert - Insère une réponse.
     * 
     * @param array Valeurs de la réponse à inserer.
     * @return array Dernier identifiant d'insertion sinon erreurs.
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_reponse']) && !empty($values['ref_reponse']))
            {
                unset($values['ref_reponse']);
            }
                
            $request = $this->createQueryString("insert", $values, "reponse");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "reponse", "Reponse");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    /**
     * update - Met à jour une reponse
     * 
     * @param array Valeurs de la réponse à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_question']) && !empty($values['ref_question']) && isset($values['ref_reponse']) && !empty($values['ref_reponse']))
            {
                $refReponse = $values['ref_reponse'];
                unset($values['ref_reponse']);
                
                $refQuestion = $values['ref_question'];
                unset($values['ref_question']);
                
                $request = $this->createQueryString("update", $values, "reponse", "WHERE ref_question = ".$refQuestion." AND id_reponse=".$refReponse);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "reponse", "Reponse");
            }
            else
            {
                $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Il n'y a aucun identifiant pour la réponse à mettre à jour.");
            }
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "update", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
    
    
    
    /**
     * delete - Efface une réponse
     * 
     * @param int Identifiant de la réponse
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($refReponse) 
    {
        $this->initialize();
        
        if (!empty($refReponse))
        {
            $request = "DELETE FROM reponse WHERE id_reponse = ".$refReponse;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "reponse", "Reponse");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la question.");
        }

        return $this->resultset;
    }
    
    
    
    
    /**
     * delete - Efface toutes les réponses correspondant à unequestion.
     * 
     * @param int Identifiant de la question.
     * @return array Nbre de lignes effacées sinon erreurs.
     */
    public function deleteByQuestion($refQuestion) 
    {
        $this->initialize();
        
        if (!empty($refReponse))
        {
            $request = "DELETE FROM reponse WHERE ref_question = ".$refQuestion;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "reponse", "Reponse");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "delete", 'message' => "Il n'y a aucun identifiant pour la suppression de la question.");
        }

        return $this->resultset;
    }
}

?>
