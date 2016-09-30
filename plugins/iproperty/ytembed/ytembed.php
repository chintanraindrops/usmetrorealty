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

class plgIpropertyYtembed extends JPlugin
{	
	protected $position;
	protected $order;
	
	public function __construct(&$subject, $config)  
    {
		parent::__construct($subject, $config);
		$this->position = (int)$this->params->get('position', 0, 'INT');
		$this->order = (int)$this->params->get('order', 0, 'INT');
        $this->loadLanguage();
	}

	public function onBeforeRenderProperty($property, $settings)
	{
        if ($this->position !== 0) return;	
        $app = JFactory::getApplication();
        if($app->getName() != 'site') return;
        if(!$property->video) return;
        echo $this->createEmbedCode($property);
    }
    
    public function onBeforeRenderForms($property, $settings)
    {
		if ($this->position !== 2) return;
        if (!$this->order) $this->buildMainTab($property, $settings);
    }
	
	public function onAfterRenderForms($property, $settings)
    {
        if ($this->position !== 2) return;
        if ($this->order) $this->buildMainTab($property, $settings);
    } 
    
    public function onAfterRenderMap($property, $settings)
    {
        if ($this->position !== 1) return;
        $this->buildMapTab($property, $settings);
    }
    	
	private function buildMainTab($property, $settings)
	{		
        $app        = JFactory::getApplication();
        
        if($app->getName() != 'site') return true;
        if(!$property->video) return true;
		
		echo JHtmlBootstrap::addTab('ipDetails', 'ipytembed', JText::_($this->params->get('tabtitle', 'PLG_IP_YTEMBED_VIDEO')));
            echo $this->createEmbedCode($property);
        echo JHtmlBootstrap::endTab();
		
		return true;	
	}
	
	private function buildMapTab($property, $settings)
	{		
        $app        = JFactory::getApplication();
        
        if($app->getName() != 'site') return true;
        if(!$property->video) return true;

        echo JHtmlBootstrap::addTab('ipMap', 'ipytembed', JText::_($this->params->get('tabtitle', 'PLG_IP_YTEMBED_VIDEO')));
            echo $this->createEmbedCode($property);
        echo JHtmlBootstrap::endTab();
		
		return true;	
	}
	
	    private function createEmbedCode($property)
    {		
		if (!$property->video) return false; 
		$height = $this->params->get('height', 350);
		
		$embed_code = '<iframe width="100%" height="'.$height.'" src="//www.youtube.com/embed/'.$property->video.'" frameborder="0" allowfullscreen></iframe>';
        return $embed_code;
	}
}
