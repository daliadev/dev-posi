<?php



class ServicesErreur extends Main
{

    public function __construct() 
    {
        $this->controllerName = "error";
    }
    
    
    public function page404()
    {
        $this->setTemplate("template_default");
        $this->render('page404');
    }
    
    
    public function page503()
    {
        $this->setTemplate("template_default");
        $this->render('page503');
    }
    
    
    public function page500()
    {
        $this->setTemplate("template_default");
        $this->render('page500');
    }
    
}


?>
