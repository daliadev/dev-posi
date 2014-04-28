<?php


/**
 * Représente une catégorie
 *
 * @author Nicolas Beurion
 */

class QuestionCategorie
{
    
    public $ref_question = null;
    public $ref_cat = null;

    
    public function getRefQuestion()
    {
        return $this->ref_question;
    }
    
    public function getCodeCat()
    {
        return $this->ref_cat;
    }
    
}

?>
