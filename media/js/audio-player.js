
var AudioPlayer = function(container, sources) {


	//this.$player = playerElement;
	this.container = container;
	this.player = null;
	this.playerType = null;

	this.sources = this.setSources(sources);

	this.speaker = null;
	this.startBtn = null;
	this.pauseBtn = null;

	this.isCreated = false;
	this.isLoaded = false;
	this.isFinished = false;

	//this.events = new Object();

	/*
	var audio = new Audio('audio_file.mp3');
	audio.load();
	audio.play();
	*/
	
	this.createPlayer(playerType) {

		this.playerType = playerType;

		if (this.playerType == 'html') {

			//var playerHTML = new Audio();
			this.player = '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" controls></audio>';
		}
		else if (this.playerType == 'dewp') {
			
			this.player = '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
			this.player += '<param name="movie" value="' + playerAudioUrl + '" />'; 
			this.player += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
			this.player += '<param name="wmode" value="transparent" />';
			this.player += '</object>';

		}
		/*
		<div id="dewplayer_content">
			<object data="dewplayer.swf" width="200" height="20" name="dewplayer" id="dewplayer" type="application/x-shockwave-flash">
			<param name="movie" value="dewplayer.swf" />
			<param name="flashvars" value="mp3=mp3/test1.mp3|mp3/test2.mp3|mp3/test3.mp3&javascript=on" />
			<param name="wmode" value="transparent" />
			</object>
		</div>
		*/
	}


	this.setSources(audioSources) {

		var src = new array();

		if (typeof audioSources === 'array') {

			for (var i = 0, count = audioSources.length; i < count; i++) {

				src[i] = audioSources[i];
			};
		}
		else if (typeof audioSources === 'string') {

			src[0] = audioSources;
		}
		else {

			console.log('AudioPlayer : Invalid audio sources.')
		}
	}


	this.attachBasicControls(start, pause) {

		this.startBtn = start;
		this.pauseBtn = pause;
	}


	this.attachVolumeControls(start, pause) {

	}


	this.startLoading() {

	}

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
	/*
	this.loadFile = function(file) {

	};
	*/
	this.display = function(show) {

	};

	this.hide = function() {

	};

	this.disable = function() {

	};

	this.events = function() {

		this.button.on('click', function() {
			/*
			if (typeof self.settings.callback === 'function') {
				self.settings.callback.call(self, $(this).val());
			}
			*/
		});
	},

	this.play = function() {

	};

	this.pause = function() {

	};

	this.stop = function() {

	};
};