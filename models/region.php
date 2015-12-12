<?php

require_once(ROOT.'models/departement.php');


class Region
{
	
	private $num_region = null;
	private $nom_region = null;
	private $descript_region = null;
	private $departements = null;

	
	
	public function getNum()
	{
		return $this->num_region;
	}

	public function setNum($numRegion)
	{
		$this->num_region = $numRegion;
	}


	public function getNom()
	{
		return $this->nom_region;
	}

	public function setNom($nomRegion)
	{
		$this->nom_region = $nomRegion;
	}


	public function getDescription()
	{
		return $this->descript_region;
	}

	public function setDescription($descriptRegion)
	{
		$this->descript_region = $descriptRegion;
	}


	public function getByDepartementNum($departementNum)
	{

	}

	public function getByDepartementName($departementName)
	{

	}

	/*
	public function getDepartementByNum()
	{

	}

	public function getDepartementByName()
	{
		
	}

	public function getDepartementsNums()
	{
		
	}

	public function getDepartementsNames()
	{
		
	}
	*/

	public function setDepartement()
	{

	}


	
}

?>
