<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
 
header("Content-Type: application/json; charset=utf-8");

$url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);

$currencies = array();

if ($data) {
	$xml = simplexml_load_string($data);
	foreach($xml->Cube->Cube->Cube as $a){
		foreach($a->attributes() as $b => $c){
			if ($b == 'currency'){
				$currency = (string) $c;
			} else if ($b == 'rate'){
				$rate = (float) $c;
			}
		}
	$currencies[$currency] = $rate;
	}

	echo json_encode($currencies);
	
} else {
	echo json_encode("FAILED TO LOAD DATA");
}
