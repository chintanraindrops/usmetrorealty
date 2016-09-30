<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');

// Include IP router once
require_once( JPATH_SITE.'/components/com_iproperty/helpers/route.php');

// Include helper functions only once
require_once( dirname(__FILE__).'/helper.php');
require( JModuleHelper::getLayoutPath( 'mod_ip_catmenu', $params->get('layout', 'default') ) );
?>