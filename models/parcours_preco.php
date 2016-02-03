<?php



class ParcoursPreco
{
	
	public $id_parcours = null;
	public $volume_parcours = null;
	public $nom_parcours = null;
	public $descript_parcours = null;
	
	
	public function getId()
	{
		return $this->id_parcours;
	}
  	
  	public function getVolume()
	{
		return $this->volume_parcours;
	}

	public function getNom()
	{
		return $this->nom_parcours;
	}

	public function getDescription()
	{
		return $this->descript_parcours;
	}
	
}

?>
