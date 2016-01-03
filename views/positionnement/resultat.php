<?php


	function recursiveCategories($parent, $level, $datas, $level_max = null)
	{
		$list = '';
		$previous_level = 0;
		$isMainListOpen = false;
		$isListOpen = false;

		if ($level == 0) 
		{
			$list .= '<ul>';
		}

		foreach ($datas as $cat) 
		{
			if ($parent == $cat->getParent()) 
			{
				if ($previous_level < $level) 
				{
					$list .= '<ul>';
				}

				if ($level == 0)
				{
					$list .= '<li>';
					$list .= $cat->getDescription().' / '.$cat->getNom().' / '.$cat->getScorePercent().'%';
					//$list .= '<div class="progressbar-title" title="'.$cat->getDescription().'"><h3><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></h3></div>';
					//$list .= '<div class="progress">';
					//$list .= getProgressBar($cat->getScorePercent());
					//$list .= '</div>';

					$list .= '<g class="cat-bar">';
						$list .= '<line class="cat-line" x1="1" y1="0" x2="1" y2="56"/>';
						$list .= '<text class="cat-text" x="9" y="0">Ecrit</text>';
						$list .= '<text class="reponses" x="505" y="0">14/24</text>';
						$list .= '<rect class="back" x="9" y="24" width="701" height="32" />';
						$list .= '<rect class="front" x="9" y="24" width="500" height="32" />';
						$list .= '<text class="percent-cat" x="497" y="41">72<tspan class="percent">%<tspan></text>';
					$list .= '</g>';

					$isMainListOpen = true;
				}
				else
				{
					if ($isListOpen) 
					{
						$list .= '</li>';
					}
					$list .= '<li>';
					$list .= $cat->getDescription().' / '.$cat->getNom().' / '.$cat->getScorePercent().'%';
					//$list .= '<div class="progress-title" title="'.$cat->getDescription().'"><a>'.$cat->getNom().' / <strong>'.$cat->getScorePercent().'</strong>%</a></div>';
					//$list .= '<div class="progress">';
					//$list .= getProgressBar($cat->getScorePercent());
					//$list .= '</div>';

					$isListOpen = true;
				}

				$previous_level = $level;

				$list .= recursiveCategories($cat->getCode(), ($level + 1), $datas, $level_max);
			}
		}

		if ($previous_level == $level && $previous_level != 0) 
		{
			if ($isMainListOpen || $isListOpen)
			{
				$list .= '</li>';
			}
			$list .= '</ul>';
		}

		return $list;
	}



	$time = $response['temps_total'];
	$percentGlobal = $response['total_score'];
	$totalGlobal = $response['total_reponses'];
	$totalCorrectGlobal = $response['total_reponses_correctes'];

	//var_dump('$response', $response);

