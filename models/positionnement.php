<?php



class Positionnement
{
    
    public $id_posi = null;
    public $nom_posi = null;
    public $descript_posi = null;

    
    
    public function getId()
    {
        return $this->id_posi;
    }
  
    public function getNom()
    {
        return $this->nom_posi;
    }

    public function getDescription()
    {
        return $this->descript_posi;
    }
    
}

?>
