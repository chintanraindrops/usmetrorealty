<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Registration model class for Users.
 *
 * @since  1.6
 */
class transactionModeltransaction extends JModelForm {

	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		// $loadData = JFactory::getApplication()->getUserState('com_register.register', array());
		$form = $this->loadForm('com_transaction.transaction', 'transaction', array('control' => 'jform', 'load_data' => $loadData));
		// When multilanguage is set, a user's default site language should also be a Content Language
		if (JLanguageMultilang::isEnabled()){
			$form->setFieldAttribute('language', 'type', 'frontend_language', 'params');
		}
		if (empty($form)){
			return false;
		}
		return $form;
	}
	
	function save($value1){
		//echo "<pre>"; print_r($value1); exit;
		$app   = JFactory::getApplication();
		$user = JFactory::getUser();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__iproperty_agents'));
		$query->where($db->quoteName('email')." = ".$db->quote($user->email));
		$db->setQuery($query);
		$res = $db->loadObject();
		//echo "<pre>"; print_r($res); exit;
		
		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		$row = JTable::getInstance('transaction', 'Table', array());

		$params = JComponentHelper::getParams('com_transaction');

        $arizona=array('transaction'=>$params->get('arizona_transaction'));
        $oregon=array('transaction'=>$params->get('oregon_transaction'));
        $washington=array('transaction'=>$params->get('washington_transaction'));
        $value2 = array('status'=>'open','agent_id'=>$res->id);
		$value1 = array_merge($value1,$value2);
		//echo "<pre>"; print_r($value1); exit;
        $query1 = $db->getQuery(true);
		$query1->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('state') . ' = \'' .$value1['state'].'\'')
			->order('id DESC')
			->setLimit(1);
    		$db->setQuery($query1);
    		$results = $db->loadObject();
    	//echo "<pre>"; print_r($results); exit;
		if($value1['state']=='AZ'){ 
			if($results->state=='AZ'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$arizona);
				//echo "<pre>"; print_r($value); exit;
    		}		
		} else if($value1['state']=='OR'){
			if($results->state=='AZ'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$oregon);
    		}
		}else if($value1['state']=='WA'){
    		if($results->state=='WA'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$washington);
    		}
   		}
   		//echo "<pre>"; print_r($value); exit;
		$row->bind($value);
		$row->store($value);
		//get last id 
		$query3 = $db->getQuery(true);
		$query3
    	->select('MAX(id) as m_id')
    	->from('#__transaction');
    	$db->setQuery($query3);
    	$last = $db->loadObject();
    			//file upload code
		    	$path = JPATH_SITE.'/media/com_iproperty/transactions';
				$file_ext   = array('jpeg','png','gif','jpg','pdf','doc','docx','xlsx','xls','txt');
				foreach ($_FILES['jform']['name']['upload_files'] as $k=>$v) {
					$photo_name = $_FILES['jform']['name']['upload_files'][$k];
					$photo_size = $_FILES['jform']['size']['upload_files'][$k];
					$photo_tmp = $_FILES['jform']['tmp_name']['upload_files'][$k];
					$photo_error= $_FILES['jform']['error']['upload_files'][$k];

					$ext = end((explode(".", $photo_name)));
				
					$photo_path[]=$path.'/'.$last->m_id.'/'.$photo_name;
					if((($ext == 'jpeg') || ($ext == 'gif')   ||
					   ($ext == 'png') || ($ext == 'jpg') || ($ext == 'pdf')   ||
					   ($ext == 'doc') || ($ext == 'docx') || ($ext == 'xlsx')   ||
					   ($ext == 'xls') || ($ext == 'txt') && $photo_size < 200000000000)){
						if(!file_exists($path.'/'.$last->m_id.'/'.$photo_name)){
							$a = mkdir($path.'/'.$last->m_id, 0777, true);
							move_uploaded_file($photo_tmp,$path.'/'.$last->m_id.'/'.$photo_name);
						}
					}
				}
				$implode_path=implode(',',$photo_path);
				$query4 = $db->getQuery(true);
				$conditions = array($db->quoteName('id') . ' = '.$last->m_id);
				$fields = array($db->quoteName('upload_files') . ' = \'' .$implode_path.'\'');
				$query4->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
		 		//echo $query4; exit;
				$db->setQuery($query4);
				$result = $db->execute();
				//file upload code end
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					$subject = 'Transaction Saved';
					$from   = $user->email;
					
					$config = JFactory::getConfig();
					$adminEmail = $config->get( 'mailfrom' );
					$body_content = $params->get('email_editor');
					$link="<a href='http://www.raindropsinfotech.com/example/usmetrorealty'>usmetrorealty</a>";
					
					$language = JFactory::getLanguage();
                    $language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
					$body = sprintf(JText::_('COM_IPROPERTY_TRANSACTION_EMAIL_BODY'),
	                ucwords($res->fname)."  ".ucwords($res->lname),
	                $config->get( 'fromname' ), 
	                $config->get( 'sitename' ), 
	                $link, 
	                $value['transaction'],
	                $value['MLS'],
	                $value['listing_price'],
	                $value['listing_date']);
					
					$sender = array( 
					    $from
					);
					$mailer->setSender($sender); 
					$mailer->addRecipient($adminEmail);
					$mailer->setSubject($subject);
					$mailer->setBody($body);
					$mailer->isHTML(TRUE);
					$send = $mailer->Send();
						//echo "<pre>"; print_r($send); exit;
						if ( $send !== true ) {
						    JFactory::getApplication()->enqueueMessage('errorr..!!');
						} else {
						    JFactory::getApplication()->enqueueMessage('Transaction added & Mail Sent');
						}

						$allDone =& JFactory::getApplication();
						$allDone->redirect('index.php?option=com_transaction&view=transactionlist');
	}
	function getData(){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			
			//$user = JFactory::getUser();
			$user = JFactory::getUser();
			

			$query1 = $db->getQuery(true);
			$query1->select('*');
			$query1->from($db->quoteName('#__iproperty_agents'));
			$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
			$db->setQuery($query1);
			$res = $db->loadObject();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('agent_id') . ' = ' . $db->quote($res->id));
    		$db->setQuery($query);
    		$results = $db->loadObjectList();
    		return $results;
	}
	function getmyData($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObject();
    		return $results;
	}
	function getmyimages($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction_images'))
			->where($db->quoteName('transaction_id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObjectlist();
    		return $results;
	}
	function download($id){
			$link=JRequest::getvar('count');
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction_images'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObject();
    		//echo "<pre>"; print_r($results); exit;
    		/*$a = explode(',',$results->upload_files);
	        foreach ($a as $k=>$value) {
	          	if($k==$link){
	          		$a=explode('/',$value);
	          		$name=$a[count($a)-1];
	          	}
	     	}*/
	     	$path = substr($results->path,1); 
	     	$name = $results->fname.$results->type;
	     	//echo $name; exit;
	     	$final_path = JURI::root().$path.$name;
	     	//echo $final_path; exit;
	     	$headers  = get_headers($final_path, 1);
	     	//echo "<pre>"; print_r($headers); exit;
			$fsize    = $headers['Content-Length'];

	     	//echo filesize($final_path).'M'; exit;
    		header('Content-Description: File Transfer');
		    header('Content-Type: application/force-download');
		    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . $fsize);
		    ob_clean();
		    flush();
		    readfile($final_path); //showing the path to the server where the file is to be download
		    exit;
	}
	function getedit($id){
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
		->from($db->quoteName('#__transaction'))
		->where($db->quoteName('id') .'='.$id);
		$db->setQuery($query);
		$results = $db->loadObject();
		return $results;
	}
}
?>