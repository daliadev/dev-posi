<?php


class Activite
{
    
    public $id_activite = null;
    public $nom_activite = null;
    public $theme_activite = null;
    public $descript_activite = null;
    
    
    public function getId()
    {
        return $this->id_activite;
    }
  
    public function getNom()
    {
        return $this->nom_activite;
    }
    
    public function getTheme()
    {
        return $this->theme_activite;
    }

    public function getDescription()
    {
        return $this->descript_activite;
    }

}

?>
