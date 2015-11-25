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
				
				<div style="float:left;">

					<div class="zone-formu2">
	 
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

							</fieldset>
						</div>
					</div>
				</div>



				<!-- Partie droite : Affichage / détail de la compétence -->

				<div style="float:right;">

					<div class="zone-formu2">
						
						<div class="form-half">

							<div id="detail">
								
								<fieldset id="edit-cat">
								
									<legend>Ajout / détail d'une compétence</legend>
										
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
										<label for="parent_cat_cbox">Compétence parente *</label>

										<select name="parent_cat_cbox" id="ref_parent_cbox" class="select-<?php echo $formData['disabled']; ?>" <?php echo $formData['disabled']; ?>>
											<option value="select_cbox">Aucun</option>
											<?php
											
											foreach($response['categorie'] as $categorie)
											{
												$selected = "";
												if (!empty($formData['parent_code_cat']) && $formData['parent_code_cat'] == $categorie->getCode())
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
										<label for="ordre_cat">Ordre (pour l'organisation des compétences de même niveau)</label>
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

				
				<!-- Partie basse : Gestion des parcours / préconisations de la compétence -->
				<?php if (Config::ALLOW_PRECONISATION) : ?>

				<!-- <div> -->

					<div id="precos" class="zone-formu2">
	 
						<div id="preconisations" class="form-full">

							<fieldset>
								
								<legend><span style="display: block; float: left;">Préconisations de parcours</span><span style="display: block; float: right;"><a href="#"><i class="fa fa-ellipsis-v"></i></a></span><span style="display: block; clear: both;"></span></legend>
								
								<p>
									Cette section vous permet de mettre en oeuvre une stratégie de préconisation de parcours. 
									Il vous suffit de saisir au préalable la ou les domaines (parcours, temps, ...) que vous souhaitez voir travailler par l'utilisateur, en cliquant sur "Ajouter une nouvelle préconisation".
									Ces préconisations pourront ensuite être attribuées à l'ensemble des compétences ou pour chacune d'entre elles. 
									Ceci fait, il vous est possible de définir des intervalles (en pourcentage) des résultats obtenus lors de la passation du positionnement. 
									Ces intervalles correspondent à des seuils à partir desquels vous établissez une préconisation.
								</p>
								<p>
									Ex : Pour une compétence en calcul.
										<ul>
											<li>- De 0% à 40% -> 50 heures de formation préconisées en mathématiques.</li>
											<li>- De 40% à 60% -> 30 heures de formation préconisées en mathématiques.</li>
											<li>- ...</li>
										</ul>
								</p>

								<div>
									
									<a id="add-parcours" class="add-link" href="#liste-cat"><p style="line-height: 16px;">
										<span class="fa-stack fa-1x">
											<i class="fa fa-circle-o fa-stack-2x"></i>
											<i class="fa fa-plus fa-stack-1x"></i>
										</span>
										<strong>1 - Commencer par ajouter un (ou des) nouveau(x) parcours.</strong>
									</p></a>

									<a id="add-preco" class="add-link" href="#precos"><p style="line-height: 16px;">
										<span class="fa-stack fa-1x">
											<i class="fa fa-circle-o fa-stack-2x"></i>
											<i class="fa fa-plus fa-stack-1x"></i>
										</span>
										<strong> 2 - Ensuite, créer une nouvelle préconisation, en saisissant des valeurs minimum et maximun, en pourcentage.
										Répéter l'opération pour que l'ensemble des préconisations pour cette catégorie couvre 0 à 100% des résultats.</strong>
									</p></a>

									<!-- <a href="#"><p><i class="fa fa-plus-circle"></i> Ajouter un découpage intervalaire pour cette categorie.</p></a> -->
									
								</div>

								<hr />

								<ul class="preco-list">

									<li class="preco-item">
										<span class="preco-item-num"><strong>1</strong></span>
										De 
										<input type="text" name="precoMin[]" value="" placeholder="Ex : 75" /> % 
										&nbsp;&nbsp;à 
										<input type="text" name="precoMax[]" value="" placeholder="Ex : 100" /> % 

										<span class="arrow">
											<i class="fa fa-long-arrow-right fa-lg"></i>
										</span>

										<select name="parcours-cbox">
											<option value="select-cbox">---</option>
											<option value="1">Parcours 1</option>
											<option value="2">Parcours 2</option>
										</select>
									</li>

								</ul>

							</fieldset>
						</div>
					</div>
				<!-- </div> -->
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

	
		<div style="clear:both;"></div>


		<?php
			// Inclusion du footer
			require_once(ROOT.'views/templates/footer_old.php');
		?>

	</div>


	<!-- Inclusion d'une boîte modal dédiée à la saisie et à l'enregistrement d'un parcours -->
	<?php if (Config::ALLOW_PRECONISATION) : ?>

		<div id="modal-box"></div>

	<?php endif; ?>
	<!-- Template form ajout parcours -->
	<!--
	<div class="modal-box">

		<form id="parcours-form" action="<?php //echo $form_url; ?>" method="post">
			
			<div class="modal-box-title">Ajouter un parcours</div>
			
			<div class="modal-box-text">
				<p>Saisissez une description courte du parcours</p>
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

			// Sauvegarde du premier élément de la liste des préconisations pour duplication
			//var $precoItem = $('.preco-item');

			// Rend les éléments de la liste des préconisations déplaçables
			$(".preco-list").sortable();


			/* Vide le formulaire */

			this.resetFieldsValues = function() {

				$('#nom_cat').val('');
				$('#ref_parent_cbox').val('select_cbox');
				$('#ordre_cat').val('');
				$('#descript_cat').val('');
			};


			/* Rempli le formulaire avec les valeurs en paramètres */

			this.setFieldsValues = function(name, parentCode, order, descript) {

				$('#nom_cat').val(name);
				$('#ref_parent_cbox').val(parentCode);
				$('#ordre_cat').val(order);
				$('#descript_cat').val(descript);

			}


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

			
			



			/*  Système de sélection de la liste des catégories à gauche */

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

				<?php if (Config::ALLOW_AJAX) : ?>

					console.log(mode);

					if (mode === 'view') {

						$.post('<?php echo $form_url; ?>', {"ref_cat":code}, function(data) {

							if (data.error) {

								alert(data.error);
							}
							else {

								var parentCode = self.getParentCode(code);
								console.log(parentCode);

								self.setFieldsValues(data.results.nom_cat, parentCode, 0, data.results.descript_cat)
							}
							
						}, 'json');
					}

				<?php endif; ?>
			});

			/*
			$('#add').click(function(event) {

				//$('#del').val('Annuler');
			});

			$('#edit').click(function(event) {

				//$('#del').val('Annuler');
			});
			*/


			/*** Gestion des éléments de la liste de préconisation ***/
			var i = 1;
			var $item;
			$numItemPreco = 0;
			
			$('#add-preco').on('click', function(event) {

				event.preventDefault();
				i++;
				$item = $('.preco-item:first').clone();
				$(".preco-list").append($item);
				$('.preco-item-num strong:last').replaceWith('<strong>' + i + '</strong>');
			});


			/***  Fenêtre modale d'ajout d'un parcours ***/
			
			//if ($("#name-validation").val() === "false") {
			$('#add-parcours').on('click', function(event) {

				event.preventDefault();

				var title = 'Ajouter un parcours';
				var contentText = '<p>Sélectionner un parcours pour l\'éditer ou le supprimer :</p>';
				contentText += '<select name="parcours_cbox" id="parcours_cbox" class="select-<?php echo $formData['disabled']; ?>">';
				contentText += '<option value="select_cbox">Aucun</option>';

				/*
				<?php
				foreach($response['categorie'] as $categorie)
				{
					$selected = "";
					if (!empty($formData['parent_code_cat']) && $formData['parent_code_cat'] == $categorie->getCode())
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
				*/
				
				contentText += '</select>';
				contentText += '<p>Ou saisissez la description d\'un nouveau parcours : </p>'
				contentText += '<input type="text" value="" placeholder="Ex : 10 heures de formation civique" />';
				
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

							console.log('Callback calling');
							/*
							if (buttonText === 'Enregistrer') {

								$('#name-validation').val('true');
								$('#form-inscription').submit();
								console.log('submit');
							}
							else {
								$('#name-validation').val('false');
							}
							*/
						}
					}, 
					'#modal-box'
				);
			});
			//}
			/*
			<div class="modal-box">

				<form id="parcours-form" action="<?php //echo $form_url; ?>" method="post">
					
					<div class="modal-box-title">Ajouter un parcours</div>
					
					<div class="modal-box-text">
						<p>Saisissez une description courte du parcours</p>
						<input type="text" value="" placeholder="Ex : 10 heures de formation civique" />
					</div>

					<div class="modal-box-buttons">
						<button type="submit" class="default">Annuler</button>
						<button type="submit" class="primary">Enregistrer</button>
					</div>

				</form>

			</div>
			*/


			/*** Gestion de la demande de suppression ***/

			$('#del').on('click', function(event) 
			{
				event.preventDefault();

				if (mode == 'view') 
				{
					if (confirm("Voulez-vous réellement supprimer cette compétence ?"))
					{
						$('input[name="delete"]').val("true");
						$('#form-posi').submit();
					}
				}
				else if (mode == 'new')
				{
					if (confirm("Voulez-vous réellement effacer les données que vous avez saisi ?"))
					{
						resetFieldsValues();
					}
				}
				
			});
			
		});

	</script>
	   
	   