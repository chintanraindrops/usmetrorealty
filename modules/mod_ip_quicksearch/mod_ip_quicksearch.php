<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
require_once('components/com_iproperty/helpers/route.php');

require(JModuleHelper::getLayoutPath('mod_ip_quicksearch', $params->get('layout', 'default')));