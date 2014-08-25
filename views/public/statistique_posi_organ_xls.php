<?php


$date_debut = $response['form_data']['date_debut'];
$date_fin = $response['form_data']['date_fin'];

if (!empty($date_debut) && !empty($date_fin) )
{
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_export_nbr_posi_par_organisme.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_export_nbr_posi_par_organisme_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = date("d/m/Y")."_export_nbr_posi_par_organisme.csv";
	}
	else
	{
		$file = date("d/m/Y")."_export_nbr_posi_par_organisme_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	

}
else if (!empty($date_debut) && empty($date_fin) )
{
	  
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_export_nbr_posi_par_organisme.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_export_nbr_posi_par_organisme_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && !empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_export_nbr_posi_par_organisme.csv";
	}
	else
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_export_nbr_posi_par_organisme_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
	
}


header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');


?>"Organisme";"Nbr de positionnement";"Nombre d'utilisateurs positionnes";"Score moyen global";"Temps de passation moyen";"Temps total";"Age moyen des utilisateurs"<?php	

	$content = "";

	foreach($response['stats']['global']['organismes'] as $detail)
    {
	
		if (count($response['stats']['global']['organismes'])>1)
		{
			$content .= "\n";
			$content .= '"';
			$content .= $detail['nom_organ'].'";"';
			$content .= $detail['nbre_sessions'].'";"';
			$content .= $detail['nbre_users'].'";"';
			$content .= $detail['moyenne_score_session'].'";"';
			$content .= $detail['moyenne_temps_session'].'";"';
			$content .= $detail['temps_total'].'";"';
			$content .= $detail['age_moyen'];
			$content .= '";"';
			$content .= '"';
		}
		else 
		{
			$content .= "\n";
			$content .= '"';
			$content .= $response['stats']['global']['organismes'][0]['nom_organ'].'";"';
			$content .= $response['stats']['global']['nbre_sessions'].'";"';
			$content .= $response['stats']['global']['nbre_users'].'";"';
			$content .= $response['stats']['global']['moyenne_score_session'].'";"';
			$content .= $response['stats']['global']['moyenne_temps_session'].'";"';
			$content .= $response['stats']['global']['temps_total'].'";"';
			$content .= $response['stats']['global']['age_moyen'];
			$content .= '";"';
			$content .= '"';
		}
	
	}
		echo $content;
?>

		