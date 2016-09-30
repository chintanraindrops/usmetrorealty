<?php
/**
 * @version 3.3 6-2014
 * @package Joomla
 * @subpackage Intellectual Property Spark Platform
 * @copyright (C) 2009-2014 the Thinkery
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
require_once('components/com_iproperty/helpers/route.php');

require(JModuleHelper::getLayoutPath('mod_ip_zsearch', $params->get('layout', 'default')));
