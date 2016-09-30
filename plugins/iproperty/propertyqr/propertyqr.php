<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC' ) or die( 'Restricted access');
jimport('joomla.plugin.plugin');

class plgIpropertyPropertyqr extends JPlugin
{
	public function __construct(&$subject, $config)  
    {
		parent::__construct($subject, $config);
        $this->loadLanguage();
	}
	
    public function onAfterRenderProperty($property, $settings)
    {
		$u =& JURI::getInstance();
		$url = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.urlencode($u->toString());
		echo JHTML::image($url, $property->street_address);
    }
} // end class

?>
