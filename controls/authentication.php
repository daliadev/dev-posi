<?php


/**
 * 
 *
 * @author Nicolas Beurion
 */

//require_once('utils/config.php');


class ServicesAuth
{

    static function login($droit)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        session_name("Authentification");
        $_SESSION['authenticate'] = true;
        $_SESSION['token_uncrypted'] = uniqid();
        $_SESSION['token'] = ServicesAuth::hashPassword($_SESSION['token_uncrypted']);
        
        if ($droit == "admin")
        {
            $_SESSION['droit'] = "admin";
        }
        else
        {
            $_SESSION['droit'] = "user";
        }
        
        //var_dump($_SESSION);
        //exit();
    }
    
    
    static function logout()
    {
        
        if (!isset($_SESSION) || empty($_SESSION))
        {
            session_start();
        }
        
        $_SESSION = array();
        session_destroy();
    }
    
    
    static function checkAuthentication($right)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (isset($_SESSION['token']) && ServicesAuth::hashPassword($_SESSION['token_uncrypted']) == $_SESSION['token'] && isset($_SESSION['droit']) && ($_SESSION['droit'] == $right || $_SESSION['droit'] == "admin"))
        {
            return true;
        }
        else
        {
            header("Location: ".SERVER_URL."erreur/page503");
            exit();
        }
    }
    
    
    static function getAuthenticationRight()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (isset($_SESSION['authenticate']) && !empty($_SESSION['authenticate']))
        {
            if ($_SESSION['droit'] == "admin")
            {
                return "admin";
            }
            else if ($_SESSION['droit'] == "user")
            {
                return "user";
            }
        }
        
        return false;
    }
    
    /*
    static function getAuthentication()
    {
        if ($_SESSION['authenticate'])
        {
            return true;
        }
        else 
        {
            return false;
        }
    }
    */
    
    static function openUserSession()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        $_SESSION['session'] = true;
    }
    
    
    static function closeUserSession()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (isset($_SESSION['session']))
	{
            unset($_SESSION['session']);
        }
    }
    
    
    static function checkUserSession()
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (isset($_SESSION['session']) && $_SESSION['session'])
	{
            return true;
	}
        
        return false;
    }
    

    
    
    static function setSessionData($key, $value)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }

        $_SESSION[$key] = $value;
    }
    
    
    static function getSessionData($key)
    {
        if (!isset($_SESSION))
        {
            session_start();
        }
        
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        
        return false;
    }

    
    static function hashPassword($pass)
    {
        $salt = Config::SALT;
        return sha1($salt.md5($pass.$salt).sha1($salt));
    }
    
}


?>
