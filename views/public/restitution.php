<?php

//require_once(ROOT.'utils/array_sort.php');
require_once(ROOT.'utils/tools.php');

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_organ_cbox'] = "";
$formData['ref_organ'] = "";
$formData['ref_user_cbox'] = "";
$formData['ref_user'] = "";
$formData['ref_posi_cbox'] = "";
$formData['ref_posi'] = "";
$formData['ref_session_cbox'] = "";
$formData['ref_session'] = "";

//$formData['select_trigger'] = "false";


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



if (!empty($response['stats'])) 
{
	//$stats = $response['stats'];

	$dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10));
	$timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s");
	$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));
	$tempsTotal = Tools::timeToString($response['session'][0]->getTempsTotal());

}

$form_url = $response['url'];



// Function permettant d'attribuer aux barres un fond de couleur selon le pourcentage de réussite
// 
function getProgressColor($percent)
{
	$percent = intval($percent);
	
	$color = "progress-bar-default";

	if ($percent < 50)
	{
		$color = "progress-bar-danger";
	}
	else if ($percent >= 50 && $percent < 75)
	{
		$color = "progress-bar-secondary";
	}
	else if ($percent >= 75 && $percent < 90)
	{
		$color = "progress-bar-warning";
	}
	else if ($percent >= 90)
	{
		$color = "progress-bar-success";
	}

	return $color;
}



function recursiveCategories($parent, $level, $datas)
{
	$list = '';
	$previous_level = 0;
	$isMainListOpen = false;
	$isListOpen = false;

	if ($level == 0) 
	{
		$list .= '<ul>';
	}

	foreach ($datas as $cat) 
	{
		$percent = round($cat->getScorePercent());

		if ($parent == $cat->getParentCode()) 
		{
			if ($previous_level < $level) 
			{
				$list .= '<ul>';
			}

			if ($level == 0)
			{
				if (!$cat->getHasResult())
				{
					$list .= '<li class="disabled" style="display:none;">';
				}
				else
				{
					$list .= '<li>';
				}

				$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'">';
				$list .= '<h3><a>'.$cat->getNom().' / <strong>'.$percent.'</strong>%</a></h3>';
				$list .= '<span style="display:none;">Réponses '.$cat->getTotalReponsesCorrectes().'/'.$cat->getTotalReponses().'</span><div class="clear"></div>';
				$list .= '</div>';
				$list .= '<div class="progress">';
				$list .= '<div class="progress-bar '.getProgressColor($percent).'" style="width: '.$percent.'%;"></div>';
				$list .= '</div>';

				$isMainListOpen = true;
			}
			else
			{
				if ($isListOpen) 
				{
					$list .= '</li>';
				}
				
				if (!$cat->getHasResult())
				{
					$list .= '<li class="disabled" style="display:none;">';
				}
				else
				{
					$list .= '<li>';
				}

				$list .= '<div class="progress-title" title="'.$cat->getDescription().'">';
				$list .= '<a>'.$cat->getNom().' / <strong>'.$percent.'</strong>%</a>';
				$list .= '<span style="display:none;">Réponses '.$cat->getTotalReponsesCorrectes().'/'.$cat->getTotalReponses().'</span><div class="clear"></div>';
				$list .= '</div>';
				$list .= '<div class="progress">';
				$list .= '<div class="progress-bar '.getProgressColor($percent).'" style="width: '.$percent.'%;"></div>';
				$list .= '</div>';
				
				$isListOpen = true;
			}

			$previous_level = $level;

			$list .= recursiveCategories($cat->getCode(), ($level + 1), $datas);
		}
	}

	if ($previous_level == $level && $previous_level != 0) 
	{
		if ($isMainListOpen || $isListOpen)
		{
			$list .= '</li>';
		}
		$list .= '</ul>';
	}

	return $list;
}


if (isset($response['stats']['categories']) && !empty($response['stats']['categories']))
{
	$catList = recursiveCategories(0, 0, $response['stats']['categories']);
}


