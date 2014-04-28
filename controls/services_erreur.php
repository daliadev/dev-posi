<?php


/**
 * 
 *
 * @author Nicolas Beurion
 */

//require_once('utils/config.php');


class ServicesErreur extends Main
{

    public function __construct() 
    {
        $this->controllerName = "error";
    }
    
    
    public function page404()
    {
        $this->setTemplate("template_page");
        $this->render('page404');
    }
    
    
    public function page503()
    {
        $this->setTemplate("template_page");
        $this->render('page503');
    }
    
    
    public function page500()
    {
        $this->setTemplate("template_page");
        $this->render('page500');
    }
    
}


?>
