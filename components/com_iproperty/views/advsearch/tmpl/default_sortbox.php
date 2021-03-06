<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
$app        = JFactory::getApplication();
$jinput     = $app->input;
$document   = JFactory::getDocument();
$document->addScript($this->ipbaseurl.'/components/com_iproperty/assets/js/jcombo.js');

// First, let's find out if there are any pre-set menu params
// build the pre-defined breadcrumb display
// set hierarchy and current values to pass to cascading drop downs for locations search
$predefines = '';
$hierarchy  = 0;
$currvals   = array();
if($this->params->get('cat'))       $predefines     .= '<li>'.ipropertyHTML::getCatName($this->params->get('cat')).'<span class="divider">/</span></li>';
if($this->params->get('stype')){
    foreach($this->params->get('stype') as $st){
        $predefines     .= '<li>'.ipropertyHTML::get_stype($st).'<span class="divider">/</span></li>';
    }
}
if($this->params->get('beds'))      $predefines     .= '<li>'.$this->params->get('beds').' '.JText::_('COM_IPROPERTY_BEDS').'<span class="divider">/</span></li>';
if($this->params->get('baths'))     $predefines     .= '<li>'.$this->params->get('baths').' '.JText::_('COM_IPROPERTY_BATHS').'<span class="divider">/</span></li>';
if($this->params->get('price_low')) $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MIN_PRICE').' '.ipropertyHTML::getFormattedPrice($this->params->get('price_low')).'<span class="divider">/</span></li>';
if($this->params->get('price_high'))$predefines     .= '<li>'.JText::_('COM_IPROPERTY_MAX_PRICE').' '.ipropertyHTML::getFormattedPrice($this->params->get('price_high')).'<span class="divider">/</span></li>';
if($this->params->get('sqft_low'))  $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MIN_SQFT').' '.$this->params->get('sqft_low').'<span class="divider">/</span></li>';
if($this->params->get('sqft_high')) $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MAX_SQFT').' '.$this->params->get('sqft_high').'<span class="divider">/</span></li>';
if($this->params->get('acres_low'))  $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MIN_ACRES').' '.$this->params->get('acres_low').'<span class="divider">/</span></li>';
if($this->params->get('acres_high')) $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MAX_ACRES').' '.$this->params->get('acres_high').'<span class="divider">/</span></li>';
if($this->params->get('lotsize_low'))  $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MIN_LOTSIZE').' '.$this->params->get('lotsize_low').'<span class="divider">/</span></li>';
if($this->params->get('lotsize_high')) $predefines     .= '<li>'.JText::_('COM_IPROPERTY_MAX_LOTSIZE').' '.$this->params->get('lotsize_high').'<span class="divider">/</span></li>';
if($this->params->get('hoa'))       $predefines     .= '<li>'.JText::_('COM_IPROPERTY_HOA').'<span class="divider">/</span></li>';
if($this->params->get('reo'))       $predefines     .= '<li>'.JText::_('COM_IPROPERTY_REO').'<span class="divider">/</span></li>';
if($this->params->get('waterfront'))$predefines     .= '<li>'.JText::_('COM_IPROPERTY_FRONTAGE').'<span class="divider">/</span></li>';
// location
if($this->params->get('country')){ 
    $currvals['country'] = $this->params->get('country');
    $predefines .= '<li>'.ipropertyHTML::getCountryName($this->params->get('country')).'<span class="divider">/</span></li>';
}
if($this->params->get('locstate')){
    $hierarchy = 1;
    $currvals['locstate'] = $this->params->get('locstate');
    $predefines .= '<li>'.ipropertyHTML::getStateName($this->params->get('locstate')).'<span class="divider">/</span></li>';
}
if($this->params->get('province')){
    $hierarchy = 2;
    $currvals['province'] = $this->params->get('province');
    $predefines .= '<li>'.$this->params->get('province').'<span class="divider">/</span></li>';
}
if($this->params->get('region')){
    $hierarchy = 3;
    $currvals['region'] = $this->params->get('region');
    $predefines .= '<li>'.$this->params->get('region').'<span class="divider">/</span></li>';
}
if($this->params->get('county')){
    $hierarchy = 4;
    $currvals['county'] = $this->params->get('county');
    $predefines .= '<li>'.$this->params->get('county').'<span class="divider">/</span></li>';
}
if($this->params->get('city')){
    $hierarchy = 5;
    $currvals['city'] = $this->params->get('city');
    $predefines .= '<li>'.$this->params->get('city').'<span class="divider">/</span></li>';
}
if($this->params->get('subdivision')){
    $hierarchy = 6;
    $currvals['subdivision'] = $this->params->get('subdivision');
    $predefines .= '<li>'.$this->params->get('subdivision').'<span class="divider">/</span></li>';
}

// Set default values for parent vars in cascading drop downs
$currvals       = json_encode($currvals);
$parent         = '';
$parent_field   = '';

