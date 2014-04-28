<?php


/**
 * Description
 *
 * @author Nicolas Beurion
 */

class Session
{
    
    public $id_session = null;
    public $ref_user = null;
    public $ref_intervenant = null;
    public $date_session = null;
    public $session_accomplie = null;
    public $temps_total = null;
    public $validation = null;

    
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
    
}

?>