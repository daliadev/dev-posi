
$(function() {
			
	'use strict';



	/**********************************************
	*   Création - Initialisation des variables   *
	**********************************************/



	//var audioFilename;
	var isAudioActive = true;

	// Variables controleur audio
	var playerType, playerURL;

	// URL complète de la piste audio
	var audioTrack = document.getElementById('audio-filename').value;

	// Conteneur du lecteur audio (caché)
	var audioContainer = document.getElementById('audio');

	var audioControls = [{
		action: 'play',
		item: document.getElementById('speaker-button')
	}];

	var audioPlayer = null;

	var controlsEnabled = false;

	var navAgent = new NavigatorAgent();

	var isPlaying = false;

	

	// Le type et l'URL du lecteur audio dépendent du navigateur
	if (navAgent.isAudioEnabled()) {
		
	 	playerType = 'html';
	 	playerURL = null;
	}
	else if (FlashDetect.installed) {

		playerType = 'dewp-mini';
		playerURL = '<?php echo ROOT; ?>media/dewplayer/';
	}
	else {
		isAudioActive = false;
	}



	// Creation du lecteur audio s'il y a une source
	if (isAudioActive) {

		// Tableau des éléments de contrôle de l'audio (contrôle via le bouton speaker)
		audioControls = [{
			action: 'play',
			item: document.getElementById('speaker-button')
		}];	
	}



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

		audioPlayer.startLoading(onAudioLoaded);

		audioContainer.style.display = 'block';

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

		// Icone chargement
	};


	// 3 : La piste audio est chargée

	var onAudioLoaded = function() {

		console.log('onAudioLoaded');
		
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

		isPlaying = true;

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

			// var dasharrayValue = parseInt($('#speaker-progress').css('stroke-dasharray'));
			// $('#speaker-progress').css('stroke-dashoffset', dasharrayValue);
		}
	};


	// 6 : La lecture de la piste audio est terminée

	var onAudioEnded = function() {

		console.log('onAudioEnded');

		controlsEnabled = true;

		isPlaying = false;

		// Fin ripple effect
		$('.button-icon').removeClass('animate');

		// Bouton play
		$('#speaker-icon').removeClass('fa-microphone');
		$('#speaker-icon').addClass('fa-play');
		$('#speaker-icon').css("margin-left", "2px");
	};






	if (isAudioActive) 
	{   
		createAudio();
	}
	else
	{
		alert('Ce navigateur ne prend pas en charge les médias audio.');
	}




	$('#speaker-button').on('click', function(event) {

		event.preventDefault();

		if (audioPlayer !== null)
		{
			
			if (isPlaying)
			{
				// Pause;
				$('.button-icon').removeClass('animate');
				$('#speaker-icon').removeClass('fa-microphone');
				$('#speaker-icon').addClass('fa-play');
				$('#speaker-icon').css("margin-left", "2px");
				isPlaying = false;
			}
			else
			{
				$('.button-icon').addClass('animate');
				$('#speaker-icon').css("margin-left", "0px");
				$('#speaker-icon').removeClass('fa-play');
				$('#speaker-icon').addClass('fa-microphone');
				// $('#speaker-icon').addClass('fa-play');
				$('#speaker-icon').css("margin-left", "0px");
				isPlaying = true;
			}
			
		}
	});

	// on survol souris => si play -> icone pause, si stop -> icone paly


});