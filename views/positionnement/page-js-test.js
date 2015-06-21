<script type="text/javascript">


		$(function() {
			
			'use strict';



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



			/*** Création des variables ***/


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


			// Variables état/statut des médias




			/*** fonctions de contrôle des médias ***/


			// Fonctions contrôle de l'image


			// Sur création de l'image

			var onImageCreated = function() {

				console.log('onImageCreated');
			};


			// Sur chargement terminé de l'image

			var onImageLoaded = function() {

				console.log('onImageLoaded');

				imageController.display(1500, onImageDisplayed);

				//$('#visuel img').fadeIn(1500);
				//this.fadeToBlack($('body').children().first(), 5000);

				// Creation du lecteur audio s'il y a une source
				if (audioActive) {

					//createAudioPlayer();
					//audioPlayer.setTrack(audioTrack);
				}
				else {

					//$(".reponse-qcm").prop("disabled", false);
				}
			};


			// Sur affichage terminé de l'image

			var onImageDisplayed = function() {

				console.log('onImageDisplayed');
			};



			// Fonctions contrôle du son


			// Sur création de l'audio

			var onAudioCreated = function() {

				console.log('onAudioCreated');

				//audioPlayer.setTrack(audioTrack);

				// Paramétrage et chargement de l'image
				imageController.startLoading(imageUrl, 500, onImageLoaded);
			};


			// Durant le chargement de l'audio

			var onAudioLoading = function(percent) {

				console.log('onAudioLoading : ' + percent);

				var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
				$('#speaker-loader').css('stroke-dashoffset', offset.toString());

				if (percent == 100) {

					isAudioLoaded = true;

					var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
					$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);

					audioPlayer.enableControls(true);
					setTimeout(function() { audioPlayer.startPlaying(); }, 2000);
					//audioPlayer.startPlaying();
				}
			};


			// Sur chargement de l'audio

			var onAudioLoaded = function() {

				console.log('onAudioLoaded');
			};


			// Durant la lecture de la piste audio

			var onAudioProgress = function(percent) {

				console.log('onAudioProgress : ' + percent);

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


			// Sur fin lecture de la piste audio

			var onAudioEnded = function() {

				console.log('onAudioEnded');
			};





			/*** Initialisation des variables / instanciation des objets ***/


			// Récupération des noms des différents médias dans les valeurs assignées aux champs cachés du formulaire.
			imageFilename = $('#image-filename').val();
			audioFilename = $('#audio-filename').val();
			videoFilename = $('#video-filename').val();

			// Si le média possède un nom, une variable correspondant à ce média contient la valeur "vraie".
			isImageActive = imageFilename !== '' ? true : false;
			videoActive = videoFilename !== '' ? true : false;
			audioActive = audioFilename !== '' ? true : false;


			/*
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
			*/



			// Contrôle de l'image

			// URL complète de l'image'
			imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;

			// Conteneur et icône animée de chargement de l'image
			imageContainer = $('#visuel');
			imageLoader = $('#loader');

			// Instanciation de l'objet ImageController que gére le chargement et l'affichage de l'image
			imageController = new ImageController(imageContainer, imageLoader);


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

			// Instanciation de l'objet AudioPlayer que gére et contrôle le son
			// Le player proprement dit est caché et le bouton speaker sert de bouton lecture/pause.
			audioPlayer = new AudioPlayer(playerType, audioContainer, playerURL, 200, 40);

			// Initialisation du lecteur audio
			audioPlayer.init(audioTrack, audioControls, onAudioCreated, onAudioLoading, onAudioProgress);

			audioPlayer.enableControls(false);


			





			// Le bouton suite est desactiver par défaut.
			$("#submit-suite").prop("disabled", true);
			$("#submit-suite").hide();
			var $suite = $("#btn-suite");
			$("#btn-suite").remove();


			$('#loader').hide();
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


			


			// Contenu du lecteur audio
			//var audioHtml = '';

			


			/* Création / Gestion des médias */

			



			// Evenements médias
			/*
			function onAudioCreated() {

				
			}
			*/
			/*
			function onAudioLoading(percent) {

				var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
				$('#speaker-loader').css('stroke-dashoffset', offset.toString());

				if (percent == 100) {

					isAudioLoaded = true;

					var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
					$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);

					audioPlayer.enableControls(true);
					setTimeout(function() { audioPlayer.startPlaying(); }, 2000);
					//audioPlayer.startPlaying();
				}
			}
			*/
			/*
			function onAudioProgress(percent) {

				//console.log(percent);
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
			}
			*/

			// Fonction appelée par l'objet imageController lorsque l'image est chargée.
			/*
			function onImageLoaded() {

				console.log('loader fade out');
				imageController.display(1500, onImageDisplayed);

				//$('#visuel img').fadeIn(1500);
				//this.fadeToBlack($('body').children().first(), 5000);

				// Creation du lecteur audio s'il y a une source
				if (audioActive) {

					//createAudioPlayer();
					//audioPlayer.setTrack(audioTrack);
				}
				else {

					//$(".reponse-qcm").prop("disabled", false);
				}
			}
			*/
			/*
			function onImageDisplayed() {

				console.log('image displayed');
			}
			*/

			// Paramétrage et chargement de l'image
			//imageController.startLoading(imageUrl, 500, onImageLoaded);


			// Initialisation du lecteur audio
			//audioPlayer.init(audioTrack, audioControls, onAudioCreated, onAudioLoading, onAudioProgress);

			//audioPlayer.enableControls(false);
			
			


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


			/* Création et instanciation des médias */

			// Chargement de l'image (si il n'y a pas de vidéo)
			
			if (imageActive) {
				/*
				var imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;
				
				if (!videoActive) {

					//displayImage(imageUrl);
				} 
				*/ 


				//création de l'image 
				//imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;
				//imageController = new imageController($('#visuel'), $('#loader'));          
			}


			
			// S'il existe une video on créé le lecteur vidéo, le lecteur audio ne doit pas être créé.
			if (videoActive) {
				
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
							99: 'Cliquez sur le média pour continuer.',
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