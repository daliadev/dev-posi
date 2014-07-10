<?php

    /*-------------------- Envoi d'emails ----------------------*/

    $content = "";
    foreach ($response['correction'] as $correction)
    {
        if ($correction['parent'])
        {         
            if ($correction['total'] > 0)
            {
                    $content .= '</br>';
                    $content .= $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].')';
            }
        }
    }
    
    $Destinataire = "";
    foreach (Config::$emails_admin as $email_admin) 
    {
        $Destinataire .=  $email_admin.',';
    }

    $pourqui = "g.billard@educationetformation.fr";
    $Sujet = Config::POSI_NAME;

    $From  = "From:" ;
    $From .= $pourqui ;
    $From .= "\n";
    $From .= "MIME-version: 1.0\n";
    $From .= 'Content-Type: text/html; charset=utf-8'."\n"; 

    
    $message =   '<html><head><title>'.Config::POSI_NAME.'</title></head>';
    $message .=  '<body>';
    $message .=  'Date du positionnement: '.$response['email_infos']['date_posi'].'</br>';
    $message .=  'Organisme: '.$response['email_infos']['nom_organ'].'</br>';
    $message .= '</br>';
    $message .=  'Nom: '.$response['email_infos']['nom_user'].'</br>';
    $message .=  'Prénom: '.$response['email_infos']['prenom_user'].'</br>';
    $message .=  'Email intervenant: '.$response['email_infos']['email_intervenant'].'</br>';
    $message .=  'Temps :'.$response['email_infos']['temps_posi'].'</br>';
    $message .= 'Score globale:'.$response['percent_global'].' %';
    $message .= '</br>';
    $message .=  '<strong>Score:</strong></br> '.$content;
    $message .= '</body>';
    $message .= '</html>';
                         
    mail($Destinataire,$Sujet,$message,$From);


    /*-------------------------------------------------*/


    // Attribut une couleur selon le pourcentage du résultat

    function getColor($percent)
    {
        $percent = intval($percent);
        
        $color = "gris";

        if ($percent < 40)
        {
            $color = "rouge";
        }
        else if ($percent >= 40 && $percent < 60)
        {
            $color = "orange2";
        }
        else if ($percent >= 60 && $percent < 80)
        {
            $color = "jaune";
        }
        else if ($percent >= 80)
        {
            $color = "vert";
        }

        return $color;
    }


    $time = $response['temps'];
    $percentGlobal = $response['percent_global'];
    $totalGlobal = $response['total_global'];
    $totalCorrectGlobal = $response['total_correct_global'];


    // var_dump($response);
    // var_dump($response['errors']);

?>


    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi_intro.php');
        ?>

        <div id="administrateur-login">
            <div class="zone-formu">

                <div class="titre-form" id="titre-utili">Résultats</div>

                <?php

                    if (isset($response['errors']) && !empty($response['errors']))
                    {
                        echo '<div id="zone-erreur">';
                        echo '<ul>';
                        foreach($response['errors'] as $error)
                        {
                            if ($error['type'] == "form_valid" || $error['type'] == "form_empty")
                            {
                                echo '<li>'.$error['message'].'</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    else if (isset($response['success']) && !empty($response['success']))
                    {
                        echo '<div id="zone-success">';
                        echo '<ul>';
                        foreach($response['success'] as $message)
                        {
                            if ($message)
                            {
                                echo '<li>'.$message.'</li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';
                    }

                ?>

                <div id="txt-intro">Voici vos résultats au test de positionnement : 
                    <p> </p>

                    
                    <div>
                        <p>Taux de réussite globale : <strong class="<?php echo getColor($percentGlobal); ?>"><?php echo $percentGlobal; ?>%</strong> (<?php echo $totalCorrectGlobal; ?>/<?php echo $totalGlobal; ?>)</p>
                    </div>
                    
                    <div id="R-CCSP" class="progressbars" style="width:270px;">

                        <?php

                        foreach ($response['correction'] as $correction)
                        {
                            if ($correction['parent'])
                            {
                                $percent = $correction['percent'];
                                if ($percent == 0)
                                {
                                    $percent = 4;
                                }

                                if ($correction['total'] > 0)
                                {
                                    $color = getColor($correction['percent']);

                                    echo '<div class="progressbar">';
                                        echo '<div class="progressbar-title" title="">';
                                            echo $correction['nom_categorie'].' / <strong>'.$correction['percent'].'</strong>% ('.$correction['total_correct'].'/'.$correction['total'].')';
                                            echo '<div class="progressbar-bg">';
                                                echo '<span class="bg-'.$color.'" style="width:'.$percent.'%;"></span>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';

                                }
                            }
                        }

                        ?>

                    </div>

                    <div>
                        <p>Temps passé : <strong><?php echo $time; ?></strong></p>
                    </div>

                    <hr/>
                    
                    <p class="alignleft"><strong>Vous êtes maintenant déconnecté de l'application.</strong></p>

                </div>


            </div> 
        </div>

        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>
        
    </div>



    <script language="javascript" type="text/javascript">
       
        $(function() { 
            
            //$("#R-CCSP").tooltip();
            /*
            $(".result").tooltip({
                items: "[title]",
                content: function() {
                    var element = $( this );
                    
                    if ( element.is( "[title]" ) ) {
                        var text = element.text();
                        return text;
                    }
                }
            });
            */
        });   

    </script>