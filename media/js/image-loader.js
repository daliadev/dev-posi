
var ImageLoader = function(container, loader, onLoadCallback) {

	var self = this;
	this.container = container;
	this.loader = loader;
	this.onLoadFunction = onLoadCallback;
	this.imageBox = new Image();

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
			self.container.append(this);
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


	this.fadeInBlack = function(duration) {

		var $zone = this.container.append('<div id="black-bg"></div>');
		$zone.hide();
		// $zone.css('position', 'absolute');
		// $zone.css('top', '0').css('bottm', '0').css('top', '0').css('top', '0');
		// $zone.css('height', 'auto').css('width', '100%');
		// $zone.css('z-index', '999');
		// $zone.css('background-color', '#000000').css('opacity', '0.5');
		$zone.fadeIn(duration);
	};

	this.fadeOutBlack = function() {

		if ($('#black-bg') !== null) {

			$('#black-bg').fadeOut(500);
			$('#black-bg').remove();
		}
	}

}