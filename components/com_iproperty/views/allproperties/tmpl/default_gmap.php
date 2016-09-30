<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo $this->loadTemplate('toolbar');
defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
jimport('joomla.filesystem.file');

// get category data
$cats = ipropertyHTML::getAllCats();

// check for template map icons
$templatepath       = JFactory::getApplication()->getTemplate();
$map_house_icon     = JURI::root(true).'/components/com_iproperty/assets/images/map/icon56.png';
if(JFile::exists('templates/'.$templatepath.'/images/iproperty/map/icon56.png')) $map_house_icon = JURI::root(true).'/templates/'.$templatepath.'/images/iproperty/map/icon56.png';

// get URL scheme
$scheme				= JURI::getInstance()->getScheme();

$this->document->addScript($scheme.'://maps.google.com/maps/api/js?sensor=false');

$map_js = 'var locations = [];';

foreach($this->items as $item){
	$cat_item = $cats[$item->cat_id];
	if ( !is_null($cat_item) && !empty($cat_item['icon']) && $cat_item['icon'] !== 'nopic.png' ){
		$icon = JURI::root(true).'/media/com_iproperty/categories/'.$cat_item['icon'];
	} else {
		$icon = $map_house_icon;
	}
	
	if($item->lat_pos && $item->long_pos){
		$map_js .= 'locations.push(['.$item->lat_pos.','.$item->long_pos.','.$item->id.',"'.$icon.'"]);';	
	}
}
//$_SERVER['REMOTE_ADDR']
$ip = '68.178.213.203';
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$keyword = JRequest::getvar('filter_keyword');
//$this->results = '';
if(empty($this->results)){
	//$this->results = $this->results;
	$this->results = $details->region;
} else {
	$this->results = $this->results;
}
$prepAddr = str_replace(' ','+',$this->results);
$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
$output = @json_decode($geocode);
$latitude = @$output->results[0]->geometry->location->lat;
$longitude = @$output->results[0]->geometry->location->lng;

//before this code for lattitude and longitude center(120920165)
//var location = new google.maps.LatLng('.$this->settings->adv_default_lat.','.$this->settings->adv_default_long.');
//this code fopr zoom echo $this->settings->adv_default_zoom;
//before this code for max-zoom->maxZoom:$this->settings->max_zoom(13092016)
$map_js .='
jQuery(function($) {
    $(window).load(function(){ 
         
        var width = $("#ip_mainheader").css("width");
        var height = '.$this->params->get('map_height', 300).';
        var location = new google.maps.LatLng('.$latitude.','.$longitude.');
        var mapoptions = {
            zoom: '.$this->settings->adv_default_zoom.',
            center: location,
            scaleControl: false,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            maxZoom: 7
        }
        if(locations.length){
			var bounds = new google.maps.LatLngBounds();
			$("#ip-map-canvas").css({"width": width, "height": height});
			var filters = $("#filter_keyword").val();
			var map = new google.maps.Map(document.getElementById("ip-map-canvas"), mapoptions);
			google.maps.event.trigger(map, "resize");
			map.setCenter(location);
			$.each(locations, function(i, el){
                if (el[0] == 0 || el[1] == 0) return;
				var position = new google.maps.LatLng(el[0],el[1]);
				if(filters == ""){
					bounds.extend(location);
				} else {
					bounds.extend(position);
				}
				map.fitBounds(bounds);
				var marker = new google.maps.Marker({
					position: position,
				    scaleControl: false,
				    scrollwheel: false,
					map: map,
					draggable: false,
					icon: el[3]
				})
                
                // attach infoWindow opener
                google.maps.event.addListener(marker, \'click\', function () {
                    $(\'.ip-overview-row\').removeClass(\'ip-overview-active\');
                    $(\'html,body\').animate({
                         scrollTop: jQuery(\'[id=ip-listing-\'+el[2]+\']\').offset().top
                    }, 500); 
                    $(\'[id=ip-listing-\'+el[2]+\']\').addClass(\'ip-overview-active\');
                });
				checkZoom();
			});
		}
		
		// check zoom level and set to max if zoomed in too far
		function checkZoom(){
			var curzoom = map.getZoom();
			if (curzoom > 10) map.setZoom(8);
		}
    });
});';
$this->document->addScriptDeclaration( $map_js );
echo '
    <div id="ip-map-canvas" class="ip-map-div"></div>
    <div class="clearfix"></div>
    ';
?>
