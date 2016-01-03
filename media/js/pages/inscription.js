// jQuery object
$(function() {

	// Gestion des listes déroulantes bootstrap-select(select)
	//$('.form-select').selectpicker({style: 'custom-select'});

	// Focus sur le premier champ au démarrage de la page
	$('#code_identification').focus();
	$('#nom_user').focus();

	// Gestion du formulaire organisme caché
	$('#third-part').hide();

	// Affichage du formulaire de saisie d'un nouvel organisme
	$('#second-part #ref_organ_cbox').change(function() {

		if ($(this).val() === "new") {

			$('#third-part').show(250);
		}
		else {

			$('#third-part').hide(250);
		}
	});



	/***   Gestion des erreurs   ***/

	$('.form-control').each(function() {

		var $helpBlock = $(this).next('.help-block');
		$helpBlock.hide();

		$(this).on('focus', function(event) {

			$(this).removeClass('error');
			$helpBlock.hide();
		});
	});

	

	$('select').each(function() {

		var $helpBlock = $(this).next('.help-block');

		$(this).on('focus', function(event) {

			var elementId = $(this).attr('id');
			if (elementId == 'jour_naiss_user_cbox' || elementId == 'mois_naiss_user_cbox' || elementId == 'annee_naiss_user_cbox')
			{
				$('#help-birthdate').hide();
			}

			$(this).removeClass('error');
			$helpBlock.hide();
		});
	});

	
	$('#help-birthdate').hide();

	/*
	$('.form-control').each(function() {

		$(this).next('.help-block').hide();

		if ($(this).parent().not('#third-part')) {

			$(this).bind({

				focus: function(event) {

					// $(this).removeClass('error');
					$(this).next('.help-block').hide();
				},
				blur : function(event) {

					if ($(this).val() === '') {

						// $(this).addClass('error');
						$(this).next('.help-block').show();
					}
				}
			});
		}
	});


	$('select').each(function() {

		$(this).on('click', function() {

			if ($(this).val() === 'select_cbox') {

				//$(this).siblings('.bootstrap-select').addClass('error');
				$(this).siblings('.help-block').show();
			}
			else {

				//$(this).siblings('.bootstrap-select').removeClass('error');
				$(this).siblings('.help-block').hide();
			}
		});

		if ($(this).val() === 'new') {

			$('.form-control').each(function() {

				if ($(this).parent().is('#third-part')) {

					$(this).bind({

						focus: function(event) {

							//$(this).removeClass('error');
							$(this).next('.help-block').hide();
						},
						blur : function(event) {

							if ($(this).val() === '') {

								//$(this).addClass('error');
								$(this).next('.help-block').show();
							}
						}
					});
				}
			});
		}

	});
	*/



	$('#submit[name=submit_organ]').on('click', function(event) {

		event.preventDefault();
		
		var valid = true;

		var $code = $('#code_identification');
		var $organ = $('#ref_organ_cbox');
		var $nomOrgan = $('#nom_organ');
		var $codePostalOrgan = $('#code_postal_organ');
		var $telOrgan = $('#tel_organ');
		var $email = $('#email-intervenant');

		if ($code.val() === '') {

			$code.addClass('error');
			$code.siblings('.help-block').show();
			valid = false;
		}
		// else {

		// 	$code.removeClass('error');
		// 	$code.next('.help-block').hide();
		// }

		
		if ($organ.val() === 'new') {

			if ($nomOrgan.val() === '') {

				$nomOrgan.addClass('error');
				$nomOrgan.siblings('.help-block').show();
				valid = false;
			}
			// else {

			// 	$email.removeClass('error');
			// 	$email.next('.help-block').hide();
			// }

			if ($codePostalOrgan.val() === '' || isNaN(Number($codePostalOrgan.val())) || String($codePostalOrgan.val()).length != 5) {

				$codePostalOrgan.addClass('error');
				$codePostalOrgan.siblings('.help-block').show();
				valid = false;
			}
			// else {

			// 	$codePostalOrgan.removeClass('error');
			// 	$codePostalOrgan.next('.help-block').hide();
			// }
			
			if ($telOrgan.val() === '' || isNaN(Number($telOrgan.val())) || String($telOrgan.val()).length != 10) {

				$telOrgan.addClass('error');
				$telOrgan.siblings('.help-block').show();
				valid = false;
			}
			// else {

			// 	$telOrgan.removeClass('error');
			// 	$telOrgan.next('.help-block').hide();
			// }
			
		}
		
		var atpos = $email.val().indexOf("@");
		var dotpos = $email.val().lastIndexOf(".");

		if ($email.val() === '' || atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= $email.val().length) {

			$email.addClass('error');
			$email.siblings('.help-block').show();
			valid = false;
		}
		// else {

		// 	$email.removeClass('error');
		// 	$email.next('.help-block').hide();
		// }

		
		$('select').each(function() {

			if ($(this).val() === 'select_cbox') {

				$(this).addClass('error');
				$(this).siblings('.help-block').show();
				valid = false;
			}
			// else {

			// 	$(this).removeClass('error');
			// 	$(this).siblings('.help-block').hide();
			// }
		});
		
		
		if (valid) {

			$('#form-inscript-organ').submit();
		}
		else {
			return false;
		}

		
	});


	

	/*** Soumission du formulaire utilisateur ***/

	$('#submit[name=submit_user]').on('click', function(event) {

		var valid = true;

		var $name = $('#nom_user');
		var $forname = $('#prenom_user');
		var $birthDay = $('#jour_naiss_user_cbox');
		var $birthMonth = $('#mois_naiss_user_cbox');
		var $birthYear = $('#annee_naiss_user_cbox');
		// var $niveau = $('#ref_niveau_cbox');

		if ($name.val() === '') {

			$name.addClass('error');
			$name.siblings('.help-block').show();
			valid = false;
		}

		if ($forname.val() === '') {

			$forname.addClass('error');
			$forname.siblings('.help-block').show();
			valid = false;
		}


		$('select').each(function() {

			if ($(this).val() === 'select_cbox') {

				var elementId = $(this).attr('id');
				if (elementId == 'jour_naiss_user_cbox' || elementId == 'mois_naiss_user_cbox' || elementId == 'annee_naiss_user_cbox')
				{
					$('#help-birthdate').show();
				}

				$(this).addClass('error');
				$(this).siblings('.help-block').show();
				valid = false;
			}
		});
		

		if (valid) {

			$('#form-inscript-user').submit();
		}
		else {
			return false;
		}
		
	});



	/*** Fenêtre de validation du nom dupliqué ***/

	if ($("#name-validation").val() === "false") {

		var messageString = 'Une personne portant le même nom a déjà effectuée un positionnement. S\'il s\'agit bien de vous, cliquez sur "Continuer".<br>Sinon, cliquez sur "Annuler" pour corriger la saisie de vos nom, prénom et date de naissance.';
		
		$.message(messageString, {
			icon: 'alert', 
			buttons: [
				{
					'btnvalue': 'Annuler', 
					'btnclass': 'button-default'
				},
				{
					'btnvalue': 'Continuer', 
					'btnclass': 'button-primary'
				}
			], 
			callback: function(buttonText) {
				
				if (buttonText === 'Continuer') {

					$('#name-validation').val('true');
					$('#form-inscription').submit();
					console.log('submit');
				}
				else {
					$('#name-validation').val('false');
				}
			}
		}, '#modal-message');
	}




	/* Recherche des émails d'intervenant dynamique en ajax */

	//<?php //if (Config::ALLOW_AJAX) : ?>



	/***   Gestion de l'autocompletion dans le champ email formateur   ***/
	/*
	var $request = null; // jQuery XHR object

	var $searchField = $('#email-intervenant');
	var $resultsList = $('#interv-results');

	var selectedIndex = -1;
	var previousValue = $searchField.val();

	var url = $('#form-inscription').attr('action');
	var refOrgan = null;

	var isSelectedEmail = false;


	// On récupère la valeur de la liste des organismes
	$('#ref_organ_cbox').change(function() {

		if ($(this).val() === 'select_cbox' || $(this).val() === 'select_cbox') {

			refOrgan = null;
		}
		else {

			refOrgan = $(this).val();
		}

		$resultsList.css('display', 'none');
		$resultsList.find('li').remove();

		if (isSelectedEmail) {

			$searchField.val('');
		}
	});


	var chooseResult = function($result) {

		isSelectedEmail = true;

		// On change le contenu du champ de recherche et on enregistre le résultat en tant que précédente valeur
		previousValue = $result.children('a').text();
		$searchField.val(previousValue);

		// On cache les résultats
		$resultsList.css('display', 'none');

		// On supprime l'effet de focus
		$result.removeClass('selected');

		// On remet la sélection à zéro
		selectedIndex = -1;

		// Si le résultat a été choisi par le biais d'un clic, alors le focus est perdu, donc on le réattribue
		$searchField.focus();

	};



	$searchField.keyup(function(evt) {


		// On récupére chaque 'div' contenues dans le bloc des résultats
		var $resultsElements = $resultsList.find('li');

		
		// Si la touche pressée est la flèche "haut"
		if (evt.keyCode == 38 && selectedIndex > -1) {

			// On retire la classe de l'élément inférieur et on décrémente la variable "selectedIndex"
			$resultsElements.eq(selectedIndex--).removeClass('selected');

			// Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
			if (selectedIndex > -1) {

				// On applique une classe à l'élément actuellement sélectionné
				$resultsElements.eq(selectedIndex).addClass('selected');
			}
		}


		// Si la touche pressée est la flèche "bas"
		else if (evt.keyCode == 40 && selectedIndex < $resultsElements.length - 1) {

			// On affiche les résultats "au cas où"
			$resultsList.css('display', 'block');
			
			// Cette condition évite une modification de childNodes[-1], qui n'existe pas, bien entendu
			if (selectedIndex > -1) {
				
				$resultsElements.eq(selectedIndex).removeClass('selected');
			}
			
			$resultsElements.eq(++selectedIndex).addClass('selected');
		}


		// Si la touche pressée est la touche "Entrée"
		else if (evt.keyCode == 13) {

			chooseResult($resultsElements.eq(selectedIndex))
		}
		

		// Si le contenu du champ de recherche a changé
		else if ($('#email-intervenant').val() != previousValue) {

			isSelectedEmail = false;

			// On change la valeur précédente par la valeur actuelle
			previousValue = $('#email-intervenant').val();

			// Si on a toujours une requête en cours, on l'arrête			
			if ($request && $request.readyState < 4) {

				$request.abort();
			}
			
			// On stocke la nouvelle requête
			//request = getResult(previousValue);

			$request = $.post(url, {"search_interv": previousValue, "ref_organisme": refOrgan}, function(data) {

				if (data.error) {

					alert(data.error);
				}
				else {

					// On cache le conteneur si on n'a pas de résultats
					if (data.length > 0) {

						$resultsList.css('display', 'block');
						//$resultsList.css('width', $resultsWidth);
					}
					else { 

						$resultsList.css('display', 'none');
					}
					
					// On ne modifie les résultats que si on en a obtenu		
					if (data.length > 0) {
						
						// On vide les anciens résultats
						$resultsList.html('');

						var ulResult = document.createElement('ul');
						$resultsList.append($(ulResult));

						// On parcourt les nouveaux résultats
						for (var i = 0, count = data.length; i < count; i++) {

							// Ajout d'un nouvel élément liste <li>

							var liResult = document.createElement('li');
							$(ulResult).append($(liResult));
							// $(liResult).addClass('result');

							// Ajout d'un lien dans l'élément de liste
							var liResultLink = document.createElement('a');
							$(liResult).append($(liResultLink));
							$(liResultLink).html(data[i]);

							// Le résultat sera choisi s'il est cliqué
							$(liResultLink).click(function(e) {

								chooseResult($(this).parent());
							});
						}
					}
				}

			}, 'json');

			// On remet la sélection à zéro à chaque caractère écrit
			selectedIndex = -1;
		}

		//return false;

	});
	*/

	//<?php //endif; ?>


	


});
