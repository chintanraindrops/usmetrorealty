<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

class IpropertyTableSettings extends JTable
{
	public function __construct(&$_db)
	{
		parent::__construct('#__iproperty_settings', 'id', $_db);
	}
}
?>