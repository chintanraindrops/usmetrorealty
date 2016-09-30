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

class plgIpropertyFbshare extends JPlugin
{
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    public function onAfterRenderProperty($property, $settings)
    {
        if ($this->params->get('position', 'bottom') == 'bottom') $this->_doFbShare($property, $settings);
    }

    public function onBeforeRenderProperty($property, $settings)
    {
        if ($this->params->get('position', 'top') == 'top') $this->_doFbShare($property, $settings);
    }

    private function _doFbShare($property, $settings)
    {
        $app        = JFactory::getApplication();
        $document   = JFactory::getDocument();
        $appid		= $this->params->get('appid', false);
        
        $fb_url		= "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
        
        if ($appid) $fb_url .= '&appId='.$appid;

        $uri 	= JRoute::_(JFactory::getURI()->toString());

        $script = '
            (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "'.$fb_url.'";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, "script", "facebook-jssdk"));';

        if($app->getName() != 'site') return true;

        $document->addScriptDeclaration($script);

        echo '<div id="plg_ip_facebookshare">';
        echo '  <div class="fb-share-button" data-href="'.$uri.'" data-layout="button_count"></div>';
        echo '</div>';

        return true;
    }
}
