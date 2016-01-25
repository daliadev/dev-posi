<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();

$formData['code_cat'] = "";
$formData['nom_cat'] = "";
$formData['descript_cat'] = "";



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

//var_dump($formData['code_cat']);
//var_dump($formData['ordre_cat']);

$form_url = $response['url'];


?>


	<div id="content-large">

		<a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu" style="margin-right:30px">Retour menu</div></a>

		<div style="clear:both;"></div>

		
		<!-- Header -->
		<div id="titre-admin-h2">Gestion des compétences</div>

		
		<div id="main-form">

			<form id="form-posi" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

				<input type="hidden" id="mode" name="mode" value="<?php echo $formData['mode']; ?>" />
				<input type="hidden" id="code" name="code_cat" value="<?php echo $formData['code_cat']; ?>" />
				<!-- <input type="hidden" id="ordre" name="level" value="" /> -->
				
				
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

				
				<!-- Partie gauche : Listing des compétences sélectionnables -->
				<div style="position: relative;">
				
				<div class="fleche-cat">
					<i class="fa fa-arrow-right"></i>
				</div>

				<div style="float:left;">

					<div id="liste-cat-main" class="zone-formu2">
	 
						<div class="form-half">

							<fieldset>
								
								<legend>Liste des compétences (sélection)</legend>
								
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
											<!-- <div class="cat-item-block"> -->
												<a class="cat-item-link <?php echo $selected; ?>" href="#">
													<span class="cat-item-code" style="display: none;"><?php echo $code; ?></span>
													<span style="<?php echo $style; ?>"><?php echo $name; ?></span>
												</a>
											<!-- </div> -->
										</li>

										<?php } ?>

									</ul>
									
								</div>
								
								<div id="submit">    
									<input id="selection" type="submit" name="selection" value="Sélectionner" />
									<!-- <div style="clear: both;"></div> -->
								</div>

							</fieldset>

						</div>
					</div>
				</div>



				<!-- Partie droite : Affichage / détail de la compétence -->

				<div style="float:right;">

					<div id="detail-cat" class="zone-formu2">
						
						<div class="form-half">

							<div id="detail">
								
								<fieldset id="edit-cat">
								
									<legend>Détail d'une compétence (ajout, modification)</legend>
										
									<?php if(isset($formData['code_cat'])) : ?>

									<div id="num-cat" class="num-indicator">
										N°<?php echo $formData['code_cat']; ?>
									</div>

									<?php endif; ?>

									<div id="nom-cat" class="block">
										<p>
										<label for="nom_cat">Nom *</label>
										<input type="text" name="nom_cat" id="nom_cat" value="<?php echo $formData['nom_cat']; ?>" <?php echo $formData['disabled']; ?> />
										</p>
									</div>
									

									<div id="parent-cat" class="block">
										<label for="ref_parent_cbox">Compétence parente *</label>

										<select name="parent_cat_cbox" id="ref_parent_cbox" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?>>
											<option value="select_cbox">Aucune</option>
											<?php
											/*
											foreach($response['categorie'] as $categorie)
											{
												$selected = "";
												if (!empty($formData['parent_cat_cbox']) && $formData['parent_cat_cbox'] !== null && $formData['parent_cat_cbox'] == $categorie->getCode())
												{
													$selected = "selected";
												}

												$length = strlen($categorie->getCode());

												$style = "padding-left:".(($length * 10) + 5)."px;";

												echo '<option value="'.$categorie->getCode().'" style="'.$style.'" '.$selected.'>- '.$categorie->getNom().'</option>';
											}
											*/
											?>


											<?php 

											foreach($response['categorie'] as $categorie)
											{
												$selected = "";
												if (!empty($formData['parent_cat_cbox']) && $formData['parent_cat_cbox'] !== null && $formData['parent_cat_cbox'] == $categorie->getCode())
												{
													$selected = "selected";
												}

												$space = '';

												if (strlen($categorie->getCode()) == 2)
												{
													$label = '- '.$categorie->getNom();

													$length = 0;
												}
												else
												{
													$label = $categorie->getNom();

													$length = strlen($categorie->getCode());
													for ($i = 0; $i < $length; $i++) 
													{ 
														$space .= '&nbsp;&nbsp;';
													}
												}
												
												echo '<option value="'.$categorie->getCode().'" '.$selected.'>'.$space.$label.'</option>';
												
											}

											?>
										</select>
									</div>
									
									<div id="ordre-cat-block" class="block">
										<label for="ordre-cat">Ordre (pour l'organisation des compétences de même niveau)</label>
										<input type="text" name="ordre_cat" id="ordre-cat" value="<?php echo $formData['ordre_cat']; ?>" <?php echo $formData['disabled']; ?> style="width: 80px !important;" />
									</div>


									<div id="descript-cat" class="block">
										<label for="descript-cat">Description</label>
										<textarea name="descript_cat" id="descript-cat" cols="30" rows="4" maxlength="250" class="select-" <?php echo $formData['disabled']; ?>><?php echo $formData['descript_cat']; ?></textarea>
									</div>

								</fieldset>

							</div>
						</div>
					</div>
				</div>
				
				<div style="clear:both"></div>
				

				<!-- Partie basse : Gestion des type / préconisations de la compétence -->

				<?php if (Config::ALLOW_PRECONISATION) : ?>
				
				<div id="precos" class="zone-formu2">

					<div id="preconisations" class="form-full">

						<fieldset>
							
							<legend>
								<span style="display: block; float: left;">Préconisations</span>
								<span style="display: block; float: right;"><a href="#"><i class="fa fa-ellipsis-v"></i></a></span>
								<span style="display: block; clear: both;"></span>
							</legend>
							
							<div class="preco-text">
								<p style="text-align: justify;">
									Cette section vous permet de mettre en oeuvre une stratégie de préconisation de parcours. 
									Il vous suffit de saisir au préalable la ou les domaines (parcours, temps, ...) que vous souhaitez voir travailler par l'utilisateur, en cliquant sur "Ajouter une nouvelle préconisation".
									Ces préconisations pourront ensuite être attribuées à l'ensemble des compétences ou pour chacune d'entre elles. 
									Ceci fait, il vous est possible de définir des intervalles (en pourcentage) des résultats obtenus lors de la passation du positionnement. 
									Ces intervalles correspondent à des seuils à partir desquels vous établissez une préconisation.
								</p>

								<p style="text-align: left;">
									Ex : Pour une compétence en calcul.
									<ul>
										<li>- De 0% à 40% -> 50 heures de formation préconisées.</li>
										<li>- De 40% à 60% -> 30 heures de formation préconisées.</li>
										<li>- ...</li>
									</ul>
								</p>
							</div>
							
							<div class="preco-content">

								<div id="type-preco-section">
									
									<div class="type-title">Présaisir les types de préconisation :</div>
									
									<div class="type-text">

										<select id="type-preco-cbox" name="type_preco_cbox_edit" style="width: 200px;" <?php echo $formData['disabled']; ?>>
											<option value="select_cbox">---</option>
											<?php
											if (isset($response['type_preco']) && !empty($response['type_preco']))
											{
												foreach($response['type_preco'] as $type)
												{
													$selected = "";
													if (!empty($formData['ref_type_preco_edit']) && $formData['ref_type_preco_edit'] == $type->getId())
													{
														$selected = "selected";
													}				
													
													echo '<option value="'.$type->getId().'" '.$selected.'>'.$type->getNom().'</option>';	
												}
											}
											?>
										</select>

										<button type="submit" id="add-type-preco" name="add_type_preco" class="square-btn" value="" <?php echo $formData['disabled']; ?>><i class="fa fa-plus"></i></button>
										<input type="text" id="type-preco" name="nom_type_preco" value="" placeholder="Ex: 10 heures" style="width: 100px; margin: 0 5px;" />

										<button type="submit" id="edit-type-preco" name="edit_type_preco" class="square-btn" value="" <?php echo $formData['disabled']; ?>><i class="fa fa-pencil"></i></button>
										<button type="submit" id="save-type-preco" name="save_type_preco" class="square-btn" value="" <?php echo $formData['disabled']; ?>><i class="fa fa-refresh"></i></button>
										<button type="submit" id="suppr-type-preco" name="suppr_type_preco" class="square-btn" value="" <?php echo $formData['disabled']; ?>><i class="fa fa-times"></i></button>
										
									</div>
									
									<hr />

								</div>



								<div id="add-preco-button">
									<input type="button" id="add-preco" name="add_preco" class="bt-admin-menu-ajout" style="width: 200px;" value="Ajouter une préconisation" <?php echo $formData['disabled']; ?> />
								</div>
						

								<ul class="preco-list">
									
									<?php

									$nbrPrecos = 1;

									if (isset($formData['precos']) && !empty($formData['precos']))
									{
										$nbrPrecos = (count($formData['precos']) > 0) ? count($formData['precos']) : 1;
									}

									for ($i = 0; $i < $nbrPrecos; $i++) 
									{
										
										echo '<li class="preco-item">';
										
										echo '<span class="anchor">::</span> ';



										if (isset($formData['precos'][$i]) && !empty($formData['precos'][$i]))
										{
											echo '<input type="hidden" name="preco_active[]" class="preco-active" value="1" />';

											if (!empty($formData['precos'][$i]['ref_preco']))
											{
												echo '<input type="hidden" name="ref_preco[]" value="'.$formData['precos'][$i]['ref_preco'].'" />';
											}
											else
											{
												echo '<input type="hidden" name="ref_preco[]" value="" />';
											}

											echo '<input type="hidden" name="num_ordre_preco[]" class="num-ordre" value="'.$formData['precos'][$i]['num_ordre_preco'].'" />';
											echo 'De<input type="text" name="preco_min[]" value="'.$formData['precos'][$i]['preco_min'].'" placeholder="Ex: 0" />&nbsp;%';
											echo '&nbsp; à<input type="text" name="preco_max[]" value="'.$formData['precos'][$i]['preco_max'].'" placeholder="Ex: 20" />&nbsp;%';
										}
										else
										{
											echo '<input type="hidden" name="preco_active[]" class="preco-active" value="0" />';
											echo '<input type="hidden" name="ref_preco[]" value="" />';
											echo '<input type="hidden" name="num_ordre_preco[]" class="num-ordre" value="1" />';
											echo 'De<input type="text" name="preco_min[]" value="" placeholder="Ex: 0" />&nbsp;%';
											echo '&nbsp; à<input type="text" name="preco_max[]" value="" placeholder="Ex: 20" />&nbsp;%';
										}

										echo '<span class="preco-icon"><i class="fa fa-arrow-right"></i></span>';

										echo '<select class="choix-type-preco-cbox" name="choix_type_preco_cbox[]" '.$formData['disabled'].'>';
											echo '<option value="select_cbox">---</option>';

											if (isset($response['type_preco']) && !empty($response['type_preco']))
											{
												foreach($response['type_preco'] as $type)
												{
													$selected = "";
													if (!empty($formData['precos'][$i]['ref_type_preco']) && $formData['precos'][$i]['ref_type_preco'] == $type->getId())
													{
														$selected = "selected";
													}				
													
													echo '<option value="'.$type->getId().'" '.$selected.'>'.$type->getNom().'</option>';	
												}
											}
											/*
											if (!empty($formData['ref_organ_cbox']) && $formData['ref_organ_cbox'] === "Nouveau")
											{
												$selected = "selected";

												echo '<option value="new" '.$selected.' style="font-weight:bold;">Autre</option>';
											}
											*/

										echo '</select>';
										
										/*
										<span class="preco-icon">
											<i class="fa fa-plus-square"></i>
										</span>
										*/
										echo '<span class="del-preco preco-icon">';
										echo '<i class="fa fa-times"></i>';
										echo '</span>';
										
										echo '</li>';

									} 
									?>

								</ul>
							</div>

							<div style="clear:both"></div>

						</fieldset>
					</div>
				</div>

				<?php endif; ?>

				
				
				

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

	
		<!-- <div style="clear:both;"></div> -->


		<?php
			// Inclusion du footer
			require_once(ROOT.'views/templates/footer_old.php');
		?>

	</div>


	<!-- Inclusion d'une boîte modal dédiée à la saisie et à l'enregistrement d'un type -->
	<?php if (Config::ALLOW_PRECONISATION) : ?>

		<!-- <div id="modal-box"></div> -->

	<?php endif; ?>
	<!-- Template form ajout type -->
	<!--
	<div class="modal-box">

		<form id="type-form" action="<?php //echo $form_url; ?>" method="post">
			
			<div class="modal-box-title">Ajouter un type</div>
			
			<div class="modal-box-text">
				<p>Saisissez une description courte du type</p>
				<input type="text" value="" placeholder="Ex : 10 heures de formation civique" />
			</div>

			<div class="modal-box-buttons">
				<button type="submit" class="default">Annuler</button>
				<button type="submit" class="primary">Enregistrer</button>
			</div>

		</form>

	</div>
	-->



	<!-- js -->
	
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-ui-1.10.3.custom.all.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/modal-box.js" type="text/javascript"></script>

	<script type="text/javascript">
		

		$(function() {

			var self = this;
			var mode = $('#mode').val();

			//$('#precos').hide();
			//$('#type-preco-section').hide();
			$('#type-preco').hide();


			/* Vide le formulaire */

			this.resetFieldsValues = function() {

				$('.num-indicator').text('');
				$('#nom_cat').val('');
				$('#ref_parent_cbox').val('select_cbox');
				$('#ordre-cat').val('');
				$('#descript-cat').val('');
			};


			/* Rempli le formulaire avec les valeurs en paramètres */

			this.setFieldsValues = function(code, name, parentCode, order, descript) {
				
				$('.num-indicator').text('N°' + code);
				$('#nom_cat').val(name);
				$('#ref_parent_cbox').val(parentCode);
				$('#ordre-cat').val(order);
				$('#descript-cat').val(descript);

				//console.log($('.num-indicator').html());
			};

			/* Supprime une élément 'préconisation' */

			this.delPreco = function($precoItem) {

				console.log($precoItem);

				$precoItem.remove();
			};


			/* Trouve la catégorie parente */

			this.getParentCode = function(code) {

				var parentCode = null;

				code = code.toString();

				var parentCodeLength = code.length - 2;

				if (parentCodeLength > 0) {

					parentCode = code.substring(0, parentCodeLength);
					return parentCode;
				}

				return false;
			}

			
			/* Modifie l'intitulé du boutton de suppression selon le contexte */
			if (mode == 'edit' || mode == 'del')
			{
				$('#del').val('Supprimer');
			}
			else if (mode == 'new')
			{
				$('#del').val('Annuler');
			}


			/*  Système de sélection de la liste des catégories à gauche */

			//if (mode != 'edit' && mode != 'delete')
			if (mode == 'view')
			{	
				//$('#del').val('Supprimer');

				var $selected = null;

				$('.cat-item-link').each(function() {

					if ($(this).hasClass('selected')) {
						$selected = $(this);
					}
				});

				$('.cat-item-link').on('click', function(event) {

					event.preventDefault();

					if ($selected !== null) {

						$selected.removeClass('selected');
					}

					$(this).addClass('selected');
					$selected = $(this);

					var code = $(this).find('.cat-item-code').html();
					$('#code').val(code);
					$('#edit').removeProp('disabled');
					//$('#add').removeProp('disabled');

					<?php //if (Config::ALLOW_AJAX) : ?>
					/*
						console.log(mode);

						if (mode == 'view') {

							$.post('<?php echo $form_url; ?>', {"ref_cat": code}, function(data) {

								if (data.error) {

									alert(data.error);
								}
								else {

									var parentCode = self.getParentCode(code);
									//console.log(parentCode);

									self.setFieldsValues(code, data.results.nom_cat, parentCode, data.results.ordre_cat, data.results.descript_cat)
								}
								
							}, 'json');
						}
					*/
					<?php //endif; ?>
					
					
					

					//$(this).blur();
				});
			}

			// Ajout d'une nouvelle préconisation par duplication
			//console.log($('#add-preco'));
			
			
			
			if (mode == 'edit' || mode == 'new') {

				$('#precos').fadeIn(500);

				// Rend les éléments de la liste des préconisations déplaçables et triables
				$(".preco-list").sortable();

				var i = 1;
				var $item;
				$numItemPreco = 0;
				
				// Ajout d'une nouvelle préconisation par duplication
				$('#add-preco').on('click', function(event) {

					event.preventDefault();
					i++;
					$item = $('.preco-item:first').clone();
					$('.preco-list').append($item);
					$('.preco-item-num strong:last').replaceWith('<strong>' + i + '</strong>');
				});
				/*
				$('#add-preco').on('click', function(event) {

					//event.preventDefault();
					$item = $('.preco-item:last').clone();
					var num = $item.find('.num-ordre').val();
					$(".preco-list").append($item);

					num++;
					$('.preco-item:last').find('.num-ordre').val(num);
				});
				*/
			
				$('.del-preco').on('click', function(event) {

					var num = $(this).siblings('.preco-item-num').val()
					//$(this).parent('.preco-item').addClass('selected');
					self.delPreco(num);
				});



				$("#add-type-preco").on('click', function (event) {

					event.preventDefault();
					$('#type-preco').show();
				});


				$("#edit-type-preco").on('click', function (event) {

					event.preventDefault();
				});
				

				$("#save-type-preco").on('click', function (event) {

					event.preventDefault();

					var refType = null;
					var nomType = '';

					<?php if (Config::ALLOW_AJAX) : ?>

					if ($('#type-preco-cbox').val() !== '' && $('#type-preco-cbox').val() !== 'select_cbox')
					{
						refType = $('#type-preco-cbox').val();
					}

					console.log(refType);

					nomType = $('#type-preco').val();

					console.log(nomType);

					if (nomType !== '' && nomType !== null)
					{
						$.post('<?php echo $form_url; ?>', {'ref_type': refType, 'nom_type': nomType}, function(data) {

							if (data.error) {

								alert(data.error);
							}
							else {

								//var parentCode = self.getParentCode(code);
								//console.log(parentCode);

								//self.setFieldsValues(code, data.results.nom_cat, parentCode, 0, data.results.descript_cat)
							}
							
						}, 'json');
					}
					else
					{
						alert('Vous devez saisir un type de préconisation pour pouvoir l\'enregistrer.');
					}

					<?php endif; ?>
				});


				$("#suppr-type-preco").on('click', function (event) {

					event.preventDefault();
				});
				
				
				//$('.choix_type_preco_cbox').on('change', function(event) {

					/*** Gestion de la requête pour éditer un type dans la liste des types ***/

					//if ($(this).val() == 'new') {
						//console.log('onChangeParcours');
						//var refParcours = $(this).val();
						//console.log(refParcours);

						//$('#type-preco-section').show();
					//}
					//else {

						//$('#type-preco-section').hide();
					//}
						/*
						<?php if (Config::ALLOW_AJAX) : ?>

							if (refParcours != 'select_cbox')
							{
								$.post('<?php echo $form_url; ?>', {'ref_type': refParcours}, function(data) {

									if (data.error) {

										alert(data.error);
									}
									else if (data.results) {

										console.log(data.results);
										$('#id-type').val(data.results.id_type);
										$('#nom-type').val(data.results.nom_type);
									}

								}, 'json');
							}

						<?php endif; ?>
						*/
					//};
					//});
				//});
				
				/*
				// Ajout d'une nouvelle préconisation par duplication
				$('#add-preco').on('click', function(event) {

					//event.preventDefault();
					$item = $('.preco-item:last').clone();
					var num = $item.find('.num-ordre').val();
					$(".preco-list").append($item);

					num++;
					$('.preco-item:last').find('.num-ordre').val(num);
				});


				$('.del-preco').on('click', function(event) {

					var precoItem = $(this).parent();
					precoItem.remove();

				});
				*/

				//$('.choix-type-preco-cbox').on('change', function(event) {

					// if ($(this).val() != 'select_cbox') 
					// {
					// 	$(this).parent().find('.preco-active').val('1');
					// }
					// else
					// {
					// 	$(this).parent().find('.preco-active').val('0');
					// }
				$('.choix-type-preco-cbox').each(function() {

					$(this).on('change', function(event) {

						if ($(this).val() != 'select_cbox') 
						{
							$(this).parent().find('.preco-active').val('1');
						}
						else
						{
							$(this).parent().find('.preco-active').val('0');
						}
					});
				});


				if (mode == 'edit')
				{
					/*
					$('#precos').fadeIn(500);

					// Rend les éléments de la liste des préconisations déplaçables et triables
					$(".preco-list").sortable();

					var i = 1;
					var $item;
					$numItemPreco = 0;
					
					// Ajout d'une nouvelle préconisation par duplication
					$('#add-preco').on('click', function(event) {

						event.preventDefault();
						i++;
						$item = $('.preco-item:first').clone();
						$(".preco-list").append($item);
						$('.preco-item-num strong:last').replaceWith('<strong>' + i + '</strong>');
					});
					/*
					/*
					$('#add-preco').on('click', function(event) {

						//event.preventDefault();
						$item = $('.preco-item:last').clone();
						var num = $item.find('.num-ordre').val();
						$(".preco-list").append($item);

						num++;
						$('.preco-item:last').find('.num-ordre').val(num);
					});
					*/
					/*
					$('.del-preco').on('click', function(event) {

						var precoItem = $(this).parent();
						precoItem.remove();

					});
					*/
				}
			}

			/*
			$('#add').click(function(event) {

				$('#del').val('Annuler');
			});
			*/





			/*** Gestion des éléments de la liste de préconisation ***/

			//$('#edit').click(function(event) {

				//event.preventDefault();
				
			//});

			/***  Fenêtre modale de gestion des parcours de la catégorie ***/
			/*
			$('#add-parcours').on('click', function(event) {

				event.preventDefault();

				var title = 'Ajouter un parcours';

				var contentText = '<p>Sélectionner un parcours pour l\'éditer ou le supprimer :</p>';
				contentText += '<select name="parcours_cbox" id="parcours_cbox" class="select-' + '<?php echo $formData["disabled"]; ?>' + '">';
				contentText += '<option value="select_cbox">Aucun</option>';

				<?php

				if (isset($response['parcours']) && !empty($response['parcours']))
				{
					foreach($response['parcours'] as $parcours)
					{
						$selected = "";
						if (!empty($formData['ref_parcours']) && $formData['ref_parcours'] == $parcours->getId())
						{
							$selected = "selected";
						}
						?>					
						contentText += '<option value="<?php echo $parcours->getId(); ?>" <?php echo $selected; ?>>- <?php echo $parcours->getNom(); ?></option>';	
						<?php
					}
				}

				?>

				contentText += '</select>';

				contentText += '<p>Ou saisissez la description d\'un nouveau parcours : </p>';
				contentText += '<input id="id-parcours" name="id_parcours" type="hidden" value="" />';
				contentText += '<input id="nom-parcours" name="nom_parcours" type="text" value="" placeholder="Ex : 10 heures de formation mathématiques" />';
				

				$.modalbox(
					{
						formId: '#form-parcours',
						action: '<?php echo $form_url; ?>',
						method: 'post'
					},
					title,
					contentText, 
					{
						buttons: [
							{
								'btnvalue': 'Annuler', 
								'btnclass': 'default'
							},
							{
								'btnvalue': 'Enregistrer', 
								'btnclass': 'primary'
							}
						], 
						callback: function(buttonText) {

							if (buttonText === 'Enregistrer') {

								$('#form-parcours').submit();
								//console.log('submit');
							}
						}
					},
					null,*//*
					{

						events: [
							// {
							// 	'type': 'change', 
							// 	'selector': '#parcours_cbox',
							// 	'callback': 'onChangeParcours'
							// }
						]
					},
					*//*
					'#modal-box'
				);
			});
			*/
			

			/*** Gestion de la requête pour éditer un type dans la liste des type ***/

			//$('#type_cbox').change(function(event) {

				//alert('change');
				/*
			onChangeParcours = function() {

				console.log('onChangeParcours');
				var refParcours = $('#type_cbox').val();
				
				<?php if (Config::ALLOW_AJAX) : ?>

					if (refParcours != 'select_cbox')
					{
						$.post('<?php echo $form_url; ?>', {'ref_type': refParcours}, function(data) {

							if (data.error) {

								alert(data.error);
							}
							else if (data.results) {

								console.log(data.results);
								$('#id-type').val(data.results.id_type);
								$('#nom-type').val(data.results.nom_type);
							}

						}, 'json');
					}

				<?php endif; ?>
			};
			//});
			*/
			







			/*** Gestion de la demande de suppression ***/
			//console.log($('#del').val());

			$('#del').on('click', function(event) 
			{
				event.preventDefault();
				/*
				if (mode == 'view') 
				{
					if (confirm("Voulez-vous réellement supprimer cette compétence ?"))
					{
						//$('input[name="delete"]').val("true");
						//$('#form-posi').submit();
					}
				}
				else*/

				if (mode == 'new')
				{
					if (confirm("Voulez-vous réellement effacer les données que vous avez saisi ?"))
					{
						self.resetFieldsValues();
						$('#mode').val('view');
						$('#form-posi').submit();
					}
				}
				else if (mode == 'edit')
				{
					// Retour au mode view
					//$('#mode').val('view');

					// $('#form-posi').submit();

					if (confirm("Voulez-vous réellement supprimer cette compétence ?"))
					{
						$('input[name="delete"]').val("true");
						$('#mode').val('view');
						$('#form-posi').submit();
					}
				}
				
			});
			
		});


	</script>
	   
	   