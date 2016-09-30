<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined('JPATH_BASE') or die;

class JFormFieldGallery extends JFormField
{
    protected $type = 'Gallery';

	protected function getInput()
	{
		$settings = ipropertyAdmin::config();

		// set defaults for Amazon S3 to false
		$use_aws 		= 0;
		$bucket			= false;
		$accessKeyId 	= false;
		$secret		 	= false;
		$policy		 	= false;
		$signature	 	= false;
		
		if ($settings->gallery_use_s3 && $settings->gallery_s3_bucket && $settings->gallery_s3_key && $settings->gallery_s3_secret){
			$use_aws		= true;
			$bucket			= $settings->gallery_s3_bucket;
			$accessKeyId	= $settings->gallery_s3_key;
			$secret			= $settings->gallery_s3_secret;
			// prepare policy
			$policy = base64_encode(json_encode(array(
				// ISO 8601 - date('c'); generates uncompatible date, so better do it manually
				'expiration' => date('Y-m-d\TH:i:s.000\Z', strtotime('+1 day')),  
				'conditions' => array(
					array('bucket' => $bucket),
					array('acl' => 'public-read'),
					array('success_action_status' => '201'),
					array('starts-with', '$key', ''),
					// for demo purposes we are accepting only images
					array('starts-with', '$Content-Type', 'image/'),
					// Plupload internally adds name field, so we need to mention it here
					array('starts-with', '$name', ''), 	
					// One more field to take into account: Filename - gets silently sent by FileReference.upload() in Flash
					// http://docs.amazonwebservices.com/AmazonS3/latest/dev/HTTPPOSTFlash.html
					array('starts-with', '$Filename', ''), 
				)
			)));

			// sign policy
			$signature = base64_encode(hash_hmac('sha1', $policy, $secret, true));
		}		
        ?>
		<script type="text/javascript">
			var saveRemoteImage; 
			var aws = <?php echo $use_aws; ?>; // false for non-S3
			var client_path = (ipGalleryOptions.client == 'administrator') ? 'administrator/' : '';
			var upload_url = aws ? 'http://<?php echo $bucket; ?>.s3.amazonaws.com/' : ipGalleryOptions.ipbaseurl+client_path+'index.php?option=com_iproperty&task=ajax.ajaxUpload&format=raw&propid='+ipGalleryOptions.propid+'&'+ipGalleryOptions.iptoken+'=1';
			
			//*********************************
			// PLUPLOAD FUNCTION
			//*********************************
			jQuery(function($) {	
				$(document).ready(function(){
					$('#ipUploader').pluploadQueue({
						runtimes : 'html5,flash,silverlight,html4',
						url : upload_url,
						dragdrop: true,
						multiple_queues: true,
						multipart: true,
						multipart_params: {
							'key': '${filename}', // use filename as a key
							'Filename': '${filename}', // adding this to keep consistency across the runtimes
							'acl': 'public-read',
							'Content-Type': 'image/jpeg',
							'AWSAccessKeyId' : '<?php echo $accessKeyId; ?>',		
							'policy': '<?php echo $policy; ?>',
							'signature': '<?php echo $signature; ?>',
							'success_action_status': String("201")
						},

						filters: {
							max_file_size : ipGalleryOptions.ipmaximagesize+'kb',
							mime_types: ipGalleryOptions.allowedFileTypes
						},
						unique_names : true,

						// Flash settings
						flash_swf_url : ipGalleryOptions.pluploadpath+'/plupload/js/Moxie.swf',

						// Silverlight settings
						silverlight_xap_url : ipGalleryOptions.pluploadpath+'/plupload/js/Moxie.xap',

						init : {
							Error: function(up, args) {
								// Called when a error has occured
								console.dir(args);
							},
							BeforeUpload: function(uploader, file){
								$('#uploadProgress').show();
							},
							FileUploaded: function(up, file, res) {
								if (!aws) {
									var response = $.parseJSON(res.response);
									if(response[0].status == 'ok'){
										//console.log(response[0].data.type);exit;
										if (response[0].data.type == '.jpg') {
											buildSelected(response[0].data, false);
										} else if (response[0].data.type == '.mp4') {
											buildSelectedVideos(response[0].data, false);
										} else {
											buildSelectedDocs(response[0].data, false);
										}
										// refresh sortable
										$('#ip_selected_images').sortable('refresh');
										// save ordering
										if ($('#ipnoresults')) $('#ipnoresults').remove();
									} else {
										if(response[0].status == 'error'){
											var errortext = '<div class="alert alert-error fade in">';
											errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
											errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+response[0].message;
											errortext += 	'</div>';
											$('#ipUploader_container').append(errortext);
										} else {
											console.log(response[0].message);
										}
									}
								} else {
									if (res.status == '201'){					
										var response = $($.parseXML(res.response));
										var location = response.find( "Location" );						
										saveRemoteImage(location.text());
									}
								}
							},
							UploadComplete: function(uploader, file) {
								$('#uploadProgress').hide();
								var errortext = '<div id="ipcomplete" class="alert alert-info fade in">';
								errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
								errortext += 	ipGalleryOptions.language.uploadcomplete;
								errortext += 	'</div>';
								$('#ipUploader_container').append(errortext).fadeIn(4500, function(){
									$('#ipcomplete').fadeOut('slow');
								});
								saveImageOrder();
							}
						}
					});
				});
			});		
		</script>
        <div class="ipgallerycontainer">
            <div class="span12 image-uploader">
                <h4><?php echo JText::_('COM_IPROPERTY_UPLOAD'); ?></h4>
                <hr />
                <div id="ipUploader">
                    <!-- ADD STANDARD UPLOADER HERE AS FALLBACK -->
                    <?php echo JText::_('COM_IPROPERTY_FLASH_DISABLED'); ?>
                </div>
                <div class="clearfix"></div>
                <div class="remote_container control-group">
                    <div class="control-label">
                        <label class="hasTip" title="<?php echo JText::_('COM_IPROPERTY_REMOTE'); ?>::<?php echo JText::_('COM_IPROPERTY_UPLOAD_REMOTE'); ?>"><?php echo JText::_('COM_IPROPERTY_REMOTE'); ?></label>
                    </div>
                    <div class="controls">
                        <input type="text" id="uploadRemote" class="inputbox" maxlength="150" value="" />
                        <div id="uploadRemoteGo" class="btn btn-info"><?php echo JText::_('COM_IPROPERTY_SAVE'); ?></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="uploadProgress" class="span12 center" style="display: none;"><img src="<?php echo JURI::root(true); ?>/components/com_iproperty/assets/images/ajax-loader.gif" /></div>
            <div class="clearfix"></div>
			<div id="ip_message"></div>
			<div class="clearfix"></div>
            <ul class="nav nav-tabs">
                <li class="active"><a href="#ip_sortableimages" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_IMAGES');?></a></li>
                <li><a href="#ip_sortabledocs" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_DOCUMENTS');?></a></li>
                <li><a href="#ip_sortablevideos" data-toggle="tab"><?php echo 'Videos'; //echo JText::_('COM_IPROPERTY_VIDEOS');?></a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="ip_sortableimages">
					<div class="well"><button id="addAvailable" class="btn btn-success" type="button" data-toggle="modal" data-target="#ip_avail_modal"><?php echo JText::_('COM_IPROPERTY_SELECT_AVAILABLE'); ?></button></div>
                    <div>
                        <ul id="ip_selected_images" class="thumbnails"></ul>
                    </div>
                </div>
                <div class="tab-pane" id="ip_sortabledocs">
					<div class="well"><button id="addAvailableDocs" class="btn btn-success" type="button" data-toggle="modal" data-target="#ip_availdocs_modal"><?php echo JText::_('COM_IPROPERTY_SELECT_AVAILABLE_DOCS'); ?></button></div>
                    <div>
                        <ul id="ip_selected_docs" class="thumbnails"></ul>
                    </div>
                </div>
                <div class="tab-pane" id="ip_sortablevideos">
					<div>
						<ul id="ip_selected_videos" class="thumbnails"></ul>
                    </div>
                </div>
            </div>
        </div>
		<!-- IMAGES HIDDEN FORMS -->
		<!-- hidden form for pop up dialog -->
		<div id="ip_image_form" title="<?php echo JText::_('COM_IPROPERTY_MODIFY_IMAGE'); ?>" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><?php echo JText::_('COM_IPROPERTY_EDIT'); ?></h3>
			</div>
			<div class="modal-body form-horizontal">
				<fieldset>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo JText::_('COM_IPROPERTY_TITLE'); ?>
                        </div>
                        <div class="controls">
                            <input type="text" name="imgtitle" id="imgtitle" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo JText::_('COM_IPROPERTY_DESCRIPTION'); ?>
                        </div>
                        <div class="controls">
                            <textarea name="imgdescription" rows="3" id="imgdescription"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="imgid" name="imgid" />
				</fieldset>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_IPROPERTY_CLOSE'); ?></button>
				<button id="ip_image_form_save" class="btn btn-primary"><?php echo JText::_('COM_IPROPERTY_SAVE'); ?></button>
			</div>
		</div>
		<!-- hidden form for available images dialog -->
		<div id="ip_avail_modal" class="modal hide fade" style="width: 800px; margin-left: -400px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><?php echo JText::_('COM_IPROPERTY_SELECT_AVAILABLE'); ?></h3>
				<div class="input-append">
					<input id="ip_avail_filter" type="text" class="input-small" placeholder="<?php echo JText::_('COM_IPROPERTY_KEYWORD'); ?>">
					<button class="btn" type="button"><?php echo JText::_('COM_IPROPERTY_GO'); ?></button>
				</div>
				<div id="ip_avail_pager" class="pagination"></div>
			</div>
			<div class="modal-body">
				<div>
					<ul id="ip_available_images" class="thumbnails row-fluid"></ul>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_IPROPERTY_CLOSE'); ?></button>
				<button id="ip_avail_form_save" class="btn btn-primary"><?php echo JText::_('COM_IPROPERTY_SAVE'); ?></button>
			</div>
		</div>
		<!-- DOCS HIDDEN FORMS -->
		<!-- hidden form for pop up dialog -->
		<div id="ip_docs_form" title="<?php echo JText::_('COM_IPROPERTY_MODIFY_DOCUMENT'); ?>" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><?php echo JText::_('COM_IPROPERTY_EDIT'); ?></h3>
			</div>
			<div class="modal-body form-horizontal">
				<fieldset>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo JText::_('COM_IPROPERTY_TITLE'); ?>
                        </div>
                        <div class="controls">
                            <input type="text" name="doctitle" id="doctitle" />
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo JText::_('COM_IPROPERTY_DESCRIPTION'); ?>
                        </div>
                        <div class="controls">
                            <textarea name="docdescription" rows="3" id="docdescription"></textarea>
                        </div>
                    </div>
                    <input type="hidden" id="docid" name="docid" />
				</fieldset>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_IPROPERTY_CLOSE'); ?></button>
				<button id="ip_doc_form_save" class="btn btn-primary"><?php echo JText::_('COM_IPROPERTY_SAVE'); ?></button>
			</div>
		</div>
		<!-- hidden form for available docs dialog -->
		<div id="ip_availdocs_modal" class="modal hide fade">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3><?php echo JText::_('COM_IPROPERTY_SELECT_AVAILABLE_DOCS'); ?></h3>
				<div class="input-append">
					<input id="ip_availdocs_filter" type="text" class="input-small" placeholder="<?php echo JText::_('COM_IPROPERTY_KEYWORD'); ?>">
					<button class="btn" type="button"><?php echo JText::_('COM_IPROPERTY_GO'); ?></button>
				</div>
				<div id="ip_availdocs_pager" class="pagination"></div>
			</div>
			<div class="modal-body">
				<div>
					<ul id="ip_available_docs" class="thumbnails row-fluid"></ul>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_IPROPERTY_CLOSE'); ?></button>
				<button id="ip_availdocs_form_save" class="btn btn-primary"><?php echo JText::_('COM_IPROPERTY_SAVE'); ?></button>
			</div>
		</div>
    <?php
	}
}
