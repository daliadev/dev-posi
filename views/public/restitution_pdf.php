<?php 

function getProgressColor($percent)
{
	$percent = intval($percent);
	
	$color = "#00bfa5";

	if ($percent < 50)
	{
		$color = "#e74c3c";
	}
	else if ($percent >= 50 && $percent < 75)
	{
		$color = "#ff9800";
	}
	else if ($percent >= 75 && $percent < 90)
	{
		$color = "#f1c40f";
	}
	else if ($percent >= 90)
	{
		$color = "#2ecc71";
	}

	return $color;
}



function recursiveCategories($parent, $level, $datas)
{
	$list = '';
	$previous_level = 0;
	//$isMainListOpen = false;
	//$isListOpen = false;
	$isTableOpen = false;

	if ($level == 0) 
	{
		//$list .= '<table>';
	}
	
	foreach ($datas as $cat) 
	{
		$percent = $cat->getScorePercent();

		if ($parent == $cat->getParentCode()) 
		{

			if ($previous_level < $level) 
			{
				$isTableOpen = true;
				//$list .= '<tr><td><table>';
			}

			if ($level == 0)
			{
 
				/*
				if (!$cat->getHasResult())
				{
					$list .= '<tr class="info disabled"><td>';
				}
				else
				{
					$list .= '<tr><td class="info">';
				}

				$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'">';
				$list .= '<h3><a>'.$cat->getNom().' / <strong>'.$percent.'</strong>%</a></h3>';
				$list .= '<span>Réponses '.$cat->getTotalReponsesCorrectes().'/'.$cat->getTotalReponses().'</span><div class="clear"></div>';
				$list .= '</div>';
				$list .= '<div class="progress">';
				$list .= '<div class="progress-bar '.getProgressColor($percent).'" style="width: '.$percent.'%;"></div>';
				$list .= '</div>';

				//$isMainListOpen = true;
				$list .= '</td></tr>';
				*/
				$width = ($percent * 152) / 100;

				if ($percent == 0) { $width = 0.5; }
				
				if (!$cat->getHasResult())
				{
					$list .= '<tr class="info disabled"><td></td></tr>';
				}
				else
				{
					//foreach ($stats['categories'] as $statCategorie) {
					//if ($statCategorie['total'] > 0 && $statCategorie['parent']) {

					$list .= '<tr>';
						$list .= '<td class="info">';
							$list .= '<div class="stats-text">';
								$list .= $cat->getNom().' :'; 
								$list .= '<strong>'.$percent.' %</strong> (<strong>'.$cat->getTotalReponsesCorrectes().'</strong> réponses correctes sur <strong>'.$cat->getTotalReponses().'</strong> questions)';
							$list .= '</div>';
						$list .= '</td>';
					$list .= '</tr>';

					$list .= '<tr>';
						$list .= '<td class="info">';
							$list .= '<div class="percent" style="width:'.$width.'mm;">';
								$position = $width;
								$width = 152 - $position + 3;
								$list .= '<img src="'.ROOT.'media/images/gradiant.png" />';
								$list .= '<div class="cache" style="width: '.$width.'mm; left: '.$position.'mm; top: -1mm; z-index: 99;"></div>';
							$list .= '</div>';
						$list .= '</td>';
					$list .= '</tr>';

						/*
							<tr>
								<td class="info">
									<div class="percent" style="width:<?php echo $width; ?>mm;">
										<?php $position = $width; ?>
										<?php $width = 152 - $position + 3; ?>
										<img src="<?php echo ROOT; ?>media/images/gradiant.png" />
										<div class="cache" style="width:<?php echo $width; ?>mm; left:<?php echo $position; ?>mm; top:-1mm; z-index:99;"></div>
									</div>
								</td>
							</tr>

							   
							<tr>
								<td class="line"></td>
							</tr>
							*/
						//}
					//}
				}
			}
			
			else
			{
				/*
				// if ($isListOpen) 
				// {
				// 	$list .= '</td></tr>';
				// }

				if (!$cat->getHasResult())
				{
					$list .= '<tr class="disabled"><td>';
				}
				else
				{
					$list .= '<tr><td>';
				}
				$list .= '<div class="progress-title" title="'.$cat->getDescription().'">';
				$list .= '<a>'.$cat->getNom().' / <strong>'.$percent.'</strong>%</a>';
				$list .= '<span>Réponses '.$cat->getTotalReponsesCorrectes().'/'.$cat->getTotalReponses().'</span><div class="clear"></div>';
				$list .= '</div>';
				$list .= '<div class="progress">';
				$list .= '<div class="progress-bar '.getProgressColor($percent).'" style="width: '.$percent.'%;"></div>';
				$list .= '</div>';

				//$isListOpen = true;
				$list .= '</td></tr>';
				*/
			}
			

			// if ($previous_level > $level && $isTableOpen) 
			// {
			// 	$list .= '</table></td></tr>';
			// }

			$previous_level = $level;

			$list .= recursiveCategories($cat->getCode(), ($level + 1), $datas);

			//$list .= '</table></td></tr>';
			/*
			if ($isTableOpen) 
			{
				$list .= '</table></td></tr>';
				$isTableOpen = false;
			}
			*/
			
		}

	}



	if ($previous_level == $level && $previous_level != 0) 
	{
		/*
		if ($isMainListOpen || $isListOpen)
		{
			$list .= '</td></tr>';
		}
		*/
		//$list .= '</table>';
	}

	return $list;
}


