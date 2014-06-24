<?php
/*$dateSession = Tools::toggleDate(substr($response['stats']['sessions'][0]->getDate(), 0, 10));
$timeToSeconds = Tools::timeToSeconds(substr($response['stats']['sessions'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); 
$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); */
$date_debut = $response['form_data']['date_debut'];
$date_fin = $response['form_data']['date_fin'];

if (!empty($date_debut) && !empty($date_fin) )
{
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_niveau_de_formation.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_niveau_de_formation__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = date("d/m/Y")."_niveau_de_formation.csv";
	}
	else
	{
		$file = date("d/m/Y")."_niveau_de_formation__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	

}
else if (!empty($date_debut) && empty($date_fin) )
{
	  
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_niveau_de_formation.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_niveau_de_formation__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && !empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_niveau_de_formation.csv";
	}
	else
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_niveau_de_formation_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
	
}

header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');

//print_r($response);
?>"Niveau de formation";"Utilisateurs"<?php

	$content = "";

	foreach($response['stats']['global']['niveaux'] as $detail)
    {
	
		$content .= "\n";
		$content .= '"';
		$content .= $detail['nom_niveau'].'";"';
		$content .= $detail['nbre_users'].'";"';
		$content .= '";"';
		$content .= '"';
	
	}
		echo $content;
		
	