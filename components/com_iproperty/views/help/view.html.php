<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.view');

class IpropertyViewHelp extends JViewLegacy
{

    public function display($tpl = null)
    {
    	$user=JFactory::getUser();
		if(!empty($user->id) && isset($user->id)){
	    	$this->layout = JRequest::getVar('layout');
	        $this->msg = 'Ask a Question';
	        $this->settings=ipropertyAdmin::config();
	        $this->form   = $this->get('Form');
	        if($this->layout == 'agenthelp'){
				$model=$this->getModel('help');
				$this->result = $model->getData();
				//echo "<pre>"; print_r($this->result);exit;
				parent::display('agenthelp');
			}else if($this->layout == 'agentdetails'){
				$id = JRequest::getVar('id');
				$model=$this->getModel('help');
				$this->result = $model->getdetails($id);
				//echo "<pre>"; print_r($this->result);exit;
				parent::display('agentdetails');
			} else {
				parent::display($tpl);
			}
		} else {
			JFactory::getApplication()->enqueueMessage('Login First');
			$allDone =& JFactory::getApplication();
			$allDone->redirect('index.php?option=com_users&view=login');

		}
    }
}

?>
