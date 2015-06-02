
var AudioPlayer = function(audioSources) {


	//this.$player = playerElement;
	

	/* Privates */
	
	var self = this;
	var container = null;
	
	var sources = null;

	var startBtn = null;
	var pauseBtn = null;

	var isCreated = false;
	var isLoaded = false;
	var isFinished = false;
	var isPlaying = false;

	var completeCallBack = null;


	/*
	var audio = new Audio('audio_file.mp3');
	audio.load();
	audio.play();
	*/

	
	//var aggregateSources = function(audioSrc) {
	var sources = function(audioSrc) {

		var src = new Array();

		if (typeof audioSrc === 'array') {

			for (var i = 0, count = audioSrc.length; i < count; i++) {

				src.push('mp3/' + audioSrc[i]);
			}
		}
		else if (typeof audioSrc === 'string') {

			src[0] = audioSrc;
		}
		else {

			console.log('AudioPlayer : Invalid audio sources.');
		}

		return src.join('|');
	};
	//sources = aggregateSources(audioSources);



	var update = function() {

	};


	/*
	var attachEvents = function() {

		console.log(startBtn);

		startBtn.on("click", function() {

			console.log('click');
			//self.play();
		});

		//console.log(startBtn);

	};
	*/



	/* Publics */

	this.playerType = null;
	this.player = null;
	
	this.create = function(content, type, playerURL, size) {

		container = content;
		this.playerType = type;

		var width = 160;
		var height = 20;

		if (typeof size === 'object') {

			width = size.w;
			height = size.h;
		}
		
		if (this.playerType == 'html') {

			var playerHTML = '<audio id="audioplayer" name="audioplayer"></audio>';
			//$(player).css('width', width);
			//$(player).css('height', height);
			container.append(playerHTML);
		}
		else if (this.playerType == 'dewp' || this.playerType == 'dewp-mini') {
			
			var dewpType = null;

			switch(this.playerType) {

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
					mp3: sources(audioSources), //'mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3',
					javascript: 'on'
				};
				var params = {
					wmode: 'transparent'
				};
				var attributes = {
					id: 'audioplayer'
				};

				swfobject.embedSWF(dewpType, 'dewp-content', width.toString(), height.toString(), '9.0.0', false, flashvars, params, attributes);
			}
			
			/*
			this.player = '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
			this.player += '<param name="movie" value="' + playerAudioUrl + '" />'; 
			this.player += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
			this.player += '<param name="wmode" value="transparent" />';
			this.player += '</object>';
			
			<div id="dewplayer_content">
				<object data="dewplayer.swf" width="200" height="20" name="dewplayer" id="dewplayer" type="application/x-shockwave-flash">
				<param name="movie" value="dewplayer.swf" />
				<param name="flashvars" value="mp3=mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3&javascript=on" />
				<param name="wmode" value="transparent" />
				</object>
			</div>
			*/
		}
		else {

			console.log('AudioPlayer : Player non spécifié ou inexistant.');
		}

	};

	


	this.attachControls = function(controls) {

		player = document.getElementById('audioplayer');

		if (typeof controls === 'object') {

			if (controls.startBtn !== null) {

				startBtn = controls.startBtn;

				if (controls.pauseBtn !== undefined && controls.pauseBtn !== null) {

					startBtn.on('click', function(event) {

						self.play();
					});
				}
				else {

					startBtn.on('click', function(event) {

						if (!isPlaying) {

							self.play();
						}
						else {

							self.pause();
						}
						
					});
				}
			}
			
			if (controls.pauseBtn !== undefined && controls.pauseBtn !== null) {

				pauseBtn = controls.pauseBtn;

				pauseBtn.on('click', function(event) {

					self.pause();
				});
			}
			
			/*
			if (controls.startBtn !== null && (controls.pauseBtn === undefined || controls.pauseBtn === null)) {

				startBtn.on('click', function(event) {

					if (!isPlaying) {

						self.play();
					}
					else
					{
						self.pause();
					}
					
				});
			}
			*/
			
			//attachEvents();
		}
	};


	this.startLoading = function() {

	};

	/*
	this.templateHtml = function(file) {

		var html = '<audio id="audioplayer" name="audioplayer" src="' + file + '"></audio>';
		return html;
	};

	this.templateFlash = function(file, flashPlayerUrl) {

		var html = '';
		html += '<object id="dewplayer" name="dewplayer" data="' + flashPlayerUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
		html += '<param name="movie" value="' + flashPlayerUrl + '" />'; 
		html += '<param name="flashvars" value="mp3=' + file + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
		html += '<param name="wmode" value="transparent" />';
		html += '</object>';
		return html;
	};
	*/

	/*
	this.loadFile = function(file) {

	};
	
	this.display = function(show) {

	};

	this.hide = function() {

	};
	*/

	this.enable = function(enabled) {

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



	this.play = function() {

		player = document.getElementById('audioplayer');

		if (player !== null && !isPlaying) {

			if (self.playerType === 'html') {

				player.play();
			}
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {

	  			player.dewplay();
			}

			isPlaying = true;

		}
	};


	this.pause = function() {

		player = document.getElementById('audioplayer');

		if (player !== null && isPlaying) {

			if (self.playerType === 'html') {

				player.pause();
			}
			else if (this.playerType === 'dewp' || this.playerType === 'dewp-mini') {

	  			player.dewpause();
			}

			isPlaying = false;
		}
	};

	this.stop = function() {

	};


	this.setOnCompleteCallBack = function(callBack) {

		completeCallBack = callBack;
	};


	
};