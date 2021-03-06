<?php



// Inclusion du fichier de la classe Organisme
require_once(ROOT.'models/session.php');



class SessionDAO extends ModelDAO
{

    
    
    public function __construct()
    {
         $this->initialize();
    }

    
    
    
    
    /**
     * selectById - Récupère la session correspondant à l'identifiant.
     * 
     * @param int Identifiant session
     * @return array Session correspondant à l'identifiant sinon erreurs
     */
    public function selectById($idSession) 
    {
        $this->initialize();
        
        if (!empty($idSession))
        {
            $request = "SELECT * FROM session WHERE id_session = ".$idSession;

            $this->resultset['response'] = $this->executeRequest("select", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * selectByDate - Récupère la session correspondant à une date précise.
     * 
     * @param date Date désirée au format 'us'.
     * @return array Session correspondant à la date sinon erreurs.
     */
    public function selectByDate($date) 
    {
        $this->initialize();
        
        if(!empty($date))
        {
            $request = "SELECT * FROM session WHERE date_session = '".$date."'";

            $this->resultset['response'] = $this->executeRequest("select", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    


    
    /**
     * selectByDatesUserOrgan - Récupère la référence et la date des sessions correspondantes à un utilisateur par rapport à un organisme donné. Ne prend pas en compte les sessions non terminées.
     * 
     * @param int Référence de l'utilisateur.
     * @return array Sessions correspondantes à l'utilisateur.
     */
    public function selectByDatesUserOrgan($startDate, $endDate, $refUser, $refOrgan) 
    {

        $this->initialize();

        $request = "SELECT id_session, ref_user, ref_intervenant, intervenant.ref_organ, ref_valid_acquis, date_session, session_accomplie, temps_total, validation, score_pourcent ";
        $request .= "FROM session, intervenant ";
        $request .= "WHERE session_accomplie = 1 ";

        if ($startDate)
        {
            $request .= "AND session.date_session >= '".$startDate."' ";
        }
        if ($endDate)
        {
            $request .= "AND session.date_session <= '".$endDate."' ";
        }
        if ($refUser)
        {
            $request .= "AND session.ref_user = ".$refUser." ";
        }
        
        $request .= "AND session.ref_intervenant = intervenant.id_intervenant ";

        if ($refOrgan)
        {
            $request .= "AND intervenant.ref_organ = ".$refOrgan." ";
        }

        $request .= "ORDER BY session.date_session ASC";


        $this->resultset['response'] = $this->executeRequest("select", $request, "session", "Session");

        

        return $this->resultset;
    }
    
    


    
    
    
    /**
     * selectByUser - Récupère la référence et la date des sessions correspondantes à un utilisateur par rapport à un organisme donné. Ne prend pas en compte les sessions non terminées.
     * 
     * @param int Référence de l'utilisateur.
     * @return array Sessions correspondantes à l'utilisateur.
     */
    public function selectByUser($refUser, $refOrganisme) 
    {
        $this->initialize();
        
        if(!empty($refUser) && !empty($refOrganisme))
        {
            $request = "SELECT id_session, ref_user, ref_intervenant, ref_valid_acquis, date_session, session_accomplie, temps_total, validation, score_pourcent FROM session, intervenant ";
            $request .= "WHERE session.ref_user = ".$refUser." ";
            $request .= "AND session.ref_intervenant = intervenant.id_intervenant ";
            $request .= "AND intervenant.ref_organ = ".$refOrganisme." ";
            $request .= "AND session_accomplie = 1 ";
            $request .= "GROUP BY id_session ORDER BY date_session DESC";
            
            $this->resultset['response'] = $this->executeRequest("select", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }


    
    
   
    
   /**
     * insert - Insère une session
     * 
     * @param array Valeurs de la session à inserer
     * @return array Dernier identifiant d'insertion sinon erreurs
     */
    public function insert($values) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("insert", $values, "session");
            
            $this->resultset['response'] = $this->executeRequest("insert", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }
        
        return $this->resultset;
    }
    
    
    
    
    
    /**
     * update - Met à jour une session
     * 
     * @param array Valeurs de la session à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function update($values, $idSession) 
    {
        $this->initialize();
        
        if (!empty($values))
        {
            $request = $this->createQueryString("update", $values, "session", "WHERE id_session = ".$idSession);

            $this->resultset['response'] = $this->executeRequest("update", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }


    /**
     * update - Met à jour la référence à la table "validation des acquis" d'une session
     * 
     * @param number Référence de la table "validation des acquis" à mettre à jour
     * @param number Référence de la session à mettre à jour
     * @return array Nbre de lignes mises à jour sinon erreurs
     */
    public function updateValidAcquis($refValidAcquis, $idSession) 
    {
        $this->initialize();
        
        if (!empty($refValidAcquis) && !empty($idSession))
        {

            if ($refValidAcquis == "NULL")
            {
                $request = "UPDATE session SET ref_valid_acquis = NULL WHERE id_session = ".$idSession;
            }
            else
            {
                $request = "UPDATE session SET ref_valid_acquis = ".$refValidAcquis." WHERE id_session = ".$idSession;
            }

            $this->resultset['response'] = $this->executeRequest("update", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }

    
    
    
    
    /**
     * delete - Efface un organisme
     * 
     * @param int Identifiant de l'organisme
     * @return array Nbre de lignes effacées sinon erreurs
     */
    public function delete($idSession) 
    {
        $this->initialize();
        
        if (!empty($idSession))
        {
            $request = "DELETE FROM session WHERE id_session = ".$idSession;

            $this->resultset['response'] = $this->executeRequest("delete", $request, "session", "Session");
        }
        else
        {
            $this->resultset['response']['errors'][] = array('type' => "form_request", 'message' => "Les données sont vides");
        }

        return $this->resultset;
    }
    
    
}

?>
