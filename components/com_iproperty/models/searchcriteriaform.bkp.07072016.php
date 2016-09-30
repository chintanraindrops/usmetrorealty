<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined('_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.model');

class IpropertyModelSearchcriteriaForm extends JModelForm
{
    public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_iproperty.searchcriteriaform', 'SearchcriteriaForm', array('control' => 'jform', 'load_data' => true, 'form_type' => 'request'));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	function save($value){
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		$row = JTable::getInstance('Search_criteria', 'Table', array());

		$row->bind( $value );
		$row->store($value);

		JFactory::getApplication()->enqueueMessage('Successfully inserted');

		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
	}
	public function getsearchData(){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('*')
			->from($db->quoteName('#__iproperty_search_criteria'));
    		$db->setQuery($query);
    		$results = $db->loadObjectList();
    		return $results;
	}
	public function getedit($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('*')
			->from($db->quoteName('#__iproperty_search_criteria'))
			->where($db->quoteName('id') .'='.$id);
    		$db->setQuery($query);
    		$results = $db->loadObject();
    		return $results;
	}
	public function getState(){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('*')
			->from($db->quoteName('#__iproperty_states'));
    		$db->setQuery($query);
    		$State = $db->loadObjectList();
    		return $State;
	}
	function update($value){
		$id = JRequest::getVar('id');
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		$row = JTable::getInstance('Search_criteria', 'Table', array());

		$conditions = array($db->quoteName('id') . ' = '.$id);
		//echo "<pre>"; print_r($conditions); exit;
		$fields = array($db->quoteName('hometype') . ' = ' . $db->quote($value['hometype']),$db->quoteName('minprice') . ' = ' . $db->quote($value['minprice']),$db->quoteName('maxprice') . ' = ' . $db->quote($value['maxprice']),$db->quoteName('city') . ' = ' . $db->quote($value['city']),$db->quoteName('locstate') . ' = ' . $db->quote($value['locstate']),$db->quoteName('beds') . ' = ' . $db->quote($value['beds']),$db->quoteName('baths') . ' = ' . $db->quote($value['baths']),$db->quoteName('reception') . ' = ' . $db->quote($value['reception']),$db->quoteName('total_units') . ' = ' . $db->quote($value['total_units']),$db->quoteName('tax') . ' = ' . $db->quote($value['tax']),$db->quoteName('income') . ' = ' . $db->quote($value['income']),$db->quoteName('sqft') . ' = ' . $db->quote($value['sqft']),$db->quoteName('lotsize') . ' = ' . $db->quote($value['lotsize']),$db->quoteName('lot_acres') . ' = ' . $db->quote($value['lot_acres']),$db->quoteName('yearbuilt') . ' = ' . $db->quote($value['yearbuilt']),$db->quoteName('heat') . ' = ' . $db->quote($value['heat']),$db->quoteName('cool') . ' = ' . $db->quote($value['cool']),$db->quoteName('fuel') . ' = ' . $db->quote($value['fuel']),$db->quoteName('garage_type') . ' = ' . $db->quote($value['garage_type']),$db->quoteName('garage_size') . ' = ' . $db->quote($value['garage_size']),$db->quoteName('zoning') . ' = ' . $db->quote($value['zoning']),$db->quoteName('frontage') . ' = ' . $db->quote($value['frontage']),$db->quoteName('siding') . ' = ' . $db->quote($value['siding']),$db->quoteName('roof') . ' = ' . $db->quote($value['roof']),$db->quoteName('propview') . ' = ' . $db->quote($value['propview']),$db->quoteName('school_district') . ' = ' . $db->quote($value['school_district']),$db->quoteName('lot_type') . ' = ' . $db->quote($value['lot_type']),$db->quoteName('style') . ' = ' . $db->quote($value['style']),$db->quoteName('hoa') . ' = ' . $db->quote($value['hoa']),$db->quoteName('reo') . ' = ' . $db->quote($value['reo']));

		//echo "<pre>"; print_r($fields); exit;
		$query->update($db->quoteName('#__iproperty_search_criteria'))->set($fields)->where($conditions);
 		//echo $query; exit;
		$db->setQuery($query);
		$result = $db->execute();
		JFactory::getApplication()->enqueueMessage('Successfully Updated');

		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
	}
}