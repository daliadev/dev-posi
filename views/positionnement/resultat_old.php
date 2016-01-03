<?php

	/*-------------------- Envoi d'emails ----------------------*/
	/*
	$content = "";
	foreach ($response['correction'] as $correction)
	{
		if ($correction['parent'])
		{         
			if ($correction['total'] > 0)
			{
					$content .= '</br>';
					$content .= $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].' questions)';
			}
		}
	}
	*/
	/*
	$Destinataire = "";
	foreach (Config::$emails_admin as $email_admin) 
	{
		$Destinataire .=  $email_admin.',';
	}


	if (Config::ENVOI_EMAIL_REFERENT == 1 && isset($response['email_infos']['email_intervenant']) && !empty($response['email_infos']['email_intervenant'])) 
	{
		$Destinataire .=  $response['email_infos']['email_intervenant'];
	}

	$pourqui = "f.rampion@educationetformation.fr";
	$Sujet = Config::POSI_NAME;

	$From  = "From:";
	$From .= $pourqui;
	$From .= "\n";
	$From .= "MIME-version: 1.0\n";
	$From .= 'Content-Type: text/html; charset=utf-8'."\n"; 

	
	$message = '<html><head><title>'.Config::POSI_NAME.'</title></head>';
	$message .= '<body>';
	$message .= 'Date du positionnement : <strong>'.$response['email_infos']['date_posi'].'</strong><br/>';
	$message .= 'Organisme : <strong>'.$response['email_infos']['nom_organ'].'</strong><br/>';
	$message .= '<br/>';
	$message .= 'Nom : <strong>'.$response['email_infos']['nom_user'].'</strong><br/>';
	$message .= 'Prénom : <strong>'.$response['email_infos']['prenom_user'].'</strong><br/>';
	$message .= 'Email intervenant : <strong>'.$response['email_infos']['email_intervenant'].'</strong><br/>';
	$message .= '<br/>';
	$message .= 'Temps : <strong>'.$response['email_infos']['temps_posi'].'</strong><br/>';
	$message .= 'Score globale : <strong>'.$response['percent_global'].' %</strong><br/>';
	$message .= '<br/>';
	$message .= 'Score détaillé : <br/>'.$content;
	$message .= '<br/>';
	$message .= '<br/>';
	$message .= 'Votre accès à la page des résultats : '.$response['email_infos']['url_restitution'];
	$message .= '<br/>';
	$message .= 'Votre accès à la page des statistiques : '.$response['email_infos']['url_stats'];

	$message .= '</body>';
	$message .= '</html>';
						 
	mail($Destinataire,$Sujet,$message,$From);
	*/

	/*-------------------------------------------------*/


	// Attribut une couleur selon le pourcentage du résultat

	function getColorClass($percent)
	{
		$percent = intval($percent);
		
		$color = "gris";

		if ($percent < 40)
		{
			// Rouge
			$color = "danger";
		}
		else if ($percent >= 40 && $percent < 60)
		{
			// Orange
			$color = "primary2";
		}
		else if ($percent >= 60 && $percent < 80)
		{
			// Jaune
			$color = "warning";
		}
		else if ($percent >= 80)
		{
			// Vert
			$color = "success";
		}

		return $color;
	}


	$time = $response['temps'];
	$percentGlobal = $response['percent_global'];
	$totalGlobal = $response['total_global'];
	$totalCorrectGlobal = $response['total_correct_global'];


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
				<h2>Résultats</h2>
				<i class="fa fa-chevron-down"></i>
				<div class="clear"></div>
			</div>

			<form class="form-results" id="form-results" name="form_results" action="<?php //echo $form_url; ?>" method="post">

		
				<fieldset>

					<!-- 
					<div class="fieldset-title" id="titre-organ">
						<i class="fa fa-cube"></i> <h2 class="section-form"> Votre organisme</h2>
					</div> 
					-->


					<?php
						/*
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
						*/
					?>
					


					<p class="form-text-large"><strong>Voici vos résultats au test de positionnement :</strong></p>

					<p>Taux de réussite globale : <strong class="text-<?php echo getColorClass($percentGlobal); ?>"><?php echo $percentGlobal; ?>%</strong> (<?php echo $totalCorrectGlobal; ?>/<?php echo $totalGlobal; ?>)</p>
					
					<div id="r-ccsp" class="progressbars">

						<?php

						foreach ($response['correction'] as $correction)
						{
							if ($correction['parent'])
							{
								$percent = $correction['percent'];
								if ($percent == 0)
								{
									$percent = 4;
								}

								if ($correction['total'] > 0)
								{
									$color = getColorClass($correction['percent']);

									echo '<p class="form-text-large">';
										echo $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].')';
										echo '<div class="progress">';
                      						echo '<div class="progress-bar progress-bar-'.$color.'" style="width: '.$percent.'%;"></div>';
											//echo '<span class="bg-'.$color.'" style="width:'.$percent.'%;"></span>';
										echo '</div>';
									echo '</p>';

								}
							}
						}

						?>

					</div>

					<div>
						<p>Temps passé : <strong><?php echo $time; ?></strong></p>
					</div>

					<hr/>
					
					<p class="form-text"><strong>Vous êtes maintenant déconnecté de l'application.</strong></p>



				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>
		
		<!-- Footer -->
		<?php
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>


	
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>


	<script language="javascript" type="text/javascript">
	   
		$(function() { 
			
			//$("#R-CCSP").tooltip();
			/*
			$(".result").tooltip({
				items: "[title]",
				content: function() {
					var element = $( this );
					
					if ( element.is( "[title]" ) ) {
						var text = element.text();
						return text;
					}
				}
			});
			*/
		});   

	</script>