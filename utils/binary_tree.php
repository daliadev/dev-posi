<?php 
	/**
	 *
	 *	Algorythme Arbre binaire (en pseudo C ou C) issu de 'Exercices et problèmes d'algorythmque:
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


class Tree
{
	private $info = null;
	private $leftChild; // Pointeur sous-arbre gauche
	private $rightChild; // Pointeur sous-arbre droite

	public function __construct($infos, $leftChild, $rightChild)
	{
		$this->info = $infos;
		$this->leftChild = $leftChild;
		$this->rightChild = $rightChild;
	}


	public function getInfo()
	{
		return $this->info;
	}

	public function setInfo($infos)
	{
		$this->info = $infos;
	}

	public function getLeftChild()
	{
		return $this->leftChild;
	}

	public function setLeftChild($leftChild)
	{
		$this->leftChild = $leftChild;
	}

	public function getRightChild()
	{
		return $this->rightChild;
	}

	public function setRightChild($rightChild)
	{
		$this->rightChild = $rightChild;
	}
}




class BinaryTree
{

	/*typedef struct arbre
	 * 	{
	 * 		T info;
	 * 		struct arbre *sag; // Pointeur sous-arbre gauche
	 * 		struct arbre *sad; // Pointeur sous-arbre droite
	 * 	} arbre;
	 */
	// typedef arbre *ptr_arbre; // Définition pointeur arbre départ

	private $tree; // Référence Tree


	public function __construct()
	{

	}

	/*
	public function create()
	{



	}
	*/

	public function add($infos, $treeNode = null)
	{
		//$treeNode = newTree();
		/* 	- Ajouter :
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
		 * 		FIN
		 */

		if ($treeNode === null)
		{
			$treeNode->setInfo($infos);
			$treeNode->setLeftChild(null);
			$treeNode->setRightChild(null);
		}
		else if ($treeNode->getInfo() <= $infos)
		{
			add($infos, $treeNode->getRightChild());
		}
		else
		{
			add($infos, $treeNode->getLeftChild())
		}
	}


	public function search()
	{
		/*	- Rechercher :
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
		 */
	}



}




?>