<?php


class CustomeDAO extends ModelDAO
{

	
	public function create($request, $resultName) 
	{
		$this->initialize();

		$this->resultset['response'] = executeCustomRequest($request, $resultName)

		return $this->resultset;
	}

}

?>
