<?php
$dateSession = Tools::toggleDate(substr($response['session'][0]->getDate(), 0, 10));
$timeToSeconds = Tools::timeToSeconds(substr($response['session'][0]->getDate(), 11, 8), $inputFormat = "h:m:s"); 
$time = str_replace(":", "h", Tools::timeToString($timeToSeconds, "h:m")); 
$file = $this->returnData['response']['infos_user']['nom']."_".$this->returnData['response']['infos_user']['prenom']."_".$dateSession."_".$time.".csv";

header('Content-Type: text/csv;');
header('Content-Disposition: attachment; filename="'.$file.'"');

//print_r($response);
?>"Question";"Catégorie/Compétence";"Degré";"Réponse utilisateur";"Réponse correcte";"Réussite"<?php
 foreach($response['details']['questions'] as $detail)
                {
				$detail['categories'][0]['nom_cat'] = preg_replace("`&#39;`","'", $detail['categories'][0]['nom_cat'] );
				if (!empty($detail['reponse_user_qcm']) ) 
				{
					echo "\n".'"'.$detail['num_ordre'].'";"'.utf8_decode($detail['categories'][0]['nom_cat']).'";"'.$detail['nom_degre'].'";"'.$detail['reponse_user_qcm'].'";"'.$detail['reponse_qcm_correcte'].'";"'.$detail['reussite'].'"';
				}
				else if (!empty($detail['reponse_user_champ']))
				{
					echo "\n".'"'.$detail['num_ordre'].'";"'.utf8_decode($detail['categories'][0]['nom_cat']).'";"'.$detail['nom_degre'].'";"'.utf8_decode($detail['reponse_user_champ']).'";"'.$detail['reponse_qcm_correcte'].'";"'.$detail['reussite'].'"';
				}

}
?>