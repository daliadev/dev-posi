<?php


require_once('utils/tools.php');



class Main 
{
	
	public $controllerName = 'inscription'; // valeur par défaut
	public $url = null;
	
	public $errors = array();
	public $success = array();
	
	public $formData = array();
	public $returnData = array();
	
	private $data = array();

	private $pageTitle = null;
	private $template = null;
	private $header = null;
	private $footer = null;

	private $styles = array();
	private $headScripts = array();
	private $queueScripts = array();
	
	
	public function __construct()
	{
		$this->controllerName = "main";
	}
	

	public function initialize()
	{
		$this->formData = array();
		
		$this->returnData = array();
		$this->returnData['response'] = array();
		$this->returnData['response']['errors'] = array();
		
		$this->errors = array();
		
		$this->url = null;

		$this->pageTitle = Config::POSI_TITLE.' '.Config::CLIENT_NAME;
	}

	
	
	public function setResponse($response_data)
	{
		$this->data = array_merge($this->data, $response_data);
	}
	


	public function setPageTitle($title)
	{
		$this->pageTitle = $title;
	}

	public function setHeader($filename)
	{
		$this->header = ROOT.'views/templates/headers/'.$filename.'.php';
	}

	public function setFooter($filename)
	{
		$this->footer = ROOT.'views/templates/footers/'.$filename.'.php';
	}



	public function addStyleSheet($filename, $path = null)
	{
		if (!empty($filename) && $filename !== null)
		{
			if ($path !== null)
			{
				$file = $path.'/'.$filename.'.css';
			}
			else
			{
				$file = SERVER_URL.'media/css/'.$filename.'.css';
			}

			array_push($this->styles, '<link type="text/css" rel="stylesheet" media="all" href="'.$file.'" />');
		}
	}

	public function addScript($filename, $path = null)
	{
		if (!empty($filename) && $filename !== null)
		{
			if ($path !== null)
			{
				$file = $path.'/'.$filename.'.js';
			}
			else
			{
				$file = SERVER_URL.'media/js/'.$filename.'.js';
			}

			array_push($this->headScripts, '<script src="'.$file.'" type="text/javascript"></script>');
		}
	}

	public function enqueueScript($filename, $path = null)
	{
		if (!empty($filename) && $filename !== null)
		{

			if ($path !== null)
			{
				$file = $path.'/'.$filename.'.js';
			}
			else
			{
				$file = SERVER_URL.'media/js/'.$filename.'.js';
			}

			array_push($this->queueScripts, '<script src="'.$file.'" type="text/javascript"></script>');
		}
	}
	


	public function setTemplate($requestTemplate)
	{
		$this->template = ROOT.'views/templates/pages/'.$requestTemplate.'.php';
	}


	
	public function render($filename)
	{
		extract($this->data);
		
		ob_clean();
		ob_start();
		require(ROOT.'views/'.$this->controllerName.'/'.$filename.'.php');
		$template_content = ob_get_clean();
		
		// Injection des feuilles de styles
		$style_sheets = '';

		for ($i = 0; $i < count($this->styles); $i++)
		{
			$style_sheets .= $this->styles[$i];
		}

		// Injection des scripts d'entête
		$script_files = '';

		for ($i = 0; $i < count($this->headScripts); $i++)
		{
			$script_files .= $this->headScripts[$i];
		}

		// Injection des script de fin de page
		$queue_script_files = '';

		for ($i = 0; $i < count($this->queueScripts); $i++)
		{
			$queue_script_files .= $this->queueScripts[$i];
		}

		$page_title = $this->pageTitle;
		$header_content = $this->header;
		$footer_content = $this->footer;


		// Injection du template de la page
		if ($this->template)
		{
			require($this->template);
		}
		else
		{
			require(ROOT.'views/templates/pages/tpl_basic_page.php');
			//echo $template_content;
		}
	}


	public function renderPDF($filename, $output, $dest = "D")
	{
		require_once(ROOT.'lib/html2pdf/html2pdf.class.php');
		
		extract($this->data);
		
		ob_clean();
		ob_start();
		require(ROOT.'views/'.$this->controllerName.'/'.$filename.'.php');
		$pdf_content = ob_get_clean();

		try
		{
			$pdf = new HTML2PDF("P", "A4", "fr", true, "UTF-8", array(5, 5, 5, 5));
			
			$pdf->pdf->SetDisplayMode('fullpage');
			$pdf->pdf->SetTitle('Restitution du positionnement');
			$pdf->pdf->SetAuthor(Config::POSI_NAME);
			
			$pdf->WriteHTML($pdf_content);
			
			if (!$dest)
			{
				//$pdf->Output($output);
			}
			else
			{
				//$pdf->Output($output, $dest);
			}
		}
		catch (HTML2PDF_exception $e)
		{
			echo 'Erreur : '.$e->getMessage();
		}
	}


	
	
	
	/***   Outils   ***/
	
	public function validatePostData($value, $key, $type, $required = false, $errorEmpty = "", $errorData = "")
	{
		$filteredData = "";
		
		if (isset($value) && !empty($value))
		{
			$filteredData = $this->filterData($value, $type);
			
			if (!$filteredData)
			{
				$filteredData = "";
				$this->registerError("form_valid", $errorData);
			}
		}
		else 
		{
			if ($required)
			{
				$this->registerError("form_empty", $errorEmpty);
			}
		}
		
		return $filteredData;
	}
	
	
	
	public function filterData($value, $type)
	{
		$validValue = false;
		
		switch ($type)
		{
			case "string" : 
				$validValue = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
				break;

			case "integer" :
				if (preg_match("`^[0-9]*$`", $value))
				{
					$validValue = $value;
				}
				break;

			case "date" :
				if (preg_match("`^[0-3][0-9](\/|-|\s)[0-1][0-9](\/|-|\s)[1-2][0-9][0-9][0-9]$`", $value))
				{
					$validValue = $value;
				}
				break;

			case "email" :
				$validValue = strtolower(filter_var($value, FILTER_VALIDATE_EMAIL));
				break;

			default :
				break;
		}
		
		return $validValue;
	}

	
	
	
	/**
	 * Filtre et enregistre la ou les valeur "errors" contenu dans le tableau passés en paramètres.
	 * 
	 * @param array Le tableau à "nettoyer" de ses erreurs.
	 * 
	 * @return boolean Si erreurs trouvées => true, sinon => false.
	 */
	public function filterDataErrors(&$data)
	{
		if (isset($data['errors']) && !empty($data['errors']))
		{
			foreach ($data['errors'] as $dataError)
			{
				$this->registerError($dataError['type'], $dataError['message']);
			}
			unset($data['errors']);
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	
	/**
	 * Enregistre une nouvelle entrée dans le tableau des erreurs.
	 * 
	 * @params string Le type d'erreur.
	 * @params string Le message de l'erreur.
	 */
	public function registerError($errorType, $errorMessage)
	{
		$this->errors[] = array('type' => $errorType, 'message' => $errorMessage);
	}
	
	
	/**
	 * Enregistre une nouvelle entrée dans le tableau des succès.
	 * 
	 * @params string Le message.
	 */
	public function registerSuccess($message)
	{
		$this->success[] = $message;
	}
	
	
}

?>
