
$(function() { 

	var animCircle = null;
	var animbar = null;

	var percentGlobal = 0;
	
	var circleCircum = 0;
	var circleOffset = 0;
	var circleOffsetTarget = 0;

	var progressBars = new Array();

	var barsLength = new Array();


	function animateCircle() {

		d = circleOffset - circleOffsetTarget;
		circleOffset -= d * 0.05;

		$('#circle-percent').css('stroke-dashoffset', circleOffset);

		if (circleOffset <= circleOffsetTarget) {

			clearInterval(animCircle);
		}
	}


	function animateBar() {

		
	}



	var reponsesCorrectes = new Number($('#reponses-ok').html());
	var nbreReponses = new Number($('#reponses-global').html());
	percentGlobal = Math.round(reponsesCorrectes / nbreReponses * 100);

	var circleCircumValue = $('#circle-percent').css('stroke-dasharray');
	circleCircum = circleCircumValue.substring(0, circleCircumValue.length - 2)
	$('#circle-percent').css('stroke-dashoffset', circleCircum);

	circleOffsetTarget = Math.round(circleCircum - (circleCircum / 100 * percentGlobal));
	circleOffset = circleCircum;

	animCircle = setInterval(animateCircle, 40);

	//animbar = setInterval(animateBar, 40);

	
});