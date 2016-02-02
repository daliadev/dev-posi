<?php

/**
 * Configuration du positionnement
 *
 * @author Nicolas Beurion / Dalia Team
 */



class Config 
{

    /* Version */
    const POSI_VERSION = "0.25.07";


    /* Nom et titre du positionnement */

    // Nom du positionnement.
    const POSI_NAME = "Positionnement";
    
    // Titre/accroche du positionnement.
    const POSI_TITLE = "Test de positionnement"; 
    
    // Titre de la partie admin.
    const ADMIN_TITLE = "Gestion du positionnement";

    const CLIENT_NAME = "Uniformation"; /*"Uniformation";*/
    const CLIENT_NAME_LONG = "Uniformation";
    
    
    /* Gestion du nombre de positionnement */

    // Nombre de positionnements maximum que peut effectuer l'organisme client.
    const POSI_MAX_COUNT = 0; // 0: illimité (non implémenté).
    

    // Affichage du débuguage (développement).
    const DEBUG_MODE = 0; // Activer (1) / désactiver (0)
    
    // Active la gestion des activités dans la partie admin (prédiction d'un parcours, orientation) (non implémenté).
    const ALLOW_ACTIVITES = 0; // Activer (1) / désactiver (0)
    
    // Autorise le lecteur audio au format flash (non implémenté).
    const ALLOW_AUDIO = 1; // Activer (1) / désactiver (0)

    // Autorise le lecteur audio au format flash (non implémenté).
    const ALLOW_VIDEO = 1; // Activer (1) / désactiver (0)

    // Permet d'obtenir des requêtes instantanées (listes déroulantes lièes...).
    const ALLOW_AJAX = 1; // Activer (1) / désactiver (0) 
    


    /* Gestion spécifique des organismes lors de l'inscription */

    // Active la saisie d'un organisme par un utilisateur lors de l'inscription.
    const ALLOW_OTHER_ORGAN = 1; // Activer (1) / désactiver (0) (Champ Autre) 




    /* Gestion spécifique des intervenants lors de l'inscription */

    // Active l'affichage un champ de saisie pour le référent/formateur, sinon affiche la liste des intervenants présaisis.
    const ALLOW_REFERENT_INPUT = 1; // Activer (1) / désactiver (0)

    // Tableau des emails des référents/formateurs présaisis lors de l'inscription.
    // Attention : ne marche que si ALLOW_REFERENT_INPUT = 0 et si ce tableau n'est pas vide.
    public static $emails_referent = array(
        "xxx.xxxx@organisme1.fr"
    );

    // Permet la saisie ou la présélection d'un même intervenant pour plusieurs organismes differents.
    const ALLOW_REFERENT_FOR_MULTI_ORGAN = 0; // Permission (1) / interdiction (0)




    /* Gestion des envois d'email de résultats des positionnements */

    // Permet l'envoi du mail de résultats au référent/formateur
    const ENVOI_EMAIL_REFERENT = 1; // Envoi (1) / pas d'envoi (0)

    // Email du responsable du positionnement
    //public static $main_email_admin = "f.rampion@educationetformation.fr";
    public static $main_email_admin = "n.beurion.dev@gmail.com";

    // Tableau des adresse emails des administrateurs pour la réception des positionnements effectués.
    public static $emails_admin = array(
        //"f.rampion@educationetformation.fr",
        //"g.billard@educationetformation.fr", 
        //"n.beurion@educationetformation.fr"
        "n.beurion.dev@gmail.com"
    );



    // Active la gestion des préconisations de parcourts pour les compétences
    const ALLOW_PRECONISATION = 1; // autorisé (1) / non autorisé (0)


    /* Infos de connexion de la base de données*/

    public static $database = array(
        'driver'    =>  "mysql",
        'host'      =>  "127.0.0.1",
        'schema'    =>  "posi_dev",
        'username'  =>  "root",
        'password'  =>  ""
    );



    /* Menu admin */

    public static $admin_menu = array(

        // Partie "Gestion du positionnement" du menu
        array(

            'title' =>"Gestion des données",

            array(
                'code_menu' => "10",
                'label_menu' => "Questions / Réponses",
                'url_menu' => "question",
                'display' => true,
                'droits' => "admin"
            ),
            array(
                'code_menu' => "20",
                'label_menu' => "Compétences / catégories",
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
                'code_menu' => "35",
                'label_menu' => "Degrés d'interprétation des acquis",
                'url_menu' => "validation",
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
                'label_menu' => "Utilisateurs",
                'url_menu' => "utilisateur",
                'display' => true,
                'droits' => "custom,admin"
            ),
    		array(
                'code_menu' => "60",
                'label_menu' => "Organismes",
                'url_menu' => "organisme",
                'display' => true,
                'droits' => "custom,admin"
            ),
            array(
                'code_menu' => "70",
                'label_menu' => "Comptes administrateur",
                'url_menu' => "compte",
                'display' => true,
                'droits' => "admin"
            )
        ),
        
        // Partie "Gestion des résultats" du menu.
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
                'label_menu' => "Statistiques",
                'url_menu' => "statistique",
                'display' => true,
                'droits' => "custom,admin"
            )
        )
    );
    


    /* Gestion du code organisme */

    // code : dalia2013 (à remplacer si besoin).
    public static function getCodeOrganisme()
    {
        $pass = array();
        $pass[0] = Config::hashPassword("dalia2013");
        $pass[1] = Config::hashPassword("nico");
        return $pass;
    }


    // Hashage (encodage) du mot de passe
    const SALT = "#zE'rGr[kj+KtCH£>FjF|fm-76s}T'Yjk<]JDs[{hj,[fbS*"; 

    public static function hashPassword($pass)
    {
        $salt = Config::SALT;
        return sha1($salt.md5($pass.$salt).sha1($salt));
    }

}


?>

