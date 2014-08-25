<?php



class Degre
{
    
    public $id_degre = null;
    public $nom_degre = null;
    public $descript_degre = null;

    
    
    public function getId()
    {
        return $this->id_degre;
    }
  
    public function getNom()
    {
        return $this->nom_degre;
    }

    public function getDescription()
    {
        return $this->descript_degre;
    }
    
}

?>
