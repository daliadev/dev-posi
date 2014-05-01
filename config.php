<?php

/**
 * Description of Config
 *
 * @author Nicolas Beurion
 */

class Config 
{

    const DEBUG_MODE = 0; // Activer (1) / désactiver (0) l'affichage du débuguage
    
    const ALLOW_ACTIVITES = 0; // Activer (1) / désactiver (0) la gestion des activités dans la partie admin (prédiction d'un parcours d'orientation)
    
    const ALLOW_AUDIO = 1; // Activer (1) / désactiver (0) le lecteur audio flash

    const ALLOW_AJAX = 1; // Activer (1) / désactiver (0) Est utilisé pour obtenir des requêtes instantanées (listes déroulantes lièes...)
 

    const POSI_NAME = "Positionnement Chantier-Ecole"; // Nom du positionnement
    
    const POSI_TITLE = "Positionnement Chantier-Ecole"; // Titre/accroche du positionnement


    const EMAIL_REFERENT = "xxx.xx@xxx.xx"; // Email de destination des positionnements éffectués

    
    
    // Coordonnées de la base de données
    public static $database = array(
        'driver'    =>  "mysql",
        'host'      =>  "127.0.0.1",
        'schema'    =>  "posi_dalia",
        'username'  =>  "root",
        'password'  =>  ""
    );


    // Tableau du menu admin
    public static $menu_gestion = array(
    
        'titre' =>"Gestion",
    
        array(
            'code_menu' => "10",
            'label_menu' => "Questions / Réponses",
            'url_menu' => "question",
            'type_lien_menu' => "dynamic",
            'icone' => 'ghiug.jpg'
        ),
        array(
            'code_menu' => "20",
            'label_menu' => "Catégories / Compétences",
            'url_menu' => "categorie",
            'type_lien_menu' => "dynamic"
        ),
        array(
            'code_menu' => "30",
            'label_menu' => "Degrés d'aptitude",
            'url_menu' => "degre",
            'type_lien_menu' => "dynamic"
        ),
        array(
            'code_menu' => "40",
            'label_menu' => "Activités",
            'url_menu' => "activite",
            'type_lien_menu' => "static"
        )
       
    );
    
    
    public static $menu_stat = array(
    
        'titre' =>"Statistique",
    
        array(
            'code_menu' => "10",
            'label_menu' => "Restitution des résultats",
            'url_menu' => "restitution",
            'type_lien_menu' => "dynamic"
        ),
        array(
            'code_menu' => "20",
            'label_menu' => "Gérer les comptes",
            'url_menu' => "compte",
            'type_lien_menu' => "static"
        )
    );
    


    const SALT = "#zE'rGr[kj+KtCH£>FjF|fm-76s}T'Yjk<]JDs[{hj,[fbS*"; // Sert au hashage du mot de passe


    /* CodeOrganisme : dalia2013 (à remplacer si besoin) */
    public static function getCodeOrganisme()
    {
        $pass = sha1(Config::SALT.md5("dalia2013".Config::SALT).sha1(Config::SALT));

        return $pass;
    }

}


?>

