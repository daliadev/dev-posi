
var AudioPlayer = function(playertype, playerContainer, swfPlayerURL, width, height) {

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

	var swfPlayer = swfPlayerURL;

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

	var createdCallback = null;
	var loadingCallBack = null;
	var loadedCallBack = null;
	var progressCallBack = null;


	var duration = null;



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

				//console.log('loadstart');
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

			// On attache les évènements au player html (chargement, lecture...).
			if (playerType === 'html') {

				attachEvents();

				// On signale que le player a été créé.
				createdCallback.call();

				container.style.display = 'block';
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {
				
				// On signale que le player a été créé avec un petit décalage pour éviter les problèmes d'appels aux fonctions Dewplayer.
				setTimeout(function() { 
					createdCallback.call();
					container.style.display = 'block';
				}, 1000);
			}
		}
	};


	var updateLoading = function() {

		if (player !== null) {

			// Par défaut le pourcentage du chargement est à 0.
			var percentLoaded = 0;

			// Pour le player HTML
			if (playerType === 'html') {

				duration = player.duration;
				//console.log('duration :' + duration);
				// On récupére le tableau 'buffered' qui contient les infos sur le statut actuel du lecteur
				var bufferedEnd = player.buffered.length > 0 ? player.buffered.end(0) : 0;
				// Le pourcentage de chargement est la division du temps chargé par la durée totale (x 100%)
				percentLoaded = bufferedEnd !== 0 ? (bufferedEnd / duration) * 100 : 100;
				//console.log('percentLoaded :' + percentLoaded);
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

				// Pour Dewplayer, si la position de lecture est à 0, la lecture peut démarrer (streaming)
				if (player.dewgetpos() === 0) {

					percentLoaded = 100;
				}
			}

			// On appelle la fonction callback durant le chargement
			if (loadingCallBack !== null) {
		
				loadingCallBack.call(this, percentLoaded);
			}

			// Si le chargement est à 100%, on stoppe le timer.
			if (percentLoaded == 100) {

				loadedCallBack.call(this);
				clearInterval(loadingTimer);
			}
		}
		else {

			console.log('Player not found or not ready');
		}
	};


	var updateProgress = function() {

		if (player !== null) {

			var percent = 0;
			var currenttime = 0;

			if (playerType === 'html') {

				duration = player.duration; // Duree totale
				currenttime = player.currentTime; // Temps écoulé
				
				percent = (currenttime / duration) * 100;
				//console.log('percent :' + percent);

				if (player.ended || percent === 100) {

					clearInterval(progressTimer);
					isPlaying = false;
				}

			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

				if (duration === null) {

					player.dewsetpos(3600000);
					duration = player.dewgetpos();
					player.dewsetpos(0);
				}

				currenttime = player.dewgetpos(); // Temps écoulé
				
				percent = (currenttime / duration) * 100;
				//console.log('percent :' + percent);

				if (Math.ceil(percent) === 100) {
					
					clearInterval(progressTimer);
					isPlaying = false;
				}
			}

			if (progressCallBack !== null) {

				progressCallBack.call(this, Math.ceil(percent));
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


		if (playerType === 'html') {

			player.src = track;

			player.load();

		}
		else if (playerType === 'dewp' || playerType === 'dewp-mini') {
			
			//player.dewset(track);
			/*
			var flashvars = {};
			var params = {};
			var attributes = { id: "ExternalInterfaceExample", name: "ExternalInterfaceExample" };

			swfobject.embedSWF("ExternalInterfaceExample.swf", "flashcontent", "550", "200", "8", false, flashvars, params, attributes);

			function formSend() {
				var text = document.htmlForm.sendField.value;
				var swf = document.getElementById("ExternalInterfaceExample");
				swf.sendTextToFlash(text);
			}
			 
			function getTextFromFlash(str) {
				document.htmlForm.receivedField.value = "From Flash: " + str;
				return str + " received";
			}
			*/

			//var dewp = swfobject.getObjectById('audioplayer');
  			//dewp.mp3 = track;
	
			console.log('loadstart');
			loadingTimer = setInterval(updateLoading, 1000);
		}
	};




	
	this.init = function(track, controls, onCreatedCallback, onloadingCallBack, onProgressCallback) {
		
		createdCallback = onCreatedCallback;
		loadingCallBack = onloadingCallBack;
		progressCallBack = onProgressCallback;

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
			
			if (playerType === 'html') {

				player.setAttribute('controls', true);
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

				
			}

		}



		/** Création du player **/

		createTimer = setInterval(createProgress, 100);

		container.style.display = 'none';


		if (playerType === 'html') {

			var playerHTML = document.createElement('audio');
			playerHTML.id = 'audioplayer';
			playerHTML.setAttribute('name', 'audioplayer');
			playerHTML.setAttribute('preload', 'auto');
			playerHTML.setAttribute('volume', 1);
			
			container.appendChild(playerHTML);
		}
		else if (playerType === 'dewp' || playerType === 'dewp-mini') {
			
			var dewpType = null;

			switch(playerType) {

				case 'dewp' :
					dewpType = swfPlayer + 'dewplayer.swf';
					break;

				case 'dewp-mini' :
					dewpType = swfPlayer + 'dewplayer-mini.swf';
					break;

				default :
					break;
			}

			if (dewpType !== null) {
				
				var tempContent = document.createElement('div');
				tempContent.id = 'dewp-content';
				container.appendChild(tempContent);
				
				var flashvars = {
					mp3: track,
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
				if (swfobject.hasFlashPlayerVersion("9.0.0")) {

					var createSWF = function() {
						var att = { id: 'audioplayer', name: 'dewplayer', data: dewpType, width: playerWidth, height: playerHeight };
						var par = { flashvars: 'nopointer=1&amp;javascript=on', wmode: 'transparent' };
						var id = 'dewp-content';

						swfPlayerObject = swfobject.createSWF(att, par, id);
					};

					swfobject.addDomLoadEvent(createSWF);
				}
				*/

				/*
				var flashObject = document.createElement('object');
				flashObject.id = 'audioplayer';
				flashObject.setAttribute('name', 'dewplayer');
				flashObject.setAttribute('data', dewpType);
				flashObject.setAttribute('type', 'application/x-shockwave-flash');
				flashObject.width = playerWidth;
				flashObject.height = playerHeight;
				//flashObject.style.display = 'block';

				var movieParam = document.createElement('param');
				movieParam.setAttribute('name', 'movie');
				movieParam.setAttribute('value', dewpType);
				flashObject.appendChild(movieParam);

				var flashvarsParam = document.createElement('param');
				flashvarsParam.setAttribute('name', 'flashvars');
				var flashvarsValues = 'nopointer=1&amp;javascript=on';
				flashvarsParam.setAttribute('value', flashvarsValues);
				flashObject.appendChild(flashvarsParam);
				
				var wmodeParam = document.createElement('param');
				wmodeParam.setAttribute('name', 'wmode');
				wmodeParam.setAttribute('value', 'transparent');
				flashObject.appendChild(wmodeParam);

				container.appendChild(flashObject);
				*/

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

	};


	this.startLoading = function(onLoadedCallBack) {

		loadedCallBack = onLoadedCallBack;
		loadingTimer = setInterval(updateLoading, 1000);

	}


	this.reload = function() {

		if (player !== null) {

			player.load();
		}
	};

	
	this.startPlaying = function(onStartCallBack) {

		if (player !== null) {

			this.play();
			onStartCallBack.call(this);
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
				}
				else if (enabled) {

					controllers[i].removeAttribute('disabled');
				}
			}
		}
		
	};


	
	this.play = function() {

		console.log('play');

		if (player !== null && !isPlaying) {

			if (playerType === 'html') {

				if (isEnded) {

					isEnded = false;
				}

				player.play();

				//progressTimer = setInterval(updateProgress, 100);
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {
				
				player.dewplay();
			}

			progressTimer = setInterval(updateProgress, 100);

			isPlaying = true;
		}
	};


	this.pause = function() {

		console.log('pause');

		if (player !== null && isPlaying) {

			if (playerType === 'html') {

				player.pause();
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

				player.dewpause();
			}

			clearInterval(progressTimer);
			isPlaying = false;
		}
	};


	this.stop = function() {

		console.log('stop');

		if (player !== null && isPlaying) {

			if (this.playerType === 'html') {

				player.pause();
				player.currentTime = 0;
			}
			else if (playerType === 'dewp' || playerType === 'dewp-mini') {

				player.dewstop();
			}

			clearInterval(progressTimer);
			isPlaying = false;
		}
	};

	
};