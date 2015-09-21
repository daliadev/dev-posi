(function($) {
			
	"use strict";
	
	var MessageBox = {

		self: this,
		link: "#",
		bgOpacity: 0.5,
		animDuration: 500,
		container: null;

		windowWidth: function() {
			return window.innerWidth;
		},
		windowHeight: function() {
			return window.innerHeight;
		},

		template: function(text, buttons, icon) {
			
			return ['<div class="message-box-' + icon + '" style=""></div>', 
				'<p class="message-box-text">' + text + '</p>',
				'<div class="message-box-buttons">', buttons, '</div>'].join('');
		},

		initialize: function(text, settings, attachement) {
			/*
			PopBox.posx = (PopBox.windowWidth() - PopBox.w) / 2;
			PopBox.posy = (PopBox.windowHeight() - PopBox.h) / 2;

			$(window).resize(PopBox.resize);
			
			var innerHtml = ['<div id="popbox">',
				'<div id="popbox-fill"></div>',
				'<div id="popbox-loader"></div>',
				'<div id="popbox-container">',
				'<div id="popbox-inner">',
				'<div id="popbox-close"></div>',
				'<div id="popbox-content"></div>',
				'</div></div></div>'].join('');
			$("body").append(innerHtml);
			// $("#popbox-close").hide();
			//$("#popbox-container").hide();
			$("#popbox-loader").hide().fadeIn();
			$("#popbox-fill").hide().fadeTo(PopBox.animDuration, this.bgOpacity);
			//$("#popbox-content").hide();
			$("#popbox-close").click(PopBox.close);
			$("#popbox-fill").click(PopBox.close);
			
			PopBox.img = new Image();
			PopBox.img.src = this.link;

			PopBox.timer = setInterval(PopBox.load, 100);
			*/

			this.text = text;
			this.container = attachement;
			this.el = $('<div>', {'class': 'message-box', 'style': 'display:none'});
			
			this.settings = $.extend({}, $.message.defaults, settings);
			var buttons = this.createButtons(this.settings.buttons);
			this.el.html(this.template(text, buttons, this.settings.icon));

			this.events();

			this.el.appendTo(this.container);
			
			return this;
		},

		createButtons: function(buttons) {
			return $.map(buttons, function(button) {
				return '<button type="submit" class="' + button.btnclass + '">' + button.btnvalue + '</button>';
			}).join('');
		},

		events: function() {
			var self = this;
			this.el.find('input').on('click', function() {
				self.close();
				if (typeof self.settings.callback === 'function') {
					self.settings.callback.call(self, $(this).val());
				}
			});
		},

		close: function() {
			this.el.animate({
				//top: $(window).height() / 2 - this.el.outerHeight() / 2, 
				opacity: 'hide'}, 
				250,
				function() {
					$(this).remove();
				}
			);
		},

		show: function() {
			var posX = Math.round(($(window).width() / 2) - (this.el.outerWidth(true) / 2));
			//var posY = Math.round($(window).height() / 3 - this.el.outerHeight(true) / 2);
			var posY = 0;
			this.el.css('left', posX).css('top', posY);
			
			this.el.animate({top: Math.round($(window).height() / 3 - this.el.outerHeight(true) / 2), opacity: 'show'}, 500);
			this.container.hide().fadeTo(this.animDuration, this.bgOpacity);
		}
	};

	$.message = function(text, settings, attachement) {
		var msg = MessageBox;
		msg.initialize(text, settings, attachement);
		msg.show();
		return msg;
	};
	
	$.message.defaults = {
		icon: 'info', 
		buttons: [
			{
				'btnvalue': 'Okay', 
				'btnclass': 'button-default'
			}
		], 
		callback: null
	};
			
			
})(jQuery);