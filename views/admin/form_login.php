<?php

// url vers laquel doit pointer le formulaire
$form_url = WEBROOT."admin/login";

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['nom_admin'] = "";
$formData['pass_admin'] = "";

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
    foreach($response['form_data'] as $key => $value)
    {
        $formData[$key] = $value;
    }
}



?>


    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
<!--*************************** Formulaire login admin **************************************-->


        <form action="<?php echo $form_url; ?>" method="POST" name="form_login_admin">
         
        <div id="administrateur-login">
            <div class="zone-formu">

                <div class="titre-form" id="titre-admin">Connexion administrateur</div>

                <form action="<?php echo $form_url; ?>" method="POST" name="form_login_admin">

                    <div class="form-small">
                        
                        <div class="input">
                            <label for="Login">Login *</label>
                            <input type="text" name="login" id="login" value="" required />
                        </div>

                        <div class="input">
                            <label for="password">Mot de passe *</label>
                            <input type="password" name="password" id="password" value="" required />
                        </div>

                        <?php

                        if (isset($response['errors']) && !empty($response['errors']))
                        {
                            echo '<div id="zone-erreur">';
                            echo '<ul>';
                            foreach($response['errors'] as $error)
                            {
                                if ($error['type'] == "form_valid" || $error['type'] == "form_empty")
                                {
                                    echo '<li>'.$error['message'].'</li>';
                                }
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
                        
                        <div id="submit">
                            <input type="submit" value="Envoyer" onclick="verifAdmin();" />
                        </div>

                    </div>

                </form>

            </div>
        </div>

        
    

        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>


    <script language="javascript" type="text/javascript">
        
        function verifAdmin()
        {
            if (window.navigator.appName == 'Microsoft Internet Explorer')
            {
                var login = document.formulaire.login;
                if (login.value == "")
                {
                    alert ('Veuillez entrer votre login');
                    login.focus();
                    return false;
                }

                var mdp = document.formulaire.password;
                if (mdp.value == "")
                {
                    alert ('Veuillez entrer votre mot de passe');
                    mdp.focus();
                    return false;
                }
            }
        }
        
        $(function() { 
            
        });   

    </script>



    