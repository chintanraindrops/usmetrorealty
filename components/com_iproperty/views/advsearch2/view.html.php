<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.view');

class IpropertyViewAdvsearch2 extends JViewLegacy
{
	protected $ipbaseurl;
    protected $ipauth;
    protected $fullscreen;

    function display($tpl = null)
    {
        jimport('joomla.filesystem.file');
        JHtml::_('jquery.framework'); // load jquery
        JHtml::_('jquery.ui'); // load jquery ui

        $app            = JFactory::getApplication();
        $itemid         = (JRequest::getInt('recallSearch')) ? JRequest::getInt('recallSearch') : JRequest::getInt('Itemid', 99999);
        $this->params   = $app->getParams();
        $lang 			= JFactory::getLanguage();
        $rtl            = $lang->isRTL() ? 1 : 0;

        JPluginHelper::importPlugin( 'iproperty');
        $dispatcher = JDispatcher::getInstance();

        $this->ipbaseurl    = JURI::root(true);
        $document           = JFactory::getDocument();
        $settings           = ipropertyAdmin::config();
        $user               = JFactory::getUser();
        $this->ipauth       = new ipropertyHelperAuth();
        $uri                = JURI::getInstance();       
        $this->return       = base64_encode($uri);
        $this->return_page	= $this->get('ReturnPage');
        $this->fullscreen   = $app->input->getCmd('tmpl') == 'component';

        if(!$settings->map_provider){
            $this->_displayNoAccess();
            return;
        }
		
		// if we have map, then set IPGMAP
        define('IPGMAP', 1);

		// get currency options
		$c_format	= $settings->nformat ? ',' : '.';
		$c_symbol	= $settings->currency ?: '$';
		$c_round	= $settings->currency_digits ?: '-2';
		$c_position = $settings->currency_pos ? '%n %s' : '%s %n'; // TODO: not sure if supported by currency plugin

        // check for template map and property preview icons
        $house_icon_path        = '/components/com_iproperty/assets/images/map/';
        $templatepath           = $app->getTemplate();
        if(JFile::exists('templates'.'/'.$templatepath.'/images/iproperty/map/icon56.png')) $map_house_icon = '/templates/'.$templatepath.'/images/iproperty/map/icon56.png';

        // Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

        // load jQuery cookie plugin
        $document->addScript($this->ipbaseurl.'/components/com_iproperty/assets/js/jquery.cookie.js');
		
        // add advsearch css
        $document->addStyleSheet($this->ipbaseurl.'/components/com_iproperty/assets/css/advsearch.css');
		
        // create toolbar
        $dispatcher->trigger('onBeforeRenderToolbar', array($settings));

        $this->assignRef('settings'     , $settings);
        $this->assignRef('dispatcher'   , $dispatcher);
        $this->assignRef('user'         , $user);

        $munits     = (!$settings->measurement_units) ? JText::_('COM_IPROPERTY_SQFT2' ) : JText::_('COM_IPROPERTY_SQM2');

        // set layout to boolean, since pre-3.0rc1 options were 'table' and 'overview'
        $this->adv_layout = ($this->params->get('adv_layout') == 0 || $this->params->get('adv_layout') == 'table') ? 0 : 1;
        
        // get categories
        $cats = ipropertyHTML::getCatChildren();
        $this->cat_array = array();
        
        foreach ($cats as $c){
			$this->cat_array[$c->id] = $c->title;
		}
		
		// get stypes
		$this->stypes = ipropertyHTML::get_stypes();
		
        $mapscript = "
		var iptoolsshown = true;
        var langOptions = {
            tprop:'".addslashes(JText::_('COM_IPROPERTY_RESULTS'))."',
            price:'".addslashes(JText::_('COM_IPROPERTY_PRICE'))."',
            nolimit: '".addslashes(JText::_('COM_IPROPERTY_NO_LIMIT'))."',
            pid: '".addslashes(JText::_('COM_IPROPERTY_PROPERTY_ID'))."',
            street: '".addslashes(JText::_('COM_IPROPERTY_STREET'))."',
            beds: '".addslashes(JText::_('COM_IPROPERTY_BEDS'))."',
            baths: '".addslashes(JText::_('COM_IPROPERTY_BATHS'))."',
            sqft: '".addslashes($munits)."',
            more: '".addslashes(JText::_('COM_IPROPERTY_MORE' ))."',
            inputText: '".addslashes(JText::_('COM_IPROPERTY_INPUT_TIP'))."',
            noRecords: '".addslashes(JText::_('COM_IPROPERTY_NO_RECORDS_TEXT'))."',
            searchopt: '".addslashes(JText::_('COM_IPROPERTY_SEARCH_OPTIONS'))."',
            savesearch: '".addslashes(JText::_('COM_IPROPERTY_SAVESEARCH'))."',
            clearsearch: '".addslashes(JText::_('COM_IPROPERTY_CLEARSEARCH'))."',
            results: '".addslashes(JText::_('COM_IPROPERTY_RESULTS'))."',
            no_results: '".addslashes(JText::_('COM_IPROPERTY_NO_RESULTS'))."',
            isRTL: ".$rtl."
        };

        var searchOptions = {
            limit: ".$this->params->get('adv_perpage', 50000)."
        };

        var mapOptions = {
            resultColumns: [],
            debug: false,
            currentvals : {
                'Itemid': ".$itemid.",
                'property': {},
                'sliders': {},
                'categories': []
            },
            ipbaseurl: '".JURI::root()."',
            itemid: ".$itemid.",
            marker_path: '".$house_icon_path."',
            token: '',
            zoom: ".$this->params->get('adv_default_zoom', 8).",
            maxZoom: 25,
            lat: '".$this->params->get('adv_default_lat', $settings->adv_default_lat)."',
            lng: '".$this->params->get('adv_default_long', $settings->adv_default_long)."',
            maptype: '".$settings->adv_maptype."',
            credentials: '".$settings->map_credentials."',
            sort: '".$settings->default_p_sort."',
            order: '".$settings->default_p_order."',
            maptools: ".$this->params->get('adv_show_shapetools', $settings->adv_show_shapetools).",
            layout: ".$this->adv_layout.",
            savesearch: ".$settings->show_savesearch.",
			mapcluster: 1,
			user_id: ".$this->user->id.",
			ajaxroute: '".JRoute::_('index.php?option=com_iproperty&format=raw')."'
        };
        jQuery(function($) {
            $(document).ready(function(){
				// get token
				$.get( mapOptions.ipbaseurl+'index.php?option=com_iproperty&format=raw&task=ajax.getToken', function( data ) {
					mapOptions.token = data;	
					// create initial page elements
					//ipMapFunctions.handleCookie();
					ipMapFunctions.buildMap(mapOptions);
				});
				
				// add change events to the controls
				jQuery('.ip_selector').on('change', function(){
					ipMapFunctions.getAjaxResults();
				});	
            });
            
            $('#ip-mapcontrol-show').on('click', function () {    
				if (iptoolsshown) {
					// animate to hidden
                    ipMapFunctions.toggleControls(false);
                    iptoolsshown = false;					
				} else {       
					// animate to shown
					ipMapFunctions.toggleControls(true);
                    iptoolsshown = true;
				}
			});
        });
        var cat_icons = [];"."\n";
        
        foreach ($cats as $c){
			$mapscript .= 'cat_icons['.$c->id.'] = "'.$c->icon.'";'."\n";
		}

        $document->addScriptDeclaration($mapscript);

        $this->_prepareDocument();
        parent::display($tpl);
    }

