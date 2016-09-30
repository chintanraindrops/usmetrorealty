<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

require_once (JPATH_ADMINISTRATOR.'/components/com_iproperty/classes/admin.class.php');

class JFormFieldSortBy extends JFormFieldList
{
    protected $type = 'sortby';

    public function getOptions()
	{       
        JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
        $jinput = JFactory::getApplication()->input;
        $options    = array();
        
        $settings   = ipropertyAdmin::config();
        $munits     = (!$settings->measurement_units) ? JText::_( 'COM_IPROPERTY_SQFTDD' ) : JText::_( 'COM_IPROPERTY_SQMDD' );
        
        if($settings->showtitle) $options['p.title'] = JText::_( 'COM_IPROPERTY_TITLE' );
       /* $options['p.street']    = JText::_( 'COM_IPROPERTY_STREET' );*/
        $options['p.yearbuilt']      = JText::_( 'COM_IPROPERTY_YEARBUILT' );
        $options['p.city']     = JText::_( 'COM_IPROPERTY_CITY' );
        $options['p.state']      = JText::_( 'COM_IPROPERTY_STATE' );
        $options['p.price2']     = JText::_( 'COM_IPROPERTY_PRICE' );
       /* $options['p.created']   = JText::_( 'COM_IPROPERTY_LISTED_DATE' );
        $options['p.modified']  = JText::_( 'COM_IPROPERTY_MODIFIED_DATE' );*/
        
        if($jinput->get('view') == 'settings') $options['RAND()'] = JText::_('COM_IPROPERTY_RANDOM');
        
        // Merge any additional options in the XML definition.
		if(isset($this->element))
        {            
            $options = array_merge(parent::getOptions(), $options);
            array_unshift($options, JHtml::_('select.option', '', JText::_('COM_IPROPERTY_SORT')));
        }

		return $options;
    }
}
