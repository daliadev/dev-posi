
	
	<div class="content-form-small" id="admin-menu">

		<div class="form-header">
			<h2>Interface administration</h2>
			<i></i>
			<div class="clear"></div>
		</div>
		
		<!-- <form> -->

			<fieldset>
				
				<div class="fieldset-header" id="titre-organ">
					<i class="fa fa-compass"></i><h2 class="fieldset-title">Menu</h2>
				</div>
				
				<?php
				
					$authType = ServicesAuth::getAuthenticationRight();

					$j = 0;

					foreach (Config::$admin_menu as $menu)
					{
						$title = $menu['title'];
						unset($menu['title']);

						echo '<div class="admin-menu-title">'.$title.'</div>';
						echo '<hr>';

						for ($i = 0; $i < count($menu); $i++)
						{
							$menuItem = $menu[$i];

							$requiredAuth = explode(",", $menuItem['droits']);

							if ($menuItem['display'] == true && in_array($authType, $requiredAuth))
							{
								
								//echo '<button type="button" name="submit_menu_'.$j.'" class="btn btn-primary" id="submit-menu-'.$j.'">';
									echo '<a class="menu-link" href="'.SERVER_URL.'admin/'.$menuItem['url_menu'].'">'.$menuItem['label_menu'].'</a>';
								//echo '</button>';
								
								$j++;
								//echo '<a href="'.SERVER_URL.'admin/'.$menuItem['url_menu'].'">';
								//echo '<div class="main-menu-btn">'.$menuItem['label_menu'].'</div>';
								//echo '</a>';
							}
						}
					}

				?>

			</fieldset>

		<!-- </form> -->

	</div>
	