<!DOCTYPE html>

<html lang="fr">

<head>

	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title><?php echo $page_title; ?></title>

	<link type="image/x-icon" rel="shortcut icon" href="<?php echo SERVER_URL; ?>favicon.ico" />
	<!-- <link rel="shortcut icon" href="favicon.ico"> -->
	<!-- css -->
	<link type="text/css" rel="stylesheet" media="all" href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' /> 
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/font-awesome.min.css" />
	<!-- <link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/reset_new.css" /> -->
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/bootstrap.min.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/base_new.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/main_new.css" />
	<link type="text/css" rel="stylesheet" media="all" href="<?php echo SERVER_URL; ?>media/css/client-styles.css" />
	
	<!-- css injecté dynamiquement -->
	<?php echo $style_sheets; ?>
	
	<!-- JQuery v1.11.2 -->
	<!--<script src="<?php echo SERVER_URL; ?>media/js/jquery.min.js" type="text/javascript"></script>-->

	<!-- js injecté dynamiquement -->
	<?php echo $script_files; ?>


</head>

<body>

	<!-- Contenu de la page -->
	<div id="posi-main" class="main">
		
		<?php include($header_content); ?>

		<?php echo $template_content; ?>

		<?php include($footer_content); ?>
	
	</div>

	<!-- Balise d'info en cas de blocage de fichiers javascript -->
	<noscript><div id="no-js">Pour un fonctionnement correct du positionnement, activez JavaScript en modifiant les options de votre navigateur</div></noscript>
	
	
	<!-- JQuery v1.11.2 -->
	<script src="<?php echo SERVER_URL; ?>media/js/jquery.min.js" type="text/javascript"></script>
	<!-- Bootstrap v3.0.6 -->
	<script src="<?php echo SERVER_URL; ?>media/js/bootstrap.min.js" type="text/javascript"></script>
	
	<!-- fichier js de fin de page -->
	<?php echo $queue_script_files; ?>

</body>

</html>