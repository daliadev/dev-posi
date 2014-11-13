<?php

    $form_url = $response['url'];

    $imageFile = $response['question']->getImage();
    $audioFile = $response['question']->getSon();
    $videoFile = $response['question']->getVideo();
?>


    <div id="posi_content">
        
        <?php if (!empty($videoFile)) : ?>

            <div id="lecteur-video" class="projekktor"></div>

        <?php elseif (!empty($imageFile)) : ?>

            <div id="image-content-appli">
                <img src="<?php echo SERVER_URL.IMG_PATH.$response['question']->getImage(); ?>" />
            </div>

        <?php else : ?>
            
            <div id="image-content-appli"></div>

        <?php endif; ?>


        <div id="txt-content-appli"><?php echo $response['question']->getNumeroOrdre().'. '.$response['question']->getIntitule(); ?></div>

        <form action="<?php echo $form_url; ?>" method="post" id="formulaire" name="formulaire">
            
            <input type="hidden" name="num_page" value="<?php echo $response['question']->getNumeroOrdre(); ?>" />
            <input type="hidden" name="ref_question" value="<?php echo $response['question']->getId(); ?>" />
            
            <input type="hidden" id="image-filename" name="image-filename" value="<?php echo $imageFile; ?>" />
            <input type="hidden" id="audio-filename" name="audio-filename" value="<?php echo $audioFile; ?>" />
            <input type="hidden" id="video-filename" name="video-filename" value="<?php echo $videoFile; ?>" />


            
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
                            echo '<input type="hidden" name="ref_reponse_correcte" value="'.$reponse->getId().'">';
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

            <?php if (empty($videoFile) && !empty($audioFile)) : ?>

                <div id="lecteur-audio"></div>

            <?php endif; ?>
            
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
    <script type="text/javascript" src="<?php echo SERVER_URL; ?>media/projekktor/projekktor-1.3.09.min.js"></script>
    
    <script language="javascript" type="text/javascript">

        /*
        var imageFilename = document.getElementById('image-filename').value;
        var audioFilename = document.getElementById('audio-filename').value;
        var videoFilename = document.getElementById('video-filename').value;

        var audioActive = null;
        var videoActive = null;


        function createAudioPlayer() {

            var player = '';

            //var audioFilename = document.getElementById('audio-filename').value;

            if (audioFilename) {

                var playerUrl = '<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf';
                var audioUrl = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

                if (FlashDetect.installed) {
                    
                    player += '<object id="dewplayer" name="dewplayer" data="' + playerUrl + '" width="160" height="20" type="application/x-shockwave-flash">'; 
                    player += '<param name="movie" value="' + playerUrl + '" />'; 
                    player += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
                    player += '<param name="wmode" value="transparent" />';
                    player += '</object>';
                }
                else {
                    
                    player += '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" autoplay controls></audio>';
                }
                
                var playerTag = document.getElementById("lecteur-audio");

                if (playerTag != null) {
                    playerTag.innerHTML = player;
                }
            }
        }

        function getAudioPlayerPosition() {

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




        function createVideoPlayer() {

            var videoPlayer = '';

            if (videoFilename) {

                var imageUrl = imageFilename ? '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename : '';

                var videoPlayerUrl = '<?php echo SERVER_URL; ?>media/swf/Jarisplayer/jarisplayer.swf';
                var videoUrl = '<?php echo SERVER_URL.VIDEO_PATH; ?>' + videoFilename;

                projekktor('#lecteur-video', {

                        platforms: ['native', 'flash'],
                        poster: imageUrl,
                        title: 'Lecteur vidéo',
                        playerFlashMP4: videoPlayerUrl,
                        playerFlashMP3: videoPlayerUrl,
                        width: 750,
                        height: 420,
                        controls: false,
                        enableFullscreen: false,
                        autoplay: true,
                        playlist: [{
                                0: {src: videoUrl, type: "video/mp4"}
                            }
                        ]
                    }, 
                    function(player) {

                        var stateListener = function(state) {

                            $('#playerstate').html(state);

                            switch(state) {

                                case 'PLAYING':
                                    break;

                                case 'PAUSED':

                                case 'STOPPED':

                                    if (player.getPosition() === player.getDuration()) {
                                        $("#submit_suite").removeProp("disabled");
                                    }
                                    break;
                            }
                        };

                        // on player ready
                        player.addListener('state', stateListener);
                    }
                );
            }
        }
        */





        (function($) {
            

            // On fait apparaitre l'image quand elle est chargée.
            //$("#image-content-appli img").hide();
            //$("#image-content-appli img").fadeIn(500);
            

            // Le bouton suite est desactiver par défaut.
            $("#submit_suite").prop("disabled", true);

            // S'il y a une champ de réponse, on le désactive.
            $("#reponse_champ").prop("readonly", true);
            $("#reponse_champ").attr("placeholder", "Veuillez attendre que le son se termine...");


            // Récupération des noms des différents médias dans les valeurs assignées aux champs cachés du formulaire.
            var imageFilename = $('#image-filename').val();
            var audioFilename = $('#audio-filename').val();
            var videoFilename = $('#video-filename').val();

            // Si le média possède un nom, une variable correspondant à ce média contient la valeur "vraie".
            var audioActive = audioFilename != '' ? true : false;
            var videoActive = videoFilename != '' ? true : false;

            // Les tags des lecteurs vidéo et audio qui ne sont pas encore créés sont vides par défaut .
            var $audioPlayer = null;
            var $videoPlayer = null;




            /* Création et instanciation des lecteurs médias */

            // S'il existe une video on créé le lecteur vidéo, le lecteur audio ne doit pas être créé
            // L'image sert alors de "poster" pour la vidéo
            if (videoActive) {

                projekktor('#player_a', {

                        poster: 'media/intro.png',
                        title: 'this is projekktor',
                        playerFlashMP4: 'swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
                        playerFlashMP3: 'swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
                        width: 750,
                        height: 420,
                        controls: false,
                        enableFullscreen: false,
                        autoplay: false,
                        playlist: [{
                                0: {src: "media/video_faftt_finale.mp4", type: "video/mp4"},
                            }
                        ],
                        plugins: ['display', 'controlbar']  

                    }, function(player) {

                        // on player ready
                        
                        var stateListener = function(state) {

                            switch(state) {

                                case 'PLAYING':
                                    //$('#playerstate').html('PLAYING');
                                    break;

                                case 'PAUSED':
                                    $('.ppstart').removeClass('inactive');
                                    $('.ppstart').addClass('active');
                                    //$('#playerstate').html('PAUSED');
                                    break;

                                case 'STOPPED':
                                    
                                    //$('#playerstate').html('STOPPED');
                                    //break;
                                    
                                case 'IDLE':
                                    
                                    //$('#playerstate').html('IDLE');
                                    //break;
                                    
                                case 'COMPLETED':

                                    $("#submit_suite").removeProp("disabled");

                                    //$('#playerstate').html('COMPLETED');
                                    break;
                            }
                        };

                        player.addListener('state', stateListener);
                        
                    }
                );

                $videoPlayer = $('#lecteur-video');
            }


            // Sinon, on créé le lecteur audio
            else if (audioActive) {

                var audioHtml = '';

                var playerAudioUrl = '<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf';
                var audioUrl = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

                if (FlashDetect.installed) {
                    
                    audioHtml += '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash">'; 
                    audioHtml += '<param name="movie" value="' + playerAudioUrl + '" />'; 
                    audioHtml += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
                    audioHtml += '<param name="wmode" value="transparent" />';
                    audioHtml += '</object>';
                }
                else {
                    
                    audioHtml += '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" autoplay controls></audio>';
                }
                
                $audioPlayer = $("#lecteur-audio");

                if ($audioPlayer != null) {

                    $audioPlayer.html(audioPlayer);
                }
            }
            

            
            
            
            /* Evenements */

            // Sur click d'un des boutons radio
            $(".radio_posi").on("click", function() {

                if (getAudioPlayerPosition() === 0)
                {
                    $("#submit_suite").removeProp("disabled");
                }
                else
                {
                    $(this).attr("checked", false);
                }
            });
            

            // Sur click dans le champ de réponse s'il existe.
            $("#reponse_champ").on("click", function() {

                if (getAudioPlayerPosition() === 0)
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
            

            // Lorsque l'utilisateur éffectue une saisie dans le champ de réponse.
            var numChar = 0;

            $("#reponse_champ").on("keydown", function() {

                // On s'assure que la vidéo ou le son sont terminés
                // et que l'utilisateur a saisi au moins 2 caractères.
                if (getAudioPlayerPosition() === 0)
                {
                    numChar++;

                    if (numChar >= 2) {

                        $("#submit_suite").removeProp("disabled");
                    }
                }
            });
            
        })(jQuery);

    </script>