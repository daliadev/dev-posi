<!DOCTYPE html>

<html>

<head>
    
    <meta charset="UTF-8">
    <title>Positionnement DALIA</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <link type="image/x-icon" rel="shortcut icon" href="<?php echo SERVER_URL; ?>favicon.ico" />

   <!-- css -->
    <link type="text/css" rel="stylesheet" media="all" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' /> 
<!--     <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/font-awesome.min.css" /> -->
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/reset_new.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/base.css" />
    <link type="text/css" rel="stylesheet" media="screen" href="<?php echo SERVER_URL; ?>media/css/main.css" />

    <!--[if lt IE 9]>
    <script src="<?php echo SERVER_URL; ?>media/js/dist/html5shiv.js"></script>
    <![endif]-->
</head>

<body style="background: none #7b827a;">
    
    <?php
        echo $template_content;
    ?>

</body>

</html>