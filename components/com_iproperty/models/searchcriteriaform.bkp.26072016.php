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

		$criteria_id = $row->id;

		// Amenities
        $amens = array();
        $amen_fields = array('general_amens', 'interior_amens', 'exterior_amens', 'accessibility_amens', 'green_amens', 'security_amens', 'landscape_amens', 'community_amens', 'appliance_amens' );
        foreach ($amen_fields as $f) {
            if (array_key_exists($f, $value) && is_array($value[$f])) {
                $amens = array_merge($amens, $value[$f]);
            }
        }

        foreach( $amens as $amen ){
            $query = 'INSERT INTO #__iproperty_searchcritmid (criteria_id, amen_id) VALUES ('.(int)$criteria_id.','.(int)$amen.')';
            $this->_db->setQuery($query);
            
            if (!$this->_db->execute()) {
                JFactory::getApplication()->enqueueMessage(JText::_('SOMETHING_WRONG'), 'error');
            }
        }
        // Amenities

        $value['amens'] = $amens;
        $this->send_mail($value);
		JFactory::getApplication()->enqueueMessage(JText::_('SEARCH_CRITERIA_SENT'));

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
		//echo "<pre>"; print_r($value); exit;
		$fields = array($db->quoteName('title') . ' = ' . $db->quote($value['title']),$db->quoteName('description') . ' = ' . $db->quote($value['description']),$db->quoteName('hometype') . ' = ' . $db->quote($value['hometype']),$db->quoteName('buyer_id') . ' = ' . $db->quote($value['buyer_id']),$db->quoteName('minprice') . ' = ' . $db->quote($value['minprice']),$db->quoteName('maxprice') . ' = ' . $db->quote($value['maxprice']),$db->quoteName('city') . ' = ' . $db->quote($value['city']),$db->quoteName('locstate') . ' = ' . $db->quote($value['locstate']),$db->quoteName('beds') . ' = ' . $db->quote($value['beds']),$db->quoteName('kitchen') . ' = ' . $db->quote($value['kitchen']),$db->quoteName('sleeps') . ' = ' . $db->quote($value['sleeps']),$db->quoteName('shared_own') . ' = ' . $db->quote($value['shared_own']),$db->quoteName('lease_hold') . ' = ' . $db->quote($value['lease_hold']),$db->quoteName('baths') . ' = ' . $db->quote($value['baths']),$db->quoteName('reception') . ' = ' . $db->quote($value['reception']),$db->quoteName('total_units') . ' = ' . $db->quote($value['total_units']),$db->quoteName('tax') . ' = ' . $db->quote($value['tax']),$db->quoteName('income') . ' = ' . $db->quote($value['income']),$db->quoteName('sqft') . ' = ' . $db->quote($value['sqft']),$db->quoteName('lotsize') . ' = ' . $db->quote($value['lotsize']),$db->quoteName('lot_acres') . ' = ' . $db->quote($value['lot_acres']),$db->quoteName('yearbuilt') . ' = ' . $db->quote($value['yearbuilt']),$db->quoteName('heat') . ' = ' . $db->quote($value['heat']),$db->quoteName('cool') . ' = ' . $db->quote($value['cool']),$db->quoteName('fuel') . ' = ' . $db->quote($value['fuel']),$db->quoteName('garage_type') . ' = ' . $db->quote($value['garage_type']),$db->quoteName('garage_size') . ' = ' . $db->quote($value['garage_size']),$db->quoteName('zoning') . ' = ' . $db->quote($value['zoning']),$db->quoteName('frontage') . ' = ' . $db->quote($value['frontage']),$db->quoteName('siding') . ' = ' . $db->quote($value['siding']),$db->quoteName('roof') . ' = ' . $db->quote($value['roof']),$db->quoteName('propview') . ' = ' . $db->quote($value['propview']),$db->quoteName('school_district') . ' = ' . $db->quote($value['school_district']),$db->quoteName('lot_type') . ' = ' . $db->quote($value['lot_type']),$db->quoteName('style') . ' = ' . $db->quote($value['style']),$db->quoteName('hoa') . ' = ' . $db->quote($value['hoa']),$db->quoteName('reo') . ' = ' . $db->quote($value['reo']));

		//echo "<pre>"; print_r($fields); exit;
		$query->update($db->quoteName('#__iproperty_search_criteria'))->set($fields)->where($conditions);
 		//echo $query; exit;
		$db->setQuery($query);
		$result = $db->execute();
		
		// Amenities
        $amens = array();
        $amen_fields = array('general_amens', 'interior_amens', 'exterior_amens', 'accessibility_amens', 'green_amens', 'security_amens', 'landscape_amens', 'community_amens', 'appliance_amens' );
        foreach ($amen_fields as $f) {
            if (array_key_exists($f, $value) && is_array($value[$f])) {
                $amens = array_merge($amens, $value[$f]);
            }
        }

        if(count($amens) > 0){	
        	$delete_query = 'DELETE FROM #__iproperty_searchcritmid WHERE criteria_id = '.$id;
        	$db->setQuery($delete_query);
        	$db->execute();

        	foreach( $amens as $amen ){
	            $query = 'INSERT INTO #__iproperty_searchcritmid (criteria_id, amen_id) VALUES ('.(int)$id.','.(int)$amen.')';
	            $db->setQuery($query);
	            
	            if (!$db->execute()) {
	                JFactory::getApplication()->enqueueMessage(JText::_('SOMETHING_WRONG'), 'error');
	            }
	        }
        }
        //var_dump($amens);exit;
        // Amenities

        $value['amens'] = $amens;
        $this->send_mail($value);

		JFactory::getApplication()->enqueueMessage(JText::_('SEARCH_CRITERIA_SENT'));

		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');
	}

	function send_mail($value){

		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		// set email vars
        $user = JFactory::getUser();
        $from_name	= ucwords($user->name);
		$from_email	= $user->email;
		$admin_from     = $app->getCfg('fromname');
        $admin_email    = $app->getCfg('mailfrom');
        $site_name      = $app->getCfg('sitename');
        $date           = JHTML::_('date', 'now', JText::_('DATE_FORMAT_LC4'));
        $subject        = sprintf(JText::_('COM_IPROPERTY_SEARCH_CRITERIA_EMAIL_SUBJECT'), $from_name, "Updated");
        
        $hometype_query = 'SELECT name FROM `#__iproperty_stypes` WHERE `id` ='.$value['hometype'];
        $db->setQuery($hometype_query);
        $hometype_res_obj = $db->loadObject();
        $hometype = $hometype_res_obj->name;
        
        $price = $value['minprice'].' to '.$value['maxprice'];
        
        $state_query = 'SELECT title FROM `#__iproperty_states` WHERE `id` ='.$value['locstate'];
        $db->setQuery($state_query);
        $state_res_obj = $db->loadObject();
        $state = $state_res_obj->title;
        $shared_own = (($value['shared_own'])?'Yes':'No');
        $lease_hold = (($value['lease_hold'])?'Yes':'No');

        // amenities for mail
        $amens = array();
        $amens_str = '';
        $amen_fields = array('general_amens', 'interior_amens', 'exterior_amens', 'accessibility_amens', 'green_amens', 'security_amens', 'landscape_amens', 'community_amens', 'appliance_amens' );
        foreach ($amen_fields as $f) {
            switch ($f) {
	        	case 'interior_amens':
	        		$cat_f = "Interior Amenities";
	        		break;

				case 'exterior_amens':
	        		$cat_f = "Exterior Amenities";
	        		break;

	        	case 'accessibility_amens':
	        		$cat_f = "Accessibility Amenities";
	        		break;

	        	case 'green_amens':
	        		$cat_f = "Green Amenities";
	        		break;

	        	case 'security_amens':
	        		$cat_f = "Security Amenities";
	        		break;

	        	case 'landscape_amens':
	        		$cat_f = "Landscape Amenities";
	        		break;

	        	case 'community_amens':
	        		$cat_f = "Community Amenities";
	        		break;

	        	case 'appliance_amens':
	        		$cat_f = "Appliance Amenities";
	        		break;

	        	default:
	        		$cat_f = "General Amenities";
	        		break;
	        }
            if (array_key_exists($f, $value) && is_array($value[$f])) {
            	$is_amenities = true;
                //$amens = array_merge($amens, $value[$f]);
                $ame_s = implode(',', $value[$f]);
            	$ame_query = 'SELECT title FROM `#__iproperty_amenities` WHERE `id` IN ('.$ame_s.')';
		        $db->setQuery($ame_query);
		        $ame_obj_list = $db->loadObjectList();
		        //var_dump($ame_obj_list);
		        //$amens[$f] = array();
		        //array_push($amens[$f], $ame_obj_list->title);
		        //echo $f;
		        $amens_str .= '<b>'.$cat_f.'</b> : ';
		        $num_objs = count($ame_obj_list);
		        $obj_i = 0;
		        foreach ($ame_obj_list as $ame_obj) {
		        	$obj_i++;
		        	if($obj_i < $num_objs){
		        		$amens_str .= $ame_obj->title.' , ';
		        	} else {
		        		$amens_str .= $ame_obj->title.'<br>';
		        	}
		        	//var_dump($ame_obj->title);
		        }
		        //var_dump($ame_obj_list->title);
		        $amens_str .= '<br>';
            }
        }
        if(empty($amens_str)){
        	$amens_str = 'No amenities selected for now.';
        }
        //var_dump($amens_str);
        //exit;
        
        $body = sprintf(JText::_('COM_IPROPERTY_SEARCH_CRITERIA_EMAIL_BODY'),
                $from_name,
                $date, 
                $value['title'], 
                $value['description'], 
                $hometype,
                $price,
                $value['city'],
                $state,
                $value['beds'],
        		$value['baths'],
        		$value['total_units'],
        		$value['sleeps'],
        		$value['kitchen'],
				$value['sqft'],
				$value['reception'],
				$shared_own,
				$lease_hold,
				$value['lotsize'],
				$value['lot_acres'],
				$value['lot_type'],
				$value['heat'],
				$amens_str);

        //var_dump($body);exit;
		//customize viral
        	$settings   = ipropertyAdmin::config();
			$criteria_email = str_replace(array('-','/',' ','\\'),'',$settings->searchcriteria_message);

			$explode_email=explode(",", $criteria_email);
        //end
		$sent = '';
        $mail = JFactory::getMailer();
        $mail->addRecipient( $explode_email );
        //$mail->addReplyTo(array($from_email, $from_name));
        if(version_compare(JVERSION, '3.0', 'ge')) {
          $mail->addReplyTo($from_email, $from_name);
        } else {
          $mail->addReplyTo(array($from_email, $from_name));
        }
        $mail->setSender( array( $from_email, $from_email ));
        $mail->setSubject( $subject );
        $mail->IsHTML('true');
        $mail->setBody( $body );
        $sent = $mail->Send();

        return $sent;
	}
}