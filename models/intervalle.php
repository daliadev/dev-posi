<?php



class Intervalle
{
    
    public $id_intervalle = null;
    public $taux_min = null;
    public $taux_max = null;
    
    
    public function getId()
    {
        return $this->id_intervalle;
    }
  
    public function getTauxMin()
    {
        return $this->taux_min;
    }

    public function getTauxMax()
    {
        return $this->taux_max;
    }
    
}

?>
