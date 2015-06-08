
var AudioPlayer = function(audioTracks, playertype, playerContainer, playerURL, width, height) {

	/// <summary>La classe audio</summary>
	/// <param name="audioTracks" type="Array">Un tableau des pistes audios sous forme d'url pointant vers le médio audio.</param>
	/// <param name="playertype" type="string">Le type de lecteur ('html' = natif, 'dewp' et 'dewp-mini' pour le lecteur flash).</param>
	/// <param name="playerContainer" type="HTMLelement">Le conteneur ou sera intégré le lecteur.</param>
	/// <param name="playerURL" type="string">Url du lecteur si différent du lecteur HTML natif (lecteur flash dewplayer).</param>
	/// <param name="width" type="Number">La largeur du lecteur.</param>
	/// <param name="height" type="Number">La hauteur du lecteur.</param>

	var player = null;
	var playerType = playertype;
	var container = playerContainer;


	var playerWidth = width;
	var playerHeight = height;

	var controllers = {
		start: null,
		pause: null,
		stop: null,
		vol: null,
		mute: null,
		progress: null
	};

	var audioSources = null;


	var isLoaded = false;
	var isPlaying = false;
	var isEnded = false;

	var loadingTimer = null;
	var progressTimer = null;

	var loadCallBack = null;
	var progressCallBack = null;



	/*** Private methods ***/

	var create = function(tracks) {

		audioSources = attachAudioTracks(tracks);

		if (playerType === 'html') {


			//var playerHTML = '<audio id="audioplayer" name="audioplayer"></audio>';
			var playerHTML = document.createElement('audio');
			playerHTML.id = 'audioplayer';
			playerHTML.name = 'audioplayer';
			playerHTML.setAttribute('preload', 'none');
			playerHTML.setAttribute('volume', '1');
			playerHTML.width = 200;
			playerHTML.height = 40;

			for (var i = 0, count = audioSources.length; i < count; i++) {

				playerHTML.appendChild(audioSources[i]);
			};
			
			container.appendChild(playerHTML);
		}
		else if (playerType === 'dewp' || playerType === 'dewp-mini') {
			
			var dewpType = null;

			switch(playerType) {

				case 'dewp' :
					dewpType = playerURL + 'dewplayer.swf';
					break;

				case 'dewp-mini' :
					dewpType = playerURL + 'dewplayer-mini.swf';
					break;

				default :
					break;
			}

			if (dewpType !== null) {

				var tempContent = document.createElement('div');
				tempContent.id = 'dewp-content';
				container.appendChild(tempContent);

				var flashvars = {
					mp3: audioSources.join('|'), //ex : 'mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3'
					javascript: 'on',
					autostart: 1,
					nopointer: 1
				};
				var params = {
					wmode: 'transparent'
				};
				var attributes = {
					id: 'audioplayer'
				};

				swfobject.embedSWF(dewpType, 'dewp-content', playerWidth, playerHeight, '9.0.0', false, flashvars, params, attributes);
			}
			
			/*
			this.player = '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
			this.player += '<param name="movie" value="' + playerAudioUrl + '" />'; 
			this.player += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
			this.player += '<param name="wmode" value="transparent" />';
			this.player += '</object>';
			*/
		}
		else {

			console.log('AudioPlayer : Player non spécifié ou inexistant.');
		}
	}



	var attachAudioTracks = function(audioSrc) {

		/// <summary>Permet de formater les données audios pour le player voulu.</summary>
		/// <param name="audioSrc" type="Array">Un tableau des données sous forme d'url.</param>
		/// <returns type="Array">Un tableau contenant les pistes audios au format du lecteur audio.</returns>

		var srcArray = new Array();

		if (typeof audioSrc !== 'array') {

			if (typeof audioSrc === 'string') {

				var srcString = audioSrc;
				audioSrc = [srcString];
			}
			else {

				// Format de données incorrectes
				console.log('AudioPlayer : Invalid audio sources.');
			}
		}

		for (var i = 0, count = audioSrc.length; i < count; i++) {

			// Pour un lecteur HTML, les données du tableau sont rassemblées avec des balises <sources> et restituées sous forme d'HTMLElement.
			if (playerType === 'html') {

					var source = document.createElement('source');
					source.src = audioSrc[i];
					source.setAttribute('type', 'audio/mp3');
					srcArray[i] = source;
				
			}
			// Pour le lecteur Dewplayer, les données du tableau sont préfixées par 'mp3/'.
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {
				
				srcArray[i] = 'mp3/' + audioSrc[i];
			}
		}

		// Retour des sources formattées selon le type de lecteur.
		return srcArray;
	};



	var attachControls = function() {

		var self = this;
		
		if (controllers.play !== undefined && controllers.play !== null) {

			if (controllers.pause === undefined || controllers.pause === null) {

				controllers.play.onclick = function(event) {

					if (!isPlaying) {

						self.play();
					}
					else {

						self.pause();
					}
				};
			}
			else {

				controllers.play.onclick = function(event) {

					self.play();
				};

				controllers.pause.onclick = function(event) {

					self.pause();
				};
			}
		}
	};



	var attachEvents = function() {

	};



	var updateLoading = function() {

		if (player !== null) {

			var duration = player.duration;
			console.log('duration :' + duration);
			var bufferedEnd = player.buffered.length > 0 ? player.buffered.end(0) : 0;
			var percentLoaded = bufferedEnd !== 0 ? (bufferedEnd / duration) * 100 : 100;
			console.log('percentLoaded :' + percentLoaded);

			if (loadCallBack !== null) {

				loadCallBack.call(this, percentLoaded);
			}

			if (percentLoaded == 100) {

				clearInterval(loadingTimer);
			}
		}
		else {

			console.log('Player not found or not ready')
		}
	};


	var updateProgress = function() {

		if (player !== null) {

			var duration = player.duration; // Duree totale
			var currenttime = player.currentTime; // Temps écoulé
			
			var percent = (currenttime / duration) * 100;
			console.log('percent : ' + percent);
			//var roundPercent = Math.round(percent);
			//console.log('roundPercent : ' + roundPercent);

			if (progressCallBack !== null) {

				progressCallBack.call(this, percent);
			}

			if (player.ended || percent == 100) {

				clearInterval(progressTimer);
				
				isPlaying = false;
				/*
				//if (completeCallBack !== null && !isEnded) {
				if (completeCallBack !== null) {

					//isEnded = true;
					completeCallBack.call(this);
				}
				*/
			}
		}
	};



	create(audioTracks);






	/*** Public methods ***/


	this.setPlayerType = function(type) {

		playerType = type;
	};

	this.getPlayerType = function() {

		return (playerType);
	};

	this.addTracks = function(tracks) {

		//playerType = type;
	};

	/*
	this.setLoadCallBack = function(callBack) {

		loadCallBack = callBack;
	};

	this.setProgressCallBack = function(callBack) {

		progressCallBack = callBack;
	};
	*/
	/*
	this.setCompleteCallBack = function(callBack) {

		completeCallBack = callBack;
	};
	*/

	
	//this.init = function(playertype, content, playerURL, size) {
	this.init = function(controls, onLoadCallback, onProgressCallback) {

		//playerType = playertype;

		//container = content;

		//var width = size.w ? size.w : 160;
		//var height = size.h ? size.h : 20;

		/*
		controllers = {
			start: null,
			pause: null,
			stop: null,
			vol: null,
			mute: null,
			progress: null
		};
		*/
		/*
		if (playerType === 'html') {

			var playerHTML = '<audio id="audioplayer" name="audioplayer"></audio>';
			container.append(playerHTML);
			
			var tracks = getTracks(audioTracks).split('|');
			for (var i = 0, count = tracks.length; i < count; i++) {

				var src = tracks[i].replace('mp3/', '');
				var sourceHTML = '<source src="' + src + '"></source>';
				$('#audioplayer').append(sourceHTML);
			}

			$('#audioplayer').attr('volume', '1');
			$('#audioplayer').attr('preload', 'auto');
			$('#audioplayer').css('width', width);
			$('#audioplayer').css('height', height);
		}
		else if (playerType === 'dewp' || playerType === 'dewp-mini') {
			
			var dewpType = null;

			switch(playerType) {

				case 'dewp' :
					dewpType = playerURL + 'dewplayer.swf';
					break;

				case 'dewp-mini' :
					dewpType = playerURL + 'dewplayer-mini.swf';
					break;

				default :
					break;
			}

			if (playerURL !== null) {

				container.append('<div id="dewp-content"></div>');

				var flashvars = {
					mp3: getTracks(audioTracks), //ex : 'mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3'
					javascript: 'on',
					autostart: 1,
					nopointer: 1
				};
				var params = {
					wmode: 'transparent'
				};
				var attributes = {
					id: 'audioplayer'
				};

				swfobject.embedSWF(dewpType, 'dewp-content', width.toString(), height.toString(), '9.0.0', false, flashvars, params, attributes);
			}
			
		}
		else {

			console.log('AudioPlayer : Player non spécifié ou inexistant.');
		}
		*/

		loadCallBack = onLoadCallback;
		progressCallBack = onProgressCallback;

		player = document.getElementById('audioplayer');


		if (player !== null  && player !== undefined) {

			if (typeof controls === 'array') {

				for (var i = 0, count = controls.length; i < count; i++) {

					if (controls[i].item !== undefined && controls[i].item !== null) {

						switch(controls[i].action) {

							case 'play':
								controllers.play = controls[i].item;
								break;

							case 'pause':
								controllers.pause = controls[i].item;
								break;

							case 'stop':
								controllers.stop = controls[i].item;
								break;

							case 'volUp':
								controllers.volUp = controls[i].item;
								break;

							case 'volDown':
								controllers.volDown = controls[i].item;
								break;

							case 'mute':
								controllers.mute = controls[i].item;
								break;

							case 'progress':
								controllers.progress = controls[i].item;
								break;

							default:
								break;
						}
					}
				}

				attachControls();

			}
			else if (typeof controls === 'string' && controls == 'on') {

				if (player !== null && player !== undefined) {

					if (playerType === 'html') {

						player.setAttribute('controls', true);
					}
					else if (playerType === 'dewp' || playerType === 'dewp-mini') {

			  			
					}
				}
			}
		}

		player.setAttribute('preload', 'auto');

		
		player.onloadstart = function() {

			console.log('start loading');
			loadingTimer = setInterval(updateLoading, 40);
		};
		

		/*
		player.onloadeddata = function() {

			clearInterval(loadingTimer);

			var percentLoaded = 100;

			if (loadCallBack !== null) {

				loadCallBack.call(this, percentLoaded);
			}
		};
		*/
	};


	this.load = function() {

		

		//player.load();

		//loadingTimer = setInterval(updateLoading, 40);

	}

	this.show = function(duration) {

	};

	this.hide = function(duration) {

	};


	this.enableControls = function(enabled) {

		/*
		for (var i in controls) {

			// console.log(controls[i]);

			if (!enabled) {

				controls[i].prop('disabled', true);
			}
			else if (enabled) {

				controls[i].removeProp('disabled');
			}
			
		}
		*/
	};


	/*
	this.startPlaying = function() {

	};
	*/

	this.play = function() {

		if (player !== null && !isPlaying) {

			if (this.playerType === 'html') {

				if (isEnded) {

					isEnded = false;
				}

				player.play();

				progressTimer = setInterval(updateProgress, 100);
			}
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {

	  			player.dewplay();
			}

			isPlaying = true;
		}
	};


	this.pause = function() {

		if (player !== null && isPlaying) {

			if (this.playerType === 'html') {

				player.pause();
			}
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {

	  			player.dewpause();
			}

			isPlaying = false;
		}
	};


	this.stop = function() {

		if (player !== null && isPlaying) {

			if (this.playerType === 'html') {

				player.pause();
				player.currentTime = 0;
			}
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {

	  			player.dewstop();
			}

			isPlaying = false;
		}
	};

	
};