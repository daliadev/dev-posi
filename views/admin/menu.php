
    
    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <div id="administrateur-login">
            <div class="zone-formu">
            	
                <div class="titre-form" id="titre-menu">Administration</div>

                <div id="menu-admin">

				<?php
				
					$authType = ServicesAuth::getAuthenticationRight();

					foreach (Config::$admin_menu as $menu)
					{
						$title = $menu['title'];
						unset($menu['title']);

						echo '<div class="main-menu-title">'.$title.'</div>';
						echo '<hr>';

						for ($i = 0; $i < count($menu); $i++)
						{
							$menuItem = $menu[$i];

							$requiredAuth = explode(",", $menuItem['droits']);

							if ($menuItem['display'] == true && in_array($authType, $requiredAuth))
							{
								echo '<a href="'.SERVER_URL.'admin/'.$menuItem['url_menu'].'">';
								echo '<div class="main-menu-btn">'.$menuItem['label_menu'].'</div>';
								echo '</a>';
							}
						}
					}

				?>

                </div>
            </div>
        </div>
 

        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer_old.php');
        ?>

    </div>
    