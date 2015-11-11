<?php



class Categorie
{
	
	public $code_cat = null;
	public $nom_cat = null;
	public $descript_cat = null;
	//public $type_lien_cat = null;
	public $temps = null;
	public $total_reponses = null;
	public $total_reponses_correctes = null;
	public $score_percent = null;
	public $has_results = false;

	
	public function getCode()
	{
		return $this->code_cat;
	}
  

	public function getNom()
	{
		return $this->nom_cat;
	}


	public function getDescription()
	{
		return $this->descript_cat;
	}
	
	/*
	public function getTypeLien()
	{
		return $this->type_lien_cat;
	}
	*/

	public function getParent()
	{
		$parentCode = 0;

		if ($this->code_cat !== null)
		{
			$parentLength = (strlen($this->code_cat) - 2 > 0) ? strlen($this->code_cat) - 2 : 0;
			$parentCode = substr($this->code_cat, 0, $parentLength);
		}

		return $parentCode;
	}


	public function getTemps()
	{
		return $this->temps;
	}

	public function setTemps($time)
	{
		$this->temps = $time;
	}


	public function getTotalReponses()
	{
		return $this->total_reponses;
	}

	public function setTotalReponses($total)
	{
		$this->total_reponses = $total;
	}


	public function getTotalReponsesCorrectes()
	{
		return $this->total_reponses_correctes;
	}

	public function setTotalReponsesCorrectes($total)
	{
		$this->total_reponses_correctes = $total;
	}


	public function getScorePercent()
	{
		return $this->score_percent;
	}

	public function setScorePercent($percent)
	{
		$this->score_percent = $percent;
	}


	public function getHasResult()
	{
		return $this->has_results;
	}

	public function setHasResult($result)
	{
		$this->has_results = $result;
	}

}

?>
