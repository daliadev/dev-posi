<?php

	$form_url = $response['url'];
?>
	

	<div id="posi-inscript" class="main">
		
		<div class="header">

			<div class="header-wrapper">

				<div class="logo"></div>

				<div class="header-title">
					<h1>Test de positionnement DALIA</h1>
				</div>

			</div>

		</div>


		<div class="content">
			
			<div class="form-header">
				<h2>Introduction</h2>
				<i class="fa fa-chevron-down"></i>
				<div class="clear"></div>
			</div>


			<form class="form-inscript" id="form-intro" name="form_intro" action="<?php echo $form_url; ?>" method="post">
				
				<fieldset>

					<p class="form-text-large">Bonjour,</p>

					<p class="form-text-large">En vue d’établir le parcours de formation le plus adapté à votre niveau et vos objectifs, vous allez effectuer un test de positionnement.</p>
					<p class="form-text-large">Vous allez pour cela répondre à une série de questions en lien avec le domaine professionnel.</p> 
					<p class="form-text-large">Les résultats que vous obtiendrez indiqueront d’une part vos acquis et de l’autre, les compétences à travailler.</p>
					<p class="form-text-large">Lisez bien les consignes et prenez le temps d’observer les documents avant de répondre.</p>

					<p class="orange2-text">Vous avez près de <?php echo $response['nbre_questions']; ?> questions.</p>

					<p class="form-text-large">Bon courage !!</p>

					
					<div id="lecteur-intro"></div>

					<input type="submit" name="submit" class="button-primary action-button" id="submit" value="Continuer" title="Cliquez sur ce bouton pour continuer" />

				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>
		
		<!-- Footer -->
		<?php
			require_once(ROOT.'views/templates/footer.php');
		?>

	</div>


	

	<!-- JQuery -->
	<script src="<?php echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script>
	<script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/flash_detect.js"></script>
	
	<script language="javascript" type="text/javascript">

		var player;

		if (FlashDetect.installed) {

			player = '<object type="application/x-shockwave-flash" data="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="300" height="20" id="dewplayer" name="dewplayer">'; 
			player += '<param name="movie" value="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" />'; 
			player += '<param name="flashvars" value="mp3=<?php echo SERVER_URL; ?>media/mp3/intro.mp3&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
			player += '<param name="wmode" value="transparent" />';
			player += '</object>';
		}
		else {

			player = '<audio id="audioplayer" name="audioplayer" src="<?php echo SERVER_URL; ?>media/mp3/intro.mp3" preload="auto" autoplay controls></audio>';
		}

		document.getElementById("lecteur-intro").innerHTML = player;



		$(function() {
			/*
			if (valid) {

				$('#form-intro').submit();
				//alert('submit ok');   
			}
			else {
				return false;
			}
			*/

		});

	</script>