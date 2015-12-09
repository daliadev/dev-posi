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
	

	<div id="posi-inscript" class="main">
		
		<div class="header">

			<div class="header-wrapper">

				<div class="logo">
					<!-- <img src="images/logo-dalia_40x40.png" alt="Positionnement Dalia"> -->
				</div>

				<div class="header-title">
					<h1>Test de positionnement <?php echo Config::CLIENT_NAME; ?></h1>
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
				
				<input type="hidden" name="ref_user" value="<?php echo $formData['ref_user']; ?>" />
                <input type="hidden" name="name_validation" id="name-validation" value="<?php echo $formData['name_validation']; ?>" />
				
				<fieldset>
					
					<div class="fieldset-title" id="titre-user">
						<i class="fa fa-user"></i> <h2 class="section-form"> Votre profil</h2>
					</div>

					<?php
				
						if (isset($response['errors']) && !empty($response['errors']))
						{ 
							
							echo '<div class="error-zone">';
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
							echo '<div class="zone-success">';
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
					
					<label for="nom_user">Nom<!--  <span class="asterix">*</span> --></label>
					<input type="text" name="nom_user" id="nom_user" class="input-text" value="<?php echo $formData['nom_user']; ?>" title="Saisissez votre nom" placeholder="Ex: Durand" />
					<span class="form-hint">Indiquez votre nom de famille</span>

					<label for="prenom_user">Prénom<!--  <span class="asterix">*</span> --></label>
					<input type="text" name="prenom_user" id="prenom_user" class="input-text" value="<?php echo $formData['prenom_user']; ?>" title="Saisissez votre prénom" placeholder="Ex: Alain" />
					<span class="form-hint">Indiquez votre prénom</span>

					<p class="form-text" style="margin-top: 15px;">Date de naissance<!--  <span class="asterix">*</span> --></p>
					
					<div id="date_naiss_user">
						
						<div style="float: left; width: 26%;">

							<label for="jour_naiss_user_cbox" class="form-text-small" >Jour</label>
							<select name="jour_naiss_user_cbox" id="jour_naiss_user_cbox" class="selectpicker">
								<option value="select_cbox">---</option>

								<?php
								
								for ($i = 1; $i <= 31; $i++)
								{
									$jour = $i;
									$selected = '';

									if (!empty($formData['jour_naiss_user_cbox']) && $formData['jour_naiss_user_cbox'] !== 'select_cbox' && $formData['jour_naiss_user_cbox'] == $i)
									{
										$selected = 'selected';
									}
									echo '<option value="'.$jour.'" '.$selected.'>'.$jour.'</option>';
								}
								
								?>

							</select>
							<span class="form-hint">Sélectionnez le jour de votre naissance dans la liste</span>

						</div>


						<div style="float: left; width: 40%; margin-left: 2%;">
			
							<label for="mois_naiss_user_cbox" class="form-text-small">Mois</label>
							<select name="mois_naiss_user_cbox" id="mois_naiss_user_cbox" class="selectpicker">
								<option value="select_cbox">---</option>

								<?php
								
								$monthsName = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');

								for ($i = 1; $i <= 12; $i++)
								{
									$nomMois = $monthsName[($i - 1)];
									$selected = "";

									if (!empty($formData['mois_naiss_user_cbox']) && $formData['mois_naiss_user_cbox'] !== "select_cbox" && $formData['mois_naiss_user_cbox'] == $i)
									{
										$selected = "selected";
									}

									echo '<option value="'.$i.'" '.$selected.'>'.$nomMois.'</option>';
								}

								?>

							</select>
							<span class="form-hint">Sélectionnez le mois de votre naissance dans la liste</span>
							
						</div>


						<div style="float: right; width: 30%;">

							<label for="annee_naiss_user_cbox" class="form-text-small">Année</label>
							<select name="annee_naiss_user_cbox" id="annee_naiss_user_cbox" class="selectpicker">
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
							<span class="form-hint">Sélectionnez l'année de votre naissance dans la liste</span>
							
						</div>

						<div class="clear"></div>
					</div>
					

					<label for="ref_niveau_cbox">Niveau de formation<!--  <span class="asterix">*</span> --></label>
					<select name="ref_niveau_cbox" id="ref_niveau_cbox" class="selectpicker">
						<option value="select_cbox">---</option>
						
						<?php
						
						foreach($response['niveau_etudes'] as $niveau)
						{
							$selected = "";
							if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] !== "select_cbox" && $formData['ref_niveau_cbox'] == $niveau->getId())
							{
								$selected = "selected";
							}
							//echo '<option value="'.$niveau->getId().'" title="'.$niveau->getDescription().'" '.$selected.'>'.$niveau->getNom().'</option>';
							echo '<option value="'.$niveau->getId().'" '.$selected.'>'.$niveau->getNom().'</option>';
						}
						
						?>

					</select>
					<span class="form-hint">Sélectionnez votre niveau d'étude dans la liste</span>


					<input type="submit" name="submit" class="button-primary action-button" id="submit" value="Continuer" title="Cliquez sur ce bouton pour continuer" />
					
					<div class="clear"></div>

				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>
		
		<!-- Footer -->
		<?php
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>

	

	<!-- Inclusion message d'erreur modal si erreur de doublon sur le nom et la date de naissance de l'utilisateur -->
	<div id="modal-message"></div>






	<!--
	<div id="content">

		<?php
			// Inclusion du header
			//require_once(ROOT.'views/templates/header_posi.php');
		?>


		<div id="utilisateur">
			<div class="zone-formu">
				
				<div class="titre-form" id="titre-utili">Utilisateur</div>
				

				<?php
					/*
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
					*/
				?>


				<form id="form-posi" action="<?php //echo $form_url; ?>" method="POST">
					
					<div class="form-small">

						<input type="hidden" value="<?php //echo $formData['ref_user']; ?>" name="ref_user">
						<input type="hidden" value="<?php //echo $formData['name_validation']; ?>" name="name_validation" id="name-validation">

						<div class="input">
							<label for="nom_user">Nom <span class="asterix">*</span></label>
							<input type="text" name="nom_user" id="nom_user" value="<?php //echo $formData['nom_user']; ?>" required>
						</div>

						<div class="input">
							<label for="prenom_user">Prénom <span class="asterix">*</span></label>
							<input type="text" name="prenom_user" id="prenom_user" value="<?php //echo $formData['prenom_user']; ?>" required>
						</div>


						<p style="margin-bottom:0px;">Date de naissance</p>

						<div class="input" style="float:left; width:90px;">
							<label for="jour_naiss_user_cbox">Jour <span class="asterix">*</span></label>
							<select name="jour_naiss_user_cbox" id="jour_naiss_user_cbox" style="width:80px;">
								<option value="select_cbox">---</option>

								<?php
								/*
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
								*/
								?>
							</select>

						</div>

						<div class="input" style="float:left; width:110px;">
							<label for="mois_naiss_user_cbox">Mois <span class="asterix">*</span></label>
							<select name="mois_naiss_user_cbox" id="mois_naiss_user_cbox" style="width:100px;">
								<option value="select_cbox">---</option>

								<?php
								/*
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
								*/
								?>
							</select>

						</div>

						<div class="input" style="float:left; width:100px;">
							<label for="annee_naiss_user_cbox">Année <span class="asterix">*</span></label>
							<select name="annee_naiss_user_cbox" id="annee_naiss_user_cbox" style="width:100px;">
								<option value="select_cbox">---</option>

								<?php
								/*
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
								*/
								?>
							</select>

						</div>

						<div style="clear:both;"></div>


						<div class="input">
							<label for="ref_niveau_cbox">Niveau de formation <span class="asterix">*</span></label>
							<select name="ref_niveau_cbox" id="ref_niveau_cbox">
								<option value="select_cbox">---</option>
								<?php
								/*
								foreach($response['niveau_etudes'] as $niveau)
								{
									$selected = "";
									if (!empty($formData['ref_niveau_cbox']) && $formData['ref_niveau_cbox'] != "select_cbox" && $formData['ref_niveau_cbox'] == $niveau->getId())
									{
										$selected = "selected";
									}
									echo '<option value="'.$niveau->getId().'" title="'.htmlentities($niveau->getDescription()).'" '.$selected.'>'.$niveau->getNom().'</option>';
								}
								*/
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
			//require_once(ROOT.'views/templates/footer.php');
		?>
		
	<div>
	-->
		
	
	<!-- JQuery -->
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
	
	<!-- Easing animation -->
	<script src="<?php echo SERVER_URL; ?>media/js/jquery.easing.1.3.min.js" type="text/javascript"></script>

	<!-- Bootstrap forms -->
	<script src="<?php echo SERVER_URL; ?>media/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/bootstrap-select.min.js" type="text/javascript"></script>
	
	<!-- Plugin message modal -->
	<script src="<?php echo SERVER_URL; ?>media/js/message-box.js" type="text/javascript"></script>
	
	
	<script language="javascript" type="text/javascript">

		// jQuery object
		$(function() {
			
			// Gestion des listes déroulantes (select)
			$('.selectpicker').selectpicker({style: 'custom-select'});

			// Focus sur le premier champ au démarrage de la page
			$('#nom_user').focus();
			
			/*
			// Gestion du formulaire organisme caché
			$('#third-part').hide();
			
			// Affichage du formulaire de saisie d'un nouvel organisme
			$('#second-part #ref_organ_cbox').change(function() {

				if ($(this).val() === "new") {

					$('#third-part').show(250);
				}
				else {

					$('#third-part').hide(250);
				}
			});
			*/



			/***   Gestion des erreurs   ***/

			/*
			$('.input-text').each(function() {

				if ($(this).parent().not('#third-part')) {

					$(this).bind({

						focus: function(event) {

							$(this).removeClass('error');
							$(this).next('.form-hint').hide();
						},
						blur : function(event) {

							if ($(this).val() === '') {

								$(this).addClass('error');
								$(this).next('.form-hint').show();
							}
						}
					});
				}
			});


			$('.selectpicker').each(function() {

				$(this).change(function() {

					if ($(this).val() === 'select_cbox') {

						$(this).siblings('.bootstrap-select').addClass('error');
						$(this).siblings('.form-hint').show();
					}
					else {

						$(this).siblings('.bootstrap-select').removeClass('error');
						$(this).siblings('.form-hint').hide();
					}
				});

				if ($(this).val() === 'new') {

					$('.input-text').each(function() {

						if ($(this).parent().is('#third-part')) {

							$(this).bind({

								focus: function(event) {

									$(this).removeClass('error');
									$(this).next('.form-hint').hide();
								},
								blur : function(event) {

									if ($(this).val() === '') {

										$(this).addClass('error');
										$(this).next('.form-hint').show();
									}
								}
							});
						}
					});
				}

			});
			*/


			/* Fenêtre de validation du nom dupliqué */

			if ($("#name-validation").val() === "false") {

				var messageString = 'Une personne portant le même nom a déjà effectuée un positionnement. S\'il s\'agit bien de vous, cliquez sur "Continuer".<br>Sinon, cliquez sur "Annuler" pour corriger la saisie de vos nom, prénom et date de naissance.';
				
				$.message(messageString, {
					icon: 'alert', 
					buttons: [
						{
							'btnvalue': 'Annuler', 
							'btnclass': 'button-default'
						},
						{
							'btnvalue': 'Continuer', 
							'btnclass': 'button-primary'
						}
					], 
					callback: function(buttonText) {
						
						if (buttonText === 'Continuer') {

							$('#name-validation').val('true');
							$('#form-inscription').submit();
							console.log('submit');
						}
						else {
							$('#name-validation').val('false');
						}
					}
				}, '#modal-message');
			}




			$('#submit').click(function(event) {

				var valid = true;
				/*
				$code = $('#code_identification');
				$organ = $('#ref_organ_cbox');
				$nomOrgan = $('#nom_organ');
				$codePostalOrgan = $('#code_postal_organ');
				$telOrgan = $('#tel_organ');
				$email = $('#email_intervenant');

				if ($code.val() === '') {

					$code.addClass('error');
					$code.next('.form-hint').show();
					valid = false;
				}
				else {

					$code.removeClass('error');
					$code.next('.form-hint').hide();
				}

				if ($email.val() === '') {

					$email.addClass('error');
					$email.next('.form-hint').show();
					valid = false;
				}
				else {

					$email.removeClass('error');
					$email.next('.form-hint').hide();
				}

				if ($organ.val() === 'new') {

					if ($nomOrgan.val() === '') {

						$nomOrgan.addClass('error');
						$nomOrgan.next('.form-hint').show();
						valid = false;
					}
					else {

						$email.removeClass('error');
						$email.next('.form-hint').hide();
					}

					if ($codePostalOrgan.val() === '' || isNaN(Number($codePostalOrgan.val())) || String($codePostalOrgan.val()).length != 5) {

						$codePostalOrgan.addClass('error');
						$codePostalOrgan.next('.form-hint').show();
						valid = false;
					}
					else {

						$codePostalOrgan.removeClass('error');
						$codePostalOrgan.next('.form-hint').hide();
					}
					
					if ($telOrgan.val() === '' || isNaN(Number($telOrgan.val())) || String($telOrgan.val()).length != 10) {

						$telOrgan.addClass('error');
						$telOrgan.next('.form-hint').show();
						valid = false;
					}
					else {

						$telOrgan.removeClass('error');
						$telOrgan.next('.form-hint').hide();
					}
					
				}
				

				$('.selectpicker').each(function() {

					if ($(this).val() === 'select_cbox') {

						$(this).siblings('.bootstrap-select').addClass('error');
						$(this).siblings('.form-hint').show();
						valid = false;
					}
					else {

						$(this).siblings('.bootstrap-select').removeClass('error');
						$(this).siblings('.form-hint').hide();
					}
				});
				*/
				

				if (valid) {

					$('#form-inscription').submit();
					//alert('submit ok');	
				}
				else {
					return false;
				}

				
			});


		});

	</script>

