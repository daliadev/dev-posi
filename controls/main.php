<?php

/**
 * Description of main.php
 *
 * @author Nicolas Beurion
 */


//require_once('utils/config.php');
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
    private $template = null;
    
    
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
    }

    
    
    
    public function setResponse($response_data)
    {
        $this->data = array_merge($this->data, $response_data);
    }
    
    
    public function setTemplate($requestTemplate)
    {
        $this->template = $requestTemplate;
    }

    
    public function render($filename)
    {
        extract($this->data);
        
        ob_clean();
        ob_start();
        require(ROOT.'views/'.$this->controllerName.'/'.$filename.'.php');
        $template_content = ob_get_clean();
        
        if (!$this->template)
        {
            echo $template_content;
        }
        else
        {
            require(ROOT.'views/templates/'.$this->template.'.php');
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
                $pdf->Output($output);
            }
            else
            {
                $pdf->Output($output, $dest);
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
                $validValue = filter_var($value, FILTER_VALIDATE_EMAIL);
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
