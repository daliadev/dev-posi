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


	/**
	 *
	 *	Algorythme Arbre binaire (en pseudo C ou C) issu de 'Exercices et problèmes d'algorythmique:
	 * 
	 * 	// Définition structure d'un noeud
	 * 	typedef struct arbre
	 * 	{
	 * 		T info;
	 * 		struct arbre *sag; // Pointeur sous-arbre gauche
	 * 		struct arbre *sad; // Pointeur sous-arbre droite
	 * 	} arbre;
	 * 	typedef arbre *ptr_arbre; // Définition pointeur arbre départ
	 * 
	 *	- Création
	 *	- Parcourir (en profondeur) :
	 * 		FONCTION preordre(a : ptr_arbre)
	 * 		DEBUT
	 * 			SI a <> NULL ALORS
	 * 				afficher(a->info)
	 * 				preordre(a->sag)
	 * 				preordre(a->sad)
	 * 			FINSI
	 * 		FIN
	 * 
	 * 		FONCTION ordre(a : ptr_arbre)
	 * 		DEBUT
	 * 			SI a <> NULL ALORS
	 * 				ordre(a->sag)
	 * 				afficher(a->info)
	 * 				ordre(a->sad)
	 * 			FINSI
	 * 		FIN
	 * 
	 * 		FONCTION postordre(a : ptr_arbre)
	 * 		DEBUT
	 * 			SI a <> NULL ALORS
	 * 				postordre(a->sag)
	 * 				postordre(a->sad)
	 * 				afficher(a->info)
	 * 				
	 * 			FINSI
	 * 		FIN
	 *
	 *	- Rechercher :
	 * 		FONCTION recherche(x : T, a : ptr_arbre) : booléen
	 * 		VAR ok : booléen
	 * 		DEBUT
	 * 			SI a = NULL ALORS
	 * 				ok<-faux
	 * 			SINON SI a->info = x ALORS
	 * 				ok<-vrai
	 * 			SINON SI a->info > x ALORS
	 * 				recherche(x, a->sag, ok)
	 * 			SINON
	 * 				recherche(x, a->sad, ok)
	 * 			FINSI
	 * 			RETOURNER ok
	 * 		FIN
	 * 	
	 * 	- Ajouter :
	 * 		FONCTION ajout(x : T, *aj_a : ptr_arbre) : vide
	 * 		DEBUT
	 * 			SI *aj_a = NULL ALORS
	 * 				reserver(*aj_a)
	 * 				*aj_a->info <- x
	 * 				*aj_a->sag <- NULL
	 * 				*aj_a->sad <- NULL
	 * 			SINON SI *aj_a->info <= x ALORS
	 * 				ajout(x, &(*aj_a->sad))
	 * 			SINON
	 * 				ajout(x, &(*aj_a->sag))
	 * 			FINSI
	 * 			RETOURNER ok
	 * 		FIN
	 * 
	 * 	- Compter :
	 * 		FONCTION compter(a : ptr_arbre) : entier
	 * 		VAR n : entier
	 * 		DEBUT
	 * 			SI a = NULL ALORS
	 * 				n <- 0
	 * 			SINON
	 * 				n <- 1 + compter(a->sag) + compter(a->sad)
	 * 			FINSI
	 * 			RETOURNER n
	 * 		FIN
	 * 
	 * 	- Hauteur :
	 * 		FONCTION hauteur(a : ptr_arbre) : entier
	 * 		VAR n : entier
	 * 		DEBUT
	 * 			SI a = NULL ALORS
	 * 				n <- 0
	 * 			SINON
	 * 				n <- 1 + maximum(compter(a->sag), compter(a->sad))
	 * 			FINSI
	 * 			RETOURNER n
	 * 		FIN
	 *	
	 */
	
	static public function recursiveArray($parentRef, $depth, $datasObjects, $refField)
    {
    	//var_dump($depth, 'yo', $index, 'end');
    	$array = null;
    	//$tempArray = array();
		$previousDepth = 0;
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

		$i = 0;

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