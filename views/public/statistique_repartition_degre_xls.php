<?php

$date_debut = $response['form_data']['date_debut'];
$date_fin = $response['form_data']['date_fin'];

if (!empty($date_debut) && !empty($date_fin) )
{
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_répartition_des_degrés.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_répartition_des_degrés__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = date("d/m/Y")."_répartition_des_degrés.csv";
	}
	else
	{
		$file = date("d/m/Y")."_répartition_des_degrés__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	

}
else if (!empty($date_debut) && empty($date_fin) )
{
	  
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_répartition_des_degrés.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_répartition_des_degrés__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && !empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_répartition_des_degrés.csv";
	}
	else
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_répartition_des_degrés__".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
	
}
 
header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');


?>"Nom";"Nombre de positionnements";"Taux (%)"<?php

	$content = "";

	foreach($response['stats']['global']['acquis'] as $acquis)
    {
	
		$content .= "\n";
		$content .= '"';
		$content .= utf8_decode($acquis['name'] = preg_replace("`&#39;`","'", $acquis['name']).'";"');
		$content .= $acquis['count'] .'";"';
		$content .= round(($acquis['count'] / $response['stats']['global']['nbre_sessions']) * 100) .'%";"';
		$content .= '";"';
		$content .= '"';
	}

	/*
	$nonValid = "\n" . 'Positionnement(s) non validé(s) : '. $response['stats']['global']['non_valid_count'];
	$nonValidPercent = " soit " . round($response['stats']['global']['non_valid_count'] / $response['stats']['global']['nbre_sessions'] * 100) . "%";
	$content .= utf8_decode($nonValid = preg_replace("`&#39;`","'", $nonValid));
	$content .= utf8_decode($nonValidPercent = preg_replace("`&#39;`","'", $nonValidPercent));
	*/

	$nonValid = $response['stats']['global']['non_valid_count'];
	$nonValidPercent = round($response['stats']['global']['non_valid_count'] / $response['stats']['global']['nbre_sessions'] * 100);

	$content .= "\n";
	$content .= '"';
	$content .= utf8_decode(preg_replace("`&#39;`","'", 'Positionnement(s) non validé(s) : ').'";"');
	$content .= $nonValid .'";"';
	$content .= $nonValidPercent.'%";"';
	$content .= '";"';
	$content .= '"';
		
	echo $content;
		
	