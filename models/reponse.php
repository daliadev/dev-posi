<?php


/**
 * Ensemble des attributs d'une réponse
 *
 * @author Nicolas Beurion
 */

class Reponse
{
    
    public $id_reponse = null;
    public $ref_question = null;
    public $num_ordre_reponse = null;
    public $intitule_reponse = null;
    public $est_correct = null;
    
    /*
    public function __construct($idReponse, $refQuestion, $numOrdreReponse, $intituleReponse, $reponseCorrect) 
    {
        $this->id = $idReponse;
        $this->refQuestion = $refQuestion;
        $this->numOrdre = $numOrdreReponse;
        $this->intitule = $intituleReponse;
        $this->estCorrect = $reponseCorrect;
    }
    */
    
    public function getId()
    {
        return $this->id_reponse;
    }
  
    public function getRefQuestion()
    {
        return $this->ref_question;
    }
    
    public function getNumeroOrdre()
    {
        return $this->num_ordre_reponse;
    }

    public function getIntitule()
    {
        return $this->intitule_reponse;
    }
    
    public function getEstCorrect()
    {
        return $this->est_correct;
    }
    
}

?>
