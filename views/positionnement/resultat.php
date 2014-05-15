<?php

    $time = $response['temps'];
    $percentGlobal = $response['percent_global'];
    $totalGlobal = $response['total_global'];
    $totalCorrectGlobal = $response['total_correct_global'];

    function getColor($percent)
    {
        $percent = intval($percent);
        
        $color = "gris";

        if ($percent <= 50)
        {
            $color = "rouge";
        }
        else if ($percent > 50 && $percent < 80)
        {
            $color = "orange2";
        }
        else if ($percent >= 80)
        {
            $color = "vert";
        }

        return $color;
    }

?>


    <div id="content">

        <?php
            // Inclusion du header
            require_once(ROOT.'views/templates/header_posi_intro.php');
        ?>

        <div id="administrateur-login">
            <div class="zone-formu">

                <div class="titre-form" id="titre-utili">Résultats</div>

                <div id="txt-intro">Voici vos résultats au test de positionnement. 
                    <p> </p>

                    <?php

                    if (isset($response['errors']) && !empty($response['errors']))
                    {
                        echo '<div id="error">';
                        foreach($response['errors'] as $error)
                        {
                           echo "<p>".$error['message']."</p>";
                        }
                        echo '</div">';
                    }
                    ?>

                    <div id="R-CCSP">
                        <?php
                        foreach ($response['correction'] as $correction)
                        {
                            //var_dump($correction);
                            
                            if ($correction['parent'])
                            {
                                $percent = $correction['percent'];


                                if ($correction['total'] > 0)
                                {
                                    $color = getColor($correction['percent']);

                                    $title = "";
                                    
                                    if (!empty($correction['children']))
                                    {
                                        //$title .= "Dont :";
                                        //$title .= "<ul>";

                                        foreach ($correction['children'] as $child)
                                        {
                                            //var_dump($child);
                                            //$title .= "<li>";
                                            //$title .= $child['nom'].' ('.$child['total_correct'].'/'.$child['total'].')';
                                            //$title .= "</li>";
                                        }
                                        //$title .= "</ul>";
                                    }
                                    
                                    echo '<p>'.$correction['nom_categorie'].' : <strong class="'.$color.'">'.$percent.'%</strong> ('.$correction['total_correct'].'/'.$correction['total'].')</p>';
                                }
                            }
                        }
                        ?>
                    </div>
                    
                    <div>
                        <p>Taux de réussite globale : <strong class="<?php echo getColor($percentGlobal); ?>"><?php echo $percentGlobal; ?> %</strong> (<?php echo $totalCorrectGlobal; ?>/<?php echo $totalGlobal; ?>)</p>
                    </div>
                    <div>
                        <p>Temps total : <strong><?php echo $time; ?></strong></p>
                    </div>

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
            
            $("#R-CCSP").tooltip();
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