<?php

/**
 * @version		$Id: JINCSubscription.php 1-mar-2010 13.23.11 lhacky $
 * @package		plgJINCNewsSubscription
 * @subpackage
 * @copyright           Copyright (C) 2010 - Lhacky
 * @license		GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
 *   This file is part of JINC.
 *
 *   JINC is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   JINC is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with JINC.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Piwik system plugin
 */
class plgSystemJPiwik extends JPlugin {

    /**
     * JPikiw plugin constructor.
     *
     * @access	protected
     * @param	object	$subject The object to observe
     * @param 	array   $config  An array that holds the plugin configuration
     * @since	1.0
     */
    function plgSystemJPiwik(&$subject, $param) {
        parent::__construct($subject, $param);
    }

    private function getMenuTracking() {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $active = $menu->getActive();
        if ($active == NULL)
            return '';
        $menu_titles = array();
        $i = 0;
        while ($active != null && $i < 10) {
            array_push($menu_titles, $active->title);
            $active = $menu->getItems(array('id'), array($active->parent_id), true);
            $i++;
        }
        return implode('/', array_reverse($menu_titles));
    }

    private function getTitleTracking($cat_prefix = false) {
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');
        if ($option == "com_content" && $view == "article") {
            $ids = explode(':', JRequest::getString('id'));
            $article_id = $ids[0];
            $article = & JTable::getInstance("content");
            $article->load($article_id);
            $title = $article->get("title");
            if ($cat_prefix) {
                $category = & JTable::getInstance("category");
                if (isset($category) && is_object($category)) {
                    $category->load($article->get('catid'));
                    $title = $category->get('path') . '/' . $title;
                }
            }
            
            return $title;
        }
        return false;
    }

