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

class IpropertyModelHelp extends JModelForm
{
    public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_iproperty.help', 'Help', array('control' => 'jform', 'load_data' => true, 'form_type' => 'request'));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	function save($value){
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$join = $db->getQuery(true)
		->select($db->quoteName(array('email', 'agent_id')))
	    ->from('#__iproperty AS p')
	    ->join('INNER', '#__iproperty_agentmid AS m ON (p.id = m.prop_id)')
	    ->join('INNER', '#__iproperty_agents AS ag ON (m.agent_id = ag.id)')
	    ->where($db->quoteName('p.mls_id')."=".$db->quote($value['listing_no']));
	    $db->setQuery($join);
	    $res = $db->loadObject();

	    $config = JFactory::getConfig();
		$adminEmail = $config->get( 'mailfrom' );
		$adminname = $config->get( 'fromname' );
		$mail=array($adminEmail,$res->email);

		$user = JFactory::getUser();
		//echo "<pre>"; print_r($user); exit;
		$buyer = $db->getQuery(true);
		$buyer->select('*');
		$buyer->from($db->quoteName('#__iproperty_agents'));
		$buyer->where($db->quoteName('user_id')." = ".$user->id);
		$db->setQuery($buyer);
		$val = $db->loadObject();
		//echo "<pre>"; print_r($val); exit;

		$tc_ref_no = (rand(1000,9999))."-".(rand(1000,9999))."-".(rand(1000,9999));
		
		$query = $db->getQuery(true);
		$columns = array('subject', 'listing_no', 'question', 'tc_ref_no', 'status', 'buyer_id','agent_id' , 'post_date');
		//var_dump($columns);exit;
		$values = array($db->quote($value['subject']),$db->quote($value['listing_no']),$db->quote($value['question']),$db->quote($tc_ref_no), $db->quote('open'), $val->id, $res->agent_id);
		$query
	    ->insert($db->quoteName('#__iproperty_helpdesk_question'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values).',NOW()');
	    $db->setQuery($query);
		//echo($query->__toString());exit;
		if ( $db->execute() !== true ) {
		    JError::raiseError( 4711, 'A severe error occurred' );
		} else {
			$mailer = JFactory::getMailer();
			$subject = "Question Received";
			$from   = $adminEmail;
			$fromname ='usmetrorealty';
			$body = "You have one question receive from <strong>".$val->fname." ".$val->lname."</strong>(Buyer).<br/>
					<strong>Subject </strong>:- ".$value['subject']."<br/>
					<strong>For </strong>:- ".$value['listing_no']."<br/>
					<strong>Question </strong>:- ".$value['question']."<div style='margin-top:150px'><strong>Regards</strong><br/><p>".$adminname."</p></div>";

			$sender = array( 
				$from,
				$fromname
			);
			
			$mailer->setSender($sender); 
			$mailer->addRecipient($mail);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			if ( $send !== true ) {
				JFactory::getApplication()->enqueueMessage('Mail not send');
			} else {
				JFactory::getApplication()->enqueueMessage('Question SuccessFully Sent');
			}
		    //JFactory::getApplication()->enqueueMessage('Your question SuccessFully Sent');
		}
		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_iproperty&view=help');
	}
	function getData(){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
	
			$user = JFactory::getUser();
			//echo "<pre>"; print_r($user); 	exit;
			$query = $db->getQuery(true);
			$query->select('h.*');
		    $query->from('#__iproperty_helpdesk_question As h');
		    $query->join('INNER', '#__iproperty_agents AS ag ON (h.agent_id = ag.id)');
		    $query->where($db->quoteName('ag.user_id')."=".$db->quote($user->id));
		    $db->setQuery($query);
			//echo($query->__toString());exit;
			$results = $db->loadObjectlist();
			//echo "<pre>"; print_r($results); exit;
    		return $results;
	}
	function getdetails($id){
		//echo $id; exit;
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$user = JFactory::getUser();
			$query = $db->getQuery(true);
			$query->select('h.*,ag.email AS agent_email, buyer.email As buyer_email');
		    $query->from('#__iproperty_helpdesk_question As h');
		    $query->join('INNER', '#__iproperty_agents AS ag ON (h.agent_id = ag.id)');
		    $query->join('INNER', '#__iproperty_agents AS buyer ON (h.buyer_id = buyer.id)');
		    $query->where($db->quoteName('h.id')."=".$db->quote($id));
		    $db->setQuery($query);
			//echo($query->__toString());exit;
			$results = $db->loadObject();
			//echo "<pre>"; print_r($results); exit;
    		return $results;
	}
	function reply($value){
		//echo "<pre>"; print_r($value); 
		//echo "<pre>"; print_r($_REQUEST); 
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();

		$question_id = JRequest::getvar('question_id');
		$agent_id = JRequest::getvar('agent_id');
		$agent_email = JRequest::getvar('agent_email');
		$buyer_email = JRequest::getvar('buyer_email');
		//echo $buyer_email; exit;

		$agent = $db->getQuery(true);
		$agent->select('*');
		$agent->from($db->quoteName('#__iproperty_agents'));
		$agent->where($db->quoteName('id')." = ".$agent_id);
		$db->setQuery($agent);
		//echo($agent->__toString());exit;
		$val = $db->loadObject();
		

		$que = $db->getQuery(true);
		$que->select('*');
		$que->from($db->quoteName('#__iproperty_helpdesk_question'));
		$que->where($db->quoteName('id')." = ".$question_id);
		$db->setQuery($que);
		$que_res = $db->loadObject();

		//echo "<pre>"; print_r($que); exit;

		
		$query = $db->getQuery(true);
		$columns = array('answer', 'question_id', 'status', 'agent_id','post_date');
		//var_dump($columns);exit;
		$values = array($db->quote($value['answer']),$db->quote($question_id),$db->quote('open'),$db->quote($agent_id));
		$query
	    ->insert($db->quoteName('#__iproperty_helpdesk_answer'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values).',NOW()');
	    $db->setQuery($query);
	    //echo($query->__toString());exit;
	    //$db->execute();

	    
		//echo "<pre>"; print_r($que); exit;
	    if ( $db->execute() !== true ) {
		    JError::raiseError( 4711, 'A severe error occurred' );
		} else {
			$mailer = JFactory::getMailer();
			$subject = "Answer Received";
			$from   = $agent_email;
			$fromname ='usmetrorealty';
			$body = "You have one answer receive from <strong>".$val->fname." ".$val->lname."</strong>(Buyer).<br/>
					<strong>Question </strong>:- ".$que_res->subject."<br/>
					<strong>Question </strong>:- ".$que_res->question."<br/>
					<strong>For </strong>:- ".$que_res->listing_no."<br/>
					<strong>Answer </strong>:- ".$value['answer']."<div style='margin-top:150px'><strong>Regards</strong><br/><p>".$adminname."</p></div>";


			$sender = array( 
				$from,
				$fromname
			);
			//echo"<pre>"; print_r($buyer_email); exit;
			$mailer->setSender($sender); 
			$mailer->addRecipient($buyer_email);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			//echo "<pre>"; print_r($send);exit;
			if ( $send !== true ) {
				JFactory::getApplication()->enqueueMessage('Mail not send');
			} else {
				JFactory::getApplication()->enqueueMessage('Answer SuccessFully Sent');
			}
		    //JFactory::getApplication()->enqueueMessage('Your question SuccessFully Sent');
		}
		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_iproperty&view=help&layout=agentdetails&id='.$question_id);
	    
	}
}