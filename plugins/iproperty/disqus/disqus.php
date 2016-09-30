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

class plgIpropertyDisqus extends JPlugin
{
    public function __construct(&$subject, $config)  
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onBeforeRenderForms($property, $settings)
    {
		if ($this->params->get('position')) return true; 
        $this->_doCommentTab($property, $settings);
    }
	
	public function onAfterRenderForms($property, $settings)
    {
        if (!$this->params->get('position')) return true; 
        $this->_doCommentTab($property, $settings);
    }

	private function _doCommentTab($property, $settings)
	{
		$app        = JFactory::getApplication();
        $document   = JFactory::getDocument();
        $disqus_shortname = (string) $this->params->get('disqus_shortname', false);
        
        if($app->getName() != 'site' || !$disqus_shortname) return true;
        
        $posts  = (int) $this->params->get('number', 5);
        $scheme = (boolean) $this->params->get('scheme') ? 'dark' : 'light';
        $width  = (int) $this->params->get('width', 600);
	
		$script  = "var disqus_shortname = '".$disqus_shortname."';"."\n";
		$script .= "var disqus_identifier = '".$property->mls_id."';"."\n";
		$script .= "var disqus_title = '".$property->street_address."';"."\n";
		$script .= "var disqus_url = '".JUri::getInstance()."';"."\n";
		$script .= "(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();";
		
		$document->addScriptDeclaration($script);

        echo JHtmlBootstrap::addTab('ipDetails', 'ipdisqusplug', JText::_($this->params->get('tabtitle', 'PLG_IP_DISQUS_COMMENTS')));
            ?>
            <div id="disqus_thread"></div> 
            <?php
        echo JHtmlBootstrap::endTab();
        
        return true;	
	}
}
