<?php



class NiveauEtudes
{
    
    public $id_niveau = NULL;
    public $nom_niveau = NULL;
    public $descript_niveau = NULL;
 

    
    public function getId()
    {
        return $this->id_niveau;
    }
  
    public function getNom()
    {
        return $this->nom_niveau;
    }

    public function getDescription()
    {
        return $this->descript_niveau;
    }
    
}

?>
