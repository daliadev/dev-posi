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
				
				<input type="hidden" name="ref_organ" value="<?php echo $formData['ref_organ']; ?>" />
				<input type="hidden" name="ref_intervenant" value="<?php echo $formData['ref_intervenant']; ?>" />

				
				<!-- Fieldsets parts -->
				<!-- <div class="fieldsets-parts"> -->
				<fieldset>

					<div class="fieldset-title" id="titre-organ">
						<i class="fa fa-cube"></i> <h2 class="section-form"> Votre organisme</h2>
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

					<div id="first-part">

						<label for="code_identification">Code organisme<!--  <span class="asterix">*</span> --></label>
						<br/>
						<input type="password" name="code_identification" class="input-text" id="code_identification" title="Entrer votre code organisme" value="" />
						<span class="form-hint">Le code n'a pas été correctement saisi</span>

					</div>

					<div id="second-part">

						<label for="ref_organ_cbox">Sélectionnez votre organisme<!--  <span class="asterix">*</span> --></label><br/>
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
						<span class="form-hint">Sélectionnez un organisme dans la liste</span>

					</div>
					
					<div id="third-part" class="sub-form">

						<label for="nom_organ">Nom de votre organisme<!--  <span class="asterix">*</span> --></label><br/>
						<input type="text" name="nom_organ" id="nom_organ" class="input-text" value="<?php echo $formData['nom_organ']; ?>" />
						<span class="form-hint">Veuillez saisir le nom de l'organisme</span>

						<label for="code_postal_organ">Code postal<!--  <span class="asterix">*</span> --></label><br/>
						<input type="tel" name="code_postal_organ" id="code_postal_organ" class="input-text" value="<?php echo $formData['code_postal_organ']; ?>" title="Ex:76000" />
						<span class="form-hint">Le code postal est incorrect</span>

						<label for="tel_organ">Téléphone<!--  <span class="asterix">*</span> --></label><br/>
						<input type="tel" name="tel_organ" id="tel_organ" class="input-text" value="<?php echo $formData['tel_organ']; ?>" />
						<span class="form-hint">Le numéro de téléphone n'a pas été correctement saisi</span>

					</div>

					<div id="fourth-part">
								
						<?php if (Config::ALLOW_REFERENT_INPUT == 1 || count(Config::$emails_referent) == 0) : ?>
								
							<label for="email_intervenant">Email formateur<!--  <span class="asterix">*</span> --></label><br/>
							<input type="email" name="email_intervenant" id="email-intervenant" class="input-text"  value="<?php echo $formData['email_intervenant']; ?>" title="Format email requis(exemple@xxx.yy)" placeholder="exemple@xxx.yy" autocomplete="off" />
							
							<!-- Autocompletion -->
							<div id="interv-results"></div>

							<span class="form-hint">Vous devez saisir une adresse email valide (exemple@domaine.fr)</span>

						<?php elseif (isset(Config::$emails_referent) && is_array(Config::$emails_referent) && count(Config::$emails_referent) > 0) : ?>
								
							<label for="ref_inter_cbox">Email formateur<!--  <span class="asterix">*</span> --></label><br/>
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
							<span class="form-hint">Sélectionnez l'adresse email du référent dans la liste</span>

						<?php endif; ?>

					</div>


					<input type="submit" name="submit_organ" class="button-primary action-button" id="submit" value="Continuer" title="Cliquez sur ce bouton pour continuer" />

					<div class="clear"></div>

				</fieldset>

				<!-- </div> -->

			</form>

		</div>
		 
		<div class="clear"></div>
		
		<!-- Footer -->
		<?php
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>



	
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
			
			// Gestion des listes déroulantes (select)
			$('.selectpicker').selectpicker({style: 'custom-select'});

			// Focus sur le premier champ au démarrage de la page
			$('#code_identification').focus();
			
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




			/***   Gestion des erreurs   ***/


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




			$('#submit').click(function(event) {

				var valid = true;

				$code = $('#code_identification');
				$organ = $('#ref_organ_cbox');
				$nomOrgan = $('#nom_organ');
				$codePostalOrgan = $('#code_postal_organ');
				$telOrgan = $('#tel_organ');
				$email = $('#email-intervenant');

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

				

				if (valid) {

					$('#form-inscription').submit();
					//alert('submit ok');	
				}
				else {
					return false;
				}

				
			});


			
			/* Recherche des émails d'intervenant dynamique en ajax */

			<?php if (Config::ALLOW_AJAX) : ?>


			// Effectue une requête et récupère les résultats

			/*
			function getResult(searchStr) {

				
				var xhr = new XMLHttpRequest();

				xhr.open('POST', 'autocomplete.php');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

				xhr.onreadystatechange = function() {

					if (xhr.readyState == 4 && xhr.status == 200) {
						
						displayResult(xhr.responseText);
					}
				}

				var searchString = encodeURIComponent(this.value);
				xhr.send('searchstr=' + searchStr);

				return xhr;
				
			}
			*/


			// Affiche les résultats d'une requête
			/*
			function displayResult(response) {

				// On cache le conteneur si on n'a pas de résultats
				if (response.length > 0) {

					divResults.style.display = 'block';
				}
				else {

					divResults.style.display = 'none';
				}
				
				// On ne modifie les résultats que si on en a obtenu		
				if (response.length > 0) {

					// On parse la réponse de la requête afin d'obtenir les résultats dans un tableau
					response = response.split('|');
					//var responseLength = response.length;
					
					// On vide les anciens résultats
					divResults.innerHTML = "";


					// On parcourt les nouveaux résultats
					for (var i = 0, count = response.length; i < count; i++) {

						// Ajout d'un nouvel élément <div>
						var divResult = divResults.appendChild(document.createElement('div'));
						divResult.className = "result";
						
						divResult.innerHTML = response[i];

						// Le résultat sera choisi s'il est cliqué
						divResult.onclick = function(event) {

							chooseResult(this);
						};
					}
				}
				
			}
			*/


			// Choisit un des résultats d'une requête et gère tout ce qui y est attaché
			/*
			function chooseResult(result) {

				// On change le contenu du champ de recherche et on enregistre en tant que précédente valeur
				previousValue = result.innerHTML;
				searchField.value = previousValue;

				// On cache les résultats
				divResults.style.display = 'none';

				// On supprime l'effet de focus
				result.className = '';

				// On remet la sélection à zéro
				selectedIndex = -1;

				// Si le résultat a été choisi par le biais d'un clic, alors le focus est perdu, donc on le réattribue
				searchField.focus();
			}
			*/


			var $searchField = $('#email-intervenant');
			var $resultsList = $('#interv-results');
			
			var selectedIndex = -1;
			var previousValue = $searchField.val();

			var url = $('#form-inscription').attr('action');



			$searchField.keyup(function(evt) {


				//var evt = e || window.event;

				// On récupére chaque 'div' contenues dans le bloc results
				//var resultsDivs = divResults.getElementsByTagName('div');
				var $resultsElements = $resultsList.find('div');


				
				// Si la touche pressée est la flèche "haut"
				if (evt.keyCode == 38 && selectedIndex > -1) {

					// On retire la classe de l'élément inférieur et on décrémente la variable "selectedIndex"
					$resultsElements.eq(selectedIndex--).removeClass('result');

					// Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
					if (selectedIndex > -1) {

						// On applique une classe à l'élément actuellement sélectionné
						$resultsElements.eq(selectedIndex).addClass('result-focus');
					}
				}


				// Si la touche pressée est la flèche "bas"
				else if (evt.keyCode == 40 && selectedIndex < $resultsElements.length - 1) {

					// On affiche les résultats "au cas où"
					$resultsList.css('display', 'block');
					
					// Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
					if (selectedIndex > -1) {
						
						$resultsElements.eq(selectedIndex).removeClass('result');
					}
					
					$resultsElements.eq(++selectedIndex).addClass('result-focus');
				}


				// Si la touche pressée est la touche "Entrée"
				else if (evt.keyCode == 13) {

					alert('Enter');
					//chooseResult($resultsElements[selectedIndex]);
					var $resultElement = $resultsElements.eq(selectedIndex);

					// On change le contenu du champ de recherche et on enregistre en tant que précédente valeur
					previousValue = $resultElement.html();
					$searchField.val(previousValue);

					// On cache les résultats
					$resultsList.css('display', 'none');

					// On supprime l'effet de focus
					$resultElement.removeClass('result');

					// On remet la sélection à zéro
					selectedIndex = -1;

					// Si le résultat a été choisi par le biais d'un clic, alors le focus est perdu, donc on le réattribue
					$searchField.focus();
				}
				

				// Si le contenu du champ de recherche a changé
				else if ($('#email-intervenant').val() != previousValue) {

					// On change la valeur précédente par la valeur actuelle
					previousValue = $('#email-intervenant').val();

					// Si on a toujours une requête en cours, on l'arrête
					if (request && request.readyState < 4) {

						request.abort();
					}

					// On stocke la nouvelle requête
					//request = getResult(previousValue);

					$.post(url, {"search_interv": previousValue, "ref_organ": refOrgan}, function (data) {

						if (data.error) {

							alert(data.error);
							//$('#testjson').fadeOut();
							//$('#testjson').before(data.retour);
						}
						else {

							// On cache le conteneur si on n'a pas de résultats
							if (data.length > 0) {

								$resultsList.css('display', 'block');
							}
							else {

								$resultsList.css('display', 'none');
							}
							
							// On ne modifie les résultats que si on en a obtenu		
							if (data.length > 0) {

								// On parse la réponse de la requête afin d'obtenir les résultats dans un tableau
								var datasTab = data.split('|');
								//var responseLength = response.length;
								
								// On vide les anciens résultats
								$resultsList.html('');


								// On parcourt les nouveaux résultats
								for (var i = 0, count = datasTab.length; i < count; i++) {

									// Ajout d'un nouvel élément <div>
									var $divResult = $resultsList.append('div');
									$divResult.addClass('result');
									
									$divResult.html(datasTab[i]);

									// Le résultat sera choisi s'il est cliqué
									$divResult.click(function(e) {;

										// On change le contenu du champ de recherche et on enregistre en tant que précédente valeur
										previousValue = $(this).html();
										$searchField.value = previousValue;

										// On cache les résultats
										$resultsList.css('display', 'none');

										// On supprime l'effet de focus
										$(this).removeClass('result');

										// On remet la sélection à zéro
										selectedIndex = -1;

										// Si le résultat a été choisi par le biais d'un clic, alors le focus est perdu, donc on le réattribue
										$searchField.focus();
									};
								}
							}
						}

					}, 'json');

					// On remet la sélection à zéro à chaque caractère écrit
					selectedIndex = -1;
				}

				//return false;

			});


			/*
			$('.ajax-list').change(function(event) {


				var select = $(this);
				var target = '#' + select.data('target');
				var url = select.data('url');
				var sortOf = select.data('sort');
				
				var refOrgan = null;
				var refUser = null;

				if (sortOf === "user") {

					$("#ref_session_cbox").parents('.filter-item').hide();

					refOrgan = $("#ref_organ_cbox").val();
				}
				else if (sortOf === "session") {

					//$('#ref_session_cbox').show();

					$('.organ-option').each(function() {

						var option = $(this)[0];
						
						if ($(option).prop('selected')) {

							refOrgan = $(option).val();
						}
					});

					refUser = $('#ref_user_cbox').val();


					var cbox = $('#ref_session_cbox').get(0);

					if (cbox.options.length > 1) {

						cbox.options.length = 1;
						
					}
				}


				$.post(url, {"ref_organ":refOrgan,"ref_user":refUser,"sort":sortOf}, function(data) {
					
					if (data.error) {

						alert(data.error);
					}
					else {

						$(target).parents('.filter-item').show();
						var $target = $(target).get(0);
						$target.options.length = 1;
						
						if (data.results.utilisateur) {
							
							var i = 1;
							for (var prop in data.results.utilisateur) {
							
								var result = data.results.utilisateur[prop];

								$target.options[i] = new Option(result.nom_user + " " + result.prenom_user, result.id_user, false, false);

								i++;
							}
						}
						else if (data.results.session) {

							var i = 1;
							for (var prop in data.results.session) {
							
								var result = data.results.session[prop];

								$target.options[i] = new Option(result.date + " " + result.time, result.id, false, false);

								i++;
							}
						}
						
					}

				}, 'json');
				

			}).each(function() {

				var select = $(this);
				if (select.val() == "select_cbox")
				{
					var target = $('#' + select.data('target'));
					target.parents('.filter-item').hide();
				}
				
			});
			*/

			<?php endif; ?>


		});

	</script>