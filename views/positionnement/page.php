<?php

	$form_url = $response['url'];

    $imageFile = $response['question']->getImage();
    $audioFile = $response['question']->getSon();
    $videoFile = $response['question']->getVideo();

?>


	<div id="posi-page" class="main">
		
		<div class="header">

			<div class="header-wrapper">
				<div class="logo">
					<!-- <img src="images/logo-dalia_40x40.png" alt="Positionnement Dalia"> -->
				</div>
				<div class="header-title">
					<i class="fa fa-file-text-o"></i>
					<h1>Test de positionnement</h1>
				</div>
				<div class="clear"></div>
			</div>

		</div>
		

		<div class="content">
			
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
				<?php if (!empty($videoFile)) : ?>

					<div class="media-display" id="media-question">
						<div id="lecteurvideo" class="projekktor"></div>
						<div class="btn-suite">
							<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
						</div>
					</div>
					
				<?php elseif (empty($videoFile) && !empty($imageFile)) : ?>

					<div class="media-display" id="media-question">
						
						<div id="audio"></div>

						<div id="visuel"></div>

						<div id="loader" class="image-loader"></div>

						
						<?php if (!empty($audioFile)) : ?>
						
						<!-- 
						<button type="button" id="speaker">
							<i class="fa fa-volume-up"></i>
						</button> -->
						
						<div id="speaker">
							<button type="button" id="speaker-button">
								<i class="fa fa-volume-up"></i>
							</button>
							<svg id="svg-speaker-progress" version="1.1" viewBox="0 0 46 46" preserveAspectRatio="xMinYMin meet">
								<circle id="speaker-loader" r="20" transform="translate(23.5, 23) rotate(-90)"></circle>
								<circle id="speaker-progress" r="20" transform="translate(23.5, 23) rotate(-90)"></circle>
							</svg>
						</div>

						<?php endif; ?>

						<!-- <div id="black-bg"></div> -->

						<div id="btn-suite">
							<div class="vert-align"></div><!-- Pas d'espace impératif entre ces 2 éléments
						 --><input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
						</div>
						
					</div>

				<?php else : ?>
					
					<div class="media-display" id="media-question">

						<div class="btn-suite">
							<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
						</div>

					</div>

				<?php endif; ?>

			

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
						echo '<textarea class="reponse-champ" id="reponse-champ" name="reponse_champ"></textarea>';
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

		<!-- Footer -->
		<?php
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>




	<!-- JQuery -->
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js"></script>
	
	<!-- Outils -->
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/placeholders.min.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/flash_detect.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/navigator-agent.js"></script>
	
	<!-- Medias -->
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/projekktor/projekktor-1.3.09.min.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/image-loader.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/audio-player.js"></script>
	<!--<script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/video-player.js"></script>-->
	

	<script type="text/javascript">


		$(function() {
			
			"use strict";

			/**
				Etats :
				-------

				- Creation de l'image
				- Création du lecteur video
				- Création du lecteur audio


				- Le loader apparait
				- Le loader disparait

				- Le bouton suite est désactivé
				- Le bouton suite est affiché
				- Le bouton suite est masqué


				- Le bouton son est désactivé
				- Le bouton son est affiché
				- Le bouton son est masqué
				

				- Le lecteur audio est chargé
				- Le lecteur audio est créé
				- Le lecteur audio démarre
				- Le lecteur audio est stoppé
				- Le lecteur audio est en pause
				- Le lecteur audio est affiché
				- Le lecteur audio est masqué

				- l'image est créée
				- L'image est chargée
				- L'image est affichée
				- L'image est masquée

				

				- Les boutons radio sont désactivés
				- Les boutons radio sont activés

				- Le champ texte est désactivé
				- Le champ texte est activé

				- Sur image chargée
				- Sur son chargé
				- Sur vidéo chargée

				- Sur bouton son clické
				- Sur bouton suite clické

			**/
			

			// Le bouton suite est desactiver par défaut.
			$("#submit-suite").prop("disabled", true);
			$("#submit-suite").hide();
			var $suite = $("#btn-suite");
			$("#btn-suite").remove();


			// Le barre du lecteur audio est cachée.
			//$("#audio").hide();

			// Le haut-parleur est également caché le temps du chargement du son
			//$(".speaker").prop('disabled', true);


			// Désactivation des éléments de réponse
			if ($('.reponse-qcm') !== null) {

				// S'il y a des boutons radio, on les désactive.
				$(".reponse-qcm").prop("disabled", true);
			}

			if ($('#reponse-champ') !== null) {

				// S'il y a une champ de réponse, on le désactive et on met un placeholder.
				$("#reponse-champ").prop("disabled", true);
				$("#reponse-champ").attr("placeholder", "Veuillez attendre que le son se termine...");
			}


			// Récupération des noms des différents médias dans les valeurs assignées aux champs cachés du formulaire.
			var imageFilename = $('#image-filename').val();
			var audioFilename = $('#audio-filename').val();
			var videoFilename = $('#video-filename').val();

			// Si le média possède un nom, une variable correspondant à ce média contient la valeur "vraie".
			var imageActive = imageFilename !== '' ? true : false;
			var videoActive = videoFilename !== '' ? true : false;
			var audioActive = audioFilename !== '' ? true : false;



			// Création d'une variable permettant de savoir si le lecteur video a terminé la lecture du média.
			var isVideoComplete = false;

			// Etat du chargement de l'image
			var isImageLoaded = false;

			// Etat du chargement du lecteur audio
			var isAudioLoaded = false;

			// Etat de lecture du lecteur audio
			var audioIsPlaying = false;


			// Timer du chargement de l'image
			var timerImage = null;

			// Timer de selection automatique du champ de saisie
			var timerPlayerComplete = null;

			var playerComplete = false;


			// Contenu du lecteur audio
			//var audioHtml = '';



			


			/* Création / Gestion des médias */

			
			// Fonction appelée par l'objet ImageLoader lorsque l'image est chargée.

			function onImageLoaded() {

				$('#visuel img').fadeIn(1500);

				//$('#audio').show();

				//this.fadeToBlack($('body').children().first(), 5000);

				//$('#media-question').append($suite);

				// Creation du lecteur audio s'il y a une source
				if (audioActive) {

					//createAudioPlayer();
				}
				else {

					//$(".reponse-qcm").prop("disabled", false);
				}
			}


			function onAudioProgress(percent) {

				//var offset = parseInt($('#speaker-progress').css('stroke-dasharray')) / 100 * (100 - percent);
				//var offset = parseInt($('#speaker-progress').css('stroke-dasharray')) / 100 * -percent;
				//$('#speaker-progress').css('stroke-dashoffset', offset.toString());

				var rotation = Math.round(360 / 100 * percent - 90);
				$('#speaker-progress').attr('transform', 'translate(23.5, 23) rotate(' + rotation.toString() + ')');
			}

			function onAudioCompleted() {

				console.log('audioCompleted');
				playerComplete = true;

				//var dasharrayValue = parseInt($('#speaker-progress').css('stroke-dasharray'));
				//$('#speaker-progress').css('stroke-dashoffset', dasharrayValue);

				if ($('.reponse-qcm') !== null) {

					$(".reponse-qcm").prop("disabled", false);
				}

				if ($('#reponse-champ') !== null) {

					$('#reponse-champ').removeProp('disabled');
					$('#reponse-champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
					$('#reponse-champ').focus();
				}
			}




			// Création de l'image
			var imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;

			var imageLoader = new ImageLoader($('#visuel'), $('#loader'), onImageLoaded);
			imageLoader.startLoading(imageUrl, 500);




			// Création du lecteur audio caché (contrôle via le bouton speaker)
			
			var audioUrl = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

			var audioPlayer = new AudioPlayer(audioUrl);

			// Création du player
			if (navAgent.isAudioEnabled()) {

				audioPlayer.setPlayerType('html');
				audioPlayer.create($('#audio'), null, {w: 200, h: 40});
			}
			else if (FlashDetect.installed) {

				audioPlayer.setPlayerType('dewp-mini');
				var playerAudioUrl = '<?php echo SERVER_URL; ?>media/dewplayer/';
				audioPlayer.create($('#audio'), playerAudioUrl, {w: 200, h: 40});
			}
			else {

				alert('Ce navigateur ne prend pas en charge les médias audio.');
			}

			audioPlayer.attachControls({startBtn: $("#speaker-button")});
			
			audioPlayer.setOnProgressCallBack(onAudioProgress);
			audioPlayer.setOnCompleteCallBack(onAudioCompleted);
			
			//audioPlayer.enable(false);
			//audioPlayer.startPlaying();
			

			/*** Events ***/
			
			// Sur click d'un des boutons radio
			$(".reponse-qcm").on("click", function(e) {

				if (playerComplete) {

					//imageLoader.fadeToBlank($('#media-question'), 0);
					imageLoader.fadeToBlack($('#media-question'), 2000);
					$('#media-question').append($suite);
					$("#submit-suite").hide().fadeIn(2000);
					$("#submit-suite").prop("disabled", false);
				}
				else {

					$(this).attr("checked", false);
				}
			});
			

			// Sur click dans le champ de réponse s'il existe.
			$("#reponse-champ").on("click", function(e) {

				if (playerComplete) {

					// $('#media-question').append($suite);
					// $("#submit-suite").show(500);
					// $("#submit-suite").prop("disabled", false);

					//$(this).removeProp("readonly");
					//$(this).prop("placeholder", "Vous pouvez écrire votre réponse.");
				}
				else {

					$(this).blur();
				}
			});
			

			// Lorsque l'utilisateur effectue une saisie dans le champ de réponse.

			$("#reponse-champ").on("keydown", function(e) {

				// On s'assure que la vidéo ou le son sont terminés
				// et que l'utilisateur a saisi au moins 2 caractères.
				if (playerComplete) {

					$(this).attr("placeholder", "");
					$('#media-question').append($suite);

					if ($(this).val().length > 1) {

						
						$("#submit-suite").show(500);
						$("#submit-suite").prop("disabled", false);
					}
					else if ($(this).val().length <= 1) {

						$("#submit-suite").prop("disabled", false);
					}
				}
			});


			/*
			function getPlayerComplete() {

				var mediaPlayer = null;

				if (videoActive) {

					if (isVideoComplete) {

						return true;
					}

				}
				else if (audioActive) {

					if (FlashDetect.installed) {

						mediaPlayer = document.getElementById('dewplayer');

						if (mediaPlayer != null) {
							
							if (mediaPlayer.dewgetpos() == 0 && isAudioLoaded)
							{
								return true;
							}
						}
					}
					else {

						mediaPlayer = document.getElementById('audioplayer');

						if (mediaPlayer != null) {

							if ((mediaPlayer.duration - mediaPlayer.currentTime) == 0 && isAudioLoaded) {

								return true;
							}
						}
					}
				}

				return false;
			}
			*/
			
			/*
			function checkPlayerComplete() {


				if (getPlayerComplete()) {

					clearInterval(timerPlayerComplete);
					audioIsPlaying = false;

					if ($('.reponse-qcm') != null) {

						$(".reponse-qcm").prop("disabled", false);
					}

					if ($('#reponse-champ') != null) {

						$('#reponse-champ').removeProp('disabled');
						$('#reponse-champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
						$('#reponse-champ').focus();
					}
				}
			}
			*/

			
			/*
			function onAudioPlayerLoaded() {
				
				var dewp = document.getElementById('dewplayer');
				var $playerHtml = $('#audioplayer');
				
				if (dewp != null) {

					dewp.style.display = 'block';
					//dewp.dewplay();
					$(".speaker").prop('disabled', false);
					//audioIsPlaying = true;
				}
				else if ($playerHtml != null) {
					
					$playerHtml.css('display', 'block');
					$playerHtml.prop('disabled', false);
					//audioIsPlaying = true;
				}
				else {
					alert('player not found');
				}
				

				isAudioLoaded = true;


				timerPlayerComplete = setInterval(checkPlayerComplete, 500);
			}
			*/

			/*
			function checkImageLoaded() {

				if (isImageLoaded) {

					clearInterval(timerImage);

					if (audioActive) {

						createAudioPlayer();
					}
					else {

						$(".reponse-qcm").prop("disabled", false);
					}
				} 
			}

			function displayImage(link) {
				
				var imageBox = new Image();

				imageBox.onload = function() {

					$('.media-display').css('height', 'auto').css('padding-bottom', '0');
					$('.image-loader').fadeOut(250);
					$('#media-question').prepend(imageBox);
					$('#media-question img').hide().fadeIn(1000);
					isImageLoaded = true;
				};

				imageBox.src = link;
				$('.image-loader').fadeIn(250);
			}
			*/

			/*
			function audioPlay() {

				if (!audioIsPlaying) {

					var dewp = document.getElementById('dewplayer');
					var playerHtml = document.getElementById('audioplayer');
					
					if (dewp != null) {

						dewp.dewplay();
						audioIsPlaying = true;
					}
					else if (playerHtml != null) {
						
						playerHtml.play();
						audioIsPlaying = true;
					}
				}
			}
			*/

			/*
			function audioPause() {

				if (audioIsPlaying) {

					var dewp = document.getElementById('dewplayer');
					var playerHtml = document.getElementById('audioplayer');
					
					if (dewp != null) {

						dewp.dewpause();
						audioIsPlaying = false;
					}
					else if (playerHtml != null) {
						
						playerHtml.pause();
						audioIsPlaying = false;
					}
				}
			}
			*/


			/* Création et instanciation des médias */

			// Chargement de l'image (si il n'y a pas de vidéo)
			
			if (imageActive) {
				/*
				var imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;
				
				if (!videoActive) {

					//displayImage(imageUrl);
				} 
				*/              
			}


			
			// S'il existe une video on créé le lecteur vidéo, le lecteur audio ne doit pas être créé.
			if (videoActive) {
				/*
				// L'image, si elle existe, sert alors de "poster" pour la vidéo.
				var imageUrl = imageFilename ? '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename : '';

				// On récupère l'adresse absolue du lecteur vidéo Flash (pour les navigateurs qui ne supportent pas le HTML5).
				var videoPlayerUrl = '<?php echo SERVER_URL; ?>media/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf';

				// Puis l'adresse absolue de la vidéo.
				var videoUrl = '<?php echo SERVER_URL.VIDEO_PATH; ?>' + videoFilename;

				// On génére le lecteur vidéo et on le configure.
				projekktor('#lecteurvideo', {

						poster: imageUrl,
						title: 'Lecteur vidéo',
						playerFlashMP4: videoPlayerUrl,
						playerFlashMP3: videoPlayerUrl,
						width: 750,
						height: 420,
						controls: true,
						enableFullscreen: false,
						autoplay: true,
						playlist: [{
							0: {src: videoUrl, type: "video/mp4"}
						}],
						plugins: ['display', 'controlbar'],
						messages: {
							0: 'Une erreur s\'est produite.',
							1: 'Vous avez interrompu la lecture de la vidéo.',
							2: 'La vidéo n\'a pas pu être chargée.',
							3: 'La vidéo a été interrompue en raison d\'un problème d\'encodage.',
							4: 'Le média n\'a pas pu être chargé en raison d\'un problème avec le serveur.',
							5: 'Désolé, le format de la vidéo n\'est pas supporté par votre navigateur.',
							6: 'Vous devez disposer de la version %{flashver} ou plus du lecteur Flash.',
							7: 'Aucun média n\'a été trouvé.',
							8: 'La configuration du média est incompatible !',
							9: 'Le fichier (%{file}) n\'a pas été trouvé.',
							10: 'Les paramètres de qualité sont invalide pour %{title}.',
							11: 'Les paramètres de streaming sont invalides ou incompatible avec %{title}.',
							12: 'Le paramètrage de la qualité est incompatible pour %{title}.',
							80: 'Le média requis n\'existe pas ou son contenu est invalide.',
							97: 'Aucun média n\'a été prévu.',
							98: 'Les données de la playlist sont invalides !',
							99: 'Cliquez sur le média pour continuer. ',
							100: 'Espace réservé.'
						} 

					}, function(player) {

						// on player ready
						
						var stateListener = function(state) {

							switch(state) {
									
								case 'PLAYING':
									break;

								case 'PAUSED':

									$('.ppstart').removeClass('inactive');
									$('.ppstart').addClass('active');
									break;

								case 'STOPPED':
								case 'IDLE':
								case 'COMPLETED':

									$(".reponse-qcm").prop("disabled", false);
									
									isVideoComplete = true;

									checkPlayerComplete();
									break;
							}
						};

						player.addListener('state', stateListener);

						
						var playerError =  function(data) { 

							isVideoComplete = true; 

							$('#lecteurvideo').html('');

							if (imageActive) {

								//displayImage(imageUrl);
							}                 
						};
						player.addListener('error', playerError);
						
					}
				);
				*/
			}
			

			// Sinon, on créé le lecteur audio
			
			else if (audioActive) {

				/*
				var audioHtml = '';

				var playerAudioUrl = '<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf';
				var audioUrl = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

				if (navAgent.isAudioEnabled()) {

					audioHtml += '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" controls></audio>';
				}
				else if (FlashDetect.installed) {
					
					audioHtml += '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
					audioHtml += '<param name="movie" value="' + playerAudioUrl + '" />'; 
					audioHtml += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
					audioHtml += '<param name="wmode" value="transparent" />';
					audioHtml += '</object>';
				}
				else {
					
					//audioHtml += '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" controls></audio>';
					alert('Audioplay not supported by the browser');
				}
				

				//timerImage = setInterval(checkImageLoaded, 500);
				*/
			}
			
			


			/****   Evenements  *****/



			// Sur click du haut-parleur
			/*
			$(".speaker").on("click", function(e) {

				if (isAudioLoaded) {

					if (!audioIsPlaying) {

						audioPlay();
					}
					else {

						audioPause();
					}
				}
			});
			*/

			
			/*
			// Sur click d'un des boutons radio
			$(".reponse-qcm").on("click", function(e) {

				if (getPlayerComplete()) {

					$("#submit-suite").removeProp("disabled");
					$("#submit-suite").show(250);
				}
				else {

					$(this).attr("checked", false);
				}
			});
			

			// Sur click dans le champ de réponse s'il existe.
			$("#reponse-champ").on("click", function(e) {

				if (getPlayerComplete()) {

					//$(this).removeProp("readonly");
					//$(this).prop("placeholder", "Vous pouvez écrire votre réponse.");
				}
				else {

					$(this).blur();
				}
			});
			

			// Lorsque l'utilisateur effectue une saisie dans le champ de réponse.

			$("#reponse-champ").on("keydown", function(e) {

				// On s'assure que la vidéo ou le son sont terminés
				// et que l'utilisateur a saisi au moins 2 caractères.
				if (getPlayerComplete()) {

					$(this).attr("placeholder", "");
					
					if ($(this).val().length > 1) {

						$("#submit-suite").removeProp("disabled");
						$("#submit-suite").show(250);
					}
					else if ($(this).val().length <= 1) {

						$("#submit-suite").prop("disabled", true);
					}
				}
			});
			*/

			/*
			$("#submit-suite").on("click", function(e) {

				var innerHtml = '<div id="popbox-fill"></div>';
				$("body").append(innerHtml);

				$("#popbox-fill").hide().fadeTo(250, 0.7);
				$("#popbox-fill").on('click', function(event) {
					$(this).fadeOut(PopBox.animDuration, function() {
						$(this).remove();
					});
				});
			});
			*/

			
		});



	</script>