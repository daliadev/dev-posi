<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */


class Inscription
{
    
    public $id_inscription = NULL;
    public $ref_user = NULL;
    public $ref_intervenant = null;
    public $date_inscription = NULL;
    
    /*
    public function __construct($idInscript, $refUser, $refInterv, $dateInscript) 
    {
        $this->id = $idInscript;
        $this->refUtilisateur = $refUser;
        $this->refIntervenant = $refInterv;
        $this->dateInscription = $dateInscript;
    }
    */
    
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
