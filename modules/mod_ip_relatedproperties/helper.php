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

class modIPRelatedHelper
{
    public static function getList(&$params, $prop_id)
	{
        $db     = JFactory::getDbo();
        $user   = JFactory::getUser();
        $groups	= $user->getAuthorisedViewLevels();
        
        $count                  = (int) $params->get('count', 5);
		$search_cat             = (int) $params->get('search_cat', 1);
        $search_city            = (int) $params->get('search_city', 1);
        $search_state           = (int) $params->get('search_state', 1);
        $search_province        = (int) $params->get('search_province', 1);
        $search_county          = (int) $params->get('search_county', 1);
        $search_region          = (int) $params->get('search_region', 1);
        $search_country         = (int) $params->get('search_country', 1);  
        $search_range	        = (int) $params->get('search_range', 0);
        
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

        //get current property data        
        $where['property']['id'] = (int) $prop_id;
        $pquery = new IpropertyHelperQuery($db);
        $query  = $pquery->buildPropertyQuery($where, 'property');
        $db->setQuery($query, 0, 1);
        
        if($property = $db->loadObject())
        {           
            $where  = array();            
            
            // Filter by start and end dates.
            $nullDate   = $db->Quote($db->getNullDate());
            $date       = JFactory::getDate();
            $nowDate    = $db->Quote($date->toSql());
            
            // if we have price range set get percentage
            $price_delta = false;
            if ($search_range) {
				$price_delta = ( $search_range / 100 ) * $property->price;
			}
            
            // set order
            if ($sort && !$order) // this means random sort (ie featured display)
			{
				$query->order($sort);
			}else if ($sort && $order){
				$query->order($sort . ' ' . $order);
			}

            // Join over prop mid table if getting related by category
			if ($params->get('search_cat'))
			{            
				$where['categories'] = $params->get('search_cat');
			}      
			            
            // Filter by locations
            if($property->city && $search_city)         $where['property']['city'] = $property->city;            
            if($property->locstate && $search_state)    $where['property']['locstate'] = $property->locstate;
            if($property->province && $search_province) $where['property']['province'] = $property->province;
            if($property->county && $search_county)     $where['property']['county'] = $property->county;
            if($property->region && $search_region)     $where['property']['region'] = $property->region;
            
            // Filter by price
            if ($price_delta) {
				$p_high = (int) $property->price + $price_delta;
				$p_low 	= (int) $property->price - $price_delta;
			
				$where['sliders']['price']['min'] = (int) $p_low;
				$where['sliders']['price']['max'] = (int) $p_high;
			}			
			
			// get items using query helper
			$pquery = new IpropertyHelperQuery($db, $sort, $order);
			$query  = $pquery->buildPropertyQuery($where, 'properties');
			// remove current listing ID from query
			$query->where('p.id != '.$property->id);			
			$db->setQuery($query, 0, $count); 
            $items = ipropertyHelperProperty::getPropertyItems($db->loadObjectList(), true, false, $hidenopic);

			return $items;
            
        }else{
            return false;
        }
	}
}
