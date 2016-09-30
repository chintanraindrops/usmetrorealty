<?php
/**
 * @package		VINAORA VISITORS COUNTER
 * @subpackage	vvisit_counter
 *
 * @copyright	Copyright (C) 2007-2015 VINAORA. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @website		http://vinaora.com
 * @twitter		http://twitter.com/vinaora
 * @facebook	https://www.facebook.com/pages/Vinaora/290796031029819
 * @google+		https://plus.google.com/111142324019789502653
 */

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper/vvisit_counter.php';

class plgSystemVVisit_Counter extends JPlugin
{
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	public function onAfterInitialise()
	{
		// Don't run on back-end
		$onbackend = (int) $this->params->get('onbackend', 0);
		if ( !$onbackend && (JPATH_BASE !== JPATH_ROOT) ) return;

		$visit_type	= plgVVisitCounterHelper::visitType();
		$requests = JRequest::get();
		//print_r($requests);exit;
		if(($requests['ipquicksearch'] == 1 || $requests['view'] == 'advsearch' || isset($requests['filter_keyword']) || isset($requests['filter_cat']) || isset($requests['filter_stype']) || isset($requests['filter_order']) || isset($requests['filter_order_Dir'])) && $visit_type == "guests"){
			self::_insertSearchRecord($lastlog, $visit_type);		
		}

		$now		= time();
		$session	= JFactory::getSession();
		$lastlog	= (int) $session->get('vvisit_counter.lastlog');
		if ( $session->isNew() || ($now > $lastlog) )
		{
			
			$lifetime	= (int) $session->getExpire();			
			$lastlog	= ( floor($now/$lifetime)+1 ) * $lifetime;
			
			self::_insertRecord($lastlog, $visit_type);
			$session->set('vvisit_counter.lastlog', $lastlog);
			return;
		}
	}

	public function onAfterDispatch(){
		//echo JURI::current();exit;
	}

	/*
	 * Insert a new Record
	 */
	private static function _insertRecord($time=0, $visit_type='guests')
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Insert columns.
		$columns = array('time', 'visits', $visit_type);
		
		// Insert values.
		$values	= array($time, 1, 1);

		// Prepare the insert query.
		$query
			->insert('#__vvisit_counter')
			->columns($columns)
			->values(implode(',', $values));
		
		// Try to update if has more than one visitor who has visited the site
		if(self::_updateRecord($time, $visit_type)) return 1;
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		
		return $db->getAffectedRows();
	}

	private static function _insertSearchRecord($time=0, $visit_type='guests')
	{
		$ip_addr = $_SERVER['REMOTE_ADDR'];

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('ip_address', 'count_num')));
		$query->from($db->quoteName('#__vvisit_search_users'));
		$query->where($db->quoteName('ip_address') . ' = '. $db->quote($ip_addr));
		 
		$db->setQuery($query);
		$ip_results = $db->loadObject();
		//var_dump($ip_results);exit;
		if(empty($ip_results)){
			$insquery = $db->getQuery(true);
			$columns = array('ip_address', 'count_num');
			$values	= array($db->quote($ip_addr), 1);
			$insquery
				->insert('#__vvisit_search_users')
				->columns($columns)
				->values(implode(',', $values));
			$db->setQuery($insquery);
			$db->execute();
		} else {
			$guest_visit_count = $ip_results->count_num;
			if($guest_visit_count < 3){
				self::_updateSearchRecord($guest_visit_count, $visit_type);
			} else {
				$app = JFactory::getApplication();
				$link=JRoute::_('index.php?option=com_iproperty&view=ipuser&Itemid=143');
				$app->redirect($link, 'Please login or register yourself to make more use of our site.');
			}
		}
		// Try to update if has more than one visitor who has visited the site
		//if(self::_updateRecord($time, $visit_type)) return 1;
		
		// Set the query using our newly populated query object and execute it.
		
		
		return $db->getAffectedRows();
	}

	/*
	 * Update the last Record
	 */
	private static function _updateRecord($time=0, $visit_type='guests')
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Fields to update.
		$fields = array("visits=visits+1", "$visit_type=$visit_type+1");
		 
		// Conditions for which records should be updated.
		$where = "time=$time";
		 
		$query
			->update('#__vvisit_counter')
			->set($fields)
			->where($where);
		 
		$db->setQuery($query);
		$db->execute();
		
		return $db->getAffectedRows();
	}


	private static function _updateSearchRecord($guest_visit_count, $visit_type='guests')
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);

		$ip_addr = $_SERVER['REMOTE_ADDR'];
		$guest_visit_count += 1;
		
		// Fields to update.
		$fields = array("count_num=$guest_visit_count");
		 
		// Conditions for which records should be updated.
		$where = "ip_address='$ip_addr'";
		 
		$query
			->update('#__vvisit_search_users')
			->set($fields)
			->where($where);
		 
		$db->setQuery($query);
		//echo $query->__toString();exit;
		$db->execute();
		
		return $db->getAffectedRows();
	}

}
