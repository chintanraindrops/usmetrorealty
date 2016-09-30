<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
jimport('joomla.filesystem.file');

$map_house_icon = JURI::root().'/components/com_iproperty/assets/images/map/icon56.png';
?>
<input type="hidden" id="map_icon" name="mapicon" value="<?php echo $map_house_icon; ?>" />
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<?php //echo $this->markers;exit; ?>
<div id="map" style="height: 400px; width: 1000px;"></div>
<script type="text/javascript">
    /*var locations = [
      ['Bondi Beach', -33.890542, 151.274856, 4],
      ['Coogee Beach', -33.923036, 151.259052, 5],
      ['Cronulla Beach', -34.028249, 151.157507, 3],
      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
      ['Maroubra Beach', -33.950198, 151.259302, 1]
    ];*/

    var locations = <?php echo ($this->markers) ? $this->markers : '[]' ; ?>;
    var map_icon = document.getElementById('map_icon').value;
    console.log(map_icon);

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 8,
      center: new google.maps.LatLng(34.0489, -111.0937),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) { 
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: map_icon
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>
