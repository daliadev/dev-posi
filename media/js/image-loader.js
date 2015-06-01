
var ImageLoader = function(container, loader, onLoadCallback) {

	var self = this;
	this.container = container;
	this.loader = loader;
	this.onLoadFunction = onLoadCallback;
	this.imageBox = new Image();
	this.blackBg = null; //$('#black-bg');

	this.startLoading = function(imgSrc, loaderFadeDuration) {

		this.fadeDuration = loaderFadeDuration;
		this.imageBox.onload = function() {
			
			//imageBox.style.display = 'none';
			/*
			self.container.appendChild(this);
			self.container.style.height = 'auto';
			self.container.style.paddingBottom = '0';
			*/
			$(this).hide();
			//self.blackBg.hide();
			//self.container.prepend('<div id="black-bg"></div>');
			self.container.prepend(this);
			self.container.css('height', 'auto');
			self.container.css('padding-bottom', '0px');
			self.loader.fadeOut(self.fadeDuration);

			if (typeof self.onLoadFunction === 'function') {
				self.onLoadFunction.call(self);
			}
		};

		this.imageBox.src = imgSrc;
		//this.imageBox.style.display = 'none';
		this.loader.fadeIn(this.fadeDuration);
	};


	this.fadeToBlack = function(duration) {

		//var blackDiv = document.createElement('div');
		//blackDiv.id = 'black-bg';

		//this.container.children().last().before('<div id="black-bg"></div>');
		$('body').children().first().after('<div id="black-bg"></div>');
		//var suiteBtn = this.container.lastChild;
		//suiteBtn.appendChild('<div id="black-bg"></div>');
		//this.container.prepend('<div id="black-bg"></div>');
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