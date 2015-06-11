<?php 

//$animaux = array();


$animaux = array(
	array('id' => 1, 'parent' => 0, 'nom' => 'Félins'), 
	array('id' => 2, 'parent' => 0, 'nom' => 'Canins'),
	array('id' => 3, 'parent' => 1, 'nom' => 'Lion'),
	array('id' => 4, 'parent' => 1, 'nom' => 'Chat'),
	array('id' => 5, 'parent' => 2, 'nom' => 'Chien'),
	array('id' => 6, 'parent' => 4, 'nom' => 'Chat de gouttière'),
	array('id' => 7, 'parent' => 4, 'nom' => 'Chat siamois'),
	array('id' => 8, 'parent' => 5, 'nom' => 'Berger allemand'),
	array('id' => 9, 'parent' => 5, 'nom' => 'Caniche')
);



//var_dump($animaux);


function afficher($parent, $level, $datas) {

	$text = '';

	foreach ($datas as $node) {
		
		if ($parent == $node['parent']) {

			for ($i = 0; $i < $level; $i++) {

				$text .= '-';
			}

			$text .= ' '.$node['nom'].'<br/>';
			$text .= afficher($node['id'], ($level + 1), $datas);
		}
	}

	return $text;
}


echo afficher(4, 2, $animaux);



function afficherCategories($parent, $level, $datas) {

	$html = '';
	$previous_level = 0;

	if ($level <= 0 && $previous_level <= 0) {
		$html .= '<ul>';
	}

	foreach ($datas as $node) {
		
		if ($parent == $node['parent']) {

			if ($previous_level < $level) {
				$html .= '<ul>';
			}

			$html .= '<li>'.$node['nom'];
			$previous_level = $level;

			$html .= afficherCategories($node['id'], ($level + 1), $datas);
		}
	}

	if ($previous_level == $level && $previous_level != 0) {
		$html .= '</ul></li>';
	}
	else if ($previous_level == $level) {
		$html .= '</ul>';
	}
	else {
		$html .= '</li>';
	}

	return $html;
}


echo afficherCategories(0, 0, $animaux);

?>