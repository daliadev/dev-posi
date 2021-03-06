<?php


// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_intervenant'] = "";
$formData['date_inscription'] = "";
$formData['ref_user'] = "";
$formData['ref_niveau_cbox'] = "";
$formData['ref_niveau'] = "";
$formData['nom_user'] = "";
$formData['prenom_user'] = "";
$formData['jour_naiss_user_cbox'] = "";
$formData['jour_naiss_user'] = "";
$formData['mois_naiss_user_cbox'] = "";
$formData['mois_naiss_user'] = "";
$formData['annee_naiss_user_cbox'] = "";
$formData['annee_naiss_user'] = "";
$formData['date_naiss_user'] = "";
$formData['adresse_user'] = "";
$formData['code_postal_user'] = "";
$formData['ville_user'] = "";
$formData['email_user'] = "";
$formData['name_validation'] = "";


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

// url vers laquel doit pointer le formulaire
$form_url = $response['url'];


?>


    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi.php');
        ?>


        <!-- Formulaire -->

        <div id="utilisateur">
            <div class="zone-formu">
                
                <div class="titre-form" id="titre-utili">Utilisateur</div>
                

                <?php

                    $showErrors = true;

                    if (isset($response['errors']) && !empty($response['errors']))
                    { 
                        foreach($response['errors'] as $error)
                        {
                            if ($error['type'] == "duplicate_name")
                            {
                                $showErrors = false;
                                break;
                            }
                        }
                        
                        if ($showErrors)
                        {
                            echo '<div id="zone-erreur">';
                            echo '<ul>';
                            foreach($response['errors'] as $error)
                            {
                                if ($error['type'] == "form_valid" || $error['type'] == "form_empty")
                                {
                                    echo '<li>- '.$error['message'].'</li>';
                                }
                                
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                        
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


                <form id="form-posi" action="<?php echo $form_url; ?>" method="POST">
                    
                    <div class="form-small">

                        <input type="hidden" value="<?php echo $formData['ref_user']; ?>" name="ref_user">
                        <input type="hidden" value="<?php echo $formData['name_validation']; ?>" name="name_validation" id="name-validation">

                        <div class="input">
                            <label for="nom_user">Nom <span class="asterix">*</span></label>
                            <input type="text" name="nom_user" id="nom_user" value="<?php echo $formData['nom_user']; ?>" required>
                        </div>

                        <div class="input">
                            <label for="prenom_user">Prénom <span class="asterix">*</span></label>
                            <input type="text" name="prenom_user" id="prenom_user" value="<?php echo $formData['prenom_user']; ?>" required>
                        </div>


                        <p style="margin-bottom:0px;">Date de naissance</p>

                        <div class="input" style="float:left; width:90px;">
                            <label for="jour_naiss_user_cbox">Jour <span class="asterix">*</span></label>
                            <select name="jour_naiss_user_cbox" id="jour_naiss_user_cbox" style="width:80px;">
                                <option value="select_cbox">---</option>

                                <?php

                                for ($i = 1; $i <= 31; $i++)
                                {
                                    $jour = $i;
                                    $selected = "";

                                    if (!empty($formData['jour_naiss_user_cbox']) && $formData['jour_naiss_user_cbox'] != "select_cbox" && $formData['jour_naiss_user_cbox'] == $i)
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$jour.'" '.$selected.'>'.$jour.'</option>';
                                }
                                ?>
                            </select>

                        </div>

                        <div class="input" style="float:left; width:110px;">
                            <label for="mois_naiss_user_cbox">Mois <span class="asterix">*</span></label>
                            <select name="mois_naiss_user_cbox" id="mois_naiss_user_cbox" style="width:100px;">
                                <option value="select_cbox">---</option>

                                <?php
                                $monthsName = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

                                for ($i = 1; $i <= 12; $i++)
                                {
                                    $nomMois = $monthsName[($i - 1)];
                                    $selected = "";

                                    if (!empty($formData['mois_naiss_user_cbox']) && $formData['mois_naiss_user_cbox'] != "select_cbox" && $formData['mois_naiss_user_cbox'] == $i)
                                    {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.$i.'" '.$selected.'>'.$nomMois.'</option>';
                                }

                                ?>
                            </select>

                        </div>

                        <div class="input" style="float:left; width:100px;">
                            <label for="annee_naiss_user_cbox">Année <span class="asterix">*</span></label>
                            <select name="annee_naiss_user_cbox" id="annee_naiss_user_cbox" style="width:100px;">
                                <option value="select_cbox">---</option>

                                <?php
                                $minYear = intval(date('Y')) - 70;
                                $maxYear = intval(date('Y')) - 10;

                                for ($i = $maxYear; $i >= $minYear; $i--)
                                {
                                    $year = $i;
                                    $selected = "";

                                    if (!empty($formData['annee_naiss_user_cbox']) && $formData['annee_naiss_user_cbox'] != "select_cbox" && $formData['annee_naiss_user_cbox'] == $i)
                                    {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
                                }
                                ?>
                            </select>

                        </div>

                        <div style="clear:both;"></div>


                        <div class="input">
                            <label for="ref_niveau_cbox">Niveau de formation <span class="asterix">*</span></label>
                            <select name="ref_niveau_cbox" id="ref_niveau_cbox">
                                <option value="select_cbox">---</option>
                                <?php
                                foreach($response['niveau_etudes'] as $niveau)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] != "select_cbox" && $formData['ref_niveau_cbox'] == $niveau->getId())
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$niveau->getId().'" title="'.htmlentities($niveau->getDescription()).'" '.$selected.'>'.$niveau->getNom().'</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div id="submit">
                            <input type="submit" value="Envoyer" name="valid_form_utili">
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
        
    <div>

        
    
    
    
    <!--   Script spécifiques à la page   -->
    
    <script src="<?php echo SERVER_URL; ?>media/js/message-box.js"></script>
    <script language="javascript" type="text/javascript">

        // jQuery object
        jQuery(function($){

            /*  Fenêtre de validation du nom dupliqué */

            if ($("#name-validation").val() === "false") {

                $.message('Une personne portant le même nom a déjà effectuée un positionnement. S\'il s\'agit bien de vous, cliquez sur "Continuer".<br>Sinon, cliquez sur "Annuler" pour corriger la saisie de vos nom, prénom et date de naissance.', {
                    icon: 'info', 
                    buttons: ['Continuer', 'Annuler'], 
                    callback: function(buttonText) {
                        if (buttonText === 'Continuer') {
                            $("#name-validation").val("true");
                            $("#form-posi").submit();
                        }
                        else
                        {
                            $("#name-validation").val("false");
                        }
                    }
                }, "body");
            }
            

        });

       

    </script>
