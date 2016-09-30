<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', '.ipform select');

JHtml::script(Juri::base() . 'media/system/js/jquery.formatCurrency.js');

// Create shortcut to parameters.
$params = $this->state->get('params');

// Change header element background to match template per IP settings
$headers_array = array('description', 'geocode', 'general_amen', 'exterior_amen', 'interior_amen');
foreach($headers_array as $h){
    $this->form->setFieldAttribute($h.'_header', 'color', $this->settings->accent);
    $this->form->setFieldAttribute($h.'_header', 'tcolor', $this->settings->secondary_accent);
}

// change measurement units label depending on settings
$this->form->setFieldAttribute('sqft', 'label', (!$this->settings->measurement_units) ? JText::_('COM_IPROPERTY_SQFT') : JText::_('COM_IPROPERTY_SQM'));

$app        = JFactory::getApplication();
$document   = JFactory::getDocument();
$curr_lang  = JFactory::getLanguage();
$languages  = JLanguageHelper::getLanguages('lang_code'); 
$languageCode = $languages[ $curr_lang->getTag() ]->sef;
         
// check if maps are enabled, and use google for geocoding
if($this->settings->map_provider)
{
    // set defaults from item properties or default settings
    $lat        = ($this->item->latitude) ? $this->item->latitude : $this->settings->adv_default_lat;
    $lon        = ($this->item->longitude) ? $this->item->longitude : $this->settings->adv_default_long;
    $start_zoom = ($this->item->latitude) ? '13' : $this->settings->adv_default_zoom;
    $kml        = ($this->item->kml) ? JURI::root(true).'/media/com_iproperty/kml/'.$this->item->kml : false;

    $map_script = "    
		var map_options = {
			startLat: '".$lat."',
			startLon: '".$lon."',
			startZoom: ".(int)$start_zoom.",
			mapDiv: 'ip-map-canvas',
			clickResize: '#proplocation',
			credentials: '".$this->settings->map_credentials."',
			kml: '".$kml."'
		};
		
		// create gallery options
		var ipGalleryOptions = {
			propid: ".(int)$this->item->id.",
			iptoken: '".JSession::getFormToken()."',
			ipbaseurl: '".JURI::root()."',
			ipthumbwidth: '".$this->settings->thumbwidth."',
			iplimitstart: 0,
			iplimit: 50,
			ipmaximagesize: '".$this->settings->maximgsize."',
			ipfilemaxupload: 0,
			pluploadpath: '".JURI::root()."components/com_iproperty/assets/js',
			debug: false,
			language: {
				save: '".addslashes(JText::_('COM_IPROPERTY_SAVE'))."',
				del: '".addslashes(JText::_('COM_IPROPERTY_DELETE'))."',
				edit: '".addslashes(JText::_('COM_IPROPERTY_EDIT'))."',
				add: '".addslashes(JText::_('COM_IPROPERTY_ADD'))."',
				confirm: '".addslashes(JText::_('COM_IPROPERTY_CONFIRM'))."',
				ok: '".addslashes(JText::_('JYES'))."',
				cancel: '".addslashes(JText::_('JCANCEL'))."',
				iptitletext: '".addslashes(JText::_('COM_IPROPERTY_TITLE'))."',
				ipdesctext: '".addslashes(JText::_('COM_IPROPERTY_DESCRIPTION'))."',
				noresults: '".addslashes(JText::_('COM_IPROPERTY_NO_RESULTS'))."',
				updated: '".addslashes(JText::_('COM_IPROPERTY_UPDATED'))."',
				notupdated: '".addslashes(JText::_('COM_IPROPERTY_NOT_UPDATED'))."',
				previous: '".addslashes(JText::_('COM_IPROPERTY_PREVIOUS'))."',
				next: '".addslashes(JText::_('COM_IPROPERTY_NEXT'))."',
				of: '".addslashes(JText::_('COM_IPROPERTY_OF'))."',
				fname: '".addslashes(JText::_('COM_IPROPERTY_FNAME'))."',
				overlimit: '".addslashes(JText::_('COM_IPROPERTY_OVERIMGLIMIT'))."',
				warning: '".addslashes(JText::_('COM_IPROPERTY_WARNING'))."',
				uploadcomplete: '".addslashes(JText::_('COM_IPROPERTY_UPLOAD_COMPLETE'))."'
			},
			client: '".$app->getName()."',
			allowedFileTypes: [{title : 'Files', extensions : 'jpg,gif,png,pdf,doc,txt,jpeg,mp4'}]
		};
       
       jQuery(document).ready(function($){
			$('#clear_geopoint').click(function(){
				$('#jform_latitude').val('');
				$('#jform_longitude').val('');
			});
		});";
    $document->addScriptDeclaration($map_script);
    $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/manage_tabs.js');
    
    if (!defined('IPGMAP')){
		$mapsurl = $params->get('maptype', 0) ? '//ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&mkt='.$curr_lang->get('tag') : '//maps.googleapis.com/maps/api/js?sensor=false';
		$document->addScript( $mapsurl );
		define("IPGMAP", 1);
	}
    
    // add map scripts
    switch ($this->settings->map_provider){
        case 1: // GOOGLE
            $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/fields/gmapField.js' );
        break;
        case 2: // BING
            $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/fields/bingmapField.js' );
        break;
    }
}
?>
<style>
#jform_vtour{height: 75px;}
</style>
<script type="text/javascript">
function checkInt(fieldid, fieldval){
            if (fieldval.match(/[^\d\.]/g)) {
                console.log('falseee');
                jQuery('#'+fieldid).val('');
                jQuery('#'+fieldid).addClass('invalid');
                jQuery('#'+fieldid+'-lbl').addClass('invalid');
                jQuery('#'+fieldid).after('<span class="invalid">Only Interger is Valid</span>');
                //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');
            } else {
                //console.log('true');
                jQuery('#'+fieldid).removeClass('invalid');
                jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            }
        }

    function checkAlpha(fieldid, fieldval){
        if (fieldval.match(/^[0-9]+$/)) {
            //console.log('false');
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid">Only Interger is Valid</span>');
            //jQuery('#'+fieldid).attr('placeholder','Only Alpha character is Valid');
        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }

	Joomla.submitbutton = function(task) 
    {
        document.formvalidator.setHandler('coord', function(value) {
			if (isNaN(value) == false) return true;
			return false;
	    });
        
        if (task == 'propform.cancel'){
            
            <?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
        }else if(document.formvalidator.isValid(document.id('adminForm'))) {
            if(jQuery( "#jform_price" ).val() == ''){
                jQuery('#jform_price').addClass('required');
                jQuery('#jform_price').attr('required','required');
                jQuery('#jform_price-lbl').addClass('required');

                jQuery('#jform_price').addClass('invalid');
                jQuery('#jform_price-lbl').addClass('invalid');
                jQuery('#jform_price').after('<span class="invalid" id="txt_blank_price">Please Enter Price</span>');

                 jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                
                var datatabe = jQuery("#jform_price").attr("data-tab");

                jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
            } else {
                jQuery('#txt_blank_price').remove();
            }
            if(jQuery( "#jform_street_num" ).val() == ''){
                jQuery('#jform_street_num').addClass('required');
                jQuery('#jform_street_num').attr('required','required');
                jQuery('#jform_street_num-lbl').addClass('required');

                jQuery('#jform_street_num').addClass('invalid');
                jQuery('#jform_street_num-lbl').addClass('invalid');
                jQuery('#jform_street_num').after('<span class="invalid" id="txt_blank_num">Please Enter Street Number</span>');

                 jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                
                var datatabe = jQuery("#jform_street_num").attr("data-tab");

                jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
            } else {
                jQuery('#txt_blank_num').remove();
            }
            if(jQuery( "#jform_street" ).val() == ''){
                jQuery('#jform_street').addClass('required');
                jQuery('#jform_street').attr('required','required');
                jQuery('#jform_street-lbl').addClass('required');

                jQuery('#jform_street').addClass('invalid');
                jQuery('#jform_street-lbl').addClass('invalid');
                jQuery('#jform_street').after('<span class="invalid" id="txt_blank_address">Please Enter Street Address</span>');

                 jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                
                var datatabe = jQuery("#jform_street").attr("data-tab");

                jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
            } else {
                jQuery('#txt_blank_address').remove();
            }
            if(jQuery( "#jform_city" ).val() == ''){
                //alert(jQuery( "#jform_city" ).val());
                jQuery('#jform_city_chzn').addClass('required');
                jQuery('#jform_city_chzn').attr('required','required');
                jQuery('#jform_city-lbl').addClass('required');

                /*jQuery('#jform_city_chzn').addClass('invalid');*/
                jQuery('#jform_city-lbl').addClass('invalid');
                jQuery('#jform_city_chzn').after('<span class="invalid" id="drop_blank_city">Please Select City</span>');

                 jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                
                var datatabe = jQuery("#jform_city").attr("data-tab");

                jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
            } else {
                jQuery('#drop_blank_city').remove();
            }

            if(jQuery( "#jform_beds" ).val() != ''){
                var regex = /^[0-9]+$/;
                if(!regex.test(jQuery( "#jform_beds" ).val())){
                        var bed_id = jQuery("#jform_beds").attr('id');
                        var bed_value = jQuery( "#jform_beds" ).val();
                        console.log(bed_id);
                        checkInt(bed_id,bed_value);
                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            if(jQuery( "#jform_baths" ).val() != ''){
                    /*var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_baths" ).val())){
                        var bath_id = jQuery("#jform_baths").attr('id');
                        var bath_value = jQuery( "#jform_baths" ).val();
                        console.log(bath_value);
                        checkInt(bath_id, bath_value);

                        var datatabe = jQuery("#"+bath_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }*/
                    var regex = /^\d+(.\d{1,2})?$/;
                    if(!regex.test(jQuery( "#jform_baths" ).val())){
                        
                        var bath_id = jQuery("#jform_baths").attr('id');
                        var bath_value = jQuery( "#jform_baths" ).val();
                        jQuery('#'+bath_id).val('');
                        jQuery('#'+bath_id).addClass('invalid');
                        jQuery('#'+bath_id+'-lbl').addClass('invalid');
                        jQuery('#'+bath_id).after('<span class="invalid">Only Interger is Valid</span>');



                        var datatabe = jQuery("#"+bath_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    } else {
                        jQuery('#'+bath_id).removeClass('invalid');
                        jQuery('#'+bath_id+'-lbl').removeClass('invalid');
                    }
            }
            /*if(jQuery( "#jform_kitchen" ).val() != ''){
                    var regex = /^[a-z]+$/;   
                    if(!regex.test(jQuery( "#jform_kitchen" ).val())){
                        var kitchen_id = jQuery("#jform_kitchen").attr('id');
                        var kitchen_value = jQuery( "#jform_kitchen" ).val();
                        //console.log(kitchen_value);
                        checkAlpha(kitchen_id, kitchen_value);

                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }*/
            if(jQuery( "#jform_garage_size" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_garage_size" ).val())){
                        var garage_id = jQuery("#jform_garage_size").attr('id');
                        var garage_value = jQuery("#jform_garage_size").val();
                        console.log(garage_value);
                        checkInt(garage_id, garage_value);

                        var datatabe = jQuery("#"+garage_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            <?php if($this->ipauth->getAdmin()): //only confirm company if admin user ?>
                if(document.id('jform_listing_office').selectedIndex == ''){
                    alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_COMPANIES')); ?>');
                    return false;
                }
            <?php endif; ?>
            <?php if($this->ipauth->getAdmin() || $this->ipauth->getSuper()): //only confirm agnets if admin or super agent ?>
                if(document.id('jform_agents').selectedIndex == ''){
                    alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_AGENT')); ?>');
                    return false;
                }
            <?php endif; ?>
            if(document.id('jform_stype').selectedIndex == ''){
                alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_STYPE')); ?>');
                return false;
            }/*else if(document.id('jform_categories').selectedIndex == ''){
                alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_CATEGORY')); ?>');
                return false;
            }*/
            

			<?php echo $this->form->getField('description')->save(); ?>
			//alert(task);
            Joomla.submitform(task);
		} else {
            var fields, invalid = [], valid = true, label, error, i, l;
            fields = jQuery('form.form-validate').find('input, textarea, select');
            //console.log(fields);
            if (!document.formvalidator.isValid(jQuery('form'))) {
                for (i = 0, l = fields.length; i < l; i++) {
                    if (document.formvalidator.validate(fields[i]) === false) {
                        valid = false;
                        invalid.push(fields[i]);
                    }
                }

            // Run custom form validators if present
            jQuery.each(document.formvalidator.custom, function (key, validator) {
                if (validator.exec() !== true) {
                    valid = false;
                }
            });

            if (!valid && invalid.length > 0) {
                error = {"error": []};

                for (i = invalid.length - 1; i >= 0; i--) {
                    //console.log(i);
                    // console.log(invalid[i]);
                    //label = jQuery.trim($(invalid[i]).data("id").text().replace("*", "").toString());
                    var inputId = jQuery(invalid[i]).attr("id");
                    console.log(inputId);
                    //red line remove(alert mesage div)
                    jQuery('#system-message-container').remove();
                    /*if (inputId) {
                        if(inputId === 'jform_price') {                            
                            error.error.push('Please Enter Price');
                        }if(inputId === 'jform_street_num') {
                            error.error.push('Please Enter Street Number');
                        }if(inputId === 'jform_street') {                            
                            error.error.push('Please Enter Street Address');
                        }if(inputId === 'jform_city') {
                            error.error.push('Please Enter Select City');
                        }
                    }*/
                }
            }
            Joomla.renderMessages(error);
        }
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

    jQuery(document).ready(function(){

    

        jQuery("#jform_price").formatCurrency();
        jQuery("#jform_price2").formatCurrency();

        jQuery("#jform_price").blur(function () {
            jQuery("#jform_price").formatCurrency();
        });

        jQuery("#jform_price2").blur(function () {
            jQuery("#jform_price2").formatCurrency();
        });

        setTimeout(function(){
     var countryArr = jQuery( "#jform_country" ).val();loadStates(countryArr); }, 100);

        jQuery( "#jform_country" ).change(function() { 
            console.log(jQuery(this).val());
            var countryArr = jQuery(this).val();
            if(countryArr !== ""){
                loadStates(countryArr);
            }
        });

checkAgentMLS = function(){
        document.id('system-message-container').set('tween');
        var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_iproperty&task=ajax.checkAgentMLS';
        var agentMLS = document.id('jform_mls_id').value;
        //alert(agentMLS);
        req = new Request({
            method: 'post',
            url: checkurl,
            data: { 'mls_id': agentMLS,
                    'format': 'raw'},
            onRequest: function() {
                document.id('mls_error').set('html', '');
            },
            onSuccess: function(response) {
                if(response){
                    document.id('mls_error').highlight('#ff0000');
                    document.id('jform_mls_id').value = '';
                    document.id('jform_mls_id').set('class', 'inputbox invalid');
                    document.id('jform_mls_id').focus();
                    document.id('mls_error').set('html', '<div class="ip_warning" style="color:red;"><?php echo JText::_('A Property with this MLS already exists....'); ?></div>');                    
                }
            }
        }).send();
    }


        function loadStates(val){

            jQuery.ajax({
                type:"GET",
                url : "index.php?option=com_iproperty&task=searchcriteriaform.getStates",
                data : "countries="+val,
                async: false,
                success : function(data) {
                   var obj = JSON.parse(data);

                        jQuery(".region").html("<option value=''>Select States</option>");
                        jQuery.each(obj, function(i, m){
                            
                            jQuery(".region").append("<option value='"+m.value+"'>"+m.text+"</option>").trigger( "liszt:updated" );
                            
                        });
                 
                },
                error: function() {
                    alert('Error occured');
                }
            });
        }

        jQuery( "#jform_locstate" ).change(function() {
            console.log(jQuery(this).val());
            var stateArr = jQuery(this).val();
            console.log(stateArr);
            if(stateArr !== ""){
                loadCities(stateArr);
            }
        });

        function loadCities(val){

            jQuery.ajax({
                type:"GET",
                url : "index.php?option=com_iproperty&task=searchcriteriaform.getCities",
                data : "states="+val,
                async: false,
                success : function(data) {
                   var obj = JSON.parse(data);

                        jQuery(".city").html("<option value=''>Select City</option>");
                        jQuery.each(obj, function(i, m){
                            
                            jQuery(".city").append("<option value='"+m.value+"'>"+m.text+"</option>").trigger( "liszt:updated" );
                            
                        });

                 
                },
                error: function() {
                    alert('Error occured');
                }
            });
        }

        jQuery('#jform_city').change(function(){
            var cityname = jQuery("#jform_city option:selected").text();
            var geocoder =  new google.maps.Geocoder();
            geocoder.geocode( { 'address': cityname}, function(results, status) { //miami, us
                if (status == google.maps.GeocoderStatus.OK) {
                    if(results[0].geometry.location.lat() !== ""){
                        jQuery('#jform_latitude').val(results[0].geometry.location.lat());
                    }
                    if(results[0].geometry.location.lng() !== ""){
                        jQuery('#jform_longitude').val(results[0].geometry.location.lng());
                    }
                    console.log("location : " + results[0].geometry.location.lat() + " " +results[0].geometry.location.lng()); 
                    addMarker(results[0].geometry.location.lat(), results[0].geometry.location.lng(), 'ip-map-canvas');
                    /*marker.setPosition(results[0].geometry.location);
                    var myLatlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                    var myOptions = {
                        zoom: <?php echo (int)$start_zoom ?>,
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                    }
                    var map = new google.maps.Map(document.getElementById('ip-map-canvas'), myOptions);*/
                } else {
                    console.log("Something got wrong " + status);
                }
            });
        });

    /*jQuery('.checkint').on('clic', function(){
        //console.log('sdfsdfgdfgdf');
        checkInt(this.id, this.value);
    });
*/
    function addMarker(lat, lng, mapId)
    {
        var mapOptions = {
            center: new google.maps.LatLng(lat, lng),
            zoom: 5,
            scrollwheel: false,
            disableDefaultUI: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById(mapId), mapOptions);

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat, lng),
            map: map
        });
    }
    
var lia = jQuery('#ip-propviewTabs li a');
    console.log(lia);
    jQuery.each( lia, function( key, value ) {
      console.log( lia[key] );
      var the_href = jQuery(lia[key]).attr('href').substring(1);
      console.log( the_href );
      console.log(key);
      cuskey = key+1;
      jQuery( "#ip-propviewTabs li:nth-child("+cuskey+")" ).addClass(the_href);

      jQuery('#'+the_href+' input').attr('data-tab', the_href);
      jQuery('#'+the_href+' select').attr('data-tab', the_href);
    });

    
});    
</script>
<?php echo $this->loadTemplate('toolbar'); ?>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1>
            <?php echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php endif; ?>
    <div class="ip-mainheader">
        <h2><?php echo $this->iptitle; ?></h2>
    </div>
    <div id="system-message-container"></div>
    <form action="<?php echo JRoute::_('index.php?option=com_iproperty&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="btn-toolbar">
			<div class="btn-group">
                <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('propform.apply')">
                    <i class="icon-edit"></i> <?php echo JText::_('COM_IPROPERTY_APPLY') ?>
                </button>
                <button type="button" class="btn" onclick="Joomla.submitbutton('propform.save')">
                    <i class="icon-ok"></i> <?php echo JText::_('JSAVE') ?>
                </button>
                <?php if ($this->item->id && $this->ipauth->canAddProp()): ?>
                    <button type="button" class="btn" onclick="Joomla.submitbutton('propform.save2copy')">
                        <i class="icon-copy"></i> <?php echo JText::_('COM_IPROPERTY_CLONE') ?>
                    </button>
                <?php endif; ?>
                <button type="button" class="btn" onclick="Joomla.submitbutton('propform.cancel')">
                    <i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
        </div>
        <?php 
        echo JHtmlBootstrap::startTabSet('ip-propview', array('active' => 'propgeneral'));
        echo JHtmlBootstrap::addTab('ip-propview', 'propgeneral', JText::_('COM_IPROPERTY_DESCRIPTION')); ?>
            <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                <?php if($this->settings->showtitle): ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('title'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('title'); ?>
                        </div>
                    </div>
                <?php endif; ?>       

                <?php if (is_null($this->item->id) || $this->ipauth->getAdmin()):?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('alias'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('alias'); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('alias'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getValue('alias'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('mls_id'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('mls_id'); ?>
                        <span id="mls_error"></span>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('available'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('available'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('expired'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('expired'); ?>
                    </div>
                </div>                                    
                <?php if($this->ipauth->getAdmin()): //only show company if admin user ?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('listing_office'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('listing_office'); ?>
                        </div>
                    </div>
                <?php else: //if not admin, set the listing office as the user agent company id ?>
                    <?php if($this->form->getValue('listing_office')): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('listing_office'); ?>
                            </div>
                            <div class="controls">
                                <?php echo ipropertyHTML::getCompanyName($this->form->getValue('listing_office')); ?>
                            </div>
                        </div>
                        <input type="hidden" name="jform[listing_office]" value="<?php echo $this->form->getValue('listing_office'); ?>" />
                    <?php else: ?>
                        <input type="hidden" name="jform[listing_office]" value="<?php echo $this->ipauth->getUagentCid(); ?>" />
                    <?php endif; ?>
                <?php endif; ?>            
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('stype'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('stype'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('price'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('price'); ?>
                        <!-- &nbsp;<?php echo JText::_('COM_IPROPERTY_PER'); ?>&nbsp;
                        <?php echo $this->form->getInput('stype_freq'); ?> -->
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('price2'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('price2'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('call_for_price'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('call_for_price'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('vtour'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('vtour'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('categories'); ?>                            
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('categories'); ?>                            
                    </div>
                </div>
                <?php if($this->ipauth->getAdmin() || $this->ipauth->getSuper()): //only show agents if admin or super agent user ?>
                    <div class="control-group" style="display:none">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('agents'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('agents'); ?>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="jform[agents][]" value="" />
                <?php endif; ?>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('short_description'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('short_description'); ?>
                    </div>
                </div>
                <div class="control-group form-vertical">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('description_header'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('description'); ?>
                    </div>
                </div>           
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'proplocation', JText::_('COM_IPROPERTY_LOCATION'));
        ?>
            <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_LOCATION'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('hide_address'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('hide_address'); ?>
                    </div>
                </div> 
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('street_num'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('street_num'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('street'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('street'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('street2'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('street2'); ?>
                    </div>
                </div> 
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('apt'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('apt'); ?>
                    </div>
                </div> 
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('subdivision'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('subdivision'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('country'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('country'); ?>
                    </div>
                </div> 
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('locstate'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('locstate'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('city'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('city'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('county'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('county'); ?>
                    </div>
                </div>
                <!-- <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('region'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('region'); ?>
                    </div>
                </div>  -->
                <!-- <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('province'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('province'); ?>
                    </div>
                </div> -->
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('postcode'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('postcode'); ?>
                    </div>
                </div>
                <div class="clearfix"></div>

                <?php if($this->settings->map_provider): ?>
                    <?php echo $this->form->getLabel('geocode_header'); ?>                        
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('show_map'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('show_map'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('latitude'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('latitude'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('longitude'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('longitude'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('kml'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('kml'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('kmlfile'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            &nbsp;
                        </div>
                        <div class="controls">
                            <button id="clear_geopoint" class="btn btn-warning" type="button"><?php echo JText::_('JSEARCH_FILTER_CLEAR');?></button>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="control-group">
                        <div class="controls">
                            <?php echo $this->form->getInput('map'); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propdetails', JText::_('COM_IPROPERTY_DETAILS'));
        ?>
            <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                <div class="row-fluid">
                    <div class="span6 pull-left form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('beds'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('beds'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('baths'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('baths'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('total_units'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('total_units'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sqft'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sqft'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lotsize'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lotsize'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_acres'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_acres'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_type'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('heat'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('heat'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('cool'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('cool'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('fuel'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('fuel'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_type'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span6 pull-right form-vertical">
						<div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_size'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_size'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('siding'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('siding'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('roof'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('roof'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reception'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reception'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('tax'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('tax'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('income'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('income'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('yearbuilt'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('yearbuilt'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('zoning'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('zoning'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('propview'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('propview'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('school_district'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('school_district'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('style'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('style'); ?>
                            </div>
                        </div>
                        <?php if($this->settings->adv_show_wf): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('frontage'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('frontage'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($this->settings->adv_show_reo): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reo'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reo'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($this->settings->adv_show_hoa): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('hoa'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('hoa'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propamens', JText::_('COM_IPROPERTY_AMENITIES'));
        ?>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('general_amen_header'); ?>
                    <?php echo $this->form->getInput('general_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('interior_amen_header'); ?>
                    <?php echo $this->form->getInput('interior_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('exterior_amen_header'); ?>
                    <?php echo $this->form->getInput('exterior_amens'); ?>
                </div>
            </div>
			<div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('accessibility_amen_header'); ?>
                        <?php echo $this->form->getInput('accessibility_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('green_amen_header'); ?>
                        <?php echo $this->form->getInput('green_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('security_amen_header'); ?>
                        <?php echo $this->form->getInput('security_amens'); ?>
                    </div>
            </div>
				<div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('landscape_amen_header'); ?>
                        <?php echo $this->form->getInput('landscape_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('community_amen_header'); ?>
                        <?php echo $this->form->getInput('community_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('appliance_amen_header'); ?>
                        <?php echo $this->form->getInput('appliance_amens'); ?>
                    </div>
                </div>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propimages', JText::_('COM_IPROPERTY_IMAGES').' / '.JText::_('COM_IPROPERTY_VIDEO'));
        ?>
            <div class="row-fluid">
                <ul class="nav nav-tabs ip-vid-tab">
                    <li class="active"><a href="#imgtab" data-toggle="tab"><?php echo (($this->user_type == 3) ? JText::_('COM_IPROPERTY_IMAGES_AND_VIDEO') : JText::_('COM_IPROPERTY_IMAGES_AND_DOCS_AND_VIDEO') );?></a></li> <!-- [[CUSTOM]] RI, for making diff label for sellers -->
                    <li><a href="#vidtab" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_EMBED_VIDEO');?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="imgtab">
                        <div class="clearfix"></div>                        
                        <?php if($this->item->id): ?>
                            <?php echo $this->form->getInput('gallery'); ?>
                        <?php else: ?>
                            <div class="alert alert-info"><?php echo JText::_('COM_IPROPERTY_SAVE_BEFORE_IMAGES'); ?></div>
                        <?php endif; ?> 
                    </div>
                    <div class="tab-pane" id="vidtab">
                    
		                <div class="control-group form-vertical">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('video'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('video'); ?>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
        <?php
        echo JHtmlBootstrap::endTab();
        /*echo JHtmlBootstrap::addTab('ip-propview', 'propother', JText::_('COM_IPROPERTY_OTHER'));
        ?>
            <!-- <fieldset class="adminform">
                <legend><?php echo JText::_('COM_IPROPERTY_NOTES'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('agent_notes'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('agent_notes'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('terms'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('terms'); ?>
                    </div>
                </div>
            </fieldset> -->
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_IPROPERTY_PUBLISHING'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('access'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('access'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('publish_up'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('publish_up'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('publish_down'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('publish_down'); ?>
                    </div>
                </div>
            </fieldset>
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_IPROPERTY_META_INFO'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('metakey'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('metakey'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('metadesc'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('metadesc'); ?>
                    </div>
                </div>
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();*/
        $this->dispatcher->trigger('onAfterRenderPropertyEdit', array($this->item, $this->settings ));
        echo JHtmlBootstrap::endTabSet();
        ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
        <?php echo JHtml::_( 'form.token'); ?>
    </form>
    <div class="ip-manage-pagination pull-right">
	  <button id="ip-previous" class="btn btn-small" type="button"><?php echo JText::_('COM_IPROPERTY_PREVIOUS'); ?></button>
	  <button id="ip-next" class="btn btn-small" type="button"><?php echo JText::_('COM_IPROPERTY_NEXT'); ?></button>
	</div>
</div>

<?php if($this->item->id): 
    
    jimport('joomla.filesystem.file');
    
    // plupload scripts

	$document->addStyleSheet( "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" );
    $document->addStyleSheet( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" );
    //$document->addStyleSheet( JURI::root(true).'/components/com_iproperty/assets/css/jquery.videocontrols.css' );
	
	$document->addScript( "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" );
    $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/plupload.full.min.js" );
    $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js" );
    // include language file for uploader if it exists
    if(JFile::exists(JPATH_SITE.'/components/com_iproperty/assets/js/plupload/js/i18n/'.$languageCode.'.js')){
        $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/i18n/".$languageCode.".js" );
    }

    // sortable tables
    $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/ipsortables.js');
    $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/ipsortables_docs.js');

    // videocontrols
    //$document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/jquery.videocontrols.js');
	
	// ****************************
	// not sure if this should stay -- hosted version of the FULL bootstrap so icons etc. work
	//$document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/image.resize.js');
	$document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/bootbox.min.js');
    
    ?>
    <script type="text/javascript">                                   
        (function($) {              
            // create auto complete             
            $.each(['city','province','region','county'], function(index, value){
                var url = '<?php echo JURI::base('true'); ?>/index.php?option=com_iproperty&task=ajax.ajaxAutocomplete&format=raw&field='+value+'&<?php echo JSession::getFormToken(); ?>=1';
                $.getJSON(url).done(function( data ){
                    $('#jform_'+value).typeahead({source: data, items:5});
                });  
            });
        })(jQuery);
    </script>
<?php endif; ?>
<style>
#jform_country_chzn{width:220px !important;}
#jform_locstate_chzn{width:220px !important;}
#jform_city_chzn{width:220px !important;}
#jform_total_units_chzn{width:220px !important;}
#jform_garage_type_chzn{width:220px !important;}
</style>