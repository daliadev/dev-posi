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
    
    <style>
        
        #zone-formu label 
        {
            margin-top: 10px;
        }
        
        #zone-formu select
        {
            padding: 5px;
        }
        
        
        #zone-formu textarea 
        {
            height: 90px;
            padding: 5px;
            margin-top: 5px;
            width: 280px;
            border: 1px solid #c3c9cf;
            border-radius: 5px 5px 5px 5px;
        }
		
        
        #zone-formu input 
        {
            height: 20px;
            line-height: 20px;
            padding: 5px;
            margin-top: 5px;
            width: 280px;
            border: 1px solid #c3c9cf;
            border-radius: 5px 5px 5px 5px;
        }
        
        
        #zone-formu input[type=text]
        {
            height: 20px;
            line-height: 20px;
            padding: 5px;
        }
        
        
        #zone-formu input[type=radio]
        {
            width: 30px;
            height: 30px;
            padding: 0;
            line-height: 15px;
            margin: 0;
        }
        
        
        #zone-formu ul
        {
            list-style: none;
        }
            
            #zone-formu li
            {
                margin-left: -30px;
                /* height: 30px;
                line-height: 0px; */
            }
        
            
        #zone-formu input[type=submit] 
        {
            cursor: pointer;
            background-color: #f39c12;
            color: #fff;
            width: 143px;
            height: 30px;
            line-height: 20px;
            padding: 5px;
            margin-top: 8px;
            margin-bottom :8px;
            border: 0px;
            border-radius: 0px;
            font-size: 14px;
        }
        
        #zone-formu input[type="submit"][disabled]
        {
            background-color: #bdc3c7;
            cursor: default;  	
        }
        
        #zone-formu input[type="text"][disabled]
        {
            background-color: #f0f0f0;
            color: #797d80;
            cursor: default;
        }
        
        #zone-formu textarea[disabled]
        {
            color: #797d80;
        }
        
        #zone-formu input[name='selection'] { background: #f39c12; margin-left: 25%;}
        
        #buttons input[name='add'] { background: #f39c12;}
        #buttons input[name='save'] { background: #3498db;}
        #buttons input[name='edit'] { background: #1fbba6;}
        #buttons input[name='del'] { background: #f75556;}
        
        
        
    </style>
    
    
    
    <div id="content">
        
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
        
        <div style="clear:both;"></div>
        
        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <!--**************************** Formulaire admin gestion catégories *************************************-->

        

        <form id="form-categorie" action="<?php echo $form_url; ?>" method="POST" name="form_admin_categorie">
            <div id="organisme">
                <div id="zone-formu">
                    <div id="ico-utili">Gestion des catégories</div>

                    <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />
                    <input type="hidden" name="code" value="<?php echo $formData['code_cat']; ?>" />

                    <div>
                        <fieldset>
                            <label for="code_cat_cbox">Liste des catégories :</label>
                            <select name="code_cat_cbox" id="ref_organ_cbox">
                                <option value="select_cbox">---</option>

                                <?php 
                                foreach($response['categorie'] as $categorie)
                                {
                                    $selected = "";
                                    if (!empty($formData['code_cat']) && $formData['code_cat'] == $categorie->getCode())
                                    {
                                        $selected = "selected";
                                    }

                                    if (strlen($categorie->getCode()) == 4)
                                    {
                                        echo '<option value="'.$categorie->getCode().'" '.$selected.'> &nbsp; - '.$categorie->getNom().'</option>';
                                    }
                                    else if (strlen($categorie->getCode()) == 6)
                                    {
                                        echo '<option value="'.$categorie->getCode().'" '.$selected.'> &nbsp; &nbsp; &nbsp; - '.$categorie->getNom().'</option>';
                                    }
                                    else 
                                    {
                                        echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$categorie->getNom().'</option>';
                                    }
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
                            
                            <div class="input">
                                <label for="code_cat">Code * <a target="blank" class="lien-no-underline" rel="lightbox" href="<?php echo SERVER_URL; ?>media/images/code.jpg">"explication utilisation code"</a></label>
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
                                <label for="type_lien_cat">Type de categorie *</label>
                                <!-- </div>
                                <div class="input"> -->
                                
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
                                        <input type="checkbox" name="type_lien_cat" id="type_lien_cat" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                        <p>Si la case est cochée, cette categorie héritera des scores de ses sous-catégories et ne pourra pas posséder de score propre.</p>
                                        
                                    <?php
                                    //endforeach;
                                    ?>
                                <!--
                                </ul>
                                -->
                            </div>
                            
                        </fieldset>
                    </div>
                    
                    <!-- Boutons de gestion des catégories -->

                    <div id="buttons">
                        <fieldset>
                            <hr>
                            <input type="hidden" name="delete" value="false" />
                            <input type="submit" name="add"  value="Ajouter" <?php echo $formData['add_disabled']; ?> />
                            <input type="submit" name="edit"  value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                            <input type="submit" name="save"  value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
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
                    $('#form-categorie').submit();
                }
            });
        
        });

    </script>
