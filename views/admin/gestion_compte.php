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

                <div class="titre-form" id="titre-utili">Gestion des comptes</div>

                <form id="form-posi" action="<?php echo $form_url; ?>" method="POST" name="form_admin_user">

                    <div class="form-small">

                        <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>">
                        <input type="hidden" name="ref_user_admin" value="<?php //echo $formData['ref_user_admin']; ?>">

                        
                        <div class="input">
                            <label for="ref_user_admin_cbox">Liste des administrateurs :</label>
                            <select name="ref_user_admin_cbox" id="ref_user_admin_cbox">
                                <option value="select_cbox">---</option>

                                <?php
                                
                                foreach($response['compte'] as $compte)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_organ']) && $formData['ref_organ'] == $compte->getId())
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


                                
                        <div class="input">
                            <label for="nom_admin">Nom d'utilisateur <span class="asterix">*</span></label>
                            <input type="text" name="nom_admin" id="nom_admin" value="<?php echo $formData['nom_admin']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>
                        <!-- 
                        <div class="input">
                            <label for="email">Adresse email <span class="asterix">*</span></label>
                            <input type="text" name="email" id="email" value="<?php //echo $formData['email']; ?>" <?php  echo $formData['disabled']; ?>>
                        </div>
                         -->

                        <?php if ($formData['disabled'] != "disabled") : ?>
                            <div class="input">
                            <label for="pass_admin">Mot de passe <span class="asterix">*</span></label>
                            <input type="text" name="pass_admin" id="pass_admin" value="<?php //echo $formData['pass_admin']; ?>" <?php echo $formData['disabled']; ?>>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($formData['disabled'] != "disabled") : ?>
                            <div class="input">
                                <label for="pass_admin_verif">Confirmation du mot de passe <span class="asterix">*</span></label>
                                <input type="text" name="pass_admin_verif" id="pass_admin_verif" value="<?php //echo $formData['mdp_verif']; ?>" <?php echo $formData['disabled']; ?>>
                            </div>
                        <?php endif; ?>

                        <div class="input">
                            <label for="droits">Droits<span class="asterix">*</span></label>
                            <input type="text" name="droits" id="droits" value="<?php echo $formData['droits']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>

                        <hr>


                        <!-- Boutons de gestion des utilisateurs -->

                        <div id="buttons">
                                <input type="hidden" name="delete" value="false">
                                <input type="submit" class="add" name="add" style="float:left;" value="Ajouter" <?php //echo $formData['add_disabled']; ?>>
                                <input type="submit" class="edit" name="edit" style="float:right;" value="Modifier" <?php //echo $formData['edit_disabled']; ?>>
                                <input type="submit" class="save" name="save" style="float:left;" value="Enregistrer" <?php //echo $formData['save_disabled']; ?>>
                                <input type="submit" class="del" name="del" style="float:right;" value="Supprimer" <?php //echo $formData['delete_disabled']; ?>>      
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


    
 