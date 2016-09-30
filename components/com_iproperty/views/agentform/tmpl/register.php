<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', '.ipform select');

// Create shortcut to parameters.
$params = $this->state->get('params');
            $user = JFactory::getUser();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('*');
            $query->from($db->quoteName('#__iproperty_agents'));
            $query->where($db->quoteName('user_id')." = ".$user->id);
            $db->setQuery($query);
            $results = $db->loadObject();
            $type=$results->agent_type ;


?>
<style type="text/css">
    .form-horizontal #agentdetails .controls {
    margin-left: 0px !important;
}
#jform_agent_type_chzn{width: 220px !important;}
</style>
<script type="text/javascript">
	var pluploadpath	= '<?php echo JURI::root().'/components/com_iproperty/assets/js'; ?>';
	Joomla.submitbutton = function(task) {
		// hack for &*%& IE8
		//alert(task);
        if(Browser.ie8){
			if(document.getElementById('pluploadcontainer') != null){
				document.id('pluploadcontainer').destroy();
			}
		}	
	
		if (task == 'agentform.cancel'){
            <?php echo $this->form->getField('bio')->save(); ?>
			Joomla.submitform(task);
        }else if(document.formvalidator.isValid(document.id('adminForm'))) {
            //alert('here');
            <?php //if($this->ipauth->getAdmin()): //only confirm company if admin user ?>
                /*if(document.id('jform_company').selectedIndex == ''){
                    alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_COMPANIES')); ?>');
                    return false;
                }*/
                var minMaxLength = /^[\s\S]{6,32}$/;
                var upper = /[A-Z]/;
                var lower = /[a-z]/;
                var number = /[0-9]/;
                var special = /[ !"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]/;
                var password = jQuery('#jform_password').val();

                if(jQuery('#jform_email').val() != jQuery('#jform_email2').val()){
                    var email_id = jQuery("#jform_email").attr('id');
                    var email_value = jQuery( "#jform_email" ).val();
                    console.log(email_value);
                    checkChar(email_id, email_value);
                    return false;
                }
                if(jQuery('#jform_password').val() != jQuery('#jform_password2').val()){
                    var password2_id = jQuery("#jform_password2").attr('id');
                    var password2_value = jQuery( "#jform_password2" ).val();
                    console.log(password2_value);
                    checkChar(password2_id, password2_value);
                    return false;
                }
                if(jQuery( "#jform_password" ).val() != ''){
                    console.log(password);
                    var checkLength = minMaxLength.test(password);
                    var checkUpper = upper.test(password);
                    var checkLower = lower.test(password);
                    var checkNumber = number.test(password);
                    var checkSpecial = special.test(password);
                    console.log(checkLength);
                    console.log(checkUpper);
                    console.log(checkLower);
                    console.log(checkNumber);
                    console.log(checkSpecial);
                    if(!checkLength || !checkUpper || !checkLower || !checkNumber || !checkSpecial){
                        var password_id = jQuery("#jform_password").attr('id');
                        var password_value = jQuery( "#jform_password" ).val();
                        console.log(password_value);
                        checkChar(password_id, password_value);
                        return false;
                    }
                }
                if(jQuery( "#jform_phone" ).val() != ''){
                var regex = /^\d{3}-?\d{3}-?\d{4}$/g;
                 if(!regex.test(jQuery( "#jform_phone" ).val())){
                        var phone_id = jQuery("#jform_phone").attr('id');
                        var phone_value = jQuery( "#jform_phone" ).val();
                        console.log(phone_value);
                        checkChar(phone_id, phone_value);
                    return false;
                }
            }

                /*if(!/[A-Z]/.test(jQuery('#jform_password').val())){
                    alert("Password should contain alphanumeric characters only and must have one uppercase and one special character");
                    return false;
                }if(!/[a-z]/.test(jQuery('#jform_password').val())){
                    alert("Password should contain alphanumeric characters only and must have one uppercase and one special character");
                    return false;
                }if(!/[0-9]/.test(jQuery('#jform_password').val())){
                    alert("Password should contain alphanumeric characters only and must have one uppercase and one special character");
                    return false;
                }
                if(jQuery('#jform_email').val() != jQuery('#jform_email2').val()){
                    alert('Email and Confirm Email should be same.');
                    return false;
                }
                if(jQuery('#jform_password').val() != jQuery('#jform_password2').val()){
                    alert('Password and Confirm Password should be same.');
                    return false;
                }
                if(!/[_!@#$%^&*]/.test(jQuery('#jform_password').val())){
                    alert("Password should contain alphanumeric characters only and must have one uppercase and one special character");
                    return false;
                }if(!/^[0-9]+$/.test(jQuery( "#jform_phone" ).val())){
                    
                    alert("Phone should contain Numeric characters only");
                    return false;
                }if(jQuery( "#jform_phone" ).val().length != 10){
                        alert("Phone should contain only 10 characters");
                        return false;
                }*//*if(!/^[0-9]+$/.test(jQuery( "#jform_mobile" ).val())){
                        alert("Mobile should contain Numeric characters only");
                        return false;
                }if(jQuery( "#jform_mobile" ).val().length != 10){
                        alert("Mobile should contain only 10 characters");
                        return false;
                }if(!/^[0-9]+$/.test(jQuery( "#jform_fax" ).val())){
                        alert("Fax should contain Numeric characters only");
                        return false;
                }if(jQuery( "#jform_fax" ).val().length != 10){
                        alert("Fax should contain only 10 characters");
                        return false;
                }*/
            <?php //endif; ?>
			<?php echo $this->form->getField('bio')->save(); ?>
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
                        if(inputId === 'jform_fname') {                           
                            error.error.push('Please Enter First Name');
                        }if(inputId === 'jform_lname') {
                            error.error.push('Please Enter Last Name');
                        }if(inputId === 'jform_email') {                            
                            error.error.push('Please Enter Email');
                        }if(inputId === 'jform_email2') {
                            error.error.push('Please Enter Confirm Email');
                        }if(inputId === 'jform_password') {                            
                            error.error.push('Please Enter Password');
                        }if(inputId === 'jform_password2') {                            
                            error.error.push('Please Enter Confirm Password');
                        }if(inputId === 'jform_phone') {                            
                            error.error.push('Please Enter Phone');
                        }
                    }
                }
            }
            Joomla.renderMessages(error);
        }
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

