<?php


class CustomDAO extends ModelDAO
{

	
	public function read($request, $resultName) 
	{
		$this->initialize();

		$this->resultset['response'] = $this->executeCustomRequest($request, $resultName);

		return $this->resultset;
	}

}

?>
