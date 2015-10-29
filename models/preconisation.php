<?php



class Preconisation
{
    
    public $id_preco = NULL;
    public $ref_parcours = NULL;
    public $ref_intervalle = NULL;
    public $nom_preco = NULL;
    public $descript_preco = NULL;
    public $num_ordre = NULL;


    
    public function getId()
    {
        return $this->id_preco;
    }
    
    public function getRefParcours()
    {
        return $this->ref_parcours;
    }

    public function getRefIntervalle()
    {
        return $this->ref_intervalle;
    }
    
    public function getNom()
    {
        return $this->nom_preco;
    }
    
    public function getDescription()
    {
        return $this->descript_preco;
    }
    
    public function getNumOrdre()
    {
        return $this->num_ordre;
    }

    
}

?>
