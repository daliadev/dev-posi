<?php

/**
 * Description of ArraySort
 *
 * @author Nicolas Beurion
 */

class ArraySort {


	/**
	 * Créer une liste hierarchique à partir d'un tableau pré-formaté
	 * 
	 * @param string $parent Le parent de l'id de l'élément en cours de traitement. Au départ, il s'agit souvent de 0.
	 * @param int $level Le biveau hiérachique dans lequel on se trouve.
	 * @param array $datas Les données à triée. Le tableau de ces données doit être préformaté avec, pour chaque index: un 'id', un 'parent', un 'nom', et éventuellement une 'description'.
	 * 
	 * @return string Une chaîne au format html avec des balises <ul> et <li> avec les différents niveaux hièrarchiques.
	 */

	static public function recursiveList($parent, $level, $datas)
	{
		$list = '';
		$previous_level = 0;

		if ($level == 0) 
		{
			$list .= '<ul>';
		}

		foreach ($datas as $node) 
		{
			if (isset($node['parent']) && $parent == $node['parent']) 
			{
				if ($previous_level < $level) 
				{
					$list .= '<ul>';
				}

				$list .= '<li>'.$node['nom'].'</li>';
				$previous_level = $level;

				$list .= self::recursiveList($node['id'], ($level + 1), $datas);
			}
		}

		if ($previous_level == $level && $previous_level != 0) 
		{
			$list .= '</ul>';
		}

		return $list;
	}



	static public function recursiveArray($index, $depth, $datas, $currentArray = array())
    {
    	//var_dump($depth, 'yo', $index, 'end');
    	$array = null;
    	$tempArray = array();
		$previousDepth = 0;
		//$count = 0;

		if ($depth == 0) 
		{
			$array = array();
		}
		else
		{
			$array = $currentArray;
		}

		$i = 0;

		foreach ($datas as $node) 
		{
			//var_dump($count);
			if ($depth - 1 == count($array[$index]))
			{
				$index++;
				//var_dump(count($currentArray));

				for ($i = 0; $i < count($depth); $i++) 
				{ 	
					//$tempArray = 
				}
				
				$array[] = $tempArray;
				//array_push($tempArray, $node);
				$previousDepth = $depth;

				$array[$depth] = self::recursiveArray($index, ($depth + 1), $datas, $tempArray);
			}

			$i++;
		}

		if ($previousDepth == $depth && $previousDepth != 0) 
		{
			//$index++;
			//$array[] = $tempArray;
		}

		return $array;
    }




	/*
	function afficher($parent, $depth, $datas) {

		$text = '';

		foreach ($datas as $node) {
			
			$parentNode = substr($node['id'], 0, -2);

			//var_dump($parent, $parentNode);
			
			if ($parent == $parentNode) {

				for ($i = 0; $i < $depth; $i++) {

					$text .= '-';
				}

				$text .= ' '.$node['nom'].'<br/>';
				$text .= afficher($node['id'], ($depth + 1), $datas);
			}
			
		}

		return $text;
	}
	*/
}