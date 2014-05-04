<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['code_cat'] = "";
$formData['nom_cat'] = "";
$formData['descript_cat'] = "";
$formData['type_lien_cat'] = "";

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


$form_url = WEBROOT."admin/categorie/";


?>
    
    <div id="content">
        
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
        
        <div style="clear:both;"></div>
        
        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <!--**************************** Formulaire admin gestion catégories *************************************-->

        
        <div id="organisme">
            <div id="zone-formu">

                <div class="titre-form" id="titre-cat">Gestion des catégories</div>

                <form id="form-categorie" action="<?php echo $form_url; ?>" method="POST" name="form_admin_categorie">

                    <div class="formu">

                        <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />
                        <input type="hidden" name="code" value="<?php echo $formData['code_cat']; ?>" />

                        <!-- <div> -->
                            <fieldset>
                                <label for="code_cat_cbox">Liste des catégories :</label>
                                <select name="code_cat_cbox" id="ref_organ_cbox">
                                    <option value="select_cbox">---</option>

                                    <?php
                                    $optgroup = false;

                                    foreach($response['categorie'] as $categorie)
                                    {
                                        $selected = "";

                                        if (!empty($formData['code_cat']) && $formData['code_cat'] == $categorie->getCode())
                                        {
                                            $selected = "selected";
                                        }

                                        if (strlen($categorie->getCode()) == 2)
                                        {
                                            echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$categorie->getNom().'</option>';
                                        }
                                        else if (strlen($categorie->getCode()) == 4)
                                        {
                                            echo '<option value="'.$categorie->getCode().'" style="margin-left:20px" '.$selected.'>- '.$categorie->getNom().'</option>';
                                        }
                                        else if (strlen($categorie->getCode()) == 6)
                                        {
                                            echo '<option value="'.$categorie->getCode().'" style="margin-left:40px" '.$selected.'>- '.$categorie->getNom().'</option>';
                                        }
                                        else if (strlen($categorie->getCode()) == 8)
                                        {
                                            echo '<option value="'.$categorie->getCode().'" style="margin-left:60px" '.$selected.'>- '.$categorie->getNom().'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="">Impossible d\'afficher cette gatégorie</option>';
                                        }

                                        
                                    }

                                    ?>

                                </select> &nbsp;

                                <div id="submit">
                                    <input type="submit" value="Sélectionner" name="valid_form_cat" />
                                </div>
                                <hr>
                            </fieldset>

                        <!-- </div> -->

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

                        <!-- <div> -->
                            <fieldset>
                                
                                <div class="input">
                                    <label for="code_cat">Code * <a target="blank" class="lien-no-underline" rel="lightbox" href="<?php echo SERVER_URL; ?>media/images/code.jpg"><small>Mode d'emploi</small></a></label>
                                    <input type="text" name="code_cat" id="code_cat" value="<?php echo $formData['code_cat']; ?>" <?php echo $formData['disabled']; ?> />
                                </div>
                                
                                <div class="input">
                                    <label for="nom_cat">Nom *</label>
                                    <input type="text" name="nom_cat" id="nom_cat" value="<?php echo $formData['nom_cat']; ?>" <?php echo $formData['disabled']; ?> />
                                </div>
                                
                                <div class="input">
                                    <label for="descript_cat">Description</label>
                                    <textarea name="descript_cat" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_cat']; ?></textarea>
                                </div>
                                
                                <div class="input">
                                    <label for="type_lien_cat">Type de categorie *</label><br/>

                                    <!--
                                    <ul>
                                    -->
                                        <?php
                                        /*
                                        $typeLiens = array(
                                            array("type_lien" => "static", "label" => "Statique"),
                                            array("type_lien" => "dynamic", "label" => "Dynamique"));
                                        
                                        foreach($typeLiens as $typeLien) :
                                            $checked = "";
                                            if ($formData['type_lien_cat'] == $typeLien['type_lien']) :
                                                $checked = "checked";
                                            endif;*/
                                        $checked = "";
                                        if (isset($formData['type_lien_cat']) && !empty($formData['type_lien_cat']) && $formData['type_lien_cat'] == "dynamic")
                                        {
                                            $checked = "checked";
                                        }
                                        ?>

                                        <input type="checkbox" name="type_lien_cat" id="type_lien_cat" style="float:left;" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                        <p class="descript">Si la case est cochée, cette categorie héritera des scores de ses sous-catégories et ne pourra pas posséder de score propre.</p>
                                            
                                        <?php
                                        //endforeach;
                                        ?>
                                    <!--
                                    </ul>
                                    -->
                                </div>
                                
                            </fieldset>
                        <!-- </div> -->
                        <hr>
                        <!-- Boutons de gestion des catégories -->

                        <div id="buttons">
                                <input type="hidden" name="delete" value="false" />
                                <input type="submit" class="add" name="add" style="float: left;" value="Ajouter" <?php echo $formData['add_disabled']; ?> />
                                <input type="submit" class="edit" name="edit" style="float: right;" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                                <input type="submit" class="save" name="save" style="float: left;" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                                <input type="submit" class="del" name="del" style="float: right;" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
                                <div style="clear:both;"></div>
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

            // Demande de suppression
            $('input[name="del"]').click(function(event) {

                $('input[name="delete"]').val("true");
                
                if (confirm("Voulez-vous réellement supprimer ce degré ?"))
                {
                    $('#form-categorie').submit();
                }
            });
        
        });

    </script>
