<?php 


require_once(ROOT.'models/localisation/territoire.php');



class ServicesLocalisation extends Main
{



	public function __construct()
	{
		$this->initialize();

		$this->controllerName = "localisation";

	}
}


?>