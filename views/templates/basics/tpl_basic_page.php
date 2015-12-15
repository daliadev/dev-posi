<!DOCTYPE html>

<html lang="fr" class="no-js">

<head>

	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title>Positionnement DALIA</title>

	<link type="image/x-icon" rel="shortcut icon" href="<?php echo SERVER_URL; ?>favicon.ico" />
	<!-- <link rel="shortcut icon" href="favicon.ico"> -->
	<!-- css -->
	<link type="text/css" rel="stylesheet" media="all" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' /> 
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/font-awesome.min.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/reset_new.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/base.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/main.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/client.css" />
	
	<!-- css injecté dynamiquement -->
	<?php echo $style_sheets; ?>
	
	<!-- JQuery v1.11.2 -->
	<script src="<?php echo SERVER_URL; ?>media/js/jquery.min.js" type="text/javascript"></script>

	<!-- js injecté dynamiquement -->
	<?php echo $script_files; ?>

	<!--[if lt IE 9]>
	<script src="<?php echo SERVER_URL; ?>media/js/dist/html5shiv.js"></script>
	<![endif]-->

</head>

<body>

	<!-- Contenu de la page -->
	<div id="posi-inscript" class="main">

		<?php echo $template_content; ?>
	
	</div>

	<!-- Balise d'info en cas de blocage de fichiers javascript -->
	<noscript><div id="no-js">Pour un fonctionnement correct du positionnement, activez JavaScript en modifiant les options de votre navigateur</div></noscript>
	
	<!-- fichier js de fin de page -->
	<?php echo $queue_script_files; ?>

</body>

</html>