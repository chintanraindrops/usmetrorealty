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
?>
<script type="text/javascript">
Joomla.submitbutton = function(task) {
    if(document.formvalidator.isValid(document.id('adminForm'))){
        //console.log('success.');
    } else {
        //window.location.href+="#propgeneral";
        jQuery('#ip-propviewTabs li:first a').trigger('click');
    }
}

jQuery(document).ready(function(){

    jQuery('.checkint').on('blur', function(){
        //console.log('sdfsdfgdfgdf');
        checkInt(this.id, this.value);
    });
    
    function checkInt(fieldid, fieldval){
        //console.log(fieldid);
        if (fieldval.match(/[^\d\.]/g)) {
            //console.log('false');
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            //jQuery('#'+fieldid).after('<span class="span-warning">Only Interger is Valid</span>');
            jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');
        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }

    jQuery('#ip-propviewTabs li a').click(function(){
        alert('You will lose all data that you entered until you send them by clicking SEND button !!');
    });
});
</script>
<?php echo $this->loadTemplate('toolbar'); ?>
<h4 class="search-criteria-header"><?php echo $this->msg; ?></h4>
<form action="index.php?option=com_iproperty&view=searchcriteriaform" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="btn-toolbar" id="btns_bar">
            <div class="btn-group">

                <button type="submit" class="btn" value="save" name="save" onclick="Joomla.submitbutton('SearchcriteriaFrom.save')">
                    <i class="icon-ok"></i> Send <?php //echo JText::_('JSAVE') ?>
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
                        <?php echo $this->form->getLabel('title'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('title'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('description'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('description'); ?>
                    </div>
                </div>
                        
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('hometype'); ?>
                    </div>
                    <div class="controls">
                        <input type="hidden" name="jform[buyer_id]" value="<?php echo $this->ipauth->getAgentId(); ?>" />
                        <?php echo $this->form->getInput('hometype'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('minprice'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('minprice'); ?>
                        &nbsp;<?php echo "To"; ?>&nbsp;
                        <?php echo $this->form->getInput('maxprice'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('locstate'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('locstate'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('city'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('city'); ?>
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
                                <?php echo $this->form->getLabel('beds'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('beds'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('baths'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('baths'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sleeps'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sleeps'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('kitchen'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('kitchen'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('total_units'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('total_units'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sqft'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sqft'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lotsize'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lotsize'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_acres'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_acres'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_type'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('heat'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('heat'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('cool'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('cool'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('fuel'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('fuel'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_type'); ?>
                            </div>
                        </div> -->
                    </div>
                    <div class="span6 pull-right form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_size'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_size'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('siding'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('siding'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('roof'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('roof'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reception'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reception'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('tax'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('tax'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('income'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('income'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('yearbuilt'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('yearbuilt'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('zoning'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('zoning'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('propview'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('propview'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('school_district'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('school_district'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('style'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('style'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('shared_own'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('shared_own'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lease_hold'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lease_hold'); ?>
                            </div>
                        </div> -->
                        <?php /*if($this->settings->adv_show_wf): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('frontage'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('frontage'); ?>
                            </div>
                        </div>
                        <?php endif;*/ ?>
                        <?php if($this->settings->adv_show_reo): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reo'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reo'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($this->settings->adv_show_hoa): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('hoa'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('hoa'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>
        <?php
            echo JHtmlBootstrap::endTab();
            echo JHtmlBootstrap::addTab('ip-propview', 'propamens', JText::_('COM_IPROPERTY_AMENITIES'));
        ?>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('general_amen_header'); ?>
                    <?php echo $this->form->getInput('general_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('interior_amen_header'); ?>
                    <?php echo $this->form->getInput('interior_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('exterior_amen_header'); ?>
                    <?php echo $this->form->getInput('exterior_amens'); ?>
                </div>
            </div>
            <div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('accessibility_amen_header'); ?>
                        <?php echo $this->form->getInput('accessibility_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('green_amen_header'); ?>
                        <?php echo $this->form->getInput('green_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('security_amen_header'); ?>
                        <?php echo $this->form->getInput('security_amens'); ?>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('landscape_amen_header'); ?>
                        <?php echo $this->form->getInput('landscape_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('community_amen_header'); ?>
                        <?php echo $this->form->getInput('community_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('appliance_amen_header'); ?>
                        <?php echo $this->form->getInput('appliance_amens'); ?>
                    </div>
                </div>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propnotes', JText::_('COM_IPROPERTY_NOTES'));
        ?>
            <fieldset>
                <div class="row-fluid">
                    <div class="span12 pull-left">
                        <?php echo $this->form->getLabel('notes'); ?>
                        <?php echo $this->form->getInput('notes'); ?>
                    </div>
                </div>
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();
        ?>
            <input type="hidden" name="task" value="searchcriteriaform.save">

</form>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
        var div_top = jQuery('#ip-propviewContent').offset().top;
        //console.log('win'+window_top);
        //console.log('div'+div_top);
        if (window_top > div_top) {
            jQuery('#btns_bar').addClass('stickybar');
        } else {
            jQuery('#btns_bar').removeClass('stickybar');
        }
    });
});
</script>
<style>#jform_notes{height:auto;width:auto;}</style>