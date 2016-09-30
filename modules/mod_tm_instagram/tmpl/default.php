<?php
/**
 * Instagram for Joomla! Module
 *
 * @author    TemplateMonster http://www.templatemonster.com
 * @copyright Copyright (C) 2012 - 2013 Jetimpex, Inc.
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 
 * 
 */

defined('_JEXEC') or die;

?>
<div class="mod_tm_instagram" id="module_<?php echo $module->id; ?>">
	<ul>
	</ul>
</div>

<script>
jQuery("#module_<?php echo $module->id; ?> ul").jtminstagram({
  client_id: "<?php echo $CLIENT_ID; ?>",
  user_name: "<?php echo $USER_NAME; ?>",
  count: <?php echo $AdminPhotoCount; ?>,
  afterLoad: function(){
  	jQuery('.mod_tm_instagram li a').fancybox({
	    padding: 0,
	    margin: 0,
	    loop: true,
	    openSpeed:500,
	    closeSpeed:500,
	    nextSpeed:500,
	    prevSpeed:500,
	    afterLoad : function (){
	      jQuery('.fancybox-inner').click(function(){
	        if(click == true){
	          jQuery('body').toggleClass('fancybox-full');
	        }
	      })
	    },
	    beforeShow: function() {
	      jQuery('body').addClass('fancybox-lock');
	    },
	    afterClose : function() {
	      jQuery('body').removeClass('fancybox-lock');
	    },
	    tpl : {
	      image    : '<div class="fancybox-image" style="background-image: url(\'{href}\');"/>'
	    },
	    helpers: {
	      title : null,
	      thumbs: {
	        height: 50,
	        width: 80
	      },
	      overlay : {
	        css : {
	          'background' : '#191919'
	        }
	      }
	    }
	  });
  }
});
</script>