
var AudioPlayer = function(playertype, playerContainer, playerURL, width, height) {

	/// <summary>La classe audio</summary>
	/// <param name="playertype" type="string">Le type de lecteur ('html' = natif, 'dewp' et 'dewp-mini' pour le lecteur flash).</param>
	/// <param name="playerContainer" type="HTMLelement">Le conteneur ou sera intégré le lecteur.</param>
	/// <param name="playerURL" type="string">Url du lecteur si différent du lecteur HTML natif (lecteur flash dewplayer).</param>
	/// <param name="width" type="Number">La largeur du lecteur.</param>
	/// <param name="height" type="Number">La hauteur du lecteur.</param>


	var self = this;
	
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

	
	var isPlaying = false;
	var isEnded = false;

	var createTimer = null;
	var loadingTimer = null;
	var progressTimer = null;

	var createdCallback = null
	var loadCallBack = null;
	var progressCallBack = null;




	if (playerType === 'html') {

		var playerHTML = document.createElement('audio');
		playerHTML.id = 'audioplayer';
		playerHTML.setAttribute('name', 'audioplayer');
		playerHTML.setAttribute('preload', 'auto');
		playerHTML.setAttribute('volume', 1);
		// playerHTML.setAttribute('width', playerWidth);
		// playerHTML.setAttribute('height', playerHeight);
		
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
				//mp3: audioSources.join('|'), //ex : 'mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3'
				javascript: 'on',
				//autostart: 1,
				nopointer: 1
			};
			var params = {
				wmode: 'transparent'
			};
			var attributes = {
				id: 'audioplayer'
			};

			swfobject.embedSWF(dewpType, 'dewp-content', playerWidth, playerHeight, '9.0.0', false, flashvars, params, attributes);

			/*
			this.player = '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
			this.player += '<param name="movie" value="' + playerAudioUrl + '" />'; 
			this.player += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
			this.player += '<param name="wmode" value="transparent" />';
			this.player += '</object>';
			*/
		}
		
		
	}
	else {

		console.log('AudioPlayer : Player non spécifié ou inexistant.');
	}




	var attachControls = function() {
		
		if (controllers.play !== undefined && controllers.play !== null) {

			if (controllers.pause === undefined || controllers.pause === null) {

				controllers.play.onclick = function() {

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

		if (player !== null) {

			player.onloadstart = function() {

				console.log('loadstart');
				loadingTimer = setInterval(updateLoading, 40);
			};

			/*
			player.onabort = function() {

				console.log('abort');
			};

			player.oncanplay = function() {

				console.log("canplay");
			};

			player.oncanplaythrough = function() {

				console.log("canplaythrough");
			};

			player.ondurationchange = function() {

				console.log("durationchange");
			};

			player.onerror = function() {

				console.log("error");
			};

			player.onloadeddata = function() {

				console.log("loadeddata");
			};

			player.onloadedmetadata = function() {

				console.log("loadedmetadata ");
			};

			player.onsuspend = function() {

				console.log("suspend");
			};

			player.onstalled = function() {

				console.log("stalled");
			};

			player.onwaiting = function() {

				console.log("waiting");
			};

			player.onemptied = function() {

				console.log("emptied");
			};

			player.onloadeddata = function() {
				
				console.log("loadeddata");
			};
			*/
		}
	};

	
	var createProgress = function() {

		// Si l'id du player existe, c'est qu'il a été créé.
		if (document.getElementById('audioplayer') !== null && document.getElementById('audioplayer') !== undefined) {

			// On stoppe le timer.
			clearInterval(createTimer);

			// On assigne le player html ou flash.
			player = document.getElementById('audioplayer');

			// Création d'un paramètre dans la balise <object>, manquante par défaut dans Dewplayer.
			if (playerType === 'dewp' || playerType === 'dewp-mini') {

				var url = player.getAttribute('data');
				var movieParam = document.createElement('param');
				movieParam.setAttribute('name', 'movie');
				movieParam.setAttribute('value', url);
				player.appendChild(movieParam);

				// On signale que le player a été créé avec un petit décalage pour éviter les problèmes d'appels aux fonctions Dewplayer.
				setTimeout(function() { createdCallback.call(); }, 1000);
			}

			// On attache les évènements on player html (chargement, lecture...).
			if (playerType === 'html') {

				attachEvents();

				// On signale que le player a été créé.
				createdCallback.call();
			}
		}
	}


	var updateLoading = function() {

		if (player !== null) {

			var duration = player.duration;
			console.log('duration :' + duration);
			var bufferedEnd = player.buffered.length > 0 ? player.buffered.end(0) : 0;
			var percentLoaded = bufferedEnd !== 0 ? (bufferedEnd / duration) * 100 : 100;
			console.log('percentLoaded :' + percentLoaded);

			if (playerType === 'dewp' || playerType === 'dewp-mini') {
				percentLoaded = 100;
			}

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
			console.log('percent :' + percent);

			if (progressCallBack !== null) {

				progressCallBack.call(this, percent);
			}

			if (player.ended || percent == 100) {

				clearInterval(progressTimer);
				
				isPlaying = false;
			}
		}
	};



	






	/*** Public methods ***/


	this.setPlayerType = function(type) {

		playerType = type;
	};

	this.getPlayerType = function() {

		return (playerType);
	};


	this.setTrack = function(track) {

		/// <summary>Ajoute la piste audio au lecteur</summary>
		/// <param name="track" type="String">L'url de la piste audio</param>

		//var audioplay = document.getElementById('audioplayer');

		if (playerType === 'html') {

			//var audioplay = document.getElementById('audioplayer');
			player.src = track;

			player.load();

		}
		else if (playerType === 'dewp' || playerType === 'dewp-mini') {
			/*
			var dewParams = document.getElementsByName('flashvars');

			var flashvars = 'mp3=' + track + '&amp;';
			flashvars += dewParams[0].getAttribute('value');
			dewParams[0].setAttribute('value', flashvars);
			*/

			//console.log(player);

			player.dewset(track);
		}
	};


	
	this.init = function(controls, onCreatedCallback, onLoadCallback, onProgressCallback) {

		
		createdCallback = onCreatedCallback;
		loadCallBack = onLoadCallback;
		progressCallBack = onProgressCallback;

		createTimer = setInterval(createProgress, 100);
		/*
		if (playerType === 'html') {

			player = document.getElementById('audioplayer');
		}
		*/
		//console.log(player);

		//if (player !== null && player !== undefined) {

			if (typeof controls === 'object') {

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
				/*
				if (playerType === 'html') {

					player.setAttribute('controls', true);
				}
				else if (playerType === 'dewp' || playerType === 'dewp-mini') {

		  			
				}
				*/
			}
		//}
		
	};


	this.reload = function() {

		if (player !== null) {

			player.load();
		}
	};

	
	this.startPlaying = function() {

		if (player !== null) {

			this.play();
		}
	};




	this.show = function(duration) {

	};

	this.hide = function(duration) {

	};



	this.enableControls = function(enabled) {

		for (var i in controllers) {

			if (controllers[i] !== null) {

				if (!enabled) {

					controllers[i].disabled = true;
					//controllers[i].className = 'disabled';
				}
				else if (enabled) {

					controllers[i].removeAttribute('disabled');
				}
			}
		}
		
	};


	
	this.play = function() {

		if (player !== null && !isPlaying) {

			if (playerType === 'html') {

				if (isEnded) {

					isEnded = false;
				}

				player.play();

				progressTimer = setInterval(updateProgress, 100);
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

	  			player.dewplay();
			}

			isPlaying = true;
		}
	};


	this.pause = function() {

		console.log('pause');

		if (player !== null && isPlaying) {

			if (playerType === 'html') {

				player.pause();
				clearInterval(progressTimer);
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

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
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

	  			player.dewstop();
			}

			isPlaying = false;
		}
	};

	
};