    /**
     * Writing Piwik Javascript code after Joomla! page rendering
     */
    function onAfterRender() {
        $mainframe = &JFACTORY::getApplication();

        if ($mainframe->isAdmin() || strpos($_SERVER["PHP_SELF"], "index.php") === false) {
            return;
        }

        $jpiwik_site_id = trim($this->params->get('jpiwik_site_id', ''));
        if ($jpiwik_site_id == '')
            return;

        $jpiwik_http_url = trim($this->params->get('jpiwik_http_url', ''));
        if (strlen($jpiwik_http_url) > 0) {
            if (!(substr($jpiwik_http_url, strlen($jpiwik_http_url) - 1) == '/')) {
                $jpiwik_http_url .= '/';
            }
        }
        $jpiwik_https_url = trim($this->params->get('jpiwik_https_url', ''));
        if (strlen($jpiwik_https_url) > 0) {
            if (!(substr($jpiwik_https_url, strlen($jpiwik_https_url) - 1) == '/')) {
                $jpiwik_https_url .= '/';
            }
        }
        if (strlen($jpiwik_https_url) == 0 && strlen($jpiwik_http_url) == 0)
            return;

        $jpiwik_pt_default = intval($this->params->get('jpiwik_pt_default', '1'));
        $jpiwik_pt_content = intval($this->params->get('jpiwik_pt_content', '1'));

        $jpiwik_domain_cookie = trim($this->params->get('jpiwik_domain_cookie', ''));
        $jpiwik_ignore_outlink = trim($this->params->get('jpiwik_ignore_outlink', ''));
        $jpiwik_dl_out = intval($this->params->get('jpiwik_dl_out', '1'));

        $jpiwik_cv_active_menu = intval($this->params->get('jpiwik_cv_active_menu', '1'));
        $jpiwik_cv_logged_user = intval($this->params->get('jpiwik_cv_logged_user', '1'));
        $jpiwik_cv_component = intval($this->params->get('jpiwik_cv_component', '1'));
        
        $jpiwik_download_css = trim($this->params->get('jpiwik_download_css', ''));
        $jpiwik_outlink_css = trim($this->params->get('jpiwik_outlink_css', ''));
        
        $cv_num = 1;

        $buffer = JResponse::getBody();
        $jpiwik_javascript = '<!-- Piwik -->
            <script type="text/javascript">
                var pkBaseURL = (("https:" == document.location.protocol) ? "' . $jpiwik_https_url . '" : "' . $jpiwik_http_url . '");
                document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
                </script><script type="text/javascript">
                try {
                    var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", ' . $jpiwik_site_id . ');
                        ';

        if ($jpiwik_cv_active_menu == 1) {
            $menu_title = $this->getMenuTracking();
            if (strlen($menu_title) > 0)
                $jpiwik_javascript .= '
                    piwikTracker.setCustomVariable(' . $cv_num . ', \'ActiveMenu\', \'' . addslashes($menu_title) . '\', \'page\');';
            $cv_num++;
        }

        if ($jpiwik_cv_logged_user == 1) {
            $user = & JFactory::getUser();
            if ($user->guest) {
                $logged_user = 'guest';
            } else {
                $logged_user = $user->username;
            }
            if (strlen($logged_user) > 0)
                $jpiwik_javascript .= '
                    piwikTracker.setCustomVariable(' . $cv_num . ', \'LoggedUser\', \'' . addslashes($logged_user) . '\', \'page\');';
            $cv_num++;
        }

        if ($jpiwik_cv_component == 1) {
            $option = JRequest::getCmd('option');
            $component = JComponentHelper::getComponent($option);
            $extension = JTable::getInstance('Extension');
            $extension->load($component->id);
            $jpiwik_javascript .= '
                    piwikTracker.setCustomVariable(' . $cv_num . ', \'ActiveComponent\', \'' . addslashes($option) . '\', \'page\');';
            $cv_num++;
        }

        switch ($jpiwik_pt_default) {
            case 1:
                $page_title = 'document.domain + "/" + document.title';
                break;

            case 2:
                $config = & JFactory::getConfig();
                $page_title = '\'' . addslashes($config->getValue('config.sitename')) . '\'';
                break;

            default:
                $page_title = 'document.title';
                break;
        }

        switch ($jpiwik_pt_content) {
            case 1:
                if ($doc_title = $this->getTitleTracking())
                    $page_title = '\'' . addslashes($doc_title) . '\'';
                break;

            case 2:
                if ($doc_title = $this->getTitleTracking(true))
                    $page_title = '\'' . addslashes($doc_title) . '\'';
                break;

            default:
                break;
        }
        if (strlen($page_title) > 0) {
            $jpiwik_javascript .= '
                    piwikTracker.setDocumentTitle(' . $page_title . ');';
        }

        if (strlen($jpiwik_domain_cookie) > 0) {
            $jpiwik_javascript .= '
                    piwikTracker.setCookieDomain(\'' . addslashes($jpiwik_domain_cookie) . '\');
                    piwikTracker.setDomains(\'' . addslashes($jpiwik_domain_cookie) . '\');';
        }

        if (strlen($jpiwik_ignore_outlink)) {
            $domain_array = explode(',', $jpiwik_ignore_outlink);
            foreach ($domain_array as $key => $domain) {
                $domain_array[$key] = '"' . addslashes(trim($domain)) . '"';
            }
            $jpiwik_javascript .= '
                    piwikTracker.setDomains([' . implode(',', $domain_array) . ']);';
        }
                
        if (strlen($jpiwik_download_css)) {
            $css_array = explode(',', $jpiwik_download_css);
            foreach ($css_array as $key => $css) {
                $css_array[$key] = '"' . addslashes(trim($css)) . '"';
            }
            $jpiwik_javascript .= '
                    css_download = new Array(' . implode(',', $css_array) . ');';
            $jpiwik_javascript .= '
                    piwikTracker.setDownloadClasses(css_download);';
        }
        
        if (strlen($jpiwik_outlink_css)) {
            $css_array = explode(',', $jpiwik_outlink_css);
            foreach ($css_array as $key => $css) {
                $css_array[$key] = '"' . addslashes(trim($css)) . '"';
            }
            $jpiwik_javascript .= '
                    css_outlink = new Array(' . implode(',', $css_array) . ');';
            $jpiwik_javascript .= '
                    piwikTracker.setLinkClasses(css_outlink);';
        }        
        
        $jpiwik_javascript .= '
                    piwikTracker.trackPageView();';

        if ($jpiwik_dl_out > 0) {
            $jpiwik_javascript .= '
                    piwikTracker.enableLinkTracking();';
        }

        $jpiwik_javascript .= '                    
                } catch( err ) {}
            </script><noscript><p><img src="' . $jpiwik_http_url . 'piwik.php?idsite=' . $jpiwik_site_id . '" style="border:0" alt="" /></p></noscript>
            <!-- End Piwik Tracking Code -->';


        $pos = strrpos($buffer, "</body>");
        if ($pos > 0) {
            $buffer = substr($buffer, 0, $pos) . $jpiwik_javascript . substr($buffer, $pos);
            JResponse::setBody($buffer);
        }

        return true;
    }

}

?>