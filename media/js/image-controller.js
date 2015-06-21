
var ImageController = function(container, loader) {

	var $self = $(this);
	var $container = container;
	var $loader = loader;
	var imageBox = new Image();
	var $blackBg = null; //$('#black-bg');

	var loadTimer = null;
	var loaderFadeDuration = 0;

	var isImageLoaded = false;

	var loadCallback = null;
	var displayCallback = null;


	var loaderFadeIn = function() {

		if (isImageLoaded) {

			clearInterval(loadTimer);
			//loadTimer = setInterval(loaderFadeOut, loaderInterval);
			$(loader).fadeOut(loaderFadeDuration);
		}
		


		/*
		var opacity = parseFloat(loader.style.opacity);

		if (opacity >= 1.0) {

			loader.style.opacity = 1;

			
		}
		else {

			opacity += (1000 / loaderInterval) / loaderFadeDuration;
			loader.style.opacity = opacity;
		}
		*/
	};

	/*
	var onLoaderFadeIn = function() {

		
		if (hasJQuery) {

			$(loader).fadeOut(loaderFadeDuration);
		}
		else{

			clearInterval(loadTimer);
			loadTimer = setInterval(loaderFadeOut, loaderInterval);
		}
	};
	*/

	/*
	var loaderFadeOut = function() {


		var opacity = parseFloat(loader.style.opacity);
		opacity -= (1000 / loaderInterval) / loaderFadeDuration;
		loader.style.opacity = opacity;

		if (opacity <= 0.0) {

			
			loader.style.display = 'none';
			loader.style.opacity = '1';
		}

		clearInterval(loadTimer);
	};
	*/


	var onDisplay = function() {

		//clearInterval(displayTimer);
		displayCallback.call(this);
	};




	this.startLoading = function(imgSrc, loaderDuration, onLoadCallback) {

		loaderFadeDuration = loaderDuration;
		loadCallback = onLoadCallback;

		loadTimer = setInterval(loaderFadeIn, 100);
		$loader.fadeIn(loaderFadeDuration);

		imageBox.onload = function() {

			$(this).hide();
			$container.append($(this));

			isImageLoaded = true;
			loadCallback.call($self);
			//console.log('image loaded');

			//clearInterval(loadTimer);
			//loader.style.opacity = 1;
			//loader.style.display = 'block';
			//loadTimer = setInterval(loaderFadeOut, loaderInterval);

			//imageBox.style.display = 'block';
			//container.prepend(imageBox);
			//self.container.css('height', 'auto');
			//self.container.css('padding-bottom', '0px');

		};

		imageBox.src = imgSrc;
	};
	/*
	this.setLoaderInterval = function(duration) {

		loaderInterval = duration;
	};
	this.getLoaderInterval = function() {

		return loaderInterval;
	};
	*/



	this.display = function(duration, onDisplayCallback) {

		//displayFadeDuration = duration;
		displayCallback = onDisplayCallback;

		//displayTimer = setInterval(onDisplay, displayInterval);
		$(imageBox).fadeIn(duration);
		setTimeout(onDisplay, duration);
	};
	/*
	this.setDisplayInterval = function(duration) {

		displayInterval = duration;
	};
	this.getDisplayInterval = function() {

		return displayInterval;
	};
	*/



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

};