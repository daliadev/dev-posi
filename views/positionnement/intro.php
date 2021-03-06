<?php

    $form_url = $response['url'];
?>

    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi_intro.php');
        ?>
    
        

        <div id="utilisateur">
            <div class="zone-formu">

                <div class="titre-form" id="titre-intro">Introduction</div>

                <form id="form-posi" action="<?php echo $form_url; ?>" method="post" id="formulaire" name="formulaire">

                    <div class="form-small">

                        <div id="txt-intro">
                            
                            <p>Bonjour,</p>

                            <p>En vue d’établir le parcours de formation le plus adapté à votre niveau et vos objectifs, vous allez effectuer un test de positionnement.</p>
                            <p>Vous allez pour cela répondre à une série de questions en lien avec le domaine professionnel.</p> 
                            <p>Les résultats que vous obtiendrez indiqueront d’une part vos acquis et de l’autre, les compétences à travailler.</p>
                            <p>Lisez bien les consignes et prenez le temps d’observer les documents avant de répondre.</p>

                            <p class="rouge">Vous avez près de <?php echo $response['nbre_questions']; ?> questions.</p>

                            <p>Bon courage !!</p>

                        </div>
                        
                        <div id="lecteur-intro"></div>
                        <!-- <object type="application/x-shockwave-flash" data="<?php //echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="160" height="20" id="dewplayer" name="dewplayer"> 
                            <param name="wmode" value="transparent" />
                            <param name="movie" value="<?php //echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" /> 
                            <param name="flashvars" value="mp3=<?php //echo SERVER_URL; ?>media/mp3/intro.mp3&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />
                        </object> -->

                        <div id="submit">
                            <input type="submit" value="Commencer" />
                        </div>

                    </div>
                </form>
            </div> 
        </div>

        

        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>


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

    </script>