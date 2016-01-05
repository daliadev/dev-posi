<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['ref_valid'] = "";
$formData['nom_acquis'] = "";
$formData['descript_acquis'] = "";


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
    
    
    
    <div class="content-form-small">
        
        <div class="form-header">
            <h2>Gestion des données</h2>
            <a href="<?php echo SERVER_URL; ?>admin/menu" class="form-header-back">
                <i class="fa fa-bars"></i>
            </a>
            <div class="clear"></div>
        </div>
        

        <form class="form-admin-small" id="form-admin" name="form_admin_valid" action="<?php echo $form_url; ?>" method="post">
            
            <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />
            <input type="hidden" name="delete" value="false" />

            <fieldset>

                <div class="fieldset-header" id="titre-degre">
                    <i class="fa fa-cube"></i><h2 class="fieldset-title">Gestion de l'interprétation des acquis</h2>
                </div>

                <!-- Liste de sélection -->

                <div class="form-group">
                    <label for="ref-valid-cbox">Liste des degrés de validation</label>
                    <select name="ref_valid_cbox_cbox" id="form-selection" class="form-control">
                        <option value="select_cbox">---</option>

                        <?php 
                        
                        if (!empty($response['valid_acquis']) && is_array($response['valid_acquis']))
                        {
                            foreach($response['valid_acquis'] as $validAcquis)
                            {  
                                $selected = "";
                                if (!empty($formData['ref_valid']) && $formData['ref_valid'] != "select_cbox" && $formData['ref_valid'] == $validAcquis->getId())
                                {
                                    $selected = "selected";
                                }
                                echo '<option value="'.$validAcquis->getId().'" '.$selected.'>'.$validAcquis->getNom().'</option>';
                            }
                        }

                        ?>

                    </select>
                </div>
                
                <button type="submit" name="submit_select" class="btn btn-primary" id="submit-select">Sélectionner</button>
                
                <div class="clear"></div>
                
                <hr />


                <!-- Edition -->

                <?php
                
                    if (isset($response['errors']) && !empty($response['errors']))
                    { 
                        echo '<div class="alert alert-danger">';
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
                        echo '<div class="alert alert-success">';
                            echo '<ul>';
                            foreach($response['success'] as $message)
                            {
                                echo '<li>'.$message.'</li>';
                            }
                            echo '</ul>';
                        echo '</div>';
                    }
                ?>
                
                <div class="form-group">
                    <label for="nom-acquis">Nom</label>
                    <input type="text" name="nom_acquis" class="form-control" id="nom-acquis" value="<?php echo $formData['nom_acquis']; ?>" <?php echo $formData['disabled']; ?> />
                    <span id="nom-degre-help" class="help-block">Le nom du degré n'a pas été correctement saisi</span>
                </div>
                <div class="form-group">
                    <label for="descript-acquis">Description</label>
                    <textarea name="descript_acquis" id="descript-acquis" cols="30" rows="4" class="form-control" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_acquis']; ?></textarea>
                </div>
                
                <!-- <hr/> -->

                
                <!-- Boutons -->
                
                <button type="submit" name="add" class="btn btn-primary" id="submit-add" <?php echo $formData['add_disabled']; ?>>Ajouter</button>
                <button type="submit" name="edit" class="btn btn-secondary" id="submit-edit" <?php echo $formData['edit_disabled']; ?>>Modifier</button>
                <div class="clear"></div>
                <button type="submit" name="save" class="btn btn-info" id="submit-save" <?php echo $formData['save_disabled']; ?>>Enregistrer</button>
                <button type="submit" name="del" class="btn btn-danger" id="submit-del" <?php echo $formData['delete_disabled']; ?>>Supprimer</button>
                <div class="clear"></div>


            </fieldset>

        </form>
    
    </div>

    
 