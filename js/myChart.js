
var canvas = $($('canvas')[0]);
var w = canvas.width();
var h = canvas.height();
$('canvas').attr({width: w, height: h});

$('.chart').each(function(i){

	var gage = parseInt($(this).find('em').text(), 10);
	console.log(gage);

	var data = [
		{
			value: gage,
			color:"#fff"
		},
		{
			value : (100-gage),
	        // Draw with background color
			color : "#519bbe" 
		}
	];
	var ctx = $(this).find('.myChart')[0].getContext("2d");
	new Chart(ctx).Doughnut(data,{
		segmentShowStroke : false,
		segmentStrokeColor : "#fff",
		segmentStrokeWidth : 1,
		percentageInnerCutout : 80, // **** Border width
		animation : true,
		animationSteps : 100,
		animationEasing : "easeOutBounce",
		animateRotate : true,
		animateScale : false,
		onAnimationComplete : null
	});
});
