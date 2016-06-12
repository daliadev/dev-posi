
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


	var navAgent = new NavigatorAgent();


	// Variables état/statut des médias
	
	// Création d'une variable permettant de savoir si le lecteur video a terminé la lecture du média.
	//var isVideoComplete = false;

	// Etat du chargement de l'image
	//var isImageLoaded = false;

	// Etat du chargement du lecteur audio
	//var isAudioLoaded = false;

	// Etat de lecture du lecteur audio
	var audioIsPlaying = false;


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
	imageUrl = imageFilename;

	// Conteneur et icône animée de chargement de l'image
	imageContainer = $('#visuel');

	if (navAgent.isCSSAnimateSupported()) {
		
		$('.image-loader').hide();
		loader = $('.custom-loader');
	}
	else
	{
		$('.custom-loader').hide();
		loader = $('.image-loader');
	}
	


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
		isAudioActive = false;
		//enableUserResponse();
		//alert('Ce navigateur ne prend pas en charge les médias audio.');
	}

	// URL complète de la piste audio
	audioTrack = audioFilename;

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
	videoUrl = videoFilename;

	// Message de la vidéo alternative
	var question = $('.question').html();
	$('.question').html('<p>Regardez et écoutez attentivement la vidéo, puis répondez à la question.</p>');



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

			

			
			// Si vidéo -> load vidéo
			if (isVideoActive) {

				//loadVideo();
			}
			// Creation du lecteur audio s'il y a une source
			if (isAudioActive) {

				audioPlayer.startLoading(onAudioLoaded);
			}
			else {

				//enableUserResponse();
			}

			loader.fadeOut(1000);

			displayImage(1500);
		};


		// 3 : Affichage de l'image

		var displayImage = function(duration) {

			console.log('displayImage');
			
			imageController.display(duration, onImageDisplayed);
		}


		// 4 : Affichage de l'image terminé

		var onImageDisplayed = function() {

			console.log('onImageDisplayed');

			if (isVideoActive) {

				displayVideo();
			}
			else if (isAudioActive) {

				audioContainer.style.display = 'block';
			}

			if (!isAudioActive && !isVideoActive) {

				controlsEnabled = true;
				enableUserResponse();
			}
		};

		var onImageHidden = function() {

			console.log('onImageHidden');

			/*
			if (isVideoActive) {

				displayVideo();
			}
			else if (isAudioActive) {

				audioContainer.style.display = 'block';
			}

			if (!isAudioActive && !isVideoActive) {

				controlsEnabled = true;
				enableUserResponse();
			}
			*/
		};
		


	/**********************************************
	*      Fonctions de gestion de la vidéo       *
	**********************************************/

	var createVideo = function() {

		console.log('createVideo');

		$('#video').hide();

		videoCreateTimer = setInterval(onVideoCreateProgress, 100);


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
							break;

						case 'STOPPED':
							console.log('STOPPED');
							onVideoStop();
							break;

						case 'COMPLETED':
							console.log('COMPLETED');
							onVideoEnded();
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

			console.log('onVideoCreated');
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

		if (isImageActive)
		{
			imageController.hide(1000, onImageHidden);
		}
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
			$('.question').html(question);
			enableUserResponse();
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

			if (!isImageActive) {

				audioContainer.style.display = 'block';
				audioPlayer.startLoading(onAudioLoaded);
			}
			
			$('#speaker-icon').addClass('fa-refresh fa-spin');
		};


		// 2 : La piste audio est en chargement

		var onAudioLoading = function(percent) {

			console.log('onAudioLoading : ' + percent);

			//var offset = parseInt($('#speaker-loader').css('stroke-dasharray')) / 100 * (100 - percent);
			//$('#speaker-loader').css('stroke-dashoffset', offset.toString());

			if (percent === 100) {

				//var dasharrayValue = parseInt($('#speaker-loader').css('stroke-dasharray'));
				//$('#speaker-loader').css('stroke-dashoffset', dasharrayValue);
			}
		};


		// 3 : La piste audio est chargée

		var onAudioLoaded = function() {

			console.log('onAudioLoaded');

			setTimeout(function() { 
				audioPlayer.startPlaying(onAudioStart); 
			}, 1000);
		};


		// 4 : La lecture de la piste audio démarre

		var onAudioStart = function() {

			console.log('onAudioStart');
			audioPlayer.enableControls(true);

			audioIsPlaying = true;

			// Démarrage ripple effect
			$('.button-icon').addClass('animate');

			// Changement icone play
			$('#speaker-icon').css("margin-left", "0px");
			$('#speaker-icon').removeClass('fa-play').removeClass('fa-refresh fa-spin').addClass('fa-microphone');
		};


		// 5 : La piste audio est en cours de lecture

		var onAudioProgress = function(percent) {

			console.log('onAudioProgress : ' + percent);

			//var offset = parseInt($('#speaker-progress').css('stroke-dasharray')) / 100 * (100 - percent);
			//$('#speaker-progress').css('stroke-dashoffset', offset.toString());

			if (percent === 100) {

			}
		};


		// 6 : La lecture de la piste audio est terminée

		var onAudioEnded = function() {

			console.log('onAudioEnded');

			audioIsPlaying = false;

			// Fin ripple effect
			$('.button-icon').removeClass('animate');

			// Bouton play
			$('#speaker-icon').removeClass('fa-microphone');
			$('#speaker-icon').addClass('fa-play');
			$('#speaker-icon').css("margin-left", "2px");

			if (isVideoActive) {

				displayVideo();
			}
			else {

				controlsEnabled = true;
				enableUserResponse();
			}
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

	loader.hide(); // Le loader est caché par défaut
	


	/******************
	*    Evénements   *
	******************/
	
	// Sur clic du bouton audio
	$('#speaker-button').on('click', function(event) {

		event.preventDefault();

		if (audioPlayer !== null)
		{
			if (audioIsPlaying)
			{
				// Pause
				$('.button-icon').removeClass('animate');
				$('#speaker-icon').removeClass('fa-microphone');
				$('#speaker-icon').addClass('fa-play');
				$('#speaker-icon').css("margin-left", "2px");
				audioIsPlaying = false;
			}
			else
			{
				// Lecture
				$('.button-icon').addClass('animate');
				$('#speaker-icon').removeClass('fa-play');
				$('#speaker-icon').addClass('fa-microphone');
				$('#speaker-icon').css("margin-left", "0px");
				audioIsPlaying = true;
			}
		}
	});

	// Sur click d'un des boutons radio
	$(".reponse-qcm").on("click", function(e) {
		
		if (controlsEnabled) {
			
			$('#media-question').append($suite);
			$("#submit-suite").fadeIn(500);
			$("#submit-suite").prop("disabled", false);
		}
		else {
			
			$(this).attr("checked", false);
		}
	});
	

	// Sur click dans le champ de réponse s'il existe.
	$(".reponse-champ").on("click", function(e) {

		if (!controlsEnabled) {

			$(this).blur();
		}
	});
	

	// Lorsque l'utilisateur effectue une saisie dans le champ de réponse.

	$(".reponse-champ").on("keydown", function(e) {

		// On s'assure que la vidéo ou le son sont terminés
		// et que l'utilisateur a saisi au moins 2 caractères.
		if (controlsEnabled) 
		{
			var $suiteBtn = $("#submit-suite");

			$(this).attr("placeholder", "");

			if ($(this).val().length > 1) {

				if ($suiteBtn != null) {

					$('#media-question').append($suite);
				}

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
			createVideo();
		}
		else if (isAudioActive && isVideoActive) 
		{
			createAudio();
			createVideo();
		}
		else
		{
			$('#speaker').hide();
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
