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

    public function getParent()
    {
        $parentCode = 0;
        //$parentLength = 0;

        if ($this->code_cat !== null)
        {
            $parentLength = (strlen($this->code_cat) - 2 > 0) ? strlen($this->code_cat) - 2 : 0;
            $parentCode = substr($this->code_cat, 0, $parentLength);
        }

        return $parentCode;
    }
}

?>
