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

			$this->regionFileToArray();
		}
		else
		{
			// error
		}
	}


	public function getList()
	{
		return $this->regions;
	}

	public function getByNumDep($numDepartement)
	{

	}

	public function getDepartementsByRefRegion($refRegion)
	{

	}

	/*
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
	
	private function regionFileToArray()
	{
		if (!empty($this->contentText))
		{
			$regionFileArray = explode($this->separator, $this->contentText);

			$k = -1;

			for ($i = 0; $i < count($regionFileArray); $i++) { 

				if (!empty(trim($regionFileArray[$i])))
				{
					//$this->regions[$i] = array();
					$regionsRawText = trim($regionFileArray[$i]);

					if ($regionsRawText)
					{
						// Detection de la référence et de l'intitulé de la région
						if (strpos($regionsRawText, '[') !== false && strpos($regionsRawText, ']') !== false && strpos($regionsRawText, '=') !== false) 
						{
							$k++;

							// on enléve les crochets
							$posBracket1 = strpos($regionsRawText, '[');
							$posBracket2 = strpos($regionsRawText, ']');
							$posEqualSign = strpos($regionsRawText, '=');
							$regionsRawText = substr($regionsRawText, $posBracket1 + 1, $posBracket2 - 1);

							$refRegion = substr($regionsRawText, 0, $posEqualSign - 1);
							$nameRegion = substr($regionsRawText, $posEqualSign);

							$this->regions[$k] = array(
								'ref' => $refRegion,
								'nom' => $nameRegion,
								'departements' => array()
							);
						}

						// sinon du département affilié à la région
						else if (strpos($regionsRawText, '=') !== false) 
						{	
							$posEqualSign = strpos($regionsRawText, '=');
							$numero = substr($regionsRawText, 0, $posEqualSign);
							$nomDptmt = substr($regionsRawText, $posEqualSign + 1);

							$this->regions[$k]['departements'][$numero] = $nomDptmt;
						}

						// erreur pas de région ou ligne vide
						else
						{
							
						}
						
					}
				}

			}
			//var_dump($regions);
		}
	}
	
}

?>