<?php



class TypePreco
{
    
    public $id_type = null;
    public $nom_type = null;
    public $descript_type = null;
    
    
    public function getId()
    {
        return $this->id_type;
    }
  
    public function getNom()
    {
        return $this->nom_type;
    }

    public function getDescription()
    {
        return $this->descript_type;
    }
    
}

?>
