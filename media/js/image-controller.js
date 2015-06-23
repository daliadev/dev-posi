
var ImageController = function(container, loader, onCreateCallback) {

	
	var $self = $(this);
	var $container = container;
	var $loader = loader;
	$loader.hide();
	var imageBox = new Image();
	// var $blackBg = null;

	var loadTimer = null;
	var timerInterval = 100;
	var timerTime = 0;
	var loaderFadeDuration = 0;


	var isImageLoaded = false;

	var createCallback = onCreateCallback;
	var loadCallback = null;
	var displayCallback = null;



	var loaderFadeIn = function() {

		timerTime += timerInterval;

		if (isImageLoaded && timerTime >= loaderFadeDuration) {

			clearInterval(loadTimer);
			loadCallback.call($self);
			$loader.fadeOut(loaderFadeDuration);
		}
	};



	var onDisplay = function() {

		//clearInterval(displayTimer);
		displayCallback.call(this);
	};




	this.startLoading = function(imgSrc, loaderDuration, onLoadCallback) {

		loaderFadeDuration = loaderDuration;
		loadCallback = onLoadCallback;

		timerTime = 0;
		loadTimer = setInterval(loaderFadeIn, timerInterval);
		$loader.fadeIn(loaderFadeDuration);

		imageBox.onload = function() {

			$(this).hide();
			$container.append($(this));

			isImageLoaded = true;
			//loadCallback.call($self);
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




	this.display = function(duration, onDisplayCallback) {

		//displayFadeDuration = duration;
		displayCallback = onDisplayCallback;

		//displayTimer = setInterval(onDisplay, displayInterval);
		$(imageBox).fadeIn(duration);
		setTimeout(onDisplay, duration);
	};



	/*
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
	*/

};