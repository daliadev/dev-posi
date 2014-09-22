<?php
$dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10));
$timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); 
$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); 
$file = $this->returnData['response']['infos_user']['nom']."_".$this->returnData['response']['infos_user']['prenom']."_".$dateSession."_".$time.".csv";

header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');


?>"numero";"Question";"Catégorie/Compétence";"Degré";"Réponse utilisateur";"Réponse correcte";"Réussite"<?php

	$content = "";

	foreach($response['details']['questions'] as $detail)
    {
    	$detail['intitule'] = preg_replace("`&#39;`","'", $detail['intitule']);

		$detail['categories'][0]['nom_cat'] = preg_replace("`&#39;`","'", $detail['categories'][0]['nom_cat']);
		$detail['categories'][0]['nom_cat_parent'] = preg_replace("`&#39;`","'", $detail['categories'][0]['nom_cat_parent']);

		$content .= "\n";
		$content .= '"';
		$content .= $detail['num_ordre'].'";"';
		$content .= utf8_decode($detail['intitule']).'";"';
		if (isset($detail['categories'][0]['nom_cat_parent']) && !empty($detail['categories'][0]['nom_cat_parent']))
		{
			$content .= utf8_decode($detail['categories'][0]['nom_cat_parent']).' // ';
		}
		$content .= utf8_decode($detail['categories'][0]['nom_cat']).'";"';
		$content .= utf8_decode($detail['nom_degre']).'";"';

		if (!empty($detail['reponse_user_qcm']) && $detail['reponse_user_qcm'] != "-")
		{
			$content .= utf8_decode($detail['reponse_user_qcm']).'";"';
		}
		else if (!empty($detail['reponse_user_champ']) && $detail['reponse_user_champ'] != "-")
		{
			$content .= utf8_decode($detail['reponse_user_champ']).'";"';
		}
		else
		{
			$content .= '-'.'";"';
		}

		$content .= utf8_decode($detail['reponse_qcm_correcte']).'";"';
		$content .= utf8_decode($detail['reussite']);
		$content .= '"';

	}

	echo $content;
?>