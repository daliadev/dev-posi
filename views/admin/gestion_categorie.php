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
        if (is_array($response['form_data'][$key]) && count($response['form_data'][$key]) > 0)
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


    <div id="content-large">

        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu" style="margin-right:30px">Retour menu</div></a>

        <div style="clear:both;"></div>

        
        <!-- Header -->
        <div id="titre-admin-h2">Administration positionnement - Gestion des catégories</div>


        
        <!-- Partie haute : combo-box question -->

        <div id="main-form">

            <form id="form-posi" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="mode" id="mode" value="<?php //echo $formData['mode']; ?>" />
                <input type="hidden" name="num_ordre_question" value="<?php //echo $formData['num_ordre_question']; ?>" />
                

                <!-- <div class="zone-formu2">
                    
                    <div id="liste_questions" class="form-full">
                            
                            <select name="ref_question_cbox" id="ref_organ_cbox" style="margin: 0px 10px 3px 10px;">
                                <option value="select_cbox">---</option>

                                <?php 
                                /*
                                foreach($response['question'] as $question)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_question']) && $formData['ref_question'] == $question->getId())
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$question->getId().'" '.$selected.'>Question '.$question->getNumeroOrdre().'</option>';
                                }
                                */
                                ?>

                            </select> &nbsp;

                            <input type="submit" name="selection" value="Sélectionner"/>
                        
                    </div>
                </div>

                
                <div style="clear:both"></div> -->
                
                <?php

                    if (isset($response['errors']) && !empty($response['errors']))
                    {
                        echo '<div id="zone-erreur">';
                        echo '<p><strong>Le formulaire n\'est pas correctement rempli :</strong></p>';
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

                
                <!-- Partie gauche : Affichage question en cours -->
                
                <div style="float:left;">

                    <div class="zone-formu2">
     
                        <div class="form-half">

                            <fieldset>
                                
                                <legend>Listing</legend>
                                
                                <div>

                                    <ul id="sortable" style="max-height: 400px; padding: 10px 5px; margin-bottom: 5px; background-color: #f0f0f0; overflow: auto;">
                                    <?php
                                    
                                    foreach($response['categorie'] as $categorie)
                                    {   
                                        
                                        $prefix = '';
                                        $textSize = 14;
                                        $weight = 'normal';

                                        if (strlen($categorie->getCode()) <= 2) {

                                            $prefix = substr($categorie->getCode(), 0, 1);
                                            $textSize = 14;
                                            $weight = 'bold';
                                        }
                                        else if (strlen($categorie->getCode()) <= 4) {

                                            $prefix = substr($categorie->getCode(), 0, 1);
                                            $prefix .= '.'.substr($categorie->getCode(), 2, 1);
                                            $textSize = 13;
                                        }
                                        else
                                        {
                                            $prefix = substr($categorie->getCode(), 0, 1);
                                            $prefix .= '.'.substr($categorie->getCode(), 2, 1);
                                            $prefix .= '.'.substr($categorie->getCode(), 4, 1);
                                            $textSize = 12;
                                        }
                                        
                                        $length = strlen($categorie->getCode()) - 2;
                                        
                                        if ($length < 0)
                                        {
                                            $length = 0;
                                        }
                                        
                                        $styleMargin = 'margin-left:'.($length * 10).'px;';
                                        $style = 'font-size: '.$textSize.'px; font-weight: '.$weight.';';
                                        /*
                                        if ($length <= 0)
                                        {
                                            echo '<option value="'.$categorie->getCode().'">'.$categorie->getNom().'</option>';
                                        }
                                        else
                                        {
                                            echo '<option value="'.$categorie->getCode().'" style="'.$style.'">- '.$categorie->getNom().'</option>';
                                        }
                                        */
                                        echo '<li class="ui-state-default" style="padding: 2px; margin: 2px; '.$styleMargin.'"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span style="'.$style.'">'.$prefix.'- '.$categorie->getNom().'</span></li>';
                                    }
                                    
                                    ?>
                                    </ul>
                                    
                                </div>

                                <!-- Intitulé -->

                                <!-- <div id="intitule">
                                    <label for="intitule_question">Intitulé:</label>
                                    <textarea name="intitule_question" rows="6" maxlength="390" placeholder="380 caractères maximum" <?php //echo $formData['disabled']; ?>><?php //echo $formData['intitule_question']; ?></textarea>
                                </div> -->


                                <!-- Réponses qcm -->

                                <!-- <div class="response-block">
                                    <div id="type_qcm" style="float:left">
                                        <p>
                                            <?php
                                            /*
                                            $checked = "";
                                            if ($formData['type_question'] == "qcm") 
                                            {
                                                $checked = "checked";
                                            }
                                            */
                                            ?>
                                            <input type="radio" id="type-qcm" name="type_question" value="qcm" <?php //echo $checked; ?> <?php //echo $formData['disabled']; ?> /> <span>QCM</span>
                                        </p>
                                    </div>

                                    <div id="intitules_reponses" style="float:right;">
                                        <div id="responses-items">
                                            <?php
                                            /*
                                            $nbReponsesMax = 5;

                                            for ($i = 0; $i < $nbReponsesMax; $i++) 
                                            {
                                                
                                                echo '<p class="response-item">';

                                                if (isset($formData['reponses'][$i]) && !empty($formData['reponses'][$i]))
                                                {
                                                    $checked = "";
                                                    if ($formData['reponses'][$i]['est_correct'] == 1) 
                                                    {
                                                        $checked = "checked";
                                                    }

                                                    if (!empty($formData['reponses'][$i]['ref_reponse']))
                                                    {
                                                        echo '<input type="hidden" name="ref_reponses[]" value="'.$formData['reponses'][$i]['ref_reponse'].'" />';
                                                    }
                                                    else 
                                                    {
                                                        echo '<input type="hidden" name="ref_reponses[]" value="" />';
                                                    }

                                                    echo '<input type="text" name="intitules_reponses[]" value="'.$formData['reponses'][$i]['intitule_reponse'].'" placeholder="Réponse" '.$formData['disabled'].' /> &nbsp;';
                                                    echo '<input type="radio" name="correct" value="'.$formData['reponses'][$i]['num_ordre_reponse'].'" '.$checked.' '.$formData['disabled'].' />';
                                                }
                                                else 
                                                {
                                                    echo '<input type="hidden" name="ref_reponses[]" value="" />';
                                                    echo '<input type="text" name="intitules_reponses[]" value="" placeholder="Réponse" '.$formData['disabled'].' /> &nbsp;';
                                                    echo '<input type="radio" name="correct" value="'.($i + 1).'" '.$formData['disabled'].' />';
                                                }

                                                echo '</p>';
                                            }
                                            */
                                            ?>

                                        </div>
                                    
                                    </div>

                                    <div style="clear:both"></div>
                                </div>
                                <br />

                                <div class="response-block">
                                    <div>
                                        <p>
                                            <?php
                                            /*
                                            $checked = "";
                                            if ($formData['type_question'] == "champ_saisie") 
                                            {
                                                $checked = "checked";
                                            }
                                            */
                                            ?>

                                            <input type="radio" id="type-champ" name="type_question" value="champ_saisie" <?php //echo $checked; ?> <?php //echo $formData['disabled']; ?> /> <span>Réponse ouverte</span>
                                        </p>
                                    </div>

                                </div> -->

                            </fieldset>
                        </div>
                    </div>
                </div>


                <!-- Partie droite : Propriétés question en cours -->

                <div style="float:right;">

                    <!-- Catégories -->
                    <div class="zone-formu2">
                        
                        <div class="form-half">

                            <div id="competences">
                                
                                <fieldset>
                                
                                    <legend>Ajout / détail</legend>
                                        
                                        <div class="input">
                                            <label for="nom_cat">Nom *</label>
                                            <input type="text" name="nom_cat" id="nom_cat" value="<?php echo $formData['nom_cat']; ?>" <?php echo $formData['disabled']; ?> />
                                        </div>
                                        
                                        <div class="input">
                                            <label for="parent_cat_cbox">Catégorie parente *</label>

                                            <select name="parent_cat_cbox" id="ref_parent_cbox">
                                                <option value="select_cbox">---</option>
                                                <?php
                                                
                                                foreach($response['categorie'] as $categorie)
                                                {
                                                    $selected = "";
                                                    if (!empty($formData['code_cat']) && $formData['code_cat'] == $categorie->getCode())
                                                    {
                                                        $selected = "selected";
                                                    }
                                                    
                                                    $length = strlen($categorie->getCode()) - 2;
                                                    
                                                    if ($length < 0)
                                                    {
                                                        $length = 0;
                                                    }

                                                    $style = "padding-left:".($length * 10)."px;";

                                                    if ($length <= 0)
                                                    {
                                                        echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$categorie->getNom().'</option>';
                                                    }
                                                    else
                                                    {
                                                        echo '<option value="'.$categorie->getCode().'" style="'.$style.'" '.$selected.'>- '.$categorie->getNom().'</option>';
                                                    }
                                                }
                                                
                                                ?>
                                            </select>
                                        </div>
                        
                                        <!-- <div id="submit">
                                            <input type="submit" name="selection" value="Sélectionner" />
                                        </div> -->
                                        
                                        <div class="input">
                                            <label for="ordre" style="display: block;">Ordre hiérarchique</label>
                                            <input type="text" name="ordre" id="ordre" value="<?php //echo $formData['ordre']; ?>" <?php echo $formData['disabled']; ?> style="width: 80px;" />
                                        </div>


                                        <div class="input">
                                            <label for="descript_cat">Description</label>
                                            <textarea name="descript_cat" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_cat']; ?></textarea>
                                        </div>
                                                
                                        <div class="input">

                                            <label for="type_lien_cat">Gestion des scores</label><br/>
                                            
                                            <p>Chaque réponse de l'apprenant est liée à une catégorie. Ce score peut dépendre de la catégorie active ou de celles de ses enfants.</p> 
                                            <?php
                                            
                                            $checked = "";
                                            if (isset($formData['type_lien_cat']) && !empty($formData['type_lien_cat']) && $formData['type_lien_cat'] == "dynamic")
                                            {
                                                $checked = "checked";
                                            }
                                            ?>
                                            
                                            <p>
                                                <input type="radio" name="type_lien_cat" id="type_lien_cat" style="float:left;" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                                <label>La catégorie possède son propre score.</label>
                                            </p>
                                            
                                            <p>
                                                <input type="radio" name="type_lien_cat" id="type_lien_cat" style="float:left;" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                                <label>Cette catégorie héritera des scores de ses catégories enfants ainsi que de son propre score.</label> 
                                            </p>

                                            <p>
                                                <input type="radio" name="type_lien_cat" id="type_lien_cat" style="float:left;" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                                <label>Cette catégorie héritera uniquement des scores de ses catégories enfants.</label> 
                                            </p>

                                            <p>
                                                <input type="radio" name="type_lien_cat" id="type_lien_cat" style="float:left;" value="<?php $formData['type_lien_cat']; ?>" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> 
                                                <label>Cette categorie ne possède pas de score.</label> 
                                            </p>

                                        </div>
                                        <!-- 
                                        <div id="submit">
                                            <input type="submit" class="add" name="add" value="Ajouter" <?php //echo $formData['add_disabled']; ?> />
                                        </div> -->

                                    <!-- 
                                    <select id="code_comp_cbox" name="code_cat_cbox" class="select-<?php// echo $formData['disabled']; ?>" style="margin:10px 0;" <?php //echo $formData['disabled']; ?>>
                                        <option value="select_cbox">---</option>
                                        <?php 
                                        /*
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
                                                if ($optgroup)
                                                {
                                                    echo '</optgroup>';
                                                    $optgroup = false;
                                                }

                                                if ($categorie->getTypeLien() == "dynamic")
                                                {
                                                    echo '<optgroup label="'.$categorie->getNom().'">';
                                                    $optgroup = true;
                                                }
                                            }

                                            $length = strlen($categorie->getCode());

                                            if ($optgroup)
                                            {
                                                $length -= 2;
                                                if ($length < 0)
                                                {
                                                    $length = 0;
                                                }
                                            }

                                            $style = "padding-left:".($length * 10)."px;";

                                            if ($length > 0)
                                            {
                                                echo '<option value="'.$categorie->getCode().'" style="'.$style.'" '.$selected.'>- '.$categorie->getNom().'</option>';
                                            }

                                        }

                                        if ($optgroup)
                                        {
                                            echo '</optgroup>';
                                        }
                                        */
                                        ?>
                                        
                                    </select>
                                    -->



                                </fieldset>

                            </div>
                        </div>
                    </div>
                </div>


                <div style="clear:both"></div>


                <!-- Partie basse : Boutons de gestion de la question -->
                <div class="zone-formu2">

                    <div id="buttons" class="form-full">

                        <input type="hidden" name="delete" value="false" />
                        <div class="buttons-block">
                            <input type="submit" id="add" name="add" class="bt-admin-menu-ajout" style="width:160px;" value="Ajouter une question" <?php echo $formData['add_disabled']; ?> />
                            <input type="submit" id="edit" name="edit" class="bt-admin-menu-modif" style="margin-left:108px;" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                        </div>
                        <div class="buttons-block">
                            <input type="submit" id="save" name="save" class="bt-admin-menu-enreg" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                            <input type="submit" id="del" name="del" class="bt-admin-menu-sup" style="margin-left:118px;" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
                        </div>

                    </div>
                </div>

            </form>
        </div>

    
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer_old.php');
        ?>

    </div>
    
    
    <!--  -->
    <!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/lightbox-2.6.min.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/flash_detect.js"></script> -->
    <!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/loader.js"></script> -->

    <script type="text/javascript">
        

        $(function() {




            /*** Gestion de la demande de suppression ***/

            $('#del').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez-vous réellement supprimer cette catégorie ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-posi').submit();
                }
            });
            

            /* Envoi du formulaire avec affichage d'un loader le temps de la sauvegarde */
            
            $('#save').click(function(event) {
                
                $.loader();
            });
            
        });

    </script>
       
       