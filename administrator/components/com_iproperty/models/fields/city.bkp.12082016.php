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

class JFormFieldCity extends JFormFieldList
{
    protected $type = 'City';

    public function getOptions($locstate = false, $ctype = 'property')
    {
        $locstate   = (isset($this->element) && $this->element['locstate']) ? $this->element['locstate'] : $locstate;
        $ctype       = (isset($this->element) && $this->element['ctype']) ? $this->element['ctype'] : $ctype;

        $table = '#__iproperty';

        switch ($ctype){
            case 'agent':
                $table = '#__iproperty_agents';
                break;
            case 'company':
                $table = '#__iproperty_companies';
                break;
            default:
                $table = '#__iproperty';
                break;
        }

        JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
        $options = array();

        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $query->select('DISTINCT(city) AS value, city AS text')
            ->from($table)
            ->where('state = 1')
            ->where('city != ""');
        if($locstate){
            $query->where('locstate = '.(int)$locstate);
        }
        $query->order('city ASC');
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
            array_unshift($options, JHtml::_('select.option', '', JText::_('COM_IPROPERTY_CITY')));
        }

        return $options;
    }
}


