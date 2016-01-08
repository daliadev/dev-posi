
$(function() { 

	var animCircle = null;
	var animBars = new Array();

	var percentGlobal = 0;
	
	var circleCircum = 0;
	var circleOffset = 0;
	var circleOffsetTarget = 0;

	var progressBars = new Array();



	function animateBar(index) {

		var $el = progressBars[index].el;
		var maxWidth = progressBars[index].leng;
		var currentWidth = new Number($el.attr('width'));

		var d = maxWidth - currentWidth;
		currentWidth += Math.round(d * 0.1);
		$el.attr('width', currentWidth);
		
		if (index == 0) {
			console.log(currentWidth);
		}
		
		//if (currentWidth >= maxWidth) {
		
			//console.log('end');
			clearInterval(animBars[index]);
		//}
		
		//console.log('en cours');
	}


	function startAnimateBar(index) {

		animBars[index] = setInterval(animateBar, 40, index);
	}


	function animateCircle() {

		d = circleOffset - circleOffsetTarget;
		circleOffset -= d * 0.1;

		$('#circle-percent').css('stroke-dashoffset', circleOffset);


		if (Math.round(circleOffset) <= circleOffsetTarget) {
			
			clearInterval(animCircle);
			
			for (var i = 0; i < progressBars.length; i++) {
				
				var time = i * 500;
				var timer = setTimeout(startAnimateBar, time, i);
			}
		}
	}


	
	$('#bars').children().each(function(i) {
 
		var $bar = $(this).children('.front');
		var barLength = $bar.attr('width');

		progressBars[i] = {
			el: $bar,
			leng: Math.round(barLength)
		}
	});
	
	console.log(progressBars);
	$('#bars .cat-bar .front').attr('width', '0');


	var reponsesCorrectes = new Number($('#reponses-ok').html());
	var nbreReponses = new Number($('#reponses-global').html());
	percentGlobal = Math.round(reponsesCorrectes / nbreReponses * 100);

	var circleCircumValue = $('#circle-percent').css('stroke-dasharray');
	circleCircum = circleCircumValue.substring(0, circleCircumValue.length - 2)
	$('#circle-percent').css('stroke-dashoffset', circleCircum);

	circleOffsetTarget = Math.round(circleCircum - (circleCircum / 100 * percentGlobal));
	circleOffset = circleCircum;

	animCircle = setInterval(animateCircle, 40);

	
});