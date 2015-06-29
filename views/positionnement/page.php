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
				<?php //if (!empty($videoFile)) : ?>
<!-- 
					<div class="media-display" id="media-question">
						<div id="lecteurvideo" class="projekktor"></div>
						<div class="btn-suite">
							<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
						</div>
					</div> -->
					
				<?php if (!empty($videoFile) || !empty($imageFile)) : ?>

					<div class="media-display" id="media-question">
						
						<div id="audio"></div>

						<div id="visuel">

							<?php if (!empty($videoFile)) : ?>

								<div id="video"></div>

							<?php endif; ?>

						</div>

						<div id="loader" class="image-loader"></div>

						
						<?php if (!empty($audioFile)) : ?>
						
						<!-- 
						<button type="button" id="speaker">
							<i class="fa fa-volume-up"></i>
						</button> -->
						
						<div id="speaker">
							<button type="button" id="speaker-button" class="button-info">
								<!-- <i class="fa fa-volume-up"></i> -->
								<img src="<?php echo SERVER_URL ?>media/images/ic_volume_up_white_24dp.png" />
							</button>
							<svg id="svg-speaker-progress" version="1.1" viewBox="0 0 52 52" preserveAspectRatio="xMinYMin meet">
								<circle id="speaker-loader" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
								<circle id="speaker-progress" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
							</svg>
						</div>

						<?php endif; ?>

						<!-- <div id="black-bg"></div> -->

						<div id="btn-suite">
							<!-- <div class="vert-align"></div> --><!-- Pas d'espace impératif entre ces 2 éléments
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
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/image-controller.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/audio-player.js"></script>
	<!--<script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/video-player.js"></script>-->
	

	<script type="text/javascript">


		$(function() {
			
			'use strict';


			/**********************************************
			*         Déclaration des variables           *
			**********************************************/


			// Variables contenants le nom du média.
			var imageFilename, audioFilename, videoFilename; // Type string


			// Variables pour spécifié qu'un média est actif pour la page ou non
			var isImageActive, isAudioActive, isVideoActive; // Type boolean


			// Variables controleur image
			var imageUrl; // Type string
			var imageContainer, imageLoader; // Type JQuery element
			var imageController = null; // Type ImageController


			// Variables controleur audio
			var playerType, playerURL; // Type string
			var audioTrack; // Type string
			var audioContainer, audioControls; // Type HTMLelement
			var audioPlayer = null;


			// Variables controleur vidéo
			var videoPlayerUrl, videoUrl;
			var videoPlayer = null;
			var videoCreateTimer;




			// Variables état/statut des médias
			
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
			


			/**********************************************
			*        Initialisation des variables         *
			**********************************************/


			// Récupération des noms des différents médias dans les valeurs assignées aux champs cachés du formulaire.
			imageFilename = $('#image-filename').val();
			audioFilename = $('#audio-filename').val();
			videoFilename = $('#video-filename').val();


			// Si le média possède un nom, une variable correspondant à ce média contient la valeur "vraie".
			isImageActive = imageFilename !== '' ? true : false;
			isAudioActive = audioFilename !== '' ? true : false;
			isVideoActive = videoFilename !== '' ? true : false;
			


			// Contrôle de l'image

			// URL complète de l'image
			imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;

			// Conteneur et icône animée de chargement de l'image
			imageContainer = $('#visuel');
			imageLoader = $('#loader');
			imageLoader.hide(); // Le loader est caché par défaut


			// Contrôle du son

			// Le type et l'URL du lecteur audio dépendent du navigateur
			// if (navAgent.isAudioEnabled()) {
			// 	playerType = 'html';
			// 	playerURL = null;
			// }
			// else 
			if (FlashDetect.installed) {

				playerType = 'dewp-mini';
				playerURL = '<?php echo SERVER_URL; ?>media/dewplayer/';
			}
			else {

				alert('Ce navigateur ne prend pas en charge les médias audio.');
			}

			// URL complète de la piste audio
			audioTrack = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

			// Conteneur du lecteur audio (caché)
			audioContainer = document.getElementById('audio');
			
			// Tableau des éléments de contrôle de l'audio (contrôle via le bouton speaker)
			audioControls = [{
				action: 'play',
				item: document.getElementById('speaker-button')
			}];



			// Contrôle de la vidéo

			// L'URL du lecteur vidéo alternatif flash (si balise <video> non supportée)
			videoPlayerUrl = '<?php echo SERVER_URL; ?>media/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf';

			// URL complète de la vidéo
			videoUrl = '<?php echo SERVER_URL.VIDEO_PATH; ?>' + videoFilename;



			/*** Fonctions de contrôle des interactions ***/


			// Désactivation du système de réponses

			// Activation du système de réponses

			// Affichage/activation du bouton 'speaker'

			// Masquage/désactivation du bouton 'speaker'

			// Affichage/activation du bouton 'suite'

			// Masquage/désactivation du bouton 'suite'




			/**********************************************
			*      Fonctions de gestion de l'image        *
			**********************************************/


			var createImage = function() {
				
				console.log('createImage');

				imageController = new ImageController(imageContainer, imageLoader, null);

				if (isAudioActive) {

					createAudio();
				}
				else {

					imageController.startLoading(imageUrl, 500, onImageLoaded);
				}
				
			}


				/* Evénements liés à l'image */


				// 1 : L'image est en cours de chargement

				var onImageLoading = function() {

					console.log('onImageLoading');
				}


				// 2 : L'image est chargée

				var onImageLoaded = function() {

					console.log('onImageLoaded');

					// Creation du lecteur audio s'il y a une source
					if (isAudioActive) {

						//audioPlayer.setTrack(audioTrack);
						//audioContainer.style.display = 'block';
						audioPlayer.startLoading(onAudioLoaded);
					}
					else {

						//$(".reponse-qcm").prop("disabled", false);
					}

					displayImage(1500);
				};


			// 3 : Affichage de l'image

			var displayImage = function(duration) {

				console.log('displayImage');
				/*
				if (isAudioActive) {

					$(audioContainer).fadeIn(duration);
				}
				*/
				imageController.display(duration, onImageDisplayed);
			}


				// 4 : Affichage de l'image terminé

				var onImageDisplayed = function() {

					console.log('onImageDisplayed');

					if (isAudioActive) {

						audioContainer.style.display = 'block';
					}
				};
				


			/**********************************************
			*       Fonctions de gestion de l'audio       *
			**********************************************/

			var createAudio = function() {

				console.log('createAudio');

				audioContainer.style.display = 'none';
				// Instanciation de l'objet AudioPlayer que gére et contrôle le son
				// Le player proprement dit est caché et le bouton speaker sert de bouton lecture/pause.
				audioPlayer = new AudioPlayer(playerType, audioContainer, playerURL, 200, 40);

				// Initialisation du lecteur audio (piste, boutons de controles, fonctions événementielles)
				audioPlayer.init(audioTrack, audioControls, onAudioCreated, onAudioLoading, onAudioProgress);
				audioPlayer.enableControls(false);
			}


				/* Evénements liés à l'audio */


				// 1 : Le lecteur audio a été créé

				var onAudioCreated = function() {

					console.log('onAudioCreated');

					imageController.startLoading(imageUrl, 1000, onImageLoaded);

					//audioPlayer.setTrack(audioTrack);
				};


				// 2 : La piste audio est en chargement

				var onAudioLoading = function(percent) {

					console.log('onAudioLoading : ' + percent);

					var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
					$('#speaker-loader').css('stroke-dashoffset', offset.toString());

					if (percent == 100) {

						isAudioLoaded = true;

						var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
						$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);

						//audioPlayer.enableControls(true);
						setTimeout(function() { 
							audioPlayer.startPlaying(onAudioStart); 
						}, 1000);
					}
				};


				// 3 : La piste audio est chargée

				var onAudioLoaded = function() {

					console.log('onAudioLoaded');
					// Demande d'affichage de l'image
					//displayImage(1500);
				};


				// 4 : La lecture de la piste audio démarre

				var onAudioStart = function() {

					console.log('onAudioStart');
					audioPlayer.enableControls(true);
				};


				// 5 : La piste audio est en cours de lecture

				var onAudioProgress = function(percent) {

					//console.log('onAudioProgress : ' + percent);
					//console.log(typeof(percent));

					var offset = parseInt($('#speaker-progress').css('stroke-dasharray')) / 100 * (100 - percent);
					$('#speaker-progress').css('stroke-dashoffset', offset.toString());

					if (percent === 100) {

						playerComplete = true;

						var dasharrayValue = parseInt($('#speaker-progress').css('stroke-dasharray'));
						$('#speaker-progress').css('stroke-dashoffset', dasharrayValue);

						if ($('.reponse-qcm') !== null) {

							$(".reponse-qcm").prop("disabled", false);
						}

						if ($('#reponse-champ') !== null) {

							$('#reponse-champ').removeProp('disabled');
							$('#reponse-champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
							$('#reponse-champ').focus();
						}
					}
				};


				// 6 : La lecture de la piste audio est terminée

				var onAudioEnded = function() {

					console.log('onAudioEnded');
				};




			/**********************************************
			*      Fonctions de gestion de la vidéo       *
			**********************************************/

			var createVideo = function() {

				videoCreateTimer = setInterval(onVideoCreateProgress, 100);

				// On récupère l'adresse absolue du lecteur vidéo Flash (pour les navigateurs qui ne supportent pas le HTML5).
				//var videoPlayerUrl = '<?php echo SERVER_URL; ?>media/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf';

				// Puis l'adresse absolue de la vidéo.
				//var videoUrl = '<?php echo SERVER_URL.VIDEO_PATH; ?>' + videoFilename;


				// Prise en compte des évnements par tous les navigateurs
				//var addEvent =  window.attachEvent || window.addEventListener;
				/*	
				var event = window.attachEvent ? 'onclick' : 'click';
				addEvent(event, function(){
				    alert('Hello!')
				});
				*/

				// On génére le lecteur vidéo et on le configure.
				videoPlayer = projekktor('#video', {

						poster: imageUrl,
						title: 'Lecteur vidéo',
						playerFlashMP4: videoPlayerUrl,
						playerFlashMP3: videoPlayerUrl,
						width: 750,
						height: 420,
						controls: true,
						enableFullscreen: false,
						autoplay: false, //true,
						playlist: [{
							0: {src: videoUrl, type: "video/mp4"}
							//0: {src: "../media/projekktor/media/intro.mp4", type: "video/mp4"}
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
							99: 'Cliquez sur le média pour continuer.',
							100: 'Espace réservé.'
						} 

					}, 

					function(player) {

						var stateListener = function(state) {

							console.log('statelistener called');

							switch(state) {
								
								case 'IDLE':
									console.log('IDLE');
									break;

								case 'AWAKENING' :
									console.log('AWAKENING');
									break;

								case 'STARTING':
									console.log('STARTING');
									break;

								case 'PLAYING':
									console.log('PLAYING');
									//onVideoPlaying();
									break;

								case 'PAUSED':
									console.log('PAUSED');
									//onVideoPaused();
									//$('.ppstart').removeClass('inactive');
									//$('.ppstart').addClass('active');
									break;

								case 'STOPPED':
									console.log('STOPPED');
									break;

								case 'COMPLETED':
									console.log('COMPLETED');
									//onVideoEnded();
									//$(".reponse-qcm").prop("disabled", false);
									
									//isVideoComplete = true;

									//checkPlayerComplete();
									break;

								case 'ERROR' :
									console.log('ERROR');
									break;

								case 'DESTROYING' :
									console.log('DESTROYING');
									break;

							}
						};
						player.addListener('state', stateListener);

						
						var errorListener =  function(data) { 

							console.log(data);
							//isVideoComplete = true; 

							//$('#lecteurvideo').html('');

							if (imageActive) {

								//displayImage(imageUrl);
							}                 
						};
						player.addListener('error', errorListener);


						// Temps de chargement
						var progressListener =  function(progress) { 

							console.log('progress : ' + progress);               
						};
						player.addListener('progress', progressListener);
						

						// Temps de lecture
						var timeListener =  function(time) { 

							console.log('time : ' + time);  
						};
						player.addListener('time', timeListener);

						
						// Affichage de l'image ou de la vidéo (si autostart)
						var displayListener =  function(time) { 

							console.log('displayReady');
						};
						player.addListener('displayReady', displayListener);
					}
				);
			};


				/* Evénements liés à la vidéo */


				// ? : Le lecteur vidéo est en création

				var onVideoCreateProgress = function() {

					// Si l'id du player existe, c'est qu'il a été créé.
					if (videoPlayer !== null && videoPlayer !== undefined) {

						// On stoppe le timer.
						clearInterval(videoCreateTimer);
						onVideoCreated();
					}
				};


				// ? : Le lecteur vidéo a été créé

				var onVideoCreated = function() {

					//console.log(videoPlayer);
					console.log('onVideoCreated');
					//$('.projekktor').css('background-color', '#999999');
					//$('.ppdisplay').css('background-color', '#ffffff');
					videoPlayer.setPlay();
				};
				

				// ? : -

				var onVideoLoading = function() {

					console.log('onVideoLoading');
				};


				// ? : -

				var onVideoLoaded = function() {

					console.log('onVideoLoaded');
				};


			// ? : -

			var displayVideo = function() {

				console.log('displayVideo');
			};


				// ? : -

				var onVideoDisplaying = function() {

					console.log('onVideoDisplaying');
				};


				// ? : -

				var onVideoDisplayed = function() {

					console.log('onVideoDisplayed');
				};


			// ? : -

			var startVideo = function() {

				console.log('startVideo');
			};


				// ? : -

				var onVideoStarted = function() {

					console.log('onVideoStarted');
				};


				// ? : -

				var onVideoPlaying = function() {

					console.log('onVideoPlaying');
				};


				// ? : -

				var onVideoPaused = function() {

					console.log('onVideoPaused');
				};


				// ? : -

				var onVideoEnded = function() {

					console.log('onVideoEnded');
				};





			/**********************************************
			*    Détection du(des) média(s) à afficher    *
			**********************************************/

			//console.log(isImageActive, isAudioActive, isVideoActive);

			if (isImageActive && !isVideoActive) {

				createImage();
			}
			else if (isVideoActive) {

				if (!isAudioActive) {

					$('speaker').hide();
				}
				
				$('#visuel').addClass('projekktor')
				createVideo();
			}









			// Le bouton suite est desactiver par défaut.
			$("#submit-suite").prop("disabled", true);
			$("#submit-suite").hide();
			var $suite = $("#btn-suite");
			$("#btn-suite").remove();


			//$('#loader').hide();
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


			


			
			
			


			/*** Events ***/
			
			// Sur click d'un des boutons radio
			$(".reponse-qcm").on("click", function(e) {
				
				if (playerComplete) {
					
					//imageController.fadeToBlank($('#media-question'), 0);
					//imageController.fadeToBlack($('#media-question'), 1500);
					$('#media-question').append($suite);
					$("#submit-suite").hide().fadeIn(1000);
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