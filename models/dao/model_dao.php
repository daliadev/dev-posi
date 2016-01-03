<?php



require_once('models/drivers/pdo_mysql_db.php');


class ModelDAO 
{
    
    private $dbConnect = null;
    private $callStatement = null;
    
    private $isConnected = false;
    
    private $resultset = null;
    
    
    /**
     * initialize - Initialise le tableau dans lequel se trouvent les résultats des requêtes et les erreurs
     * 
     */
    public function initialize()
    {
        $this->resultset['response'] = array();
        $this->resultset['response']['errors'] = array();
    }
    
    
    
    public function connectDB()
    {
        if (!$this->isConnected)
        {
            $this->dbConnect = new PDOConnectDB();
            $this->dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->isConnected = true;
        }
    }
    
    public function disconnectDB()
    {
        $this->dbConnect = null;
        $this->isConnected = false;
    }
    
    
    
    public function prepareStatement($queryString)
    {
        $this->callStatement = $this->dbConnect->prepare($queryString);
    }
    
    
    public function bindStatementParams($params = array())
    {
        if (!empty($params))
        {
            foreach ($params as $param)
            {
                if (!empty($param['id']) && !empty($param['value']) && !empty($param['type']))
                {
                    $id = $param['id'];
                    $value = $param['value'];
                    $type = PDO::PARAM_STR;
                    
                    switch($param['type'])
                    {
                        case "string":
                            $type = PDO::PARAM_STR;
                            break;

                        default :
                            break;
                    }
                    $this->callStatement->bindParam($id, $value, $type);
                }
            }
        }
        
    }
    
    public function executeStatement()
    {
        $this->callStatement->execute();
    }
    
    
    public function getStatementFetch()
    {
        return $this->callStatement->fetch();
    }

    
    public function getLastInsertId()
    {
        return $this->dbConnect->lastInsertId();
    }
    
    
    public function getRowCount()
    {
        return $this->callStatement->rowCount();
    }

    
    public function closeStatement()
    {
        $this->callStatement->closeCursor();
        $this->callStatement = null;
    }



    

    public function createQueryString($mode, $fieldsvalues, $table, $whereStmt = '', $orderByFields = '')
    {
        $queryString = "";
        
        if (!empty($mode) && !empty($table))
        {    
            if ($mode == 'select')
            {
                if (!empty($orderByFields))
                {
                    $orderByFields = "ORDER BY ".$orderByFields;
                }
                $queryString = "SELECT * FROM ".$table." WHERE ".$whereStmt." ".$orderByFields;

            }  
            else if ($mode == "insert" && !empty($fieldsvalues))
            {
                $fields = "";
                $insertString = "";
                
                $i = 0;
                foreach ($fieldsvalues as $field => $value)
                {
                    if (empty($fields))
                    {
                        $fields .= $field;
                        if (is_null($value))
                        {
                            $insertString .= "NULL"; 
                        }
                        else
                        {
                            $insertString .= "'".$value."'";  
                        }
                    }
                    else 
                    {
                        $fields .= ", ".$field;
                        if (is_null($value))
                        {
                            $insertString .= ", NULL"; 
                        }
                        else
                        {
                            $insertString .= ", '".$value."'"; 
                        }
                    }

                    $i++;
                }

                $queryString = "INSERT INTO ".$table." (".$fields.") VALUES (".$insertString.") ".$whereStmt;
                
            }
            else if ($mode == "update" && !empty($fieldsvalues))
            {
                $updateString = "";

                $i = 0;
                foreach ($fieldsvalues as $field => $value)
                {

                    if (empty($updateString))
                    {
                        if (is_null($value))
                        {
                            $updateString .= $field." = NULL"; 
                        }
                        else
                        {
                            $updateString .= $field." = '".$value."'"; 
                        }
                         
                    }
                    else 
                    {
                        if (is_null($value))
                        {
                            $updateString .= ", ".$field." = NULL";
                        }
                        else
                        {
                            $updateString .= ", ".$field." = '".$value."'";
                        }
                    }   

                    $i++;
                }

                $queryString = "UPDATE ".$table." SET ".$updateString." ".$whereStmt;
            } 
        }

        return $queryString;
    }
    
    
    
    
    
    public function executeRequest($mode, $query, $tableName, $objectName = null)
    {
        $resultset = array();
        
        try
        {
            // Connection à la base de données.
            $this->connectDB();

            // Création de l'appel à la requête préparée.
            $this->prepareStatement($query);

            // Execution de la requête préparée.
            $this->executeStatement();
            
            switch ($mode)
            {
                case "select":
                        
                    // resultat des organismes trouvées.
                    if ($this->getRowCount() > 1)
                    {
                        $resultset[$tableName] = array();
                        
                        // Pour chaque ligne trouvées, ajout d'un objet dans la liste.
                        while ($tabChamps = $this->getStatementFetch())
                        {
                            // Si le tableau des résultats comporte des clés numériques, on supprime l'entrée du tableau.
                            // On ne garde que les clés qui sont des noms.
                            foreach ($tabChamps as $key => $value) 
                            {
                               if (is_numeric($key)) 
                               {
                                   unset($tabChamps[$key]);
                               }
                            }
                            
                            // Construction dynamique de l'objet demandé.
                            $object = $this->buildModel($objectName, $tabChamps);
                            
                            // Ajout de l'objet au tableau de sortie.
                            array_push($resultset[$tableName], $object);
                        }
                    }
                    else if ($this->getRowCount() === 1)
                    {
                        // la ligne trouvée est unique.
                        $tabChamps = $this->getStatementFetch();
                        
                        // Construction dynamique de l'objet.
                        $object = $this->buildModel($objectName, $tabChamps);
                        
                        // On renvoi l'objet directement (non dans un tableau).
                        $resultset[$tableName] = $object; 
                    }
                    break;
                
                case "insert":
                    
                    $resultset[$tableName] = array();
                    
                    // On récupère l'id généré par l'insertion.
                    $resultset[$tableName]['last_insert_id'] = $this->getLastInsertId();
                    
                    break;
                
                case "update":
                    
                    $resultset[$tableName] = array();
                    
                    $resultset[$tableName]['row_count'] = $this->getRowCount();
                    
                    break;
                
                case "delete":
                    
                    $resultset[$tableName] = array();
                    
                    $resultset[$tableName]['row_count'] = $this->getRowCount();
                    
                    break;
                
                default:
                    break;  
            }

            // Fermeture de la requête préparée et fermeture de la connection.
            $this->closeStatement();
            $this->disconnectDB();
        } 
        catch (PDOException $e)
        {
            // Erreur de connection ou probleme avec la création de la requête préparée.
            $resultset['errors'][] = array('type' => "pdo_exception", 'message' => $e->getMessage().".");
        }
        
        return $resultset;
    }
    

    /*
    public function requestModel($requestType, $objectName, $data, $clause)
    {

    }
    */
    

    public function filterResultToArray($resultset, $objectName)
    {
        if (isset($resultset['response']['errors']) && !empty($resultset['response']['errors']))
        {
            if (isset($resultset['response'][$objectName]) && !empty($resultset['response'][$objectName]) && count($resultset['response'][$objectName]) == 1)
            { 
                $resultLine = $resultset['response'][$objectName];
                $resultset['response'][$objectName] = array($resultLine);
            }

            return $resultset;
        }
        
        return false;
    }


    
    private function buildModel($objectName, $data)
    {
        $object = new $objectName();
        
        foreach ($data as $key => $value)
        {
            if (property_exists($object, $key))
            {
                $object->$key = $value;
            }
        }

        return $object;
    }

    
}

?>