function checkInt(fieldid, fieldval){
        //console.log(fieldid);

        if (fieldval.match(/[^\d\.]/g)) {
            //console.log('false');
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid">Only Interger is Valid</span>');


            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');

        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }
function checkChar(fieldid, fieldval){
        /*console.log(fieldid);*/
        if(fieldid == 'jform_email'){
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Email and Confirm Email should be same.</span>');
        } else if (fieldid == 'jform_password') {
            //console.log('false');
            /*jQuery('#'+fieldid).val('');*/
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Password should contain atleast 6 characters, must have one uppercase, one special character, one lowercase, one numeric characters</span>');


            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');

        } else if(fieldid == 'jform_password2'){
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Password and Confirm Email should be same.</span>');
        }else if(fieldid == 'jform_phone'){
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
            jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Phone should be in this pattern xxx-xxx-xxxx</span>');
            
        }else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }
    checkAgentEmail = function(){
        document.id('system-message-container').set('tween');
        var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_iproperty&task=ajax.checkUserEmail';
        var agentEmail = document.id('jform_email').value;
        
        req = new Request({
            method: 'post',
            url: checkurl,
            data: { 'email': agentEmail,
                    'agent_id': <?php echo (int) $this->item->id; ?>,
                    '<?php echo JSession::getFormToken(); ?>':'1',
                    'format': 'raw'},
            onRequest: function() {
                document.id('email_error').set('html', '');
            },
            onSuccess: function(response) {
                if(response){
                    document.id('email_error').highlight('#ff0000');
                    document.id('jform_email').value = '';
                    document.id('jform_email').set('class', 'inputbox invalid');
                    document.id('jform_email').focus();
                    document.id('email_error').set('html', '<div class="ip_warning" style="color:red;"><?php echo JText::_('COM_IPROPERTY_AGENT_EMAIL_ALREADY_EXISTS'); ?></div>');                    
                }
            }
        }).send();
    }

        jQuery(document).ready(function(){
            jQuery('#jform_password').addClass('required');
            jQuery('#jform_password').attr('required','required');
            jQuery('#jform_password-lbl').addClass('required');
            jQuery('#jform_password-lbl').append('<span class="star">&nbsp;*</span>');
            
            jQuery("input[name='jform[phone]']").keyup(function() {
                jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "$1-$2-$3"));
            });
        });    
</script>

