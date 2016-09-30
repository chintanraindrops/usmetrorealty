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

class JFormFieldCompanySortBy extends JFormFieldList
{
    protected $type = 'CompanySortby';

    public function getOptions()
	{
        JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
        $jinput = JFactory::getApplication()->input;
        
        $options = array('name' => JText::_('COM_IPROPERTY_NAME'),
                         'city' => JText::_('COM_IPROPERTY_CITY'));
                         
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


