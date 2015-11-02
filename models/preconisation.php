<?php



class Preconisation
{
	
	public $id_preco = NULL;
	public $ref_code_cat = NULL;
	public $ref_parcours = NULL;
	// public $ref_inter = NULL;
	public $nom_preco = NULL;
	public $descript_preco = NULL;
	public $taux_min = NULL;
	public $taux_max = NULL;
	public $num_ordre = NULL;

	
	public function getId()
	{
		return $this->id_preco;
	}
	
	public function getRefParcours()
	{
		return $this->ref_parcours;
	}

	// public function getRefInter()
	// {
	//     return $this->ref_inter;
	// }
	
	public function getNom()
	{
		return $this->nom_preco;
	}
	
	public function getDescription()
	{
		return $this->descript_preco;
	}

	public function getTauxMin()
	{
		return $this->taux_min;
	}

	public function getTauxMax()
	{
		return $this->taux_max;
	}
	
	public function getNumOrdre()
	{
		return $this->num_ordre;
	}

	
}

?>
