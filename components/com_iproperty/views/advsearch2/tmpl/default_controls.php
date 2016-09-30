<?php
/**
 * @version 3.3.2 2012-12-04
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

// check if there's saved search URL var
$jinput = JFactory::getApplication()->input;
$search = $jinput->get('searchId', false, 'INTEGER');
$saved_search = false;

if ($search) {
	// get the search params from the stored record
	$db = JFactory::getDbo();
	$query = $db->getQuery(true);
	$query->select($db->quoteName('search_string'))
		->from($db->quoteName('#__iproperty_saved'))
		->where($db->quoteName('id') . ' = '. $db->quote($search));
	
	$db->setQuery($query);
	$results = $db->loadObject();
	$saved_search = json_decode($results->search_string);
	
	// check for geopoint data
	if ($saved_search->geopoint && $saved_search->geopoint->sw && $saved_search->geopoint->ne){
		// set the map bounds to match the saved search bounds
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
			var saved_bounds = new google.maps.LatLngBounds(
				new google.maps.LatLng('.$saved_search->geopoint->sw[0].','.$saved_search->geopoint->sw[1].'),
				new google.maps.LatLng('.$saved_search->geopoint->ne[0].','.$saved_search->geopoint->ne[1].')
			);
		');
	} 
}
?>

<div id="ip-mapcontrols" class="panel-group">
    <div class="ip-mapcontrols-container">
    <h3><?php echo JText::_('COM_IPROPERTY_SEARCH'); ?></h3>
    <hr />
    <form class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="price_min"><?php echo JText::_('COM_IPROPERTY_MIN_PRICE'); ?></label>
			<div class="controls">
				<?php
					$preset = ($saved_search && $saved_search->sliders->price->min) ? $saved_search->sliders->price->min : $this->params->get('adv_price_low');
					echo ipropertyHTML::priceSelectList('price_min', array('class' => "ip_selector"), $preset, false, false); 
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="price_max"><?php echo JText::_('COM_IPROPERTY_MAX_PRICE'); ?></label>
			<div class="controls">
				<?php 
					$preset = ($saved_search && $saved_search->sliders->price->max) ? $saved_search->sliders->price->max : $this->params->get('adv_price_high');
					echo ipropertyHTML::priceSelectList('price_max', array('class' => "ip_selector"), $preset, false, true); 
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="beds"><?php echo JText::_('COM_IPROPERTY_MIN_BEDS'); ?></label>
			<div class="controls">
				<select id="beds" name="beds" class="ip_selector" data-default="<?php echo $this->params->get('adv_beds_low') ?: '0'; ?>">
					<?php 
						$preset = ($saved_search && $saved_search->sliders->beds->min) ? $saved_search->sliders->beds->min : $this->params->get('adv_beds_low');
						for ($x=0; $x <= $this->settings->adv_beds_high; $x++) {
                            $selected = ($x == $preset) ? ' selected="selected"': '';
							echo '<option value="'.$x.'" '.$selected.'>'.$x.'</option>';
						} 						
					?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="baths"><?php echo JText::_('COM_IPROPERTY_MIN_BATHS'); ?></label>
			<div class="controls">
				<select id="baths" name="baths" class="ip_selector" data-default="<?php echo $this->params->get('adv_beds_high') ?: '0'; ?>">
					<?php 
						$preset = ($saved_search && $saved_search->sliders->baths->min) ? $saved_search->sliders->baths->min : $this->params->get('adv_beds_high');
						for ($x=0; $x <= $this->settings->adv_baths_high; $x++) {
                            $selected = ($x == $preset) ? ' selected="selected"': '';
							echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
						} 						
					?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="sqft"><?php echo JText::_('COM_IPROPERTY_MIN_SQFT'); ?></label>
			<div class="controls">
				<select id="sqft" name="sqft" class="ip_selector" data-default="<?php echo $this->params->get('adv_sqft_low') ?: '0'; ?>">
					<?php 
						$preset = ($saved_search && $saved_search->sliders->sqft->min) ? $saved_search->sliders->sqft->min : $this->params->get('adv_sqft_low');
						for ($x=0; $x <= $this->settings->adv_sqft_high; $x = $x + 1000) {
							$selected = ($x == $preset) ? ' selected="selected"': '';
							echo '<option value="'.$x.'"'.$selected.'>'.$x.'</option>';
						} 						
					?>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="cat"><?php echo JText::_('COM_IPROPERTY_CATEGORY'); ?></label>
			<div class="controls">
				<select id="cat" name="cat" class="ip_selector" data-default="<?php echo $this->params->get('default_cat') ?: ''; ?>">
					<option value=""><?php echo JText::_('JOPTIONS' ); ?></option>
					<?php 
						$preset = ($saved_search && !empty($saved_search->categories[0])) ? $saved_search->categories[0] : $this->params->get('default_cat');
						foreach ($this->cat_array as $key => $value) {
						$selected = ($key == $preset) ? ' selected="selected"': '';
						echo '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
					};
					?>
				</select>
			</div>
		</div>        
    </form>
	<div id="control-buttons">
		<button id="iproperty_locate" type="button" class="btn btn-default"><?php echo JText::_('COM_IPROPERTY_USE_LOCATION'); ?></button>
		<button id="iproperty_save" type="button" class="btn btn-info"><?php echo JText::_('COM_IPROPERTY_SAVESEARCH'); ?></button>
	</div>
    <div id="ip-map-information">
        <span id="ip-advsearch-results"><?php echo JText::_('COM_IPROPERTY_NO_RESULTS'); ?></span> | 
        <a href="javascript:Void(0);" id="ip-advsearch-clear"><?php echo JText::_('COM_IPROPERTY_CLEARSEARCH'); ?></a> | 
        <?php if($this->fullscreen): ?>
            <a href="<?php echo $this->return_page; ?>"><?php echo JText::_('COM_IPROPERTY_MINIMIZE'); ?></a>
        <?php else: ?>
            <a href="<?php echo JRoute::_('index.php?option=com_iproperty&tmpl=component&return='.$this->return); ?>"><?php echo JText::_('COM_IPROPERTY_FULL_SCREEN'); ?></a>
        <?php endif; ?>
    </div>
    </div>
</div>
