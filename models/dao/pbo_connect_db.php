<?php

class PDOConnectDB extends PDO
{
    /*
    public function __construct($file = 'models/dao/db_config.ini')
    {
        if (!$setting = parse_ini_file($file, TRUE))
        {
            throw new Exception("Impossible d'ouvrir " . $file . ".<br/>");
        }

        $dns = $setting['database']['driver'] . ":host=" . $setting['database']['host'];
        if (!empty($setting['database']['port']))
        {
            $dns .= ";port=" . $setting['database']['port'];
        }
        $dns .= ";dbname=" . $setting['database']['schema'];

        parent::__construct($dns, $setting['database']['username'], $setting['database']['password']);

    }
    */
    
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