?>
	

		<div class="content-form">
			
			<div class="form-header">
				<h2>Résultats</h2>
				<!-- <i class="fa fa-area-chart"></i> -->
				<i class="fa fa-bar-chart"></i>
				<div class="clear"></div>
			</div>


			<div id="stats" class="stats">

				<div class="global-text">
					<div class="titles">
						<span class="time-title"><p>Temps total</p></span>
						<span class="time"><i class="fa fa-clock-o"></i>&nbsp;<?php echo $time; ?></span>
						<span class="result-title"><i></i>Score global</span>
						<!-- <div class="clear"></div> -->
					</div>
					<div class="reponses">Nombre de bonnes réponses : <span class="score"><?php echo $totalCorrectGlobal; ?></span> sur <?php echo $totalGlobal; ?></div>
				</div>
					
				

				<div class="global-result">

					<svg id="global-percent" version="1.1" class="svg-donut" viewBox="0 0 140 140" preserveAspectRatio="xMinYMin meet">
						
						<g transform="translate(70, 70)">
							<circle r="60" class="circle-back" />
							<circle r="60" class="circle-front" transform="rotate(270.1)" style="stroke-dasharray: 349px; stroke-dashoffset: 349px;" />
						</g>

						<g>
						  <text x="73" y="75" class="text-percent"><?php echo $percentGlobal; ?><tspan class="percent">%<tspan></text>
						</g>

					</svg>

				</div>

				<div style="clear: both;"></div>

			</div>
			


			<div class="main-graph" id="main-graph">

				<svg id="graph-svg" class="graph-svg" version="1.1" viewBox="0 0 720 500" preserveAspectRatio="none">
					
					<defs> 
						<polygon id="arrow" class="arrow" points="-3.5,5 0,0 3.5,5" />

						<!-- <g id="cat-bar" class="cat-bar">
							<line class="cat-line" x1="1" y1="0" x2="1" y2="56"/>
							<text class="cat-text" x="9" y="0">Ecrit</text>
							<text class="reponses" x="505" y="0">14/24</text>
							<rect class="back" x="9" y="24" width="700" height="32" />
							<rect class="front" x="9" y="24" width="500" height="32" />
							<text class="percent-cat" x="497" y="41">72<tspan class="percent">%<tspan></text>
						</g> -->
					</defs>

					<g class="bg-grid">
						<?php 
							for ($i = 0; $i <= 10; $i++) { 
								$x1 = ($i * 70) + 10;
								$x2 = $x1;
								echo '<line class="bg-line" x1="'.$x1.'" y1="485" x2="'.$x2.'" y2="0" />';
							}
						?>
						<!-- <line class="bg-line" x1="10" y1="485" x2="10" y2="0" />
						<line class="bg-line" x1="80" y1="485" x2="80" y2="0" />
						<line class="bg-line" x1="150" y1="485" x2="150" y2="0" />
						<line class="bg-line" x1="220" y1="485" x2="220" y2="0" />
						<line class="bg-line" x1="290" y1="485" x2="290" y2="0" />
						<line class="bg-line" x1="360" y1="485" x2="360" y2="0" />
						<line class="bg-line" x1="430" y1="485" x2="430" y2="0" />
						<line class="bg-line" x1="500" y1="485" x2="500" y2="0" />
						<line class="bg-line" x1="570" y1="485" x2="570" y2="0" />
						<line class="bg-line" x1="640" y1="485" x2="640" y2="0" />
						<line class="bg-line" x1="710" y1="485" x2="710" y2="0" /> -->
					</g>

					<g class="arrows">
						<?php 
							for ($i = 0; $i <= 10; $i++) { 
								$x = ($i * 70) + 10;
								echo '<use xlink:href="#arrow" x="'.$x.'" y="484" />';
							}
						?>
						<!-- <use xlink:href="#arrow" x="10" y="484" />
						<use xlink:href="#arrow" x="80" y="484" />
						<use xlink:href="#arrow" x="150" y="484" />
						<use xlink:href="#arrow" x="220" y="484" />
						<use xlink:href="#arrow" x="290" y="484" />
						<use xlink:href="#arrow" x="360" y="484" />
						<use xlink:href="#arrow" x="430" y="484" />
						<use xlink:href="#arrow" x="500" y="484" />
						<use xlink:href="#arrow" x="570" y="484" />
						<use xlink:href="#arrow" x="640" y="484" />
						<use xlink:href="#arrow" x="710" y="484" /> -->
					</g>

					<g class="bg-percent-text">
						<?php 
							for ($i = 0; $i <= 10; $i++) { 
								$x = ($i * 70) + 10;
								if ($i == 10)
								{
									$x -= 2;
								}
								$percent = $i * 10;
								echo '<text class="text-percent" x="'.$x.'" y="500">'.$percent.'%</text>';
							}
						?>
						<!-- <text class="text-percent" x="10" y="500">0%</text>
						<text class="text-percent" x="80" y="500">10%</text>
						<text class="text-percent" x="150" y="500">20%</text>
						<text class="text-percent" x="220" y="500">30%</text>
						<text class="text-percent" x="290" y="500">40%</text>
						<text class="text-percent" x="360" y="500">50%</text>
						<text class="text-percent" x="430" y="500">60%</text>
						<text class="text-percent" x="500" y="500">70%</text>
						<text class="text-percent" x="570" y="500">80%</text>
						<text class="text-percent" x="640" y="500">90%</text>
						<text class="text-percent" x="708" y="500">100%</text> -->
					</g>
					
					<g class="cat-bars">
						<!-- <use xlink:href="#cat-bar" x="0" y="0" /> -->
						<?php 
							$catList = recursiveCategories(0, 0, $response['categorie']);
							//echo $catList;
						?>


						<?php foreach ($variable as $key => $value) : ?>
							
							<g class="cat-bar">
								<line class="cat-line" x1="1" y1="0" x2="1" y2="56"/>
								<text class="cat-text" x="9" y="0">Ecrit</text>
								<text class="reponses" x="505" y="0">14/24</text>
								<rect class="back" x="9" y="24" width="701" height="32" />
								<rect class="front" x="9" y="24" width="500" height="32" />
								<text class="percent-cat" x="497" y="41">72<tspan class="percent">%<tspan></text>
							</g>



						<?php //endforeach; ?>
						<g class="cat-bar">
							<line class="cat-line" x1="1" y1="0" x2="1" y2="56"/>
							<text class="cat-text" x="9" y="0">Ecrit</text>
							<text class="reponses" x="505" y="0">14/24</text>
							<rect class="back" x="9" y="24" width="701" height="32" />
							<rect class="front" x="9" y="24" width="500" height="32" />
							<text class="percent-cat" x="497" y="41">72<tspan class="percent">%<tspan></text>
						</g>

						<g class="cat-bar">
							<line class="cat-line" x1="1" y1="100" x2="1" y2="156"/>
							<text class="cat-text" x="9" y="100">Oral</text>
							<text class="reponses" x="404" y="100">14/24</text>
							<rect class="back" x="9" y="124" width="701" height="32" />
							<rect class="front" x="9" y="124" width="399" height="32" />
							<text class="percent-cat" x="397" y="141">57<tspan class="percent">%<tspan></text>
						</g>

						<g class="cat-bar">
							<line class="cat-line" x1="1" y1="200" x2="1" y2="256"/>
							<text class="cat-text" x="9" y="200">Calcul</text>
							<text class="reponses" x="306" y="200">14/24</text>
							<rect class="back" x="9" y="224" width="701" height="32" />
							<rect class="front" x="9" y="224" width="301" height="32" />
							<text class="percent-cat" x="299" y="241">43<tspan class="percent">%<tspan></text>
						</g>

						<g class="cat-bar">
							<line class="cat-line" x1="1" y1="300" x2="1" y2="356"/>
							<text class="cat-text" x="9" y="300">Geste/posture/orientation</text>
							<text class="reponses" x="628" y="300">14/24</text>
							<rect class="back" x="9" y="324" width="701" height="32" />
							<rect class="front" x="9" y="324" width="623" height="32" />
							<text class="percent-cat" x="621" y="341">89<tspan class="percent">%<tspan></text>
						</g>
					
						<!-- <g class="cat-bar">
							<line class="cat-line" x1="1" y1="400" x2="1" y2="456"/>
							<text class="cat-text" x="9" y="400">Ecrit</text>
							<text class="reponses" x="663" y="400">14/24</text>
							<rect class="back" x="9" y="424" width="701" height="32" />
							<rect class="front" x="9" y="424" width="658" height="32" />
							<text class="percent-cat" x="656" y="441">94<tspan class="percent">%<tspan></text>
						</g> -->
					</g>
				</svg>

				<!-- 
				<div id="speaker">
						
					<svg id="svg-speaker-bg" version="1.1" viewBox="0 0 52 52" preserveAspectRatio="xMinYMin meet">
						<circle id="speaker-bg" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
					</svg>

					<svg id="svg-speaker-progress" version="1.1" viewBox="0 0 52 52" preserveAspectRatio="xMinYMin meet">
						<circle id="speaker-loader" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
						<circle id="speaker-progress" r="24" transform="translate(26.5, 26) rotate(-90)"></circle>
					</svg>

				</div>
				-->
			</div>
		</div>
