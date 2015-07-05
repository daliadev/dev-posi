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

var_dump($formData['code_cat']);

?>


	<div id="content-large">

		<a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu" style="margin-right:30px">Retour menu</div></a>

		<div style="clear:both;"></div>

		
		<!-- Header -->
		<div id="titre-admin-h2">Gestion des catégories</div>

		
		<div id="main-form">

			<form id="form-posi" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

				<input type="hidden" id="mode" name="mode" value="<?php echo $formData['mode']; ?>" />
				<input type="hidden" id="code" name="code_cat" value="<?php echo $formData['code_cat']; ?>" />
				<input type="hidden" id="ordre" name="level" value="" />
				
				
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

				
				<!-- Partie gauche : Listing des catégories sélectionnables -->
				
				<div style="float:left;">

					<div class="zone-formu2">
	 
						<div class="form-half">

							<fieldset>
								
								<legend>Liste des catégories (sélection)</legend>
								
								<div id="liste-cat">
									

									<ul>
									<?php
									
									foreach($response['categorie'] as $categorie)
									{   
										
										$prefix = '';
										$textSize = 14;
										$weight = 'normal';

										if (strlen($categorie->getCode()) <= 2) {

											$prefix = substr($categorie->getCode(), 0, 1);
											$textSize = 13;
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
										
										$code = $categorie->getCode();
										//$name = $prefix . '- ' . $categorie->getNom();
										$name = $categorie->getNom();
										$length = strlen($categorie->getCode()) - 2;
										
										if ($length < 0)
										{
											$length = 0;
										}
										
										$styleMargin = 'margin-left:'.($length * 10).'px;';
										$style = 'font-size: '.$textSize.'px; font-weight: '.$weight.';';

										$selected = '';
										if ($formData['code_cat'] == $code) {

											$selected = 'selected';
										}

										?>

										<li style="<?php echo $styleMargin; ?>">
											<div class="cat-item-block">
												<a class="<?php echo $selected; ?>" href="#">
													<!-- <span class="ui-icon ui-icon-arrowthick-2-n-s"></span> -->
													<span class="cat-item-code" style="display: none;"><?php echo $code; ?></span>
													<span style="<?php echo $style; ?>"><?php echo $name; ?></span>
												</a>
											</div>
										</li>

										<?php
										//echo '<li class="ui-state-default" style="padding: 2px; margin: 2px; '.$styleMargin.'"><a class="cat-item" href="#"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><span style="'.$style.'">'.$prefix.'- '.$categorie->getNom().'</span></a></li>';
									}
									
									?>
									</ul>
									
								</div>

							</fieldset>
						</div>
					</div>
				</div>



				<!-- Partie droite : Affichage / détail de la catégorie -->

				<div style="float:right;">

					<div class="zone-formu2">
						
						<div class="form-half">

							<div id="detail">
								
								<fieldset>
								
									<legend>Ajout / détail d'une catégorie</legend>
										
									<div id="nom-cat" class="block">
										<p>
										<label for="nom_cat">Nom *</label>
										<input type="text" name="nom_cat" id="nom_cat" value="<?php echo $formData['nom_cat']; ?>" <?php echo $formData['disabled']; ?> />
										</p>
									</div>
									
									<div id="parent-cat" class="block">
										<label for="parent_cat_cbox">Catégorie parente *</label>

										<select name="parent_cat_cbox" id="ref_parent_cbox" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?>>
											<option value="select_cbox">Aucun</option>
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

												$style = "padding-left:".(($length * 10) + 5)."px;";

												//if ($length <= 0)
												//{
													//echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$categorie->getNom().'</option>';
												//}
												//else
												//{
													echo '<option value="'.$categorie->getCode().'" style="'.$style.'" '.$selected.'>- '.$categorie->getNom().'</option>';
												//}
											}
											
											?>
										</select>
									</div>
					
									
									<div id="ordre-cat" class="block">
										<label for="ordre_cat">Ordre (pour l'organisation des catégories de même niveau)</label>
										<input type="text" name="ordre_cat" id="ordre_cat" value="<?php echo $formData['ordre_cat']; ?>" <?php echo $formData['disabled']; ?> style="width: 80px !important;" />
									</div>


									<div id="descript-cat" class="block">
										<label for="descript_cat">Description</label>
										<textarea name="descript_cat" id="descript_cat" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_cat']; ?></textarea>
									</div>
										
									<!-- <div id="score-cat" class="block">

										<label for="type_lien_cat"><strong>Gestion des scores</strong></label><br/>
										
										<p>Chaque réponse de l'apprenant est liée à une catégorie. Ce score peut dépendre de la catégorie active ou de celles de ses enfants.</p> 
										<?php
										/*
										$checked = "";
										if (isset($formData['type_lien_cat']) && !empty($formData['type_lien_cat']) && $formData['type_lien_cat'] == "dynamic")
										{
											$checked = "checked";
										}
										*/
										?>
										
 
										<p>
											<input type="radio" name="type_lien_cat" id="type_lien_cat" value="static" <?php //echo $checked; ?> <?php //echo $formData['disabled']; ?> /> 
											<label class="checkbox-<?php //echo $formData['disabled']; ?>">Catégorie qui possède son propre score.</label>
										</p>
										
										<p>
											<input type="radio" name="type_lien_cat" id="type_lien_cat" class="radio_degre" value="dynamic" <?php //echo $checked; ?> <?php //echo $formData['disabled']; ?> /> 
											<label>Catégorie qui hérite de la moyenne des scores de ces enfants.</label>

										</p>

										<p>
											<input type="radio" name="type_lien_cat" id="type_lien_cat" class="radio_degre" value="null" <?php //echo $checked; ?> <?php //echo $formData['disabled']; ?> /> 
											<label>Catégorie sans score.</label>
										</p>

									</div> -->
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
							<input type="submit" id="add" name="add" class="bt-admin-menu-ajout" style="width:160px;" value="Ajouter une catégorie" <?php echo $formData['add_disabled']; ?> />
							<input type="submit" id="edit" name="edit" class="bt-admin-menu-modif" style="margin-left:108px;" value="Modifier" <?php echo $formData['edit_disabled']; ?> />
						</div>
						<div class="buttons-block">
							<input type="submit" id="save" name="save" class="bt-admin-menu-enreg" value="Enregistrer" <?php echo $formData['save_disabled']; ?> />
							<input type="submit" id="del" name="del" class="bt-admin-menu-sup" style="margin-left:118px;" value="<?php echo $formData['delete_label']; ?>" <?php echo $formData['delete_disabled']; ?> />
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
	
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>

	<script type="text/javascript">
		

		$(function() {

			var mode = $('#mode').val();


			/* Vide le formulaire */

			var resetFields = function() {

				$('#nom_cat').val('');
				$('#ref_parent_cbox').val('select_cbox');
				$('#ordre_cat').val('');
				$('#descript_cat').val('');
			};



			/*  Système de sélection de la liste des catégories à gauche */

			var $selected = null;

			$('.cat-item-block > a').each(function() {

				if ($(this).hasClass('selected')) {
					$selected = $(this);
				}
			});
				

			$('.cat-item-block > a').click(function(event) {

				event.preventDefault();

				if ($selected !== null) {

					$selected.removeClass('selected');
				}

				$(this).addClass('selected');
				$selected = $(this);

				var $code = $(this).find('.cat-item-code').html();
				$('#code').val($code);
				$('#edit').removeProp('disabled');
				//$('#add').removeProp('disabled');
			});

			/*
			$('#add').click(function(event) {

				//$('#del').val('Annuler');
			});

			$('#edit').click(function(event) {

				//$('#del').val('Annuler');
			});
			*/

			/*** Gestion de la demande de suppression ***/

			$('#del').click(function(event) 
			{
				event.preventDefault();

				if (mode == 'view') 
				{
					if (confirm("Voulez-vous réellement supprimer cette catégorie ?"))
					{
						$('input[name="delete"]').val("true");
						$('#form-posi').submit();
					}
				}
				else if (mode == 'new')
				{
					if (confirm("Voulez-vous réellement effacer les données que vous avez saisi ?"))
					{
						resetFields();
					}
				}
				
			});
			

			/* Envoi du formulaire avec affichage d'un loader le temps de la sauvegarde */
			/*
			$('#save').click(function(event) {
				
				$.loader();
			});
			*/
		});

	</script>
	   
	   