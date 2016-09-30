<?php
/**
 * IceMegaMenu Extension for Joomla 3.0 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icemegamenu.html
 * @Support 	http://www.icetheme.com/Forums/IceMegaMenu/
 *
 */



$icemegamenu->render($params, 'modIceMegaMenuXMLCallback');
?>
<script>
	jQuery(function($){
		$('#icemegamenu li.parent[class^="iceMenuLiLevel"]').hover(function(){
			$('#icemegamenu li.parent[class^="iceMenuLiLevel"]').not($(this).parents('li')).not($(this)).removeClass('hover');
			$(this).addClass('hover').attr('data-hover','true')
			$(this).find('>ul.icesubMenu').addClass('visible')
		},
		function(){
			$(this).attr('data-hover','false')
			$(this).delay(800).queue(function(n){
				if($(this).attr('data-hover') == 'false'){
					$(this).removeClass('hover').delay(250).queue(function(n){
						if($(this).attr('data-hover') == 'false'){
							$(this).find('>ul.icesubMenu').removeClass('visible')
						}
						n();
					});
				}
				n();
			})
		})
		var ismobile = navigator.userAgent.match(/(iPhone)|(iPod)|(iPad)|(android)|(webOS)/i)
		if(ismobile && screen.width>767){
			$('#icemegamenu').sftouchscreen();
		}
		/*$(window).load(function(){
			$('#icemegamenu').parents('[id*="-row"]').scrollToFixed({minWidth: 768});
		})*/
	});
</script>