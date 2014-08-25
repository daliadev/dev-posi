<?php

class PDOConnectDB extends PDO
{
    
    public function __construct()
    {
        require_once(ROOT."config.php");
        
        if (isset(Config::$database) && !empty(Config::$database) && is_array(Config::$database))
        {
            $dns = Config::$database['driver'] . ":host=" . Config::$database['host'];
            if (!empty(Config::$database['port']))
            {
                $dns .= ";port=" . Config::$database['port'];
            }
            $dns .= ";dbname=" . Config::$database['schema'];

            parent::__construct($dns, Config::$database['username'], Config::$database['password']);
        }
        else 
        {
            return false;
        }
    }
}

?>

