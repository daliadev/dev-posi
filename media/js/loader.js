
var Loader = {

	self: this,
	//loaderSrc: "./images/loader.gif",
	bgOpacity: 0.7,
	animDuration: 250,
	/*
	windowWidth: function() {
		return window.innerWidth;
	},
	windowHeight: function() {
		return window.innerHeight;
	},
	*/
	w: 128,
	h: 128,
	//posx: 0,
	//posy: 0,

	open: function() {

		//Loader.posx = (Loader.windowWidth() - Loader.w) / 2;
		//Loader.posy = (Loader.windowHeight() - Loader.h) / 2;
		var innerHtml = ['<div id="loader">',
			'<div id="loader-bg"></div>',
			'<div id="loader-icon"></div>',
			'</div>'].join('');
		$('body').append(innerHtml);	
		/*
		$('#loader-bg').css('position', 'fixed');
		$('#loader-bg').css('background-color', '#000000');
		$('#loader-bg').css('width', 100 + '%').css('height', 100 + '%');
		$('#loader-bg').css('top', 0).css('left', 0);
		$('#loader-bg').css('z-index', 9997);
		
		$('#loader-icon').css('position', 'fixed');
		$('#loader-icon').css('background', 'url('+ Loader.loaderSrc +') center center no-repeat');
		$('#loader-icon').css('width', 100 + '%').css('height', 100 + '%');
		$('#loader-icon').css('top', 0).css('left', 0);
		$('#loader-icon').css('z-index', 9998);
		*/
		
		$("#loader-bg").hide().fadeTo(Loader.animDuration, Loader.bgOpacity);
		$("#loader-icon").hide().fadeIn();
	}
}

$.loader = function() {

	var loaderObj = Loader;
	loaderObj.open();
	
	return loaderObj;
}