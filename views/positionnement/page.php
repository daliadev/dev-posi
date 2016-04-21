<?php

	$form_url = $response['url'];

    $imageFile = $response['question']->getImage();
    $audioFile = $response['question']->getSon();
    $videoFile = $response['question']->getVideo();

    if ($imageFile != null)
	{
		$imageFile = SERVER_URL.IMG_PATH.$imageFile;
	}

	if ($audioFile != null)
	{
		$audioFile = SERVER_URL.AUDIO_PATH.$audioFile;
	}

	if ($videoFile != null)
	{
		$videoFile = SERVER_URL.VIDEO_PATH.$videoFile;
	}

?>


	<div class="content-form">
			
			<!-- Formulaire réponse -->
			<form class="form-page" id="form-page" name="form_page" action="<?php echo $form_url; ?>" method="post">
			
				<input type="hidden" name="num_page" value="<?php echo $response['question']->getNumeroOrdre(); ?>" />
				<input type="hidden" name="ref_question" value="<?php echo $response['question']->getId(); ?>" />
				
				<input type="hidden" id="image-filename" name="image-filename" value="<?php echo $imageFile; ?>" />
				<input type="hidden" id="audio-filename" name="audio-filename" value="<?php echo $audioFile; ?>" />
				<input type="hidden" id="video-filename" name="video-filename" value="<?php echo $videoFile; ?>" />
				
				<!-- Timer -->
				<?php
					$startTimer = microtime(true);
				?>
				<input type="hidden" name="start_timer" value="<?php echo $startTimer; ?>" />


				<!-- Image ou vidéo -->
				<div class="media-display" id="media-question">
	
					<div id="audio"></div>
					
					<?php if (!empty($imageFile) || !empty($videoFile) || !empty($audioFile)) : ?>

					<div id="visuel">

						<?php if (!empty($videoFile)) : ?>

						<div id="video" class="projekktor"></div>

						<?php endif; ?>

					</div>
					
					<div id="loader">
						<div class="image-loader"></div>
						<div class="custom-loader"></div>
					</div>


					
						<?php if (!empty($audioFile)) : ?>
						


						<!-- Audio -->
						<!-- <div id="audio"> -->
							
							<!-- Lecteur audio  sous-forme d'îcone html par defaut, sinon lecteur flash -->
							<div id="speaker">
								
								<button id="speaker-button" type"button">
									<svg id="svg-speaker-bg" version="1.1" viewBox="0 0 48 48" preserveAspectRatio="xMinYMin meet">
										<circle id="speaker-bg" r="24" transform="translate(24, 24) rotate(-90)"></circle>
									</svg>
									<div class="button-icon">
										<span class="ripple"><i id="speaker-icon" class="fa fa-music"></i><!-- <i id="speaker-icon"class="fa fa-headphones"></i> --></span>
										<!-- <span class="ripple"><i id="speaker-icon" class="fa fa-refresh fa-spin"></i></span> -->
										<!-- <i class="fa fa-volume-up"></i> -->
									</div>
									
								</button>
	
							</div>
						<!-- </div> -->

						<?php endif; ?>

					<?php endif; ?>

					<div id="btn-suite">
						<!-- <input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" /> -->
						<button type="submit" class="btn btn-primary" id="submit-suite" name="submit_suite" title="Cliquez sur ce bouton pour continuer" style="width: 100px;">Suite</button>
					</div>
				
				</div>

				<!-- Intitulé question -->
				<div class="question" id="intitule-question">
					<p><?php echo $response['question']->getNumeroOrdre().'. '.$response['question']->getIntitule(); ?></p>
				</div>
				
				
				<!-- Réponse de l'utilisateur -->
				<div class="reponse-user" id="reponse-user">

					<?php
					
					if ($response['question']->getType() === 'qcm')
					{
						/* Réponse QCM */
						$j = 0;

						echo '<ul class="radio-group">';

						foreach ($response['reponse'] as $reponse)
						{
							echo '<li>';
								echo '<input type="radio" class="reponse-qcm" id="reponse-qcm-'.$j.'" name="reponse_qcm" value="'.$reponse->getId().'" />';
								echo '<label for="reponse-qcm-'.$j.'"> &nbsp;'.$reponse->getIntitule().'</label>';
							 echo '</li>';

							if ($reponse->getEstCorrect())
							{
								echo '<input type="hidden" name="ref_reponse_correcte" value="'.$reponse->getId().'" />';
							}

							$j++;
						}

						echo '</ul>';
					}
					else if ($response['question']->getType() === 'champ_saisie')
					{
						/* Réponse champ */
						echo '<div class="form-group">';
						echo '<textarea class="reponse-champ form-control" id="reponse-champ" name="reponse_champ"></textarea>';
						echo '</div>';
					}
					
					?>
	 
				</div>
				

				<!-- Audio (caché par du js, le bouton se trouve plus haut)-->
				
				<?php //if (empty($videoFile) && !empty($audioFile)) : ?>

					<!-- <div class="audio-media" id="audio"></div> -->

				<?php //endif; ?>
				

				<!-- Bouton suite -->
				<!-- <div class="btn-suite">
					<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
				</div> -->
				
				<!-- <div style="clear:both;"></div> -->

			</form>

		</div>





	<!-- JQuery -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js"></script> -->
	
	<!-- Outils -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/placeholders.min.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/flash_detect.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/navigator-agent.js"></script> -->
	
	<!-- Medias -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/projekktor/projekktor-1.3.09.min.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/image-controller.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/audio-player.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/video-player.js"></script>-->
	

	<!-- <script type="text/javascript"> -->


		



	<!-- </script> -->