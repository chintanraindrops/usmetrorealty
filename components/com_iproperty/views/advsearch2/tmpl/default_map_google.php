<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

$mapsurl = '//maps.googleapis.com/maps/api/js?sensor=false';
// set locale
$mapsurl .= $this->settings->map_locale ? '&language='.$this->settings->map_locale : '';
if ($this->params->get('adv_show_shapetools', $this->settings->adv_show_shapetools)) $mapsurl .= '&libraries=drawing';

// include map scripts
$this->document->addScript(JURI::root(true).'/components/com_iproperty/assets/advsearch/markerclusterer_packed.js');
$this->document->addScript($mapsurl);
$this->document->addScript(JURI::root(true).'/components/com_iproperty/assets/advsearch/gmap2.js');

echo '<div id="ip-map-canvas" class="ip-map-div"></div>';
