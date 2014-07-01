<?php

/**
 * Description of Config
 *
 * @author Nicolas Beurion
 */

class Config 
{

    const DEBUG_MODE = 0; // Activer (1) / désactiver (0) l'affichage du débuguage
    
    const ALLOW_ACTIVITES = 0; // Activer (1) / désactiver (0) la gestion des activités dans la partie admin (prédiction d'un parcours, orientation) (non implémenté)
    
    const ALLOW_AUDIO = 1; // Activer (1) / désactiver (0) le lecteur audio flash (non implémenté)

    const ALLOW_AJAX = 1; // Activer (1) / désactiver (0) Est utilisé pour obtenir des requêtes instantanées (listes déroulantes lièes...) (non implémenté)
 


    const POSI_NAME = "Positionnement Chantier-École"; // Nom du positionnement
    
    const POSI_TITLE = "Test de positionnement"; // Titre/accroche du positionnement

    const ADMIN_TITLE = "Gestion du positionnement"; // Titre de la partie admin


    const NBRE_POSI_MAX = 0; // Nombre de positionnements maximum que peut effectuer l'organisme client (0: illimité) (non implémenté)
    

    // Tableau des emails des administrateurs (pour les positionnements effectués)
    public static $emails_admin = array(
        "g.billard@educationetformation.fr", 
        "n.beurion@educationetformation.fr"
    );


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
        ),
		 array(
            'code_menu' => "50",
            'label_menu' => "Utilisateur",
            'url_menu' => "utilisateur",
            'type_lien_menu' => "dynamic"
        ),
		array(
            'code_menu' => "60",
            'label_menu' => "Organisme",
            'url_menu' => "organisme",
            'type_lien_menu' => "dynamic"
		
       ),
        array(
            'code_menu' => "70",
            'label_menu' => "Comptes administrateur",
            'url_menu' => "compte",
            'type_lien_menu' => "static"
        
       )
    );
    
    
    public static $menu_stat = array(
    
        'titre' =>"Gestion des résultats",
    
        array(
            'code_menu' => "10",
            'label_menu' => "Restitution",
            'url_menu' => "restitution",
            'type_lien_menu' => "dynamic"
        ),
		 array(
            'code_menu' => "20",
            'label_menu' => "Statistique",
            'url_menu' => "statistique",
            'type_lien_menu' => "dynamic"
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

