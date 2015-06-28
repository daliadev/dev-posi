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
	
	<div id="content">
		
		<a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>
		
		<div style="clear:both;"></div>
		
		<?php
			// Inclusion du header
			require_once(ROOT.'views/templates/header_admin.php');
		?>
		
		
		<!--**************************** Formulaire admin gestion catégories *************************************-->

		
		<div id="organisme">
			<div class="zone-formu">

				<div class="titre-form" id="titre-cat">Gestion des catégories</div>

				<form id="form-posi" action="<?php echo $form_url; ?>" method="POST" name="form_admin_categorie">

					<div class="form-small">

						<input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" />
						<input type="hidden" name="code" value="<?php echo $formData['code_cat']; ?>" />
						

						<label><strong>Ajouter une catégorie :</strong></label>

						<!-- <div class="input" style="height: 300px; overflow: auto;">
							<ul id="sortable" style="padding: 0;">
							<?php
							/*
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
								
								$style = 'padding-left:'.($length * 10).'px; font-size: '.$textSize.'px; font-weight: '.$weight.';';
								/*
								if ($length <= 0)
								{
									echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$categorie->getNom().'</option>';
								}
								else
								{
									echo '<option value="'.$categorie->getCode().'" style="'.$style.'" '.$selected.'>- '.$categorie->getNom().'</option>';
								}
								
								echo '<li class="ui-state-default" style="padding: 2px; margin: 2px; background: #eeeeee;"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span style="'.$style.'">'.$prefix.'- '.$categorie->getNom().'</span></li>';
							}
							*/
							?>
							</ul>
							
						</div> -->
						


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
						<!-- 		
						<div class="input">
							<label for="code_cat">Code * <a target="blank" class="lien-no-underline" rel="lightbox" href="<?php //echo SERVER_URL; ?>media/images/code.jpg"><small>Mode d'emploi</small></a></label>
							<input type="text" name="code_cat" id="code_cat" value="<?php //echo $formData['code_cat']; ?>" <?php //echo $formData['disabled']; ?> />
						</div>
						 -->
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

						<div id="submit">
							<input type="submit" class="add" name="add" value="Ajouter" <?php echo $formData['add_disabled']; ?> />
						</div>

						<hr />

						<div class="input">
							<label><strong>Liste des catégories :</strong></label>

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
						<!-- 
						<div id="submit">
							<input type="submit" name="selection" value="Sélectionner" />
						</div>
						 -->
						<!-- <hr/> -->

						<!-- Boutons de gestion des catégories -->

						<div id="buttons">
								<input type="hidden" name="delete" value="false" />
								<input type="submit" name="selection" value="Sélectionner" style="float:left;"/>
								<!-- <input type="submit" class="add" name="add" style="float:left;" value="Ajouter" <?php //echo $formData['add_disabled']; ?> /> -->
								<input type="submit" class="edit" name="edit" style="float:right;" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
								<input type="submit" class="save" name="save" style="float:left;" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
								<input type="submit" class="del" name="del" style="float:right;" value="Supprimer" <?php echo $formData['delete_disabled']; ?> />      
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

	<!--<script src="<?php echo SERVER_URL; ?>media/js/jquery-ui.sort.min.js" type="text/javascript"></script>-->

	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/lightbox-2.6.min.js"></script>

	<script type="text/javascript">

		
		$(function() { 

			$(function() {
				$( "#sortable" ).sortable();
				$( "#sortable" ).disableSelection();
			});

			/*** Gestion de la demande de suppression ***/

			$('input[name="del"]').click(function(event) {

				event.preventDefault();

				if (confirm("Voulez-vous réellement supprimer cette catégorie ?"))
				{
					$('input[name="delete"]').val("true");
					$('#form-posi').submit();
				}
			});
		
		});

	</script>
