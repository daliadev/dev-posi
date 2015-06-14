
var ImageController = function(container, loader) {

	var self = this;
	var container = container;
	var loader = loader;
	var onLoadFunction = null;
	var imageBox = new Image();
	var blackBg = null; //$('#black-bg');

	this.startLoading = function(imgSrc, loaderFadeDuration, onLoadCallback) {

		onLoadFunction = onLoadCallback;

		imageBox.onload = function() {
			
			imageBox.style.display = 'none';
			container.prepend(imageBox);
			//self.container.css('height', 'auto');
			//self.container.css('padding-bottom', '0px');
			loader.fadeOut(loaderFadeDuration);

			if (typeof onLoadFunction === 'function') {
				onLoadFunction.call(self);
			}
		};

		imageBox.src = imgSrc;
		loader.fadeIn(loaderFadeDuration);
	};


	this.display = function(duration) {

		$(imageBox).fadeIn(duration);
	}


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
	}

}