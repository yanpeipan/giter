
$(function(){
	resize();
	$(window).resize(resize);
	$('.install-btn p').mouseover(function(){
		$(this).addClass('selected').siblings().removeClass('selected');
	});
});
var resize = function(){
	$('.nav,.main').height($(window).height());
	$('.main-scroll').outerHeight($(window).height()-51);
	$('.agreement-content').outerHeight($(window).height()-282);
	$('.installing').outerHeight($(window).height()-170);
}