<?php

/**
 * Description of main_dao
 *
 * @author Nicolas Beurion
 */

require_once('models/dao/pbo_connect_db.php');


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
    
    
    
    public function prepareStatement($requestString)
    {
        $this->callStatement = $this->dbConnect->prepare($requestString);
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
    /*
    public function execute()
    {
        $this->callStatement->execute();
    }
    */
    /*
    public function queryStatement($queryString)
    {
        $this->callStatement = $this->dbConnect->query($queryString);
    }
    */
    
    /*
    public function query($queryString)
    {
        $this->callStatement = $this->dbConnect->query($queryString);
    }
    */
    
    
    public function getStatementFetch()
    {
        return $this->callStatement->fetch();
    }
    /*
    public function fetch()
    {
        return $this->callStatement->fetch();
    }
    */
    
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

    
    public function createQueryString($mode, $fieldsvalues, $table, $whereStmt = "")
    {
        
        $requestString = "";
        
        if (!empty($mode) && !empty($fieldsvalues) && !empty($table))
        {      
            if ($mode == "insert")
            {
                $fields = "";
                //$insertValues = "";
                $insertString = "";
                $i = 0;
                foreach ($fieldsvalues as $field => $value)
                {
                    if ($value == null)
                    {    
                        $value = "NULL";
                    }

                    if ($i == 0)
                    {
                        $fields .= $field;
                        $insertString .= "'".$value."'"; 
                    }
                    else 
                    {
                        $fields .= ", ".$field;
                        $insertString .= ", '".$value."'"; 
                    }
                    $i++;
                }
                

                $requestString = "INSERT INTO ".$table." (".$fields.") VALUES (".$insertString.") ".$whereStmt;
                
            }
            else if ($mode == "update")
            {
                $updateString = "";

                $i = 0;
                foreach ($fieldsvalues as $field => $value)
                {
                    if ($value == null)
                    {    
                        $value = "NULL";
                    }

                    if ($i == 0)
                    {
                        $fields .= $field;
                        $updateString .= "'".$value."'"; 
                    }
                    else 
                    {
                        $fields .= ", ".$field;
                        $updateString .= ", '".$value."'"; 
                    }
                    $i++;
                }

                $requestString = "UPDATE ".$table." SET ".$updateString." ".$whereStmt;
            } 
        }

        return $requestString;
    }
    
    
    
    
    
    public function executeRequest($mode, $request, $tableName, $objectName = null)
    {
        $resultset = array();
        
        try
        {
            // Connection à la base de données.
            $this->connectDB();

            // Création de l'appel à la requête préparée.
            $this->prepareStatement($request);

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
                            $object = $this->constructObject($objectName, $tabChamps);
                            
                            // Ajout de l'objet au tableau de sortie.
                            array_push($resultset[$tableName], $object);
                        }
                    }
                    else if ($this->getRowCount() === 1)
                    {
                        // la ligne trouvée est unique.
                        $tabChamps = $this->getStatementFetch();
                        
                        // Construction dynamique de l'objet.
                        $object = $this->constructObject($objectName, $tabChamps);
                        
                        // On renvoi l'objet directement (non dans un tableau).
                        $resultset[$tableName] = $object; 
                    }
                    break;
                
                case "insert":
                    
                    $resultset[$tableName] = array();
                    
                    // On récupère l'id généré par l'insertion.
                    $resultset[$tableName]['last_insert_id'] = $this->getLastInsertId();
                    /*
                    if (!$resultset[$tableName]['last_insert_id'])
                    {
                        // L'insertion a échouée.
                        $resultset['errors'][] = array('type' => "form_request", 'message' => "L'insertion n'a pas fonctionnée.");
                    }
                    */
                    break;
                
                case "update":
                    
                    $resultset[$tableName] = array();
                    
                    //if ($this->getRowCount() > 0)
                    //{
                        $resultset[$tableName]['row_count'] = $this->getRowCount();
                    //}
                    /*
                    if (!$resultset[$tableName]['row_count'])
                    {
                        // La mise à jour a échouée.
                        $resultset['errors'][] = array('type' => "form_request", 'message' => "La mise à jour n'a pas fonctionnée.");
                    }
                    
                     */
                    break;
                
                case "delete":
                    
                    $resultset[$tableName] = array();
                    
                    $resultset[$tableName]['row_count'] = $this->getRowCount();
                    
                    /*
                    if (!$resultset[$tableName]['row_count'])
                    {
                        // La suppression a échouée.
                        $resultset['errors'][] = array('type' => "form_request", 'message' => "La suppression n'a pas fonctionnée.");
                    }
                    
                     */
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
    
    
    private function constructObject($objectName, $data)
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
