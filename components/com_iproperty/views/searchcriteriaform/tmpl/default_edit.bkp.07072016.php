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
$data=$this->result;
?>
<h1><?php echo $this->msg; ?></h1>


<form action="index.php?option=com_iproperty&view=searchcriteriaform" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="btn-toolbar">
            <div class="btn-group">

                <button type="submit" class="btn" value="update" name="update" onclick="Joomla.submitbutton('SearchcriteriaFrom.update')">
                    <i class="icon-ok"></i> Update
                </button>
                <button type="submit" class="btn" value="cancel" name="save" onclick="Joomla.submitbutton('propform.cancel')">
                    <i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
       </div>
        <?php 
        echo JHtmlBootstrap::startTabSet('ip-propview', array('active' => 'propgeneral'));
        echo JHtmlBootstrap::addTab('ip-propview', 'propgeneral', JText::_('COM_IPROPERTY_DESCRIPTION')); ?>
        <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                       <label>Home Type</label>
                    </div>
                    <div class="controls">
                     <select id="jform_hometype" name="jform[hometype]" class="inputbox required" size="1" required="" aria-required="true">
                        <option value="">Sale Type</option>
                        <option value="2">For Lease</option>
                        <option value="4">For Rent</option>
                        <option value="1">For Sale</option>
                        <option value="3">For Sale or Lease</option>
                        <option value="6">Pending</option>
                        <option value="5">Sold</option>
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
                        <?php //echo "<pre>"; print_r($this->State);?>
                        <select id="jform_hometype" name="jform[locstate]" class="inputbox required" size="1" required="" aria-required="true">
                                <option value="">Select State</option>
                                <?php foreach ($this->State as $value) { ?> 
                                   <option value="<?php echo $value->id;?>"><?php echo $value->title; ?></option>
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
                                    <option value="" selected="selected">Beds</option><option value="0">0</option><option value="1">1</option>
                                    <option value="2">2</option><option value="3">3</option><option value="4">4</option>
                                    <option value="5">5</option><option value="6">6</option><option value="7">7</option>
                                    <option value="8">8</option><option value="9">9</option><option value="10">10</option>
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
                                    <option value="0">0</option><option value="0.25">0.25</option><option value="0.5">0.5</option>
                                    <option value="0.75">0.75</option><option value="1">1</option><option value="1.25">1.25</option>
                                    <option value="1.5">1.5</option><option value="1.75">1.75</option><option value="2">2</option>
                                    <option value="2.25">2.25</option><option value="2.5">2.5</option><option value="2.75">2.75</option>
                                    <option value="3">3</option><option value="3.25">3.25</option><option value="3.5">3.5</option>
                                    <option value="3.75">3.75</option><option value="4">4</option><option value="4.25">4.25</option>
                                    <option value="4.5">4.5</option><option value="4.75">4.75</option><option value="5">5</option>
                                    <option value="5.25">5.25</option><option value="5.5">5.5</option><option value="5.75">5.75</option>
                                    <option value="6">6</option><option value="6.25">6.25</option><option value="6.5">6.5</option>
                                    <option value="6.75">6.75</option><option value="7">7</option><option value="7.25">7.25</option>
                                    <option value="7.5">7.5</option><option value="7.75">7.75</option><option value="8">8</option>
                                    <option value="8.25">8.25</option><option value="8.5">8.5</option><option value="8.75">8.75</option>
                                    <option value="9">9</option><option value="9.25">9.25</option><option value="9.5">9.5</option>
                                    <option value="9.75">9.75</option><option value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Total-Units</label>
                            </div>
                            <div class="controls">
                                <select id="jform_total_units" name="jform[total_units'" class="chzn-done">
                                    <option value="1" selected="selected">1</option><option value="2">2</option><option value="3">3</option>
                                    <option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option>
                                </select>
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
                                <label>Style</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[style]" class="inputbox" size="50" value="<?php echo $data->style?>">
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
            <input type="hidden" name="task" value="searchcriteriaform.update">
            <input type="hidden" name="id" value="<?php echo $data->id?>">

</form>