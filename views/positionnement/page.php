<?php

    $form_url = $response['url'];

?>


    <div id="posi_content">

        <div id="image-content-appli">
            <img src="<?php echo SERVER_URL; ?>uploads/img/<?php echo $response['question']->getImage(); ?>" />
        </div>

        <div id="txt-content-appli"><?php echo $response['question']->getNumeroOrdre().'. '.$response['question']->getIntitule(); ?></div>

        <form action="<?php echo $form_url; ?>" method="post" id="formulaire" name="formulaire">  
            
            <input type="hidden" name="num_page" value="<?php echo $response['question']->getNumeroOrdre(); ?>" />
            <input type="hidden" name="ref_question" value="<?php echo $response['question']->getId(); ?>" />
            
            
            <div id="reponse-content-appli">

                <?php
                
                if ($response['question']->getType() == "qcm")
                {
                    $j = 0;

                    foreach ($response['reponse'] as $reponse)
                    {
                        echo '<p>';
                            echo '<label for="radio_reponse_'.$j.'"><input type="radio" class="radio_posi" id="radio_reponse_'.$j.'" name="radio_reponse" value="'.$reponse->getId().'"> &nbsp;'.$reponse->getIntitule().'</label></br>';
                        echo '</p>';
                        if ($reponse->getEstCorrect())
                        {
                            echo '<input type="hidden" name="ref_reponse_correcte" value="'.$reponse->getId().'"">';
                        }

                        $j++;
                    } 
                }
                else if ($response['question']->getType() == "champ_saisie")
                {
                    echo '<textarea class="reponse_champ" id="reponse_champ" name="reponse_champ" placeholder="Ecrivez votre réponse ici."></textarea>';
                }
                
                ?>
                
            </div>


            <div id="lecteur"></div>
            
            
            <?php
                $startTimer = microtime(true);
            ?>
            <input type="hidden" name="start_timer" value="<?php echo $startTimer; ?>" />
            
            <div id="submit">
  
                <input id="submit_suite" type="submit" name="submit_suite" class="bt-suivant" style="width:100px;" value="Suite" disabled>
                
            </div>

        </form>

        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>


    <script type="text/javascript" src="<?php echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script>
    <script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/flash_detect.js"></script>
    
    <script language="javascript" type="text/javascript">


        function createPlayer() {

            var player;

            if (FlashDetect.installed) {

                player = '<object id="dewplayer" type="application/x-shockwave-flash" data="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="160" height="20" name="dewplayer">'; 
                player += '<param name="movie" value="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" />'; 
                player += '<param name="flashvars" value="mp3=<?php echo SERVER_URL; ?>uploads/audio/<?php echo $response['question']->getSon(); ?>&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
                player += '<param name="wmode" value="transparent" />';
                player += '</object>';
            }
            else {

                player = '<audio id="audioplayer" name="audioplayer" src="<?php echo SERVER_URL; ?>uploads/audio/<?php echo $response['question']->getSon(); ?>" preload="auto" autoplay controls></audio>';
            }

            document.getElementById("lecteur").innerHTML = player;
        }



        function getPlayerPosition() {

            var player;

            if (FlashDetect.installed) {

                player = document.getElementById("dewplayer");
                if (player != null) {
                    return player.dewgetpos();
                }
                else {
                    return false;
                }
            }
            else {

                player = document.getElementById("audioplayer");
                if (player != null) {
                    return player.duration - player.currentTime;
                }
                else {
                    return false;
                }
            }
        }



        /*
        player = document.getElementById('lecteur');
        player.addEventListener('mousemove', function(e) {
            document.getElementById('position').innerHTML = getPlayerPosition();
        });
        */

        /*
        function getPlayerType() {

            if (FlashDetect.installed) {

                var dewp = document.getElementById("dewplayer");
                if (dewp != null) {
                     return dewp;
                }
            }
            else {
                return
            }
        }

        function updateHTMLPlayer(player) {

           duration = player.duration;    // Durée totale 
           time = player.currentTime; // Temps écoulé 
           //fraction = time / duration;
           //document.getElementById("fraction").innerHTML = fraction;
        }
        */


        $(function() {
            
            createPlayer();

            $("#image-content-appli img").hide();
            $("#image-content-appli img").fadeIn(500);
            
            $("#submit_suite").prop("disabled", true);

            $("#reponse_champ").prop("readonly", true);
            $("#reponse_champ").attr("placeholder", "Veuillez attendre que le son se termine...");
            
            var numChar = 0;


            $(".radio_posi").on("click", function() {

                if (getPlayerPosition() === 0)
                {
                    $("#submit_suite").removeProp("disabled");
                }
                else
                {
                    $(this).attr("checked", false);
                }
            });
            

            $("#reponse_champ").on("click", function() {

                if (getPlayerPosition() === 0)
                {
                    $(this).removeProp("readonly");
                    $(this).attr("placeholder", "Vous pouvez écrire votre réponse.");
                    
                    //$("#submit_suite").removeProp("disabled");
                }
                else
                {
                    $(this).blur();
                }
            });
            
            $("#reponse_champ").on("keydown", function() {

                if (getPlayerPosition() === 0)
                {
                    numChar++;

                    if (numChar >= 2) {

                        $("#submit_suite").removeProp("disabled");
                    }
                }
            });
            
        });

    </script>