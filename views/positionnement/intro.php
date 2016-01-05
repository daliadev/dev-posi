<?php

	$form_url = $response['url'];

	//exit();
?>
	


		<div class="content-form-small">
			
			<div class="form-header">
				<h2>Introduction</h2>
				<i class="fa fa-chevron-down"></i>
				<div class="clear"></div>
			</div>


			<form class="form-intro" id="form-intro" name="form_intro" action="<?php echo $form_url; ?>" method="post">
				
				<input type="hidden" id="audio-filename" name="audio-filename" value="<?php ROOT; ?>/media/mp3/intro.mp3" />

				<fieldset>

					<p><strong>Bonjour,</strong></p>

					<p>En vue d’établir le parcours de formation le plus adapté à votre niveau et vos objectifs, vous allez effectuer un test de positionnement.</p>
					<p>Vous allez pour cela répondre à une série de questions en lien avec le domaine professionnel.</p> 
					<p>Les résultats que vous obtiendrez indiqueront d’une part vos acquis et de l’autre, les compétences à travailler.</p>
					<p>Lisez bien les consignes et prenez le temps d’observer les documents avant de répondre.</p>

					<p>Vous avez près de <?php echo $response['nbre_questions']; ?> questions.</p>

					<p><strong>Bon courage !!</strong></p>

					<!-- Audio -->
					<div id="audio">
						
						<!-- Lecteur audio  sous-forme d'îcone html par defaut, sinon lecteur flash -->

						<div id="speaker">
							
							
							
							<button id="speaker-button" type"button">
								<svg id="svg-speaker-bg" version="1.1" viewBox="0 0 48 48" preserveAspectRatio="xMinYMin meet">
									<circle id="speaker-bg" r="24" transform="translate(24, 24) rotate(-90)"></circle>
								</svg>
								<div class="button-icon">
									<span class="ripple"><i id="speaker-icon" class="fa fa-music"></i><!-- <i id="speaker-icon"class="fa fa-headphones"></i> --></span>
									<!-- <span class="ripple"><i id="speaker-icon" class="fa fa-refresh fa-spin"></i></span> -->
									<!-- <i class="fa fa-volume-up"></i> -->
								</div>
								<!--
								<svg version="1.1" class="speaker-svg" id="speaker-svg" viewBox="0 0 40 40" preserveAspectRatio="xMinYMin meet">
									<path d="M23.04,12.754v21.857c0,0.349-0.116,0.65-0.348,0.904s-0.506,0.381-0.822,0.381c-0.317,0-0.592-0.127-0.823-0.381
										l-6.087-6.689h-4.79c-0.317,0-0.591-0.127-0.823-0.383C9.116,28.189,9,27.889,9,27.54v-7.714c0-0.348,0.116-0.649,0.347-0.904
										s0.506-0.382,0.823-0.382h4.79l6.087-6.69c0.231-0.254,0.506-0.381,0.823-0.381c0.316,0,0.591,0.127,0.822,0.381
										C22.924,12.105,23.04,12.406,23.04,12.754z M29.283,20.83c0.518,0.884,0.776,1.835,0.776,2.853s-0.259,1.965-0.776,2.842
										c-0.519,0.877-1.204,1.504-2.057,1.879c-0.122,0.066-0.274,0.1-0.457,0.1c-0.317,0-0.592-0.123-0.823-0.371S25.6,27.58,25.6,27.219
										c0-0.281,0.073-0.52,0.219-0.713c0.146-0.194,0.323-0.361,0.53-0.502s0.414-0.295,0.622-0.463c0.207-0.167,0.384-0.404,0.53-0.713
										c0.146-0.309,0.219-0.69,0.219-1.145c0-0.456-0.073-0.837-0.219-1.146c-0.146-0.308-0.323-0.545-0.53-0.713
										c-0.208-0.167-0.415-0.321-0.622-0.462s-0.384-0.308-0.53-0.502c-0.146-0.194-0.219-0.432-0.219-0.713
										c0-0.361,0.115-0.666,0.347-0.914c0.231-0.248,0.506-0.372,0.823-0.372c0.183,0,0.335,0.034,0.457,0.101
										C28.079,19.324,28.765,19.946,29.283,20.83z M33.187,18.008c1.035,1.734,1.554,3.626,1.554,5.675s-0.519,3.941-1.554,5.674
										c-1.036,1.735-2.407,2.998-4.113,3.787c-0.159,0.067-0.312,0.102-0.457,0.102c-0.329,0-0.61-0.128-0.842-0.383
										c-0.231-0.254-0.347-0.555-0.347-0.904c0-0.521,0.237-0.916,0.713-1.185c0.683-0.388,1.146-0.683,1.39-0.884
										c0.901-0.723,1.605-1.631,2.111-2.723c0.505-1.091,0.759-2.253,0.759-3.485c0-1.232-0.254-2.394-0.759-3.486
										c-0.506-1.091-1.21-1.999-2.111-2.722c-0.244-0.201-0.707-0.495-1.39-0.884c-0.476-0.268-0.713-0.663-0.713-1.185
										c0-0.348,0.115-0.649,0.347-0.904c0.231-0.254,0.506-0.381,0.823-0.381c0.158,0,0.316,0.033,0.476,0.1
										C30.779,15.011,32.15,16.273,33.187,18.008z M37.099,15.195c1.547,2.578,2.321,5.407,2.321,8.488s-0.774,5.91-2.321,8.487
										c-1.548,2.579-3.608,4.477-6.18,5.696c-0.158,0.066-0.316,0.101-0.475,0.101c-0.317,0-0.592-0.128-0.823-0.382
										c-0.231-0.255-0.348-0.556-0.348-0.904c0-0.481,0.238-0.877,0.714-1.185c0.085-0.055,0.222-0.125,0.411-0.211
										c0.188-0.088,0.326-0.158,0.411-0.211c0.561-0.336,1.061-0.677,1.499-1.025c1.499-1.219,2.669-2.738,3.51-4.561
										c0.841-1.82,1.262-3.756,1.262-5.805s-0.421-3.984-1.262-5.806c-0.841-1.821-2.011-3.341-3.51-4.56
										c-0.438-0.348-0.938-0.69-1.499-1.024c-0.085-0.054-0.223-0.124-0.411-0.211c-0.189-0.087-0.326-0.158-0.411-0.211
										c-0.476-0.308-0.714-0.703-0.714-1.186c0-0.348,0.116-0.649,0.348-0.904s0.506-0.382,0.823-0.382c0.158,0,0.316,0.034,0.475,0.101
										C33.49,10.719,35.551,12.617,37.099,15.195z"/>
								</svg>
								-->
							</button>
							
							<!-- 
							<svg id="svg-speaker-progress" version="1.1" viewBox="0 0 48 48" preserveAspectRatio="xMinYMin meet">
								<circle id="speaker-loader" r="24" transform="translate(24, 24) rotate(-90)"></circle>
								<circle id="speaker-progress" r="24" transform="translate(24, 24) rotate(-90)"></circle>
							</svg>
							 -->
						</div>
					</div>

					<button type="submit" name="submit_intro" class="btn btn-primary" id="submit" title="Cliquez sur ce bouton pour continuer">Continuer</button>
					
					<div class="clear"></div>
				</fieldset>

			</form>

		</div>
		 
		<div class="clear"></div>



	

	<!-- JQuery -->
	<!-- <script src="<?php //echo SERVER_URL; ?>media/js/jquery-1.11.2.min.js" type="text/javascript"></script> -->
	
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script> -->
	<!-- <script type="text/javascript" src="<?php //echo SERVER_URL; ?>media/js/flash_detect.js"></script> -->
	
	<script language="javascript" type="text/javascript">
		/*
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

		document.getElementById("audio").innerHTML = player;
		*/


		//$(function() {
			/*
			if (valid) {

				$('#form-intro').submit();
				//alert('submit ok');   
			}
			else {
				return false;
			}
			*/

		//});

	</script>