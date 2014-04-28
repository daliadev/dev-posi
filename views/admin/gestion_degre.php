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


$form_url = WEBROOT."admin/degre/";

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

        <form id="form-degre" action="<?php echo $form_url; ?>" method="POST" name="form_admin_degre">
            <div id="organisme">
                <div id="zone-formu">
                    <div id="ico-utili">Gestion des degrés d'aptitude</div>

                    <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />

                    <div>
                        <fieldset>
                            <label for="ref_degre_cbox">Liste des degrés :</label>
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

                            </select> &nbsp;
                            
                            
                            <input type="submit" name="selection" value="Sélectionner" >
                            <hr>
                        </fieldset>
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
                    
                    <div>
                        <fieldset>
                            <label for="nom_degre">Nom *</label>
                            <input type="text" name="nom_degre" id="nom_degre" value="<?php echo $formData['nom_degre']; ?>" <?php echo $formData['disabled']; ?> />

                            <label for="descript_degre">Description</label>
                            <textarea name="descript_degre" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_degre']; ?></textarea>

                        </fieldset>
                    </div>
                    

                    <!-- Boutons de gestion des compétenecs -->

                    <div id="buttons">
                        <fieldset>
                            <hr>
                            <input type="hidden" name="delete" value="false" />
                            <input type="submit" name="add" value="Ajouter" <?php echo $formData['add_disabled']; ?> />
                            <input type="submit" name="edit" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                            <input type="submit" name="save" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                            <input type="submit" name="del" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
                        </fieldset>
                    </div>



                </div>
            </div>

        </form>
        
        
        <div style="clear:both;"></div>

        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>



    <script type="text/javascript">

        $(function() { 

            // Demande de suppression
            $('input[name="del"]').click(function(event) {

                $('input[name="delete"]').val("true");
                
                if (confirm("Voulez-vous réellement supprimer ce degré ?"))
                {
                    $('#form-degre').submit();
                }
            });
        
        });

    </script>


    
 