?>



	<div id="content-large">
		
		
		<!-- Header -->
		<div id="titre-admin-h2">Restitution des résultats - <?php echo Config::POSI_NAME; ?>

		<?php //if (ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom-admin" || ServicesAuth::getAuthenticationRight() == "custom-public") : ?>
			<!-- <div class="retour-btn"><a href="<?php //echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a></div> -->
		<?php //endif; ?>

		</div>



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

		?>


		<div id="main-form">

			<form id="form-posi" action="<?php echo $form_url; ?>" method="post" name="formu_admin_com_act">
				
				<input type="hidden" id="select-trigger" name="select_trigger" value="<?php echo $formData['select_trigger']; ?>" />

				<div class="zone-formu2">

					<div class="form-full">
						
						<fieldset id="posi-search">

							<legend>Recherche du positionnement</legend>
							
							<p style="margin-top: 0;"><strong>Filtres de recherche : </strong></p>
							
							<?php $visible = Config::ALLOW_LOCALE ? '' : 'style="display: none;"' ?>
							<div class="filter-item" <?php echo $visible; ?>>
								<label for="ref-region-cbox">Région : </label>

								<?php $disabled = (isset($response['regions']) && !empty($response['regions']) && count($response['regions']) <= 1) ? "disabled" : ""; ?>
								<select name="ref_region_cbox" id="ref-region-cbox" class="ajax-list" data-target="ref_user_cbox" data-url="<?php echo $form_url; ?>" data-sort="user" data-request="region-organ" style="max-width: 170px;" <?php echo $disabled; ?>>
								
									<?php if ($disabled == "") : ?>
										<option class="region-option" value="select_cbox">Toute la France</option>
									<?php endif; ?>
									<?php
									
									if (isset($response['regions']) && !empty($response['regions']) && count($response['regions']) > 0)
									{
										foreach ($response['regions'] as $region)
										{
											$selected = "";
											
											if (!empty($_POST['ref_region_cbox']) && $_POST['ref_region_cbox'] == $region['ref'])
											{
												$selected = "selected";
											}
											
											echo '<option class="region-option" value="'.$region['ref'].'" '.$selected.'>'.$region['nom'].'</option>';
										}
									}
									
									?>
								</select>
							</div>
							

							<div class="filter-item" id="combo-organ">
								<label for="ref-organ-cbox">Organisme :</label>

								<?php $disabled = (isset($response['organisme']) && !empty($response['organisme']) && count($response['organisme']) <= 1) ? "disabled" : ""; ?>
								<?php $disabled = "" ?>
								<select name="ref_organ_cbox" id="ref-organ-cbox" class="ajax-list" style="max-width: 170px;" <?php echo $disabled; ?>>
									
									<?php if ($disabled == "") : ?>
									<option class="organ-option" value="select_cbox">---</option>
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
							


							<div class="filter-item" id="combo-user">
								<label for="ref-user-cbox">Utilisateur :</label>
								<select name="ref_user_cbox" id="ref-user-cbox" class="ajax-list" style="max-width: 170px;">
									<option value="select_cbox">---</option>

									<?php
									
									if (isset($response['utilisateur']) && !empty($response['utilisateur']) && count($response['utilisateur']) > 0)
									{
										foreach ($response['utilisateur'] as $utilisateur)
										{
											$selected = "";
											if (!empty($formData['ref_user']) && $formData['ref_user'] == $utilisateur->getId())
											{
												$selected = "selected";
											}
											echo '<option value="'.$utilisateur->getId().'" '.$selected.'>'.$utilisateur->getNom().' '.$utilisateur->getPrenom().'</option>';
										}
									}
									
									?>

								</select>
							</div>

							
							<?php $visible = (isset($response['positionnement']) && !empty($response['positionnement'])) ? '' : 'style=display: none;'; ?>

							<div class="filter-item" <?php echo $visible; ?>>
								<label for="ref-posi-cbox">Domaine : </label>

								<?php $disabled = ""; //(isset($response['positionnement']) && !empty($response['positionnement']) && count($response['positionnement']) <= 1) ? "disabled" : ""; ?>
								<select name="ref_posi_cbox" id="ref-posi-cbox" class="ajax-list" style="max-width: 170px;" <?php echo $disabled; ?>>
								
									<?php if (empty($disabled)) : ?>
										<option class="posi-option" value="select_cbox">---</option>
									<?php endif; ?>

									<?php
									if (isset($response['positionnement']) && !empty($response['positionnement']) && count($response['positionnement']) > 0)
									{
										
										foreach ($response['positionnement'] as $domaine)
										{
											$disabled = "";
											$selected = "";
											
											if (!empty($formData['ref_posi']) && $formData['ref_posi'] == $domaine->getId())
											{
												$selected = "selected";
											}
											else if ($domaine->getId() == null) 
											{
												$disabled = "disabled";
											}
											echo '<option value="'.$domaine->getId().'" '.$selected.' '.$disabled.'>'.$domaine->getNom().'</option>';
										}
									}
									?>

								</select>
							</div>

							
							<div class="filter-item" style="margin-right: 0;">
								<input type="submit" value="Filtrer" id="submit-filter" style="margin: 19px 0 0 0; width: 70px; height: 32px;">
							</div>
							
							<div style="clear: both;"></div>

							<hr />

							<!-- <p style="margin-top: 0;"><strong>Sélection du positionnement : </strong></p> -->

							<div class="filter-item" id="combo-posi" style="margin-top: 0;">
								<label for="ref-session-cbox"><strong>Positionnement(s) trouvé(s) :</strong></label>
								<select name="ref_session_cbox" id="ref-session-cbox" class="ajax-list" style="margin: 10px 0 0 0; width: 160px;">
									<option value="select_cbox">---</option>
									

									<?php
									
									if (isset($response['session']) && !empty($response['session']) && count($response['session']) > 0) 
									{
										foreach ($response['session'] as $session)
										{
											$selected = "";
											if (!empty($formData['ref_session']) && $formData['ref_session'] == $session->getId())
											{
												$selected = "selected";
											}

											$date = Tools::toggleDate(substr($session->getDate(), 0, 10));
											$timeToSeconds = Tools::timeToSeconds(substr($session->getDate(), 11, 8), $inputFormat = "h:m:s");
											$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));
											echo '<option value="'.$session->getId().'" '.$selected.'>'.$date.' '.$time.'</option>';
										}
									}
									
									
									?>

								</select>
								
								
							</div>


							<div class="filter-item">
								<input type="submit" value="Sélectionner" name="validate_search" id="select-posi" style="margin: 24px 0 0 0; width: 100px; height: 32px;">
							</div>

							<div style="float:right;">
							
								<a href="<?php echo $form_url; ?>">
									
												<svg version="1.1" width="50" height="50" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
													 viewBox="0 0 496.156 496.156" style="enable-background:new 0 0 496.156 496.156;" xml:space="preserve">
													<path style="fill:#32BEA6;" d="M0,248.08C0,111.06,111.069,0.002,248.074,0.002c137.013,0,248.082,111.059,248.082,248.078
													c0,137.005-111.069,248.074-248.082,248.074C111.069,496.154,0,385.085,0,248.08z"/>
													<g>
													<path style="fill:#FFFFFF;" d="M428.243,172.149c-10.348,0.023-20.694,0.054-31.042,0.077
														c-23.272-52.177-68.95-89.871-126.649-98.91c-64.287-10.071-125.816,19.788-162.504,72.312
														c-16.296,23.326,22.003,45.165,38.16,22.034c50.317-72.037,157.757-65.527,201.907,4.686c-9.559,0.022-19.118,0.046-28.678,0.068
														c-2.674,0.008-4.574,1.197-5.749,2.877c-1.92,2.058-2.65,5.072-0.639,8.186c18.204,28.25,36.408,56.499,54.616,84.745
														c3.061,4.747,9.663,4.708,12.696-0.046c18.062-28.338,36.126-56.675,54.188-85.013C437.538,178.48,433.602,172.135,428.243,172.149
														z"/>
													<path style="fill:#FFFFFF;" d="M350.353,327.224c-49.927,71.49-156.108,65.63-200.886-3.049c9.161-0.022,18.322-0.046,27.484-0.068
														c2.666-0.008,4.563-1.19,5.741-2.865c1.924-2.059,2.658-5.072,0.646-8.197c-18.2-28.246-36.405-56.499-54.609-84.741
														c-3.056-4.751-9.662-4.712-12.695,0.046c-18.063,28.334-36.13,56.671-54.196,85.009c-2.987,4.689,0.948,11.032,6.308,11.017
														c10.669-0.027,21.337-0.054,32.006-0.08c23.498,51.319,68.777,88.332,125.865,97.275c64.287,10.072,125.816-19.784,162.496-72.312
														C404.806,325.928,366.508,304.09,350.353,327.224z"/>
													</g>

											</svg>	
								</a>
							
							</div>
							
						<div style="clear: both;"></div>
						
						</fieldset>
					</div>
				</div>



				<div class="zone-formu2">

					<div id="infos-posi" class="form-full">

						<fieldset>

							<legend>Informations du positionnement</legend>

							<ul>
								<li><a class="tab-link" href="#infos">1 - Informations utilisateur</a></li>
								<li><a class="tab-link" href="#stats">2 - Les résultats</a></li>
								<li><a class="tab-link" href="#details">3 - Détails des résultats</a></li>
								<?php if (Config::ALLOW_PRECONISATION) : ?>
									<li><a class="tab-link" href="#parcours">4 - Parcours de formation</a></li>
									<li><a class="tab-link" href="#exports">5 - Exports</a></li>
								<?php endif; ?>
								<li><a class="tab-link" href="#exports">4 - Exports</a></li>
							</ul>

							<div id="infos" class="zone-liste-restitution">

								<div class="tab-block">

								<?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user'] ?>
									
									<div class="info">Nom de l'organisme : <strong><?php echo $infos_user['nom_organ']; ?></strong></div>

									<?php/* if ((ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom") && isset($infos_user['code_organ'])) : ?>
									<div class="info">Code de l'organisme : <?php echo $infos_user['code_organ']; ?> (<a href="<?php echo $form_url.$infos_user['code_organ']; ?>" target="_blank"><?php echo $form_url.$infos_user['code_organ']; ?></a>)</div>
									<?php endif; */ ?>

									<!--<div class="info">Nom de l'intervenant - responsable : <strong><?php //echo $infos_user['nom_intervenant']; ?></strong></div> -->
									<div class="info">Email de l'intervenant : <strong><a href="mailto:<?php echo $infos_user['email_intervenant']; ?>" target="_top"><?php echo $infos_user['email_intervenant']; ?></a></strong></div>
									<hr>
									<div class="info">Nom : <strong><?php echo $infos_user['nom']; ?></strong></div>
									<div class="info">Prénom : <strong><?php echo $infos_user['prenom']; ?></strong></div>
									<!--
									<div class="info">Date de naissance : <strong><?php// echo $infos_user['date_naiss']; ?></strong></div>
									<div class="info">Niveau d'études : <strong><span title="<?php //echo $infos_user['descript_niveau']; ?>"><?php //echo $infos_user['nom_niveau']; ?></span></strong></div>
									<br/>
									<div class="info">Nombre de positionnements terminés : <strong><?php //echo $infos_user['nbre_positionnements']; ?></strong></div>
									<div class="info">Date du dernier positionnement : <strong><?php //echo $infos_user['date_last_posi']; ?></strong></div>
									-->
									
									<?php //if (!empty($response['infos_user']['ref_selected_session'])) : ?>
										
									<!-- <hr>
									<div class="info">
										<label for="ref_valid_cbox" style="line-height:40px;"><strong>Interprétation des acquis :</strong> </label>
										 &nbsp; 
										<select name="ref_valid_cbox" id="ref_valid_cbox" style="width:200px;">
											<option value="select_cbox">Non validé</option>

											<?php
											/*
											foreach ($response['valid_acquis'] as $valid_acquis)
											{
												$selected = "";
												if (!empty($infos_user['ref_valid_acquis']) && $infos_user['ref_valid_acquis'] == $valid_acquis->getId())
												{
													$selected = "selected";
												}
												
												echo '<option value="'.$valid_acquis->getId().'" '.$selected.'>'.$valid_acquis->getNom().'</option>';
											}
											*/
											?>

										</select>
										 &nbsp;

										<div id="buttons" style="display: inline;">
											<input type="button" value="Modifier" id="modif-acquis" name="modif_acquis" class="add" style="width:100px; margin: 0 0 0 0;" />
											<input type="submit" value="Enregistrer" id="submit-acquis" name="submit_acquis" class="save" style="width:100px; margin: 0 0 0 0;" />
											<input type="button" value="Annuler" id="clear-acquis" name="clear_acquis" class="del" style="width:100px; margin: 0 0 0 0;" />
										</div>

									</div> -->

									<?php //endif; ?>
						
								<?php else : ?>
									<div class="info">Aucun utilisateur n'a été sélectionné.</div>
								<?php endif; ?>
								</div>

							</div>


							<div id="stats" class="zone-liste-restitution">
								
								<div id="statistiques" class="tab-block">

									<?php if (!empty($response['stats'])) : $stats = $response['stats'];
										//$dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10));
										//$timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s");
										//$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));
										//$tempsTotal = Tools::timeToString($response['session'][0]->getTempsTotal());
									?>
										<div class="info">Positionnement du : <strong><?php echo $dateSession; ?> à <?php echo $time; ?></strong></div>
										<div class="info">Temps total : <strong><?php echo $tempsTotal; ?></strong></div>
										<?php if (!empty($stats['percent_global'])) : ?>
											<div class="info">Taux de réussite global : <strong><?php echo $stats['percent_global']; ?>%</strong><span style="display: none;"> (<strong><?php echo $stats['total_correct_global']; ?></strong> réponses correctes sur <strong><?php echo $stats['total_global']; ?></strong> questions)</span></div>
										<?php endif; ?>
											
										<br/>
										<!-- 
										<div class="stats gradiant_pic">
											<ul>

											<?php //foreach ($stats['categories'] as $statCategorie) : ?>

												<?php //if ($statCategorie['total'] > 0 && $statCategorie['parent']) : ?>

												<li>

													<p><?php //echo $statCategorie['nom_categorie']; ?> :
														<strong><?php //echo $statCategorie['percent']; ?>%</strong> (<strong><?php //echo $statCategorie['total_correct']; ?></strong> réponses correctes sur <strong><?php //echo $statCategorie['total']; ?></strong> questions)
														<?php //$width = $statCategorie['percent']; ?>
														<span class="percent" style="width:<?php //echo $width; ?>%" title="<?php //echo $statCategorie['descript_categorie']; ?>"></span>
													</p>

												</li>

												<?php //endif; ?>
											<?php //endforeach; ?>

											</ul>

										</div> -->
										
										<!-- <div class="info"> -->
											<div class="categories-list">
											
											<!-- <div class="progressbars" style="width:100%;"> -->
												
												<?php
													//$catList = recursiveCategories(0, 0, $response['stats']['categories']);
													echo $catList;
												?>
												
												<!-- 
												<div class="progressbar">
													<div class="progressbar-title" title="<?php //echo $cat->getDescription(); ?>">
														<?php //echo $cat->getNom(); ?> / <strong><?php //echo $cat->getScorePercent(); ?></strong>%
														<div class="progressbar-bg">
															<span class="bg-<?php //echo getColor($cat->getScorePercent()); ?>" style="width:<?php //echo $cat->getScorePercent(); ?>%;"></span>
														</div>
												
													</div>

												</div>-->
											<!-- </div> -->

											</div>
										<!-- </div> -->


									<?php else : ?>

										<div class="info">Aucun positionnement n'est sélectionné.</div>

									<?php endif; ?>

								</div>

							</div>

							<div id="details" class="zone-liste-restitution">

								<div id="resultats" class="tab-block">
									
									<input type="hidden" id="num_session" name="ref_session" value="<?php echo $formData['ref_session']; ?>">
									
									<?php if (!empty($response['details']['questions'])) : ?>
										
										<table id="table-resultats" class="tablesorter">
											<thead>
												<tr>
													<!-- <th style="width:0%;"></th> -->
													<th style="width:15%;">Question</th>
													<th style="width:35%;">Catégorie/<br/>compétence</th>
													<!-- <th style="width:8%;">Degré</th> -->
													<th style="width:20%;">Réponse utilisateur</th>
													<th style="width:10%;">Réponse<br/>correcte</th>
													<th style="width:20%;" colspan="2">Validation</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$i = 0;
												foreach($response['details']['questions'] as $detail)
												{	
													
													if ($i % 2 == 0)
													{
														echo '<tr style="background-color:#a0c4ff;">';
													}
													else
													{
														echo '<tr style="background-color:#d3e4ff;">';
													}

													echo '<td class="num_question" style="width:0%; display:none;">';
														echo '<input type="hidden" name="ref_question" value="'.$detail['ref_question'].'">';
													echo '</td>';

													echo '<td style="width:15%;">';
														echo '<a rel="lightbox" href="'.SERVER_URL.'uploads/img/'.$detail['image'].'" title="'.$detail['intitule'].'" >';
															echo 'Question n°'.$detail['num_ordre'];
														echo '</a>';
													echo '</td>';

													echo '<td style="width:35%; font-size:11px; text-align:left;">';
														/*
														if (isset($detail['categories'][0]['nom_cat_parent']) && !empty($detail['categories'][0]['nom_cat_parent']))
														{
															echo '<strong>'.$detail['categories'][0]['nom_cat_parent']." : </strong><br/>";
														}
														*/
														echo '<a title="'.$detail['categories'][0]['descript_cat'].'">'.$detail['categories'][0]['nom_cat'].'</a>';
													echo '</td>';

													// echo '<td style="width:8%;">';
													// 	echo '<a title="'.$detail['descript_degre'].'">'.$detail['nom_degre'].'</a>';
													// echo '</td>';

													if (!empty($detail['reponse_user_qcm']) && $detail['reponse_user_qcm'] != "-")
													{
														echo '<td style="width:20%;"><a title="'.$detail['intitule_reponse_user'].'">'.$detail['reponse_user_qcm'].'</a></td>';
													}
													else if (!empty($detail['reponse_user_champ']))
													{
														if ($detail['reponse_user_champ'] == "-")
														{
															echo '<td style="width:20%; text-align: center; line-height: 1.3em">'.$detail['reponse_user_champ'].'</td>';
														}
														else 
														{
															echo '<td style="width:20%; text-align: left; line-height: 1.3em" class="rep-ouverte" title="'.$detail['reponse_user_champ'].'">'.Tools::getExtrait($detail['reponse_user_champ'], 100).'</td>';
														}
													}
													else
													{
														echo '<td style="width:20%;"></td>';
													}

													echo '<td style="width:10%;"><a title="'.$detail['intitule_reponse_correcte'].'">'.$detail['reponse_qcm_correcte'].'</a></td>';


													if ($detail['reussite'] === 1)
													{
														echo '<td style="width:20%;" class="cell-right" colspan="2"><span style="display:none;">2</span><img src="'.SERVER_URL.'media/images/valide.png"></td>';
													}
													else if ($detail['reussite'] === 0)
													{
														echo '<td style="width:20%;" class="cell-wrong" colspan="2"><span style="display:none;">1</span><img src="'.SERVER_URL.'media/images/faux.png"></td>';
													}
													else
													{

														if ($detail['validation'] == "null") {
															$checkedRight = '';
															$checkedWrong = '';
															$classRignt = '';
															$classWrong = '';
														}
														else if ($detail['validation'] == 1) {
															$checkedRight = 'checked';
															$checkedWrong = '';
															$classRignt = 'class="cell-right"';
															$classWrong = 'class="cell-right"';
														}
														else if ($detail['validation'] == 0) {
															$checkedRight = '';
															$checkedWrong = 'checked';
															$classRignt = 'class="cell-wrong"';
															$classWrong = 'class="cell-wrong"';
														}
														
														echo '<td style="width:10%;" '.$classRignt.'>';
															echo '<p>Vrai</p>';
															echo '<input type="radio" class="valid" name="valid_reponse-'.$detail['ref_question'].'" value="1" '.$checkedRight.'>';
														echo '</td>';

														echo '<td style="width:10%;" '.$classWrong.'>';
															echo '<p>Faux</p>';
															echo '<input type="radio" class="valid" name="valid_reponse-'.$detail['ref_question'].'" value="0" '.$checkedWrong.'>';
														echo '</td>';
														/*
														echo '<td class="white-cell" style="width:20%;"><span style="display:none;">0</span>';
															echo 'vrai- Faux';
															echo '<input type="radio" name="" value="" placeholder="">';
															echo '<input type="checkbox" name="validations[]"></td>';
														echo '</td>';

														echo '<td class="white-cell" style="width:20%;"><span style="display:none;">0</span>';
															echo 'vrai- Faux';
															echo '<input type="radio" name="" value="" placeholder="">';
															echo '<input type="checkbox" name="validations[]"></td>';
														echo '</td>';
														*/
													}

													echo '</tr>';
													
												   $i++;
												} 
												?>
											</tbody>

										</table>

									<?php else : ?>
										<div class="info">Aucun détail à afficher.</div>
									<?php endif; ?>
								</div>

							</div>
							

							<?php if (Config::ALLOW_PRECONISATION) : ?>
							<div id="parcours" class="zone-liste-restitution">

								<div id="parcours-formation" class="tab-block">
									
									<?php if (!empty($response['stats'])) : $stats = $response['stats']; ?>

										<div class="info">Positionnement du : <strong><?php echo $dateSession; ?> à <?php echo $time; ?></strong></div>
										<div class="info">Temps total : <strong><?php echo $tempsTotal; ?></strong></div>
										<?php if (!empty($stats['percent_global'])) : ?>
											<div class="info">Taux de réussite global : <strong><?php echo $stats['percent_global']; ?>%</strong> (<strong><?php echo $stats['total_correct_global']; ?></strong> réponses correctes sur <strong><?php echo $stats['total_global']; ?></strong> questions)</div>
										<?php endif; ?>
											
										<br/>

										<div class="precos-block">

											<?php $numCat = count($response['stats']['categories']); ?>
											
											<?php //for ($i=0; $i < $numCat; $i++) : ?> 
											<?php foreach ($response['stats']['categories'] as $categorie) : ?> 

												<?php if (strlen($categorie->getCode()) == 2) : ?>

												<div class="bandeau-preco">
										
													<div class="cat-block">

														<div class="cat-name">
															<span><?php echo Tools::getExtrait($categorie->getNom(), 68); ?></span>
														</div>
														<div class="cat-score"><?php echo $categorie->getScorePercent(); ?>%</div>
														<div style="clear: both;"></div>

													</div>

													<div class="volume"><?php echo $categorie->getVolumePreconisations(); ?> heures</div>

													<!-- <div style="clear: both;"></div> -->

												</div>

												<?php endif; ?>

											<?php endforeach; ?>



											<div style="clear: both;"></div>

										</div>

										<!-- <div style="clear: both;"></div> -->

									<?php else : ?>

										<div class="info">Aucun parcours à afficher.</div>

									<?php endif; ?>

								</div>

							</div>

							<?php endif; ?>



							<div id="exports" class="zone-liste-restitution">

								<div id="export-files" class="tab-block">

									<?php if (!empty($response['details']['questions'])) : ?>

										<!-- <input type="submit" value="Générer un PDF" name="export_pdf" class="bt-admin-menu-ajout2" disabled title="indisponible" style="background-color: #bdbdbd;" /> -->
										<input type="submit" value="Générer un PDF" name="export_pdf" class="bt-admin-menu-ajout2" />
										<input type="submit" value="Générer un Excel" name="export_xls" class="bt-admin-menu-ajout2" />
  
									<?php else : ?>
										<div class="info">Aucun export n'est disponible.</div>
									<?php endif; ?>

								</div>

							</div>

						</fieldset>

					</div>
				</div>

			</form>

		</div>
		
		
		<!-- <div class="clear"></div> -->


		<?php
			// Inclusion du footer
			require_once(ROOT.'views/templates/footer_old.php');
		?>

	</div>
	



	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-ui-1.10.3.custom.all.js" type="text/javascript"></script>

	<script src="<?php echo SERVER_URL; ?>media/js/lightbox-2.6.min.js" type="text/javascript"></script>
	<script src="<?php echo SERVER_URL; ?>media/js/jquery.tablesorter.js" type="text/javascript"></script>


	<script type="text/javascript">
	   
		$(function() { 
			
			$("#infos-posi").tabs();

			$("#infos-posi").tooltip();

			$("#table-resultats").tablesorter();

			$('.categories-list ul:first-child').children('li:first-child').addClass('active');


			var self = this;

			//var isSelectTriggered = false;
			
			var refRegion = null;
			var refOrgan = null;
			var refUser = null;
			var refPosi = null;
			//var dateSession = null;
			var refSession = null;

			var selectRegion = $('#posi-search #ref-region-cbox').get(0);
			var selectOrgan = $('#posi-search #ref-organ-cbox').get(0);
			var selectUser = $('#posi-search #ref-user-cbox').get(0);
			var selectPosi = $('#posi-search #ref-posi-cbox').get(0);
			var dateInput = $('#posi-search #date-session').val();
			var selectSession = $('#posi-search #ref-session-cbox').get(0);

			var $filterButton = $('#posi-search #submit-filter');
			var $selectButton = $('#posi-search #select-posi');
			var isFilterable = false;
			var filterRequested = false;
			
			$filterButton.prop('disabled', true);
			$selectButton.prop('disabled', true);
			


			/* Listes dynamiques en ajax */

			this.changeFilter = function(id, value) {

				//console.log('Filter changed for : ' + id + ' = ' + value);
				//console.log('ref_region = ' + refRegion + ' - ref_organ = ' + refOrgan + ' - ref_user = ' + refUser + ' - date_session = ' + dateSession);

				var onlyOrgan = false;

				if (id == selectRegion.id) {

					if (value == null)
					{
						onlyOrgan = true;
					}

					if (selectRegion.value == 'select_cbox') {

						refRegion = null;
					}
					refOrgan = null;
					refUser = null;
					refPosi = null;

					isFilterable = false;
					$filterButton.prop('disabled', true);
				}
				else if (id == selectOrgan.id) {

					if (selectOrgan.value == 'select_cbox') {

						refOrgan = null;
					}
					refUser = null;
					refPosi = null;

					isFilterable = false;
					$filterButton.prop('disabled', true);
				}
				else if (id == selectUser.id) {

					if (selectUser.value == 'select_cbox') {

						refUser = null;
					}

					if (selectPosi) {

						refPosi = null;

						isFilterable = false;
						$filterButton.prop('disabled', true);
					}
					else
					{
						isFilterable = true;
						$filterButton.prop('disabled', false);
					}
				}
				else if (id == selectPosi.id) {

					isFilterable = true;
					$filterButton.prop('disabled', false);
				}

				
				var url = $('#form-posi').attr('action');

				//$.post(url, {'filter': true, 'ref_region': refRegion, 'ref_organ': refOrgan, 'ref_user': refUser, 'date_session': dateSession}, function(data) {
				$.post(url, {'filter': true, 'ref_region': refRegion, 'ref_organ': refOrgan, 'ref_user': refUser, 'ref_posi': refPosi}, function(data) {

					if (data.error) {

						alert(data.error);
					}
					else {

						//var dateInput = $('#date-session');

						var currentRefOrgan = null;
						var currentRefUser = null;
						var currentRefPosi = null;
						var currentRefSession = null;

						var selected = false;


						if (filterRequested) {
							
							if (data.results != null)
							{
								selectSession.options.length = 1;
								selectSession.options[0].selected;

								currentRefSession = null;

								var session = null;
								var i = 1;

								for (var prop in data.results) {

									if (data.results[prop].id_session != null && data.results[prop].date_session != null && data.results[prop].id_user != currentRefSession) {

										session = data.results[prop];
										currentRefSession = session.id_session;

										selectSession.options[i] = new Option(session.date_session, session.id_session, false, false);

										i++;
									}
								}

								$selectButton.prop('disabled', false);
							}
							else
							{

							}

							filterRequested = false;

						}
						else {

							selectSession.options.length = 1;
							selectSession.options[0].selected;

							$selectButton.prop('disabled', true);

							if (id == selectRegion.id) {

								//console.log('Region selected');
								selectOrgan.options.length = 1;
								selectOrgan.options[0].selected;
								selectUser.options.length = 1;
								selectUser.options[0].selected;
								// selectPosi.options.length = 1;
								// selectPosi.options[0].selected;

 								currentRefOrgan = null;
								currentRefUser = null;
								//currentRefPosi = null;

								if (selectPosi) {

									selectPosi.options.length = 1;
									selectPosi.options[0].selected;
									currentRefPosi = null;
								}
 								/*
								if (id == selectRegion.id) {

									selectOrgan.options.length = 1;
									selectOrgan.options[0].selected;
									selectUser.options.length = 1;
									selectUser.options[0].selected;

									var currentRefOrgan = null;
									var currentRefUser = null;
								*/
								var i = 1;
								//var j = 1;

								for (var prop in data.results) {

									// Changement des organismes
									if (data.results[prop].id_organ != null && data.results[prop].nom_organ != null && data.results[prop].id_organ != currentRefOrgan) {

										var organ = data.results[prop];
										currentRefOrgan = organ.id_organ;

										selected = false;
										//if (organ.id_organ = refOrgan) { selected = true; }

										selectOrgan.options[i] = new Option(organ.nom_organ, organ.id_organ, false, selected);

										i++;
									}
									/*
									else if (!onlyOrgan && data.results[prop].id_user != null && data.results[prop].nom_user != null && data.results[prop].prenom_user != null && data.results[prop].id_user != currentRefUser) {

										var user = data.results[prop];
										currentRefUser = user.id_user;

										selectUser.options[j] = new Option(user.nom_user + " " + user.prenom_user, user.id_user, false, false);

										j++;
									}
									*/
								}
							}
							else if (id == selectOrgan.id) {

								//console.log('Organ selected');
								selectUser.options.length = 1;
								selectUser.options[0].selected;
								currentRefUser = null;

								if (selectPosi) {
									selectPosi.options.length = 1;
									selectPosi.options[0].selected;
									currentRefPosi = null;
								}
								
								var i = 1;
								//var j = 1;

								for (var prop in data.results) {
									
									// Changement des utilisateurs
									if (data.results[prop].id_user != null && data.results[prop].nom_user != null && data.results[prop].prenom_user != null && data.results[prop].id_user != currentRefUser) {

										var user = data.results[prop];
										currentRefUser = user.id_user;

										selectUser.options[i] = new Option(user.nom_user + " " + user.prenom_user, user.id_user, false, false);

										i++;
									}
									/*
									else if (selectPosi && data.results[prop].id_posi != null && data.results[prop].nom_posi != null && data.results[prop].id_posi != currentRefPosi) {

										var posi = data.results[prop];
										currentRefPosi = posi.id_posi;

										selectUser.options[j] = new Option(user.nom_posi, user.id_posi, false, false);

										j++;
									}
									*/
								}
							}

							else if (id == selectUser.id) {

								//console.log('User selected');
								if (selectPosi) {

									selectPosi.options.length = 1;
									selectPosi.options[0].selected;
									currentRefPosi = null;

									var i = 1;

									for (var prop in data.results) {

										if (data.results[prop].id_posi != null && data.results[prop].nom_posi != null && data.results[prop].id_posi != currentRefPosi) {

											var posi = data.results[prop];
											currentRefPosi = posi.id_posi;

											selectPosi.options[i] = new Option(posi.nom_posi, posi.id_posi, false, false);

											i++;
										}
									}
								}
								
							}
							else {
								//console.log('everything\'s set');
								/*
								selectOrgan.options.length = 1;
								selectOrgan.options[0].selected;
								selectUser.options.length = 1;
								selectUser.options[0].selected;
								*/
								//currentRefOrgan = null;
								//currentRefUser = null;
							}
						}


						/*
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
						*/
					}
				}, 'json');
				
			};



			$('#posi-search .ajax-list').on('change', function(event) {

				var $select = $(this);
				var id = $select.get(0).id;
				var ref = null;
				var hasChanged = false;
				
				switch (id) {
					case 'ref-region-cbox' :
						hasChanged = $select.val() != refRegion;
						refRegion = $select.val() != 'select_cbox' ? $select.val() : null;
						ref = refRegion;
						break;

					case 'ref-organ-cbox' :
						hasChanged = $select.val() != refOrgan;
						refOrgan = $select.val() != 'select_cbox' ? $select.val() : null;
						ref = refOrgan;
						break;

					case 'ref-user-cbox' :
						hasChanged = $select.val() != refUser;
						refUser = $select.val() != 'select_cbox' ? $select.val() : null;
						ref = refUser;
						break;

					// case 'date-session' :
					// 	hasChanged = $select.val() != refUser;
					// 	refUser = $select.val() != 'select_cbox' ? $select.val() : null;
					// 	ref = refUser;
					// 	break;

					case 'ref-posi-cbox' :
						hasChanged = $select.val() != refPosi;
						refPosi = $select.val() != 'select_cbox' ? $select.val() : null;
						ref = refPosi;
						break;

					default :
						break;
				}

				if (hasChanged) {
					self.filterRequested = false;
					self.changeFilter(id, ref);
				}
			});

			/*
			var date = new Date();

			$("#posi-search #date-session").datepicker({
				dateFormat: "dd/mm/yy",
				changeMonth: true, 
				changeYear: true, 
				yearRange: "2014:" + date.getFullYear(),
				closeText: 'Fermer',
				prevText: 'Précédent',
				nextText: 'Suivant',
				currentText: 'Aujourd\'hui',
				monthNames: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
				monthNamesShort: ['janv', 'fév', 'mars', 'avr', 'mai', 'juin', 'juil', 'août', 'sept', 'oct', 'nov', 'déc'],
				dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
				dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
				dayNamesMin: ['D','L','M','M','J','V','S'],
				weekHeader: 'Sem.',
				firstDay: 1,
				showMonthAfterYear: false,
				onClose: function(dateText, instance) {
					var pickedDate = dateText.split('/'); 
					//var dateUs = new Date(pickedDate[2], pickedDate[1] - 1, pickedDate[0]);
					//this.
					//$("#date-debut").datepicker('option', 'maxDate', dateUs);

					//dateSession = new Date(pickedDate[2], pickedDate[1] - 1, pickedDate[0]);
					dateSession = pickedDate[2] + '-' + pickedDate[1] + '-' + pickedDate[0];

					self.changeFilter(this.id, dateSession);
				}
			});
			*/

			$filterButton.on('click', function(event) {
				
				event.preventDefault();
				
				if (isFilterable) {
					filterRequested = true;
					self.changeFilter('ref-session-cbox', null);
				}
			});

			$selectButton.on('click', function(event) {
				
				event.preventDefault();
				
				if ($('ref-session-cbox').val() != 'select_cbox')
				{
					$('#form-posi').submit();
				}
			});


			if ($('#select-trigger').val() == "true")
			{
				$('#select-trigger').val(null);
				this.changeFilter('ref-region-cbox', null);
			}
			


			/*=======================================================================
			=            Gestion de la validation des questions ouvertes            =
			========================================================================*/

			var resultHasChanged = false;


			$('.valid').on('click', function(event) {
				
				var $validation = $(event.currentTarget);
				var refSession = $('#num_session').val();
				var refQuestion = $validation.parent().siblings('.num_question').children('input').val();
				var isValid = $validation.val();
				var $selectedCell = $validation.parent();

				if (isValid === '1') {
					$selectedCell.prop('class', 'cell-right');
					$selectedCell.next().prop('class', 'cell-right');
				}
				else if (isValid === '0') {
					$selectedCell.prop('class', 'cell-wrong');
					$selectedCell.prev().prop('class', 'cell-wrong');
				}

				if (!resultHasChanged) {

					resultHasChanged = true;
				}

				// Requête ajax de changement de validation dans la table 'resultat' de la question

				var url = $('#form-posi').attr('action');

				$.post(url, {'validation': isValid, 'ref_question': refQuestion, 'ref_session': refSession}, function(data) {

					if (data.error) {

						alert(data.error);
					}
					else {

						//alert(data.result);

					}
				});
			});

			// Si click sur une des étiquettes de section
			
			$('.tab-link').on('click', function(event) {

				// On vérifie s'il y a des validations qui ont été effectuées
				if (resultHasChanged) {

					//event.preventDefault();
					resultHasChanged = false;

					var message = 'Pour pouvoir visualiser les résultats prenant en compte les modifications que vous avez apporté, la page va être actualisée.'

					alert(message);
					
					$('#form-posi').submit();
				}
				
			});
			


			/*=====  End Gestion de la validation des questions ouvertes  ======*/



			
			// Liste des résultats par catégories interactives
			$('.categories-list a').on('click', function() {

				var link = $(this);
				var closest_ul = link.closest('ul');
				var parallel_active_links = closest_ul.find('.active')
				var closest_li = link.closest('li');
				var link_li_hasClass = closest_li.hasClass('active');
				var label = closest_li.closest('h3');

				var count = 0;
				
				closest_ul.find('ul').slideUp(function() {

					if (++count == closest_ul.find('ul').length) {

						parallel_active_links.removeClass('active');
					}
				});

				if(!link_li_hasClass)
				{
					closest_li.children('ul').slideDown();
					closest_li.addClass('active');
				}
			});
			



			// Validation des acquis : le select et le bouton "Enregistrer sont désactivés par défaut
			var selectedAcquis = $("#ref_valid_cbox").val();
			//console.log(selectedAcquis);
			$("#ref_valid_cbox").attr("disabled", true);
			$("#submit-acquis").attr("disabled", true);
			$("#clear-acquis").attr("disabled", true);
			$("#submit-acquis").hide();
			$("#clear-acquis").hide();

			// Gestion de la validation des acquis
			$('#modif-acquis').click(function(event) {
				$(this).hide();
				$('#submit-acquis').show();
				$('#clear-acquis').show();
				$("#submit-acquis").attr("disabled", false);
				$("#clear-acquis").attr("disabled", false);
				$("#ref_valid_cbox").attr("disabled", false);
				return false;
			});

			$('#submit-acquis').click(function(event) {
				$(this).hide();
				$("#clear-acquis").hide();
				$('#modif-acquis').show();
				$('#form-posi').submit();
				return false;
			});

			$('#clear-acquis').click(function(event) {
				$(this).hide();
				$('#submit-acquis').hide();
				$('#modif-acquis').show();
				$("#ref_valid_cbox").attr("disabled", true);
				$("#ref_valid_cbox").val(selectedAcquis);
				return false;
			});


		});

	</script>