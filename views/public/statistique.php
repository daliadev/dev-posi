<?php

// Function permettant d'attribuer aux barres un fond de couleur selon le pourcentage 
function getColor($percent)
{
	$percent = intval($percent);
	
	$color = "gris";

	if ($percent < 40)
	{
		$color = "rouge";
	}
	else if ($percent >= 40 && $percent < 60)
	{
		$color = "orange2";
	}
	else if ($percent >= 60 && $percent < 80)
	{
		$color = "jaune";
	}
	else if ($percent >= 80)
	{
		$color = "vert";
	}

	return $color;
}


// Initialisation par défaut des valeurs du formulaire

$formData = array();
$formData['ref-organ-cbox'] = "";
$formData['ref_organ'] = "";
$formData['date_debut'] = "";
$formData['date_fin'] = "";


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


$form_url = $response['url'];

//var_dump($response);            

?>



	<div id="content-large">

		<?php //if (ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom") : ?>
		<!-- <a href="<?php //echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a> -->

		<!-- <div style="clear:both;"></div> -->
		<?php //endif; ?>
		
		<!-- Header -->
		<div id="titre-admin-h2">Statistiques du positionnement
		
		<?php if (ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom") : ?>
			<!-- <div class="retour-btn"><a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a></div> -->
		<?php endif; ?>

		</div>

		<div id="main-form">

			<form id="form-posi" action="<?php echo $form_url; ?>" method="post">
  
				<div class="zone-formu2">

					<div id="bloc-stat-filtre" class="form-full">

						<p style="margin-top:0;"><strong>Filtres : </strong></p>

						<hr>
						
						<?php $visible = Config::ALLOW_LOCALE ? '' : 'style="display: none;"' ?>
						<div class="filter-item" <?php echo $visible; ?>>
							<label for="ref-region-cbox">Région : </label>

							<?php $disabled = (isset($response['regions']) && !empty($response['regions']) && count($response['regions']) == 0) ? "disabled" : ""; ?>
							<select name="ref_region_cbox" id="ref-region-cbox" class="region-list" style="width:120px;" <?php echo $disabled; ?>>
							
								<?php if ($disabled == "") : ?>
									<option class="region-option" value="select_cbox">Toute la France</option>
								<?php endif; ?>

								<?php
								
								if (isset($response['regions']) && !empty($response['regions']) && count($response['regions']) > 0)
								{

									foreach ($response['regions'] as $region)
									{

										$selected = "";
										
										if (!empty($formData['ref_region']) && $formData['ref_region'] == $region['ref'])
										{
											$selected = "selected";
										}
										
										echo '<option class="region-option" value="'.$region['ref'].'" '.$selected.'>'.$region['nom'].'</option>';
									}
								}
								
								?>
							</select>
						</div>

						<div class="filter-item">
							<label for="ref-organ-cbox">Organisme : </label>

							<?php $disabled = (isset($response['organisme']) && !empty($response['organisme']) && count($response['organisme']) == 0) ? "disabled" : ""; ?>
							<select name="ref_organ_cbox" id="ref-organ-cbox" style="width:120px;" <?php echo $disabled; ?>>
							
								<?php if ($disabled == "") : ?>
									<option class="organ-option" value="select_cbox">Tous</option>
								<?php endif; ?>

								<?php
								
								if (isset($response['organisme']) && !empty($response['organisme']) && count($response['organisme']) > 0)
								{                       
									foreach ($response['organisme'] as $organisme)
									{
										$selected = "";
										if (!empty($formData['ref_organ']) && $formData['ref_organ'] == $organisme->getId())
										{
											$selected = "selected";
										}
										echo '<option class="organ-option" value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
									}
								}
								
								?>
							</select>
						</div>

						<div class="filter-item">
							<label for="date_debut">Date de début : </label>
							<input type="text" name="date_debut" id="date_debut" class="search-date" placeholder="jj/mm/aaaa" style="width:100px;" title="Veuillez entrer la date de début" value="<?php echo $formData['date_debut']; ?>">
						</div>

						<div class="filter-item">
							<label for="date_fin">Date de fin : </label>
							<input type="text" name="date_fin" id="date_fin" class="search-date" placeholder="jj/mm/aaaa" style="width:100px;" title="Veuillez entrer la date de fin" value="<?php echo $formData['date_fin']; ?>">
						</div>

						<div class="filter-item">
							<input type="submit" name="select-form" value="Sélectionner" style="width:130px; margin: 18px 0 0 0;">
						</div>

					</div>
				</div>

				<div class="zone-formu2">

					<div id="bloc-stat-global" class="form-full">

						<fieldset>
							
							<legend>

							<?php 
								if(!isset($response['stats']['global']['organismes']) || empty($response['stats']['global']['organismes']) || count($response['stats']['global']['organismes']) > 1)
								{
									echo 'Résultats'; 
								}
								else
								{
									echo 'Résultats pour '.$response['stats']['global']['organismes'][0]['nom_organ'];  
								}     
							?>

							</legend>

							<div class="stats-detail">

								<div class="bloc-stat">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 0h24v24H0z" fill="none"/>
												<path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
									</svg>
									
									<div class="bloc-stat-number"><strong><?php echo $response['stats']['global']['nbre_sessions']; ?></strong></div>
									<div class="bloc-stat-title">Nombre de positionnements</div>
								</div>
								
								<div class="bloc-stat">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												 <!--<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>-->
												 <path d="M11.99 2C6.47 2 2 6.47 2 12s4.47 10 9.99 10S22 17.53 22 12 17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm1-10.06L14.06 11l1.06-1.06L16.18 11l1.06-1.06-2.12-2.12zm-4.12 0L9.94 11 11 9.94 8.88 7.82 6.76 9.94 7.82 11zM12 17.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/>
												<path d="M0 0h24v24H0z" fill="none"/>
									</svg>
									<div class="bloc-stat-number"><strong><?php echo $response['stats']['global']['nbre_users']; ?></strong></div>
									<div class="bloc-stat-title">Nombre d'utilisateurs positionnés</div>
								</div>
								
								<div class="bloc-stat">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												  <path d="M0 0h24v24H0z" fill="none"/>
												  <!--<path d="M15.9 5c-.17 0-.32.09-.41.23l-.07.15-5.18 11.65c-.16.29-.26.61-.26.96 0 1.11.9 2.01 2.01 2.01.96 0 1.77-.68 1.96-1.59l.01-.03L16.4 5.5c0-.28-.22-.5-.5-.5zM1 9l2 2c2.88-2.88 6.79-4.08 10.53-3.62l1.19-2.68C9.89 3.84 4.74 5.27 1 9zm20 2l2-2c-1.64-1.64-3.55-2.82-5.59-3.57l-.53 2.82c1.5.62 2.9 1.53 4.12 2.75zm-4 4l2-2c-.8-.8-1.7-1.42-2.66-1.89l-.55 2.92c.42.27.83.59 1.21.97zM5 13l2 2c1.13-1.13 2.56-1.79 4.03-2l1.28-2.88c-2.63-.08-5.3.87-7.31 2.88z"/>-->
												  <path d="M8 19h3v4h2v-4h3l-4-4-4 4zm8-14h-3V1h-2v4H8l4 4 4-4zM4 11v2h16v-2H4z"/>
									</svg>
									<div class="bloc-stat-number"><strong class="<?php echo getColor($response['stats']['global']['moyenne_score_session']); ?>"><?php echo $response['stats']['global']['moyenne_score_session']; ?><small>%</small></strong></div>
									<div class="bloc-stat-title">Score moyen global</div>
								</div>

								<div class="bloc-stat">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 0h24v24H0z" fill="none"/>
												<path d="M12,20C8.13,20 5,16.87 5,13C5,9.13 8.13,6 12,6C15.87,6 19,9.13 19,13C19,16.87 15.87,20 12,20M19.03,7.39L20.45,5.97C20,5.46 19.55,5 19.04,4.56L17.62,6C16.07,4.74 14.12,4 12,4C7.03,4 3,8.03 3,13C3,17.97 7.03,22 12,22C17,22 21,17.97 21,13C21,10.88 20.26,8.93 19.03,7.39M11,14H13V8H11M15,1H9V3H15V1Z" />
									</svg>
									<div class="bloc-stat-number"><strong style="font-size:13px;"><?php echo $response['stats']['global']['moyenne_temps_session']; ?></strong></div>
									<div class="bloc-stat-title">Temps de passation moyen</div>
								</div>

								<div class="bloc-stat">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 0h24v24H0z" fill="none"/>
												<path d="M20,2V4H18V8.41L14.41,12L18,15.59V20H20V22H4V20H6V15.59L9.59,12L6,8.41V4H4V2H20M16,16.41L13,13.41V10.59L16,7.59V4H8V7.59L11,10.59V13.41L8,16.41V17H10L12,15L14,17H16V16.41M12,9L10,7H14L12,9Z" />
									</svg>
									<div class="bloc-stat-number"><strong style="font-size:12px;"><?php echo $response['stats']['global']['temps_total']; ?></strong></div>
									<div class="bloc-stat-title">Temps total</div>
								</div>

								<div class="bloc-stat last">
									<svg fill="#949494" height="40" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 0h24v24H0z" fill="none"/>
												<path d="M12 6c1.11 0 2-.9 2-2 0-.38-.1-.73-.29-1.03L12 0l-1.71 2.97c-.19.3-.29.65-.29 1.03 0 1.1.9 2 2 2zm4.6 9.99l-1.07-1.07-1.08 1.07c-1.3 1.3-3.58 1.31-4.89 0l-1.07-1.07-1.09 1.07C6.75 16.64 5.88 17 4.96 17c-.73 0-1.4-.23-1.96-.61V21c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-4.61c-.56.38-1.23.61-1.96.61-.92 0-1.79-.36-2.44-1.01zM18 9h-5V7h-2v2H6c-1.66 0-3 1.34-3 3v1.54c0 1.08.88 1.96 1.96 1.96.52 0 1.02-.2 1.38-.57l2.14-2.13 2.13 2.13c.74.74 2.03.74 2.77 0l2.14-2.13 2.13 2.13c.37.37.86.57 1.38.57 1.08 0 1.96-.88 1.96-1.96V12C21 10.34 19.66 9 18 9z"/>
									</svg>
									<div class="bloc-stat-number"><strong><?php echo $response['stats']['global']['age_moyen']; ?> ans</strong></div>
									<div class="bloc-stat-title">Age moyen des utilisateurs</div>
								</div>
								
								<input type="submit" value="Export Posi/Organ"  title="Export nombre de positionnement par organisme" name="export_total_organisme" style="float:right; margin-right:3px; width:150px;">
								
								<div style="clear:both;"></div>

							</div>
							
							<div class="stats-detail">

								<!--<p><strong>Nombre de candidats répartis par niveau de formation</strong></p>

								<hr>

								<div class="progressbars" style="width:580px;">

									<?php/* for ($i = 0; $i < count($response['stats']['global']['niveaux']); $i++) : ?>

										<div class="progressbar">
											<div class="progressbar-title" title="<?php echo $response['stats']['global']['niveaux'][$i]['descript_niveau']; ?>">
												<?php echo $response['stats']['global']['niveaux'][$i]['nom_niveau']; ?> / <strong><?php echo $response['stats']['global']['niveaux'][$i]['nbre_users']; ?></strong> utilisateurs sur <?php echo $response['stats']['global']['nbre_users']; ?>
												<div class="progressbar-bg">
													<span style="width:<?php echo $response['stats']['global']['niveaux'][$i]['pourcent']; ?>%; background-color: #f39c12;"></span>
												</div>
											</div>
										</div>

									<?php endfor;*/ ?>
										
								</div>
								
								<input type="submit" value="Export niveau"  title="Export nombre de candidats répartis par niveau" name="export_niveau_nombre" style="float:right; margin: 0 3px 0 0; width:150px;">
								   
								<div style="clear:both;"></div>

							</div>	-->
							
							<div class="stats-detail">

								<p><strong>Score moyen par compétences</strong></p>

								<hr>

								<div class="progressbars" style="width:580px;">

									<?php for ($i = 0; $i < count($response['stats']['global']['categories']); $i++) : ?>

										<div class="progressbar">
											<div class="progressbar-title" title="<?php echo $response['stats']['global']['categories'][$i]['description']; ?>">
												<?php echo $response['stats']['global']['categories'][$i]['nom']; ?> / <strong><?php echo $response['stats']['global']['categories'][$i]['pourcent']; ?></strong>%
												<div class="progressbar-bg">
													<span class="bg-<?php echo getColor($response['stats']['global']['categories'][$i]['pourcent']); ?>" style="width:<?php echo $response['stats']['global']['categories'][$i]['pourcent']; ?>%;"></span>
												</div>
											</div>
										</div>

									<?php endfor; ?>
										
								</div>
								
								<input type="submit" value="Export score moyen"  title="Export score moyen par compétences" name="export_score_competences" style="float:right; margin:0 3px 0 0; width:150px;">

								<div style="clear:both;"></div>

							</div>

							<!-- 
							<div class="stats-detail">

								<p><strong>Répartition (des positionnements) par degré</strong></p>

								<hr>

								<div class="progressbars" style="width:580px;">
	
									<?php 
									/*
									 // Calcul du pourcentage de l'item
									$percent = array();
									$nonvalid = 100;
									$nonvalidNum = $response['stats']['global']['nbre_sessions'];
									$nonvalidIndex = 0;


									for ($i = 0; $i < count($response['stats']['global']['acquis']); $i++) :

										$percent[$i] = round($response['stats']['global']['acquis'][$i]['count'] / $response['stats']['global']['nbre_sessions'] * 100);
										$nonvalid -= $percent[$i];
										$nonvalidNum -= $response['stats']['global']['acquis'][$i]['count'];
										$nonvalidIndex = $i + 1;
										
										switch($i) {
											case 0 :
												$colors[$i] = "primary";
												break;
											case 1 :
												$colors[$i] = "secondary";
												break;
											case 2 :
												$colors[$i] = "warning";
												break;
											case 3 :
												$colors[$i] = "danger";
												break;
											case 4 :
												$colors[$i] = "success";
												break;
											case 5 :
												$colors[$i] = "info";
												break;
											default :
												$colors[$i] = "default";
												break;
										}
										*/
										?>


										<div class="progressbar">
											<div class="progressbar-title" title="<?php //echo $response['stats']['global']['acquis'][$i]['desc']; ?>">

												<?php //echo $response['stats']['global']['acquis'][$i]['name']; ?> / <strong><?php //echo $percent[$i]; ?>%</strong> (<?php //echo $response['stats']['global']['acquis'][$i]['count']; ?> sur <?php //echo $response['stats']['global']['nbre_sessions']; ?> positionnements)
												
												<div class="progressbar-bg">
													<span class="<?php //echo $colors[$i]; ?>" style="width:<?php //echo $percent[$i]; ?>%;"></span>
												</div>

											</div>
										</div>

									<?php //endfor; ?>


									<?php //if ($response['stats']['global']['non_valid_count'] > 0) : ?>
										
										<div class="progressbar">

												Non validé(s) / <strong><?php //echo $nonvalid; ?>%</strong> (<?php //echo $nonvalidNum; ?> sur <?php //echo $response['stats']['global']['nbre_sessions']; ?> positionnements)
												
												<div class="progressbar-bg">
													<span class="default" style="width:<?php //echo $nonvalid; ?>%;"></span>
												</div>

											</div>
										</div>

									<?php //endif; ?>
										
									</div>
								
								<input type="submit" value="Export répartition par degrés"  title="Export des répartition des degrés." name="export_acquis" style="float:right; margin:0 3px 0 0; width:200px;">

								<div style="clear:both;"></div>

							</div> -->

						</fieldset>
					</div>
				</div>

			</form>

		</div>
		
		<div style="clear:both;"></div>


		<?php
			// Inclusion du footer
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>
	

	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-ui-1.10.3.custom.all.js" type="text/javascript"></script>


	<script type="text/javascript">
	   
		$(function() { 
			
			var date = new Date();
			var year = date.getFullYear();

			$(".search-date").focus(function(event) {
				$(this).val('');
			});
			
			$("#date_debut").datepicker({
				dateFormat: "dd/mm/yy",
				changeMonth: true, 
				changeYear: true, 
				yearRange: "2014:"+year,
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
				firstDay: 1,
				showMonthAfterYear: false,
				onSelect: function(dateText, inst)
				{
					var the_date = dateText.split('/'); 
					var dateUs = new Date(the_date[2], the_date[1]-1, the_date[0]);
					$("#date_fin").datepicker('option', 'minDate', dateUs);
				}

						
			});
			
			
			
			$("#date_fin").datepicker({
				dateFormat: "dd/mm/yy",
				changeMonth: true, 
				changeYear: true, 
				yearRange: "2014:"+year,
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
				firstDay: 1,
				showMonthAfterYear: false,
				onSelect: function(dateText, inst)
				{
					var the_date = dateText.split('/'); 
					var dateUs = new Date(the_date[2], the_date[1]-1, the_date[0]);
					$("#date_debut").datepicker('option', 'maxDate', dateUs);
				}

			});


			<?php if (Config::ALLOW_AJAX) : ?>


				/* Listes dynamiques en ajax */
			   
				$('.region-list').on('change', function(event) {

					/*
					var select = $(this);
					var target = '#' + select.data('target');
					var url = select.data('url');
					var sortOf = select.data('sort');
					*/
					var select = $(this);
					var target = '#ref-organ-cbox';
					var url = $('#form-posi').attr('action');
					//console.log(url);
					var refRegion = null;
					//var refUser = null;
					/*
					if (sortOf === "user") {

						$("#ref_session_cbox").parents('.filter-item').hide();

						refOrgan = $("#ref_organ_cbox").val();
					}
					else if (sortOf === "session") {
					*/
						//$('#ref_session_cbox').show();
					<?php if (Config::ALLOW_LOCALE) : ?>

						$('.region-option').each(function() {

							var option = $(this)[0];
							
							if ($(option).prop('selected')) {

								refRegion = $(option).val();
							}
						});
						
					<?php endif; ?>

						/*
						refUser = $('#ref_user_cbox').val();


						var cbox = $('#ref_session_cbox').get(0);

						if (cbox.options.length > 1) {
	
							cbox.options.length = 1;
							
						}
						*/
					//}


					$.post(url, {'ajax_request': 'organ', 'ref_region': refRegion, 'ref_organ': null}, function(data) {
						
						var $target = $(target).get(0);
						$target.options.length = 1;
						$target.options[0].selected;

						if (data.error) {

							//alert(data.error);
						}
						else {

							//$(target).parents('.filter-item').show();
							
							//console.log($target.options.length);
							
							
							if (data.results.organisme) {
								
								var i = 1;
								for (var prop in data.results.organisme) {
								
									var result = data.results.organisme[prop];

									var selected = false;

									if (data.results.organisme.length <= 1) {
										selected = true
									}
									$target.options[i] = new Option(result.nom_organ, result.id_organ, false, selected);

									i++;
								}
							}
							/*
							else if (data.results.session) {

								var i = 1;
								for (var prop in data.results.session) {
								
									var result = data.results.session[prop];

									$target.options[i] = new Option(result.date + " " + result.time, result.id, false, false);

									i++;
								}
							}
							*/
						}

					}, 'json');
					

				});
				/*	
				.each(function() {

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