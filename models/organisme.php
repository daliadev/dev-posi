<?php



class Organisme 
{
    
    public $id_organ = NULL;
    public $ref_code_organ = NULL;
    public $numero_interne = NULL;
    public $nom_organ = NULL;
    public $adresse_organ = NULL;
    public $code_postal_organ = NULL;
    public $ville_organ = NULL;
    public $tel_organ = NULL;
    public $fax_organ = NULL;
    public $email_organ = NULL;
    public $nbre_posi_total = NULL;
    public $nbre_posi_max = NULL;

  

    public function getId()
    {
        return $this->id_organ;
    }
    
    public function getRefCode()
    {
        return $this->ref_code_organ;
    }
    
    public function getNumeroInterne()
    {
        return $this->numero_interne;
    }
    
    public function getNom()
    {
        return $this->nom_organ;
    }
    
    public function getAdresse()
    {
        return $this->adresse_organ;
    }
    
    public function getCodePostal()
    {
        return $this->code_postal_organ;
    }
    
    public function getVille()
    {
        return $this->ville_organ;
    }
    
    public function getTelephone()
    {
        return $this->tel_organ;
    }
    
    public function getFax()
    {
        return $this->fax_organ;
    }
    
    public function getEmail()
    {
        return $this->email_organ;
    }

    public function getNbrePosiTotal()
    {
        return $this->nbre_posi_total;
    }

    public function getNbrePosiMax()
    {
        return $this->nbre_posi_max;
    }

    
}

?>
