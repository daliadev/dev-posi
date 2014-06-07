<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */

class Resultat
{
    
    public $id_result = null;
    public $ref_session = null;
    public $ref_question = null;
    public $ref_cat = null;
    public $ref_reponse_qcm = null;
    public $ref_reponse_qcm_correcte = null;
    public $reponse_champ = null;
    public $validation_reponse_champ = null;
    public $temps_reponse = null;
    
    
    
    public function getId()
    {
        return $this->id_result;
    }
  
    public function getRefSession()
    {
        return $this->ref_session;
    }
    
    public function getRefQuestion()
    {
        return $this->ref_question;
    }

    public function getRefCat()
    {
        return $this->ref_cat;
    }

    public function getRefReponseQcm()
    {
        return $this->ref_reponse_qcm;
    }

    public function getRefReponseQcmCorrecte()
    {
        return $this->ref_reponse_qcm_correcte;
    }

    public function getReponseChamp()
    {
        return $this->reponse_champ;
    }
    
    public function getValidationReponseChamp()
    {
        return $this->validation_reponse_champ;
    }
    
    public function getTempsReponse()
    {
        return $this->temps_reponse;
    }
    
}

?>
