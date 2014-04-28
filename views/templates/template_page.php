
<!DOCTYPE html>

<html>

<?php
    // Inclusion du bloc <head>
    require_once(ROOT.'views/templates/head.php');
?>


<body>
    
    <?php
        echo $template_content;
    ?>

    
    <!-- Les scripts à cet endroit s'applique à l'ensemble des pages -->

    <script language="javascript" type="text/javascript">
        
        //jQuery object
        $(function() {
            
            //$(document).tooltip();
            
        });

    </script>
	
	

</body>

</html>