<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.modellist');

class TransactionModelTransaction extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'p.id',
                'MLS', 'p.MLS',
				'transaction', 'p.transaction',
                'status', 'p.status',
                'listing_price', 'p.listing_price',
                'listing_date', 'p.listing_date'
			);
		}

		parent::__construct($config);
	}
	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('filter.id');
		$id	.= ':'.$this->getState('filter.MLS');
        $id	.= ':'.$this->getState('filter.transaction');
        $id	.= ':'.$this->getState('filter.status');
        $id	.= ':'.$this->getState('filter.listing_price');
        $id	.= ':'.$this->getState('filter.listing_date');
		return parent::getStoreId($id);
	}
protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

        $filters = array('search', 'MLS', 'id', 'transaction', 'listing_price', 'listing_date', 'status');

		// Load the filter state.
        foreach ($filters as $f){
            $search = $app->getUserStateFromRequest($this->context.'.filter.'.$f, 'filter_'.$f);
            $this->setState('filter.'.$f, $search);
        }
		// List state information.
		parent::populateState('p.id', 'asc');
	}
	public function getTable($type = 'transaction', $prefix = 'transactionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
public function getData(){
    		$db         = $this->getDbo();
			$query      = $db->getQuery(true);
    		$query->select(
			$this->getState(
				'list.select',
				'p.id as id, p.MLS, p.transaction, p.listing_price, p.listing_date, p.status' 
			)
		);
			$query->from('`#__transaction` AS p');

		$MLS = $this->getState('filter.MLS');
		if ($MLS) {
			$query->where('p.MLS = '.$db->Quote($MLS
			));
		}
		$transaction = $this->getState('filter.transaction');
		if ($transaction) {
			$query->where('p.transaction = '.$db->Quote($transaction
			));
		}
		$id = $this->getState('filter.id');
		if ($id) {
			$query->where('p.id = '.$db->Quote($id
			));
		}
		$listing_price = $this->getState('filter.listing_price');
		if ($listing_price) {
			$query->where('p.listing_price = '.$db->Quote($listing_price
			));
		}
		$listing_date = $this->getState('filter.listing_date');
		if ($listing_date) {
			$query->where('p.listing_date = '.$db->Quote($listing_date
			));
		}
		$status = $this->getState('filter.status');
		if ($status) {
			$query->where('p.status = '.$db->Quote($status
			));
		}
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('p.id = '.(int) substr($search, 3));
			}
			else {
				$search     = JString::strtolower($search);
                $search     = explode(' ', $search);
                $searchwhere   = array();

                if (is_array($search)){ //more than one search word
                    foreach ($search as $word){
                        $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        //echo "<pre>"; print_r($searchwhere); exit;
                        $searchwhere[] = 'LOWER(p.MLS) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.transaction) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.listing_price) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.listing_date) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                    }
                } else {
                    $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                  	$searchwhere[] = 'LOWER(p.MLS) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.transaction) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.listing_price) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.listing_date) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                   	$searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                }
                $query->where('('.implode( ' OR ', $searchwhere ).')');
			}
		}
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
     	$query->group('p.id');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return $results;
	}
	public function delete($cid){
        if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
		}
		$db = & JFactory::getDBO();   
         $query = $db->getQuery(true);
         $query->delete();
         $query->from('#__transaction');
         $query->where('id IN('.implode(',', $cid).')');
         $db->setQuery($query);
         if (!$db->execute()) {
				JError::raiseError( 4711, 'Please try again' );
			}
			JFactory::getApplication()->enqueueMessage('Seccessfully Deleted');
			$app = JFactory::getApplication();
			$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
	}
	public function approve($cid){
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
		}
		$db = & JFactory::getDBO();   
        $query = $db->getQuery(true);
		//update data
        $conditions = 'id IN('.implode(',', $cid).')';
    	$fields = array('is_approve' .'='. 1);
     	$query->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
        $db->setQuery($query);
		$result = $db->execute();
		if($result==true){
			$query->select('*');
			$query->from($db->quoteName('#__transaction'));
			$query->where('id IN('.implode(',', $cid).')');
			$db->setQuery($query);
			$res = $db->loadObjectlist();
			//echo "<pre>"; print_r($res); exit;
			foreach ($res as $val) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from($db->quoteName('#__iproperty_agents'));
				$query->where($db->quoteName('id')." = ".$val->agent_id);
				$db->setQuery($query);
				$reslt = $db->loadObjectlist();
					// echo "<pre>"; print_r($reslt);
					foreach ($reslt as $value) {
						 
						$config = JFactory::getConfig();
						$params = JComponentHelper::getParams('com_transaction');
						$admin= $params->get('cmpny_email');
						$body_content= $params->get('email_editor');
						$from = $config->get( 'mailfrom' );

						$key = array("submitted", "[ADMINNAME]", "[SITENAME]", "[AGENTNAME]", "[ADMINTRANSACTIONLINK]", "[UTRANID_VAL]", "[MLSNO_VAL]", "[LISTPRI_VAL]", "[LISTDATE_VAL]", "[AGENTNAME]");

						$replace   = array("Approved", $config->get( 'fromname' ), $config->get( 'sitename' ), ucwords($value->fname)."  ".ucwords($value->lname), "<a href='http://www.raindropsinfotech.com/example/usmetrorealty'>usmetrorealty</a>", $val->transaction, $val->MLS, $val->listing_price, $val->listing_date);
							$body =str_replace($key,$replace,$body_content);

						
						$mailer = JFactory::getMailer();
						$subject = 'Approved Mail';
						$fromname =$config->get( 'fromname' );
						$sender = array( 
						    $from,
						    $fromname
						);
						//echo "<pre>"; print_r($sender);
						$mailer->setSender($sender); 
						$mailer->addRecipient($admin);
						$mailer->setSubject($subject);
						$mailer->setBody($body);
						$mailer->isHTML(TRUE);
						$send = $mailer->Send();

						if ( $send !== true ) {
						    JFactory::getApplication()->enqueueMessage('errorr..!!');
						} else {
						   JFactory::getApplication()->enqueueMessage('Successfully Approved');
						}
					}
				}
		}
		$app = JFactory::getApplication();
		$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
	}
	public function disapprove($cid){
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
		}
		$db = & JFactory::getDBO();   
        $query = $db->getQuery(true);
        $conditions = 'id IN('.implode(',', $cid).')';
     	$fields = array('is_approve' .'='. 0);
     	$query->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
        $db->setQuery($query);
		$result = $db->execute();
		JError::raiseError( 4711, 'Disapprove' );
		$app = JFactory::getApplication();
		$app->redirect('http://www.raindropsinfotech.com/example/usmetrorealty/administrator/index.php?option=com_transaction');
	}
}