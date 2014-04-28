<?php


/**
 * Description of intervenant
 *
 * @author Nicolas Beurion
 */


class Intervenant
{
    
    public $id_intervenant = NULL;
    public $ref_organ = NULL;
    public $nom_intervenant = NULL;
    public $tel_intervenant = NULL;
    public $email_intervenant = NULL;
    
    /*
    public function __construct($idIntervenant, $refOrgan, $emailIntervenant, $nomIntervenant = "", $telIntervenant = "") 
    {
        $this->id = $idIntervenant;
        $this->refOrganisme = $refOrgan;
        $this->nom = $nomIntervenant;
        $this->telephone = $telIntervenant;
        $this->email = $emailIntervenant;
    }
    */
    
    public function getId()
    {
        return $this->id_intervenant;
    }
    
    public function getRefOrganisme()
    {
        return $this->ref_organ;
    }
    
    public function getNom()
    {
        return $this->nom_intervenant;
    }
    
    public function getTelephone()
    {
        return $this->telephone;
    }
    
    public function getEmail()
    {
        return $this->email_intervenant;
    }

    
}

?>
