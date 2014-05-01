<?php

// url vers laquel doit pointer le formulaire
$form_url = WEBROOT."inscription/validation/organisme";

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['ref_organ'] = "";
$formData['ref_organ_cbox'] = "";
$formData['ref_intervenant'] = "";

$formData['code_identification'] = "";
$formData['nom_organ_cbox'] = "";
$formData['nom_organ'] = "";
$formData['adresse_organ'] = "";
$formData['code_postal_organ'] = "";
$formData['ville_organ'] = "";
$formData['tel_organ'] = "";
$formData['fax_organ'] = "";
$formData['email_organ'] = "";
$formData['numero_interne'] = "";

$formData['date_inscription'] = "";
$formData['nom_intervenant'] = "";
$formData['email_intervenant'] = "";
$formData['tel_intervenant'] = "";


// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
    foreach($response['form_data'] as $key => $value)
    {
        $formData[$key] = $value;
    }
 
}

//var_dump($formData);


?>



    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi.php');
        ?>


    <!--********************************* Formulaire ********************************************-->


        
        <div id="organisme">
            <div id="zone-formu">

                <div id="ico-orga">Organisme</div>

                <form action="<?php echo $form_url; ?>" method="POST" name="form_organisme">
                    
                    <div class="formu">

                        <input type="hidden" value="<?php echo $formData['ref_organ']; ?>" name="ref_organ">
                        <input type="hidden" value="<?php echo $formData['ref_intervenant']; ?>" name="ref_intervenant">

                        <div class="input"> 
                            <label for="code_identification">Code organisme <span class="asterix">*</span></label>
                            <input type="password" name="code_identification" id="code_identification" value="" required title="Entrer votre code organisme" />
                        </div>
                        <!--
                        <div class="input">
                            <label for="date_inscription">Date <div class="asterix">*</div></label>
                            <input type="text" title="Veuillez entrer la date" value="<?php //echo $formData['date_inscription']; ?>" name="date_inscription" id="date_inscription" required />
                        </div>
                        -->
                        <fieldset id="main-form">
                            <label for="ref_organ_cbox">Organisme <span class="asterix">*</span></label>
                            <select name="ref_organ_cbox" id="ref_organ_cbox">
                                <option value="select_cbox">---</option>

                                <?php 
                                if (!empty($response['organisme']) && is_array($response['organisme']))
                                {
                                    foreach($response['organisme'] as $organisme)
                                    {  
                                        $selected = "";
                                        if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] != "select_cbox" && $formData['ref_organ_cbox'] == $organisme->getId())
                                        {
                                            $selected = "selected";
                                        }
                                        echo '<option value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
                                    }
                                }

                                $selected = "";
                                if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] == "new")
                                {
                                    $selected = "selected";
                                }

                                echo '<option value="new" '.$selected.'>Autre</option>';

                                ?>

                            </select>
                        </fieldset>

                        <fieldset id="second-form">

                            <div class="input">
                                <label for="nom_organ">Veuillez entrer votre organisme <span class="asterix">*</span></label>
                                <input value="<?php echo $formData['nom_organ']; ?>" name="nom_organ" id="nom_organ" type="text"  />
                            </div>

                            <div class="input">
                                <label for="code_postal_organ">Code postal <span class="asterix">*</span></label>
                                <input type="tel" value="<?php echo $formData['code_postal_organ']; ?>" name="code_postal_organ" id="code_postal_organ"  pattern="[0-9]{5}" title="Ex:76000"   />
                            </div>

                            <div class="input">
                                <label for="tel_organ">Téléphone <span class="asterix">*</span></label>
                                <input type="tel" value="<?php echo $formData['tel_organ']; ?>" name="tel_organ" id="tel_organ" pattern="[0-9]{10}"  />
                            </div>

                        </fieldset>

                        
                        <div class="input">
                            <label for="email_intervenant">EMail formateur <span class="asterix">*</span></label>
                            <input type="email" value="<?php echo $formData['email_intervenant']; ?>" name="email_intervenant" id="email_intervenant" required title="Format Email requis(exemple@xxx.yy)" />
                            <label class="texte-petit"></label>
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
                        ?>
                    
                        <div id="submit">
                            <input type="submit" value="Envoyer" name="valid_form_organ" onclick="verifOrgan();" />
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
    
    <script src="<?php echo SERVER_URL; ?>media/js/modernizr-2.6.2.min.js"></script>
    
    <script language="javascript" type="text/javascript">

        function verifOrgan()
        {
            if (window.navigator.appName == 'Microsoft Internet Explorer')
            {
                var code_identification = document.formulaire.code_identification.value;
                if (document.formulaire.code_identification.value == "")
                {
                    alert ('Veuillez entrer votre code organisme');
                    document.formulaire.code_identification.focus();
                    return false;
                }

                var date_inscription = document.formulaire.date_inscription.value;
                if (document.formulaire.date_inscription.value == "")
                {
                    alert ('Veuillez entrer la date');
                    document.formulaire.date_inscription.focus();
                    return false;
                }

                var email_intervenant = document.formulaire.email_intervenant.value;
                if(document.formulaire.email_intervenant.value.indexOf('@') == -1) 
                { 
                    alert("Il y a une erreur à votre adresse électronique! format Email requis(exemple@xxx.yy)"); 
                    document.formulaire.email_intervenant.focus(); 
                    return false; 
                }
                var ref_organ_cbox = document.formulaire.ref_organ_cbox.value;
                if (document.formulaire.ref_organ_cbox.value == "new" || document.formulaire.organismes.value == "")
                {
                    var nom_organ = document.formulaire.nom_organ.value;
                    if (document.formulaire.nom_organ.value == "")
                    {
                        alert ('Veuillez entrer votre organisme');
                        document.formulaire.nom_organ.focus();
                        return false;
                    }
                    if(document.formulaire.code_postal_organ.value.length != 5)
                    { 
                        alert ('Le code postal doit comporter 5 chiffres'); 
                        document.formulaire.code_postal_organ.focus();
                        return false; 
                    }
                    if(document.formulaire.tel_organ.value.length != 10)
                    { 
                        alert ('Le n° de téléphone doit comporter 10 chiffres'); 
                        document.formulaire.tel_organ.focus();
                        return false; 
                    }
                }
            }
        }


        // jQuery object
        $(function() {
            
            $('#main-form #ref_organ_cbox').change(function() {

                if ( $( this ).val() === "new" ) {

                  $('#second-form').show();
                }
                else {

                  $('#second-form').hide();
                }
            });


            $('#date_inscription').datepicker({dateFormat:"dd/mm/yy"}); 

        });

    </script>