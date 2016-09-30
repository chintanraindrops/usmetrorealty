<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC' ) or die( 'Restricted access');
jimport('joomla.plugin.plugin');

class plgIpropertyJlike extends JPlugin
{
	public function plgIpropertyJlike(&$subject, $config)  {
		parent::__construct($subject, $config);
        $this->loadLanguage(); 
	}

    public function onBeforeRenderForms($property, $settings)
    {
		if ($this->params->get('position')) return true;
        $this->_doJlikeForm($property);
    }
	
	public function onAfterRenderForms($property, $settings)
    {
        if (!$this->params->get('position')) return true;
        $this->_doJlikeForm($property);
    }
    
    private function _doJlikeForm($property)
	{
        $app = JFactory::getApplication();
		if($app->getName() != 'site') return true;       
        
        // Make sure the jLike helper class exists
        if(file_exists(JPATH_SITE.'/components/com_jlike/helper.php')){
            require_once(JPATH_SITE.'/components/com_jlike/helper.php');
        }else{
            return true;
        }
        
        // set vars for options
        $show_comments      = $this->params->get('show_comments', 1);
		$show_like_buttons  = $this->params->get('show_like_buttons', 1);
        
        // Set data to be used by jLike helper
        JRequest::setVar ( 'data', 
            json_encode ( array (
                'cont_id' => $property->id, 
                'element' => 'com_iproperty.property', 
                'title' => $property->street_address, 
                'url' => $property->proplink, 
                'plg_name' => 'jlike_iproperty', 
                'show_comments' => $show_comments, 
                'show_like_buttons' => $show_like_buttons 
                ) 
            ) 
        );
        
        // instantiate jlike helper class
        $jlike_helper   = new comjlikeHelper();
        $jlike_html     = $jlike_helper->showlike();       
        
        // Echo new tab to display jlike html        
        echo JHtmlBootstrap::addTab('ipDetails', 'ipjlikeplug', JText::_($this->params->get('tabtitle', 'PLG_IP_JLIKE_COMMENTS')));
            echo $jlike_html;
        echo JHtmlBootstrap::endTab();
		
		return true;
	}	
}
