<?php



class MenuAdmin
{
    
    public $code_menu = null;
    public $label_menu = null;
    public $url_menu = null;
    public $slug_menu = null;
    public $type_lien_menu = null;

    
    
    public function getCode()
    {
        return $this->code_menu;
    }
  
    public function getLabel()
    {
        return $this->label_menu;
    }
    
    public function getUrl()
    {
        return $this->url_menu;
    }

    
    public function getTypeLien()
    {
        return $this->type_lien_menu;
    }
    
    
}

?>
