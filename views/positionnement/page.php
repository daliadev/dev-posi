<?php

    $form_url = $response['url'];

    $imageFile = $response['question']->getImage();
    $audioFile = $response['question']->getSon();
    $videoFile = $response['question']->getVideo();
?>


    <div id="posi_content">


        <?php if (!empty($videoFile)) : ?>

            <div id="image-content-appli">
                <div id="lecteurvideo" class="projekktor"></div>
            </div>
            
        <?php elseif (!empty($imageFile)) : ?>

            <div id="image-content-appli">
                
                <div class="image-loader"></div>
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
                            echo '<label for="radio_reponse_'.$j.'"><input type="radio" class="radio_posi" id="radio_reponse_'.$j.'" name="radio_reponse" value="'.$reponse->getId().'"> &nbsp;'.$reponse->getIntitule().'</label><br />';
                        echo '</p>';
                        if ($reponse->getEstCorrect())
                        {
                            echo '<input type="hidden" name="ref_reponse_correcte" value="'.$reponse->getId().'" />';
                        }

                        $j++;
                    } 
                }
                else if ($response['question']->getType() == "champ_saisie")
                {
                    echo '<textarea class="reponse_champ" id="reponse_champ" name="reponse_champ"></textarea>';
                }
                
                ?>
                
            </div>

            <?php if (empty($videoFile) && !empty($audioFile)) : ?>

                <div id="lecteuraudio"></div>

            <?php endif; ?>
            
            <?php

                $startTimer = microtime(true);
            ?>

            <input type="hidden" name="start_timer" value="<?php echo $startTimer; ?>" />
            
            <div id="submit">
  
                <input id="submit_suite" type="submit" name="submit_suite" class="bt-suivant" style="width:100px;" value="Suite" disabled />
                
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
    <script type="text/javascript" src="<?php echo SERVER_URL; ?>media/js/placeholders.min.js"></script>


    <script type="text/javascript">


        $(function() {
            

            // Le bouton suite est desactiver par défaut.
            $("#submit_suite").prop("disabled", true);

            //$(".radio_posi").prop("disabled", true);

            // S'il y a une champ de réponse, on le désactive et on met un placeholder.
            //$("#reponse_champ").prop("disabled", true);
            //$("#reponse_champ").attr("placeholder", "Veuillez attendre que le son se termine...");

            if ($('.radio_posi') != null) {

                // S'il y a des boutons radio, on les désactive.
                $(".radio_posi").prop("disabled", true);
            }

            if ($('#reponse_champ') != null) {

                // S'il y a une champ de réponse, on le désactive et on met un placeholder.
                $("#reponse_champ").prop("disabled", true);
                $("#reponse_champ").attr("placeholder", "Veuillez attendre que le son se termine...");
            }


            // Récupération des noms des différents médias dans les valeurs assignées aux champs cachés du formulaire.
            var imageFilename = $('#image-filename').val();
            var audioFilename = $('#audio-filename').val();
            var videoFilename = $('#video-filename').val();

            // Si le média possède un nom, une variable correspondant à ce média contient la valeur "vraie".
            var imageActive = imageFilename != '' ? true : false;
            var videoActive = videoFilename != '' ? true : false;
            var audioActive = audioFilename != '' ? true : false;



            // Création d'une variable permettant de savoir si le lecteur video a terminé la lecture du média.
            var isVideoComplete = false;

            // Etat du chargement de l'image
            var isImageLoaded = false;

            // Etat du chargement du lecteur audio
            var isAudioLoaded = false;


            // Timer du chargement de l'image
            var timerImage = null;

            // Timer de selection automatique du champ de saisie
            var timerPlayerComplete = null;


            // Contenu du lecteur audio
            var audioHtml = '';




            /* Fonctions */

            function getPlayerComplete() {

                var mediaPlayer = null;

                if (videoActive) {

                    if (isVideoComplete) {

                        return true;
                    }

                }
                else if (audioActive) {

                    if (FlashDetect.installed) {

                        mediaPlayer = document.getElementById('dewplayer');

                        if (mediaPlayer != null) {
                            
                            if (mediaPlayer.dewgetpos() == 0 && isAudioLoaded)
                            {
                                return true;
                            }
                        }
                    }
                    else {

                        mediaPlayer = document.getElementById('audioplayer');

                        if (mediaPlayer != null) {

                            if ((mediaPlayer.duration - mediaPlayer.currentTime) == 0 && isAudioLoaded) {

                                return true;
                            }
                        }
                    }
                }

                return false;
            }

            
            function checkPlayerComplete() {

                
                //alert ('player complete');

                if (getPlayerComplete()) {

                    clearInterval(timerPlayerComplete);

                    if ($('.radio_posi') != null) {

                        $(".radio_posi").prop("disabled", false);
                    }

                    if ($('#reponse_champ') != null) {

                        $('#reponse_champ').removeProp('disabled');
                        $('#reponse_champ').attr("placeholder", "Vous pouvez écrire votre réponse.");
                        $('#reponse_champ').focus();
                    }
                }
            }
            



            function displayImage(link) {

                var imageBox = new Image();

                imageBox.onload = function() {

                    $('.image-loader').fadeOut(250);
                    $('#image-content-appli').prepend(imageBox);
                    $('#image-content-appli img').hide().fadeIn(1000);
                    isImageLoaded = true;
                };

                imageBox.src = link;
                $('.image-loader').fadeIn(250);
            }


            function displayAudioPlayer() {

                //$(".radio_posi").prop("disabled", true);

                var $audioPlayer = $("#lecteuraudio");

                if ($audioPlayer != null && audioHtml != '') {

                    $audioPlayer.html(audioHtml);
                }

                var dewp = document.getElementById('dewplayer');
                var $playerHtml = $('#audioplayer');
                
                if (dewp != null) {

                    dewp.style.display = 'none';
                    //dewp.dewplay();
                }
                else if ($playerHtml != null) {
                    
                    $('#audioplayer').css('display', 'none');
                    $('#audioplayer').prop('autoplay', true);
                }

                setTimeout(onAudioPlayerLoaded, 500); 
            }


            function onAudioPlayerLoaded() {
                
                var dewp = document.getElementById('dewplayer');
                var $playerHtml = $('#audioplayer');
                
                if (dewp != null) {

                    dewp.style.display = 'block';
                    //dewp.dewplay();
                }
                else if ($playerHtml != null) {
                    
                    $('#audioplayer').css('display', 'block');
                }
                else {
                    //alert('player not found');
                }
                

                isAudioLoaded = true;

                timerPlayerComplete = setInterval(checkPlayerComplete, 500);
            }


            function checkImageLoaded() {

                if (isImageLoaded) {

                    clearInterval(timerImage);

                    if (audioActive) {

                        displayAudioPlayer();
                    }
                    else {

                        $(".radio_posi").prop("disabled", false);
                    }
                } 
            }






            /* Création et instanciation des médias */


            // Chargement de l'image (si il n'y a pas de vidéo) */

            if (imageActive) {

                var imageUrl = '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename;
                
                if (!videoActive) {

                    displayImage(imageUrl);
                }                
            }


            // S'il existe une video on créé le lecteur vidéo, le lecteur audio ne doit pas être créé.
            if (videoActive) {

                // L'image, si elle existe, sert alors de "poster" pour la vidéo.
                var imageUrl = imageFilename ? '<?php echo SERVER_URL.IMG_PATH; ?>' + imageFilename : '';

                // On récupère l'adresse absolue du lecteur vidéo Flash (pour les navigateurs qui ne supportent pas le HTML5).
                var videoPlayerUrl = '<?php echo SERVER_URL; ?>media/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf';

                // Puis l'adresse absolue de la vidéo.
                var videoUrl = '<?php echo SERVER_URL.VIDEO_PATH; ?>' + videoFilename;

                // On génére le lecteur vidéo et on le configure.
                projekktor('#lecteurvideo', {

                        poster: imageUrl,
                        title: 'Lecteur vidéo',
                        playerFlashMP4: videoPlayerUrl,
                        playerFlashMP3: videoPlayerUrl,
                        width: 750,
                        height: 420,
                        controls: true,
                        enableFullscreen: false,
                        autoplay: true,
                        playlist: [{
                            0: {src: videoUrl, type: "video/mp4"}
                        }],
                        plugins: ['display', 'controlbar'],
                        messages: {
                            0: 'Une erreur s\'est produite.',
                            1: 'Vous avez interrompu la lecture de la vidéo.',
                            2: 'La vidéo n\'a pas pu être chargée.',
                            3: 'La vidéo a été interrompue en raison d\'un problème d\'encodage.',
                            4: 'Le média n\'a pas pu être chargé en raison d\'un problème avec le serveur.',
                            5: 'Désolé, le format de la vidéo n\'est pas supporté par votre navigateur.',
                            6: 'Vous devez disposer de la version %{flashver} ou plus du lecteur Flash.',
                            7: 'Aucun média n\'a été trouvé.',
                            8: 'La configuration du média est incompatible !',
                            9: 'Le fichier (%{file}) n\'a pas été trouvé.',
                            10: 'Les paramètres de qualité sont invalide pour %{title}.',
                            11: 'Les paramètres de streaming sont invalides ou incompatible avec %{title}.',
                            12: 'Le paramètrage de la qualité est incompatible pour %{title}.',
                            80: 'Le média requis n\'existe pas ou son contenu est invalide.',
                            97: 'Aucun média n\'a été prévu.',
                            98: 'Les données de la playlist sont invalides !',
                            99: 'Cliquez sur le média pour continuer. ',
                            100: 'Espace réservé.'
                        } 

                    }, function(player) {

                        // on player ready
                        
                        var stateListener = function(state) {

                            switch(state) {
                                    
                                case 'PLAYING':
                                    break;

                                case 'PAUSED':

                                    $('.ppstart').removeClass('inactive');
                                    $('.ppstart').addClass('active');
                                    break;

                                case 'STOPPED':
                                case 'IDLE':
                                case 'COMPLETED':

                                    $(".radio_posi").prop("disabled", false);
                                    
                                    isVideoComplete = true;

                                    checkPlayerComplete();
                                    break;
                            }
                        };

                        player.addListener('state', stateListener);

                        
                        var playerError =  function(data) { 

                            //console.log('error : ' + data);
                            isVideoComplete = true; 

                            $('#lecteurvideo').html('');

                            if (imageActive) {

                                displayImage(imageUrl);
                            }                 
                        };
                        player.addListener('error', playerError);
                        
                    }
                );
            }


            // Sinon, on créé le lecteur audio
            else if (audioActive) {

                var audioHtml = '';

                var playerAudioUrl = '<?php echo SERVER_URL; ?>media/dewplayer/dewplayer-mini.swf';
                var audioUrl = '<?php echo SERVER_URL.AUDIO_PATH; ?>' + audioFilename;

                if (FlashDetect.installed) {
                    
                    audioHtml += '<object id="dewplayer" name="dewplayer" data="' + playerAudioUrl + '" width="160" height="20" type="application/x-shockwave-flash" style="display:block;">'; 
                    audioHtml += '<param name="movie" value="' + playerAudioUrl + '" />'; 
                    //audioHtml += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
                    audioHtml += '<param name="flashvars" value="mp3=' + audioUrl + '&amp;autostart=1&amp;nopointer=1&amp;javascript=on" />';
                    audioHtml += '<param name="wmode" value="transparent" />';
                    audioHtml += '</object>';
                }
                else {
                    
                    audioHtml += '<audio id="audioplayer" name="audioplayer" src="' + audioUrl + '" preload="auto" controls></audio>';
                }
                

                timerImage = setInterval(checkImageLoaded, 500);

                /*
                var $audioPlayer = $("#lecteuraudio");

                if ($audioPlayer != null) {

                    //$audioPlayer.html(audioHtml);
                }
                */
            }
            



            /* Evenements */

            // Sur click d'un des boutons radio
            $(".radio_posi").on("click", function(e) {

                if (getPlayerComplete()) {

                    $("#submit_suite").removeProp("disabled");
                }
                else {

                    $(this).attr("checked", false);
                }
            });
            

            // Sur click dans le champ de réponse s'il existe.
            $("#reponse_champ").on("click", function(e) {

                if (getPlayerComplete()) {

                    //$(this).removeProp("readonly");
                    //$(this).prop("placeholder", "Vous pouvez écrire votre réponse.");
                }
                else {

                    $(this).blur();
                }
            });
            

            // Lorsque l'utilisateur effectue une saisie dans le champ de réponse.
            //var numChar = 0;

            $("#reponse_champ").on("keydown", function(e) {

                // On s'assure que la vidéo ou le son sont terminés
                // et que l'utilisateur a saisi au moins 2 caractères.
                if (getPlayerComplete()) {

                    $(this).attr("placeholder", "");
                    //$(this).removeProp("placeholder");

                    //numChar++;

                    if ($(this).val().length > 1) {

                        $("#submit_suite").removeProp("disabled");
                    }
                    else if ($(this).val().length <= 1) {

                        $("#submit_suite").prop("disabled", true);
                    }
                }
            });
            
        });

    </script>