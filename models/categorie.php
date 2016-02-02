<?php



class Categorie
{
	
	public $code_cat = null;
	public $nom_cat = null;
	public $descript_cat = null;
	private $level = null;
	private $temps = null;
	private $total_reponses = 0;
	private $total_reponses_correctes = 0;
	private $score_percent = 0;
	private $has_results = false;
	private $children_cat = array();
	private $parent_cat = null;
	private $preconisations = array();

	
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
	

	public function getParentCode()
	{
		$parentCode = 0;

		if ($this->code_cat !== null)
		{
			$parentLength = (strlen($this->code_cat) - 2 > 0) ? strlen($this->code_cat) - 2 : 0;
			$parentCode = substr($this->code_cat, 0, $parentLength);
		}

		return $parentCode;
	}



	public function getLevel()
	{
		return $this->level;
	}

	public function setLevel($level)
	{
		$this->level = $level;
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



	public function getParent()
	{
		return $this->parent_cat;
	}

	public function setParent($parent)
	{
		$this->parent_cat = $parent;
	}



	public function getChildren()
	{
		return $this->children_cat;
	}

	public function getChild($childCatcode)
	{
		if ($childCatcode !== null && !empty($childCatcode) && is_numeric($childCatcode) && strlen($childCatcode) % 2 == 0)
		{
			for ($i = 0; $i < count($this->children_cat); $i++) 
			{ 
				if ($this->children_cat[$i]->getCode() == $childCatcode)
				{
					return $this->children_cat;
				}
			}
		}

		return null;
	}

	/*
	public function addChild($childCat)
	{
		if ($childCat !== null && $childCat instanceof Categorie)
		{
			array_push($this->children_cat, $childCat);
		}
	}

	public function removeChild($childCatcode)
	{
		if ($childCatcode !== null && !empty($childCatcode) && is_numeric($childCatcode) && strlen($childCatcode) % 2 == 0)
		{
			for ($i = 0; $i < count($this->children_cat); $i++) 
			{ 
				if ($this->children_cat[$i]->getCode() == $childCatcode)
				{
					array_splice($this->children_cat, $i, 1);
					return true;
				}
			}
		}
		return false;
	}
	*/


	public function getPreconisations()
	{
		return $this->preconisations;
	}

	public function setPreconisations($precos)
	{
		$this->preconisations = $precos;
	}

}

?>
