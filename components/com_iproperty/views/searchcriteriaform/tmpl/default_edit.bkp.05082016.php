<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
$data=$this->result;

//getamenities
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('amen_id');
$query->from($db->quoteName('#__iproperty_searchcritmid'));
$query->where($db->quoteName('criteria_id')." = ".JRequest::getInt('id'));
$db->setQuery($query);
$ame_results = $db->loadObjectList();
//var_dump($ame_results);
$selected_amenities = array();
foreach ($ame_results as $ame) {
    array_push($selected_amenities, $ame->amen_id);
}
//getamenities
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
    if(document.formvalidator.isValid(document.id('adminForm'))){
        console.log('success.');
    } else {
        //window.location.href+="#propgeneral";
        jQuery('#ip-propviewTabs li:first a').trigger('click');
    }
}
</script>
<h1><?php echo $this->msg; ?></h1>


<form action="index.php?option=com_iproperty&view=searchcriteriaform" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="btn-toolbar" id="btns_bar">
            <div class="btn-group">

                <button type="submit" class="btn" value="update" name="update" onclick="Joomla.submitbutton('SearchcriteriaFrom.update')">
                    <i class="icon-ok"></i> Send
                </button>
                <button type="button" class="btn" value="cancel" name="save" onclick="window.location.href='index.php?option=com_iproperty&view=manage&layout=searchcriterialist';">
                    <i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
       </div>
        <?php 
        echo JHtmlBootstrap::startTabSet('ip-propview', array('active' => 'propgeneral'));
        echo JHtmlBootstrap::addTab('ip-propview', 'propgeneral', JText::_('COM_IPROPERTY_DESCRIPTION')); ?>
        <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_BASIC_DETAILS'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <label class="required" for="jform_title" id="jform_title-lbl">Title<span class="star">&nbsp;*</span></label>
                    </div>
                    <div class="controls">
                        <input type="text" aria-required="true" required="" size="50" class="inputbox required" value="<?php echo $data->title; ?>" id="jform_title" name="jform[title]">
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label class="" for="jform_description" id="jform_description-lbl">DESC</label>
                    </div>
                    <div class="controls">
                        <textarea rows="50" cols="10" id="jform_description" name="jform[description]"><?php echo $data->description; ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                       <label>Home Type</label>
                    </div>
                    <div class="controls">
                     <input type="hidden" name="jform[buyer_id]" value="<?php echo $this->ipauth->getAgentId(); ?>" />
                     <?php //var_dump($data); ?>
                     <select id="jform_hometype" name="jform[hometype]" class="inputbox required" size="1" required="" aria-required="true">
                        <option value="">Sale Type</option>
                        <option <?php if($data->hometype == "2") { echo 'selected="selected"'; } ?>value="2">For Lease</option>
                        <option <?php if($data->hometype == "4") { echo 'selected="selected"'; } ?> value="4">For Rent</option>
                        <option <?php if($data->hometype == "1") { echo 'selected="selected"'; } ?> value="1">For Sale</option>
                        <option <?php if($data->hometype == "3") { echo 'selected="selected"'; } ?> value="3">For Sale or Lease</option>
                        <option <?php if($data->hometype == "6") { echo 'selected="selected"'; } ?> value="6">Pending</option>
                        <option <?php if($data->hometype == "5") { echo 'selected="selected"'; } ?> value="5">Sold</option>
                    </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label>Min-Price to Max-Price</label>
                    </div>
                    <div class="controls">
                         <input type="text" name="jform[minprice]" class="inputbox" required="required" size="50" value="<?php echo $data->minprice?>">
                        &nbsp;<?php echo "TO"; ?>&nbsp;
                         <input type="text" name="jform[maxprice]" required="required" class="inputbox" size="50" value="<?php echo $data->maxprice?>">
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label>City</label>
                    </div>
                    <div class="controls">
                        <input type="text" name="jform[city]" class="inputbox" required="required" size="50" value="<?php echo $data->city?>">
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                       <label>State</label>
                    </div>
                    <div class="controls">
                        <?php $locstates = explode(',', $data->locstate); /*echo "<pre>"; print_r($locstates); echo '</pre>'; */ ?>
                        <select aria-required="true" required="" multiple="" size="50" class="inputbox required" name="jform[locstate][]" id="jform_locstate">
                        <!--<select id="jform_locstate" name="jform[locstate][]" class="inputbox required" size="1" required="" aria-required="true" multiple="">-->
                                <option value="">Select State</option>
                                <?php foreach ($this->State as $value) { ?> 
                                   <option <?php if(in_array($value->id, $locstates)) { echo 'selected="selected"'; } ?> value="<?php echo $value->id;?>"><?php echo $value->title; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
            </fieldset>
            <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propdetails', JText::_('COM_IPROPERTY_DETAILS'));
        ?>
            <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                <div class="row-fluid">
                    <div class="span6 pull-left form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <label>Beds</label>
                            </div>
                            <div class="controls">
                                <select id="jform_beds" name="jform[beds]" class="chzn-done">
                                    <option value="" selected="selected">Beds</option>
                                    <option <?php if($data->beds == "0") { echo 'selected="selected"'; } ?> value="0">0</option>
                                    <option <?php if($data->beds == "1") { echo 'selected="selected"'; } ?> value="1">1</option>
                                    <option <?php if($data->beds == "2") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->beds == "3") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->beds == "4") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->beds == "5") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->beds == "6") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->beds == "7") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->beds == "8") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->beds == "9") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->beds == "10") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Baths</label>
                            </div>
                            <div class="controls">
                               <select id="jform_baths" name="jform[baths]" class="chzn-done">
                                    <option value="" selected="selected">Baths</option>
                                    <option <?php if($data->baths == "0.00") { echo 'selected="selected"'; } ?> value="0">0</option>
                                    <option <?php if($data->baths == "0.25") { echo 'selected="selected"'; } ?> value="0.25">0.25</option>
                                    <option <?php if($data->baths == "0.50") { echo 'selected="selected"'; } ?> value="0.5">0.5</option>
                                    <option <?php if($data->baths == "0.75") { echo 'selected="selected"'; } ?> value="0.75">0.75</option>
                                    <option <?php if($data->baths == "1.00") { echo 'selected="selected"'; } ?> value="1">1</option>
                                    <option <?php if($data->baths == "1.25") { echo 'selected="selected"'; } ?> value="1.25">1.25</option>
                                    <option <?php if($data->baths == "1.50") { echo 'selected="selected"'; } ?> value="1.5">1.5</option>
                                    <option <?php if($data->baths == "1.75") { echo 'selected="selected"'; } ?> value="1.75">1.75</option>
                                    <option <?php if($data->baths == "2.00") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->baths == "2.25") { echo 'selected="selected"'; } ?> value="2.25">2.25</option>
                                    <option <?php if($data->baths == "2.50") { echo 'selected="selected"'; } ?> value="2.5">2.5</option>
                                    <option <?php if($data->baths == "2.75") { echo 'selected="selected"'; } ?> value="2.75">2.75</option>
                                    <option <?php if($data->baths == "3.00") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->baths == "3.25") { echo 'selected="selected"'; } ?> value="3.25">3.25</option>
                                    <option <?php if($data->baths == "3.50") { echo 'selected="selected"'; } ?> value="3.5">3.5</option>
                                    <option <?php if($data->baths == "3.75") { echo 'selected="selected"'; } ?> value="3.75">3.75</option>
                                    <option <?php if($data->baths == "4.00") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->baths == "4.25") { echo 'selected="selected"'; } ?> value="4.25">4.25</option>
                                    <option <?php if($data->baths == "4.50") { echo 'selected="selected"'; } ?> value="4.5">4.5</option>
                                    <option <?php if($data->baths == "4.75") { echo 'selected="selected"'; } ?> value="4.75">4.75</option>
                                    <option <?php if($data->baths == "5.00") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->baths == "5.25") { echo 'selected="selected"'; } ?> value="5.25">5.25</option>
                                    <option <?php if($data->baths == "5.50") { echo 'selected="selected"'; } ?> value="5.5">5.5</option>
                                    <option <?php if($data->baths == "5.75") { echo 'selected="selected"'; } ?> value="5.75">5.75</option>
                                    <option <?php if($data->baths == "6.00") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->baths == "6.25") { echo 'selected="selected"'; } ?> value="6.25">6.25</option>
                                    <option <?php if($data->baths == "6.50") { echo 'selected="selected"'; } ?> value="6.5">6.5</option>
                                    <option <?php if($data->baths == "6.75") { echo 'selected="selected"'; } ?> value="6.75">6.75</option>
                                    <option <?php if($data->baths == "7.00") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->baths == "7.25") { echo 'selected="selected"'; } ?> value="7.25">7.25</option>
                                    <option <?php if($data->baths == "7.50") { echo 'selected="selected"'; } ?> value="7.5">7.5</option>
                                    <option <?php if($data->baths == "7.75") { echo 'selected="selected"'; } ?> value="7.75">7.75</option>
                                    <option <?php if($data->baths == "8.00") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->baths == "8.25") { echo 'selected="selected"'; } ?> value="8.25">8.25</option>
                                    <option <?php if($data->baths == "8.50") { echo 'selected="selected"'; } ?> value="8.5">8.5</option>
                                    <option <?php if($data->baths == "8.75") { echo 'selected="selected"'; } ?> value="8.75">8.75</option>
                                    <option <?php if($data->baths == "9.00") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->baths == "9.25") { echo 'selected="selected"'; } ?> value="9.25">9.25</option>
                                    <option <?php if($data->baths == "9.50") { echo 'selected="selected"'; } ?> value="9.5">9.5</option>
                                    <option <?php if($data->baths == "9.75") { echo 'selected="selected"'; } ?> value="9.75">9.75</option>
                                    <option <?php if($data->baths == "10.00") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Total-Units</label>
                            </div>
                            <div class="controls">
                                <select id="jform_total_units" name="jform[total_units'" class="chzn-done">
                                    <option value="1">1</option>
                                    <option <?php if($data->total_units == "2") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->total_units == "3") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->total_units == "4") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->total_units == "5") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->total_units == "6") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->total_units == "7") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->total_units == "8") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->total_units == "9") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->total_units == "10") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_sleeps" id="jform_sleeps-lbl">SLEEPS</label>
                            </div>
                            <div class="controls">
                                <input type="number" min="0" step="1" max="50" id="jform_sleeps" name="jform[sleeps]" value="<?php echo $data->sleeps; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_kitchen" id="jform_kitchen-lbl">KITCHEN</label>
                            </div>
                            <div class="controls">
                                <input type="number" min="0" step="1" max="50" id="jform_kitchen" name="jform[kitchen]" value="<?php echo $data->kitchen; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>FT<sup>2</sup></label>
                            </div>
                            <div class="controls">
                                <input type="text" name="jform[sqft]" class="inputbox" size="50" value="<?php echo $data->sqft?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Lotsize</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="jform[lotsize]" class="inputbox" size="50" value="<?php echo $data->lotsize?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Lot-Acres</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[lot_acres]" class="inputbox" size="50" value="<?php echo $data->lot_acres?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Lot-Type</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[lot_type]" class="inputbox" size="50" value="<?php echo $data->lot_type?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Heat</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[heat]" class="inputbox" size="50" value="<?php echo $data->heat?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Cool</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[cool]" class="inputbox" size="50" value="<?php echo $data->cool?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Fuel</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[fuel]" class="inputbox" size="50" value="<?php echo $data->fuel?>">
                                <?php //echo $this->form->getInput('fuel'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Garage-Type</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[garage_type]" class="inputbox" size="50" value="<?php echo $data->garage_type?>">
                            </div>
                        </div>
                    </div>
                   <div class="span6 pull-right form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <label>Garage-Size</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[garage_size]" class="inputbox" size="50" value="<?php echo $data->garage_size?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Siding</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[siding]" class="inputbox" size="50" value="<?php echo $data->siding?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Roof</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[roof]" class="inputbox" size="50" value="<?php echo $data->roof?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Reception</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[reception]" class="inputbox" size="50" value="<?php echo $data->reception?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Tax</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[tax]" class="inputbox" size="50" value="<?php echo $data->tax?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Income</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[income]" class="inputbox" size="50" value="<?php echo $data->income?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Yearbuilt</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[yearbuilt]" class="inputbox" size="50" value="<?php echo $data->yearbuilt?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Zoning</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[zoning]" class="inputbox" size="50" value="<?php echo $data->zoning?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Propview</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[propview]" class="inputbox" size="50" value="<?php echo $data->propview?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>School-district</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[school_district]" class="inputbox" size="50" value="<?php echo $data->school_district?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>House Style</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[style]" class="inputbox" size="50" value="<?php echo $data->style?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_shared_own" id="jform_shared_own-lbl">Shared Own</label>
                            </div>
                            <div class="controls">
                                <fieldset class="btn-group btn-group-yesno radio" id="jform_shared_own">
                                    <input type="radio" <?php if($data->shared_own == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[shared_own]" id="jform_shared_own0">
                                    <label for="jform_shared_own0">Yes</label>
                                    <input type="radio" <?php if($data->shared_own == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[shared_own]" id="jform_shared_own1">
                                    <label for="jform_shared_own1">No</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_lease_hold" id="jform_lease_hold-lbl">Lease Hold</label>
                            </div>
                            <div class="controls">
                                <fieldset class="btn-group btn-group-yesno radio" id="jform_lease_hold">
                                    <input type="radio" <?php if($data->lease_hold == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[lease_hold]" id="jform_lease_hold0">
                                    <label for="jform_lease_hold0">Yes</label>
                                    <input type="radio" <?php if($data->lease_hold == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[lease_hold]" id="jform_lease_hold1">
                                    <label for="jform_lease_hold1">No</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Frontage</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[frontage]" class="inputbox" size="50" value="<?php echo $data->frontage?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Reo</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[reo]" class="inputbox" size="50" value="<?php echo $data->reo?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>hoa</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[hoa]" class="inputbox" size="50" value="<?php echo $data->hoa?>">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php
            echo JHtmlBootstrap::endTab();
            echo JHtmlBootstrap::addTab('ip-propview', 'propamens', JText::_('COM_IPROPERTY_AMENITIES'));
            ?>
            
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-success">
                            <h5 id="jform_general_amen_header-lbl" class="visible-first visible">General Amenities</h5>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_general_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_general_amens0" name="jform[general_amens][]" <?php if(in_array("14", $selected_amenities)){ echo 'checked="checked"'; } ?> value="14" class="inputbox" type="checkbox"> Cable Internet
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens1" name="jform[general_amens][]" <?php if(in_array("13", $selected_amenities)){ echo 'checked="checked"'; } ?> value="13" class="inputbox" type="checkbox"> Cable TV
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="80" name="jform[general_amens][]" <?php if(in_array("80", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_general_amens2"> Club House
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens3" name="jform[general_amens][]" <?php if(in_array("39", $selected_amenities)){ echo 'checked="checked"'; } ?> value="39" class="inputbox" type="checkbox"> Electric Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens4" name="jform[general_amens][]" <?php if(in_array("29", $selected_amenities)){ echo 'checked="checked"'; } ?> value="29" class="inputbox" type="checkbox"> Satellite Dish
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens5" name="jform[general_amens][]" <?php if(in_array("17", $selected_amenities)){ echo 'checked="checked"'; } ?> value="17" class="inputbox" type="checkbox"> Skylights
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens6" name="jform[general_amens][]" <?php if(in_array("43", $selected_amenities)){ echo 'checked="checked"'; } ?> value="43" class="inputbox" type="checkbox"> Water Softener
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-info">
                            <h4 id="jform_interior_amen_header-lbl" class="visible-first visible">Interior Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_interior_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_interior_amens0" name="jform[interior_amens][]" <?php if(in_array("9", $selected_amenities)){ echo 'checked="checked"'; } ?> value="9" class="inputbox" type="checkbox"> Carpet Throughout
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens1" name="jform[interior_amens][]" <?php if(in_array("8", $selected_amenities)){ echo 'checked="checked"'; } ?> value="8" class="inputbox" type="checkbox"> Central Air
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens2" name="jform[interior_amens][]" <?php if(in_array("21", $selected_amenities)){ echo 'checked="checked"'; } ?> value="21" class="inputbox" type="checkbox"> Central Vac
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="85" name="jform[interior_amens][]" <?php if(in_array("85", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_interior_amens3"> Furnished
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens4" name="jform[interior_amens][]" <?php if(in_array("16", $selected_amenities)){ echo 'checked="checked"'; } ?> value="16" class="inputbox" type="checkbox"> Jacuzi Tub
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-error">
                            <h4 id="jform_exterior_amen_header-lbl" class="visible-first visible">Exterior Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_exterior_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_exterior_amens0" name="jform[exterior_amens][]" <?php if(in_array("32", $selected_amenities)){ echo 'checked="checked"'; } ?> value="32" class="inputbox" type="checkbox"> Boat Slip
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens1" name="jform[exterior_amens][]" <?php if(in_array("31", $selected_amenities)){ echo 'checked="checked"'; } ?> value="31" class="inputbox" type="checkbox"> Covered Patio
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens2" name="jform[exterior_amens][]" <?php if(in_array("33", $selected_amenities)){ echo 'checked="checked"'; } ?> value="33" class="inputbox" type="checkbox"> Exterior Lighting
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens3" name="jform[exterior_amens][]" <?php if(in_array("25", $selected_amenities)){ echo 'checked="checked"'; } ?> value="25" class="inputbox" type="checkbox"> Fence
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens4" name="jform[exterior_amens][]" <?php if(in_array("28", $selected_amenities)){ echo 'checked="checked"'; } ?> value="28" class="inputbox" type="checkbox"> Fruit Trees
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens5" name="jform[exterior_amens][]" <?php if(in_array("4", $selected_amenities)){ echo 'checked="checked"'; } ?> value="4" class="inputbox" type="checkbox"> Garage
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens6" name="jform[exterior_amens][]" <?php if(in_array("35", $selected_amenities)){ echo 'checked="checked"'; } ?> value="35" class="inputbox" type="checkbox"> Gazebo
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens7" name="jform[exterior_amens][]" <?php if(in_array("24", $selected_amenities)){ echo 'checked="checked"'; } ?> value="24" class="inputbox" type="checkbox"> Open Deck
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens8" name="jform[exterior_amens][]" <?php if(in_array("27", $selected_amenities)){ echo 'checked="checked"'; } ?> value="27" class="inputbox" type="checkbox"> Pasture
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens9" name="jform[exterior_amens][]" <?php if(in_array("26", $selected_amenities)){ echo 'checked="checked"'; } ?> value="26" class="inputbox" type="checkbox"> RV Parking
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens10" name="jform[exterior_amens][]" <?php if(in_array("34", $selected_amenities)){ echo 'checked="checked"'; } ?> value="34" class="inputbox" type="checkbox"> Spa/Hot Tub
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-success">
                            <h4 id="jform_accessibility_amen_header-lbl" class="visible-first">Accessibility Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_accessibility_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_accessibility_amens0" name="jform[accessibility_amens][]" <?php if(in_array("19", $selected_amenities)){ echo 'checked="checked"'; } ?> value="19" class="inputbox" type="checkbox"> Han
                                dicap Facilities
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="86" name="jform[accessibility_amens][]" <?php if(in_array("86", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_accessibility_amens1"> Pets Allowed
                            </label>
                            <label class="checkbox">
                                <input id="jform_accessibility_amens2" name="jform[accessibility_amens][]" <?php if(in_array("50", $selected_amenities)){ echo 'checked="checked"'; } ?> value="50" class="inputbox" type="checkbox"> Wheelchair Ramp
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-info">
                            <h4 id="jform_green_amen_header-lbl" class="visible-first">Energy Savings Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_green_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_green_amens0" name="jform[green_amens][]" <?php if(in_array("5", $selected_amenities)){ echo 'checked="checked"'; } ?> value="5" class="inputbox" type="checkbox"> Fireplace
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens1" name="jform[green_amens][]" <?php if(in_array("11", $selected_amenities)){ echo 'checked="checked"'; } ?> value="11" class="inputbox" type="checkbox"> Gas Fireplace
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens2" name="jform[green_amens][]" <?php if(in_array("45", $selected_amenities)){ echo 'checked="checked"'; } ?> value="45" class="inputbox" type="checkbox"> Gas Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens3" name="jform[green_amens][]" <?php if(in_array("12", $selected_amenities)){ echo 'checked="checked"'; } ?> value="12" class="inputbox" type="checkbox"> Gas Stove
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens4" name="jform[green_amens][]" <?php if(in_array("20", $selected_amenities)){ echo 'checked="checked"'; } ?> value="20" class="inputbox" type="checkbox"> Pellet Stove
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens5" name="jform[green_amens][]" <?php if(in_array("46", $selected_amenities)){ echo 'checked="checked"'; } ?> value="46" class="inputbox" type="checkbox"> Propane Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens6" name="jform[green_amens][]" <?php if(in_array("15", $selected_amenities)){ echo 'checked="checked"'; } ?> value="15" class="inputbox" type="checkbox"> Wood Stove
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-error">
                            <h4 id="jform_security_amen_header-lbl" class="visible-first">Security Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_security_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_security_amens0" name="jform[security_amens][]" <?php if(in_array("18", $selected_amenities)){ echo 'checked="checked"'; } ?> value="18" class="inputbox" type="checkbox"> Burglar Alarm
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="81" name="jform[security_amens][]" <?php if(in_array("81", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_security_amens1"> Concierge
                            </label>
                            <label class="checkbox">
                                <input id="jform_security_amens2" name="jform[security_amens][]" <?php if(in_array("30", $selected_amenities)){ echo 'checked="checked"'; } ?> value="30" class="inputbox" type="checkbox"> Sprinkler System
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-success">
                            <h4 id="jform_landscape_amen_header-lbl" class="visible-first">Landscape Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_landscape_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_landscape_amens0" name="jform[landscape_amens][]" <?php if(in_array("23", $selected_amenities)){ echo 'checked="checked"'; } ?> value="23" class="inputbox" type="checkbox"> Landscaping
                            </label>
                            <label class="checkbox">
                                <input id="jform_landscape_amens1" name="jform[landscape_amens][]" <?php if(in_array("22", $selected_amenities)){ echo 'checked="checked"'; } ?> value="22" class="inputbox" type="checkbox"> Lawn
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-info">
                            <h4 id="jform_community_amen_header-lbl" class="visible-first">Community Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_community_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_community_amens0" name="jform[community_amens][]" <?php if(in_array("3", $selected_amenities)){ echo 'checked="checked"'; } ?> value="3" class="inputbox" type="checkbox"> Swimming Pool
                            </label>
                            <label class="checkbox">
                                <input id="jform_community_amens1" name="jform[community_amens][]" <?php if(in_array("1", $selected_amenities)){ echo 'checked="checked"'; } ?> value="1" class="inputbox" type="checkbox"> Tennis Court
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-error">
                            <h4 id="jform_appliance_amen_header-lbl" class="visible-first">Appliance Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_appliance_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="82" name="jform[appliance_amens][]" <?php if(in_array("82", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens0"> Daily Maid Service
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens0" name="jform[appliance_amens][]" <?php if(in_array("6", $selected_amenities)){ echo 'checked="checked"'; } ?> value="6" class="inputbox" type="checkbox"> Dishwasher
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="83" name="jform[appliance_amens][]" <?php if(in_array("83", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens2"> Double glazing
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens1" name="jform[appliance_amens][]" <?php if(in_array("42", $selected_amenities)){ echo 'checked="checked"'; } ?> value="42" class="inputbox" type="checkbox"> Dryer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens2" name="jform[appliance_amens][]" <?php if(in_array("49", $selected_amenities)){ echo 'checked="checked"'; } ?> value="49" class="inputbox" type="checkbox"> Freezer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens3" name="jform[appliance_amens][]" <?php if(in_array("7", $selected_amenities)){ echo 'checked="checked"'; } ?> value="7" class="inputbox" type="checkbox"> Garbage Disposal
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens4" name="jform[appliance_amens][]" <?php if(in_array("47", $selected_amenities)){ echo 'checked="checked"'; } ?> value="47" class="inputbox" type="checkbox"> Grill Top
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="84" name="jform[appliance_amens][]" <?php if(in_array("84", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens7"> Heating
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens5" name="jform[appliance_amens][]" <?php if(in_array("40", $selected_amenities)){ echo 'checked="checked"'; } ?> value="40" class="inputbox" type="checkbox"> Microwave
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens6" name="jform[appliance_amens][]" <?php if(in_array("36", $selected_amenities)){ echo 'checked="checked"'; } ?> value="36" class="inputbox" type="checkbox"> Range/Oven
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens7" name="jform[appliance_amens][]" <?php if(in_array("37", $selected_amenities)){ echo 'checked="checked"'; } ?> value="37" class="inputbox" type="checkbox"> Refrigerator
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens8" name="jform[appliance_amens][]" <?php if(in_array("44", $selected_amenities)){ echo 'checked="checked"'; } ?> value="44" class="inputbox" type="checkbox"> RO Combo Gas/Electric
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens9" name="jform[appliance_amens][]" <?php if(in_array("48", $selected_amenities)){ echo 'checked="checked"'; } ?> value="48" class="inputbox" type="checkbox"> Trash Compactor
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens10" name="jform[appliance_amens][]" <?php if(in_array("41", $selected_amenities)){ echo 'checked="checked"'; } ?> value="41" class="inputbox" type="checkbox"> Washer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens11" name="jform[appliance_amens][]" <?php if(in_array("10", $selected_amenities)){ echo 'checked="checked"'; } ?> value="10" class="inputbox" type="checkbox"> Washer/Dryer
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <?php
            echo JHtmlBootstrap::endTab();
            ?>
            <input type="hidden" name="task" value="searchcriteriaform.update">
            <input type="hidden" name="id" value="<?php echo $data->id?>">

</form>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
        var div_top = jQuery('#ip-propviewContent').offset().top;
        console.log('win'+window_top);
        console.log('div'+div_top);
        if (window_top > div_top) {
            jQuery('#btns_bar').addClass('stickybar');
        } else {
            jQuery('#btns_bar').removeClass('stickybar');
        }
    });
});
</script>