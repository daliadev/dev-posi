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
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_score_moyen.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.$date_fin."_score_moyen_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = date("d/m/Y")."_score_moyen.csv";
	}
	else
	{
		$file = date("d/m/Y")."_score_moyen_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	

}
else if (!empty($date_debut) && empty($date_fin) )
{
	  
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_score_moyen.csv";
	}
	else
	{
		$file = 'Du_'.$date_debut.'_au_'.date("d/m/Y")."_score_moyen_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
}
else if (empty($date_debut) && !empty($date_fin) )
{
	
	if(count($response['stats']['global']['organismes'])>1)
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_score_moyen.csv";
	}
	else
	{
		$file = 'Du_'.date("d/m/Y").'_au_'.$date_fin."_score_moyen_".$response['stats']['global']['organismes'][0]['nom_organ'].".csv";
	}
	
	
}

header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');

//print_r($response);
?>"Competence";"Score moyen"<?php	

	$content = "";

	foreach($response['stats']['global']['categories'] as $detail)
    {
	
		$content .= "\n";
		$content .= '"';
		$content .= utf8_decode($detail['nom']= preg_replace("`&#39;`","'", $detail['nom'] ).'";"');
		$content .= $detail['pourcent'].' %'.'";"';
		$content .= '";"';
		$content .= '"';
	
	}
		echo $content;
		
