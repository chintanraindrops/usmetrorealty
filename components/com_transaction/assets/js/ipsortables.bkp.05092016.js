/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

var buildSelectedDocs, getSelectedImages, getSelectedVideos, getAvailableImages, saveImageOrder, saveVideoOrder, saveImageTags, deleteImage, deleteVideo, addAvailImage, doAjaxRequest, buildSelected, buildAvailable, ajaxParseResult, buildPagination, ipPage, ipNextPageSet, ipPrevPageSet; 

jQuery(function($) {
	$(document).ready(function(){

		//*********************************
		// GET IMAGE FUNCTIONS
		//*********************************

		getSelectedImages = function()
        {
			var data        = new Object();
			data.task       = 'ajax.ajaxLoadGallery';
			data.transaction_id     = ipGalleryOptions.transaction_id;
			data.own        = 1;
			data.limitstart = 0
			data.limit      = 100;

			//console.log(data); 

			var result  = doAjaxRequest(data, function(result){				
				if(data = ajaxParseResult(result)){
					buildSelected(data, false);
					//console.log(data);
					/*if(data.totalimgs >= 1){
						if ($('#ipnoresults')) $('#ipnoresults').remove();
						// we got images
						$.each(data.photos, function(i, photo) {
							buildSelected(photo, false);
						});
						//fit images to content frame*/
						$(".ipimage").css({'width' : ipGalleryOptions.ipthumbwidth});
					/*} else {
						$('#ip_selected_images').prepend('<li id="ipnoresults" class="alert alert-info span12 fade in">'+ipGalleryOptions.language.noresults+'</li>');
					}*/
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		getSelectedVideos = function()
        {
			var data        = new Object();
			data.task       = 'ajax.ajaxLoadGallery';
			data.transaction_id     = ipGalleryOptions.transaction_id;
			data.own        = 1;
			data.limitstart = 0
			data.type 		= 1; 
			data.limit      = 100;
			data.mediatype 	= 1;

			var result  = doAjaxRequest(data, function(result){
				//alert(result);
				if(data = ajaxParseResult(result)){
					//console.log(data);
					//alert(data);
					buildSelectedVideos(data, false);
					/*if(data.totalimgs >= 1){
						if ($('#ipnoresultsvideos')) $('#ipnoresultsvideos').remove();
						// we got images
						$.each(data.photos, function(i, photo) {
							buildSelectedVideos(photo, false);
						});
						// fit images to content frame
						$(".ipimage").css({'width' : ipGalleryOptions.ipthumbwidth});
					} else {
						$('#ip_selected_videos').prepend('<li id="ipnoresultsvideos" class="alert alert-info span12 fade in">'+ipGalleryOptions.language.noresults+'</li>');
					}*/
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		getAvailableImages = function()
        {
			var data        = new Object();
			data.task       = 'ajax.ajaxLoadGallery';
			data.transaction_id     = ipGalleryOptions.transaction_id;
			data.own        = 0;
			data.limitstart = ipPagination.limitstart;
			data.limit      = ipPagination.availlimit;
			data.filter		= $("#ip_avail_filter").val();

			var result  = doAjaxRequest(data, function(result){
				if(data = ajaxParseResult(result)){
					if(data.totalimgs >= 1){
						// clear ul in case this was a filter
						$("#ip_available_images").empty();
						// set pagination vars
						ipPagination.totalavailimgs = parseInt(data.totalimgs);
						ipPagination.totalpages	= Math.ceil( ipPagination.totalavailimgs / ipPagination.availlimit );
						buildPagination();
						// remove no reults if it exists
						if ($('#ip_availnoresults')) $('#ip_availnoresults').remove();
						// we got images, parse them
						$.each(data.photos, function(i, photo) {
							buildAvailable(photo);
						});
						// fit images to content frame
						//$(".ipimage").css({'height' : 100, 'width' : ipGalleryOptions.ipthumbwidth});
					} else {
						$('#ip_available_images').prepend('<li id="ip_availnoresults" class="alert alert-info span12 fade in">'+ipGalleryOptions.language.noresults+'</li>');
					}
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		//*********************************
		// ACTION FUNCTIONS
		//*********************************

		// save image order
		saveImageOrder = function()
        {
			var order   = $("#ip_selected_images").sortable("toArray");
            order.splice( $.inArray("ipnoresults", order), 1 );
			var data    = new Object();
			data.task   = 'ajax.ajaxSort';
			data.order  = JSON.stringify(order);

			var result  = doAjaxRequest(data, function(result){
				if(!ajaxParseResult(result)){
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		// save image order
		saveVideoOrder = function()
        {
			var order   = $("#ip_selected_videos").sortable("toArray");
            order.splice( $.inArray("ipnoresults", order), 1 );
			var data    = new Object();
			data.task   = 'ajax.ajaxSort';
			data.order  = JSON.stringify(order);

			var result  = doAjaxRequest(data, function(result){
				if(!ajaxParseResult(result)){
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>'+ipGalleryOptions.language.warning+'</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		// save image tags
		saveImageTags = function(id, title, description)
        {
			var data    = new Object();
			data.task   = 'ajax.ajaxSaveImageTags';
			data.imgid  = id;
			data.title  = title;
			data.descr  = description;

			var result  = doAjaxRequest(data, function(result){
				var el  = $('#'+data.imgid);
				if(ajaxParseResult(result)){
					var id = data.imgid;
					// set the text for the li values
					$(el).data( { 'title': data.title, 'description': data.descr } );
					// set the tags
					$(el).find('span.icon-zoom-in').attr('data-content', data.descr).attr('data-original-title', data.title);
					// reset the form values
					$("#imgid").val('');
					$("#imgtitle").val('');
					$("#imgdescription").val('');
					$("#ip_image_form").modal("hide");
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>Warning!</strong> '+decodeData.message;
					errortext += 	'</div>';
					$(el).prepend(errortext);
				}
			});
		}

		// delete image
		deleteImage = function(img)
        {
			var data    = new Object();
			data.task   = 'ajax.ajaxDelete';
			data.img    = img;

			var result  = doAjaxRequest(data, function(result){
				var el  = $('#'+data.img);
				if(ajaxParseResult(result)){
					$(el).effect("fade", {}, 500, function() {
						(el).remove();
						$('#ip_selected_images').sortable('refresh');
						if ($("#ip_selected_images").sortable("toArray").length == 0) $('#ip_selected_images').prepend('<li id="ipnoresults" class="alert alert-info span12 fade in">'+ipGalleryOptions.language.noresults+'</li>').fadeIn();
					});
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>Warning!</strong> '+decodeData.message;
					errortext += 	'</div>';
					$(el).prepend(errortext);
				}
			});
		}

		// delete video
		deleteVideo = function(img)
        {
			var data    = new Object();
			data.task   = 'ajax.ajaxDelete';
			data.img    = img;

			var result  = doAjaxRequest(data, function(result){
				var el  = $('#'+data.img);
				if(ajaxParseResult(result)){
					$(el).effect("fade", {}, 500, function() {
						(el).remove();
						$('#ip_selected_videos').sortable('refresh');
						if ($("#ip_selected_videos").sortable("toArray").length == 0) $('#ip_selected_videos').prepend('<li id="ipnoresults" class="alert alert-info span12 fade in">'+ipGalleryOptions.language.noresults+'</li>').fadeIn();
					});
				} else {
					var decodeData = $.parseJSON(result);
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>Warning!</strong> '+decodeData.message;
					errortext += 	'</div>';
					$(el).prepend(errortext);
				}
			});
		}

		// add image
		addAvailImage = function(img)
        {
			var data    = new Object();
			data.task   = 'ajax.ajaxAdd';
			data.imgid  = img;
			data.propid = ipGalleryOptions.propid;
			var result  = doAjaxRequest(data, function(result){
				var decodeData = $.parseJSON(result);
				if(ajaxParseResult(result)){
					// if we still have the no result banner remove it
					if ($('#ipnoresults')){
						$('#ipnoresults').effect("fade", {}, 500, function() {
							$('#ipnoresults').remove();
						});
					}
					var data = decodeData.data;
					buildSelected(data, false);
					$('#ip_selected_images').sortable('refresh');
					// remove the image from the avail list and array
					$("#"+img).effect("fade", {}, 500, function() {
						var index = $.inArray(img, addimages);
						if (index != -1) addimages.splice(index, 1);
					});
					// save ordering
					saveImageOrder();
					$("#ip_avail_modal").modal('hide');
				} else {
					var errortext = '<div class="alert alert-error fade in">';
					errortext +=	'<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += 	'<strong>Warning!</strong> '+decodeData.message;
					errortext += 	'</div>';
					$('#ip_message').html(errortext);
				}
			});
		}

		// save remote image
		saveRemoteImage = function(path)
        {		
			var data    = new Object();
			data.task   = 'ajax.ajaxUploadRemote';
			data.propid = ipGalleryOptions.propid;
			data.path   = path;
			var result  = doAjaxRequest(data, function(result){
				var decodeData = $.parseJSON(result);
				if(ajaxParseResult(result)){
					var data = decodeData.data;
					buildSelected(data, false);
					$('#ip_selected_images').sortable('refresh');
					// save ordering
					saveImageOrder();
				} else {
					var errortext = '<div class="alert alert-error fade in">';
					errortext += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
					errortext += '<strong>Warning!</strong> '+decodeData.message;
					errortext += '</div>';
					$('#ip_message').html(errortext);
				}
			});
		};

		//*********************************
		// UTILITY FUNCTIONS
		//*********************************

		// build generic ajax request object
		doAjaxRequest = function(data, callback)
        {
			$.ajax({
			  url: ipGalleryOptions.ipbaseurl+client+'index.php?option=com_transaction&'+ipGalleryOptions.iptoken+'=1&format=raw',
			  data: data,
			  type: 'POST',
			  cache: false,
			  error: function(request, status, error_message){
				console.log(status+' - '+error_message);
				var data = { 'status': 'error', 'message': error_message };
				callback(data);
			  },
			  success: function(data) {
				callback(data);
			  }
			});
		}

		// build the object and li for the selected images sortable
		buildSelected = function(img, prepend)
        {
        	//http://192.168.1.35/projects
        	var	getUrl = window.location;
			var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
			
        var arr_split = img.split(',');
        //console.log(arr_split);
        // var main_img_path = new Array();
	        for(i=0;i<arr_split.length;i++){
				var arr = arr_split[i].split('/');
				//console.log(arr_split.length);
				var last = arr.splice(0,4);
				//console.log(join_img);
				var join_img = arr.join('/'); 
				
				var  main_img_path = baseUrl+"/"+join_img;
				
			/*var imgpath = (img.remote == 1) ? img.path : ipGalleryOptions.ipbaseurl+img.path;
			
			imgpath += (img.remote == 1) ? img.fname : img.fname+'_thumb';
			imgpath += img.type ? img.type : '';*/
			//console.log(img.id);
			// build li
			
			var li      = $('<li class="ipselected" id="' + img.id + '" />');
			var html    = $('<div class="thumbnail" />');
            var contain = $('<div class="ipimage-container" />');
			var imgtag  = '<img class="ipimage" src="'+main_img_path+'" alt="" />';
			var buttons = $('<p />');
			var edit    = $('<span class="icon-pencil" style="cursor: pointer;" rel="tooltip" title="'+ipGalleryOptions.language.edit+'"></span>');
			var del     = $('<span class="icon-minus" style="cursor: pointer;" rel="tooltip" title="'+ipGalleryOptions.language.del+'"></span>');
			var info    = $('<span class="icon-zoom-in" style="cursor: pointer;" rel="popover" data-content="'+img.description+'" data-original-title="'+img.title+'"</span>');

            // insert img into container in order to control via css
            contain.append(imgtag);

			// append elements
            li.append(html);
			html.append(contain);

			html.append(buttons);
			buttons.append(edit);
			buttons.append(del);
			buttons.append(info);

			// init popovers and tooltips
			$(edit).tooltip();
			$(del).tooltip();
			$(info).popover( {'trigger': 'hover'} );

			// css for image container to keep consistent layout
            $(contain).css({'cursor':'move', 'height': Math.round((( ipGalleryOptions.ipthumbwidth ) / 1.5 )), 'overflow':'hidden', 'margin-bottom':'5px'});

			// attach title and description data
			li.data( {'title': img.title, 'description': img.description} );

			if (prepend) {
				$("#ip_selected_images").prepend(li);
			} else {
				$("#ip_selected_images").append(li);
			}
}
			// check if noresults banner is in place
			if ($('#ipnoresults')){
				$('#ipnoresults').effect("fade", {}, 500, function() {
					$('#ipnoresults').remove();
				});
			}
		}

		// build the object and li for the selected images sortable
		buildSelectedVideos = function(img, prepend)
        {
        	//console.log(img);
			var imgpath = (img.remote == 1) ? img.path : ipGalleryOptions.ipbaseurl+img.path;
			imgpath += (img.remote == 1) ? img.fname : img.fname;
			imgpath += img.type ? img.type : '';

			// build li
			var li      = $('<li class="ipselected" id="' + img.id + '" />');
			var html    = $('<div class="thumbnail" />');
            var contain = $('<div class="ipimage-container" />');
			//var imgtag  = '{youtube}OU7_n4nAb0Q{/youtube}';
			//var imgtag  = ' {mp4}'+imgpath+'{/mp4}';
			var imgtag = '<video id="myVideo_demo_'+img.id+'" width="100%" height="100%" controls="controls"><source src="'+imgpath+'" type="video/mp4"></video>';
			var buttons = $('<p />');
			var edit    = $('<span class="icon-pencil" style="cursor: pointer;" rel="tooltip" title="'+ipGalleryOptions.language.edit+'"></span>');
			var del     = $('<span class="icon-minus" style="cursor: pointer;" rel="tooltip" title="'+ipGalleryOptions.language.del+'"></span>');
			var info    = $('<span class="icon-zoom-in" style="cursor: pointer;" rel="popover" data-content="'+img.description+'" data-original-title="'+img.title+'"</span>');

            // insert img into container in order to control via css
            contain.append(imgtag);

			// append elements
            li.append(html);
			html.append(contain);

			html.append(buttons);
			buttons.append(edit);
			buttons.append(del);
			buttons.append(info);

			// init popovers and tooltips
			$(edit).tooltip();
			$(del).tooltip();
			$(info).popover( {'trigger': 'hover'} );

			// css for image container to keep consistent layout
            $(contain).css({'cursor':'move', 'height': Math.round((( ipGalleryOptions.ipthumbwidth ) / 1.5 )), 'overflow':'hidden', 'margin-bottom':'5px'});

			// attach title and description data
			li.data( {'title': img.title, 'description': img.description} );

			if (prepend) {
				$("#ip_selected_videos").prepend(li);
			} else {
				$("#ip_selected_videos").append(li);
			}

			// check if noresults banner is in place
			if ($('#ipnoresults')){
				$('#ipnoresults').effect("fade", {}, 500, function() {
					$('#ipnoresults').remove();
				});
			}
		}


        /*$('#myVideo_demo').videocontrols( 
        { 
            markers: [40, 84, 158, 194, 236, 272, 317, 344, 397, 447, 490, 580], 
            preview: 
            { 
                //sprites: ['big_bunny_108p_preview.jpg'], 
                step: 10, 
                width: 200 
            }, 
            theme: 
            { 
                progressbar: 'blue', 
                range: 'pink', 
                volume: 'pink' 
            } 
        }); */


		// build available images
		buildAvailable = function(img)
        {
			var imgpath = (img.remote == 1) ? img.path : ipGalleryOptions.ipbaseurl+img.path;
			imgpath += (img.remote == 1) ? img.fname : img.fname+'_thumb';
			imgpath += img.type ? img.type : '';

			// full-size image path
			var orgpath = (img.remote == 1) ? imgpath : imgpath.replace('_thumb', '');

			// build li
			var li      = $('<li class="span3 ipavail" id="' + img.id + '" />');
			var	html    = $('<div class="thumbnail" />');
            var contain = $('<div class="ipimage-container" />');
			var imgtag  = $('<img class="ipimage" style="cursor: pointer;" src="'+imgpath+'" />');
			var buttons = $('<p />');
			var add     = $('<span class="icon-plus" style="cursor: pointer;" rel="tooltip" title="'+ipGalleryOptions.language.add+' - '+img.fname+img.type+'"></span>');
			//var info    = $('<span id="info'+img.id+'" class="icon-zoom-in" style="cursor: pointer;" rel="popover" data-original-title="'+img.fname+img.type+'"</span>');

			//var imgdata = '<img src="'+orgpath+'" />';
			//$(info).attr('data-content', imgdata);

            // css for image container to keep consistent layout
            $(contain).css({'height': '100px', 'overflow':'hidden', 'margin-bottom':'5px'});

            // insert img into container in order to control via css
            contain.append(imgtag);

			// append objects
			li.append(html);
			html.append(contain);
			html.append(buttons);
			buttons.append(add);

			// init popovers and tooltips
			$(add).tooltip();
			//$(info).popover( { 'trigger': 'hover', 'html': true } );

			$("#ip_available_images").append(li);
		}

		// helper function to parse ajax result object
		ajaxParseResult = function(data)
        {
			var decodeData = $.parseJSON(data);
			if (decodeData.status == 'error'){
				if (ipGalleryOptions.debug) console.dir(decodeData);
				return false;
			} else {
				/*console.log(decodeData);
				alert(decodeData.data);*/
				// if we have json data returned, return it, otherwise return true
				if ((decodeData.status == 'ok') && decodeData.data) return decodeData.data;
				return true;
			}
		}

		//*********************************
		// PAGINATION FUNCTIONS
		//*********************************

		buildPagination = function()
        {
			$("#ip_avail_pager").empty();
			if(ipPagination.totalpages > 1){
				$("#ip_avail_pager").append('<ul id="ip_avail_pagination"></ul>');
				ipPagination.totalpagesets = (Math.ceil(ipPagination.totalpages / 10));
				// build numbered page links
				var y = (ipPagination.currentpageset == 1) ? 1 : ((ipPagination.currentpageset - 1) * 10) + 1;
				var z = y + 10;
				for (y; y < z; y++){
					var pageitem = $('<li id="page'+y+'"><a href="#">'+y+'</a></li>');
					if (y <= ipPagination.totalpages){
						if (ipPagination.currentpage == y) $(pageitem).addClass('active');
						$(pageitem).click( function(e) {
							e.preventDefault();
							var pageid = $(this).attr('id').substring(4);
							ipPage(pageid);
						});
						$("#ip_avail_pagination").append(pageitem);
					}
				}
				if(ipPagination.totalpagesets > 1) {
					if (ipPagination.currentpageset < ipPagination.totalpagesets){
						var nextitem = $('<li><a href="#">'+ipGalleryOptions.language.next+'</a></li>');
						$(nextitem).click( function(e) {
							e.preventDefault();
							ipNextPageSet();
						});
						$("#ip_avail_pagination").append(nextitem);
					}
					if (ipPagination.currentpageset > 1){
						var previtem = $('<li><a href="#">'+ipGalleryOptions.language.previous+'</a></li>');
						$(previtem).click( function(e) {
							e.preventDefault();
							ipPrevPageSet();
						});
						$("#ip_avail_pagination").prepend(previtem);
					}
				}
			} else {
				// no need for pager so remove that element
				$("#ip_avail_pager").empty();
			}
		}

		ipPage = function(page)
        {
			// empty the available images ul
			$("#ip_available_images").empty();
			// set start number since we just know the batch size and page requested
			ipPagination.limitstart = (page - 1) * ipPagination.availlimit;
			// remove class from active pager, add to new page
			$("#page"+ipPagination.currentpage).removeClass('active');
			$("#page"+page).addClass('active');
			ipPagination.currentpage = page;
			// now request a new batch of avail images
			getAvailableImages();
		}

		ipNextPageSet = function()
        {
			ipPagination.currentpageset++;
			$("#ip_avail_pager").empty();
			buildPagination();
		}

		ipPrevPageSet = function()
        {
			ipPagination.currentpageset--;
			$("#ip_avail_pager").empty();
			buildPagination();
		}
		
		// DO SETUP FUNCTION
		
		// create pagination vars
		var ipPagination = {
			limitstart: 0,
			availlimit: 100, // max avail images per page
			currentpage: 1,
			totalavailimgs: 0,
			totalpages: 0,
			currentpageset: 1,
			totalpagesets: 1
		}

		// build collection for adding images
		var addimages = new Array();
        var client = (ipGalleryOptions.client == 'administrator') ? 'administrator/' : '';

		// get the images and build the display
		getSelectedImages();
		getSelectedVideos();
		getAvailableImages();

		//*********************************
		// SETUP FUNCTIONS
		//*********************************

		// hook up edit clicks
		$("#ip_selected_images").on("click",  "span.icon-pencil", function(e) {
			e.preventDefault();
			var id = $(this).closest('li.ipselected').attr('id');
			var el = $("#"+id);
			$("#imgtitle").val(el.data('title'));
			$("#imgdescription").val(el.data('description'));
			$("#imgid").val(id);
			$('#ip_image_form').modal('show');
		});

		// hook up edit clicks
		$("#ip_selected_videos").on("click",  "span.icon-pencil", function(e) {
			e.preventDefault();
			var id = $(this).closest('li.ipselected').attr('id');
			var el = $("#"+id);
			$("#imgtitle").val(el.data('title'));
			$("#imgdescription").val(el.data('description'));
			$("#imgid").val(id);
			$('#ip_image_form').modal('show');
		});

		// hook up delete clicks
		$("#ip_selected_images").on("click",  "span.icon-minus", function(e) {
			e.preventDefault();
			var id = $(this).closest('li.ipselected').attr('id');
			bootbox.confirm(ipGalleryOptions.language.confirm, ipGalleryOptions.language.cancel, ipGalleryOptions.language.ok, function(result) {
				if(result) deleteImage(id);
			});
		});

		// hook up delete clicks
		$("#ip_selected_videos").on("click",  "span.icon-minus", function(e) {
			e.preventDefault();
			var id = $(this).closest('li.ipselected').attr('id');
			bootbox.confirm(ipGalleryOptions.language.confirm, ipGalleryOptions.language.cancel, ipGalleryOptions.language.ok, function(result) {
				if(result) deleteVideo(id);
			});
		});

		// hook up save image form button
		$("#ip_image_form_save").click(function(e) {
			e.preventDefault();
			// trigger the saveimageTags function
			var id          = $("#imgid").val();
			var title       = $("#imgtitle").val();
			var description = $("#imgdescription").val();
			saveImageTags(id, title, description);
		});

		// hook up remote upload button
		$("#uploadRemoteGo").click(function() {
			var path = $('#uploadRemote').val();
			saveRemoteImage(path);
		});

		// attach sortable to ul
		$("#ip_selected_images").sortable({
			placeholder: "ip-state-highlight",
			update: function(event, ui) {
				saveImageOrder();
			}
		}).disableSelection();

		// hook up add avail clicks
		$("#ip_available_images").on("click",  "span.icon-plus, img.ipimage", function(e) {
			e.preventDefault();
			var el = $(this).closest('li.ipavail');

			if (el.hasClass('to-add')){
				// it's already been scheduled for adding, so remove it
				el.removeClass('to-add');
				// temporary code until we add a cool style for to-add class
				el.css('background', '');
                el.css('box-shadow', '');
				// get index of id then remove it
				var index = $.inArray(el.attr('id'), addimages);
				if (index != -1) addimages.splice(index, 1);
			} else {
				// add to-add class and add to collection
				addimages.push(el.attr('id'));
				el.addClass('to-add');
				// temporary code until we add a cool style for to-add class
				el.css('background', 'url('+ipGalleryOptions.ipbaseurl+'/components/com_iproperty/assets/images/tick.png) no-repeat 98% 98%');
                el.css('box-shadow', '0 0 10px #51A351');
			}
		});

		// hook up save avail image form button
		$("#ip_avail_form_save").click(function(e) {
			e.preventDefault();
			// trigger the saveimageTags function
			$.each( addimages, function(index, value){
				addAvailImage(value);
			});
		});

		// hook up avail image filter form
		$("#ip_avail_filter").change(function(e) {
			e.preventDefault();
			// trigger the saveimageTags function
			getAvailableImages();
		});

		// attach sortable to ul
		$("#ip_selected_images").sortable({
			placeholder: "ip-state-highlight",
			forcePlaceholderSize: true,
			helper: 'clone',
			update: function(event, ui) {
				saveImageOrder();
			}
		}).disableSelection();
		
	});
});
