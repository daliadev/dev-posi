<?php

/**
 * Description of Config
 *
 * @author Nicolas Beurion / Dalia Team
 */

class Config 
{

    const POSI_NAME = "Positionnement XXX"; // Nom du positionnement.
    
    const POSI_TITLE = "Test de positionnement"; // Titre/accroche du positionnement.

    const ADMIN_TITLE = "Gestion du positionnement XXX"; // Titre de la partie admin.

    const POSI_MAX_COUNT = 0; // Nombre de positionnements maximum que peut effectuer l'organisme client (0: illimité) (non implémenté).
    


    const DEBUG_MODE = 0; // Activer (1) / désactiver (0) - L'affichage du débuguage.
    
    const ALLOW_ACTIVITES = 0; // Activer (1) / désactiver (0) - La gestion des activités dans la partie admin (prédiction d'un parcours, orientation) (non implémenté).
    
    const ALLOW_AUDIO = 1; // Activer (1) / désactiver (0) - Le lecteur audio flash (non implémenté).

    const ALLOW_AJAX = 1; // Activer (1) / désactiver (0) - Est utilisé pour obtenir des requêtes instantanées (listes déroulantes lièes...).
    

    // Gestion spécifique des organismes lors de l'inscription
    const ALLOW_OTHER_ORGAN = 1; // Activer (1) / désactiver (0) Permet la saisie d'un organisme par un utilisateur lors de l'inscription.




    // Gestion spécifique des intervenants lors de l'inscription
    const ALLOW_REFERENT_INPUT = 0; // Activer (1) / désactiver (0) - Affiche un champ de saisie pour le référent/formateur, sinon affiche la liste des intervenants présaisis.

    // Tableau des emails des référents/formateurs présaisis
    public static $emails_referent = array(
        "xxx.xxxx@organisme1.fr",
        "xxx.xxxx@organisme2.fr"
    );

    const ENVOI_EMAIL_REFERENT = 0; // Activer (1) / désactiver (0) - Permet l'envoi du résultat au référent/formateur




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
    public static $admin_menu = array(

        // Partie Gestion du positionnement du menu
        array(

            'title' =>"Gestion",

            array(
                'code_menu' => "10",
                'label_menu' => "Questions / Réponses",
                'url_menu' => "question",
                'display' => true,
                'droits' => "admin"
            ),
            array(
                'code_menu' => "20",
                'label_menu' => "Catégories / Compétences",
                'url_menu' => "categorie",
                'display' => true,
                'droits' => "admin"
            ),
            array(
                'code_menu' => "30",
                'label_menu' => "Degrés d'aptitude",
                'url_menu' => "degre",
                'display' => true,
                'droits' => "admin"
            ),
            array(
                'code_menu' => "40",
                'label_menu' => "Activités",
                'url_menu' => "activite",
                'display' => false,
                'droits' => "admin"
            ),
    		 array(
                'code_menu' => "50",
                'label_menu' => "Utilisateur",
                'url_menu' => "utilisateur",
                'display' => true,
                'droits' => "custom,admin"
            ),
    		array(
                'code_menu' => "60",
                'label_menu' => "Organisme",
                'url_menu' => "organisme",
                'display' => true,
                'droits' => "custom,admin"
           ),
            array(
                'code_menu' => "70",
                'label_menu' => "Comptes administrateur",
                'url_menu' => "compte",
                //'display' => false,
                'display' => true,
                'droits' => "admin"
            
           )
        ),
        
        // Partie Gestion des résultats du menu
        array(

            'title' =>"Résultats",
        
            array(
                'code_menu' => "10",
                'label_menu' => "Restitution",
                'url_menu' => "restitution",
                'display' => true,
                'droits' => "custom,admin"
            ),
    		 array(
                'code_menu' => "20",
                'label_menu' => "Statistique",
                'url_menu' => "statistique",
                'display' => true,
                'droits' => "custom,admin"
            )
        )
    );
    


    /* CodeOrganisme : dalia2013 (à remplacer si besoin) */
    public static function getCodeOrganisme()
    {
        $pass = Config::hashPassword("dalia2013");
        return $pass;
    }


    const SALT = "#zE'rGr[kj+KtCH£>FjF|fm-76s}T'Yjk<]JDs[{hj,[fbS*"; // Sert au hashage du mot de passe

    public static function hashPassword($pass)
    {
        $salt = Config::SALT;
        return sha1($salt.md5($pass.$salt).sha1($salt));
    }

}


?>

