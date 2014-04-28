<?php


/**
 * Ensemble des attributs d'une questions d'une question
 *
 * @author Nicolas Beurion
 */

class Question
{
    
    public $id_question = null;
    public $ref_degre = null;
    //public $code_categorie = null;
    public $num_ordre_question = null;
    public $type_question = null;
    public $intitule_question = null;
    public $image_question = null;
    public $audio_question = null;
    

    
    public function getId()
    {
        return $this->id_question;
    }
    
    public function getRefDegre()
    {
        return $this->ref_degre;
    }
    
    /*
    public function getCodeCategorie()
    {
        return $this->code_categorie;
    }
    */
    
    public function getNumeroOrdre()
    {
        return $this->num_ordre_question;
    }

    public function getType()
    {
        return $this->type_question;
    }
    
    public function getIntitule()
    {
        return $this->intitule_question;
    }
    
    public function getImage()
    {
        return $this->image_question;
    }
    
    public function getSon()
    {
        return $this->audio_question;
    }
    
}

?>
