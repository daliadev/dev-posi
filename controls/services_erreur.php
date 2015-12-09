<?php



class ServicesErreur extends Main
{

    public function __construct() 
    {
        $this->controllerName = "error";
    }
    
    
    public function page404()
    {
        $this->setTemplate("tpl_error");
        $this->render('page404');
    }
    
    
    public function page503()
    {
        $this->setTemplate("tpl_error");
        $this->render('page503');
    }
    
    
    public function page500()
    {
        $this->setTemplate("tpl_error");
        $this->render('page500');
    }



    public function maintenance()
    {
        $this->setTemplate("tpl_maintenance");
        $this->render('maintenance');
    }
}


?>
