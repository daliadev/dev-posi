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


	
	static public function recursiveArray($parentRef, $depth, $datasObjects, $refField)
    {
    	//var_dump($depth, 'yo', $index, 'end');
    	$array = null;
    	//$tempArray = array();
		$prevDepth = 0;
		//$count = 0;

		if ($depth == 0) 
		{
			$array = array();
		}
		/*
		else
		{
			$array = $currentArray;
		}
		*/

		

		/*
		foreach ($datasObjects as $object) 
		{
			if ($parentRef == $object[$refField])
			{
				//$index++;

				if ($previousDepth < $depth) 
				{
					$list .= '<ul>';
				}

				for ($i = 0; $i < count($depth); $i++) 
				{ 	
					//$tempArray = 
				}
				
				$array[] = $tempArray;
				//array_push($tempArray, $node);
				$previousDepth = $depth;

				$array[$depth] = self::recursiveArray($object[$refField], ($depth + 1), $datasObjects, $refField);
			}

			//$i++;
		}
		*/

		$i = 0;


		foreach ($datasObjects as $key => $object) 
		{
			//var_dump($key, $object);
			$objectParentRef = 0;

			if ($parentRef != 0)
			{
				$objectParentRef = substr($object->$refField, 0, strlen($parentRef));
			}
			/*
			else
			{
				$objectParentRef = $object->$refField;
			}
			*/
			
			//$parentCode = $object[$refField]
			var_dump($parentRef, $objectParentRef);

			if ($parentRef == $objectParentRef)
			{
				var_dump($object);
				$tempArray;

				if ($prevDepth < $depth) 
				{
					$array[$i] = array();
				}

				array_push($array[$i], $object);
				$prevDepth = $depth;
				$i++;

				$array[$i][] = self::recursiveArray($object->$refField, ($depth + 1), $datasObjects, $refField);
			}
		}
		/*
		if ($prevDepth == $depth && $prevDepth != 0) 
		{
			//$index++;
			//$array[] = $tempArray;
		}
		*/

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