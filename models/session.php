<?php



class Session
{
    
    public $id_session = null;
    public $ref_user = null;
    public $ref_intervenant = null;
    public $ref_organ = null;
    public $ref_valid_acquis = null;
    public $date_session = null;
    public $session_accomplie = null;
    public $temps_total = null;
    public $validation = null;
    public $score_pourcent = null;

    
    public function getId()
    {
        return $this->id_session;
    }
  
    public function getRefUser()
    {
        return $this->ref_user;
    }
    
    public function getRefIntervenant()
    {
        return $this->ref_intervenant;
    }

    public function getRefOrgan()
    {
        return $this->ref_organ;
    }

    public function getRefValidAcquis()
    {
        return $this->ref_valid_acquis;
    }

    public function getDate()
    {
        return $this->date_session;
    }
    
    public function getSessionAccomplie()
    {
        return $this->session_accomplie;
    }
    
    public function getTempsTotal()
    {
        return $this->temps_total;
    }
    
    public function getValidation()
    {
        return $this->validation;
    }

    public function getScorePourcent()
    {
        return $this->score_pourcent;
    }
    
}

?>