    protected function _prepareDocument()
    {
        $app            = JFactory::getApplication();
        $menus          = $app->getMenu();
        $pathway        = $app->getPathway();
        $this->params   = $app->getParams();
        $title          = null;

        $menu = $menus->getActive();
        if ($menu) {
            $this->params->def('page_heading', $this->params->get('page_title', $menu->title));
        } else {
            $this->params->def('page_heading', JText::_('COM_IPROPERTY_INTELLECTUAL_PROPERTY' ));
        }

        $title = (is_object($menu) && $menu->query['view'] == 'advsearch2') ? $menu->title : JText::_('COM_IPROPERTY_ADVANCED_SEARCH');
        $browser_title = (is_object($menu) && $menu->query['view'] == 'advsearch2') ? $this->params->get('page_title') : $title;

        $this->iptitle = $title;
        if (empty($title)) {
            $browser_title = $app->getCfg('sitename');
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $browser_title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $browser_title);
        }
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $browser_title = JText::sprintf('JPAGETITLE', $browser_title, $app->getCfg('sitename'));
        }

        $this->document->setTitle($browser_title);

        // Set meta data according to menu params
        if ($this->params->get('menu-meta_description', '')) $this->document->setDescription($this->params->get('menu-meta_description', ''));
        if ($this->params->get('menu-meta_keywords', '')) $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords', ''));
        if ($this->params->get('robots')) $this->document->setMetadata('robots', $this->params->get('robots'));

        // Breadcrumbs
        if(is_object($menu) && $menu->query['view'] != 'advsearch') {
            $pathway->addItem($this->iptitle);
        }
    }

    function _displayNoAccess($tpl = 'noaccess')
    {
        $app                = JFactory::getApplication();
        $this->ipbaseurl    = JURI::root(true);
        $document           = JFactory::getDocument();
        $settings           = ipropertyAdmin::config();
        $pathway            = $app->getPathway();

        // Get the menu item object
        $menus = $app->getMenu();
        $menu  = $menus->getActive();

        $document->setTitle( JText::_('COM_IPROPERTY_ADVANCED_SEARCH'));
        //set breadcrumbs
        if(is_object($menu) && $menu->query['view'] != 'advsearch') {
            $pathway->addItem(JText::_('COM_IPROPERTY_ADVANCED_SEARCH'), '');
        }

        $this->assignRef('settings', $settings);

        parent::display($tpl);
    }
}
?>
