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


if (Config::DEBUG_MODE)
{
    
    if (isset($response['errors']) && !empty($response['errors']))
    {
        echo "\$response['errors'] = ";
        var_dump($response['errors']);
    }
    
    //echo "\$response = ";
    //var_dump($response);
    
    //echo "\$formdata = ";
    //var_dump($formData);
}

$form_url = WEBROOT."admin/question/";


?>


    <style type="text/css">
        
        #image-question, #audio-question {
            padding: 0px;
            margin: 20px 0px;
        }
            
    </style>

    <div id="content-large">

        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>

        <div style="clear:both;"></div>

        
        <!-- Header -->
        <div id="titre-admin-h2">Administration positionnement - Gestion des questions</div>


        
        <!-- Partie haute : combo-box question -->

        <div>

            <form id="form-question" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

                <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />
                <input type="hidden" name="num_ordre_question" value="<?php echo $formData['num_ordre_question']; ?>" />

                <div id="liste_questions" class="zone-chx-question">
                    <fieldset>
                        <select name="ref_question_cbox" id="ref_organ_cbox">
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


                        <input type="submit" name="selection" value="Sélectionner" class="bt-admin-menu-ajout2" /> &nbsp;

                        <input type="submit" name="edit" class="bt-admin-menu-modif-haut" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                        <input type="submit" name="save" class="bt-admin-menu-enreg-haut" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />

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

                    </fieldset>
                </div>


                

                
                <!-- Partie gauche : Affichage question en cours -->
                
                <div class="part-left">
                    <div class="formdiv">
                        <fieldset>
                            <div>
                                <div id="titre-question-h3">Question
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
                                </div>
                            </div>

                            <div id="intitule">
                                <p>
                                    <textarea name="intitule_question"  cols="62" rows="6" maxlength="391" placeholder="480 caractères maximum" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?>><?php echo $formData['intitule_question']; ?></textarea>
                                </p>
                            </div>

                            <div id="response-qcm" style="background:#ECF0F1; padding:10px">
                                <div id="type_qcm" style="float:left">
                                    <p>
                                        <?php
                                        $checked = "";
                                        if ($formData['type_question'] == "qcm") 
                                        {
                                            $checked = "checked";
                                        }
                                        ?>
                                        <input type="radio" id="type-qcm" name="type_question" value="qcm" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> QCM
                                    </p>
                                </div>

                                <div id="intitules_reponses" style="float:right;">
                                    <div id="responses-items">
                                        <?php

                                        $nbReponses = 1;

                                        if (isset($formData['reponses']) && is_array($formData['reponses']) && count($formData['reponses']) > 0)
                                        {
                                            $nbReponses = count($formData['reponses']);
                                        }

                                        for ($i = 0; $i < count($nbReponses); $i++) 
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

                                    <div id="responses-btn">
                                        <p>
                                            <input type="button" class="bt-admin-simple-button" name="add_response" id="add_response" value="Ajouter" <?php echo $formData['disabled']; ?> /> 
                                            <input type="button" class="bt-admin-simple-button" name="delete_response" id="delete_response" value="Supprimer" <?php echo $formData['disabled']; ?> />
                                        </p>
                                    </div>
                                
                                </div>

                                <div style="clear:both"></div>
                            </div>
                            <br />
                            <div id="response-champ" style="background:#ECF0F1; padding:10px">
                                <div>
                                    <p>
                                        <?php
                                        $checked = "";
                                        if ($formData['type_question'] == "champ_saisie") 
                                        {
                                            $checked = "checked";
                                        }
                                        ?>
                                        <input type="radio" id="type-champ" name="type_question" value="champ_saisie" <?php echo $checked; ?> <?php echo $formData['disabled']; ?> /> Réponse ouverte &nbsp;
                                    </p>
                                </div>

                            </div>

                        </fieldset>
                    </div>

                    <div class="formdiv">
                        <fieldset>

                            <div id="titre-question-h3">Medias</div>
                            <div id="medias">
                                <div id="image-question">
                                    <p>
                                    <?php
                                    if ($formData['image_question']) :
                                        $imageName = $formData['image_question'];
                                    ?>
                                        <label for="image_file"><strong>Fichier image : </strong><?php echo $imageName; ?></label><br/>
                                        <input type="file" name="image_file" accept="image/*" <?php echo $formData['disabled']; ?> /> <?php //echo $formData['image_question']; ?>
                                        <input type="hidden" name="image_question" value="<?php echo $formData['image_question']; ?>" />
                                        
                                        <div>
                                            <a rel="lightbox" href="<?php echo WEBROOT.IMG_PATH.$imageName; ?>">
                                                <img src="<?php echo WEBROOT.THUMBS_PATH; ?>thumb_<?php echo $imageName; ?>" />
                                            </a>
                                        </div>
                                    <?php
                                    else :
                                    ?>
                                        <label for="image_file">Sélectionner un fichier image (format "jpeg")</label><br/>
                                        <input type="file" name="image_file" accept="image/*" <?php echo $formData['disabled']; ?> /> <?php //echo $imageName; ?>
                                        <!-- <input type="hidden" name="image_question" value="<?php //echo $imageName; ?>" /> -->
                                    <?php
                                    endif;
                                    ?>
                                    </p>
                                </div>
                                <hr/>
                                <div id="audio-question">
                                    <p>
                                    <?php
                                    if ($formData['audio_question']) :
                                        $audioName = $formData['audio_question'];
                                    ?>
                                        <label for="audio_file"><strong>Fichier audio : </strong><?php echo $audioName; ?></label><br/>
                                        <input type="file" name="audio_file" accept="audio/*" <?php echo $formData['disabled']; ?> /> <?php //echo $audioName; ?>
                                        <input type="hidden" name="audio_question" value="<?php echo $audioName ?>" />
                                        
                                        <div>
                                            <object type="application/x-shockwave-flash" data="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="160" height="20" id="dewplayer" name="dewplayer"> 
                                            <param name="wmode" value="transparent" />
                                            <param name="movie" value="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" /> 
                                            <param name="flashvars" value="mp3=<?php echo SERVER_URL; ?>uploads/audio/<?php echo $audioName; ?>&amp;autostart=0&amp;nopointer=1&amp;javascript=on" />
                                            <param name="wmode" value="transparent" />
                                            </object>
                                        </div>
                                    <?php
                                    else :
                                    ?>
                                        <label for="audio_file">Sélectionner un fichier audio (format "mp3")</label><br/>
                                        <input type="file" name="audio_file" accept="audio/*" <?php echo $formData['disabled']; ?> /> <?php //echo $audioName; ?>
                                        <!-- <input type="hidden" name="audio_question" value="<?php //echo $audioName ?>" /> -->
                                    <?php
                                    endif;
                                    ?>
                                    </p>
                                    
                                </div>
                            </div>

                        </fieldset>      
                    </div>

                </div>



                <!-- Partie droite : Propriétés question en cours -->

                <div id="proprietes" class="part-right">

                    <div id="competences" class="formdiv" >
                        <fieldset>
                            <div id="titre-question-h3">Catégories / compétences</div>
                            <p>
                                <select id="code_comp_cbox" name="code_cat_cbox" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?> >
                                    <option value="select_cbox">---</option>
                                    <?php 

                                    foreach($response['categorie'] as $categorie)
                                    {
                                        
                                        $selected = "";
                                        if (!empty($formData['categories'][0]['code_cat']) && $formData['categories'][0]['code_cat'] == $categorie->getCode())
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
                                </select>
                            </p>
                        </fieldset>
                    </div>

                    
                    <div id="niveau" class="formdiv">
                        <fieldset>
                            <div id="titre-question-h3">Degrés d'aptitude (facultatif)</div>

                            <?php 

                            foreach($response['degre'] as $degre)
                            {
                                $checked = "";
                                if (!empty($formData['ref_degre']) && $formData['ref_degre'] == $degre->getId())
                                {
                                    $checked = "checked";
                                }
                                echo '<p>';
                                echo '<input type="radio" name="ref_degre" class="radio_degre" value="'.$degre->getId().'" title="'.$degre->getDescription().'" '.$checked.' '.$formData['disabled'].' /> <span class="checkbox-<?php echo '.$formData['disabled'].'">'.$degre->getNom().'</span>';
                                echo '</p>';
                            }

                            ?>

                            <p><input type="button" class="bt-admin-simple-button" name="remove-degrees" <?php echo $formData['disabled']; ?> value="Tout déselectionner" /></p>
                        </fieldset>
                    </div>

                </div>

                
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



                <div style="clear:both"></div>


                <!-- Partie basse : Boutons de gestion de la question -->


                <div class="formdiv" id="buttons" >

                    <input type="hidden" name="delete" value="false" />
                    <input type="submit" name="del" class="bt-admin-menu-sup" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />
                    <input type="submit" name="edit" class="bt-admin-menu-modif" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
                    <input type="submit" name="save" class="bt-admin-menu-enreg" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
                    <input type="submit" name="add" class="bt-admin-menu-ajout" value="Ajouter une question" <?php echo $formData['add_disabled']; ?> /> 

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

            var $responseItem = $("#responses-items").first().html();

            $("#add_response").click(function() {
                    
                $("#responses-items").append($responseItem);
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



            /*** Tableau des éléments du cache des réponses ***/

            var cacheInputs = new Array();


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
            



            /*** Bouton de déselection de tous les radio buttons de la partie degrés ***/

            $('input[name=remove-degrees]').click(function(event) {

                $('.radio_degre').each(function() {

                    if ($(this).attr("checked", true))
                    {
                        $(this).removeProp("checked");
                    }
                });
            });




            /*** Gestion de la demande de suppression ***/

            $('.bt-admin-menu-sup').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez-vous réellement supprimer cette question ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-question').submit();
                }
            });
            
        });

    </script>
       
       