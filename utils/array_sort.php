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

	/*
	static public function recursiveCategories($parent, $level, $datas)
	{
		$list = '';
		$previous_level = 0;

		if ($level == 0) 
		{
			$list .= '<ul>';
		}

		foreach ($datas as $node) 
		{
			if ($parent == $node->getParent()) 
			{
				if ($previous_level < $level) 
				{
					$list .= '<ul>';
				}

				$list .= '<li>'.$node->getNom().'</li>';
				$previous_level = $level;

				$list .= self::recursiveCategories($node->getCode(), ($level + 1), $datas);
			}
		}

		if ($previous_level == $level && $previous_level != 0) 
		{
			$list .= '</ul>';
		}

		return $list;
	}
	*/


	
	static public function recursiveArray($parentRef, $depth, $datasObjects, $refField, $refCharIncrement = 1)
    {

    	$array = null;

		if ($depth == 0) 
		{
			$array = array();
		}
		

		foreach ($datasObjects as $key => $object) 
		{
			$objectParentRef = null;
			$objectParentRef = substr($object->$refField, 0, (strlen($object->$refField) - $refCharIncrement));

			if ($parentRef == $objectParentRef)
			{
				$iterativeResult = null;

				$array[] = $object;
				
				$iterativeResult = self::recursiveArray($object->$refField, ($depth + 1), $datasObjects, $refField, $refCharIncrement);


				if ($iterativeResult !== null) 
				{
					$array[] = $iterativeResult;
				}
			}
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