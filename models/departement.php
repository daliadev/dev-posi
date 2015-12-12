<?php


class Departement
{
	
	private $num = null;
	private $nom = null;
	private $descript = null;
	

	public function getNum()
	{
		return $this->num;
	}

	public function setNum($num)
	{
		$this->num = $num;
	}


	public function getNom()
	{
		return $this->nom;
	}

	public function setNom($nom)
	{
		$this->nom = $nom;
	}


	public function getDescription()
	{
		return $this->descript;
	}

	public function setDescription($descript)
	{
		$this->descript = $descript;
	}
	
}

?>
