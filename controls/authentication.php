<?php


class ServicesAuth
{

	static function login($droit)
	{
		ServicesAuth::startSession();

		session_name("Authentification");
		$_SESSION['authenticate'] = true;
		$_SESSION['token_uncrypted'] = uniqid();
		$_SESSION['token'] = ServicesAuth::hashPassword($_SESSION['token_uncrypted']);

		if ($droit == "admin")
		{
			$_SESSION['droit'] = "admin";
		}
		else if ($droit == "custom-admin")
		{
			$_SESSION['droit'] = "custom-admin";
		}
		else if ($droit == "custom")
		{
			$_SESSION['droit'] = "custom";
		}
		else
		{
			$_SESSION['droit'] = "user";
		}

	}
	
	
	static function logout()
	{
		ServicesAuth::startSession();

		if (ini_get("session.use_cookies")) 
		{
			session_set_cookie_params(
				time() - 3600
				// $params["path"], 
				// $params["domain"],
				// $params["secure"], 
				// $params["httponly"]
			);
		}

		//echo "Session cookie outdated.";
		
		//ServicesAuth::startSession();

		$_SESSION = array();
		session_destroy();

		//echo "Session destroyed.";
	}
	
	
	static function checkAuthentication($right = array())
	{
		ServicesAuth::startSession();

		$isLogged = false;

		if (isset($_SESSION['token']) && ServicesAuth::hashPassword($_SESSION['token_uncrypted']) == $_SESSION['token'] && isset($_SESSION['droit']) && $right !== null)
		{

			if (!is_array($right))
			{
				$right = array($right);
			}

			for ($i = 0; $i < count($right); $i++) 
			{ 
				if ($_SESSION['droit'] == $right[$i] || $_SESSION['droit'] == "admin")
				{
					return true;
				}
			}
		}
		else
		{
			//echo session_id();
			header("Location: ".SERVER_URL."erreur/page503");
			exit();
		}

		return false;
	}
	
	
	static function getAuthenticationRight()
	{
		ServicesAuth::startSession();

		
		if (isset($_SESSION['authenticate']) && !empty($_SESSION['authenticate']))
		{
			if ($_SESSION['droit'] == "admin")
			{
				return "admin";
			}
			else if ($_SESSION['droit'] == "custom-admin")
			{
				return "custom";
			}
			else if ($_SESSION['droit'] == "custom")
			{
				return "custom";
			}
			else if ($_SESSION['droit'] == "user")
			{
				return "user";
			}
		}
		
		return false;
	}
	
	
	
	static function openUserSession()
	{
		ServicesAuth::startSession();

		$_SESSION['session'] = true;
	}
	
	
	static function closeUserSession()
	{
		ServicesAuth::startSession();
		
		if (isset($_SESSION['session']))
		{
			unset($_SESSION['session']);
		}
	}
	
	
	static function checkUserSession()
	{
		ServicesAuth::startSession();

		if (isset($_SESSION['session']) && $_SESSION['session'])
		{
			return true;
		}
		
		return false;
	}
	

	
	
	static function setSessionData($key, $value)
	{
		ServicesAuth::startSession();

		$_SESSION[$key] = $value;
	}
	
	
	static function getSessionData($key)
	{
		ServicesAuth::startSession();
		
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




	static function startSession()
	{
		if (strlen(session_id()) === 0) 
		{
			session_start();
		}
	}
	
}


?>
