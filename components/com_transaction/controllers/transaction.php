<?php

/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controllerform');

class transactionControllertransaction extends JControllerForm
{
	public function save(){
        $task = JRequest::getVar('task');
        if($task=='save'){
        $input = JFactory::getApplication()->input;
                     
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('transaction');
            $model->save($formData);
  		}
	}
	public function download(){
		 $id=JRequest::getVar('id');
		 $model = $this->getModel('transaction');
         $model->download($id);
		}
}