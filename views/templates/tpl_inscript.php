<!DOCTYPE html>

<html>

<head>
    
    <meta charset="UTF-8">
    <title>Positionnement DALIA</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo SERVER_URL; ?>favicon.ico" />

    <!-- css -->
    <link type="text/css" rel="stylesheet" media="all" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' /> 
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/font-awesome.min.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/reset_new.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/base.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/main.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/client.css" />

    <!--[if lt IE 9]>
    <script src="<?php echo SERVER_URL; ?>media/js/dist/html5shiv.js"></script>
    <![endif]-->
    
</head>


<body>
    
    <?php
        echo $template_content;
    ?>
    
    <noscript><div id="no-js">Pour un fonctionnement correct du positionnement, activez JavaScript en modifiant les options de votre navigateur</div></noscript>

</body>

</html>