<?php

    
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


    //var_dump($response);

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


                    <!-- <div id="R-CCSP"> -->
                        <?php
                        /*
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
                                    
                                    if (!empty($correction['children']) && is_array($correction['children']) && count($correction['children']) > 0)
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
                        */
                        ?>
                    <!-- </div> -->
                    
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

                                    //echo '<p>'.$correction['nom_categorie'].' : <strong class="'.$color.'">'.$percent.'%</strong> ('.$correction['total_correct'].'/'.$correction['total'].')</p>';
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