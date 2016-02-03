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
			
			var html = [];
			/*
			if (isFormUrl != null)
			{
				html = [] = '<form id="parcours-form" action="<?php //echo $form_url; ?>" method="post">';
			}
			*/
			if (title != null) {

				html.push('<div class="modal-box-title">' + title + '</div>');
			}
			
			html.push('<div class="modal-box-text">' + text + '</div>');

			if (buttons != null) {

				html.push('<div class="modal-box-buttons">');
				html.push(buttons);
				html.push('</div>');
			}
			/*
			return ['<form action=',
				'<div class="form-title-' + icon + '" style=""></div>', 
				'<p class="message-box-text">' + text + '</p>',
				'<div class="message-box-buttons">', buttons, '</div>'].join('');
			*/
			return html.join('');
		},

		initialize: function(form, title, text, settings, events, boxContainer) {

			//this.text = text;
			this.container = boxContainer;
			//console.log(boxContainer, this.container);
			//this.bg = $('<div>', {'class': 'modalbox-bg', 'style': 'display:none'});
			this.el = $('<div>', {'class': 'modal-box', 'style': 'display:none'});
			
			this.settings = $.extend({}, $.modalbox.defaults, settings);
			var buttons = this.createButtons(this.settings.buttons);
			var html = this.template(title, text, buttons)
			
			if (typeof form == 'object') {
				html = this.wrapForm(form, html);
			}

			this.el.html(html);
			
			this.addEvents(events);

			//this.bg.appendTo(this.container);
			this.el.appendTo(this.container);
			
			return this;
		},

		wrapForm: function(form, content) {
			var formHtml = [];
			formHtml.push('<form id="'+ form.formId +'" name ="'+ form.formId +'" action="'+ form.action +'" method="'+ form.method +'">');
			formHtml.push(content);
			formHtml.push(['</form>']);
			return formHtml.join('');
		},

		createButtons: function(buttons) {
			return $.map(buttons, function(button) {
				return '<button type="submit" class="' + button.btnclass + '" id="' + button.btnid + '" name="' + button.btnname + '">' + button.btnvalue + '</button>';
			}).join('');
		},

		addEvents: function(events) {

			//console.log('addEvents');
			var self = this;
			
			for (var i = 0; i < events.length; i++)
			{
				this.el.find(events[i].selector).on(events[i].type, function(event) {
					if (typeof events[i].callback === 'function') {
						events[i].callback.call(self, $(this).val());
					}
				});
			}
			
			this.el.find('button').on('click', function() {
				self.close();
				if (typeof self.settings.callback === 'function') {
					self.settings.callback.call(self, $(this).val());
				}
			});

			// // A généraliser
			// this.el.find('select').on('change', function() {
			// 	//self.close();
			// 	alert('change');
			// 	self.settings.callback.call(self, $(this).val());
			// });
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
			//console.log('show');
			var posX = Math.round(($(window).width() / 2) - (this.el.outerWidth(true) / 2));
			//var posY = Math.round($(window).height() / 3 - this.el.outerHeight(true) / 2);
			var posY = 0;
			this.el.css('left', posX).css('top', posY);

			//console.log(posX, posY);
			this.el.animate({top: Math.round($(window).height() / 2 - this.el.outerHeight(true) / 2), opacity: 'show'}, 500);
			//this.bg.fadeTo(this.animDuration, this.bgOpacity);
		}
	};


	$.modalbox = function(form, title, text, settings, events, boxContainer) {
		//console.log(boxContainer);
		var modal = ModalBox;
		modal.initialize(form, title, text, settings, events, boxContainer);
		modal.show();
		
		return modal;
	};
	
	$.modalbox.defaults = { 
		buttons: [
			{
				'btnvalue': 'Valider',
				'btnname': 'submit',
				'btnid' : 'btn-submit', 
				'btnclass': 'button-default'
			}
		], 
		callback: null
	};
			
			
})(jQuery);