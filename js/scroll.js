$(function(){

	$('a[href^=#]').click(function() {
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var position = target.offset().top + 32;
		$('body,html').animate({scrollTop:position}, 500, 'swing');
		return false;
	});

	$('#scrollup').click(function() {
		$('body,html').animate({scrollTop:0}, 400, 'swing');
	});
});