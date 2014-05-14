
    
    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_admin.php');
        ?>
        
        
        <div id="administrateur-login">
            <div class="zone-formu">
            	
                <div class="titre-form" id="titre-menu">Administration</div>

                <div id="menu-admin">
                   
					
					<!--MENU - 1	-->
					<div id="bt-menu-titre"><?php echo Config::$menu_gestion["titre"];?></div>
					
					<hr>
					<?php
					foreach(Config::$menu_gestion as $menuElement)
					{
						if ($menuElement['type_lien_menu'] == "dynamic") {
					?>		
							<a href="<?php echo SERVER_URL.'admin/'.$menuElement['url_menu'];  ?>">
							<div id="bt-menu-visible"><?php echo $menuElement['label_menu']; ?></div>
							</a>
					<?php 
							}
							elseif ($menuElement['type_lien_menu']  == "static")
							{
							
					?>
							<div id="bt-menu-cache" >
                                <?php echo Config::$menu_gestion['label_menu']; ?>
                            </div>
					<?php } ?>
					<?php 
					}
					?>
					
					
					
					<!--MENU - 2	-->
					<div id="bt-menu-titre"><?php echo Config::$menu_stat["titre"];?></div>
					<hr>
					<?php
					foreach(Config::$menu_stat as $menuElement)
					
					{
						if ($menuElement['type_lien_menu'] == "dynamic") {
					?>		
							 <a href="<?php echo SERVER_URL.'admin/'.$menuElement['url_menu'];  ?>">
							 <div id="bt-menu-visible"><?php echo $menuElement['label_menu']; ?></div>
							 </a>
					<?php 
							}
							elseif ($menuElement['type_lien_menu']  == "static")
							{	
					?>
							<div id="bt-menu-cache" ><?php echo Config::$menu_gestion['label_menu']; ?></div>
					<?php 
							} 
					?>
					<?php 
					}
					?>
					
					
	
                </div>
            </div>
        </div>
 

        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>
    