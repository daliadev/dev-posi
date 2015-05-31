
var AudioSpeaker = function(playerElement) {


	this.$player = playerElement;
	this.playerType = '';

	this.$button = null;

	this.isCreated = false;
	this.isLoaded = false;
	this.isFinished = false;

	this.speaker = null;

	var audio = new Audio('audio_file.mp3');
	


	this.setSpeaker = function(speakerEl) {

		this.speaker = speakerEl;
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