<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Hello Table class
 *
 * @since  0.0.1
 */
class TableSearchcriteriaform extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db)
	{
		parent::__construct('#__iproperty_search_criteria', 'id', $db);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_iproperty.searchcriteriaform', 'SearchcriteriaForm', array('control' => 'jform', 'load_data' => true, 'form_type' => 'request'));
		if (empty($form)) {
			return false;
		}
		//echo "<pre>"; print_r($form); exit;
		return $form;
	}
}
