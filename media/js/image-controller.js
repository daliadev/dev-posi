
var ImageController = function(container, loader) {

	var self = this;
	var container = container;
	var loader = loader;
	//var onLoadFunction = null;
	var imageBox = new Image();
	var blackBg = null; //$('#black-bg');

	var loadTimer = null;
	var loaderFadeDuration = 0;
	var loaderInterval = 40;
	var loaderCallback = null;

	var displayTimer = null;
	var displayFadeDuration = 0;
	var displayInterval = 40;
	var displayCallback = null;



	var loaderFadeIn = function() {
		
		var opacity = loader.style.opacity;
		opacity += (1000 / loaderInterval) / loaderFadeDuration;

		if (opacity >= 1) {

			clearInterval(loadTimer);
			loader.style.opacity = 1;
			loaderCallback.call(this, 'fadeIn');
		}
	};

	var loaderFadeOut = function() {

		var opacity = loader.style.opacity;
		opacity -= (1000 / loaderInterval) / loaderFadeDuration;

		if (opacity <= 0) {

			clearInterval(loadTimer);
			loader.style.display = 'none';
			loader.style.opacity = 1;
			loaderCallback.call(this, 'fadeOut');
		}
	}


	var onDisplay = function() {

		var opacity = imageBox.style.opacity;
		opacity += (1000 / displayInterval) / displayFadeDuration;

		if (opacity >= 1) {

			clearInterval(displayTimer);
			imageBox.style.opacity = 1;
			displayCallback.call(this);
		}
	};




	this.startLoading = function(imgSrc, loaderDuration, onLoadCallback) {

		loaderFadeDuration = loaderDuration;
		onLoadFunction = onLoadCallback;

		loader.style.opacity = 0;
		loader.style.display = 'block';
		loadTimer = setInterval(loaderFadeIn, loaderInterval);

		imageBox.onload = function() {
			
			loader.style.opacity = 1;
			loader.style.display = 'block';
			displayTimer = setInterval(onDisplay, displayInterval);

			imageBox.style.display = 'none';
			//container.prepend(imageBox);
			//self.container.css('height', 'auto');
			//self.container.css('padding-bottom', '0px');
			//loader.fadeOut(loaderFadeDuration);
			//loadTimer = setInterval(onLoad, loaderInterval);

		};

		imageBox.src = imgSrc;
		//loader.fadeIn(loaderFadeDuration);
	};

	this.setLoaderInterval(duration) {

		loaderInterval = duration;
	};
	this.getLoaderInterval() {

		return loaderInterval;
	};


	this.display = function(duration, onDisplayCallback) {

		//$(imageBox).fadeIn(duration);
		displayFadeDuration = duration;
		displayCallback = onDisplayCallback;
		imageBox.style.opacity = 0;
		imageBox.style.display = 'block';
		displayTimer = setInterval(onDisplay, displayInterval);
	};

	this.setDisplayInterval(duration) {

		displayInterval = duration;
	};
	this.getDisplayInterval() {

		return displayInterval;
	};




	this.fadeToBlack = function(element, duration) {

		element.append('<div id="black-bg"></div>');
		this.blackBg = $('#black-bg');
		this.blackBg.hide();
		this.blackBg.fadeIn(duration);
	};

	this.fadeToBlank = function(duration) {

		if (this.blackBg !== null) {

			this.blackBg.fadeOut(500);
			this.blackBg.remove();
		}
	};

}