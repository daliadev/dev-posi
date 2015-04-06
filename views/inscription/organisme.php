<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_organ_cbox'] = "";
$formData['ref_organ'] = "";
$formData['nom_organ'] = "";
$formData['numero_interne'] = "";
$formData['adresse_organ'] = "";
$formData['code_postal_organ'] = "";
$formData['ville_organ'] = "";
$formData['tel_organ'] = "";
$formData['fax_organ'] = "";
$formData['email_organ'] = "";
$formData['ref_intervenant'] = "";
$formData['nom_intervenant'] = "";
$formData['tel_intervenant'] = "";
$formData['email_intervenant'] = "";
$formData['date_inscription'] = "";


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

    
    <div id="posi-inscript" class="main">
        
        <div class="header">

            <div class="header-wrapper">

                <div class="logo">
                    <!-- <img src="images/logo-dalia_40x40.png" alt="Positionnement Dalia"> -->
                </div>

                <div class="header-title">
                    <h1>Test de positionnement DALIA</h1>
                </div>
                <!-- 
                <div class="header-menu">

                    <a href="#" class="menu-btn">
                       <i class="fa fa-bars"></i>
                    </a>
                    <ul class="menu-list">
                        <li><span>Organisme</span></li>
                        <li><span>Profil</span></li>
                        <li><span>Parcours</span></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                
                <div class="clear"></div>
                 -->
            </div>

        </div>


        <div class="content">
            
            <div class="form-header">
                <h2>Inscription</h2>
                <i class="fa fa-chevron-down"></i>
                <div class="clear"></div>
            </div>

            <!-- Steps progress bar -->
            <!-- <div class="stepper">
                <ul class="stepper">
                    <li class="active">Organisme</li>
                    <li>Profil</li>
                    <li>Validation</li>
                </ul>
            </div> -->

            <form class="form-inscript" id="form-inscription" name="form_inscription" action="<?php echo $form_url; ?>" method="post">
                
                <input type="hidden" value="<?php echo $formData['ref_organ']; ?>" name="ref_organ">
                <input type="hidden" value="<?php echo $formData['ref_intervenant']; ?>" name="ref_intervenant">
                <!-- <input type="hidden" value="" name="ref_organ">
                <input type="hidden" value="" name="ref_intervenant">
                <input type="hidden" value="" name="ref_user">
                <input type="hidden" value="" name="name_validation" id="name-validation"> -->
                
                <!-- Fieldsets parts -->
                <!-- <div class="fieldsets-parts"> -->
                <fieldset>

                    <div class="fieldset-title" id="titre-organ">
                        <i class="fa fa-cube"></i> <h2 class="section-form"> Votre organisme</h2>
                    </div>

                    <div id="first-part">

                        <label for="code_identification">Code organisme <span class="asterix">*</span></label>
                        <br/>
                        <input type="password" name="code_identification" class="input-text" id="code_identification" title="Entrer votre code organisme" value="" required />
                    
                    </div>

                    <div id="second-part">

                        <!-- <label for="ref_organ_cbox">Veuillez entrer votre organisme <span class="asterix">*</span></label>
                        <br/>
                        <select name="ref_organ_cbox" id="ref_organ_cbox" class="selectpicker">
                            <option value="select_cbox">---</option>
                            <option value="1">Education et Formation</option>
                            <option value="2">AFPA</option>
                            <option value="3">Active Formation</option>
                            <option value="new" style="font-weight: bold;">Autre</option>
                        </select> -->

                        <label for="ref_organ_cbox">Veuillez entrer votre organisme <span class="asterix">*</span></label><br/>
                        <select name="ref_organ_cbox" id="ref_organ_cbox" class="selectpicker">
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
                            if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] === "new")
                            {
                                $selected = "selected";
                            }

                            if (Config::ALLOW_OTHER_ORGAN)
                            {
                                echo '<option value="new" '.$selected.' style="font-weight:bold;">Autre</option>';
                            }

                            ?>

                        </select>

                    </div>
                    
                    <div id="third-part" class="sub-form">

                        <label for="nom_organ">Nom de votre organisme <span class="asterix">*</span></label><br/>
                        <input type="text" name="nom_organ" id="nom_organ" class="input-text" value="<?php echo $formData['nom_organ']; ?>" />

                        <label for="code_postal_organ">Code postal <span class="asterix">*</span></label><br/>
                        <input type="tel" name="code_postal_organ" id="code_postal_organ" class="input-text" value="<?php echo $formData['code_postal_organ']; ?>" title="Ex:76000" pattern="[0-9]{5}" required />

                        <label for="tel_organ">Téléphone <span class="asterix">*</span></label><br/>
                        <input type="tel" name="tel_organ" id="tel_organ" class="input-text" value="<?php echo $formData['tel_organ']; ?>" pattern="[0-9]{10}" required />

                    </div>

                    <div id="fourth-part">
                                
                        
                        
                        
                        <div class="input">
                                
                            <?php if (Config::ALLOW_REFERENT_INPUT == 1 || count(Config::$emails_referent) == 0) : ?>
                                
                                <label for="email_intervenant">Email formateur <span class="asterix">*</span></label><br/>
                                <input type="email" name="email_intervenant" id="email_intervenant" class="input-text"  value="<?php echo $formData['email_intervenant']; ?>" title="Format email requis(exemple@xxx.yy)" placeholder="exemple@xxx.yy" required />
                                <span class="form-hint">Format email requis(exemple@xxx.yy)</span>
                                
                            <?php elseif (isset(Config::$emails_referent) && is_array(Config::$emails_referent) && count(Config::$emails_referent) > 0) : ?>
                                    
                                <label for="ref_inter_cbox">Email formateur <span class="asterix">*</span></label><br/>
                                <select name="ref_inter_cbox" id="ref_inter_cbox" class="selectpicker">
                                    <option value="select_cbox">---</option>

                                    <?php
    
                                    foreach(Config::$emails_referent as $email_referent)
                                    {  
                                        $selected = "";
                                        
                                        if (!empty($formData['ref_inter_cbox']) && $formData['ref_inter_cbox'] != "select_cbox" && $formData['ref_inter_cbox'] == $email_referent)
                                        {
                                            $selected = "selected";
                                        }
                                        
                                        echo '<option value="'.$email_referent.'" '.$selected.'>'.$email_referent.'</option>';
                                    }
                                    
                                    ?>

                                </select>

                            <?php endif; ?>

                        </div>

                    </div>


                    <input type="submit" name="submit" class="button-primary action-button" id="submit" value="Valider" />

                    <div class="clear"></div>

                </fieldset>

                <!-- </div> -->

            </form>

        </div>
         
        <div class="clear"></div>
        
        <!-- Footer -->
        <div class="footer">
            <p class="txt-footer">
                © Education et formation 2014
            </p>
        </div>

        

    </div>




    <!-- <div id="content"> -->

        <?php
            // Inclusion du header
            //require_once(ROOT.'views/templates/header_posi.php');
        ?>


        
        <!-- <div id="organisme">
            <div class="zone-formu">

                <div class="titre-form" id="titre-organ">Organisme</div>
                
                <?php
                    /*
                    if (isset($response['errors']) && !empty($response['errors']))
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
                    */
                ?>

                <form id="form-posi" name="form_organisme" action="<?php //echo $form_url; ?>" method="post">
                    
                    <div class="form-small">

                        <input type="hidden" value="<?php //echo $formData['ref_organ']; ?>" name="ref_organ">
                        <input type="hidden" value="<?php //echo $formData['ref_intervenant']; ?>" name="ref_intervenant">

                        
                        <div id="first-part">

                            <div class="input"> 
                                <label for="code_identification">Code organisme <span class="asterix">*</span></label><br/>
                                <input type="password" name="code_identification" id="code_identification" value="" required title="Entrer votre code organisme">
                            </div>

                        </div>

                        <div id="second-part">

                            <div class="input"> 

                                <label for="ref_organ_cbox">Veuillez entrer votre organisme <span class="asterix">*</span></label><br/>
                                <select name="ref_organ_cbox" id="ref_organ_cbox">
                                    <option value="select_cbox">---</option>

                                    <?php 
                                    /*
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

                                    if (Config::ALLOW_OTHER_ORGAN)
                                    {
                                        echo '<option value="new" '.$selected.' style="font-weight:bold;">Autre</option>';
                                    }
                                    */
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div id="third-part">

                            <div class="input">
                                <label for="nom_organ">Nom de votre organisme <span class="asterix">*</span></label><br/>
                                <input value="<?php //echo $formData['nom_organ']; ?>" name="nom_organ" id="nom_organ" type="text">
                            </div>

                            <div class="input">
                                <label for="code_postal_organ">Code postal <span class="asterix">*</span></label><br/>
                                <input type="tel" value="<?php //echo $formData['code_postal_organ']; ?>" name="code_postal_organ" id="code_postal_organ"  pattern="[0-9]{5}" title="Ex:76000">
                            </div>

                            <div class="input">
                                <label for="tel_organ">Téléphone <span class="asterix">*</span></label><br/>
                                <input type="tel" value="<?php //echo $formData['tel_organ']; ?>" name="tel_organ" id="tel_organ" pattern="[0-9]{10}">
                            </div>

                        </div>


                        <div id="fourth-part">
                            
                            <div class="input">
                                
                                <?php //if (Config::ALLOW_REFERENT_INPUT == 1 || count(Config::$emails_referent) == 0) : ?>
                                    
                                    <label for="email_intervenant">EMail formateur <span class="asterix">*</span></label><br/>
                                    <input type="email" value="<?php //echo $formData['email_intervenant']; ?>" name="email_intervenant" id="email_intervenant" required title="Format email requis(exemple@xxx.yy)">

                                <?php //elseif (isset(Config::$emails_referent) && is_array(Config::$emails_referent) && count(Config::$emails_referent) > 0) : ?>
                                        
                                    <label for="ref_inter_cbox">EMail formateur <span class="asterix">*</span></label><br/>
                                    <select name="ref_inter_cbox" id="ref_inter_cbox">
                                        <option value="select_cbox">---</option>

                                        <?php
                                        /*
                                        foreach(Config::$emails_referent as $referent)
                                        {  
                                            $selected = "";
                                            
                                            if (!empty($formData['ref_inter_cbox']) && $formData['ref_inter_cbox'] != "select_cbox" && $formData['ref_inter_cbox'] == $referent)
                                            {
                                                $selected = "selected";
                                            }
                                            
                                            echo '<option value="'.$referent.'" '.$selected.'>'.$referent.'</option>';
                                        }
                                        */
                                        ?>

                                    </select>

                                <?php //endif; ?>

                            </div>
                            
                        </div>

                
                        <div id="submit">
                            <input type="submit" value="Envoyer" name="valid_form_organ">
                        </div>

                    </div>

                </form>

            </div>
        </div>
        
        
    
        <div style="clear:both;"></div> -->


        <?php
            // Inclusion du footer
            //require_once(ROOT.'views/templates/footer.php');
        ?>
    
    <!-- <div> -->

    
    
    <!-- JQuery -->
    <script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
    
    <!-- Easing animation -->
    <script src="<?php echo SERVER_URL; ?>media/js/jquery.easing.1.3.min.js" type="text/javascript"></script>

    <!-- Bootstrap forms -->
    <script src="<?php echo SERVER_URL; ?>media/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo SERVER_URL; ?>media/js/bootstrap-select.min.js" type="text/javascript"></script>
    
    
    <script language="javascript" type="text/javascript">

        // jQuery object
        $(function() {
            
            // Menu droit (hamburger)
            $('.menu-btn').on('click', function(e) {
                $(this).next().slideToggle(250);
            });


            // Gestion des listes déroulantes (select)
            $('.selectpicker').selectpicker({style: 'custom-select'});

            // Focus sur le premier champ au démarrage de la page
            $('#code_identification').focus();
            
            // Gestion du formulaire organisme caché
            if ($('#second-part #ref_organ_cbox').val() == "new") {
                
                $('#third-part').show();
            }
            else {
                $('#third-part').hide();
            }

            $('#second-part #ref_organ_cbox').change(function() {

                if ($(this).val() == "new") {

                    $('#third-part').show(250);
                }
                else {

                    $('#third-part').hide(250);
                }
            });

        });

    </script>