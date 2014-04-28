
<style>
    
    h1 {
        font-size: 15pt;
        line-height: 22pt;
    }
    h2 {
        font-size: 14pt;
        line-height: 20pt;
    }
    h3 {
        font-size: 12pt;
    }
    
    table {
        width: 170mm;
    }
    
    img {
        border: none;
    }
    
    .logo {
        width: 40mm;
    }
    
    hr {
        border-color: #2C3E50;
    }
    
    
    
    table {
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9pt;
    }
    
    
    .titre-h1 {
        width: 100%;
        height: 8mm;
        background-color: #48dcbf;
        color: #ffffff;
        font-size: 12pt;
	font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
    
    .titre-infos {
        width: 130mm;
        height: 28mm;
        line-height: 16pt;
        font-size: 10pt;
        text-align: right;
    }

    
    
    .title {
        padding: 2mm;
        width: 170mm;
        background-color: #2C3E50; /* #34495e (+clair) */
        font-size: 10pt;
	font-weight: bold;
        color: #ffffff;
    }
    
    .info {
        background-color: #f0f0f0;
        padding: 2mm;
        width: 170mm;
    }
    
    .line {
        width: 170mm;
    }
    
  
    
    .stats {
        width: 170mm;
    }
    
    .stats .info {
        padding: 2mm;
        width: 170mm;
        
    }
    
        .stats-text { 

        }

        .percent {
            position: relative;
            height: 3mm;
            margin-left: 10mm;
            
        }

            .percent img
            {
                width: 152mm;
                height: 3mm;
            }
            
            .cache {
                position: absolute;
                height: 5mm;
                background-color: #f0f0f0;
            }
    
    
    
    .resultats {
        margin: 1mm;
        width: 99%;
        border-collapse: collapse;
    }
  
        .resultats th, .resultats td {
            padding: 2mm;
            font-size: 9pt;
            text-align: center;
            vertical-align: middle;
        }

        .resultats th {
        border: 1px solid #ffffff;
        color: #ffffff;
        cursor: pointer;
		background-color: #F7A22F;
        }

        .resultats td {
           border: 1px solid #ffffff;
        }


        .red {
            background-color: #e67373;
        }

        .green {
            background-color: #6dda9b;
        }

        .white {
            background-color: #ffffff;
        }
  
    #footer {
        /* width: 210mm; */
        background: url(<?php echo ROOT; ?>media/images/footer-page.jpg) repeat-x;  
        /* text-align: center; */
        /*  margin-top: 20px;
        margin-bottom: 20px; */
    }
        
    .txt-footer{
        text-align: left;
        color:#b3b3b3;
        padding-top:15px;
        font-size: 8pt;
    }
        
</style>




