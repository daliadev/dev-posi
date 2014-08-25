<?php



class Categorie
{
    
    public $code_cat = null;
    public $nom_cat = null;
    public $descript_cat = null;
    public $type_lien_cat = null;

    
    public function getCode()
    {
        return $this->code_cat;
    }
  
    public function getNom()
    {
        return $this->nom_cat;
    }

    public function getDescription()
    {
        return $this->descript_cat;
    }
    
    public function getTypeLien()
    {
        return $this->type_lien_cat;
    }
}

?>
