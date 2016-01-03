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
//$formData['date_naiss_user'] = "";
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
	

	


		<div class="content-form-small">
			
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

			<form class="form-inscript" id="form-inscript-user" name="form_inscript_user" action="<?php echo $form_url; ?>" method="post">
				
				<input type="hidden" name="ref_user" value="<?php echo $formData['ref_user']; ?>" />
                <input type="hidden" name="name_validation" id="name-validation" value="<?php echo $formData['name_validation']; ?>" />
				
				<fieldset>
					
					<div class="fieldset-header" id="titre-user">
						<i class="fa fa-user"></i> <h2 class="fieldset-title"> Votre profil</h2>
					</div>

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
					?>
					
					<div class="form-group">
						<label for="nom_user">Nom de famille<!--  <span class="asterix">*</span> --></label>
						<input type="text" name="nom_user" id="nom_user" class="form-control" value="<?php echo $formData['nom_user']; ?>" title="Saisissez votre nom" placeholder="Ex: Durand" />
						<span class="help-block">Indiquez votre nom de famille</span>
					</div>

					<div class="form-group">
						<label for="prenom_user">Prénom<!--  <span class="asterix">*</span> --></label>
						<input type="text" name="prenom_user" id="prenom_user" class="form-control" value="<?php echo $formData['prenom_user']; ?>" title="Saisissez votre prénom" placeholder="Ex: Alain" />
						<span class="help-block">Indiquez votre prénom</span>
					</div>

					<!-- Birth date multi-select -->
					<!-- <p class="form-text" style="margin-top: 15px;">Date de naissance</p> -->
					<label style="display: block;">Date de naissance</label>

					<!-- <div style="float: left; width: 26%;"> -->
					<div class="form-group" style="float: left; width: 25%;">

						<label for="jour_naiss_user_cbox">Jour</label>
						<select name="jour_naiss_user_cbox" id="jour_naiss_user_cbox" class="form-control">
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
						<!-- <span class="help-block">Sélectionnez le jour de votre naissance</span> -->

					</div>

					<!-- <div style="float: left; width: 40%; margin-left: 2%;"> -->
					<div class="form-group" style="float: left; width: 43%; margin-left: 2%;">

						<label for="mois_naiss_user_cbox">Mois</label>
						<select name="mois_naiss_user_cbox" id="mois_naiss_user_cbox" class="form-control">
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
						<!-- <span class="help-block">Sélectionnez le mois de votre naissance</span> -->
						
					</div>

					<!-- <div style="float: right; width: 30%;"> -->
					<div class="form-group" style="float: left; width: 28%;  margin-left: 2%;">

						<label for="annee_naiss_user_cbox">Année</label>
						<select name="annee_naiss_user_cbox" id="annee_naiss_user_cbox" class="form-control">
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
						<!-- <span class="help-block">Sélectionnez l'année de votre naissance</span> -->

						
					</div>
					
					<span id="help-birthdate" class="help-block">Sélectionnez le jour, le mois et l'année de votre naissance</span>

					<div class="clear"></div>

					
					<div class="form-group">
						<label for="ref_niveau_cbox">Niveau de formation<!--  <span class="asterix">*</span> --></label>
						<select name="ref_niveau_cbox" id="ref_niveau_cbox" class="form-control">
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
						<span class="help-block">Sélectionnez votre niveau d'étude dans la liste</span>
					</div>

					<button type="submit" name="submit_user" class="btn btn-primary" id="submit" title="Cliquez sur ce bouton pour continuer">Continuer</button>
					<!-- <div class="clear"></div> -->

				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>

	

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
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script> -->
	
	<!-- Easing animation -->
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/jquery.easing.1.3.min.js" type="text/javascript"></script> -->

	<!-- Bootstrap forms -->
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/bootstrap.min.js" type="text/javascript"></script> -->
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/bootstrap-select.min.js" type="text/javascript"></script> -->
	
	<!-- Plugin message modal -->
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/message-box.js" type="text/javascript"></script> -->
	
	
	<script language="javascript" type="text/javascript">

		// jQuery object

		//$(function() {
			
			// Gestion des listes déroulantes (select)
			//$('.selectpicker').selectpicker({style: 'custom-select'});

			// Focus sur le premier champ au démarrage de la page
			//$('#nom_user').focus();
			
			
			/* Fenêtre de validation du nom dupliqué */
			/*
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
			*/

		//});

	</script>

