/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

var ipMapFunctions = (function(app) {
	/* first override maps prototype for infowindow to add isOpen method */
	google.maps.InfoWindow.prototype._open = google.maps.InfoWindow.prototype.open;
	google.maps.InfoWindow.prototype._close = google.maps.InfoWindow.prototype.close;
	google.maps.InfoWindow.prototype._openedState = false;

	google.maps.InfoWindow.prototype.open =
		function (map, anchor) {
			this._openedState = true;
			this._open(map, anchor);
		};

	google.maps.InfoWindow.prototype.close =
		function () {
			this._openedState = false;
			this._close();
		};

	google.maps.InfoWindow.prototype.getOpenedState =
		function () {
			return this._openedState;
		};

	google.maps.InfoWindow.prototype.setOpenedState =
		function (val) {
			this._openedState = val;
		};
	/* end infowindow hack */
	
	
    // define vars
    app.ipMarkers	= [];
	app.data		= false; 
	app.center		= false;

    app.buildMap = function(mapOptions){
        var maptype, infoWindow;
        switch (mapOptions.maptype){
            case 'SATELLITE':
                maptype = google.maps.MapTypeId.SATELLITE;
                break;
            case 'TERRAIN':
                maptype = google.maps.MapTypeId.TERRAIN;
                break;
            case 'ROADMAP':
                maptype = google.maps.MapTypeId.ROADMAP;
                break;
            case 'HYBRID':
            default:
                maptype = google.maps.MapTypeId.HYBRID;
                break;
        }

        var mapInitOpts = {
            center: new google.maps.LatLng(mapOptions.lat, mapOptions.lng),
            zoom: mapOptions.zoom,
            mapTypeId: maptype,
            maxZoom: mapOptions.maxZoom
        };
        
        // set the default center
        ipMapFunctions.center = mapInitOpts.center;

        // clear existing map if it exists from cache
        jQuery("#ip-map-canvas").empty();
        // create map
        app.map = new google.maps.Map(document.getElementById("ip-map-canvas"), mapInitOpts);
        google.maps.event.trigger(ipMapFunctions.map, 'resize');
        
        // check if we have saved search bounds
        if (typeof saved_bounds !== 'undefined') {
			app.bounds = saved_bounds;
			app.map.fitBounds(saved_bounds);
		} else {
			app.bounds = new google.maps.LatLngBounds();
		}
        
        // add idle event listener
        google.maps.event.addListener(app.map, 'idle', function() {
			var bounds = app.map.getBounds();		
			// only do search if we DO NOT have an infowindow open-- otherwise it will keep re-running search!
			if (!ipMapFunctions.infowindow.getOpenedState()){
				doToolSearch('viewportmove', bounds);
			}
		});

        // set resize window event to reset map when window changed
        jQuery(window).resize(function() {
            google.maps.event.trigger(ipMapFunctions.map, 'resize');
        });

        // build infoWindow
        app.infowindow = new google.maps.InfoWindow({
            content: ''
        });
        
        // monitor the closelick event and set opened state false when the close
		// button is clicked.
		(function (w) {
			google.maps.event.addListener(w, 'closeclick', function (e) {
				w.setOpenedState(false);
			});
		})(ipMapFunctions.infowindow);
		
		// build map tools
		if(mapOptions.maptools){
			ipMapFunctions.buildTools();
		}
		
		// attach clear map function
		jQuery('#ip-advsearch-clear').click( function(){
			ipMapFunctions.clearMap();
			ipMapFunctions.clearSelected();
			ipMapFunctions.getAjaxResults();
		});
		
		// attach locate button event
		jQuery('#iproperty_locate').click( function(){
			ipMapFunctions.getCurrentLocation();
		});
		
		// attach save search button 
        if (mapOptions.savesearch){
            jQuery('#iproperty_save').click(function(e){
                jQuery('#ipMapSavePanel').toggle('fast');
            });
                        
            // change form submit actions 
            jQuery('#ipsaveProperty').submit(function(e) {			
				e.preventDefault(); // don't submit the form
				// get current vals
				ipMapFunctions.getSelected();
				var data = {
					"task": "ajax.saveSearch",
					"userid": mapOptions.user_id,
					"ipsearchstring": JSON.stringify(mapOptions.currentvals),
					"notes": jQuery('#notes').val()					
				};
				// add token to data payload
				data[mapOptions.token] = 1;
				// make ajax request
				jQuery.post(mapOptions.ajaxroute, data, function(data){					
						// set message div notice					
						Joomla.renderMessages(data.messages);
						jQuery('#ipMapSavePanel').toggle('fast');
					},
					'json'
					);
			});
        }
    }

    /**************
    // functions //
    **************/

    // addMarker to map
    app.addMarker = function(listing){				
        if (listing.latitude == 0 || listing.longitude == 0) return;
        // get marker if it exists
        var icon = cat_icons[listing.cat_id];
        if (!icon || icon == 'nopic.png') {
			icon = mapOptions.ipbaseurl+mapOptions.marker_path+'icon56.png';
		} else {
			icon = mapOptions.ipbaseurl+'media/com_iproperty/categories/'+icon;
		}
        
        var markerOptions = {};
        // force clustering
		markerOptions.prop_id = listing.id;
        markerOptions.position = new google.maps.LatLng(parseFloat(listing.latitude), parseFloat(listing.longitude));
        if (mapOptions.marker_path) markerOptions.icon = icon;
        var marker = new google.maps.Marker(markerOptions);
        // add marker to array
        ipMapFunctions.ipMarkers.push(marker);
        // extend map bounds
        //ipMapFunctions.bounds.extend(markerOptions.position);
        // attach infoWindow opener
        google.maps.event.addListener(marker, 'click', function () {			
            ipMapFunctions.openMarker(this);
        });
    };

    // clear markers from map
    app.clearMap = function(){
		if (typeof app.mc !== 'undefined'){			
			// if we're both using marker clusters and one already has been created clear it
			app.mc.clearMarkers();	
		}

        // reset array
        app.ipMarkers.length = 0;
        // reset bounds
        app.bounds = new google.maps.LatLngBounds();
    };
    
    // generic openmarker function
    app.openMarker = function(marker){
		var requestdata = {
			'task': 'ajax.ajaxGetListing',
			'id': marker.prop_id
		};
		requestdata[mapOptions.token] = 1;
		
        // get the data about this marker via ajax and display it
		jQuery.ajax({
			dataType: 'json',
			url: mapOptions.ajaxroute,
			context: document.body,
			data: requestdata,
			type: 'GET'
		}).done(function(tdata) {
			if (tdata.success){
				var content = ipMapFunctions.buildInfoWindow(tdata.data);
				ipMapFunctions.infowindow.setContent(content);
				ipMapFunctions.infowindow.open(ipMapFunctions.map, marker);
                app.toggleControls(false);
                window.iptoolsshown = false
			}
		});
    };
	
	app.getAjaxResults = function (){		
		// clear the map, then get data
		ipMapFunctions.clearMap();	
		// grab all selected values
		ipMapFunctions.getSelected();		
        var options = {
            'searchvars': mapOptions.currentvals,
            'itemid': mapOptions.itemid,
            'limit': searchOptions.limit,
            'task': 'ajax.ajaxSearch2'
        };
		jQuery.ajax({
			dataType: 'json',
			data: options,
			url: mapOptions.ipbaseurl+'index.php?option=com_iproperty&'+mapOptions.token+'=1&format=raw',
			context: document.body
		}).done(function(tdata) {
			if (tdata.success){
				ipMapFunctions.data = tdata.data;
				// set results
				if (ipMapFunctions.data.length) {					
					jQuery('#ip-advsearch-results').html(ipMapFunctions.data.length + ' ' + langOptions.results);
					jQuery.each(ipMapFunctions.data, function(i, e){
						ipMapFunctions.addMarker(e);
					});

					//var mcOptions = { gridSize: 50, maxZoom: 15 };
					var mcOptions 	= {};
					app.mc = new MarkerClusterer(app.map, ipMapFunctions.ipMarkers, mcOptions);

				} else {				
					// set no results button
					jQuery('#ip-advsearch-results').html(langOptions.no_results);
					// set map to default location
					app.map.setCenter(ipMapFunctions.center);
					return;
				}
			}
		});
    };
    
    // MAP TOOLS
    var selectedShape, drawingManager;
	// set options for display
    app.buildTools = function(){
		var polyOptions = {
          strokeWeight: 0,
          fillOpacity: 0.45,
		  fillColor: '#1E90FF',
          editable: true
        };
        // initialize drawingmanager object
        drawingManager = new google.maps.drawing.DrawingManager({
			// drawing controls options
			drawingControlOptions: {
				drawingModes: [
				  google.maps.drawing.OverlayType.CIRCLE,
				  //google.maps.drawing.OverlayType.POLYGON,
				  google.maps.drawing.OverlayType.RECTANGLE
				]
            },
            rectangleOptions: polyOptions,
            circleOptions: polyOptions,
            //polygonOptions: polyOptions,
            map: ipMapFunctions.map
		}); 
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
            if (e.type !== google.maps.drawing.OverlayType.MARKER) {
                // Switch back to non-drawing mode after drawing a shape.
                drawingManager.setDrawingMode(null);

                // Add an event listener that selects the newly-drawn shape when the user
                // mouses down on it.
                var newShape = e.overlay;
                newShape.type = e.type;
                google.maps.event.addListener(newShape, 'click', function() {
                  setSelection(newShape);
                });
                setSelection(newShape);
            }
        });	
		google.maps.event.addListener(drawingManager, 'drawingmode_changed', ipMapFunctions.clearSelection);
        google.maps.event.addListener(ipMapFunctions.map, 'click', ipMapFunctions.clearSelection);		
		google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
			switch (event.type) {
				case google.maps.drawing.OverlayType.CIRCLE:
					var radius = event.overlay.getRadius(); // returned in meters!! 
					var opts = { center: event.overlay.getCenter(), radius: radius };
					doToolSearch('circle', opts);
					break;
				case google.maps.drawing.OverlayType.POLYGON:
					var path = event.overlay.getPath(); 
					doToolSearch('polygon', path);
					break;
				case google.maps.drawing.OverlayType.RECTANGLE:
					var bounds = event.overlay.getBounds(); 
					doToolSearch('rectangle', bounds);
					break;	
			} 
		});
        google.maps.event.addListener(drawingManager, 'circlecomplete', function (circle){
            google.maps.event.addListener(circle, 'radius_changed', function () {
                var radius = circle.getRadius(); // returned in meters!! 
                var opts = { center: circle.getCenter(), radius: radius };
                doToolSearch('circle', opts);
            });
            google.maps.event.addListener(circle, 'center_changed', function () {
                var radius = circle.getRadius(); // returned in meters!! 
                var opts = { center: circle.getCenter(), radius: radius };
                doToolSearch('circle', opts);
            });
        });
        google.maps.event.addListener(drawingManager, 'rectanglecomplete', function (rectangle){
            google.maps.event.addListener(rectangle, 'bounds_changed', function () {
                var bounds = rectangle.getBounds(); 
                doToolSearch('rectangle', bounds);
            });
        });
	};
		 
	// utility methods
	app.clearSelection = function() {
		if (selectedShape) {
			selectedShape.setEditable(false);
			selectedShape.setMap(null);
			selectedShape = null;
			mapOptions.currentvals['geopoint'] = {};
			// after clearing the shape, redo search
			google.maps.event.trigger(app.map, 'idle');
			app.map.setZoom(mapOptions.zoom);
		}
	};

	function setSelection(shape) {
		ipMapFunctions.clearSelection();
		selectedShape = shape;
		shape.setEditable(true);
	}
	
	function doToolSearch(type, options){
		mapOptions.currentvals['geopoint'] = {};
		switch(type){
			case 'circle':
				mapOptions.currentvals['geopoint']['lat'] = options.center.lat();
				mapOptions.currentvals['geopoint']['lon'] = options.center.lng();
				mapOptions.currentvals['geopoint']['rad'] = (options.radius / 1000); // convert meters to km
				break;
			case 'polygon':
				var paths = [];
				for (var i = 0; i < options.length; i++){
					var point = options.getAt(i);
					paths.push({ lat: point.lat(), lon: point.lng() });	
				}
				//mapOptions.currentvals['geopoint']['paths'] = paths;
				//console.dir(paths);
				break;
			case 'rectangle':
				var SW = options.getSouthWest();
				var NE = options.getNorthEast();
				var SW_array = [ SW.lat(), SW.lng() ];
				var NE_array = [ NE.lat(), NE.lng() ];
				mapOptions.currentvals['geopoint']['sw'] = SW_array;
				mapOptions.currentvals['geopoint']['ne'] = NE_array;
				break;
			case 'viewportmove':
				if (selectedShape) return;
				var SW = options.getSouthWest();
				var NE = options.getNorthEast();
				var SW_array = [ SW.lat(), SW.lng() ];
				var NE_array = [ NE.lat(), NE.lng() ];
				mapOptions.currentvals['geopoint']['sw'] = SW_array;
				mapOptions.currentvals['geopoint']['ne'] = NE_array;
				break;	
		}
		// now refresh data
		ipMapFunctions.getAjaxResults();
	}
	
	app.getSelected = function(){	
		var sel;
		mapOptions.currentvals.sliders.price = {};
		mapOptions.currentvals.sliders.price.min = []; 
		mapOptions.currentvals.sliders.price.max = []; 
		mapOptions.currentvals.sliders.beds = {};
		mapOptions.currentvals.sliders.beds.min = []; 
		mapOptions.currentvals.sliders.beds.max = [];
		mapOptions.currentvals.sliders.baths = {};
		mapOptions.currentvals.sliders.baths.min = []; 
		mapOptions.currentvals.sliders.baths.max = [];
		mapOptions.currentvals.sliders.sqft = {};
		mapOptions.currentvals.sliders.sqft.min = []; 
		mapOptions.currentvals.sliders.sqft.max = [];
		mapOptions.currentvals.categories = [];
		mapOptions.currentvals.property['stype'];
		mapOptions.currentvals.type = 2;
		
		jQuery('.ip_selector').each(function(){
			sel = jQuery(this).val();
            
            // Check if there's a default value set - if so, disable the filter to respect the menu
            // param defaults
            //if(jQuery(this).attr('data-default')){                
            //    jQuery(this).attr('disabled', 'disabled');
            //}
			switch(this.id){
				case 'cat':				
					if (sel instanceof Array){
						jQuery(sel).each(function(i, v){					
							if (sel !== null) mapOptions.currentvals['categories'].push(v);
						});
					} else {
						if (sel !== null) mapOptions.currentvals['categories'].push(sel);
					}
				break;
				case 'stype':				
					if (sel instanceof Array){
						jQuery(sel).each(function(i, v){					
							mapOptions.currentvals.property['stype'] = [];
							if (sel !== null) mapOptions.currentvals.property.stype.push(v);
						});
					} else {
						if (sel !== null) mapOptions.currentvals.property['stype'] = sel;
					}
				break;
				case 'price_max':
					if (sel !== null) mapOptions.currentvals.sliders.price.max = sel;
				break;
				case 'price_min':
					if (sel !== null) mapOptions.currentvals.sliders.price.min = sel;
				break;
				case 'beds':
					if (sel !== null) mapOptions.currentvals.sliders.beds.min = sel;
				break;
				case 'baths':
					if (sel !== null) mapOptions.currentvals.sliders.baths.min = sel;
				break;
				case 'sqft':
					if (sel !== null) mapOptions.currentvals.sliders.sqft.min = sel;
				break;
			}			
		});				
	};
	
	app.clearSelected = function(){
		jQuery('.ip_selector').each(function(){
			// clear each selector
			var start_val = jQuery(this).data('default');
			if (this.name == 'price_max') {
				start_val = 'max';
			} else if (this.name == 'price_min'){
				start_val = 'min';
			}
			jQuery(this).val(start_val);			
		});
		// now clear the shape if it exists
		app.clearSelection();	
	}
	
	// build marker HTML
    app.buildInfoWindow = function(listing){
        // remove line breaks from banner
        listing.banner = listing.banner.replace(/(\r\n|\n|\r)/gm," ");
        var contentContainer = jQuery('<div />');
        var contentString = '<div class="row-fluid ip-bubble-window">' +
                                '<div class="span5 ip-overview-img"><div class="ip-property-thumb-holder"><a href="'+listing.proplink+'">'+listing.thumb+'</a>'+listing.banner+'</div></div>' +
                                '<div class="span7">' +
                                '<h4><a href="'+listing.proplink+'">'+listing.street_address+', '+listing.city+'</a></h4>' +
                                '<div class="small"><strong>'+langOptions.pid+': </strong>'+listing.mls_id+' | <strong>'+langOptions.price+': </strong>'+listing.formattedprice+'</div>' +
                                '<p class="ip-bubble-desc">'+listing.short_description.slice(0,185).trim()+'...'+'<div class="ip-bubble-cats">'+listing.caticons.join(' ')+'</div><a href="'+listing.proplink+'">('+langOptions.more+')</a></p>' +
                                '</div>' +
                            '</div>';
        contentContainer.html(contentString);
        return contentContainer.html();
    };
    
    // toggle controls slider
    app.toggleControls = function(show){
        var leftPx = (show) ? 0 : -501;
        var oldIcon = (show) ? 'right' : 'left';
        var newIcon = (show) ? 'left' : 'right';

        jQuery('#ip-mapcontrols').animate({
            left: leftPx+'px'
        }, 'fast', 'linear',
        function () {
            jQuery('#ip-mapcontrol-show').removeClass('icon-chevron-'+oldIcon).addClass('icon-chevron-'+newIcon);
        });
    }
    
    // geolocation interface
    app.getCurrentLocation = function(){
		navigator.geolocation.getCurrentPosition(locationHandler);
	}
	
	function locationHandler(position){
	   var lat = position.coords.latitude;
	   var lng = position.coords.longitude;
	   var center = new google.maps.LatLng(lat, lng);
	   app.map.setCenter(center);
	   app.map.setZoom(17);
	 }
	
    return app;
})(ipMapFunctions || {});
