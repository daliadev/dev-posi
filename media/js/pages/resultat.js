
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
		var $percent = progressBars[index].percent;
		var maxWidth = progressBars[index].leng;
		var currentWidth = new Number($el.attr('width'));

		var d = maxWidth - currentWidth;
		currentWidth += d * 0.15;
		$el.attr('width', currentWidth);
		
		if (Math.round(currentWidth) >= maxWidth) {

			$percent.show();
			clearInterval(animBars[index]);
		}
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
		var $percent = $(this).children('.percent-cat');

		progressBars[i] = {
			el: $bar,
			leng: Math.round(barLength),
			percent: $percent
		}
	});
	
	$('#bars .cat-bar .front').attr('width', '0');
	$('#bars .cat-bar .percent-cat').hide();


	var reponsesCorrectes = new Number($('#reponses-ok').html());
	var nbreReponses = new Number($('#reponses-global').html());

	percent = ($('.text-percent')[0].textContent);
	percentGlobal= percent.substring(0, percent.length - 1);
	console.log(percentGlobal);

	var circleCircumValue = $('#circle-percent').css('stroke-dasharray');
	circleCircum = circleCircumValue.substring(0, circleCircumValue.length - 2);
	$('#circle-percent').css('stroke-dashoffset', circleCircum);

	circleOffsetTarget = Math.round(circleCircum - (circleCircum / 100 * percentGlobal));
	circleOffset = circleCircum;

	animCircle = setInterval(animateCircle, 40);

	
});