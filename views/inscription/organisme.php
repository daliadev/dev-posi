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


// url vers laquelle doit pointer le formulaire
$form_url = $response['url'];

?>

	
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
		
		<div class="clear"></div>-->

		
	
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
			<form class="form-inscript" id="form-inscript-organ" name="form_inscript_organ" action="<?php echo $form_url; ?>" method="post">
				
				<input type="hidden" name="ref_organ" value="<?php echo $formData['ref_organ']; ?>" />
				<input type="hidden" name="ref_intervenant" value="<?php echo $formData['ref_intervenant']; ?>" />
				
				
				<!-- Fieldsets parts -->
				<!-- <div class="fieldsets-parts"> -->
				<fieldset>

					<div class="fieldset-header" id="titre-organ">
						<i class="fa fa-cube"></i> <h2 class="fieldset-title"> Votre organisme</h2>
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

					<div id="first-part">
						
						<div class="form-group">
							<label for="code_identification">Code organisme -> (123456)</label>
							<input type="password" name="code_identification" class="form-control" id="code_identification" title="Entrer votre code organisme" value="" />
							<span id="code-help" class="help-block">Le code n'a pas été correctement saisi</span>
						</div>
						
					</div>

					<div id="second-part">
						
						<div class="form-group">
							<label for="ref_organ_cbox">Sélectionnez votre organisme</label>
							<select name="ref_organ_cbox" id="ref_organ_cbox" class="form-control">
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
							<span id="ref-organ-help" class="help-block">Sélectionnez un organisme dans la liste</span>
						</div>
					</div>
					
					<div id="third-part" class="sub-form">
						
						<div class="form-group">
							<label for="nom_organ">Nom de votre organisme</label>
							<input type="text" name="nom_organ" id="nom_organ" class="form-control" value="<?php echo $formData['nom_organ']; ?>" />
							<span id="organ-name-help" class="help-block">Veuillez saisir le nom de l'organisme</span>
						</div>
						<div class="form-group">
							<label for="code_postal_organ">Code postal</label>
							<input type="tel" name="code_postal_organ" id="code_postal_organ" class="form-control" value="<?php echo $formData['code_postal_organ']; ?>" title="Ex:76000" />
							<span id="organ-postal-help" class="help-block">Le code postal est incorrect</span>
						</div>
						<div class="form-group">
							<label for="tel_organ">Téléphone</label>
							<input type="tel" name="tel_organ" id="tel_organ" class="form-control" value="<?php echo $formData['tel_organ']; ?>" />
							<span id="organ-tel-help" class="help-block">Le numéro de téléphone n'a pas été correctement saisi</span>
						</div>

					</div>

					<div id="fourth-part">
								
						<?php if (Config::ALLOW_REFERENT_INPUT == 1 || count(Config::$emails_referent) == 0) : ?>
						
						<div class="form-group">		
							<label for="email_intervenant">Email formateur</label>
							<input type="email" name="email_intervenant" id="email-intervenant" class="form-control"  value="<?php echo $formData['email_intervenant']; ?>" title="Format email requis(exemple@xxx.yy)" placeholder="exemple@xxx.yy" autocomplete="off" />
							<!-- Autocompletion -->
							<!-- <div class="interv-container">
								<div id="interv-results" class=""></div>
							</div> -->
							<span id="email-inter-help" class="help-block">Vous devez saisir une adresse email valide (exemple@domaine.fr)</span>
						</div>

						<?php elseif (isset(Config::$emails_referent) && is_array(Config::$emails_referent) && count(Config::$emails_referent) > 0) : ?>
						
						<div class="form-group">	
							<label for="ref_inter_cbox">Email formateur</label>
							<select name="ref_inter_cbox" id="ref_inter_cbox" class="form-control">
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
							<span id="ref-inter-help" class="help-block">Sélectionnez l'adresse email du référent dans la liste</span>
						</div>

						<?php endif; ?>

					</div>


					<button type="submit" name="submit_organ" class="btn btn-primary" id="submit-organ" title="Cliquez sur ce bouton pour continuer">Continuer</button>

				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>
