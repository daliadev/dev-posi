
$(function() { 

	/* Focus au chargement */

	$('.form-control:first').focus();



	/* Focus sur les contrôles mis en rouge */

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

			$(this).removeClass('error');
			$helpBlock.hide();
		});
	});



	/* Enregistrement du formulaire */

	$('#submit[name=save]').on('click', function(event) {

		event.preventDefault();

		var valid = true;

		$('.form-control:not(select, input[type=checkbox], input[type=radio]), input[type=hidden]').each(function() {

			if ($(this).val() === '') {

				$(this).addClass('error');
				$(this).siblings('.help-block').show();
				valid = false;
			}
		});


		$('select:not(:form-selection)').each(function() {

			if ($(this).val() === 'select_cbox') {

				$(this).addClass('error');
				$(this).siblings('.help-block').show();
				valid = false;
			}
		});
		

		if (valid) {

			$('#form-admin').submit();
		}
		else {
			return false;
		}
	}



	/* Gestion de la demande de suppression */

	$('input[name="del"]').on('click', function(event) {

		event.preventDefault();

		if (confirm("Voulez-vous réellement supprimer cette entrée ?"))
		{
			$('input[name="delete"]').val("true");
			$('#form-admin').submit();
		}
	});

});