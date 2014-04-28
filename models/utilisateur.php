<?php

/**
 * Description of utilisateur
 *
 * @author Nicolas Beurion
 * 
 */

class Utilisateur 
{

    public $id_user = null;
    public $ref_niveau = null;
    public $nom_user = null;
    public $prenom_user = null;
    public $date_naiss_user = null;
    public $adresse_user = null;
    public $code_postal_user = null;
    public $ville_user = null;
    public $tel_user = null;
    public $email_user = null;
    public $nbre_sessions_totales = null;
    public $nbre_sessions_accomplies = null;

    /*
    public function __construct($idUser, $refNiveau, $nomUser, $prenomUser, $dateNaissUser, $adresseUser = NULL, $codePostalUser = NULL, $villeUser = NULL, $telUser = NULL, $emailUser = NULL) 
    {
        $this->id = $idUser;
        $this->refNiveau = $refNiveau;
        $this->nom = $nomUser;
        $this->prenom = $prenomUser;
        $this->dateNaissUser = $dateNaissUser;
        $this->adresse = $adresseUser;
        $this->codePostal = $codePostalUser;
        $this->ville = $villeUser;
        $this->telephone = $telUser;
        $this->email = $emailUser;
    }
    */

    public function getId()
    {
        return $this->id_user;
    }
    
    public function getRefNiveau()
    {
        return $this->ref_niveau;
    }
    
    public function getNom()
    {
        return $this->nom_user;
    }
    
    public function getPrenom()
    {
        return $this->prenom_user;
    }
    
    public function getDateNaiss()
    {
        return $this->date_naiss_user;
    }
    
    public function getAdresse()
    {
        return $this->adresse_user;
    }
    
    public function getCodePostal()
    {
        return $this->code_postal_user;
    }
    
    public function getVille()
    {
        return $this->ville_user;
    }
    
    public function getTel()
    {
        return $this->tel_user;
    }
    
    public function getEmail()
    {
        return $this->email_user;
    }
    
    public function getSessionsTotales()
    {
        return $this->nbre_sessions_totales;
    }
    
    public function getSessionsAccomplies()
    {
        return $this->nbre_sessions_accomplies;
    }
    
}

?>
