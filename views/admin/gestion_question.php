<?php


// Initialisation par défaut des valeurs du formulaire
$formData = array();

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


$form_url = WEBROOT."admin/question/";

?>


    <div id="content-large">

        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu" style="margin-right:30px">Retour menu</div></a>

        <div style="clear:both;"></div>

        
        <!-- Header -->
        <div id="titre-admin-h2">Administration positionnement - Gestion des questions</div>


        
        <!-- Partie haute : combo-box question -->

        <div id="main-form">

            <form id="form-posi" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="mode" id="mode" value="<?php echo $formData['mode']; ?>" />
                <input type="hidden" name="num_ordre_question" value="<?php echo $formData['num_ordre_question']; ?>" />
                

                <!-- <div class="bg-form"> -->

                <div class="zone-formu2">
                    <!-- <div id="liste_questions" class="zone-chx-question"> -->
                    
                    <div id="liste_questions" class="form-full">
                            
                            <select name="ref_question_cbox" id="ref_organ_cbox" style="margin: 0px 10px 3px 10px;">
                                <option value="select_cbox">---</option>

                                <?php 

                                foreach($response['question'] as $question)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_question']) && $formData['ref_question'] == $question->getId())
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$question->getId().'" '.$selected.'>Question '.$question->getNumeroOrdre().'</option>';
                                }

                                ?>

                            </select> &nbsp;

                            <input type="submit" name="selection" value="Sélectionner"/>
                        
                    </div>
                </div>

                
                <div style="clear:both"></div>
                
                <?php

                    if (isset($response['errors']) && !empty($response['errors']))
                    {
                        echo '<div id="zone-erreur">';
                        echo '<p><strong>Une erreur s\'est produite :</strong></p>';
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
                                
                                <legend>Question
        
                                    <div id="num_ordre">

                                        <?php
                                        $numOrdre = $formData['num_ordre_question'];

                                        if (!empty($numOrdre))
                                        {
                                            echo $numOrdre;
                                        }
                                        else 
                                        {
                                            echo "-"; 
                                        } 
                                        ?>              

                                    </div>

                                </legend>

                                <!-- Intitulé -->

                                <div id="intitule">
                                    <label for="intitule_question">Intitulé:</label>
                                    <textarea name="intitule_question" rows="6" maxlength="390" placeholder="380 caractères maximum" <?php echo $formData['disabled']; ?>><?php echo $formData['intitule_question']; ?></textarea>
                                </div>


                                <!-- Réponses qcm -->

                                <div class="response-block">
                                    <div id="type_qcm" style="float:left">
                                        <p>
                                            <?php
                                            $checked = "";
                                            if ($formData['type_question'] == "qcm") 
                                            {
                                                $checked = "checked";
                                            }
                                            ?>
                                            <input type="radio" id="type-qcm" name="type_question" value="qcm" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> <span>QCM</span>
                                        </p>
                                    </div>

                                    <div id="intitules_reponses" style="float:right;">
                                        <div id="responses-items">
                                            <?php

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

                                            ?>

                                        </div>
                                    
                                    </div>

                                    <div style="clear:both"></div>
                                </div>
                                <br />


                                <!-- Réponses champ saisie -->

                                <div class="response-block">
                                    <div>
                                        <p>
                                            <?php
                                            $checked = "";
                                            if ($formData['type_question'] == "champ_saisie") 
                                            {
                                                $checked = "checked";
                                            }
                                            ?>
                                            <input type="radio" id="type-champ" name="type_question" value="champ_saisie" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> <span>Réponse ouverte</span>
                                        </p>
                                    </div>

                                </div>

                            </fieldset>
                        </div>
                    </div>


                    <!-- Médias -->

                    <div class="zone-formu2">
                        
                        <div class="form-half">
                            
                            <div id="medias">

                                <fieldset>
                                
                                    <legend>Medias</legend>

                                    <div id="image-question">
                                        <strong>Image :</strong>
                                        <p>

                                        <?php if ($formData['image_question']) : $imageName = $formData['image_question']; ?>

                                            <strong>Fichier image : </strong>
                                                <label id="image_label" for="image_file"><?php echo $imageName; ?></label> &nbsp; 
                                                
                                                <?php if($formData['mode'] == "edit") : ?>
                                                    <a id="del_image" class="del-media" name="del_image" href="#medias" <?php echo $formData['disabled']; ?>>Supprimer</a> <br/>
                                                <?php endif; ?>
                                            
                                            <div id="image-thumb">
                                                <a rel="lightbox" href="<?php echo WEBROOT.IMG_PATH.$imageName; ?>">
                                                    <img src="<?php echo WEBROOT.THUMBS_PATH; ?>thumb_<?php echo $imageName; ?>">
                                                </a>
                                            </div>

                                            <input type="file" id="image_file" name="image_file" accept="image/*" <?php echo $formData['disabled']; ?>>
                                            <input type="button" id="reset_image" class="reset-button" name="reset_image" value="" <?php echo $formData['disabled']; ?>>
                                            <input type="hidden" id="image_cache" name="image_question" value="<?php echo $imageName; ?>">
                                            <div style="clear:both;"></div>
                                            
                                        <?php else : ?>

                                            <label for="image_file">Sélectionner un fichier image (format "jpeg")</label><br/>

                                            <input type="file" id="image_file" name="image_file" accept="image/*" <?php echo $formData['disabled']; ?>>
                                            <input type="button" id="reset_image" class="reset-button" name="reset_image" value="" <?php echo $formData['disabled']; ?>>
                                            <div style="clear:both;"></div>

                                        <?php endif; ?>
                                        </p>
                                    </div>

                                    <hr/>

                                    <div id="audio-question">
                                        <strong>Son :</strong>
                                        <p>

                                        <?php if ($formData['audio_question']) : $audioName = $formData['audio_question']; ?>
                                            <strong>Fichier audio : </strong>
                                                <label id="audio_label" for="audio_file"><?php echo $audioName; ?></label> &nbsp;
                                                
                                                <?php if($formData['mode'] == "edit") : ?>
                                                    <a id="del_audio" class="del-audio" name="del_audio" href="#medias" <?php echo $formData['disabled']; ?>>Supprimer</a> <br/>
                                                <?php endif; ?>

                                            <?php if (Config::ALLOW_AUDIO) : ?>
                                                <div id="audio-player">
                                                    <object type="application/x-shockwave-flash" data="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="160" height="20" id="dewplayer" name="dewplayer"> 
                                                    <param name="wmode" value="transparent" />
                                                    <param name="movie" value="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" /> 
                                                    <param name="flashvars" value="mp3=<?php echo SERVER_URL; ?>uploads/audio/<?php echo $audioName; ?>&amp;autostart=0&amp;nopointer=1&amp;javascript=on" />
                                                    <param name="wmode" value="transparent" />
                                                    </object>
                                                </div>
                                            <?php endif; ?>

                                            <input type="file" id="audio_file" name="audio_file" accept="audio/*" <?php echo $formData['disabled']; ?>>
                                            <input type="button" id="reset_audio" class="reset-button" name="reset_audio" value="" <?php echo $formData['disabled']; ?>>
                                            <input type="hidden" id="audio_cache" name="audio_question" value="<?php echo $audioName; ?>">
                                            <div style="clear:both;"></div>
                                            
                                        <?php else : ?>

                                            <label for="audio_file">Sélectionner un fichier audio (format "mp3")</label><br/>

                                            <input type="file" id="audio_file" name="audio_file" accept="audio/*" <?php echo $formData['disabled']; ?>>
                                            <input type="button" id="reset_audio" class="reset-button" name="reset_audio" value="" <?php echo $formData['disabled']; ?>>
                                            <div style="clear:both;"></div>

                                        <?php endif; ?>
                                        </p>
                                        
                                    </div>

                                </fieldset>

                            </div>      
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
                                
                                    <legend>Catégories / compétences</legend>

                                    <select id="code_comp_cbox" name="code_cat_cbox" class="select-<?php echo $formData['disabled']; ?>" style="margin:10px 0;" <?php echo $formData['disabled']; ?>>
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

                                        ?>
                                    </select>

                                </fieldset>

                            </div>
                        </div>
                    </div>


                    <!-- Degrés d'aptitude -->
                
                    <div class="zone-formu2">
                        
                        <div class="form-half"> 

                            <div id="degres">

                                <fieldset>

                                    <legend>Degrés d'aptitude (facultatif)</legend>

                                    <?php 

                                    $isChecked = false;
                                    $checked = "";

                                    if (isset($response['degre']) && !empty($response['degre']))
                                    {
                                        foreach($response['degre'] as $degre)
                                        {
                                            $checked = "";

                                            if (!empty($formData['ref_degre']) && $formData['ref_degre'] == $degre->getId() && $formData['ref_degre'] != "aucun")
                                            {
                                                $checked = "checked";
                                                $isChecked = true;
                                            }
                                            echo '<p>';
                                            echo '<input type="radio" name="ref_degre" class="radio_degre" value="'.$degre->getId().'" title="'.$degre->getDescription().'" '.$checked.' '.$formData['disabled'].' /> <span class="checkbox-'.$formData['disabled'].'">'.$degre->getNom().'</span>';
                                            echo '</p>';
                                        }

                                    }
                                    
                                    if (!$isChecked) 
                                    {
                                        $checked = "checked";
                                    }
                                    else
                                    {
                                         $checked = "";
                                    }

                                    ?>
                                    
                                    <p>
                                        <input type="radio" name="ref_degre" class="radio_degre" value="aucun" title="" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> <span class="checkbox-<?php echo $formData['disabled']; ?>">Aucun</span>
                                    </p>

                                    <!-- <p><input type="button" class="bt-admin-simple-button" name="remove-degrees" <?php //echo $formData['disabled']; ?> value="Tout déselectionner" /></p> -->
                                    
                                </fieldset>

                            </div>

                        </div>

                    </div>
                    


                
                    <!-- Activités (Préconisation de parcours) -->

                    <?php
                    if (Config::ALLOW_ACTIVITES):
                    ?>
                        <div class="formdiv" id="activites">
                            <fieldset>
                                <div id="titre-question-h3">Activités</div>
                                <div id="ref_activites" class="datalist">
                                    <p>
                                        <input type="checkbox" name="ref_activite" value="1" disabled /><span class="checkbox-<?php echo $formData['disabled']; ?>"> Aéroport - Prendre un billet</span>
                                    </p>
                                    <p>
                                        <input type="checkbox" name="ref_activite" value="2" disabled /><span class="checkbox-<?php echo $formData['disabled']; ?>"> Aéroport - Embarquer</span>
                                    </p>
                                    <p>
                                        <input type="checkbox" name="ref_activite" value="3" disabled /><span class="checkbox-<?php echo $formData['disabled']; ?>"> Aéroport - Repérage et fiches de douane</span>
                                    </p>
                                    <p>
                                        <input type="checkbox" name="ref_activite" value="4" disabled /><span class="checkbox-<?php echo $formData['disabled']; ?>"> Se déplacer en bus</span>
                                    </p>
                                    <p>
                                        <input type="checkbox" name="ref_activite" value="5" disabled /><span class="checkbox-<?php echo $formData['disabled']; ?>"> Préparer son trajet Rouen-Lyon</span>
                                    </p>

                                </div>
                            </fieldset>
                        </div>
                    }
                    <?php
                    endif;
                    ?>

                </div>


                <div style="clear:both"></div>


                <!-- Partie basse : Boutons de gestion de la question -->
                <div class="zone-formu2">

                    <div id="buttons" class="form-full">

                        <input type="hidden" name="delete" value="false" />
                        <div class="buttons-block">
                            <input type="submit" name="add" class="bt-admin-menu-ajout" style="width:160px;" value="Ajouter une question" <?php echo $formData['add_disabled']; ?> />
                            <input type="submit" name="edit" class="bt-admin-menu-modif" style="margin-left:108px;" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                        </div>
                        <div class="buttons-block">
                            <input type="submit" name="save" class="bt-admin-menu-enreg" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                            <input type="submit" name="del" class="bt-admin-menu-sup" style="margin-left:118px;" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
                        </div>

                    </div>
                </div>

            </form>
        </div>

    
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>
    
    



    <script type="text/javascript">
        

        $(function() { 
   

            /*** Ajout de réponse automatique ***/
            /*
            var responseItem = $('#responses-items').first().html();

            $(responseItem + ' input[type=text]').each(function() {

                $(this).val("");
            });
            

            $("#add_response").click(function() {
                    
                $("#responses-items").append(responseItem);
            });

            $("#delete_response").click(function() {

                if ($(".response-item").length > 1)
                {
                    $(".response-item:last").remove();
                }
                else
                {
                    $(".response-item > input[type=text]").val("");
                }
            });
            */


            /*** Tableau des éléments du cache des réponses ***/

            var cacheInputs = new Array();


            /*** Verrouillage initiale des questions du "qcm" ***/
            $mode = $("#mode").val();

            if ($mode !== 'edit')
            {
                $('#responses-items').find('input').each(function() {
                    $(this).prop('disabled', true);
                });
            }
            


            /*** Gestion du clic sur le type "qcm" ***/

            $('#type-qcm').click(function(event) {

                // Pour tous les input des réponses
                var tabInputs = $('#responses-items').find('input');

                var i = 0;
                tabInputs.each(function() {

                    var input = $(this);

                    // On réactive tous les champs et les radio buttons s'ils étaient bloqués
                    input.prop('disabled', false);

                    if (cacheInputs.length > 0) {

                        input.val(cacheInputs[i].value);

                        if (cacheInputs[i].type == "radio") {

                            if (cacheInputs[i].check == true) {
                                input.prop('checked', true);
                            }
                        }
                    }

                    i++;
                });
                
            });
            


            /*** Gestion du clic sur le type "champ-saisi" ***/

            $('#type-champ').click(function(event) {

                cacheInputs = new Array();

                // Pour tous les champs input
                var tabInputs = $('#responses-items').find('input');

                // On vide les réponses (on garde le contenu en cache ?)
                tabInputs.each(function() {

                    var input = $(this);
                    var value = null;

                    // On met en cache toutes les valeurs des inputs
                    if (input.attr('type') == "text") {

                        if (input.prop('value') != "" || input.prop('value') != undefined) {

                            value = input.prop('value');
                        }
                        else
                        {
                            value = input.val();
                        }
                    }
                    else {

                        value = input.val();
                    }
                    

                    var checked = false;
                    
                    if (input.attr('type') == "radio" && input.prop('checked')) {

                        checked = true;
                    }

                    // On met en cache la type, la valeur et la sélection de la réponse correcte
                    cacheInputs.push({type: input.attr("type"), value: value, check: checked});


                    // On efface tous les champs
                    input.val("");

                    // On désactive tous les champs et les radio buttons
                    input.prop('disabled', true);
 
                    if (input.attr('type') == "radio") {

                        input.removeProp('checked');
                    }
                });
            });
            
            


            /*** Gestion de la partie médias  ***/
            
            // Actualisation du téléchargement d'image
            var inputImageFile = $("#image_file").clone(true);

            $('#reset_image').click(function(event) {
                $("#image_file").replaceWith(inputImageFile);
                inputImageFile = $("#image_file").clone(true);
            });


            // Suppression de l'image
            var imageCacheValue = $("#image_cache").val();
            var imageName = $("#image_label").text();

            $("#del_image").click(function(event) {

                if ($(this).text() == "Supprimer") {

                    $("#image_cache").val("");
                    $("#image_label").text("");
                    $("#image-thumb").hide();
                    $("#image_file").prop("disabled", true);
                    $('#reset_image').prop("disabled", true);
                    $(this).text("Annuler la suppression");
                }
                else {

                    $("#image_cache").val(imageCacheValue);
                    $("#image_label").text(imageName);
                    $("#image-thumb").show();
                    $("#image_file").prop("disabled", false);
                    $('#reset_image').prop("disabled", false);
                    $(this).text("Supprimer");
                }

            });


            // Actualisation du téléchargement de l'audio
            var inputAudioFile = $("#audio_file").clone(true);

            $('#reset_audio').click(function(event) {
                $("#audio_file").replaceWith(inputAudioFile);
                inputAudioFile = $("#audio_file").clone(true);
            });


            // Suppression de l'audio
            var audioCacheValue = $("#image_cache").val();
            var audioName = $("#image_label").text();

            $("#del_audio").click(function(event) {

                if ($(this).text() == "Supprimer") {

                    $("#audio_cache").val("");
                    $("#audio_label").text("");
                    $("#audio-player").hide();
                    $("#audio_file").prop("disabled", true);
                    $('#reset_audio').prop("disabled", true);
                    $(this).text("Annuler la suppression");
                }
                else {

                    $("#audio_cache").val(audioCacheValue);
                    $("#audio_label").text(audioName);
                    $("#audio-player").show();
                    $("#audio_file").prop("disabled", false);
                    $('#reset_audio').prop("disabled", false);
                    $(this).text("Supprimer");
                }
            });





            /*** Gestion de la demande de suppression ***/

            $('.bt-admin-menu-sup').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez-vous réellement supprimer cette question ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-posi').submit();
                }
            });
            
        });

    </script>
       
       