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

class IpropertyControllerSearchcriteriaForm extends JControllerForm
{
    public function add()
	{
		$app =& JFactory::getApplication();
		$app->redirect('index.php?option=com_iproperty&view=searchcriteriaform');
	}
	public function save(){
        if($_REQUEST['save']=='save'){
            $input = JFactory::getApplication()->input;
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('searchcriteriaform');
            $model->save($formData);
        }
    }
    public function delete(){
	
		$cid	= JRequest::getVar('cid', array(), '', 'array');
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
		}
		$db = & JFactory::getDBO();   
         $query = $db->getQuery(true);
         $query->delete();
         $query->from('#__iproperty_search_criteria');
         $query->where('id IN('.implode(',', $cid).')');
         $db->setQuery($query);
         if (!$db->execute()) {
				JError::raiseError( 4711, 'Please try again' );
			}
			JFactory::getApplication()->enqueueMessage('Seccessfully Deleted');
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
	}

	public function edit()
	{
		$id = JRequest::getVar('id');
		$model=$this->getModel('searchcriteriaform');
		$result=$model->getedit($id);
		$this->setLayout('dafault:edit');
		parent::display($tpl);
	}
	
	public function update(){
		$update = JRequest::getVar('update');
        if($update == 'update'){
        	$id = JRequest::getVar('id');
            $input = JFactory::getApplication()->input;
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('searchcriteriaform');
            $model->update($formData);
        }
    }
}