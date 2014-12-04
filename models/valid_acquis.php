<?php



class ValidationAcquis
{
    
    public $id_acquis = NULL;
    public $nom_acquis = NULL;
    public $descript_acquis = NULL;
 

    
    public function getId()
    {
        return $this->id_acquis;
    }
  
    public function getNom()
    {
        return $this->nom_acquis;
    }

    public function getDescription()
    {
        return $this->descript_acquis;
    }
    


}

?>
