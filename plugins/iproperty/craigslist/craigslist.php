<?php
/**
 * @version 3.3 2014-05-28
 * @package Joomla
 * @subpackage IReport
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin');
jimport('joomla.application.component.model');


class plgIpropertyCraigslist extends JPlugin
{
	private $settings;
	private $propdata;
	private $images;
	private $agents;
	private $amenities;
	
    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
        $this->settings = ipropertyAdmin::config();
        $this->loadLanguage();
    }

    public function onAfterRenderPropertyEdit($property, $settings)
    {
		$document = JFactory::getDocument();
		$css = ".textarea-label
		{
		  display: block;
		  /* border, padding, and other styles go here,
		  don't set the height, and don't use floats or positioning */
		}

		.textarea-label textarea
		{
		  border: 0 none;
		  margin: 0;
		  outline: 0 none;
		  padding: 0;
		  height: 100%;
		  width: 100%;
		}";
		$document->addStyleDeclaration($css);
		// build vars
		$saddress = IpropertyHTML::getStreetAddress($this->settings, $property);
		$details = array('lot_size', 'lot_acres', 'heat', 'cool', 'fuel', 'zoning', 'siding', 'roof', 'propview', 'school_district', 'lot_type', 'style', 'year_built', 'income', 'tax', 'total_units');
		// hack since language string doesn't always match field name
		$property->lot_size = $property->lotsize;
		$property->year_built = $property->yearbuilt;
		$property->year_built = $property->yearbuilt;
		$property->totalunits = $property->total_units;
		
		// build the HTML
		$html  = '<h1>'.IpropertyHTML:: getFormattedPrice($property->price, $property->stype_freq, false, $property->call_for_price);
		$html .= $property->beds ? ' - '.$property->beds.' '.JText::_('PLG_IP_CRAIGSLIST_BEDS_ABBREV') : '';
		$html .= $property->baths ? ' - '.$property->baths.' '.JText::_('PLG_IP_CRAIGSLIST_BATHS_ABBREV') : '';
		$html .= $property->sqft ? ' - '.$property->sqft.' '.JText::_('PLG_IP_CRAIGSLIST_SQFT_ABBREV') : '';
		$html .= ' - '.$saddress.' '.$property->city.'</h1>';
		$html .= $property->mls_id ? '<h3>'.JText::_('COM_IPROPERTY_MLS_ID').': '.$property->mls_id.'</h3>': '';
		$html .= $this->params->get('image', true) ? IpropertyHTML::getThumbnail($property->id, false, false, false, '', '', false, true, false).'<br />' : ''; 
		$html .= '<hr />'; 
		// get agents		
		if ($this->params->get('agent', false)){
			$agents = IpropertyHTML::getAvailableAgents($property_>id);
			if ($agents) {
				foreach($agents as $agent){
					$html .= '<strong>'.$agent->fname.' '.$agent->lname.'</strong>';
					if ($this->params->get('phone', false)){
						$html .= ' - '.$agent->phone;
					}
					if ($this->params->get('email', false)){
						$html .= ' - '.$agent->email;
					}
					$html .= '<br />'.IpropertyHTML::getCompanyName($agent->company);
				}
			}
		}
		$html .= '<h3>'.IpropertyHTML::getFullAddress($property, $saddress).'</h3><br/>';
		$html .= '<h3>';
		$html .= $property->beds ? $property->beds.' '.JText::_('PLG_IP_CRAIGSLIST_BEDS_FULL') : '';
		$html .= $property->baths ? ' | '.$property->baths.' '.JText::_('PLG_IP_CRAIGSLIST_BATHS_FULL') : '';
		$html .= $property->sqft ? ' | '.$property->sqft.' '.JText::_('PLG_IP_CRAIGSLIST_SQFT_FULL') : '';
		$html .= '</h3><br/>';
		
		if ($property->vtour){
			$html .= '<h3>'.JText::_('PLG_IP_CRAIGSLIST_TOUR').' '.$property->vtour.'</h3>';
			$html .= '<br /><hr />';
		}
		
		$html .= $property->short_description ? '<p>'.strip_tags($property->short_description).'</p>' : '<p>'.strip_tags($property->description).'</p>'; 
		
		// details
		$html .= '<h3>'.JText::_('COM_IPROPERTY_DETAILS').'</h3>';
		$html .= '<ul>';
		foreach ($details as $detail){
			if ($property->{$detail}){
				$html .= '<li><strong>'.JText::_('COM_IPROPERTY_'.$detail).':</strong> '.$property->{$detail}.'</li>';
			}
		}
		$html .= '</ul>';
		
		if ($property->listing_info) $html .= JText::_('PLG_IP_CRAIGSLIST_LISTEDBY').' '.$property->listing_info;		
		$html .= '<br />';
		$html .= '<h4>'.$this->params->get('disclaimer', false).'</h4>';
		
		// build the tab
		echo JHtmlBootstrap::addTab('ip-propview', 'ip_craigslist_tab', JText::_($this->params->get('tabtitle', 'PLG_IP_CRAIGSLIST')));
        echo '<div id="wrap">
				<label class="textarea-label">
				<textarea rows="30">';
		// output the HTML
		echo $html;		
		echo '</textarea>
			</label>
			</div>';
		// close the tab	
        echo JHtmlBootstrap::endTab();
    }
}
