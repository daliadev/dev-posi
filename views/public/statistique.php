<?php


// Initialisation par défaut des valeurs du formulaire

$formData = array();

// S'il y a des valeurs déjà existantes pour le formulaire, on remplace les valeurs par défaut par ces valeurs
if (isset($response['form_data']) && !empty($response['form_data']))
{      
    foreach($response['form_data'] as $key => $value)
    {
        if (is_array($response['form_data'][$key]) && count($response['form_data'][$key]) > 0)
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


$form_url = $response['url'];



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
        <div id="titre-admin-h2">Statistiques du positionnement</div>


        <div id="main-form">

            <form id="form-posi" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">

                <!-- <input type="hidden" name="mode" value="<?php echo $formData['mode']; ?>" /> -->
  
                <div class="zone-formu2">

                    <!-- <div id="titre-question-h3"><strong>Statistique total du positionnement:</strong></div> -->

                    <div id="bloc-stat-global" class="form-full">

                        <fieldset>
                                
                            <legend>Statistiques globales du positionnement :</legend>

                            <p><strong>Filtres : </strong></p>

                            <div class="input" style="width:120px; display:inline-block;">
                                <label for="date_debut">Date de début : </label>
                                <input type="text" name="date_debut" id="date_debut" class="search-date" style="width:120px;" title="Veuillez entrer la date de début" value="<?php //echo $formData['date_naiss_user']; ?>">
                            </div>

                            <div class="input" style="width:120px; display:inline-block;">
                                <label for="date_fin">Date de fin : </label>
                                <input type="text" name="date_fin" id="date_fin" class="search-date" style="width:120px;" title="Veuillez entrer la date de fin" value="<?php //echo $formData['date_naiss_user']; ?>">
                            </div>

                            <input type="submit" value="Envoyer" name="valid_date">

        					<!-- <select>
        						<option>janvier 2014
        						<option>Fevrier 2014
        						<option>Mars 2014
        						<option>Avril 2014
        						<option>Mai 2014
        					</select>	
        					au
        					<select>
        						<option>janvier 2014
        						<option>Fevrier 2014
        						<option>Mars 2014
        						<option>Avril 2014
        						<option>Mai 2014
        					</select>
        					<input type="submit" value="Valider" id="submit-posi" class="bt-admin-menu-ajout2" />
        					</br> -->

        					<hr>


                            <div class="stats-detail">
        
                                <div class="bloc-stat">
        						  Nombre de positionnement <strong><?php echo $response['stats']['nbre_sessions']; ?></strong>
                                </div>
        						
                                <div class="bloc-stat">
                                    Nombre d'utilisateurs positionnés <strong><?php echo $response['stats']['nbre_users']; ?></strong>
        						</div>
                                
                                <div class="bloc-stat">
                                    Taux de réussite moyen <strong>0 %</strong>
                                </div>


                                <!-- <div style="clear:both;"></div> -->


                                <div class="bloc-stat">
                                    Temps de passation moyen : <strong style="font-size:15px;"><?php echo $response['stats']['moyenne_temps_session']; ?></strong>
        						</div>

                                <div class="bloc-stat">
                                    Temps total : <strong style="font-size:14px;"><?php echo $response['stats']['temps_total']; ?></strong></p>
        						</div>

                                <div class="bloc-stat last">
                                    Age moyen des utilisateurs : <strong>26 ans</strong></p>
                                </div>

                                <div style="clear:both;"></div>

                            </div>
                            
                            <div class="stats-detail">
        						<p>Nombre de candidats réparti par Niveau de formation : 
        							<ul>
                                        <?php
                                        for ($i = 0; $i < count($response['stats']['niveaux']); $i++)
                                        {
                                            echo '<li title="'.$response['stats']['niveaux'][$i]['descript_niveau'].'">'.$response['stats']['niveaux'][$i]['nom_niveau'].' : <strong> '.$response['stats']['niveaux'][$i]['nbre_users'].'</strong></li>';
                                        }
                                        ?>
            								<!-- <li>Niveau VI et Vbis : abandon CAP - BEP - 3e : <strong> 7</strong></li>
            								<li>Niveau V : CAP - BEP - 2e cycle : <strong> 9</strong></li>
            								<li>Niveau IV : Bac : <strong> 8</strong></li>
            								<li>Niveau III : Bac+2 : <strong> 6</strong></li>
            								<li>Niveau II : Bac+3, bac+4 : <strong> 10</strong></li>
            								<li>Niveau I : Bac+5 et plus : <strong> 6</strong></li> -->
        							</ul>
        						</p>
        					</div>	
        					
                            <div class="stats-detail">	
        						<p>Score moyen par compétence :</p>
        							<ul>
        								<li>Oral : <strong> 68 %</strong></li>
        								<li>Ecrit : <strong> 100 %</strong></li>
        								<li>Calcul: <strong> 60%</strong></li>
        								<li>Espace temps : <strong> 85%</strong></li>
        								<li>Informatique : <strong> 48%</strong></li>
        							</ul>
        						<hr>
        						
        						<p>Score moyen global: <strong>68%</strong></p>
        					</div>

                        <fieldset>
                    </div>
                </div>

            
                
                <div class="zone-formu2">

                    <div id="select-organ" class="form-full">
                        
                        <fieldset>
                                
                            <legend>Statistiques par organisme</legend>
                        <!-- <div class="zone-liste-restitution"> -->

                            <!-- <div id="titre-question-h3"><strong>Statistique d'un organisme</strong></div></br> -->
                            

                            <select name="ref_organ_cbox" id="ref_organ_cbox" style="margin: 0px 10px 3px 10px;">
                                <option value="select_cbox">---</option>

                                <?php 
                                /*
                                foreach($response['question'] as $question)
                                {
                                    $selected = "";
                                    if (!empty($formData['ref_question']) && $formData['ref_question'] == $question->getId())
                                    {
                                        $selected = "selected";
                                    }
                                    echo '<option value="'.$question->getId().'" '.$selected.'>Question '.$question->getNumeroOrdre().'</option>';
                                }
                                */
                                ?>

                            </select> &nbsp;


                            <input type="submit" name="select-organ" value="Sélectionner"/>


                            <!-- 
                            <div class="combo-box" id="combo-organ">
                                <label for="ref_organ_cbox">Organisme :</label><br/>
                                <select name="ref_organ_cbox" id="ref_organ_cbox" class="ajax-list" data-target="ref_user_cbox" data-url="<?php echo $form_url; ?>" data-sort="user">
                                    <option class="organ-option" value="select_cbox">---</option>
                                  
                                </select>
                            </div>
                           

                            <input type="submit" value="Valider" id="submit-posi" class="bt-admin-menu-ajout2" /> -->

                        </fieldset>

                        <!-- </div> -->
                    </div>
                </div>



                <div class="zone-formu2">

                    <div id="infos-posi" class="form-full">

                        <ul>
                            <li><a href="#infos">1 - Statistique globale de l'organisme</a></li>   
                        </ul>

                        <div id="infos" class="zone-liste-restitution">

                            <p>Nombre de positionnement: <strong>40</strong></p>
    						<p>Nombre de personne positionnées: <strong>40</strong></p>
    						<p>Temps de passation moyen: <strong>17 min</strong></p>
    						<p>Temps total: <strong>20h45</strong></p>
    						<p>Nombre de candidats réparti par Niveau de formation : 
    							<ul>
    								<li>Niveau VI et Vbis : abandon CAP - BEP - 3e : <strong> 7</strong></li>
    								<li>Niveau V : CAP - BEP - 2e cycle : <strong> 6</strong></li>
    								<li>Niveau IV : Bac : <strong> 8</strong></li>
    								<li>Niveau III : Bac+2 : <strong> 3</strong></li>
    								<li>Niveau II : Bac+3, bac+4 : <strong> 10</strong></li>
    								<li>Niveau I : Bac+5 et plus : <strong> 6</strong></li>
    							</ul>
    						</p>
    						
    						<p>Score moyen par compétence :</p>
    							<ul>
    								<li>Oral<strong> 80 %</strong></li>
    								<li>Ecrit : <strong> 100 %</strong></li>
    								<li>Calcul: <strong> 68%</strong></li>
    								<li>Espace temps : <strong> 90%</strong></li>
    								<li>Informatique : <strong> 48%</strong></li>
    							</ul>
    						<hr>
    						<p>Score moyen global: <strong>60%</strong></p>


                        </div>

                    </div>

                </div>


            </form>
        </div>
        
        <div style="clear:both;"></div>


        <?php
            // Inclusion du footer
            require_once(ROOT.'views/templates/footer.php');
        ?>

    </div>
    



    <script language="javascript" type="text/javascript">
       
        $(function() { 
            
            $("#infos-posi").tabs();

            //$("#infos-posi").tooltip();

            $(".search-date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true, 
                changeYear: true, 
                yearRange: "2013:2014",
                closeText: 'Fermer',
                prevText: 'Précédent',
                nextText: 'Suivant',
                currentText: 'Aujourd\'hui',
                monthNames: ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'],
                monthNamesShort: ['janv.', 'févr.', 'mars', 'avril', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'],
                dayNames: ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'],
                dayNamesShort: ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'],
                dayNamesMin: ['D','L','M','M','J','V','S'],
                weekHeader: 'Sem.',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''
            });
            
        })(jQuery);

    </script>