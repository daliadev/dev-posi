<?php

$form_url = WEBROOT."inscription/validation/utilisateur";


// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_user'] = "";
$formData['ref_intervenant'] = "";
if (isset($response['ref_intervenant']) && !empty($response['ref_intervenant']))
{ 
    $formData['ref_intervenant'] = $response['ref_intervenant'];
}
$formData['date_inscription'] = "";
if (isset($response['date_inscription']) && !empty($response['date_inscription']))
{ 
    $formData['date_inscription'] = $response['date_inscription'];
}

$formData['ref_niveau_cbox'] = "";
$formData['ref_niveau'] = "";
$formData['nom_user'] = "";
$formData['prenom_user'] = "";
$formData['date_naiss_user'] = "";
$formData['adresse_user'] = "";
$formData['code_postal_user'] = "";
$formData['ville_user'] = "";
$formData['email_user'] = "";
 

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
    foreach($response['form_data'] as $key => $value)
    {
        $formData[$key] = $value;
    }
}

//var_dump($response);

?>


    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi.php');
        ?>


        <!-- Formulaire -->

        <div id="utilisateur">
            <div id="zone-formu">

                <div id="titre-form">Utilisateur</div>

                <form action="<?php echo $form_url; ?>" method="POST">
                    <div class="formu">

                        <input type="hidden" value="<?php echo $formData['ref_user']; ?>" name="ref_user">
                        <input type="hidden" value="<?php echo $formData['ref_intervenant']; ?>" name="ref_intervenant">
                        <input type="hidden" value="<?php echo $formData['date_inscription']; ?>" name="date_inscription">

                        <div class="input">
                            <label for="nom_user">Nom <span class="asterix">*</span></label>
                            <input type="text" name="nom_user" id="nom_user" value="<?php echo $formData['nom_user']; ?>" required />
                        </div>

                        <div class="input">
                            <label for="prenom_user">Prénom <span class="asterix">*</span></label>
                            <input type="text" name="prenom_user" id="prenom_user" value="<?php echo $formData['prenom_user']; ?>" required />
                        </div>

                        <div class="input">
                            <label for="date_naiss_user">Date de naissance <span class="asterix">*</span></label>
                            <input type="text" name="date_naiss_user" id="date_naiss_user" title="Veuillez entrer votre date de naissance" value="<?php echo $formData['date_naiss_user']; ?>" required />
                        </div>

                        <label for="niveau_etude">Niveau de formation <span class="asterix">*</span></label>
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
                                echo '<option value="'.$niveau->getId().'" title="'.  htmlentities($niveau->getDescription()).'" '.$selected.'>'.$niveau->getNom().'</option>';
                            }
                            ?>
                        </select>

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
                            <input type="submit" value="Envoyer" name="valid_form_utili" onclick="verifUtil();" />
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
        
        // jQuery object
        $(function() {

            $( "#date_naiss_user" ).datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true, 
                changeYear: true, 
                yearRange: "1950:2014"
            });

        });

    </script>
