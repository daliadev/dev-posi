<?php

class Region
{

	//private $regions2015 = array();
	//private $regions2016 = array();
	private $contentText = '';
	private $regions = array();
	private $separator = ';';


	public function __construct($regionFile)
	{
		if (file_exists($regionFile)) 
		{
			$handle = fopen($regionFile, 'rb');

			if ($handle)
			{
				while (($text = fgets($handle)) !== false)
				{
					$this->contentText .= $text.$this->separator;
				}
			}

			fclose($handle);

			//$this->regionFileToArray();

			//var_dump($this->regions);
		}
		else
		{
			// error
		}
	}


	/*
	public function getRegion($numDepartement)
	{

	}

	public function getDepartements($numDepartement)
	{

	}

	public function getDepartementName($numDepartement)
	{

	}

	public static function setNewRegion($fileName, $numRegion, $nomRegion, $departements)
	{

	}

	public static function setNewDepartement($fileName, $numRegion, $nomRegion, $departements)
	{

	}
	*/
	/*
	private function regionFileToObject()
	{
		if (!empty($this->contentText))
		{
			$regionFileArray = explode($this->separator, $this->contentText);

			for ($i = 0; $i < count($regionFileArray); $i++) { 
				
				//var_dump(trim($regionFileArray[$i]));

				if (!empty(trim($regionFileArray[$i])))
				{
					$this->regions[$i] = array()
					$regionsRawText = trim($regionFileArray[$i]);

					if ($regionsRawText)
					{
						
					}
				}
				

			}
		}
	}
	*/
}

?>