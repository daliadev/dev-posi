<?php

require_once(ROOT.'utils/array_sort.php');

// Function permettant d'attribuer aux barres un fond de couleur selon le pourcentage 
function getColor($percent)
{
	$percent = intval($percent);
	
	$color = "gris";

	if ($percent < 60)
	{
		$color = "rouge";
	}
	else if ($percent >= 60 && $percent < 75)
	{
		$color = "orange2";
	}
	else if ($percent >= 75 && $percent < 90)
	{
		$color = "jaune";
	}
	else if ($percent >= 90)
	{
		$color = "vert";
	}

	return $color;
}

function getProgressBar($percent)
{
	$progressbar = '';

	if ($percent < 60)
	{
		$progressbar .= '<div class="progress-bar progress-bar-danger" style="width: '.$percent.'%;"></div>';
	}
	else if ($percent >= 60 && $percent < 75)
	{
		$percent -= 60;
		$progressbar .= '<div class="progress-bar progress-bar-danger" style="width: 60%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-secondary" style="width: '.$percent.'%;"></div>';
	}
	else if ($percent >= 75 && $percent < 90)
	{
		$percent -= 75;
		$progressbar .= '<div class="progress-bar progress-bar-danger" style="width: 60%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-secondary" style="width: 15%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-warning" style="width: '.$percent.'%;"></div>';
	}
	else if ($percent >= 90)
	{
		$percent -= 90;
		$progressbar .= '<div class="progress-bar progress-bar-danger" style="width: 60%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-secondary" style="width: 15%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-warning" style="width: 15%;"></div>';
		$progressbar .= '<div class="progress-bar progress-bar-success" style="width: '.$percent.'%;"></div>';
	}

	return $progressbar;
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
		if ($parent == $cat->getParent()) 
		{
			if ($previous_level < $level) 
			{
				$list .= '<ul>';
			}

			if ($level == 0)
			{
				$list .= '<li>'; //<h3><a>'.$cat->getNom().'</a></h3>';

				$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'"><h3><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></h3></div>';
				$list .= '<div class="progress">';
				$list .= getProgressBar($cat->getScorePercent());
				//$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'"><h3><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></h3></div>';
				//$list .= '<a>'.$cat->getNom().'</a> / <strong>'.$cat->getScorePercent().'</strong>%';
				//$list .= '<div class="progressbar-bg">';


				//$list .= '<span class="bg-'.getColor($cat->getScorePercent()).'" style="width:'.$cat->getScorePercent().'%;"></span>';
				//$list .= '</div>';
				$list .= '</div>';

				$isMainListOpen = true;
			}
			else
			{
				if ($isListOpen) 
				{
					$list .= '</li>';
				}
				$list .= '<li>';
				$list .= '<div class="progress-title" title="'.$cat->getDescription().'"><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></div>';
				$list .= '<div class="progress">';
				//$list .= '<div class="progressbar">';
				//$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'"><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></div>';
				//$list .= '<a>'.$cat->getNom().'</a> / <strong>'.$cat->getScorePercent().'</strong>%';
				//$list .= '<div class="progressbar-bg">';
				$list .= getProgressBar($cat->getScorePercent());
				//$list .= '<span class="bg-'.getColor($cat->getScorePercent()).'" style="width:'.$cat->getScorePercent().'%;"></span>';
				//$list .= '</div>';
				$list .= '</div>';
				//$list .= '</div>';
				/*
				echo '<p class="form-text-large">';
					echo $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].')';
					echo '<div class="progress">';
							echo '<div class="progress-bar progress-bar-'.$color.'" style="width: '.$percent.'%;"></div>';
						//echo '<span class="bg-'.$color.'" style="width:'.$percent.'%;"></span>';
					echo '</div>';
				echo '</p>';
				*/

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




// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['ref_organ_cbox'] = "";
$formData['ref_organ'] = "";
$formData['ref_user_cbox'] = "";
$formData['ref_user'] = "";
$formData['ref_session_cbox'] = "";
$formData['ref_session'] = "";


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


$form_url = $response['url'];


?>



	<div class="content-form-large">
		
		<div class="form-header">
			<h2>Restitution des résultats</h2>
			<?php if (ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom") : ?>
				<a href="<?php echo SERVER_URL; ?>admin/menu" class="form-header-back">
				<i class="fa fa-bars"></i>
			</a>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		
		
		<form class="form-admin-large" id="form-admin" name="form_restitution" action="<?php echo $form_url; ?>" method="post">

			<fieldset>

				<div class="fieldset-header" id="titre-organ">
					<i class="fa fa-cube"></i> <h2 class="fieldset-title">Filtre de sélection</h2>
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
					else if (isset($response['success']) && !empty($response['success']))
					{
						echo '<div class="alert alert-success">';
							echo '<ul>';
							foreach($response['success'] as $message)
							{
								echo '<li>'.$message.'</li>';
							}
							echo '</ul>';
						echo '</div>';
					}
				?>

				<div class="fieldset-header" id="titre-organ">
					<i class="fa fa-cube"></i> <h2 class="fieldset-title">Résultats de la sélection</h2>
				</div>
				
				<ul class="tabs">
					<li class="active">
						<a href="#infos">Informations utilisateur</a>
					</li>
					<li>
						<a href="#global">Synthèse des résultats</a>
					</li>
					<li>
						<a href="#details">Détails des résultats</a>
					</li>
					<li>
						<a href="#parcours">Parcours de formation</a>
					</li>
					<li>
						<a href="#exports">Exports</a>
					</li>
					<!-- <div class="clear"></div> -->
				</ul>
				

			</fieldset>
			
		</form>
	<!-- </div> -->










		<div id="main-form">

			<form id="form-posi" action="<?php echo $form_url; ?>" method="post" name="formu_admin_com_act">

				<div class="zone-formu2">

					<div class="form-full">
						
						<fieldset>

							<legend>Selection du positionnement</legend>

							<div class="filter-item" id="combo-organ">
								<label for="ref_organ_cbox">Organisme :</label>

								<?php //$disabled = (count($response['organisme']) <= 1) ? "disabled" : ""; ?>
								<?php $disabled = ""; ?>
								<select name="ref_organ_cbox" id="ref_organ_cbox" class="ajax-list" data-target="ref_user_cbox" data-url="<?php echo $form_url; ?>" data-sort="user" <?php echo $disabled; ?>>
									
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

							&nbsp;
							
							<div class="filter-item" id="combo-user">
								<label for="ref_user_cbox">Utilisateur :</label>
								<select name="ref_user_cbox" id="ref_user_cbox" class="ajax-list" data-target="ref_session_cbox" data-url="<?php echo $form_url; ?>" data-sort="session">
									<option value="select_cbox">---</option>

									<?php
									
									foreach ($response['utilisateurs'] as $utilisateur)
									{
										$selected = "";
										if (!empty($formData['ref_user']) && $formData['ref_user'] == $utilisateur->getId())
										{
											$selected = "selected";
										}
										echo '<option value="'.$utilisateur->getId().'" '.$selected.'>'.$utilisateur->getNom().' '.$utilisateur->getPrenom().'</option>';
									}
									
									?>

								</select>
							</div>
							

							&nbsp;
							
						
							<div class="filter-item" id="combo-posi">
								<label for="ref_session_cbox">Positionnement :</label>
								<select name="ref_session_cbox" id="ref_session_cbox" class="ajax-list">
									<option value="select_cbox">---</option>

									<?php
									
									foreach ($response['sessions'] as $session)
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
									
									?>

								</select>
							</div>
							

							&nbsp;

							<div class="filter-item">
								<input type="submit" value="Sélectionner" id="submit-posi" style="margin: 18px 0 0 0;" >
							</div>

						</fieldset>
					</div>
				</div>



				<div class="zone-formu2">

					<div id="infos-posi" class="form-full">

						<fieldset>

							<legend>Informations du positionnement</legend>

							<!-- <ul>
								<li><a href="#infos">1 - Informations utilisateur</a></li>
								<li><a href="#stats">2 - Les résultats</a></li>
								<li><a href="#details">3 - Détails des résultats</a></li>
								<li><a href="#exports">4 - Exports</a></li>
							</ul> -->

							<div id="infos" class="zone-liste-restitution">

								<div class="tab-block">

								<?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user'] ?>
									
									<div class="info">Nom de l'organisme : <strong><?php echo $infos_user['nom_organ']; ?></strong></div>

									<?php if (ServicesAuth::getAuthenticationRight() == "admin" || ServicesAuth::getAuthenticationRight() == "custom") : ?>
									<div class="info">Code de l'organisme : <?php echo $infos_user['code_organ']; ?> (<a href="<?php echo $form_url.$infos_user['code_organ']; ?>" target="_blank"><?php echo $form_url.$infos_user['code_organ']; ?></a>)</div>
									<?php endif; ?>

									<!--<div class="info">Nom de l'intervenant - responsable : <strong><?php //echo $infos_user['nom_intervenant']; ?></strong></div> -->
									<div class="info">Email de l'intervenant : <strong><a href="mailto:<?php echo $infos_user['email_intervenant']; ?>" target="_top"><?php echo $infos_user['email_intervenant']; ?></a></strong></div>
									<hr>
									<div class="info">Nom : <strong><?php echo strtoupper($infos_user['nom']); ?></strong></div>
									<div class="info">Prénom : <strong><?php echo $infos_user['prenom']; ?></strong></div>
									<div class="info">Date de naissance : <strong><?php echo $infos_user['date_naiss']; ?></strong></div>
									<div class="info">Niveau d'études : <strong><span title="<?php echo $infos_user['descript_niveau']; ?>"><?php echo $infos_user['nom_niveau']; ?></span></strong></div>
									<br/>
									<div class="info">Nombre de positionnements terminés : <strong><?php echo $infos_user['nbre_positionnements']; ?></strong></div>
									<div class="info">Date du dernier positionnement : <strong><?php echo $infos_user['date_last_posi']; ?></strong></div>
									
									<?php if (!empty($response['infos_user']['ref_selected_session'])) : ?>
										
									<hr>
									<div class="info">
										<label for="ref_valid_cbox" style="line-height:40px;"><strong>Interprétation des acquis :</strong> </label>
										 &nbsp; 
										<select name="ref_valid_cbox" id="ref_valid_cbox" style="width:200px;">
											<option value="select_cbox">Non validé</option>

											<?php
											
											foreach ($response['valid_acquis'] as $valid_acquis)
											{
												$selected = "";
												if (!empty($infos_user['ref_valid_acquis']) && $infos_user['ref_valid_acquis'] == $valid_acquis->getId())
												{
													$selected = "selected";
												}
												
												echo '<option value="'.$valid_acquis->getId().'" '.$selected.'>'.$valid_acquis->getNom().'</option>';
											}
											
											?>

										</select>
										 &nbsp;

										<div id="buttons" style="display: inline;">
											<input type="button" value="Modifier" id="modif-acquis" name="modif_acquis" class="add" style="width:100px; margin: 0 0 0 0;" />
											<input type="submit" value="Enregistrer" id="submit-acquis" name="submit_acquis" class="save" style="width:100px; margin: 0 0 0 0;" />
											<input type="button" value="Annuler" id="clear-acquis" name="clear_acquis" class="del" style="width:100px; margin: 0 0 0 0;" />
										</div>

									</div>

									<?php endif; ?>
						
								<?php else : ?>
									<div class="info">Aucun utilisateur n'a été sélectionné.</div>
								<?php endif; ?>
								</div>

							</div>


							<div id="stats" class="zone-liste-restitution">
								
								<div id="statistiques" class="tab-block">

									<?php if (!empty($response['stats'])) : $stats = $response['stats'];
										$dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10));
										$timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s");
										$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m"));
										$tempsTotal = Tools::timeToString($response['session'][0]->getTempsTotal());
									?>
										<div class="info">Positionnement du : <strong><?php echo $dateSession; ?> à <?php echo $time; ?></strong></div>
										<div class="info">Temps total : <strong><?php echo $tempsTotal; ?></strong></div>
										<?php if (!empty($stats['percent_global'])) : ?>
											<div class="info">Taux de réussite global : <strong><?php echo $stats['percent_global']; ?>%</strong> (<strong><?php echo $stats['total_correct_global']; ?></strong> réponses correctes sur <strong><?php echo $stats['total_global']; ?></strong> questions)</div>
										<?php endif; ?>
											
										<br/>
										<!-- 
										<div class="stats gradiant_pic">
											<ul>

											<?php //foreach ($stats['categories'] as $statCategorie) : ?>

												<?php //if ($statCategorie['total'] > 0 && $statCategorie['parent']) : ?>

												<li>

													<p><?php //echo $statCategorie['nom_categorie']; ?> :
														<strong><?php //echo $statCategorie['percent']; ?>%</strong> (<strong><?php //echo $statCategorie['total_correct']; ?></strong> réponses correctes sur <strong><?php echo $statCategorie['total']; ?></strong> questions)
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
													//$sortedCat = ArraySort::recursiveArray(0, 0, $response['stats']['categories'], 'code_cat', 2);
													$catList = recursiveCategories(0, 0, $response['stats']['categories']);
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
									
									<?php if (!empty($response['details']['questions'])) : ?>

										<table id="table-resultats" class="tablesorter">
											<thead>
												<tr>
													<th style="width:15%;">Question</th>
													<th style="width:30%;">Catégorie/<br/>compétence</th>
													<th style="width:8%;">Degré</th>
													<th style="width:30%;">Réponse utilisateur</th>
													<th style="width:9%;">Réponse<br/>correcte</th>
													<th style="width:8%;">Réussite</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$i = 0;
												foreach($response['details']['questions'] as $detail)
												{
													if ($i % 2 == 0)
													{
														echo '<tr style="background-color:#FCE7CA;" >';
													}
													else
													{
														echo '<tr style="background-color:#FFF6EA;">';
													}
													
														echo '<td style="width:15%;">';
															echo '<a rel="lightbox" href="'.SERVER_URL.'uploads/img/'.$detail['image'].'" title="'.$detail['intitule'].'" >';
																echo 'Question n°'.$detail['num_ordre'];
															echo '</a>';
														echo '</td>';

														echo '<td style="width:30%; font-size:12px; text-align:left;">';
															if (isset($detail['categories'][0]['nom_cat_parent']) && !empty($detail['categories'][0]['nom_cat_parent']))
															{
																echo '<strong>'.$detail['categories'][0]['nom_cat_parent']." : </strong><br/>";
															}
															echo '<a title="'.$detail['categories'][0]['descript_cat'].'">'.$detail['categories'][0]['nom_cat'].'</a>';
														echo '</td>';

														echo '<td style="width:8%;">';
															echo '<a title="'.$detail['descript_degre'].'">'.$detail['nom_degre'].'</a>';
														echo '</td>';

														if (!empty($detail['reponse_user_qcm']) && $detail['reponse_user_qcm'] != "-")
														{
															echo '<td style="width:30%;"><a title="'.$detail['intitule_reponse_user'].'">'.$detail['reponse_user_qcm'].'</a></td>';
														}
														else if (!empty($detail['reponse_user_champ']))
														{
															if ($detail['reponse_user_champ'] == "-")
															{
																echo '<td style="width:30%; text-align: center; line-height: 1.3em">'.$detail['reponse_user_champ'].'</td>';
															}
															else 
															{
																echo '<td style="width:30%; text-align: left; line-height: 1.3em">'.$detail['reponse_user_champ'].'</td>';
															}
														}
														else
														{
															echo '<td style="width:30%;"></td>';
														}

														echo '<td style="width:9%;"><a title="'.$detail['intitule_reponse_correcte'].'">'.$detail['reponse_qcm_correcte'].'</a></td>';

														if ($detail['reussite'] === 1)
														{
															echo '<td style="width:8%;"><span style="display:none;">2</span><img src="'.SERVER_URL.'media/images/valide.png"></td>';
														}
														else if ($detail['reussite'] === 0)
														{
															echo '<td class="red-cell" style="width:8%;"><span style="display:none;">1</span><img src="'.SERVER_URL.'media/images/faux.png"></td>';
														}
														else
														{
															echo '<td class="white-cell" style="width:8%;"><span style="display:none;">0</span><img src="'.SERVER_URL.'media/images/stylo.png"></td>';
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


							<div id="exports" class="zone-liste-restitution">

								<div class="export-files" class="tab-block">

									<?php if (!empty($response['details']['questions'])) : ?>

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

	</div>
