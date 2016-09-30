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
		$save = JRequest::getVar('task');
        if($save == 'save'){
            $input = JFactory::getApplication()->input;
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('searchcriteriaform');
            $model->save($formData);
        }
    }
    public function delete(){
	
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
		}

		$model=$this->getModel('searchcriteriaform');
		$result= $model->deleteCriterias($cid);
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
		$update = JRequest::getVar('task');
		//echo"<pre>"; exit(print_r($update));
        if($update == 'update'){
        	//exit('hererrr');
        	$id = JRequest::getVar('id');
            $input = JFactory::getApplication()->input;
            $formData =$input->get('jform', '', 'array');
            // echo "<pre>"; print_r($formData); exit;
            $model = $this->getModel('searchcriteriaform');
            $model->update($formData);
        }
    }

    public function getStates(){ /* [[CUSTOM]] */
		//var_dump(JRequest::getVar('countries'));exit;
		//var_dump($_REQUEST['countries']);exit;
		$countries = JRequest::getVar('countries');
		if(isset($countries))
		{
			$exp_countries = explode(',', $countries);
			$countries = implode("','", $exp_countries); 
			$query = "SELECT * FROM  #__iproperty_states where country IN ('$countries')";
	        //echo $query;exit;
	        $db = JFactory::getDBO();
	        $db->setQuery($query);
			$states =  $db->loadObjectList();
			//var_dump($states);exit;
			if(!empty($states))
			{ 
				foreach ($states as $key => $value) {
					$options[] =JHTML::_('select.option', $value->id, JText::_($value->title));
				}

				echo json_encode($options); exit;
			}else{
				$options[] =JHTML::_('select.option', 0, JText::_("Select state"));
				$drop=JHTML::_('select.genericlist', $options, 'jform[country_id]', 'class="chzn-done"', 'value', 'text', 'class="chzn-done"');
				echo json_encode($options); exit;
			}
		}
	}

	public function getCities(){ /* [[CUSTOM]] */

		$states = JRequest::getVar('states');
		if(isset($states))
		{
			$exp_states = explode(',', $states);
			$states = implode("','", $exp_states);  
			$query = "SELECT * FROM  #__iproperty_cities where state IN ('$states')";
	        $db = JFactory::getDBO();
	        $db->setQuery($query);
			$cities =  $db->loadObjectList();
			if(!empty($cities))
			{ 
				foreach ($cities as $key => $value) {
					$options[] =JHTML::_('select.option', $value->id, JText::_($value->title));
				}

				echo json_encode($options); exit;
			}else{
				$options[] =JHTML::_('select.option', 0, JText::_("Select city"));
				//$drop=JHTML::_('select.genericlist', $options, 'jform[region_id]', 'class="chzn-done"', 'value', 'text', 'class="chzn-done"');
				echo json_encode($options); exit;
			}
		}
	}
}