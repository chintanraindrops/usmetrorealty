<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class ipropertyViewSearchcriteriaForm extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		$app        = JFactory::getApplication();
        $document   = JFactory::getDocument();
        $user       = JFactory::getUser();

        // Assign data to the view
		$this->msg = 'search criteria Form';
		$this->layout = JRequest::getVar('layout');
		$this->state        = $this->get('State');
        $this->item         = $this->get('Item');
        $this->form         = $this->get('Form');
        $this->return_page  = $this->get('ReturnPage');
        $this->settings     = ipropertyAdmin::config();
        $this->ipauth       = new ipropertyHelperAuth();
        $this->ipauth->getAgentInfoByUserId();
		if($this->layout == 'edit'){
			$id = JRequest::getVar('id');
			$model=$this->getModel('searchcriteriaform');
			$this->result = $model->getedit($id);
			$this->State = $model->getState();
			parent::display('edit');
		} else {
			parent::display($tpl);
		}
	}
}