<?php


/**
 * Description
 *
 * @author Nicolas Beurion
 */

class Compte
{
    
    public $id_admin = null;
    public $nom_admin = null;
    public $email_admin = null;
    public $pass_admin = null;
    public $droits = null;

    
    
    public function getId()
    {
        return $this->id_admin;
    }
    
    public function getNom()
    {
        return $this->nom_admin;
    }

    /*
    public function getEmail()
    {
        return $this->email_admin;
    }
    */

    public function getPass()
    {
        return $this->pass_admin;
    }

    public function getDroits()
    {
        return $this->droits;
    }

}

?>