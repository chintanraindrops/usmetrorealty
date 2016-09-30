<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/html.helper.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/property.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/route.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/query.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_iproperty/classes/admin.class.php');

class modIPModuleHelper
{
    public static function getList($params)
	{
        $db     = JFactory::getDbo();
		$count  = (int) $params->get('count', 5);
        
        // Ordering
        switch ($params->get( 'ordering' ))
        {
            case '1':
                $sort           = 'price';
                $order          = 'ASC';
                break;
            case '2':
                $sort           = 'price';
                $order          = 'DESC';
                break;
            case '3':
                $sort           = 'p.street';
                $order          = 'ASC';
                break;
            case '4':
                $sort           = 'p.street';
                $order          = 'DESC';
                break;
            case '5':
            default:
                $sort           = 'RAND()';
                $order          = '';
                break;
        }

        $where  = array(); 
        
        // build query
        if ($params->get('featured', 0)) $where['property']['featured'] = 1;
        if ($params->get('hotsheet', 0)) $where['hotsheet'] = $params->get('hotsheet');
        if ($params->get('country', 0)) $where['property']['country'] = $params->get('country');
        if ($params->get('locstate', 0)) $where['property']['locstate'] = $params->get('locstate');
        if ($params->get('province', 0)) $where['property']['province'] = $params->get('province');
        if ($params->get('region', 0)) $where['property']['region'] = $params->get('region');
        if ($params->get('county', 0)) $where['property']['county'] = $params->get('county');
        if ($params->get('city', 0)) $where['property']['city'] = $params->get('city');
        if ($params->get('subdivision', 0)) $where['property']['subdivision'] = $params->get('subdivision');
        if ($params->get('company', 0)) $where['property']['listing_office'] = $params->get('company');
        if ($params->get('agent', 0)) $where['agents'] = $params->get('agent');
        if ($params->get('prop_stype', 0)) $where['property']['stype'] = $params->get('prop_stype');
        
        // deal with categories
        if ($params->get('cat_id', 0) && $params->get('cat_subcats', 0))
        {            
            $cats_array = array( $params->get('cat_id') );
            $squery     = $db->setQuery(IpropertyHelperQuery::getCategories($params->get('cat_id')));
            $subcats    = $db->loadObjectList();
            
            foreach ($subcats as $s)
            {
                $cats_array[] = (int)$s->id;
            }
            $where['categories'] = $cats_array;
        } elseif ($params->get('cat_id', 0)){
            $where['categories'] = $params->get('cat_id');
        }
        
        // determine if we need to hide no pic results
        $hidenopic = $params->get('hidenopic') ? true : false;
        
        // get items using query helper
        $pquery = new IpropertyHelperQuery($db, $sort, $order);
        $query  = $pquery->buildPropertyQuery($where, 'properties');
        $db->setQuery($query, 0, $count);
        
        $items = ipropertyHelperProperty::getPropertyItems($db->loadObjectList(), true, false, $hidenopic);

		return $items;
	}
}
