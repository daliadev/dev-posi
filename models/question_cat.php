<?php



class QuestionCategorie
{
    
    public $id_question_cat = null;
    public $ref_question = null;
    public $ref_cat = null;

    
    public function getId()
    {
        return $this->id_question_cat;
    }

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