<div class="edit register item-page<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1>
            <?php //echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php endif; ?>
    <div class="ip-mainheader">
        <h2><?php echo $this->iptitle; ?></h2>
    </div>
    
    <div id="system-message-container"></div>

    <form action="<?php echo JRoute::_('index.php?option=com_iproperty&layout=register&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal">
        <div class="btn-toolbar" id="btns_bar">
			<div class="btn-group">
                <!-- <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('agentform.apply')">
                    <?php echo JText::_('COM_IPROPERTY_APPLY') ?>
                </button> -->
                <button type="button" class="btn" onclick="Joomla.submitbutton('agentform.save')">
                    <?php echo JText::_('JREGISTER') ?>
                </button>
                <button type="button" class="btn" onclick="Joomla.submitbutton('agentform.cancel')">
                    <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
        </div>
        <!-- <ul class="nav nav-tabs">
            <li class="active"><a href="#agentdetails" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_DETAILS') ?></a></li>
            <!-- <li><a href="#agentbio" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_IMAGE').' / '.JText::_('COM_IPROPERTY_AGENT_BIO'); ?></a></li> -->
            <?php /*if ($this->ipauth->getAdmin() || $this->ipauth->getSuper()): ?>
                <li><a href="#agentother" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_OTHER') ?></a></li>
            <?php endif;*/ ?>
        <!--</ul> -->
        
        <div class="tab-content">
            <div class="tab-pane active" id="agentdetails">
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_ACCOUNT_DETAILS'); ?></legend>
                    <div class="span12">
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('agent_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('agent_type'); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('fname'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('fname'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lname'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lname'); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('email'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('email'); ?>
                                <span id="email_error"></span>
                            </div>
                        </div>
                    </div>    
                    <div class="span12">
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('email2'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('email2'); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('password'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('password'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span12">
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('password2'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('password2'); ?>
                            </div>
                        </div>
                        <div class="control-group span6">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('phone'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('phone'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="controls" style="margin-top:10px;">
                        <span class="linklog"><a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=ipuser'); ?>">
                            <?php echo JText::_('COM_IPROPERTY_ALREADY_REGISTERED'); ?></a></span>
                    </div>
                    <!--<div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('mobile'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('mobile'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('fax'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('fax'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('alicense'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('alicense'); ?>
                        </div>
                    </div>-->
                </fieldset>
                <fieldset id="web_fieldset1" style="display:none;">
                    <legend><?php echo JText::_('COM_IPROPERTY_AGENT'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('website'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('website'); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('msn'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('msn'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('skype'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('skype'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('gtalk'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('gtalk'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('twitter'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('twitter'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('social1'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('social1'); ?>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="tab-pane" id="agentbio">
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_IMAGE'); ?></legend>
                    <div class="control-group">
                        <div class="controls">
                            <?php echo $this->form->getInput('icon'); ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_AGENT_BIO'); ?></legend>
                    <div class="control-group form-vertical">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('iphead1'); ?>
                        </div>
                        <div class="control">
                            <?php echo $this->form->getInput('bio'); ?>
                        </div>
                    </div>            
                </fieldset>
            </div>  
            <!-- super agent or admin can edit params 
            <?php if ($this->ipauth->getAdmin() || $this->ipauth->getSuper()): ?>
                <div class="tab-pane" id="agentother">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('JPUBLISHED'); ?></legend>
                        <?php // 3.3.2 Addition - we don't want usesr to be able to unpublish own agent unless administrator ?>
                        <?php if ($this->ipauth->getAdmin() || ($this->ipauth->getSuper() && $this->item->user_id != $this->user->id)): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('state'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('state'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('featured'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('featured'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="adminform superagent_params">
                        <legend><?php echo JText::_('COM_IPROPERTY_AGENT_PARAMETERS'); ?></legend>
                        <?php foreach($this->form->getFieldset('superagent_params') as $field) :?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </fieldset>  
                </div>
            <?php endif; ?> -->
        </div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
        <?php echo JHtml::_( 'form.token'); ?>
    </form>
</div>
<script type="text/javascript">
jQuery('#jform_password2').addClass('required');
jQuery('#jform_password2').attr('required','required');
jQuery('#jform_password2-lbl').addClass('required');
jQuery('#jform_password2-lbl').append('<span class="star">&nbsp;*</span>');

jQuery('#jform_email2').addClass('required');
jQuery('#jform_email2').attr('required','required');
jQuery('#jform_email2-lbl').addClass('required');
jQuery('#jform_email2-lbl').append('<span class="star">&nbsp;*</span>');

jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
        var div_top = jQuery('.tab-content').offset().top;
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