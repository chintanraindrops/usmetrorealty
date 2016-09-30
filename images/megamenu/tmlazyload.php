<?php
/**
 * @Copyright
 * @package     TM Lazy Load
 * @author      TemplateMonster
 * @version     1.1.2
 * @link        http://www.templatemonster.com/
 *
 * @license     GNU/GPL
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

class PlgSystemTmLazyLoad extends JPlugin
{
    protected $_execute;

    function __construct(&$subject, $config)
    {
        // First check whether version requirements are met for this specific version
        if($this->checkVersionRequirements(false, '3.2', 'TM Lazy Load', 'plg_system_tmlazyload', JPATH_ADMINISTRATOR))
        {
            parent::__construct($subject, $config);
            $this->loadLanguage('', JPATH_ADMINISTRATOR);
            $this->_execute = true;
        }
    }

    /**
     * Do all checks whether the plugin has to be loaded and load needed JavaScript instructions
     */
    public function onBeforeCompileHead()
    {
        if($this->params->get('exclude_editor'))
        {
            if(class_exists('JEditor', false))
            {
                $this->_execute = false;
            }
        }

        if($this->params->get('exclude_bots') AND $this->_execute == true)
        {
            $this->excludeBots();
        }

        if($this->params->get('exclude_components') AND $this->_execute == true)
        {
            $this->excludeComponents();
        }

        if($this->params->get('exclude_urls') AND $this->_execute == true)
        {
            $this->excludeUrls();
        }

        if($this->_execute == true)
        {
            JHtml::_('jquery.framework');

            $head[] = '<script type="text/javascript" src="'.JURI::base().'plugins/system/tmlazyload/tmlazyload.js"></script>';
            $head[] = '<script type="text/javascript">
            jQuery(function($) {
                $("img.lazy").lazy({
                    threshold: 0,
                    visibleOnly: false,
                    effect: "fadeIn",
                    effectTime: 500,
                    enableThrottle: true,
                    throttle: 500,
                    afterLoad: function(element) {
                        if(typeof $.fn.BlackAndWhite_init == "function"){
                            jQuery(element).parents(".item_img a .lazy_container").BlackAndWhite_init();
                        }
                        setTimeout(function(){
                            element.parents(".lazy_container").addClass("lazyloaded");
                        },500)
                    }
                });
            });
            </script>';

            $head = "\n".implode("\n", $head)."\n";
            JFactory::getDocument()->addCustomTag($head);
        }
    }

    /**
     * Trigger onAfterRender executes the main plugin procedure
     */
    public function onAfterRender()
    {
        if($this->_execute == true)
        {
            $blankimage = JURI::base().'plugins/system/tmlazyload/blank.gif';
            $body = JFactory::getApplication()->getBody(false);

            $pattern = "@<img[^>]*src=[\"\']([^\"\']*)[\"\'][^>]*>@";
            preg_match_all($pattern, $body, $matches);

            if($this->params->get('exclude_imagenames') AND !empty($matches))
            {
                $this->excludeImageNames($matches);
            }

            if(!empty($matches))
            {
                foreach($matches[0] as $match)
                {
                    $dom = new DOMDocument();
                    $dom->loadHTML($match);
                    $info = @getimagesize($dom->getElementsByTagName('img')->item(0)->getAttribute('src'));
                    $class = $dom->getElementsByTagName('img')->item(0)->getAttribute('class');
                    $width = $info[0];
                    $height = $info[1];
                    if($height > 0){
                        $ratio = $info[1]*100/$info[0];
                    }
                    else {
                        $ratio = 0;
                    }
                    if ($class){
                        $arg_1 = array('src=','>','<img', 'class="');
                        $arg_2 = array('src="'.$blankimage.'" data-src=','></span>','<span class="lazy_container" style="width: '.$width.'px;"><span class="lazy_preloader" style="padding-top: '.$ratio.'%;"></span><img','class="lazy ');
                    }
                    else{
                        $arg_1 = array('src=','>','<img');
                        $arg_2 = array('src="'.$blankimage.'" class="lazy" data-src=','></span>','<span class="lazy_container" style="width: '.$width.'px;"><span class="lazy_preloader" style="padding-top: '.$ratio.'%;"></span><img');

                    }
                    $matchlazy = str_replace($arg_1, $arg_2, $match);
                    $body = str_replace($match, $matchlazy, $body);
                }

                JFactory::getApplication()->setBody($body);
            }
        }
    }

    /**
     * Excludes the execution in specified components if option is selected
     */
    private function excludeComponents()
    {
        $option = JFactory::getApplication()->input->getWord('option');
        $exclude_components = array_map('trim', explode("\n", $this->params->get('exclude_components')));
        $hit = false;

        foreach($exclude_components as $exclude_component)
        {
            if($option == $exclude_component)
            {
                $hit = true;
                break;
            }
        }

        if($this->params->get('exclude_components_toggle'))
        {
            if($hit == false)
            {
                $this->_execute = false;
            }
        }
        else
        {
            if($hit == true)
            {
                $this->_execute = false;
            }
        }
    }

    /**
     * Excludes the execution in specified URLs if option is selected
     */
    private function excludeUrls()
    {
        $url = JUri::getInstance()->toString();
        $exclude_urls = array_map('trim', explode("\n", $this->params->get('exclude_urls')));
        $hit = false;

        foreach($exclude_urls as $exclude_url)
        {
            if($url == $exclude_url)
            {
                $hit = true;
                break;
            }
        }

        if($this->params->get('exclude_urls_toggle'))
        {
            if($hit == false)
            {
                $this->_execute = false;
            }
        }
        else
        {
            if($hit == true)
            {
                $this->_execute = false;
            }
        }
    }

    /**
     * Excludes the execution in specified image names if option is selected
     *
     * @param $matches
     */
    private function excludeImageNames(&$matches)
    {
        $exclude_image_names = array_map('trim', explode("\n", $this->params->get('exclude_imagenames')));
        $exclude_imagenames_toggle = $this->params->get('exclude_imagenames_toggle');
        $matches_temp = array();

        foreach($exclude_image_names as $exclude_image_name)
        {
            $count = 0;

            foreach($matches[1] as $match)
            {
                if(preg_match('@'.preg_quote($exclude_image_name).'@', $match))
                {
                    if(empty($exclude_imagenames_toggle))
                    {
                        unset($matches[0][$count]);
                    }
                    else
                    {
                        $matches_temp[] = $matches[0][$count];
                    }
                }

                $count++;
            }
        }

        if($exclude_imagenames_toggle)
        {
            unset($matches[0]);
            $matches[0] = $matches_temp;
        }
    }

    /**
     * Excludes the execution for specified bots if option is selected
     */
    private function excludeBots()
    {
        $exclude_bots = array_map('trim', explode(",", $this->params->get('botslist')));
        $agent = $_SERVER['HTTP_USER_AGENT'];

        foreach($exclude_bots as $exclude_bot)
        {
            if(preg_match('@'.$exclude_bot.'@i', $agent))
            {
                $this->_execute = false;
                break;
            }
        }
    }

    /**
     * Checks whether all requirements are met for the execution
     *
     * @param $admin                 Allow backend execution - true or false
     * @param $version_min           Minimum required Joomla! version - e.g. 3.2
     * @param $extension_name        Name of the extension of the warning message
     * @param $extension_system_name System name of the extension for the language file loading - e.g. plg_system_xxx
     * @param $jpath                 Path of the language file - JPATH_ADMINISTRATOR or JPATH_SITE
     *
     * @return bool
     */
    private function checkVersionRequirements($admin, $version_min, $extension_name, $extension_system_name, $jpath)
    {
        $execution = true;
        $version = new JVersion();

        if(!$version->isCompatible($version_min))
        {
            $execution = false;
            $backend_message = true;
        }

        if(empty($admin))
        {
            if(JFactory::getApplication()->isAdmin())
            {
                $execution = false;

                if(!empty($backend_message))
                {
                    $this->loadLanguage($extension_system_name, $jpath);
                    JFactory::getApplication()->enqueueMessage(JText::sprintf('KR_JOOMLA_VERSION_REQUIREMENTS_NOT_MET', $extension_name, $version_min), 'warning');
                }
            }
        }

        return $execution;
    }
}
