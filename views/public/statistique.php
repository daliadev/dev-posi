<?php


// Initialisation par défaut des valeurs du formulaire

$formData = array();
$formData['ref_organ_cbox'] = "";
$formData['ref_organ'] = "";
$formData['date_debut'] = "";
$formData['date_fin'] = "";


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


var_dump($response['stats']);


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
  
                <div class="zone-formu2">

                    <!-- <div id="titre-question-h3"><strong>Statistique total du positionnement:</strong></div> -->

                    <div id="bloc-stat-filtre" class="form-full">

                        <p style="margin-top:0;"><strong>Filtres : </strong></p>

                        <hr>

                        <div class="filter-item">
                            <label for="date_debut">Date de début : </label>
                            <input type="text" name="date_debut" id="date_debut" class="search-date" style="width:120px;" title="Veuillez entrer la date de début" value="<?php echo $formData['date_debut']; ?>">
                        </div>

                        <div class="filter-item">
                            <label for="date_fin">Date de fin : </label>
                            <input type="text" name="date_fin" id="date_fin" class="search-date" style="width:120px;" title="Veuillez entrer la date de fin" value="<?php echo $formData['date_fin']; ?>">
                        </div>

                        <div class="filter-item">
                            <label for="ref_organ_cbox">Organisme : </label>
                            <select name="ref_organ_cbox" id="ref_organ_cbox">
                                <option class="organ-option" value="select_cbox">Tous</option>
                                <?php
                                
                                if (isset($response['organisme']) && !empty($response['organisme']) && count($response['organisme']) > 0)
                                {                       
                                    foreach ($response['organisme'] as $organisme)
                                    {
                                        $selected = "";
                                        if (!empty($formData['ref_organ']) && $formData['ref_organ'] == $organisme->getId())
                                        {
                                            $selected = "selected";
                                        }
                                        echo '<option class="organ-option" value="'.$organisme->getId().'" '.$selected.'>'.$organisme->getNom().'</option>';
                                    }
                                }
                                
                                ?>
                            </select>
                        </div>

                        <div class="filter-item">
                            <input type="submit" name="select-form" value="Sélectionner" style="margin: 18px 0 0 0;">
                        </div>

                    </div>
                </div>

                <div class="zone-formu2">

                    <div id="bloc-stat-global" class="form-full">

                        <fieldset>
                                
                            <legend>Statistiques globales</legend>

                            <!-- <div class="stats-global">

                                <div class="stat-posi">123</div>

                                <div class="stat-user">58</div>

                                <div class="stat-pourcent">63<small>%</small></div>

                                <div class="stat-temps">18 min 25 s</div>

                                <div class="stat-total-temps">25 h 35 min</div>

                                <div class="stat-age">26 ans</div>

                            </div> -->

                            <!-- <hr> -->

                            <div class="stats-detail">

                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Nombre de positionnements</div>
                                    <div class="bloc-stat-number"><strong><?php echo $response['stats']['global']['nbre_sessions']; ?></strong></div>
                                </div>
        						
                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Nombre d'utilisateurs positionnés</div>
                                    <div class="bloc-stat-number"><strong><?php echo $response['stats']['global']['nbre_users']; ?></strong></div>
        						</div>
                                
                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Score moyen global</div>
                                    <div class="bloc-stat-number" style="color:#f1b557;"><strong><?php echo $response['stats']['global']['moyenne_score_session']; ?><small>%</small></strong></div>
                                </div>


                                <!-- <div style="clear:both;"></div> -->


                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Temps de passation moyen</div>
                                    <div class="bloc-stat-number"><strong style="font-size:13px;"><?php echo $response['stats']['global']['moyenne_temps_session']; ?></strong></div>
        						</div>

                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Temps total</div>
                                    <div class="bloc-stat-number"><strong style="font-size:12px;"><?php echo $response['stats']['global']['temps_total']; ?></strong></div>
        						</div>

                                <div class="bloc-stat last">
                                    <div class="bloc-stat-title">Age moyen des utilisateurs</div>
                                    <div class="bloc-stat-number"><strong>26 ans</strong></div>
                                </div>

                                <div style="clear:both;"></div>
                                
                                <input type="submit" value="Export Posi/Organ"  title="Export nombre de positionnement par organisme"name="export_xls_total_organisme" class="bt-admin-menu-ajout2-right" />
                            </div>
                            
                            <div class="stats-detail">
                                <p><strong>Nombre de candidats répartis par niveau de formation</strong></p>

                                <hr>

        						<p>
        							<ul>
                                        <?php
                                        /*
                                        for ($i = 0; $i < count($response['stats']['niveaux']); $i++)
                                        {
                                            echo '<li title="'.$response['stats']['niveaux'][$i]['descript_niveau'].'">'.$response['stats']['niveaux'][$i]['nom_niveau'].' : <strong> '.$response['stats']['niveaux'][$i]['nbre_users'].'</strong></li>';
                                        }
                                        */
                                        ?>

        							</ul>
        						</p>

                                <input type="submit" value="Export niveau"  title="Export nombre de candidats répartis par niveau" name="export_xls_niveau_nombre" class="bt-admin-menu-ajout2-right" />
        					
                            </div>	
        					
                            <div class="stats-detail">
                                <p><strong>Score moyen par compétences</strong></p>

                                <hr>

                                <p>
        							<ul>
        								<li>Oral : <strong> 68 %</strong></li>
        								<li>Ecrit : <strong> 100 %</strong></li>
        								<li>Calcul: <strong> 60%</strong></li>
        								<li>Espace temps : <strong> 85%</strong></li>
        								<li>Informatique : <strong> 48%</strong></li>
        							</ul>
        						</p>
                                
                                <input type="submit" value="Export score moyen"  title="Export score moyen par compétences" name="export_xls_score_competences" class="bt-admin-menu-ajout2-right" />

        					</div>

                        <fieldset>
                    </div>
                </div>

            
                <!-- 
                <div class="zone-formu2">

                    <div id="select-organ" class="form-full">
                        
                        <fieldset>
                                
                            <legend>Statistiques par organisme</legend>
                            
                            

                            &nbsp;

                            <input type="submit" name="select-organ" value="Sélectionner"/>

                        </fieldset>

                    </div>
                </div>
                 -->


                <div class="zone-formu2">

                    <div id="infos-posi" class="form-full">

                        <!-- <fieldset>
                                
                            <legend>Statistiques organisme</legend> -->
                            <ul>
                                <li><a href="#infos">1 - Statistique globale de l'organisme</a></li>
                                <!-- <li><a href="#exports">2 - Exports</a></li> -->  
                            </ul>

                            <div id="infos" class="zone-liste-restitution">

                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Nombre de positionnements</div>
                                    <div class="bloc-stat-number"><strong><?php echo $response['stats']['organ']['nbre_sessions']; ?></strong></div>
                                </div>

                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Nombre d'utilisateurs positionnés</div>
                                    <div class="bloc-stat-number"><strong><?php echo $response['stats']['organ']['nbre_users']; ?></strong></div>
                                </div>
                                
                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Score moyen global</div>
                                    <div class="bloc-stat-number"><strong><?php echo $response['stats']['organ']['moyenne_score_session']; ?>%</strong></div>
                                </div>


                                <!-- <div style="clear:both;"></div> -->


                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Temps de passation moyen</div>
                                    <div class="bloc-stat-number"><strong style="font-size:11px;"><?php echo $response['stats']['organ']['moyenne_temps_session']; ?></strong></div>
                                </div>

                                <div class="bloc-stat">
                                    <div class="bloc-stat-title">Temps total</div>
                                    <div class="bloc-stat-number"><strong style="font-size:9px;"><?php echo $response['stats']['organ']['temps_total']; ?></strong></div>
                                </div>

                                <div class="bloc-stat last">
                                    <div class="bloc-stat-title">Age moyen des utilisateurs</div>
                                    <div class="bloc-stat-number"><strong>26 ans</strong></div>
                                </div>

                                <div style="clear:both;"></div>

                                <!-- <p>Nombre de positionnement: <strong>40</strong></p>
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
        						<p>Score moyen global: <strong>60%</strong></p> -->


                            </div>

                            <!-- 
                            <div id="exports" class="zone-liste-restitution">

                                <div class="export-files">

                                    <div class="info">Aucun export n'est disponible.</div>
                                
                                </div>
                            
                            </div>
                             -->
                       <!--  </fieldset> -->

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
            var date = new Date();
            var year = date.getFullYear();
            // alert(year);

            $(".search-date").datepicker({
                dateFormat: "dd/mm/yy",
                changeMonth: true, 
                changeYear: true, 
                yearRange: "2014:"+year,
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
                firstDay: 1,
                showMonthAfterYear: false
            });
            
        })(jQuery);

    </script>