<?php


// Initialisation par défaut des valeurs du formulaire
/*$formData = array();
$formData['ref_organ_cbox'] = "";
$formData['ref_organ'] = "";
$formData['ref_user_cbox'] = "";
$formData['ref_user'] = "";
$formData['ref_session_cbox'] = "";
$formData['ref_session'] = "";*/


if (isset($response['form_data']) && !empty($response['form_data']))
{   
    foreach($response['form_data'] as $key => $value)
    {
        if (is_array($response['form_data'][$key]))
        {
            for ($i = 0; $i < count($response['form_data'][$key]); $i++)
            {
                $formData[$key][$i] = $response['form_data'][$key][$i];
            }
        }
        else 
        {
            $formData[$key] = $value;
        }
    }
}


//$form_url = $response['url'];



if (Config::DEBUG_MODE)
{
    echo "\$response = ";
    var_dump($response);
}


?>



    <div id="content-large">
        <?php if (ServicesAuth::getAuthenticationRight() == "admin") : ?>
        <a href="<?php echo SERVER_URL; ?>admin/menu"><div class="retour-menu">Retour menu</div></a>

        <div style="clear:both;"></div>
        <?php endif; ?>
        
        <!-- Header -->
        <div id="titre-admin-h2-stat">Statistique du positionnement</div>




        <?php

        if (isset($response['errors']) && !empty($response['errors']))
        {
            echo '<div id="zone-erreur">';
            foreach($response['errors'] as $error)
            {
                if ($error['type'] == "form_empty" || $error['type'] == "form_data")
                {
                    echo '<div class="bt-sup">'.$error['message']."</div>";
                }    
                else
                {
                    echo '<p>'.$error['message'].'<p>';
                }
            }
            echo '</div>';
        }
        ?>

