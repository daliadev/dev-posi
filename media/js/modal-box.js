(function($) {
			
	"use strict";
	
	var ModalBox = {

		self: this,
		text: '',
		container: null,
		bg: null,
		el: null,
		bgOpacity: 0.35,
		animDuration: 1000,
		
		windowWidth: function() {
			return window.innerWidth;
		},
		windowHeight: function() {
			return window.innerHeight;
		},

		template: function(title, text, buttons) {
			
			html = [];

			if (isFormUrl != null)
			{
				html = [] = '<form id="parcours-form" action="<?php //echo $form_url; ?>" method="post">';
			}

			if (title != null) {

				html[] = '<div class="modal-box-title">' + title + '</div>';
			}
			
			html[] = '<div class="modal-box-text">' + text + '</div>';

			if (buttons != null) {

				html[] = '<div class="modal-box-buttons">' + buttons + '</div>';
			}
			/*
			return ['<form action=',
				'<div class="form-title-' + icon + '" style=""></div>', 
				'<p class="message-box-text">' + text + '</p>',
				'<div class="message-box-buttons">', buttons, '</div>'].join('');
			*/
			return html.join('');
		},

		initialize: function(form, title, text, settings, boxContainer) {

			this.text = text;
			this.container = boxContainer;
			//this.bg = $('<div>', {'class': 'message-bg', 'style': 'display:none'});
			this.el = $('<div>', {'class': 'inner-box', 'style': 'display:none'});
			
			this.settings = $.extend({}, $.message.defaults, settings);
			var buttons = this.createButtons(this.settings.buttons);
			this.el.html(this.template(isForm, title, text, buttons));

			if (typeof(form) === 'object') {
				wrapForm(form, html);
			}

			this.events();

			//this.bg.appendTo(this.container);
			this.el.appendTo(this.container);
			
			return this;
		},

		wrapForm: function(form, content) {

			console.log('wrapform');
		},

		createButtons: function(buttons) {
			return $.map(buttons, function(button) {
				return '<button type="submit" class="' + button.btnclass + '">' + button.btnvalue + '</button>';
			}).join('');
		},

		events: function() {
			var self = this;
			this.el.find('button').on('click', function() {
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
				500,
				function() {
					$(this).remove();
				}
			);

			/*
			this.bg.animate({
				//top: $(window).height() / 2 - this.el.outerHeight() / 2, 
				opacity: 'hide'}, 
				1000,
				function() {
					$(this).remove();
				}
			);
			*/
		},

		show: function() {
			var posX = Math.round(($(window).width() / 2) - (this.el.outerWidth(true) / 2));
			//var posY = Math.round($(window).height() / 3 - this.el.outerHeight(true) / 2);
			var posY = 0;
			this.el.css('left', posX).css('top', posY);
			
			this.el.animate({top: Math.round($(window).height() / 3 - this.el.outerHeight(true) / 2), opacity: 'show'}, 500);
			this.bg.fadeTo(this.animDuration, this.bgOpacity);
		}
	};


	$.modalbox = function(form, title, text, settings, boxContainer) {
		var modal = ModalBox;
		modal.initialize(form, title, text, settings, boxContainer);
		modal.show();
		return modal;
	};
	
	$.modalbox.defaults = { 
		buttons: [
			{
				'btnvalue': 'Valider', 
				'btnclass': 'button-default'
			}
		], 
		callback: null
	};
			
			
})(jQuery);