if (isset($response['stats']['categories']) && !empty($response['stats']['categories']))
{
	$catList = recursiveCategories(0, 0, $response['stats']['categories']);
}

?><style>
	
	h1 {
		font-size: 15pt;
		line-height: 22pt;
	}
	h2 {
		font-size: 14pt;
		line-height: 20pt;
	}
	h3 {
		font-size: 12pt;
	}
	
	table {
		width: 170mm;
	}
	
	img {
		border: none;
	}
	
	.logo {
		width: 40mm;
	}
	
	hr {
		border-color: #2C3E50;
	}
	
	
	
	table {
		font-family: Helvetica, Arial, sans-serif;
		font-size: 9pt;
	}
	
	
	.titre-h1 {
		width: 100%;
		height: 8mm;
		background-color: #48dcbf;
		color: #ffffff;
		font-size: 12pt;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
	}
	
	.titre-infos {
		width: 130mm;
		height: 28mm;
		line-height: 16pt;
		font-size: 10pt;
		text-align: right;
	}

	
	
	.title {
		padding: 2mm;
		width: 170mm;
		background-color: #2C3E50;
		font-size: 10pt;
		font-weight: bold;
		color: #ffffff;
	}
	
	.info {
		background-color: #f0f0f0;
		padding: 2mm;
		width: 170mm;
	}
	
	.line {
		width: 170mm;
	}
	
  
	
	.stats {
		width: 170mm;
	}
	
	.stats .info {
		padding: 2mm;
		width: 170mm;
		
	}
	
		.stats-text { 

		}

		.percent {
			position: relative;
			height: 3mm;
			margin-left: 10mm;
			
		}

			.percent img
			{
				width: 152mm;
				height: 3mm;
			}
			
			.cache {
				position: absolute;
				height: 5mm;
				background-color: #f0f0f0;
			}
	
	
	
	.resultats {
		margin: 1mm;
		width: 99%;
		border-collapse: collapse;
	}
  
		.resultats th, .resultats td {
			padding: 2mm;
			font-size: 9pt;
			text-align: center;
			vertical-align: middle;
		}

		.resultats th {
			border: 1px solid #ffffff;
			color: #ffffff;
			cursor: pointer;
			background-color: #F7A22F;
		}

		.resultats td {
			border: 1px solid #ffffff;
		}


		.red {
			background-color: #e67373;
		}

		.green {
			background-color: #6dda9b;
		}

		.white {
			background-color: #ffffff;
		}
  
	#footer {
		background: url(<?php echo ROOT; ?>media/images/footer-page.jpg) repeat-x;  
	}
		
	.txt-footer{
		text-align: left;
		color:#b3b3b3;
		padding-top:15px;
		font-size: 8pt;
	}

	.dump {
		font-size: 6pt;
		word-wrap: break-word;
		word-break: break-all;
	}


	/* Catégories */

	.categories-list {
		width: 100%;
		color: #757575; 
		font-size: 12px; 
		font-weight: normal;
	}
		
		.categories-list>table {
			padding: 0;
			margin: 0;
		}
		

			.categories-list .progressbar-title {
				width: 100%;
				height: 32px;
			}


				.categories-list td h3 {
					display: inline-block;
					float: left;
					height: 32px;
					margin: 0;
					padding: 0;
					line-height: 32px;
				}
				
				.categories-list td h3:hover {
					/*text-shadow: 0 0 1px rgba(255, 255, 255, 0.7);*/
				}

				.categories-list td h3:before {
					float: left;
					width: 15px;
					height: 32px;
					content: '\f105';
					color: #009688;
					font-family: "FontAwesome";
					text-align: left;
					line-height: 34px;
				}


				.categories-list td.active h3:before {
					content: '\f107';
				}



					.categories-list td h3 a {
						padding: 0;
						font-size: 13px; 
						font-weight: bold; 
						color: #555555;
						text-decoration: none;
						cursor: pointer;
					}

					.categories-list td .progressbar-title span {
						float: right;
						display: inline-block;
						text-align: right;
						line-height: 32px;
						font-weight: bold;
						color: #555555;
					}


				.categories-list .progress-title a {
					padding: 0;
					height: 28px;
					font-size: 12px; 
					color: #555555;
					line-height: 28px;
					text-decoration: none;
					cursor: pointer;
				}

				.categories-list .progress-title a:before {
					float: left;
					width: 14px;
					height: 28px;
					content: '\f105';
					color: #00bfa5;
					font-family: "FontAwesome";
					text-align: left;
					line-height: 28px;
				}

				.categories-list td .progress-title span {
					float: right;
					display: inline-block;
					height: 28px;
					text-align: right;
					line-height: 28px;
				}

				.categories-list td.active>a:not(:only-child):before {
					content: '\f105';
					font-family: "FontAwesome"; 
					font-size: 1em;
				}

				.categories-list td h3:hover:not(:only-child):before {
					content: '\f107';
				}
		
