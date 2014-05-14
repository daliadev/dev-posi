<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['ref_degre'] = "";

$formData['nom_degre'] = "";
$formData['descript_degre'] = "";


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


$form_url = WEBROOT."admin/gestion_organisme/";

//var_dump($response);
?>
    
    
    
    <div id="content">
        
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
        
        <div style="clear:both;"></div>
        
        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
    
    
        <!--******************** Formulaire admin gestion degrés **********************************-->

        
        <div id="organisme">
            <div class="zone-formu">

                <div class="titre-form" id="titre-cat">Gestion des organismes</div>

                <form id="form-degre" action="<?php echo $form_url; ?>" method="POST" name="form_admin_degre">

                    <div class="form-small">

                        <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />

                        <div class="input">
                            <label for="ref_degre_cbox">Liste des organismes :</label>
                            <select name="ref_degre_cbox" id="ref_degre_cbox">
                                <option value="select_cbox">---</option>

                                <?php 
                                foreach($response['degre'] as $degre)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_degre']) && $formData['ref_degre'] == $degre->getId())
                                    {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.$degre->getId().'" '.$selected.'>'.$degre->getNom().'</option>';
                                }

                                ?>

                            </select>
                        </div>

                        <div id="submit">    
                            <input type="submit" name="selection" value="Sélectionner" >
                        </div>


                        <hr/>
                        

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
                            <label for="nom_degre">Nom organisme *</label>
                            <input type="text" name="nom_degre" id="nom_degre" value="<?php echo $formData['nom_degre']; ?>" <?php echo $formData['disabled']; ?> />
                        </div>
                        <!--<div class="input">
                            <label for="descript_degre">Description</label>
                            <textarea name="descript_degre" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_degre']; ?></textarea>
                        </div>-->
                        

                        <hr/>

                        <!-- Boutons de gestion des degrés -->

                        <div id="buttons">
                            <input type="hidden" name="delete" value="false" />
                            <input type="submit" name="add" value="Ajouter" <?php echo $formData['add_disabled']; ?> />
                            <input type="submit" name="edit" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                            <input type="submit" name="save" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                            <input type="submit" name="del" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
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



    <script type="text/javascript">
        
        $(function() { 

            /*** Gestion de la demande de suppression ***/

            $('input[name="del"]').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez-vous réellement supprimer ce degré ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-degre').submit();
                }
            });
        
        });

    </script>


    
 