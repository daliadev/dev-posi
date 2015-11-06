<?php



// Inclusion du fichier de la classe Reponse
require_once(ROOT.'models/resultat.php');


class ResultatDAO extends ModelDAO
{
    
    
    public function __construct()
    {
        $this->initialize();
    }
    
    

    /**
     * selectBySession - Récupère tous les résultats correspondant à l'identifiant d'une session donnée
     * 
     * @param int Identifiant de la session
     * @return array Resultat correspondant à l'identifiant sinon erreurs
     */
    public function selectBySession($refSession) 
    {
        $this->initialize();
        
        if (!empty($refSession))
        {
            $request = "SELECT id_result, ref_session, resultat.ref_question, ref_reponse_qcm, ref_reponse_qcm_correcte, reponse_champ, validation_reponse_champ, temps_reponse, ref_cat "
                    . "FROM resultat, question_cat WHERE resultat.ref_session = ".$refSession." AND resultat.ref_question = question_cat.ref_question";

            $this->resultset['response'] = $this->executeRequest("select", $request, "resultat", "Resultat");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun identifiant de session.");
        }
        
        return $this->resultset;
    }
    
    
    public function selectBySessionAndCategories($refSession, $refCategorie = null) 
    {
        $this->initialize();
        
        if (!empty($refSession))
        {
            $request = "SELECT * FROM resultat WHERE ref_session = ".$refSession;

            $request = "SELECT id_result, ref_session, resultat.ref_question, question_cat.ref_cat, ref_reponse_qcm, ref_reponse_qcm_correcte, reponse_champ, validation_reponse_champ, temps_reponse ";
            $request .= "FROM resultat, question_cat ";
            $request .= "WHERE resultat.ref_session = ".$refSession." ";
            $request .= "AND question_cat.ref_question = resultat.ref_question ";
            if ($refCategorie)
            {
                $request .= "AND question_cat.ref_cat = ".$refCategorie." ";
            }
            //$request .= "AND categorie.code_cat = question_cat.ref_cat ";
            $request .= "ORDER BY id_result ASC";

            $this->resultset['response'] = $this->executeRequest("select", $request, "resultat", "Resultat");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "select", 'message' => "Il n'y a aucun identifiant de session.");
        }
        
        return $this->resultset;
    }


    
    
    /**
     * insert - Insère un résultat.
     * 
     * @param array Valeurs du résultat à inserer.
     * @return array Dernier identifiant d'insertion sinon erreurs.
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            if (isset($values['ref_resultat']) && !empty($values['ref_resultat']))
            {
                unset($values['ref_resultat']);
            }
                
            $request = $this->createQueryString("insert", $values, "resultat");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "resultat", "Resultat");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "insert", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    /**
     * update - Met à jour un résultat
     * 
     * @param array Valeurs de la résultat à mettre à jour
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
                
                $request = $this->createQueryString("update", $values, "resultat", "WHERE ref_question = ".$refQuestion." AND id_reponse=".$refReponse);
                
                $this->resultset['response'] = $this->executeRequest("update", $request, "resultat", "Resultat");
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
    
    
    
}

?>