<div id="select-posi">

                <div class="zone-liste-restitution">

                    <div id="titre-question-h3"><strong>Statistique total du positionnement:</strong></div>

                    
						<p>Nombre de positionnement: <strong>48</strong></p>
						<p>Nombre de personne positionnées: <strong>46</strong></p>
						<p>Temps moyen: <strong>17 min</strong></p>
						<p>Temps total: <strong>15h25</strong></p>

                  
                </div>
            </div>

        <form action="" method="post" name="formu_admin_com_act" enctype="multipart/form-data">

            <div id="select-posi">

                <div class="zone-liste-restitution">

                    <div id="titre-question-h3"><strong>Sélection d'un organisme</strong></div></br>

                    <div class="combo-box" id="combo-organ">
                        <label for="ref_organ_cbox">Organisme :</label><br/>
                        <select name="ref_organ_cbox" id="ref_organ_cbox" class="ajax-list" data-target="ref_user_cbox" data-url="<?php echo $form_url; ?>" data-sort="user">
                            <option class="organ-option" value="select_cbox">---</option>
                          
                        </select>
                    </div>

                    &nbsp;

                    <?php
                    //if (isset($response['utilisateurs']) && !empty($response['utilisateurs'])) :
                    ?>
                        <div class="combo-box" id="combo-user">
                            <label for="ref_user_cbox">Utilisateur :</label><br/>
                            <select name="ref_user_cbox" id="ref_user_cbox" class="ajax-list" data-target="ref_session_cbox" data-url="<?php echo $form_url; ?>" data-sort="session">
                                <option value="select_cbox">---</option>

                              

                            </select>
                        </div>
                    
                    
                      
                 
                    
                   

                    <input type="submit" value="Valider" id="submit-posi" class="bt-admin-menu-ajout2" />
                </div>
            </div>

        <!-- </form> -->




            <div id="infos-posi">

                <ul>
                    <li><a href="#infos">1 - Statistique organisme</a></li>
                    <li><a href="#stats">2 - liste des utilisateurs</a></li>
                </ul>

                <div id="infos" class="zone-liste-restitution">

                   <p>Positionnement de l'organisme: <strong>24</strong></p>
				   <p>Temps total: <strong>8h16</strong></p>
				   <p>Temps moyen: <strong>13 min</strong></p>
				   <p>Moyenne global de l'organisme: <strong>68 %</strong></p>

                </div>


                <div id="stats" class="zone-liste-restitution">

                   
                    
                    <div id="statistiques">

                       
                            <table width="100%" border="1" id="table-resultats" class="tablesorter">   
                            <th class="header" >Date positionnement</th>
							<th class="header" >Nom</th>
							<th class="header">Prenom</th>
							<th class="header">Naissance</th>
							<th class="header">Score Global</th>
							<th class="header">Temps (h:m:s)</th>
							<tr style="background-color:#FFF6EA;">
								<td align="center">15/05/2014</td>
								<td align="center">Billard</td>
								<td align="center">gregory</td>
								<td align="center">06/03/1985</td>
								<td align="center">85 %</td>
								<td align="center">00:20:18</td>
							</tr>
							<tr style="background-color:#FCE7CA;">
								<td align="center">12/05/2014</td>
								<td align="center">Beurion</td>
								<td align="center">nicolas</td>
								<td align="center">12/06/1975</td>
								<td align="center">71 %</td>
								<td align="center">00:14:52</td>
							</tr>
							<tr style="background-color:#FFF6EA;">
								<td align="center">11/05/2014</td>
								<td align="center">Martin</td>
								<td align="center">eric</td>
								<td align="center">10/12/1958</td>
								<td align="center">65%</td>
								<td align="center">00:31:17</td>
							</tr>
							
                            
                             </table>
                       

                    </div>

                </div>

                


               

            </div>

        </form>
        
        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>
    



    <script language="javascript" type="text/javascript">
       
        $(function() { 
            
            $("#infos-posi").tabs();

            $("#infos-posi").tooltip();

            $("#table-resultats").tablesorter();
            


            <?php if (Config::ALLOW_AJAX) : ?>

                $('#submit-posi').prop('disabled', true);


                /* Listes dynamiques en ajax */
               
                $('.ajax-list').change(function(event) {
                    
                    if ($(this).attr('id') == 'ref_session_cbox')
                    {
                        $('#submit-posi').removeProp('disabled');
                    }
                    else{

                        $('#submit-posi').prop('disabled', true);
                    }
                    
                    var select = $(this);
                    var target = '#' + select.data('target');
                    var url = select.data('url');
                    var sortOf = select.data('sort');
                    
                    var refOrgan = null;
                    var refUser = null;

                    if (sortOf === "user") {

                        $("#ref_session_cbox").parents('.combo-box').hide();
                        //$('#submit-posi').prop('disabled', true);

                        refOrgan = $("#ref_organ_cbox").val();
                    }
                    else if (sortOf === "session") {

                        $('#submit-posi').removeProp('disabled');

                        $('.organ-option').each(function() {
                            var option = $(this)[0];
                            
                            if ($(option).prop('selected')) {

                                refOrgan = $(option).val();
                            }
                        });

                        refUser = $('#ref_user_cbox').val();
                    }
                    

                    $.post(url, {"ref_organ":refOrgan,"ref_user":refUser,"sort":sortOf}, function(data) {
                        
                        if (data.error) {

                            alert(data.error);
                        }
                        else {

                            $(target).parents('.combo-box').show();
                            var $target = $(target).get(0);
                            $target.options.length = 1;
                            

                            if (data.results.utilisateur) {
                                
                                var i = 1;
                                for (var prop in data.results.utilisateur) {
                                
                                    var result = data.results.utilisateur[prop];

                                    $target.options[i] = new Option(result.nom_user + " " + result.prenom_user, result.id_user, false, false);

                                    i++;
                                }
                            }
                            else if (data.results.session) {

                                var i = 1;
                                for (var prop in data.results.session) {
                                
                                    var result = data.results.session[prop];

                                    $target.options[i] = new Option(result.date + " " + result.time, result.id, false, false);

                                    i++;
                                }

                                //$('#submit-posi').removeProp('disabled');
                            }
                            
                            
                        }

                    }, 'json');
                    

                }).each(function() {

                    var select = $(this);
                    if (select.val() == "select_cbox")
                    {
                        var target = $('#' + select.data('target'));
                        target.parents('.combo-box').hide();
                    }
                    
                });

            <?php endif; ?>
            
        })(jQuery);

    </script>