// Build cascading drop downs depending on settings and menu params
// hierarchy ==> country, state, province, county, region, city
$jcomboscript   = "
var globalParent = null;
jQuery(document).ready(function($){
	var url = '".$this->ipbaseurl."/index.php?option=com_iproperty&format=raw&task=ajax.getLocOptions&".JSession::getFormToken()."=1';";
	if ( $this->params->get('qs_show_country', $this->settings->qs_show_country) && !$this->params->get('country') && $hierarchy < 1 ){
		$jcomboscript .= "
		$('#ip-qs-country').jCombo(url+'&loctype=country', {
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_COUNTRY'))."',
			selected_value: ".(int)$this->state->get('filter.country')."
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-country';
	}
	if ( $this->params->get('qs_show_state', $this->settings->qs_show_state) && !$this->params->get('locstate') && $hierarchy < 2 ){
		$jcomboscript .= "
		$('#ip-qs-locstate').jCombo(url+'&loctype=locstate&pdvals=".$currvals."&id=', { 
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_STATE'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= "
			selected_value: ".(int)$this->state->get('filter.locstate')."
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-locstate';
	}
	if ( $this->params->get('qs_show_province', $this->settings->qs_show_province) && !$this->params->get('province') && $hierarchy < 3 ){
		$jcomboscript .= "
		$('#ip-qs-province').jCombo(url+'&loctype=province&pdvals=".$currvals."&id=', { 
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_PROVINCE'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= "
			selected_value: '".addslashes($this->state->get('filter.province'))."'
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-province';
	}
	if ( $this->params->get('qs_show_region', $this->settings->qs_show_region) && !$this->params->get('region') && $hierarchy < 5 ){
		$jcomboscript .= "
		$('#ip-qs-region').jCombo(url+'&loctype=region&pdvals=".$currvals."&id=', { 
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_REGION'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= "
			selected_value: '".addslashes($this->state->get('filter.region'))."'
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-region';
	}
	if ( $this->params->get('qs_show_county', $this->settings->qs_show_county) && !$this->params->get('county') && $hierarchy < 4 ){
		$jcomboscript .= "
		$('#ip-qs-county').jCombo(url+'&loctype=county&pdvals=".$currvals."&id=', { 
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_COUNTY'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= "
			selected_value: '".addslashes($this->state->get('filter.county'))."'
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-county';
	}
	if ( $this->params->get('qs_show_city', $this->settings->qs_show_city) && !$this->params->get('city') ){
		$jcomboscript .= "
		$('#ip-qs-city').jCombo(url+'&loctype=city&pdvals=".$currvals."&id=', {
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_CITY'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= " 
			selected_value: '".addslashes($this->state->get('filter.city'))."' 
		});";
		if ($this->settings->qs_show_cascade) $parent_field   = '#ip-qs-city';	
	}
	if ( $this->params->get('qs_show_subdivision', $this->settings->qs_show_subdivision) && !$this->params->get('subdivision') ){
		$jcomboscript .= "
		$('#ip-qs-subdivision').jCombo(url+'&loctype=subdivision&pdvals=".$currvals."&id=', {
			first_optval : '',
			initial_text: '".addslashes(JText::_('COM_IPROPERTY_SUBDIVISION'))."',";
			if($parent_field) $jcomboscript .= "\n\t\t\tparentField: (globalParent != null) ? globalParent : '".$parent_field."',";
			$jcomboscript .= " 
			selected_value: '".addslashes($this->state->get('filter.subdivision'))."' 
		});";            
	}	
$jcomboscript .= "
})";
		
$document->addScriptDeclaration($jcomboscript);


// require the IP fields
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/ipcategory.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/stypes.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/beds.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/baths.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/sortby.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/orderby.php';

// build filter lists from fields
$tmpstypes      = new JFormFieldStypes();
$tmpcats        = new JFormFieldIpCategory();
$tmpbeds        = new JFormFieldBeds();
$tmpbaths       = new JFormFieldBaths();
$tmpsort        = new JFormFieldSortBy();
$tmporder       = new JFormFieldOrderBy();

$munits     = (!$this->settings->measurement_units) ? JText::_('COM_IPROPERTY_SQFT2' ) : JText::_('COM_IPROPERTY_SQM2');
?>

<?php if($predefines): ?>
    <ul class="breadcrumb ip-pd-breadcrumb">
        <?php echo $predefines; ?>
    </ul>
<?php endif; ?>
<div id="ip-searchfilter-wrapper" class="well hidden-phone">
    <div id="ip-mainfilter-container">        
        <form action="<?php echo JRoute::_('index.php?option=com_iproperty&view='.$jinput->getCmd('view').'&id='.$jinput->getInt('id')); ?>" method="post" name="ip_quick_search" class="ip-quicksearch-form form-inline" id="ip-quicksearch-form" novalidate="novalidate">
            <div class="ip-quicksearch-sortholder">
                <div class="control-group pull-right">
                    <select name="filter_order" class="input-medium">
                        <option value=""><?php echo JText::_('COM_IPROPERTY_SORTBY'); ?></option>
                        <?php echo JHTML::_('select.options', $tmpsort->getOptions(), 'value', 'text', $this->state->get('list.ordering')); ?>
                    </select> 
                    <select name="filter_order_Dir" class="input-medium">
                        <option value=""><?php echo JText::_('COM_IPROPERTY_ORDERBY'); ?></option>
                        <?php echo JHTML::_('select.options', $tmporder->getOptions(), 'value', 'text', $this->state->get('list.direction')); ?>
                    </select>  
                    <div class="btn-group">
                        <button class="btn" onclick="clearForm(this.form);this.form.submit();" type="button"><?php echo JText::_('COM_IPROPERTY_RESET'); ?></button>
                        <button class="btn btn-primary" name="commit" type="submit"><?php echo JText::_('COM_IPROPERTY_SEARCH'); ?></button>
                    </div>
                </div>
            </div>
            <?php echo JHTML::_( 'form.token'); ?>
        </form>
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>
