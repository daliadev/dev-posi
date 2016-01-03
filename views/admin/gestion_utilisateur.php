<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['nom_user'] = "";
$formData['prenom_user'] = "";
$formData['date_naiss_user'] = "";
$formData['ref_niveau'] = "";
$formData['taux_reussite_globale'] = "-";
$formData['nbre_sessions_accomplies'] = "-";

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


$form_url = $response['url'];


?>
    
    
    
    <div id="content">
        
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
        
        <div style="clear:both;"></div>
        
        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <!--**************************** Formulaire admin gestion des utilisateurs *************************************-->

        
        <div id="utilisateur">
            <div class="zone-formu">

                <div class="titre-form" id="titre-utili">Gestion des utilisateurs</div>

                <form id="form-posi" action="<?php echo $form_url; ?>" method="POST" name="form_admin_user">

                    <div class="form-small">

                        <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>">
                        <input type="hidden" name="ref_user" value="<?php echo $formData['ref_user']; ?>">

                        
                        <div class="input">
                            <label for="ref_user_cbox">Liste des utilisateurs :</label>
                            <select name="ref_user_cbox" id="ref_user_cbox">
                                <option value="select_cbox">---</option>

                                <?php
                                
                                $optgroup = false;

                                foreach($response['organ'] as $organ)
                                {
                                    if ($optgroup)
                                    {
                                        echo '</optgroup>';
                                        $optgroup = false;
                                    }

                                    if (isset($organ['nom_organ']) && !empty($organ['nom_organ']))
                                    {
                                        echo '<optgroup label="'.$organ['nom_organ'].'">';
                                        $optgroup = true;
                                    }

                                    foreach($organ['user'] as $user)
                                    {
                                        $selected = "";
                                        if (!empty($formData['ref_user']) && $formData['ref_user'] == $user['ref_user'])
                                        {
                                            $selected = "selected";
                                        }

                                        $style = "padding-left:20px;";

                                        echo '<option value="'.$user['ref_user'].'" style="'.$style.'" '.$selected.'>'.$user['nom_user'].' '.$user['prenom_user'].'</option>';
                                    }

                                }
                                
                                if ($optgroup)
                                {
                                    echo '</optgroup>';
                                }

                                ?>

                            </select>

                        </div>

                        <div id="submit">    
                            <input type="submit" name="selection" value="Sélectionner">
                        </div>


                        <hr>


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


                                
                        <div class="input">
                            <label for="nom_user">Nom <span class="asterix">*</span></label>
                            <input type="text" name="nom_user" id="nom_user" value="<?php echo $formData['nom_user']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>
                        
                        <div class="input">
                            <label for="prenom_user">Prénom <span class="asterix">*</span></label>
                            <input type="text" name="prenom_user" id="prenom_user" value="<?php echo $formData['prenom_user']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>

                        <div class="input">
                            <label for="date_naiss_user">Date de naissance <span class="asterix">*</span></label>
                            <input type="text" name="date_naiss_user" id="date_naiss_user" value="<?php echo $formData['date_naiss_user']; ?>" <?php echo $formData['disabled']; ?>>
                        </div>

                        <div class="input">
                            <label for="niveau_etude">Niveau de formation <span class="asterix">*</span></label>
                            <select name="ref_niveau_cbox" id="ref_niveau_cbox" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?>>
                                <option value="select_cbox">---</option>
                                <?php
                                foreach($response['niveau_etudes'] as $niveau)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_niveau']) && $formData['ref_niveau'] != "select_cbox" && $formData['ref_niveau'] == $niveau->getId())
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$niveau->getId().'" title="'.htmlentities($niveau->getDescription()).'" '.$selected.'>'.$niveau->getNom().'</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <hr>


                        <p>Taux de réussite globale : <strong><?php echo $formData['taux_reussite_globale']; ?></strong></p>

                        <p>Nombre de positionnements accomplies : <strong><?php echo $formData['nbre_sessions_accomplies']; ?></strong></p>


                        <hr>

                        <!-- Boutons de gestion des utilisateurs -->

                        <div id="buttons">
                                <input type="hidden" name="delete" value="false">
                                <input type="submit" class="add" name="add" style="float:left;" value="Ajouter" disabled <?php //echo $formData['add_disabled']; ?>>
                                <input type="submit" class="edit" name="edit" style="float:right;" value="Modifier" <?php echo $formData['edit_disabled']; ?>>
                                <input type="submit" class="save" name="save" style="float:left;" value="Enregistrer" <?php echo $formData['save_disabled']; ?>>
                                <input type="submit" class="del" name="del" style="float:right;" value="Supprimer" <?php echo $formData['delete_disabled']; ?>>      
                        </div>
                        <div style="clear:both;"></div>

                    </div>

                </form>

            </div>
        </div>


        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer_old.php');
        ?>

    </div>

    

    <script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="<?php echo SERVER_URL; ?>media/js/jquery-ui-1.10.3.custom.all.js" type="text/javascript"></script>

    <script type="text/javascript">
        
        $(function() { 

            /*** Gestion du selecteur de date ***/

            $("#date_naiss_user").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true, 
                changeYear: true, 
                yearRange: "1950:2014",
                closeText: 'Fermer',
                prevText: 'Précédent',
                nextText: 'Suivant',
                currentText: 'Aujourd\'hui',
                monthNames: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
                monthNamesShort: ['janv.', 'févr.', 'mars', 'avril', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'],
                dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
                dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
                dayNamesMin: ['D','L','M','M','J','V','S'],
                weekHeader: 'Sem.',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            });


            /*** Gestion de la demande de suppression ***/

            $('input[name="del"]').click(function(event) {

                event.preventDefault();

                if (confirm("Voulez êtes sur le point de supprimer un utilisateur. Cette suppression effacera également tous les positionnements et les résultats qui en dépendent. Voulez-vous continuer ?"))
                {
                    $('input[name="delete"]').val("true");
                    $('#form-posi').submit();
                }
            });
        
        });

    </script>


    
 