<page backleft="10mm" backright="10mm" backtop="5mm" backbottom="20mm">
    
    
    <!-- <div id="content"> -->

        <table>
            <tr>
                <!--<td rowspan="2"><img class="logo" src="<?php //echo ROOT; ?>media/images/logo.png" /></td>-->
                <td rowspan="2" style="width:4mm;"></td>
                <td class="titre-h1">Restitution du positionnement <?php echo Config::POSI_NAME; ?></td>
            </tr>
            <tr>
                <td class="titre-infos">
                    <?php $dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10)); ?>
                    <?php $timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); ?>
                    <?php $time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); ?>
                    <p><strong><?php echo $response['infos_user']['prenom']; ?> <?php echo $response['infos_user']['nom']; ?></strong><br/>
                    Positionnement du <?php echo $dateSession; ?> à <?php echo $time; ?></p>
                </td>
                
            </tr>
        </table>

            
        <table>
            
            <tr>
                <td class="title">Informations utilisateur</td>
            </tr>
            
            <?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user'] ?>

                <tr>
                    <td class="info">Nom de l'organisme : <strong><?php echo $infos_user['nom_organ']; ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Nom de l'intervenant : <strong><?php echo $infos_user['nom_intervenant']; ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Email de l'intervenant : <strong><?php echo $infos_user['email_intervenant']; ?></strong></td>
                </tr>
                <tr>
                    <td class="line"></td>
                </tr>
                <tr>
                    <td class="info">Nom : <strong><?php echo strtoupper($infos_user['nom']); ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Prénom : <strong><?php echo $infos_user['prenom']; ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Date de naissance : <strong><?php echo $infos_user['date_naiss']; ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Niveau d'études : <strong><?php echo $infos_user['nom_niveau']; ?></strong></td>
                </tr>
                <tr>
                    <td class="line"></td>
                </tr>
                <tr>
                    <td class="info">Nombre de positionnements terminés : <strong><?php $infos_user['nbre_positionnements']; ?></strong></td>
                </tr>
                <tr>
                    <td class="info">Date du dernier positionnement : <strong><?php echo $infos_user['date_last_posi']; ?></strong></td>
                </tr>

            <?php else : ?>

                <tr>
                    <td class="info">Aucun utilisateur n'a été sélectionné.</td>
                </tr>

            <?php endif; ?>
                
            <tr>
                <td><hr/></td>
            </tr>
                
                
        </table>

        <br/><br/>
        
        <table>

            <tr>
                <td class="title">Les statistiques</td>
            </tr>
                
            <?php if (!empty($response['stats'])) : $stats = $response['stats'];
                $tempsTotal = Tools::timeToString($response['session'][0]->getTempsTotal()); ?>

                <tr>
                    <td class="info">Temps total : <strong><?php echo $tempsTotal; ?></strong></td>
                </tr>
                <?php if (!empty($stats['percent_global'])) : ?>
                    <tr>
                        <td class="info">Taux de réussite global : <strong><?php echo $stats['percent_global']; ?>%</strong> (<strong><?php echo $stats['total_correct_global']; ?></strong> réponses correctes sur <strong><?php echo $stats['total_global']; ?></strong> questions)</td>
                    </tr>  
                <?php endif; ?>
                    
                <tr>
                    <td class="line"><br/></td>
                </tr>

                <?php foreach ($stats['categories'] as $statCategorie) : ?>
                    <?php if ($statCategorie['total'] > 0 && $statCategorie['parent']) : $width = ($statCategorie['percent'] * 152) / 100; ?>
                        <?php if ($statCategorie['percent'] == 0) : $width = 0.5; endif; ?>
                        <tr>
                            <td class="info">
                                <div class="stats-text">
                                    <?php echo $statCategorie['nom_categorie']; ?> : 
                                    <strong><?php echo $statCategorie['percent']; ?>%</strong> (<strong><?php echo $statCategorie['total_correct']; ?></strong> réponses correctes sur <strong><?php echo $statCategorie['total']; ?></strong> questions)
                                </div>
                            </td>
                        </tr> 
                        <tr>
                            <td class="info">
                                <div class="percent" style="width:<?php echo $width; ?>mm;">
                                    <?php $position = $width; ?>
                                    <?php $width = 152 - $position + 3; ?>
                                    <img src="<?php echo ROOT; ?>media/images/gradiant.png" />
                                    <div class="cache" style="width:<?php echo $width; ?>mm; left:<?php echo $position; ?>mm; top:-1mm; z-index:99;"></div>
                                </div>
                            </td>
                        </tr>

                           
                        <tr>
                            <td class="line"></td>
                        </tr>
                
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php else : ?>

                <tr>
                    <td class="info">Aucun positionnement n'est sélectionné.</td>
                </tr>
                
            <?php endif; ?>

            <tr>
                <td><hr/></td>
            </tr>
            
        </table>
        
        <br/><br/>
        
        
        <table>
            <tr>
                <td class="title">Détails des résultats</td>
            </tr>
        </table>     

        <table class="resultats">

            <?php if (!empty($response['infos_user'])) : $infos_user = $response['infos_user']; ?>
                <thead>
                    <tr>
                        <th style="width: 15%;">Questions</th>
                        <th style="width: 30%;">Catégorie /<br/>compétence</th>
                        <th style="width: 8%;">Degré</th>
                        <th style="width: 30%;">Réponse utilisateur</th>
                        <th style="width: 9%;">Réponse<br/>correcte</th>
                        <th style="width: 8%;">Réussite</th>
                    </tr>
                </thead>
                
                <tbody>
                <?php
                $i = 0;
                foreach($response['details']['questions'] as $detail)
                {
                    if($i % 2 == 0)
                    {
                        echo '<tr style="background-color:#FCE7CA;" >';
                    }
                    else
                    {
                        echo '<tr style="background-color:#FFF6EA;">';
                    }

                    echo '<td style="width:15%;">';
                            echo 'Question n°'.$detail['num_ordre'];
                    echo '</td>';
                    
                    echo '<td style="width:30%; font-size:8pt; text-align:left;">';
                        if (isset($detail['categories'][0]['nom_cat_parent']) && !empty($detail['categories'][0]['nom_cat_parent']))
                        {
                            echo '<strong>'.$detail['categories'][0]['nom_cat_parent']." : </strong><br/>";
                        }
                        echo $detail['categories'][0]['nom_cat'];
                    echo '</td>';
                                        

                    echo '<td style="width: 8%;">';
                        echo $detail['nom_degre'];
                    echo '</td>';

                    if (!empty($detail['reponse_user_qcm']) && $detail['reponse_user_qcm'] != "-")
                    {
                        echo '<td style="width: 30%;">'.$detail['reponse_user_qcm'].'</td>';
                    }
                    else if (!empty($detail['reponse_user_champ']))
                    {
                        if ($detail['reponse_user_champ'] == "-")
                        {
                            echo '<td style="width: 30%; text-align: center;">'.$detail['reponse_user_champ'].'</td>';
                        }
                        else 
                        {
                            echo '<td style="width: 30%; text-align: left;">'.$detail['reponse_user_champ'].'</td>';
                        }
                        
                    }
                    else
                    {
                        echo '<td style="width: 30%; text-align: center;">-</td>';
                    }

                    echo '<td style="width: 9%;">'.$detail['reponse_qcm_correcte'].'</td>';

                    if ($detail['reussite'] === 1)
                    {
                        echo '<td  style="width:8%;"><img src="'.SERVER_URL.'media/images/valide.png"></td>';
                    }
                    else if ($detail['reussite'] === 0)
                    {
                        echo '<td class="red-cell"  style="width:8%;"><img src="'.SERVER_URL.'media/images/faux.png"></td>';
                    }
                    else
                    {
                        echo '<td class="white-cell"  style="width:8%;"><img src="'.SERVER_URL.'media/images/stylo.png"></td>';
                    }


                    echo '</tr>';  
                    $i++;
                } 
                ?>
                </tbody>
                
            <?php else : ?>

                <tr>
                    <td class="info">Aucun détail à afficher.</td>
                </tr>

            <?php endif; ?>
                
        </table>

    <!-- </div> -->

</page>