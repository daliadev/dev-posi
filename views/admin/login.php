<?php

// Initialisation par défaut des valeurs du formulaire
$formData = array();
$formData['nom_admin'] = "";
$formData['pass_admin'] = "";

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{
	foreach($response['form_data'] as $key => $value)
	{
		$formData[$key] = $value;
	}
}

$form_url = $response['url'];

//echo Config::hashPassword("0000");

?>


	<div class="content-form-small">
			
		<div class="form-header">
			<h2>Interface administration</h2>
			<i></i>
			<div class="clear"></div>
		</div>

		<form class="form-login" id="form-login" name="form_login" action="<?php echo $form_url; ?>" method="post">

			<fieldset>
				
				<div class="fieldset-header" id="titre-organ">
					<i class="fa fa-cube"></i> <h2 class="fieldset-title"> Connexion</h2>
				</div>

				<?php
				
					if (isset($response['errors']) && !empty($response['errors']))
					{ 
						echo '<div class="alert alert-danger">';
							echo '<ul>';
							foreach($response['errors'] as $error)
							{
								if ($error['type'] == "form_valid" || $error['type'] == "form_empty")
								{
									echo '<li>'.$error['message'].'</li>';

								}
							}
							echo '</ul>';
						echo '</div>';
					}
				?>

				<div class="form-group">
					<label for="Login">Login</label>
					<input type="text" name="login" class="form-control" id="login" value="" required />
				</div>

				<div class="form-group">
					<label for="password">Mot de passe</label>
					<input type="password" name="password" class="form-control" id="password" value="" required />
				</div>
				
				<!-- <div id="submit">
					<input type="submit" value="Envoyer" onclick="verifAdmin();" />
				</div> -->

				<button type="submit" name="submit_login" class="btn btn-primary" id="submit">Envoyer</button>

			</div>

		</form>

	</div>
