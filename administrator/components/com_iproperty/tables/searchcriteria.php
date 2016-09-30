<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

class IpropertyTableSearchcriteria extends JTable
{
    protected $tagsHelper = null;
    
	public function __construct(&$_db)
	{
		parent::__construct('#__iproperty_search_criteria', 'id', $_db);
        // @TODO: Add Joomla tags
        //$this->tagsHelper = new JHelperTags();
        //$this->tagsHelper->typeAlias = 'com_iproperty.property';
	}

	public function check()
	{
		jimport('joomla.filter.output');
        $settings   = ipropertyAdmin::config();
        $ipauth     = new IpropertyHelperAuth(array('msg'=>false));
        $date       = JFactory::getDate();
        $user       = JFactory::getUser();
        
        // new debug option
        $debug      = false;
        if ($debug) JLog::addLogger( array('text_file' => 'geocode.log.php'));

        // do geocode 
        if((!$this->latitude|| !$this->longitude) && $settings->map_provider && function_exists('curl_init')){
            switch ($settings->map_provider){
                case 1: // GOOGLE
                    $this->doGeoGoogle($debug);
                break;
                case 2: // BING
                    $this->doGeoBing($settings->map_credentials, $debug);
                break;
            }
        }
        
		// Set name
        $this->title    = htmlspecialchars_decode($this->title, ENT_QUOTES);
		$this->street   = htmlspecialchars_decode($this->street, ENT_QUOTES);
        $this->street2  = htmlspecialchars_decode($this->street2, ENT_QUOTES);
        
        // Set alias
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if (empty($this->alias)) {
            $ptitle         = ($this->title) ? $this->title : $this->street_num.' '.$this->street.' '.$this->street2;
			$this->alias    = JFilterOutput::stringURLSafe($ptitle.' '.$this->city);
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			// Swap the dates.
			$temp = $this->publish_up;
			$this->publish_up = $this->publish_down;
			$this->publish_down = $temp;
		}	
        
        // Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
        if (!empty($this->metakey)) {
			// Only process if not empty
			$bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
			$keys = explode(',', $after_clean); // create array using commas as delimiter
			$clean_keys = array();

			foreach($keys as $key) {
				if (trim($key)) {
					// Ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
		}

        if(!$ipauth->canFeatureProp($this->id, $this->featured)){
            unset($this->featured);
        }
        if(!$ipauth->canPublishProp($this->id, $this->state)){
            unset($this->state);
        }

		return true;
	}

	public function bind($array, $ignore = array())
	{       
        if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}       
        
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
        
        // if modified, set modified date and user, else set created date
        if ($this->id) {
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		} else {
			if (!intval($this->created)) {
				$this->created = $date->toSql();
			}
            if (!intval($this->publish_up)) {
				$this->publish_up = $date->toSql();
			}
            if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}
        
		// Verify that the alias is unique
		$table = JTable::getInstance('Searchcriteria', 'IpropertyTable');
		if ($table->load(array('alias'=>$this->alias)) && ($table->id != $this->id || $this->id == 0)) {
			//$this->setError(JText::_('COM_IPROPERTY_ERROR_UNIQUE_ALIAS'));
			//return false;
            $this->alias = $this->alias.rand();
		}
        
        // @TODO: store Joomla tags        
        //$this->tagsHelper->preStoreProcess($this);

		// Attempt to store the data.
		return parent::store($updateNulls);
        
        // @TODO: store Joomla tags
        //return $result && $this->tagsHelper->postStoreProcess($this);
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;       

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Property','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->state = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function feature($pks = null, $state = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Property','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->featured = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function delete($pks = null)
	{
        // Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}
        
		try
		{			
			// delete from properties table
            $query = $this->_db->getQuery(true);
            $query->delete();
            $query->from('#__iproperty_search_criteria');
            $query->where('id IN ('.implode(',', $pks).')');
            $this->_db->setQuery($query);
            
			// Check for a database error.
            if (!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg());
			}
            
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
        
		return count($this->getErrors())==0;
	}

    public function approve($pks = null, $state = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Property','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->approved = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
            
            // Send approval notification to agents
            if($state == 1){
                $this->_notifyApproval($table->id);
            }
		}
		return count($this->getErrors())==0;
	}
}
?>
