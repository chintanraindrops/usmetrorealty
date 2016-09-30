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
JHtml::_('bootstrap.modal');
JHtml::_('behavior.formvalidation');

//echo JPATH_COMPONENT_SITE; exit;
$document = JFactory::getDocument();
$document->addStyleSheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
$document->addScript('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
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
//echo $user->id; exit;
$map_script = "    
        var ipGalleryOptions = {
            agent_id: ".$res->id.",
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
<script type="text/javascript">

    var pluploadpath    = '<?php echo JURI::root().'/components/com_iproperty/assets/js'; ?>';
    Joomla.submitbutton = function(task) {
        if(document.formvalidator.isValid(document.id('adminForm'))) {
            Joomla.submitform(task);
        } else {
            var fields, invalid = [], valid = true, label, error, i, l;
            fields = jQuery('form.form-validate').find('input, textarea, select');
            //console.log(fields);
            if (!document.formvalidator.isValid(jQuery('form'))) {
                for (i = 0, l = fields.length; i < l; i++) {
                    if (document.formvalidator.validate(fields[i]) === false) {
                        valid = false;
                        invalid.push(fields[i]);
                    }
                }

            // Run custom form validators if present
            jQuery.each(document.formvalidator.custom, function (key, validator) {
                if (validator.exec() !== true) {
                    valid = false;
                }
            });

            if (!valid && invalid.length > 0) {
                error = {"error": []};

                for (i = invalid.length - 1; i >= 0; i--) {
                    //console.log(i);
                    // console.log(invalid[i]);
                    //label = jQuery.trim($(invalid[i]).data("id").text().replace("*", "").toString());
                    var inputId = jQuery(invalid[i]).attr("id");
                    console.log(inputId);
                    if (inputId) {
                        if(inputId === 'jform_MLS') {                            
                            error.error.push('Please Enter MLS#');
                        }if(inputId === 'jform_transaction_type') {
                            error.error.push('Please Select Transaction Type');
                        }if(inputId === 'jform_listing_price') {                            
                            error.error.push('Please Enter Listing Price');
                        }if(inputId === 'jform_listing_date') {
                            error.error.push('Please Enter Listing Date');
                        }if(inputId === 'jform_state') {
                            error.error.push('Please Enter Select State');
                        }
                    }
                }
            }
            Joomla.renderMessages(error);
        }
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
    jQuery(document).ready(function(){
      checkTransactionMLS = function(){
        document.id('system-message-container').set('tween');
        var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_transaction&task=ajax.checkTransactionMLS';
        var transactionMLS = document.id('jform_MLS').value;
        req = new Request({
            method: 'post',
            url: checkurl,
            data: { 'MLS': transactionMLS,
                    'format': 'raw'},
            dataType: 'JSON',
            onRequest: function() {
                document.id('mls_error').set('html', '');
            },
            onSuccess: function(response) {
                //alert(response);
                      var obj = jQuery.parseJSON(response);
                if(obj){
                    //console.log(response);
                    document.id('mls_error').highlight('#ff0000');
                    //document.id('jform_MLS').value = '';
                    document.id('jform_MLS').set('class', 'inputbox invalid');
                    document.id('jform_MLS').focus();
                    /*document.id('mls_error').set('html', '<div class="ip_warning" style="color:red;"><?php echo JText::_('A Transaction with this MLS already exists....'); ?></div>');*/

                    //when mls is exists then all data get this mls 
                    jQuery('#jform_listing_price').val(obj.listing_price);
                    jQuery('#jform_transaction_type').val(obj.transaction_type);
                    jQuery("#jform_transaction_type").attr("selected","selected");
                    jQuery('#jform_transaction_type').removeClass('required');
                    jQuery('#jform_transaction_type').removeClass('invalid');
                    jQuery('#jform_transaction_type-lbl').removeClass('required');
                    jQuery('#jform_transaction_type-lbl').removeClass('invalid');
                    jQuery('#jform_listing_date').val(obj.listing_date);
                    jQuery('#jform_sold_price').val(obj.sold_price);
                    jQuery('#jform_sold_date').val(obj.sold_date);
                    jQuery('#jform_address').val(obj.address);
                    jQuery('#jform_state').val(obj.state);
                    jQuery("#jform_state").attr("selected","selected");
                    jQuery('#jform_city').val(obj.city);
                    jQuery('#jform_zip').val(obj.zip);
                    jQuery('#jform_buyer1Name').val(obj.buyer1Name);
                    jQuery('#jform_buyer2Name').val(obj.buyer2Name);
                    jQuery('#jform_buyersfulladdress').val(obj.buyersfulladdress);
                    jQuery('#jform_buyer_phone').val(obj.buyer_phone);
                    jQuery('#jform_buyers_Agent').val(obj.buyers_Agent);
                    jQuery('#jform_buyers_agent_email').val(obj.buyers_agent_email);
                    jQuery('#jform_buyers_agent_phone').val(obj.buyers_agent_phone);
                    jQuery('#jform_seller1Name').val(obj.seller1Name);
                    jQuery('#jform_seller2Name').val(obj.seller2Name);
                    jQuery('#jform_sellersfulladdress').val(obj.sellersfulladdress);
                    jQuery('#jform_seller_phone').val(obj.seller_phone);
                    jQuery('#jform_seller_Agent').val(obj.seller_Agent);
                    jQuery('#jform_seller_agent_email').val(obj.seller_agent_email);
                    jQuery('#jform_seller_agent_phone').val(obj.seller_agent_phone);
                    jQuery('#jform_closing_title_escrow').val(obj.closing_title_escrow);
                    jQuery('#jform_escrow_tran').val(obj.escrow_tran);
                    jQuery('#jform_title_full_ddress').val(obj.title_full_ddress);
                    jQuery('#jform_title_phone').val(obj.title_phone);
                    jQuery('#jform_title_agent').val(obj.title_agent);
                    jQuery('#jform_title_email_address').val(obj.title_email_address);
                    jQuery('#jform_commission_amount').val(obj.commission_amount);
                    jQuery('#jform_commission_type').val(obj.commission_type);
                    jQuery("#jform_commission_type").attr("selected","selected");
                    jQuery('#jform_earnest_money_amount').val(obj.earnest_money_amount);
                    jQuery('#jform_earnest_money_held_by').val(obj.earnest_money_held_by);
                    jQuery("#jform_earnest_money_held_by").attr("selected","selected");
                    jQuery('#jform_home_warranty_provided').val(obj.home_warranty_provided);
                    jQuery("#jform_home_warranty_provided").attr("selected","selected");
                    jQuery('#jform_notes_for_broker_instructions').val(obj.notes_for_broker_instructions);
                    jQuery('#jform_agent_notes_for_transaction_Office').val(obj.agent_notes_for_transaction_Office);


                }
            }
        }).send();
    }


    });
</script>
<h1><?php echo $this->msg; ?></h1>
<style type="text/css">
        .nav-tabs a, .nav-tabs a:hover, .nav-tabs a:focus
        {
            outline: 0;
        }
    #jform_sold_date{width:220px !important;}
    </style>
    <input type="hidden" value="<?php echo $get_module; ?>" id="get_module">
     <div id="system-message-container"></div>
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
						<div class="controls"><?php echo $this->form->getInput('MLS'); ?></div>
                        <span id="mls_error"></span>
					</div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('transaction_type'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('transaction_type'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('listing_price'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('listing_price'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('listing_date'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('listing_date'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sold_price'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('sold_price'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sold_date'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('sold_date'); ?></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="propertyaddress">
                   <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('address'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('city'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('city'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('zip'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('zip'); ?></div>
                    </div>
                </div>
                 <div role="tabpanel" class="tab-pane" id="buyersinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer1Name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyer1Name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer2Name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyer2Name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyersfulladdress'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyersfulladdress'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyer_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyer_phone'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_Agent'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyers_Agent'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_agent_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyers_agent_email'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('buyers_agent_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('buyers_agent_phone'); ?></div>
                    </div>
                </div>
               <div role="tabpanel" class="tab-pane" id="sellersinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller1Name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller1Name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller2Name'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller2Name'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('sellersfulladdress'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('sellersfulladdress'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller_phone'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_Agent'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller_Agent'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_agent_email'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller_agent_email'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('seller_agent_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('seller_agent_phone'); ?></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="titleinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('closing_title_escrow'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('closing_title_escrow'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('escrow_tran'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('escrow_tran'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_full_ddress'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title_full_ddress'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_phone'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title_phone'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_agent'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title_agent'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('title_email_address'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('title_email_address'); ?></div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="miscinformation">
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('commission_amount'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('commission_amount'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('commission_type'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('commission_type'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('earnest_money_amount'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('earnest_money_amount'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('earnest_money_held_by'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('earnest_money_held_by'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('home_warranty_provided'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('home_warranty_provided'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('notes_for_broker_instructions'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('notes_for_broker_instructions'); ?></div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('agent_notes_for_transaction_Office'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('agent_notes_for_transaction_Office'); ?></div>
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
                            <div class="alert alert-info"><?php echo JText::_('COM_TRANSACTION_SAVE_BEFORE_IMAGES'); ?></div>
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
    $document->addStyleSheet( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css" );
    $document->addScript( "//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" );
    $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/plupload.full.min.js" );
    $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js" );

    // include language file for uploader if it exists
    if(JFile::exists(JPATH_SITE.'/components/com_iproperty/assets/js/plupload/js/i18n/'.$languageCode.'.js')){
        $document->addScript( JURI::root(true)."/components/com_iproperty/assets/js/plupload/js/i18n/".$languageCode.".js" );
    }

?>
<script>
//modalToggle
jQuery(".modalToggle").click(function(){
  	jQuery('#modal').removeClass('hide');
    });

</script>


