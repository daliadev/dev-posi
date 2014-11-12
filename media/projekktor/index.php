<!DOCTYPE HTML>
<html>
<head>
    <title>Projekktor - simply mighty video</title>
    <style type="text/css">
    body { background-color: #fdfdfd; padding: 0 20px; color:#000; font: 13px/18px monospace; width: 800px;}
    a { color: #360; }
    h3 { padding-top: 20px; }
    </style>

    <!-- Load player theme -->
    <link rel="stylesheet" href="themes/maccaco/projekktor.style.css" type="text/css" media="screen" />

    <!-- Load jquery -->
    <script type="text/javascript" src="jquery-1.9.1.min.js"></script>

    <!-- load projekktor -->
    <script type="text/javascript" src="projekktor-1.3.09.min.js"></script>

</head>
<body>
    
    <form action="index.php" method="POST">

    <div id="player_a" class="projekktor"></div>

    <div><input type="submit" value="Suite" id="submit_suite"></div>
    
    <div id="progress"></div>
    <!-- <div id="duration"></div> -->
    <div id="playerstate"></div
    </form>

    <script type="text/javascript">
    $(document).ready(function() {
        
        $("#submit_suite").prop("disabled", true);

        projekktor('#player_a', {
            poster: 'media/intro.png',
            title: 'this is projekktor',
            playerFlashMP4: 'swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
            playerFlashMP3: 'swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
            width: 750,
            height: 420,
            controls: false,
            enableFullscreen: false,
            autoplay: true,
            playlist: [
                {
                0: {src: "media/video_faftt_finale.mp4", type: "video/mp4"}
                }
            ]
        }, 
        function(player) {

            //var progress;
            //$('#progress').html(player.getLoadProgress() + "%");

            var stateListener = function(state) {

                $('#playerstate').html(state);

                switch(state) {
                    case 'PLAYING':

                        //progress = player.getLoadProgress(0);
                        //$("#loaderp").html(progress);
                        //$("#submit_suite").prop("disabled", true);
                        //var duration = player.getDuration();

                        //$("#duration").html(duration);
                        //$('#isplaying').addClass('on').removeClass('off');
                        /*
                        if (player.getPosition() === player.getDuration()) {

                            $("#submit_suite").removeProp("disabled");
                        }
                        else
                        {
                            
                        }
                        */
                        break;
                    case 'PAUSED':
                    case 'STOPPED':
                        if (player.getPosition() === player.getDuration()) {
                            $("#submit_suite").removeProp("disabled");
                        }
                        //$('#isplaying').addClass('off').removeClass('on');
                        
                        break;
                }
            };

            // on player ready
            player.addListener('state', stateListener);



            var progressListener = function(value) {
                $('#progress').html( Math.round(value) + "%" )
            }

            player.addListener('progress', progressListener);

        }); 
    });
    </script>


</body>
</html>