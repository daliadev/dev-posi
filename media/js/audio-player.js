
var AudioPlayer = function(audioSources) {


	//this.$player = playerElement;
	var self = this;
	var container = null;
	var player = null;
	


	/* Privates */
	
	//this.speaker = null;
	//var controls;
	var startBtn = null;
	var pauseBtn = null;

	var isCreated = false;
	var isLoaded = false;
	var isFinished = false;
	var isPlaying = false;

	var completeCallBack = null;

	//this.events = new Object();
	/*
	var audio = new Audio('audio_file.mp3');
	audio.load();
	audio.play();
	*/

	//var sources = aggregateSources(audioSources);
	var sources = function(audioSrc) {

		var src = new Array();

		if (typeof audioSrc === 'array') {

			for (var i = 0, count = audioSrc.length; i < count; i++) {

				src.push('mp3/' + audioSrc[i]);
			};
		}
		else if (typeof audioSrc === 'string') {

			src[0] = 'mp3/' + audioSrc;
		}
		else {

			console.log('AudioPlayer : Invalid audio sources.');
		}

		return src.join('|');
	};


	var update = function() {

	}



	var attachEvents = function() {

		startBtn.on("click", function() {

			console.log(self);
			//self.play();
		});

		console.log(startBtn);

		/*
		pauseBtn.on('click', function(event) {

			self.pause();
		});
		*/
	};




	/* Publics */

	this.playerType = null;
	
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

			this.player = '<audio id="audioplayer" name="audioplayer"></audio>';
		}
		else if (this.playerType == 'dewp' || this.playerType == 'dewp-mini') {
			
			var dewpType = null;

			switch(this.playerType) {

				case 'dewp' :
					dewpType = playerURL + 'dewplayer';
					break;

				case 'dewp-mini' :
					dewpType = playerURL + 'dewplayer-mini';
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
					id: 'dewplayer',
				};

				swfobject.embedSWF(dewpType, 'dewp-content', width, height, '9.0.0', false, flashvars, params, attributes);
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


	


	this.attachControls = function(buttons) {

		console.log(buttons);

		if (buttons.start !== null) {

			startBtn = buttons.start;
		}

		if (buttons.pause !== null) {

			pauseBtn = buttons.pause;
		}

		if (buttons.start !== null && buttons.pause === null) {

			pauseBtn = buttons.start;
		}

		attachEvents();
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
	this.create = function(file, flashPlayerUrl, buttons) {

		if (navAgent.isAudioEnabled()) {

			this.$player.html(this.templateHtml(file));
			this.$player.attr('autoplay', true);
		}
		else if (FlashDetect.installed) {
			
			this.$player.html(this.templateFlash(file, flashPlayerUrl));
		}
		else {

			alert('Audioplay not supported by the browser');
		}

		this.$player.css('display', 'none');
		
		this.$button = buttonElement;

		this.isCreated = true;
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

		console.log('play');

		if (this.playerType == 'html') {
			this.player.play();
		}
		else if (this.playerType == 'dewp' || this.playerType == 'dewp-mini') {

			var dewp = $("#dewplayer");
  			if (dewp != null) {
  				dewp.dewplay();
  			}
		}
	};

	this.pause = function() {

	};

	this.stop = function() {

	};


	this.onCompleteCallBack = function(callBack) {

		completeCallBack = callBack;
	}


	
};