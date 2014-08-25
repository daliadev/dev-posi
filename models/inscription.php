<?php




class Inscription
{
    
    public $id_inscription = NULL;
    public $ref_user = NULL;
    public $ref_intervenant = null;
    public $date_inscription = NULL;
    
    
    public function getId()
    {
        return $this->id_inscription;
    }
    
    public function getRefUtilisateur()
    {
        return $this->ref_user;
    }
    
    public function getRefIntervenant()
    {
        return $this->ref_intervenant;
    }
    
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    
    
}

?>
