<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

// Base this model on the allproperties model.
require_once __DIR__ . '/allproperties.php';

class IpropertyModelCompanyProperties extends IpropertyModelAllProperties
{    
    protected function getWhere()
    {        
        $where = parent::getWhere();
        
        $app                                    = JFactory::getApplication();
        $where['property']['listing_office']    = $app->input->get('id', '', 'uint');        
       
        return $where;
    }
}
?>