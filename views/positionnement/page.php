<?php

    $form_url = $response['url'];
    //var_dump($response);
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
                    foreach ($response['reponse'] as $reponse)
                    {
                        echo '<input type="radio" class="radio_posi" name="radio_reponse" value="'.$reponse->getId().'" onclick="check();"> <label> '.$reponse->getIntitule().'</label></br>';
                        
                        if ($reponse->getEstCorrect())
                        {
                            echo '<input type="hidden" name="ref_reponse_correcte" value="'.$reponse->getId().'"">';
                        }
                    } 
                }
                else if ($response['question']->getType() == "champ_saisie")
                {
                    echo '<textarea class="reponse_champ" name="reponse_champ" placeholder="Ecrivez votre réponse ici."></textarea>';
                }
                
                ?>
                
            </div>


            <div id="lecteur">

                <object type="application/x-shockwave-flash" data="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" width="160" height="20" id="dewplayer" name="dewplayer"> 
                <param name="wmode" value="transparent" />
                <param name="movie" value="<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf" /> 
                <param name="flashvars" value="mp3=<?php echo SERVER_URL; ?>uploads/audio/<?php echo $response['question']->getSon(); ?>&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />
                <param name="wmode" value="transparent" />
                </object>

            </div>
            
            <p id="position"></p>
            
            <?php
                $startTimer = microtime(true);
            ?>
            <input type="hidden" name="start_timer" value="<?php echo $startTimer; ?>" />
            
            <div id="submit">
  
                <input type="submit" id="submit_suite" name="submit_suite" class="bt-suivant" value="Suite" />
                
            </div>

        </form>

        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>


    <script type="text/javascript" src="<?php echo SERVER_URL; ?>media/dewplayer/swfobject.js"></script>
    
    <script language="javascript" type="text/javascript">

        function getPlayerPosition() {
            var dewp = document.getElementById("dewplayer");
            if (dewp != null) {
                 return dewp.dewgetpos();
            }
        }
        
        
        $(function() {
            
            $("#image-content-appli img").hide();
            $("#image-content-appli img").fadeIn(500);
            
            $("#submit_suite").prop("disabled", true);
            $(".reponse_champ").prop("readonly", true);
            $(".reponse_champ").attr("placeholder", "Veuillez attendre que le son se termine...");
            
            $(".radio_posi").click(function() {

                if (getPlayerPosition() === 0)
                {
                    //$("#submit_suite").css("visibility", "visible");
                    $("#submit_suite").removeProp("disabled");
                }
                else
                {
                    $(this).attr("checked", false);
                }
            });
            

            $(".reponse_champ").click(function() {

                if (getPlayerPosition() === 0)
                {
                    $(this).removeProp("readonly");
                    //$(this).blur();
                    $(this).attr("placeholder", "Vous pouvez écrire votre réponse.");
                    
                    //$("#submit_suite").css("visibility", "visible");
                    $("#submit_suite").removeProp("disabled");
                }
            });
            
        });

    </script>