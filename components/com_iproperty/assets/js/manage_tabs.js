jQuery(document).ready(function($) {
	$('#ip-next').click(function(){
		$('.nav-tabs > .active').next('li').find('a').trigger('click');
	});

	$('#ip-previous').click(function(){
		$('.nav-tabs > .active').prev('li').find('a').trigger('click');
	});
});
