<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
//echo "<pre>"; print_r($this->result); exit;
defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.modal');
$data=$this->result;
//echo JPATH_COMPONENT_SITE; exit;
$document = JFactory::getDocument();
$document->addStyleSheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
$document->addScript('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
    $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/bootbox.min.js');
/*$document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/ipsortables.js');
$document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/ipsortables_docs.js');*/
echo $this->loadTemplate('toolbar');
$language = JFactory::getLanguage();
$language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
     //echo "<pre>"; print_r($this->settings); 
$app        = JFactory::getApplication();
$settings   = ipropertyAdmin::config();
//echo "<pre>"; print_r($this->item->id); exit;
$user = JFactory::getUser();
$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__iproperty_agents'));
        $query->where($db->quoteName('user_id')." = ".$db->quote($user->id));
        $db->setQuery($query);
        $res = $db->loadObject();
        //echo "<pre>"; print_r($res); exit;
$transaction_id= JRequest::getvar('id');
$map_script = "    
        var ipGalleryOptions = {
            transaction_id: ".$transaction_id.",
            iptoken: '".JSession::getFormToken()."',
            ipbaseurl: '".JURI::root()."',
            ipthumbwidth: '".$settings->thumbwidth."',
            iplimitstart: 0,
            iplimit: 50,
            ipmaximagesize: '".$settings->maximgsize."',
            ipfilemaxupload: 0,
            pluploadpath: '".JURI::root()."components/com_iproperty/assets/js',
            debug: false,
            language: {
                save: '".addslashes(JText::_('COM_IPROPERTY_SAVE'))."',
                del: '".addslashes(JText::_('COM_IPROPERTY_DELETE'))."',
                edit: '".addslashes(JText::_('COM_IPROPERTY_EDIT'))."',
                add: '".addslashes(JText::_('COM_IPROPERTY_ADD'))."',
                confirm: '".addslashes(JText::_('COM_IPROPERTY_CONFIRM'))."',
                ok: '".addslashes(JText::_('JYES'))."',
                cancel: '".addslashes(JText::_('JCANCEL'))."',
                iptitletext: '".addslashes(JText::_('COM_IPROPERTY_TITLE'))."',
                ipdesctext: '".addslashes(JText::_('COM_IPROPERTY_DESCRIPTION'))."',
                noresults: '".addslashes(JText::_('COM_IPROPERTY_NO_RESULTS'))."',
                updated: '".addslashes(JText::_('COM_IPROPERTY_UPDATED'))."',
                notupdated: '".addslashes(JText::_('COM_IPROPERTY_NOT_UPDATED'))."',
                previous: '".addslashes(JText::_('COM_IPROPERTY_PREVIOUS'))."',
                next: '".addslashes(JText::_('COM_IPROPERTY_NEXT'))."',
                of: '".addslashes(JText::_('COM_IPROPERTY_OF'))."',
                fname: '".addslashes(JText::_('COM_IPROPERTY_FNAME'))."',
                overlimit: '".addslashes(JText::_('COM_IPROPERTY_OVERIMGLIMIT'))."',
                warning: '".addslashes(JText::_('COM_IPROPERTY_WARNING'))."',
                uploadcomplete: '".addslashes(JText::_('COM_IPROPERTY_UPLOAD_COMPLETE'))."'
            },
            client: '".$app->getName()."',
            allowedFileTypes: [{title : 'Files', extensions : 'jpg,gif,png,pdf,doc,txt,jpeg,mp4'}]
        };";
    $document->addScriptDeclaration($map_script);
    $document->addScript( JURI::root(true).'/components/com_iproperty/assets/js/manage_tabs.js');
?>
<h1><?php echo "Edit Transaction"; ?></h1>
<script>
//modalToggle
jQuery(".modalToggle").click(function(){
    jQuery('#modal').removeClass('hide');
    });



jQuery( document ).ready(function() { 
    jQuery('.nav-tabs li').click(function(){
        jQuery('.icon-pencil').click(function(){
                   // jQuery('#custom_modal_backdrop').remove();
                    jQuery('div#ip_image_form').removeClass('hide');
        });
        jQuery('#addAvailable').click(function(){
                 jQuery('#ip_avail_modal').removeClass('hide');
                 /*jQuery('#addAvailable').css({'style':'marjin-left':''});*/
                jQuery('#ip_avail_modal').css('margin-left', '');
                jQuery('#ip_avail_modal').css('margin-left', '25%');
               setTimeout(function(){ jQuery('#ip_avail_modal .modal-backdrop').remove();}, 100);
            });
        jQuery('#addAvailableDocs').click(function(){
                 jQuery('#ip_availdocs_modal').removeClass('hide');
                 /*jQuery('#addAvailable').css({'style':'marjin-left':''});*/
                jQuery('#ip_availdocs_modal').css('margin-left', '');
                jQuery('#ip_availdocs_modal').css('margin-left', '25%');
               setTimeout(function(){ jQuery('#ip_availdocs_modal .modal-backdrop').remove();}, 100);
            });
        jQuery('.icon-minus').click(function(){
            setTimeout(function(){
             jQuery('.modal-backdrop').remove();
                jQuery( ".bootbox" ).after( "<div class='modal-backdrop fade in'></div>" );
                jQuery( ".modal-footer .btn-primary" ).addClass("remove_modal_backdrop_custom" );
                jQuery( ".null" ).addClass("remove_modal_backdrop_custom" );
                 jQuery('.modal-footer .remove_modal_backdrop_custom').click(function(){
                   // jQuery('#custom_modal_backdrop').remove();
                    jQuery('.modal-backdrop').remove();
                });
            }, 100);
           
     });
   }); 
});
//$("#page_navigation1").attr("id","page_navigation1");

//  <script type='text/javascript'>jQuery( document ).ready(function() { jQuery('.remove_modal_backdrop').click(function(){ console.log('call'); alert('call'); }); });<\/script>
</script>
<style type="text/css">
        .nav-tabs a, .nav-tabs a:hover, .nav-tabs a:focus
        {
            outline: 0;
        }

    </style>
        <form action="index.php?option=com_transaction&view=transaction" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('transaction.save')">Save</button>
                    <a href="index.php?option=com_transaction&view=transactionlist" class="btn btn-primary">Cancel</a>
                </div>
            </div>
    <div class="panel panel-default" style="width:100%; padding: 10px; margin: 10px">
        <div id="Tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#information" aria-controls="personal" role="tab" data-toggle="tab">
                    Property Information </a></li>
                <li><a href="#propertyaddress" aria-controls="employment" role="tab" data-toggle="tab">Property Address</a></li>
                <li><a href="#buyersinformation" aria-controls="employment" role="tab" data-toggle="tab">Buyers Information</a></li>
                 <li><a href="#sellersinformation" aria-controls="employment" role="tab" data-toggle="tab">Sellers Information</a></li>
                 <li><a href="#titleinformation" aria-controls="employment" role="tab" data-toggle="tab">Title Information</a></li>
                 <li><a href="#miscinformation" aria-controls="employment" role="tab" data-toggle="tab">Misc Information</a></li>
                 <li><a href="#upload_files" aria-controls="employment" role="tab" data-toggle="tab">Upload</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content" style="padding-top: 20px">
                <div role="tabpanel" class="tab-pane active" id="information">
                    <div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('MLS'); ?></div>
						<input type="text" aria-required="false" required="" size="50" class="inputbox" value="<?php echo $data->MLS; ?>" id="jform_MLS" name="jform[MLS]" readonly>
					</div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('transaction_type'); ?></div>
                        <select id="jform_transaction_type" name="jform[transaction_type]" class="inputbox" size="1" required="" aria-required="true" readonly>
                        <option value="">Sale Type</option>
                        <option <?php if($data->transaction_type == "Sale Transaction") { echo 'selected="selected"'; } ?>value="Sale Transaction">Sale Transaction</option>
                        <option <?php if($data->transaction_type == "Listing Transaction") { echo 'selected="selected"'; } ?> value="Listing Transaction">Listing Transaction</option>
                        <option <?php if($data->transaction_type == "Other") { echo 'selected="selected"'; } ?> value="Other">Other</option>
                    </select>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('listing_price'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->listing_price; ?>" id="jform_listing_price" name="jform[listing_price]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('listing_date'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->listing_date; ?>" id="jform_listing_date" name="jform[listing_date]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sold_price'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->sold_price; ?>" id="jform_sold_price" name="jform[sold_price]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sold_date'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->sold_date; ?>" id="jform_sold_date" name="jform[sold_date]" readonly>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="propertyaddress">
                   <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->address; ?>" id="jform_address" name="jform[address]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                       <select id="jform_transaction_type" name="jform[transaction_type]" class="inputbox" size="1" required="" aria-required="true" readonly>
                        <option value="">State</option>
                        <option <?php if($data->state == "AZ") { echo 'selected="selected"'; } ?>value="AZ">Arizona</option>
                        <option <?php if($data->state == "OR") { echo 'selected="selected"'; } ?> value="OR">Oregon</option>
                        <option <?php if($data->state == "WA") { echo 'selected="selected"'; } ?> value="WA">Washington</option>
                    </select>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('city'); ?></div>
                         <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->city; ?>" id="jform_city" name="jform[city]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('zip'); ?></div>
                         <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->zip; ?>" id="jform_zip" name="jform[zip]" readonly>
                    </div>
                </div>
                 <div role="tabpanel" class="tab-pane" id="buyersinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer1Name'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyer1Name; ?>" id="jform_buyer1Name" name="jform[buyer1Name]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer2Name'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyer2Name; ?>" id="jform_buyer2Name" name="jform[buyer2Name]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyersfulladdress'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyersfulladdress; ?>" id="jform_buyersfulladdress" name="jform[buyersfulladdress]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer_phone'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyer_phone; ?>" id="jform_buyer_phone" name="jform[buyer_phone]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_Agent'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyers_Agent; ?>" id="jform_buyers_Agent" name="jform[buyers_Agent]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_agent_email'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyers_agent_email; ?>" id="jform_buyers_agent_email" name="jform[buyers_agent_email]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_agent_phone'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->buyers_agent_phone; ?>" id="jform_buyers_agent_phone" name="jform[buyers_agent_phone]" readonly>
                    </div>
                </div>
               <div role="tabpanel" class="tab-pane" id="sellersinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller1Name'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller1Name; ?>" id="jform_seller1Name" name="jform[seller1Name]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller2Name'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller2Name; ?>" id="jform_seller2Name" name="jform[seller2Name]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sellersfulladdress'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->sellersfulladdress; ?>" id="jform_sellersfulladdress" name="jform[sellersfulladdress]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_phone'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller_phone; ?>" id="jform_seller_phone" name="jform[seller_phone]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_Agent'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller_Agent; ?>" id="jform_seller_Agent" name="jform[seller_Agent]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_agent_email'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller_agent_email; ?>" id="jform_seller_agent_email" name="jform[seller_agent_email]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_agent_phone'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->seller_agent_phone; ?>" id="jform_seller_agent_phone" name="jform[seller_agent_phone]" readonly>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="titleinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('closing_title_escrow'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->closing_title_escrow; ?>" id="jform_closing_title_escrow" name="jform[closing_title_escrow]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('escrow_tran'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->escrow_tran; ?>" id="jform_escrow_tran" name="jform[escrow_tran]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_full_ddress'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->title_full_ddress; ?>" id="jform_title_full_ddress" name="jform[title_full_ddress]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_phone'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->title_phone; ?>" id="jform_title_phone" name="jform[title_phone]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_agent'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->title_agent; ?>" id="jform_title_agent" name="jform[title_agent]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_email_address'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->title_email_address; ?>" id="jform_title_email_address" name="jform[title_email_address]" readonly>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="miscinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('commission_amount'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->commission_amount; ?>" id="jform_commission_amount" name="jform[commission_amount]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('commission_type'); ?></div>
                        <select id="jform_commission_type" name="jform[commission_type]" class="inputbox" size="1" required="false" aria-required="true" readonly>
                        <option value="">commission_type</option>
                        <option <?php if($data->commission_type == "Percentage") { echo 'selected="selected"'; } ?>value="Percentage">Percentage</option>
                        <option <?php if($data->commission_type == "Fixed") { echo 'selected="selected"'; } ?> value="Fixed">Fixed</option>
                        <option <?php if($data->commission_type == "Referral Fee") { echo 'selected="selected"'; } ?> value="Referral Fee">Referral Fee</option>
                        <option <?php if($data->commission_type == "Other") { echo 'selected="selected"'; } ?> value="Other">Other</option>
                    </select>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('earnest_money_amount'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->earnest_money_amount; ?>" id="jform_earnest_money_amount" name="jform[earnest_money_amount]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('earnest_money_held_by'); ?></div>
                        <select id="jform_commission_type" name="jform[commission_type]" class="inputbox" size="1" required="false" aria-required="true" readonly>
                        <option value="">Select</option>
                        <option <?php if($data->earnest_money_held_by == "Title") { echo 'selected="selected"'; } ?>value="Title">Title</option>
                        <option <?php if($data->earnest_money_held_by == "Listing Co.") { echo 'selected="selected"'; } ?> value="Listing Co.">Listing Co.</option>
                        <option <?php if($data->earnest_money_held_by == "Selling Co.") { echo 'selected="selected"'; } ?> value="Selling Co.">Selling Co.</option>
                    </select>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('home_warranty_provided'); ?></div>
                        <select id="jform_home_warranty_provided" name="jform[home_warranty_provided]" class="inputbox" size="1" required="false" aria-required="true" readonly>
                        <option value="">Select</option>
                        <option <?php if($data->home_warranty_provided == "Buyer Agent") { echo 'selected="selected"'; } ?>value="Buyer Agent">Buyer Agent</option>
                        <option <?php if($data->home_warranty_provided == "Seller Agent") { echo 'selected="selected"'; } ?> value="Seller Agent">Seller Agent</option>
                        <option <?php if($data->home_warranty_provided == "Buyer") { echo 'selected="selected"'; } ?> value="Buyer">Buyer</option>
                         <option <?php if($data->home_warranty_provided == "Seller") { echo 'selected="selected"'; } ?> value="Seller">Seller</option>
                          <option <?php if($data->home_warranty_provided == "None") { echo 'selected="selected"'; } ?> value="None">None</option>
                           <option <?php if($data->home_warranty_provided == "Other") { echo 'selected="selected"'; } ?> value="Other">Other</option>
                    </select>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('notes_for_broker_instructions'); ?></div>
                        <input type="text" aria-required="false" required="false" size="50" class="inputbox" value="<?php echo $data->notes_for_broker_instructions; ?>" id="jform_notes_for_broker_instructions" name="jform[notes_for_broker_instructions]" readonly>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('agent_notes_for_transaction_Office'); ?></div>
                        <input type="text" aria-required="true" required="false" size="50" class="inputbox" value="<?php echo $data->agent_notes_for_transaction_Office; ?>" id="jform_agent_notes_for_transaction_Office" name="jform[agent_notes_for_transaction_Office]" readonly>
                    </div>
                     <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('Office_notes'); ?></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="upload_files">
                    <div class="row-fluid">
                <ul class="nav nav-tabs ip-vid-tab">
                    <li class="active"><a href="#imgtab" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_IMAGES_AND_DOCS_AND_VIDEO');?></a></li>
                    <li><a href="#vidtab" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_EMBED_VIDEO');?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="imgtab">
                        <div class="clearfix"></div>                        
                        <?php
                            $id = JRequest::getvar('id');
                         if($id): ?>
                            <?php echo $this->form->getInput('gallery'); ?>
                        <?php else: ?>
                            <div class="alert alert-info"><?php echo JText::_('COM_TRANSACTION_SAVE_BEFORE_IMAGES'); ?></div>
                        <?php endif; ?> 
                    </div>
                    <div class="tab-pane" id="vidtab">
                    
                        <div class="control-group form-vertical">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('video'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('video'); ?>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>

                   <!--  <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('upload_files'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('upload_files'); ?>
                        <button type="button" class="btn btn-primary" id="upload_file">Save</button> </div>
                    </div> -->
            </div> 
        </div>
    </div>
    <input type="hidden" name="task" value="transaction.save">
    </form>
<?php //index.php?option=com_register&controller=register&task=save 
$document->addStyleSheet( "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" );
    $document->addStyleSheet( JURI::root(true)."/components/com_transaction/assets/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" );
    $document->addScript( "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" );
    $document->addScript( JURI::root(true)."/components/com_transaction/assets/js/plupload/js/plupload.full.min.js" );
    $document->addScript( JURI::root(true)."/components/com_transaction/assets/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js" );

    // include language file for uploader if it exists
    if(JFile::exists(JPATH_SITE.'/components/com_transaction/assets/js/plupload/js/i18n/'.$languageCode.'.js')){
        $document->addScript( JURI::root(true)."/components/com_transaction/assets/js/plupload/js/i18n/".$languageCode.".js" );
    }
    //sort table
    $document->addScript( JURI::root(true).'/components/com_transaction/assets/js/ipsortables.js');
    $document->addScript( JURI::root(true).'/components/com_transaction/assets/js/ipsortables_docs.js');

?>




