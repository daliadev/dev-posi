
$(function() {
			
	// 'use strict';
	//$("#infos-posi").tabs();

	// $("#infos-posi").tooltip();

	$("#table-resultats").tablesorter();

			//$('.categories-list ul li').get(0).addClass('active)';



			// Liste des résultats par catégories interactives
			$('.categories-list a').on('click', function() {

				var link = $(this);
				var closest_ul = link.closest('ul');
				//var ul_children = $(this).closest('ul').find('ul');
				//var ul_children = link.closest('ul');
				//var active_links = closest_ul.find('.active');
				var closest_li = link.closest('li');
				//var closest_active_li = closest_li.hasClass('.active');
				var count = 0;

				/* Slide up all the link lists not marked has active */
				closest_ul.find('ul').slideUp(function() {

					if (++count == closest_ul.find('ul').length) {

						closest_ul.find('.active').removeClass('active');
					}
				});

				/* Slide down the link list below the link clicked, only if it is closed */
				if (!closest_li.hasClass('active')) {

					closest_li.children('ul').slideDown();
					closest_li.addClass('active');
				}

			});



			// Validation des acquis : le select et le bouton "Enregistrer sont désactivés par défaut
			var selectedAcquis = $("#ref_valid_cbox").val();
			//console.log(selectedAcquis);
			$("#ref_valid_cbox").attr("disabled", true);
			$("#submit-acquis").attr("disabled", true);
			$("#clear-acquis").attr("disabled", true);
			$("#submit-acquis").hide();
			$("#clear-acquis").hide();

			// Gestion de la validation des acquis
			$('#modif-acquis').click(function(event) {
				$(this).hide();
				$('#submit-acquis').show();
				$('#clear-acquis').show();
				$("#submit-acquis").attr("disabled", false);
				$("#clear-acquis").attr("disabled", false);
				$("#ref_valid_cbox").attr("disabled", false);
				return false;
			});

			$('#submit-acquis').click(function(event) {
				$(this).hide();
				$("#clear-acquis").hide();
				$('#modif-acquis').show();
				$('#form-posi').submit();
				return false;
			});

			$('#clear-acquis').click(function(event) {
				$(this).hide();
				$('#submit-acquis').hide();
				$('#modif-acquis').show();
				$("#ref_valid_cbox").attr("disabled", true);
				$("#ref_valid_cbox").val(selectedAcquis);
				return false;
			});



			//<?php if (Config::ALLOW_AJAX) : ?>

				//console.log('Ajax allowed');
				/* Listes dynamiques en ajax */
			   
				$('.ajax-list').change(function(event) {


					var select = $(this);
					var target = '#' + select.data('target');
					var url = select.data('url');
					var sortOf = select.data('sort');
					
					var refOrgan = null;
					var refUser = null;

					if (sortOf === "user") {

						$("#ref_session_cbox").parents('.filter-item').hide();

						refOrgan = $("#ref_organ_cbox").val();
					}
					else if (sortOf === "session") {

						//$('#ref_session_cbox').show();

						$('.organ-option').each(function() {

							var option = $(this)[0];
							
							if ($(option).prop('selected')) {

								refOrgan = $(option).val();
							}
						});

						refUser = $('#ref_user_cbox').val();


						var cbox = $('#ref_session_cbox').get(0);

						if (cbox.options.length > 1) {
	
							cbox.options.length = 1;
							
						}
					}


					$.post(url, {"ref_organ":refOrgan,"ref_user":refUser,"sort":sortOf}, function(data) {
						
						if (data.error) {

							alert(data.error);
						}
						else {

							$(target).parents('.filter-item').show();
							var $target = $(target).get(0);
							$target.options.length = 1;
							
							if (data.results.utilisateur) {
								
								var i = 1;
								for (var prop in data.results.utilisateur) {
								
									var result = data.results.utilisateur[prop];

									$target.options[i] = new Option(result.nom_user + " " + result.prenom_user, result.id_user, false, false);

									i++;
								}
							}
							else if (data.results.session) {

								var i = 1;
								for (var prop in data.results.session) {
								
									var result = data.results.session[prop];

									$target.options[i] = new Option(result.date + " " + result.time, result.id, false, false);

									i++;
								}
							}
							
						}

					}, 'json');
					

				}).each(function() {

					var select = $(this);
					if (select.val() == "select_cbox")
					{
						var target = $('#' + select.data('target'));
						target.parents('.filter-item').hide();
					}
					
				});


			//<?php endif; ?>


		});