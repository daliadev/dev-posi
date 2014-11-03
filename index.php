<?php

/**
 * Sert de dispacher.
 * Index est appelé à chaque changement de page, il aiguille ensuite vers le bon controlleur et le template d'affichage
 *
 * @author Nicolas Beurion
 */



// définition des constantes qui permettent d'enregistrer les racines des fichiers et du server
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('ROOT', str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('SERVER_URL', 'http://'.$_SERVER['HTTP_HOST'].WEBROOT);

define('IMG_PATH', "uploads/img/");
define('THUMBS_PATH', "uploads/img/thumbs/");
define('AUDIO_PATH', "uploads/audio/");
define('VIDEO_PATH', "uploads/video/");


// Inclusion da la classe Config
require_once(ROOT.'config.php');

// Inclusion du contrôleur principal
require_once(ROOT.'controls/main.php');

// Inclusion du gestionnaire d'acces aux données et des modeles de données
require_once('models/dao/model_dao.php');

// Définition du fuseau horiaire
date_default_timezone_set('Europe/Paris');



// On recupère les paramètres passés dans l'entête HTTP sous forme de tableau si elle existe
if (isset($_GET['p']) && (!empty($_GET['p'])))
{
    $requestParams = explode('/', $_GET['p']);
}


if (!isset($requestParams[0]) || empty($requestParams[0]))
{
    $requestParams = array('inscription', 'organisme');
}


if ($requestParams[0] == 'admin')
{
    if (!isset($requestParams[1]) || empty($requestParams[1]))
    {
        $requestParams[1] = 'login';
    }
}

if (!isset($requestParams[1]) || empty($requestParams[1]))
{
    if ($requestParams[0] == 'admin')
    {
        $requestParams[1] = 'login';
    }
    else
    {
        $requestParams = array('inscription', 'organisme'); 
    }
}


// Le contrôleur se trouve dans le premier paramètre
$controllerRequest = $requestParams[0];

// Le comportement/méthode demandé doit se trouver dans le premier paramètre
$actionRequest = $requestParams[1];

// On supprime du tableau les 2 paramètres qui viennent d'être utilisés
array_shift($requestParams);
array_shift($requestParams);


// Inclusion du controleur demandé
$controllerName = 'services_'.$controllerRequest.'.php';
require(ROOT.'controls/'.$controllerName);

// Le nom de la classe est transformé pour obtenir la bonne casse
$firstLetter = strtoupper(substr($controllerRequest, 0, 1));
$controllerClassName = 'Services'.substr_replace($controllerRequest, $firstLetter, 0, 1);

// Instanciation du contrôleur demandé
$controller = new $controllerClassName();


// Déclenchement de la fonction du contrôleur si elle existe.
// Les paramètres de l'entête HTTP restants sont transmis à la fonction.
if (method_exists($controller, $actionRequest))
{
    call_user_func_array(array($controller, $actionRequest), array($requestParams));
}
else
{
    // Sinon, renvoi vers la page 404
    header("Location: ".SERVER_URL."erreur/page404");
    exit();
}


?>
