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

class JFormFieldCountry extends JFormFieldList
{
    protected $type = 'Country';

    public function getOptions($available = false, $ctype = false)
	{
        $available  = (isset($this->element) && $this->element['available']) ? true : $available;
        $ctype      = (isset($this->element) && $this->element['ctype']) ? $this->element['ctype'] : $ctype;
        
        JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
        $options = array();

		$db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $query->select('DISTINCT(c.id), c.id AS value, c.title AS text')
            ->from('#__iproperty_countries as c');
        if($available){
            $query->join('INNER','#__iproperty AS p ON p.country = c.id');
        } else if ($ctype == 'agent'){
            $query->join('INNER','#__iproperty_agents AS a ON a.country = c.id');
        } else if ($ctype == 'company'){
            $query->join('INNER','#__iproperty_companies AS co ON co.country = c.id');
        }
        $query->where('c.published = 1');
        $query->order('c.title ASC');
        $db->setQuery($query);

        try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
        
        // Merge any additional options in the XML definition.
		if(isset($this->element))
        {            
            $options = array_merge(parent::getOptions(), $options);
            array_unshift($options, JHtml::_('select.option', '', JText::_('COM_IPROPERTY_COUNTRY')));
        }
        
        return $options;
    }
}


