

<?php

require_once(ROOT.'models/localisation/region.php');
require_once(ROOT.'models/localisation/departement.php');


class Region
{
	
	private $num_region = null;
	private $nom_territoire = null;
	private $descript_territoire = null;
	private $regions = array();


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


	public function getRegionByCode($region_code)
	{

	}

	public function getRegionByName($region_name)
	{

	}

	public function getDepartmntByCode($departmnt_code)
	{

	}

	public function getDepartmntByName($departmnt_name)
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
