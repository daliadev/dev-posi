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
					<!-- <i class="fa fa-file-text-o"></i> -->
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
				
				<!-- Old -->
				<!-- 
				<div id="image-content-appli">

				<?php //if (!empty($videoFile)) : ?>

					<div id="lecteurvideo" class="projekktor"></div>

				<?php //endif; ?>
				
				<?php //if (!empty($imageFile)) : ?>
					
					<div class="image-loader"></div>

				<?php //endif; ?>
				
				</div> -->


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

						<!-- <svg id="svg-loader" version="1.1" viewBox="0 0 50 50" preserveAspectRatio="xMinYMin meet"> -->
							<!-- <circle id="loader-bg" r="25" transform="translate(25, 25) rotate(-90)"></circle> -->
							<!-- <circle id="loader-bg" r="24" transform="translate(26.5, 26) rotate(-90)"></circle> -->
						<!-- </svg> -->
					</div>


					
						<?php if (!empty($audioFile)) : ?>
						
						<!-- 
						<button type="button" id="speaker">
							<i class="fa fa-volume-up"></i>
						</button> -->
						<!-- <div id="controls-bg"></div> -->


						<div id="speaker">

							
							<svg id="svg-speaker-bg" version="1.1" viewBox="0 0 52 52" preserveAspectRatio="xMinYMin meet">
								<circle id="speaker-bg" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
							</svg>

							<button type="button" id="speaker-button">
								<!-- <i class="fa fa-volume-up"></i> -->
								<!-- <img src="<?php //echo SERVER_URL ?>media/images/ic_volume_up_white_24dp.png" /> -->
								<!-- <svg class="speaker-svg" version="1.1" viewBox="0 0 24 24" preserveAspectRatio="xMinYMin meet">
									<path d="M6 18v12h8l10 10V8L14 18H6zm27 6c0-3.53-2.04-6.58-5-8.05v16.11c2.96-1.48 5-4.53 5-8.06zM28 6.46v4.13c5.78 1.72 10 7.07 10 13.41s-4.22 11.69-10 13.41v4.13c8.01-1.82 14-8.97 14-17.54S36.01 8.28 28 6.46z"/>
								</svg> -->

								<svg version="1.1" class="speaker-svg" id="speaker-svg" viewBox="0 0 40 40" preserveAspectRatio="xMinYMin meet">
									<path d="M23.04,12.754v21.857c0,0.349-0.116,0.65-0.348,0.904s-0.506,0.381-0.822,0.381c-0.317,0-0.592-0.127-0.823-0.381
										l-6.087-6.689h-4.79c-0.317,0-0.591-0.127-0.823-0.383C9.116,28.189,9,27.889,9,27.54v-7.714c0-0.348,0.116-0.649,0.347-0.904
										s0.506-0.382,0.823-0.382h4.79l6.087-6.69c0.231-0.254,0.506-0.381,0.823-0.381c0.316,0,0.591,0.127,0.822,0.381
										C22.924,12.105,23.04,12.406,23.04,12.754z M29.283,20.83c0.518,0.884,0.776,1.835,0.776,2.853s-0.259,1.965-0.776,2.842
										c-0.519,0.877-1.204,1.504-2.057,1.879c-0.122,0.066-0.274,0.1-0.457,0.1c-0.317,0-0.592-0.123-0.823-0.371S25.6,27.58,25.6,27.219
										c0-0.281,0.073-0.52,0.219-0.713c0.146-0.194,0.323-0.361,0.53-0.502s0.414-0.295,0.622-0.463c0.207-0.167,0.384-0.404,0.53-0.713
										c0.146-0.309,0.219-0.69,0.219-1.145c0-0.456-0.073-0.837-0.219-1.146c-0.146-0.308-0.323-0.545-0.53-0.713
										c-0.208-0.167-0.415-0.321-0.622-0.462s-0.384-0.308-0.53-0.502c-0.146-0.194-0.219-0.432-0.219-0.713
										c0-0.361,0.115-0.666,0.347-0.914c0.231-0.248,0.506-0.372,0.823-0.372c0.183,0,0.335,0.034,0.457,0.101
										C28.079,19.324,28.765,19.946,29.283,20.83z M33.187,18.008c1.035,1.734,1.554,3.626,1.554,5.675s-0.519,3.941-1.554,5.674
										c-1.036,1.735-2.407,2.998-4.113,3.787c-0.159,0.067-0.312,0.102-0.457,0.102c-0.329,0-0.61-0.128-0.842-0.383
										c-0.231-0.254-0.347-0.555-0.347-0.904c0-0.521,0.237-0.916,0.713-1.185c0.683-0.388,1.146-0.683,1.39-0.884
										c0.901-0.723,1.605-1.631,2.111-2.723c0.505-1.091,0.759-2.253,0.759-3.485c0-1.232-0.254-2.394-0.759-3.486
										c-0.506-1.091-1.21-1.999-2.111-2.722c-0.244-0.201-0.707-0.495-1.39-0.884c-0.476-0.268-0.713-0.663-0.713-1.185
										c0-0.348,0.115-0.649,0.347-0.904c0.231-0.254,0.506-0.381,0.823-0.381c0.158,0,0.316,0.033,0.476,0.1
										C30.779,15.011,32.15,16.273,33.187,18.008z M37.099,15.195c1.547,2.578,2.321,5.407,2.321,8.488s-0.774,5.91-2.321,8.487
										c-1.548,2.579-3.608,4.477-6.18,5.696c-0.158,0.066-0.316,0.101-0.475,0.101c-0.317,0-0.592-0.128-0.823-0.382
										c-0.231-0.255-0.348-0.556-0.348-0.904c0-0.481,0.238-0.877,0.714-1.185c0.085-0.055,0.222-0.125,0.411-0.211
										c0.188-0.088,0.326-0.158,0.411-0.211c0.561-0.336,1.061-0.677,1.499-1.025c1.499-1.219,2.669-2.738,3.51-4.561
										c0.841-1.82,1.262-3.756,1.262-5.805s-0.421-3.984-1.262-5.806c-0.841-1.821-2.011-3.341-3.51-4.56
										c-0.438-0.348-0.938-0.69-1.499-1.024c-0.085-0.054-0.223-0.124-0.411-0.211c-0.189-0.087-0.326-0.158-0.411-0.211
										c-0.476-0.308-0.714-0.703-0.714-1.186c0-0.348,0.116-0.649,0.348-0.904s0.506-0.382,0.823-0.382c0.158,0,0.316,0.034,0.475,0.101
										C33.49,10.719,35.551,12.617,37.099,15.195z"/>
								</svg>

							</button>

							<svg id="svg-speaker-progress" version="1.1" viewBox="0 0 52 52" preserveAspectRatio="xMinYMin meet">
								<circle id="speaker-loader" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
								<circle id="speaker-progress" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
							</svg>

						</div>

						<?php endif; ?>

					<?php endif; ?>

					<!-- <div id="black-bg"></div> -->

					<div id="btn-suite">
						<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
					</div>
				
				</div>

				<!-- <div class="media-display" id="media-question"> -->
					<!-- <div class="btn-suite">
						<input type="submit" class="button-primary" id="submit-suite" name="submit_suite" value="Suite" />
					</div>
				</div> -->


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
			var imageContainer, loader; // Type JQuery element
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

			var controlsEnabled = false;



			// Variables état/statut des médias
			
			// Création d'une variable permettant de savoir si le lecteur video a terminé la lecture du média.
			//var isVideoComplete = false;

			// Etat du chargement de l'image
			//var isImageLoaded = false;

			// Etat du chargement du lecteur audio
			//var isAudioLoaded = false;

			// Etat de lecture du lecteur audio
			//var audioIsPlaying = false;


			// Timer du chargement de l'image
			//var timerImage = null;

			// Timer de selection automatique du champ de saisie
			//var timerPlayerComplete = null;

			//var playerComplete = false;
			


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


			if (navAgent.isCSSAnimateSupported()) {
				
				$('.image-loader').css('display', 'none');
				loader = $('.custom-loader');
			}
			else
			{
				$('.custom-loader').hide();
				loader = $('.image-loader');
			}
			//loader = $('#loader');
			loader.hide(); // Le loader est caché par défaut


			// Contrôle du son

			// Le type et l'URL du lecteur audio dépendent du navigateur
			if (navAgent.isAudioEnabled()) {
				
			 	playerType = 'html';
			 	playerURL = null;
			}
			else if (FlashDetect.installed) {

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
				loader.fadeIn(1000);

				imageController = new ImageController(imageContainer, loader, null);

				imageController.startLoading(imageUrl, 1000, onImageLoaded);
			}


				/* Evénements liés à l'image */


				// 1 : L'image est en cours de chargement
				
				var onImageLoading = function() {

					//console.log('onImageLoading');
				}
				

				// 2 : L'image est chargée

				var onImageLoaded = function() {

					console.log('onImageLoaded');

					

					// Creation du lecteur audio s'il y a une source
					if (isAudioActive) {

						//audioContainer.style.display = 'block';

						audioPlayer.startLoading(onAudioLoaded);
					}
					// Sinon si vidéo -> load vidéo
					else if (isVideoActive) {

						//loadVideo();
						displayVideo();
					}
					else {

						//$(".reponse-qcm").prop("disabled", false);
					}

					loader.fadeOut(1000);

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
					else if (isVideoActive) {

						//loadVideo();
						displayVideo();
					}

					if (!isAudioActive && !isVideoActive) {

						controlsEnabled = true;
						enableUserResponse();
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
				audioPlayer.init(audioTrack, audioControls, onAudioCreated, onAudioLoading, onAudioProgress, onAudioEnded);
				audioPlayer.enableControls(false);
			}


				/* Evénements liés à l'audio */


				// 1 : Le lecteur audio a été créé

				var onAudioCreated = function() {


					console.log('onAudioCreated');

					//audioPlayer.setTrack(audioTrack);
					//$('#controls-bg').show(1000);

					if (!isImageActive) {

						audioPlayer.startLoading(onAudioLoaded);
					}
				};


				// 2 : La piste audio est en chargement

				var onAudioLoading = function(percent) {

					console.log('onAudioLoading : ' + percent);

					// var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
					// $('#speaker-loader').css('stroke-dashoffset', offset.toString());
					/*
					if (percent == 100) {

						isAudioLoaded = true;

						var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
						$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);

						//audioPlayer.enableControls(true);
						setTimeout(function() { 
							audioPlayer.startPlaying(onAudioStart); 
						}, 1000);
					}
					*/

					var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
					$('#speaker-loader').css('stroke-dashoffset', offset.toString());

					if (percent === 100) {

						//var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
						//$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);
					}
				};


				// 3 : La piste audio est chargée

				var onAudioLoaded = function() {

					console.log('onAudioLoaded');
					// Demande d'affichage de l'image
					//displayImage(1500);

					//isAudioLoaded = true;

					//var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
					//$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);

					//audioPlayer.enableControls(true);
					setTimeout(function() { 
						audioPlayer.startPlaying(onAudioStart); 
					}, 1000);
				};


				// 4 : La lecture de la piste audio démarre

				var onAudioStart = function() {

					console.log('onAudioStart');
					audioPlayer.enableControls(true);
				};


				// 5 : La piste audio est en cours de lecture

				var onAudioProgress = function(percent) {

					console.log('onAudioProgress : ' + percent);
					//console.log(typeof(percent));

					var offset = parseInt($('#speaker-progress').css('stroke-dasharray')) / 100 * (100 - percent);
					$('#speaker-progress').css('stroke-dashoffset', offset.toString());

					if (percent === 100) {

						// var dasharrayValue = parseInt($('#speaker-progress').css('stroke-dasharray'));
						// $('#speaker-progress').css('stroke-dashoffset', dasharrayValue);
						/*
						if ($('.reponse-qcm') !== null) {

							$(".reponse-qcm").prop("disabled", false);
						}

						if ($('#reponse-champ') !== null) {

							$('#reponse-champ').removeProp('disabled');
							$('#reponse-champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
							$('#reponse-champ').focus();
						}
						*/
					}
				};


				// 6 : La lecture de la piste audio est terminée

				var onAudioEnded = function() {

					console.log('onAudioEnded');

					if (isVideoActive) {

						//loadVideo
						displayVideo();
					}
					else {

						controlsEnabled = true;
						enableUserResponse();
					}
				};




			/**********************************************
			*      Fonctions de gestion de la vidéo       *
			**********************************************/

			var createVideo = function() {

				console.log('createVideo');

				$('#video').hide();

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

						fixedVolume: true,
						volume: 1,
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
						//plugins: ['display', 'controlbar'],
						plugins: ['display'],
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
									onVideoStarted();
									break;

								case 'PLAYING':
									console.log('PLAYING');
									onVideoPlaying();
									break;

								case 'PAUSED':
									console.log('PAUSED');
									onVideoPaused();
									//$('.ppstart').removeClass('inactive');
									//$('.ppstart').addClass('active');
									break;

								case 'STOPPED':
									console.log('STOPPED');
									onVideoStop();
									break;

								case 'COMPLETED':
									console.log('COMPLETED');
									onVideoEnded();
									//isVideoComplete = true;
									//checkPlayerComplete();
									break;

								case 'ERROR' :
									onVideoError();
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

							console.log('video progress : ' + progress);               
						};
						player.addListener('progress', progressListener);
						

						// Temps de lecture
						var timeListener =  function(time) { 

							console.log('video time : ' + time);  
						};
						player.addListener('time', timeListener);

						
						// Affichage de l'image ou de la vidéo (si autostart)
						var displayListener =  function(time) { 

							console.log('video displayReady');
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
					//videoPlayer.setVolume(0);
					//videoPlayer.setPlay();
				};
				

				// ? : -
				/*
				var onVideoLoading = function() {

					console.log('onVideoLoading');
				};


				// ? : -

				var onVideoLoaded = function() {

					console.log('onVideoLoaded');
				};
				*/

			// ? : -

			var displayVideo = function() {

				console.log('displayVideo');
				$('#video').show();
			};


				// ? : -

				var onVideoDisplaying = function() {

					console.log('onVideoDisplaying');
				};


				// ? : -

				var onVideoDisplayed = function() {

					console.log('onVideoDisplayed');
					startvideo();
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
					controlsEnabled = true;
					enableUserResponse();
				};



			/**********************************************************
			*      Fonctions d'autorisation de saisie utilisateur     *
			**********************************************************/

			var enableUserResponse = function() {

				console.log('enableUserResponse');

				if ($('.radio-group') !== null) 
				{
					$(".reponse-qcm").prop("disabled", false);
				}

				if ($('.reponse-champ') != null) 
				{
					$('.reponse-champ').removeProp('disabled');
					$('.reponse-champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
					$('.reponse-champ').focus();
				}
			}

			var disableUserResponse = function() {

				if ($('.radio-group') !== null) {

					// S'il y a des boutons radio, on les désactive.
					$(".reponse-qcm").prop("disabled", true);
				}

				if ($('.reponse-champ') !== null) {

					// S'il y a un champ de réponse, on le désactive et on met un placeholder.
					$(".reponse-champ").prop("disabled", true);
					$(".reponse-champ").attr("placeholder", "Veuillez attendre que le son se termine...");
				}
			}



			 

			/*********************************************
			*    Mise en place des éléments de départ    *
			*********************************************/


			// Désactivation des éléments de réponse
			disableUserResponse();
			
			// Le bouton suite est desactivé et masqué par défaut.
			$("#submit-suite").prop("disabled", true);
			$("#submit-suite").hide();
			var $suite = $("#btn-suite");
			$("#btn-suite").remove();

			//$('#loader').hide();
			// Le barre du lecteur audio est cachée.
			//$("#audio").hide();

			// Le haut-parleur est également caché le temps du chargement du son
			//$(".speaker").prop('disabled', true);

			$('#controls-bg').hide();
		
			


			/******************
			*    Evénements   *
			******************/
			
			// Sur click d'un des boutons radio
			$(".reponse-qcm").on("click", function(e) {
				
				if (controlsEnabled) {
					
					//imageController.fadeToBlank($('#media-question'), 0);
					//imageController.fadeToBlack($('#media-question'), 1500);
					$('#media-question').append($suite);
					$("#submit-suite").fadeIn(500);
					//$("#submit-suite").show(500);
					$("#submit-suite").prop("disabled", false);
				}
				else {
					
					$(this).attr("checked", false);
				}
			});
			

			// Sur click dans le champ de réponse s'il existe.
			$(".reponse-champ").on("click", function(e) {

				if (controlsEnabled) {

					//$('#media-question').append($suite);
					//$("#submit-suite").hide().fadeIn(1000);
					//$("#submit-suite").show(500);
					//$("#submit-suite").prop("disabled", false);

					//$(this).removeProp("readonly");
					//$(this).prop("placeholder", "Vous pouvez écrire votre réponse.");
				}
				else {

					$(this).blur();
				}
			});
			

			// Lorsque l'utilisateur effectue une saisie dans le champ de réponse.

			$(".reponse-champ").on("keydown", function(e) {

				// On s'assure que la vidéo ou le son sont terminés
				// et que l'utilisateur a saisi au moins 2 caractères.
				/*
				if (controlsEnabled) {

					$(this).attr("placeholder", "");
					$('#media-question').append($suite);

					if ($(this).val().length > 1) {

						$("#submit-suite").hide().fadeIn(1000);
						//$("#submit-suite").show(500);
						$("#submit-suite").prop("disabled", false);
					}
					else if ($(this).val().length <= 1) {

						$("#submit-suite").prop("disabled", true);
					}
				}
				*/

				// On s'assure que la vidéo ou le son sont terminés
				// et que l'utilisateur a saisi au moins 2 caractères.
				if (controlsEnabled) 
				{
					var $suiteBtn = $("#submit-suite");

					$(this).attr("placeholder", "");
					//$(this).removeProp("placeholder");


					if ($(this).val().length > 1) {

						if ($suiteBtn != null) {

							$('#media-question').append($suite);
						}

						//$suiteBtn.removeProp("disabled");
						$suiteBtn.prop("disabled", false);

						if ($suiteBtn.css('display') == 'none') {

							$suiteBtn.fadeIn(500);
						}
					}
					else if ($(this).val().length <= 1) {

						$suiteBtn.prop("disabled", true);

						if ($suiteBtn.css('display') == 'block' || $suiteBtn.css('display') == 'inline') {

							$suiteBtn.fadeOut(500);
						}
					}
				}
			});



			/***********************************************************
			*    Dispatcher : Détection du(des) média(s) à afficher    *
			***********************************************************/


			if (isImageActive) 
			{
				createImage();

				if (isAudioActive && !isVideoActive) 
				{   
					createAudio();
				}
				else if (!isAudioActive && isVideoActive) 
				{
					//$('speaker').hide();
					createVideo();
				}
				else if (isAudioActive && isVideoActive) 
				{
					createAudio();
					createVideo();
				}
			}
			else if (isAudioActive)
			{
				createAudio();

				if (isVideoActive) 
				{
					createVideo();
				}
			}
			else if (isVideoActive) 
			{
				createVideo();
				displayVideo();
			}
			
		});



	</script>