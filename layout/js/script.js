$(function(){
	'use strict';
	console.log("Sd");
	$('.forms  button').click(function(){
		$(this).addClass('active').siblings().removeClass('active');
		$('.forms form').hide();
		console.log('.' + $(this).data('class'));
 		$('.' + $(this).data('class')).fadeIn(100);
	});
});