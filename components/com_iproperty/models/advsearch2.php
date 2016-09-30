<?php
/**
 * @version 3.3 2014-05-01
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
require_once JPATH_COMPONENT.'/models/list.php';
jimport('joomla.application.component.model');

class IpropertyModelAdvsearch2 extends IpropertyModelList
{
    protected $ipsettings;
    protected $where;
    protected $limit;
    protected $limitstart;
    protected $orderby;
    protected $direction;

    public function __construct($config = array())
    {
        $this->ipsettings   =  ipropertyAdmin::config();
        $this->where        = (isset($config['where'])) ? $config['where'] : null;
        $this->limit        = (isset($config['limit'])) ? $config['limit'] : null;
        $this->limitstart   = (isset($config['limitstart'])) ? $config['limitstart'] : null;
        $this->orderby      = (isset($config['orderby'])) ? $config['orderby'] : null;
        $this->direction    = (isset($config['direction'])) ? $config['direction'] : null;       

        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        // List state information
        $this->setState('list.limit', $this->limit);
        $this->setState('list.start', $this->limitstart);
        $this->setState('list.ordering', $this->orderby);
        $this->setState('list.direction', $this->direction);
        
        $app    = JFactory::getApplication();
        $return = $app->input->get('return', null, 'default', 'base64');
		$this->setState('return_page', base64_decode($return));
    }

    protected function getListQuery($featured = false)
    {
        $where  = $this->getWhere();        
        $sort   = $this->getState('list.ordering');
        $order  = $this->getState('list.direction');

        $pquery = new IpropertyHelperQuery($this->_db, $sort, $order);
        $query  = $pquery->buildPropertyQuery($where, 'advsearch2');
        
        
        return $query;
    }

    public function getItems($items = null)
    {
		$db = JFactory::getDbo();
		$query = $this->getListQuery();		
		$db->setQuery($query, 0, $this->limit);
		$items = $db->loadObjectList();
        
        return $items;
    }

    protected function getWhere()
    {
        $this->where['searchfields']  = array();
        return $this->where;
    }

    public function getStart()
    {
        return $this->getState('list.start');
    }
    
    public function getReturnPage()
	{
		return $this->getState('return_page');
	}
}
?>
