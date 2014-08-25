<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['nom_organ'] = "";
$formData['code_postal_organ'] = "";
$formData['tel_organ'] = "";


// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{      
    foreach($response['form_data'] as $key => $value)
    {
        if (is_array($response['form_data'][$key]))
        {
            for ($i = 0; $i < count($response['form_data'][$key]); $i++)
            {
                $formData[$key][$i] = $response['form_data'][$key][$i];
            }
        }
        else 
        {
            $formData[$key] = $value;
        }
    }
}


$form_url = WEBROOT."admin/compte/";

?>
    
    
    
    <div id="content">
        
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
        
        <div style="clear:both;"></div>
        
        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <!--**************************** Formulaire admin gestion des organismes *************************************-->

        
        <div id="utilisateur">
            <div class="zone-formu">

                <div class="titre-form" id="titre-account">Gestion des comptes</div>

                <form id="form-posi" action="<?php echo $form_url; ?>" method="POST" name="form_admin_user">

                    <div class="form-small">

                        <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>">
                        <!-- <input type="hidden" name="ref_account_cbox" value="<?php //echo $formData['ref_account_cbox']; ?>"> -->

                        
                        <div class="input">
                            <label for="ref_account_cbox">Liste des administrateurs :</label>
                            <select name="ref_account_cbox" id="ref_account_cbox">
                                <option value="select_cbox">---</option>

                                <?php
                                
                                foreach($response['compte'] as $compte)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_account']) && $formData['ref_account'] == $compte->getId())
                                    {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.$compte->getId().'" '.$selected.'>'.$compte->getNom().'</option>';
                                }
                                
                                ?>

                            </select>
                        </div>

                        <div id="submit">    
                            <input type="submit" name="selection" value="Sélectionner">
                        </div>


                        <hr>


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
                        else if (isset($response['success']) && !empty($response['success']))
                        {
                            echo '<div id="zone-success">';
                            echo '<ul>';
                            foreach($response['success'] as $message)
                            {
                                if ($message)
                                {
                                    echo '<li>'.$message.'</li>';
                                }
                            }
                            echo '</ul>';
                            echo '</div>';
                        }

                        ?>


                        <?php //$disabled = ($formData['mode'] == "edit") ? "disabled" : $formData['disabled']; ?>
                                
                        <div class="input">
                            <label for="nom_admin">Nom d'utilisateur <span class="asterix">*</span></label>
                            <input type="text" name="nom_admin" id="nom_admin" value="<?php echo $formData['nom_admin']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>
                        <!-- 
                        <div class="input">
                            <label for="email">Adresse email <span class="asterix">*</span></label>
                            <input type="text" name="email" id="email" value="<?php //echo $formData['email']; ?>" <?php //echo $formData['disabled']; ?>>
                        </div>
                         -->

                        <?php if ($formData['mode'] == "new" || $formData['mode'] == "edit") : ?>

                            <?php $title = ($formData['mode'] == "edit") ? "Nouveau mot de passe " : "Mot de passe "; ?>

                            <div class="input">
                                <label for="pass_admin"><?php echo $title; ?> <span class="asterix">*</span></label>
                                <input type="password" name="pass_admin" id="pass_admin" value="<?php echo $formData['pass_admin']; ?>" <?php echo $formData['disabled']; ?>>
                            </div>

                            <div class="input">
                                <label for="pass_admin_verif">Confirmation du mot de passe <span class="asterix">*</span></label>
                                <input type="password" name="pass_admin_verif" id="pass_admin_verif" value="<?php echo $formData['pass_admin_verif']; ?>" <?php echo $formData['disabled']; ?>>
                            </div>

                        <?php endif; ?>

                        <!-- <div class="input">
                            <label for="droits">Droits<span class="asterix">*</span></label>
                            <input type="text" name="droits" id="droits" value="<?php //echo $formData['droits']; ?>" <?php //echo $formData['disabled']; ?>>
                        </div> -->

                        <div class="input">
                            <label for="droits_cbox">Droits<span class="asterix">*</span></label>
                            <select name="droits_cbox" id="droits_cbox" <?php echo $formData['disabled']; ?>>
                                <option value="select_cbox">---</option>
                                <?php

                                $selected1 = (isset($formData['droits']) && $formData['droits'] == "admin") ? "selected" : "";
                                $selected2 = (isset($formData['droits']) && $formData['droits'] == "custom") ? "selected" : "";

                                ?>
                                <option value="admin" <?php echo $selected1; ?>>Compte administrateur</option>
                                <option value="custom" <?php echo $selected2; ?>>Compte limité</option>

                            </select>
                        </div>

                        <hr>


                        <!-- Boutons de gestion des utilisateurs -->

                        <div id="buttons">
                                <input type="hidden" name="delete" value="false">
                                <input type="submit" class="add" name="add" style="float:left;" value="Ajouter" <?php echo $formData['add_disabled']; ?>>
                                <input type="submit" class="edit" name="edit" style="float:right;" value="Modifier" <?php echo $formData['edit_disabled']; ?>>
                                <input type="submit" class="save" name="save" style="float:left;" value="Enregistrer" <?php echo $formData['save_disabled']; ?>>
                                <input type="submit" class="del" name="del" style="float:right;" value="Supprimer" <?php echo $formData['delete_disabled']; ?>>      
                        </div>
                        <div style="clear:both;"></div>

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


    <script type="text/javascript">

        
        $(function() { 

            /*** Gestion de la demande de suppression ***/

            $('input[name="del"]').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez êtes sur le point de supprimer définitivement un compte administrateur. Voulez-vous continuer ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-posi').submit();
                }
            });
        
        });

    </script>


    
 