<?php

class Region
{

	//private $regions2015 = array();
	//private $regions2016 = array();

    public function __construct($regionFile)
    {
    	$content = '';

    	if (file_exists($regionFile)) 
    	{
    		$handle = fopen($regionFile, 'rb');

    		if ($handle)
    		{
    			while (($content = fgets($handle)) !== false)
    			{
    				echo $content;
    			}
    		}

    		fclose($handle);
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
}

?>