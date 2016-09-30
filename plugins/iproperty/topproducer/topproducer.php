<?php
/**
 * @version 3.3.2 2014-12-18
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jimport( 'joomla.plugin.plugin');

class plgIpropertyTopproducer extends JPlugin
{
	function plgIpropertyAfterRequest(&$subject, $config)  {
		parent::__construct($subject, $config);
	}

	function onAfterPropertyRequest($user_id = '', $post, $settings)
	{
        $app = JFactory::getApplication();
        $mailer = JFactory::getMailer();
		if($app->getName() != 'site') return true;

        $recipient = $this->params->get('recipient', false);
        if(!$recipient) return true;
        
        // lookup MLS ID
        $db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('mls_id', 'street_num', 'street', 'street2', 'city')));
		$query->from($db->quoteName('#__iproperty'));
		$query->where($db->quoteName('id') . ' = '. $db->quote($post['prop_id']));
		$db->setQuery($query);

		$result = $db->loadObject();
        
        if ($result){
			// build address
			$address = $result->street_num ?: '';
			$address .= $result->street ?  ' '.$result->street : '';
			$address .= $result->street2 ? ' '.$result->street2 : '';
			$address .= $result->city ? ', '.$result->city : '';
			
			$config = JFactory::getConfig();
						
			$subject    = 'IProperty Website Info Request';

			$body = "Source: ".$this->params->get('source', 'IProperty Website Info Request')." \n";
			$body .= "Name: ".$post['sender_name']." \n";
			$body .= "Email: ".$post['sender_email']." \n";
			$body .= "Address: ".$address." \n";
			$body .= "Phone: ".$post['sender_dphone']." \n";
			$body .= "MLS Number: ".$result->mls_id." \n"; /*mls number is required*/
			$body .= "Notes: ".$post['special_requests']." \n";
			
			// set sender
			$sender = array( 
				$config->get( 'config.mailfrom' ),
				$config->get( 'config.fromname' ) 
			);
			$mailer->setSender($sender);
			// set recipient, subject and body
			$mailer->addRecipient($recipient);
			$mailer->setSubject($subject);
			$mailer->setBody($body);

			$send = $mailer->Send();
			
			if ( $send !== true ) {
				JError::raiseWarning( 100, 'Warning' );
			} else {
				return true;
			}
		}
	}
}
