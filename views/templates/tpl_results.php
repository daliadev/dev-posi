<!DOCTYPE html>

<html>

<head>
    
    <meta charset="UTF-8">
    <title>Positionnement DALIA</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo SERVER_URL; ?>favicon.ico" />

    <link type="text/css" rel="stylesheet" href="<?php echo SERVER_URL; ?>media/css/reset.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/layout.css" />
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/480.css" />
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