</style>




<page backleft="10mm" backright="10mm" backtop="5mm" backbottom="20mm">
	

	<table>
		<tr>
			<td rowspan="2" style="width:4mm;"></td>
			<td class="titre-h1">Restitution du positionnement <?php echo Config::POSI_NAME; ?></td>
		</tr>
		<tr>
			<td class="titre-infos">
				<?php $dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10)); ?>
				<?php $timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); ?>
				<?php $time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); ?>
				<p><strong><?php echo $response['infos_user']['prenom']; ?> <?php echo $response['infos_user']['nom']; ?></strong><br/>
				Positionnement du <?php echo $dateSession; ?> à <?php echo $time; ?></p>
			</td>
			
		</tr>
	</table>

		
	<table>
		
		<tr>
			<td class="title">Informations utilisateur</td>
		</tr>
		
		<?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user'] ?>

			<tr>
				<td class="info">Nom de l'organisme : <strong><?php echo $infos_user['nom_organ']; ?></strong></td>
			</tr>
			<tr>
				<td class="info">Nom de l'intervenant : <strong><?php echo $infos_user['nom_intervenant']; ?></strong></td>
			</tr>
			<tr>
				<td class="info">Email de l'intervenant : <strong><?php echo $infos_user['email_intervenant']; ?></strong></td>
			</tr>
			<tr>
				<td class="line"></td>
			</tr>
			<tr>
				<td class="info">Nom : <strong><?php echo strtoupper($infos_user['nom']); ?></strong></td>
			</tr>
			<tr>
				<td class="info">Prénom : <strong><?php echo $infos_user['prenom']; ?></strong></td>
			</tr>
			<tr>
				<td class="info">Date de naissance : <strong><?php echo $infos_user['date_naiss']; ?></strong></td>
			</tr>
			<tr>
				<td class="info">Niveau d'études : <strong><?php echo $infos_user['nom_niveau']; ?></strong></td>
			</tr>
			<tr>
				<td class="line"></td>
			</tr>
			<tr>
				<td class="info">Nombre de positionnements terminés : <strong><?php $infos_user['nbre_positionnements']; ?></strong></td>
			</tr>
			<tr>
				<td class="info">Date du dernier positionnement : <strong><?php echo $infos_user['date_last_posi']; ?></strong></td>
			</tr>

		<?php else : ?>

			<tr>
				<td class="info">Aucun utilisateur n'a été sélectionné.</td>
			</tr>

		<?php endif; ?>
			
		<tr>
			<td><hr/></td>
		</tr>
			
			
	</table>

	<br/><br/>
	<!-- <table>
		<tr>
			<td class="dump"><?php 
				//$ex = explode('><', $catList);
				//for ($i=0; $i < 60; $i++) { 
				//	var_dump($ex[$i]);
				//}
			?></td>
		</tr>
	</table> -->
	



	<table>

		<tr>
			<td class="title">Les statistiques</td>
		</tr>
		

		<?php if (!empty($response['stats'])) : $stats = $response['stats'];
			$tempsTotal = Tools::timeToString($response['session'][0]->getTempsTotal()); ?>

			<tr>
				<td class="info">Temps total : <strong><?php echo $tempsTotal; ?></strong></td>
			</tr>
			<?php if (!empty($stats['percent_global'])) : ?>
			<tr>
				<td class="info">Taux de réussite global : <strong><?php echo $stats['percent_global']; ?>%</strong> (<strong><?php echo $stats['total_correct_global']; ?></strong> réponses correctes sur <strong><?php echo $stats['total_global']; ?></strong> questions)</td>
			</tr>  
			<?php endif; ?>
			

			<tr>
				<td class="line"><br/></td>
			</tr>
			
			
			<tr>
				<td class="info">
					<table class="categories-list">

						<?php echo $catList; ?>
					</table>
				</td>
			</tr>
			
			<tr>
				<td class="line"><br/></td>
			</tr>

		<?php else : ?>

			<tr>
				<td class="info">Aucun positionnement n'est sélectionné.</td>
			</tr>
			
		<?php endif; ?>

		<tr>
			<td><hr/></td>
		</tr>
		
	</table>
	
	<br/><br/>
	
	
	<table>
		<tr>
			<td class="title">Détails des résultats</td>
		</tr>
	</table>     

	<table class="resultats">

		<?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user']; ?>
			<thead>
				<tr>
					<th style="width: 15%;">Questions</th>
					<th style="width: 30%;">Catégorie /<br/>compétence</th>
					<th style="width: 8%;">Degré</th>
					<th style="width: 30%;">Réponse utilisateur</th>
					<th style="width: 9%;">Réponse<br/>correcte</th>
					<th style="width: 8%;">Réussite</th>
				</tr>
			</thead>
			
			<tbody>
			<?php
			$i = 0;
			foreach($response['details']['questions'] as $detail)
			{
				if($i % 2 == 0)
				{
					echo '<tr style="background-color:#FCE7CA;" >';
				}
				else
				{
					echo '<tr style="background-color:#FFF6EA;">';
				}

				echo '<td style="width:15%;">';
						echo 'Question n°'.$detail['num_ordre'];
				echo '</td>';
				
				echo '<td style="width:30%; font-size:8pt; text-align:left;">';
					if (isset($detail['categories'][0]['nom_cat_parent']) && !empty($detail['categories'][0]['nom_cat_parent']))
					{
						echo '<strong>'.$detail['categories'][0]['nom_cat_parent']." : </strong><br/>";
					}
					echo $detail['categories'][0]['nom_cat'];
				echo '</td>';
									

				echo '<td style="width: 8%;">';
					echo $detail['nom_degre'];
				echo '</td>';

				if (!empty($detail['reponse_user_qcm']) && $detail['reponse_user_qcm'] != "-")
				{
					echo '<td style="width: 30%;">'.$detail['reponse_user_qcm'].'</td>';
				}
				else if (!empty($detail['reponse_user_champ']))
				{
					if ($detail['reponse_user_champ'] == "-")
					{
						echo '<td style="width: 30%; text-align: center;">'.$detail['reponse_user_champ'].'</td>';
					}
					else 
					{
						echo '<td style="width: 30%; text-align: left;">'.$detail['reponse_user_champ'].'</td>';
					}
					
				}
				else
				{
					echo '<td style="width: 30%; text-align: center;">-</td>';
				}

				echo '<td style="width: 9%;">'.$detail['reponse_qcm_correcte'].'</td>';

				if ($detail['reussite'] === 1)
				{
					echo '<td  style="width:8%;"><img src="'.SERVER_URL.'media/images/valide.png"></td>';
				}
				else if ($detail['reussite'] === 0)
				{
					echo '<td class="red-cell"  style="width:8%;"><img src="'.SERVER_URL.'media/images/faux.png"></td>';
				}
				else
				{
					echo '<td class="white-cell"  style="width:8%;"><img src="'.SERVER_URL.'media/images/stylo.png"></td>';
				}


				echo '</tr>';  
				$i++;
			} 
			?>
			</tbody>
			
		<?php else : ?>

			<tr>
				<td class="info">Aucun détail à afficher.</td>
			</tr>

		<?php endif; ?>
			
	</table>

</page>