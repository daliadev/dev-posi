(function($) {
			
	"use strict";
	
	var ModalBox = {

		self: this,
		//text: '',
		container: null,
		//bg: null,
		el: null,
		//bgOpacity: 0.35,
		animDuration: 1000,
		
		windowWidth: function() {
			return window.innerWidth;
		},
		windowHeight: function() {
			return window.innerHeight;
		},

		get: function() {
			return this;
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

		initialize: function(content, settings, boxContainer) {

			//this.text = text;
			this.container = boxContainer;
			//console.log(content);
			//this.bg = $('<div>', {'class': 'modalbox-bg', 'style': 'display:none'});
			this.el = $('<div>', {'class': 'modal-box', 'style': 'display: none;'});
			//this.el = html;
			//this.el = $('');
			
			this.settings = $.extend({}, $.modalbox.defaults, settings);
			var buttons = this.createButtons(this.settings.buttons);
			
			//var html = this.template(title, text, buttons)
			
			/*
			if (typeof form == 'object') {
				html = this.wrapForm(form, html);
			}
			*/
			this.el.append(content);
			//console.log(this.el);

			this.addEvents(this.settings.events);

			//this.bg.appendTo(this.container);
			this.el.appendTo(this.container);


			
			//return this;
		},
		/*
		wrapForm: function(form, content) {
			var formHtml = [];
			formHtml.push('<form id="'+ form.formId +'" name ="'+ form.formId +'" action="'+ form.action +'" method="'+ form.method +'">');
			formHtml.push(content);
			formHtml.push(['</form>']);
			return formHtml.join('');
		},
		*/
		
		createButtons: function(buttons) {

			return $.map(buttons, function(button) {
				return '<button type="submit" class="' + button.btnclass + '" id="' + button.btnid + '" name="' + button.btnname + '">' + button.btnvalue + '</button>';
			}).join('');
		},
		
		addEvents: function(events) {

			//console.log(events.length);
			var self = this;
			
			for (var i = 0; i < events.length; i++)
			{
				//console.log(events[i]);
				//var selector = events[i].selector;
				
				//var callback = events[i].callback;

				//console.log(callback);
				var eventType = events[i].type;
				var $button = this.el.find(events[i].selector);
				//$button.attr('data-func', events[i].callback);

				$button.on(eventType, function(event) {
					
					event.preventDefault();

					var id = $(this).attr('id');
					//var callback = $(this).attr('data-func');

					self.triggerEvent(id);
					/*
					if (typeof callback === 'function' && (id.search("save") >= 0 || id.search("valid") >= 0)) {

						var formValues = self.el.find('form').serializeArray();
						var values = {};

						for (var i = 0; i < formValues.length; i++) {
							var prop = formValues[i].name;
							var value = formValues[i].value;
							values[prop] = value;
						}
						//console.log("values = " + values);

						callback.call(self, values);
					}
					else if (id.search("annul") >= 0 || id.search("cancel") >= 0) {

						self.close();
					}
					else {

						if (typeof callback === 'function') {

							if ($(this).val() != null) {

								callback.call(self, $(this).val());
							}
							else {

								callback.call(self);
							}
						}
					}
					*/
				});
			}
			
			/*
			this.el.find('button').on('click', function() {
				self.close();
				if (typeof self.settings.callback === 'function') {
					self.settings.callback.call(self, $(this).val());
				}
			});
			*/
			// // A généraliser
			// this.el.find('select').on('change', function() {
			// 	//self.close();
			// 	alert('change');
			// 	self.settings.callback.call(self, $(this).val());
			// });
		},

		triggerEvent: function(id) {

			console.log(id);

			var callback = null;

			for (var i = 0; i < this.settings.events.length; i++)
			{
				var eventHandler = this.settings.events[i];

				if (eventHandler.id == id && eventHandler.callback != null && typeof eventHandler.callback === 'function') {

					callback = eventHandler.callback;
					console.log(callback);
					
					if (id.search("save") >= 0 || id.search("valid") >= 0) {

						var formValues = self.el.find('form').serializeArray();
						var values = {};

						for (var i = 0; i < formValues.length; i++) {
							var prop = formValues[i].name;
							var value = formValues[i].value;
							values[prop] = value;
						}
						//console.log("values = " + values);

						callback.call(self, values);
					}
					else if (id.search("annul") >= 0 || id.search("cancel") >= 0) {

						self.close();
					}
					else {

						if ($(this).val() != null) {

							callback.call(self, $(this).val());
						}
						else {

							callback.call(self);
						}
					}
				}
			}
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


	$.modalbox = function(content, settings, boxContainer) {
		var modal = ModalBox;
		modal.initialize(content, settings, boxContainer);
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
		eventsCallback: null
	};
			
			
})(jQuery);