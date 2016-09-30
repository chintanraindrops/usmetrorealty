var ipMapFunctions = (function(app) {

	app.map = false;
	app.data = false;
	app.clusterLayer = false;
	app.infobox = false;

	app.buildMap = function(mapOptions) {
		
		app.map = new Microsoft.Maps.Map(
		document.getElementById('ip-map-canvas'), {
			credentials: mapOptions.credentials
		});
		
		app.map.setView({
			zoom: mapOptions.zoom,
			center: new Microsoft.Maps.Location(mapOptions.lat, mapOptions.lng)
		});
		
		Microsoft.Maps.Events.addThrottledHandler(app.map, 'viewchangeend', app.getAjaxResults, 100);
		
		// add idle event listener
        Microsoft.Maps.Events.addThrottledHandler(app.map, 'viewchangeend', function() {
			var bounds = app.map.getBounds();		
			// only do search if we DO NOT have an infowindow open-- otherwise it will keep re-running search!
			//if (!ipMapFunctions.infowindow.getOpenedState()){
				doToolSearch('viewportmove', bounds);
			//}
		});
		
		Microsoft.Maps.registerModule("ClientSideClusteringModule", mapOptions.ipbaseurl+"/components/com_iproperty/assets/advsearch/V7ClientSideClustering.min.js");
		Microsoft.Maps.loadModule("ClientSideClusteringModule", {
			callback: function() {
				app.clusterLayer = new ClusteredEntityCollection(app.map, {
					singlePinCallback: app.createPin,
					clusteredPinCallback: app.createClusteredPin
				});
				var infoboxLayer = new Microsoft.Maps.EntityCollection();
				app.infobox = new Microsoft.Maps.Infobox(new Microsoft.Maps.Location(0, 0), {
					visible: false,
					offset: new Microsoft.Maps.Point(0, 15)
				});
				infoboxLayer.push(app.infobox);
				app.map.entities.push(infoboxLayer);
			}
		});
		
		
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

	}

	function fakeData() {
		var d = [];
		for (var i = 0; i < app.data.length; i++) {
			d.push({
				cat_id: app.data[i].cat_id,
				id: app.data[i].id,
				Name: "Node" + i,
				Latitude: app.data[i].latitude,
				Longitude: app.data[i].longitude
			});
		}
		return d;
	}

	app.createPin = function(data) {	
		if (data.latitude == 0 || data.longitude == 0) return;
        // get marker if it exists
        var icon = cat_icons[data.cat_id];
        if (!icon || icon == 'nopic.png') {
			icon = mapOptions.ipbaseurl+mapOptions.marker_path+'icon56.png';
		} else {
			icon = mapOptions.ipbaseurl+'media/com_iproperty/categories/'+icon;
		}
		var pin = new Microsoft.Maps.Pushpin(data._LatLong, {icon: icon});
		pin.id = data.id;
		Microsoft.Maps.Events.addHandler(pin, 'click', displayEventInfo);
		return pin;
	}

	app.createClusteredPin = function(cluster, latlong) {
		var pin = new Microsoft.Maps.Pushpin(latlong, {
			text: "" + cluster.length + ""
		});
		//pin.title = "Cluster";
		//pin.description = "GridKey: " + cluster[0].GridKey + " Cluster Size: " + cluster.length + " Zoom in for more details.";
		Microsoft.Maps.Events.addHandler(pin, 'click', function() {
			app.map.setView({
				zoom: app.map.getZoom() + 1,
				center: app.map.getCenter()
			})
		});
		return pin;
	}

	function displayEventInfo(e) {
		if (e.targetType == "pushpin") {
			app.infobox.setLocation(e.target.getLocation());
			app.openMarker(e.target.id);
		}
	}
	
	
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
					app.clusterLayer.SetData(fakeData());
				} else {				
					// set no results button
					jQuery('#ip-advsearch-results').html(langOptions.no_results);
					// set map to default location
					app.map.setView({
						zoom: mapOptions.zoom,
						center: new Microsoft.Maps.Location(mapOptions.lat, mapOptions.lng)
					});
					return;
				}
			}
		});
    };	
	
	
	/* UTILITY FUNCTIONS */	
	
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
				//if (selectedShape) return;
				var N = options.getNorth();
				var S = options.getSouth();
				var E = options.getEast();
				var W = options.getWest();
				var SW_array = [ S, W ];
				var NE_array = [ N, E ];
				mapOptions.currentvals['geopoint']['sw'] = SW_array;
				mapOptions.currentvals['geopoint']['ne'] = NE_array;
				break;	
		}
		// now refresh data
		ipMapFunctions.getAjaxResults();
	}
	
    // clear markers from map
    app.clearMap = function(){
		//if (typeof app.mc !== 'undefined'){			
			// if we're both using marker clusters and one already has been created clear it
			//app.mc.clearMarkers();	
		//}

        // reset array
        //app.ipMarkers.length = 0;
        // reset bounds
        //app.bounds = new google.maps.LatLngBounds();
    };	
	
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
		
		jQuery('.ip_selector').each(function(){
			sel = jQuery(this).val();
            
            // Check if there's a default value set - if so, disable the filter to respect the menu
            // param defaults
            if(jQuery(this).attr('data-default')){                
                jQuery(this).attr('disabled', 'disabled');
            }
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
			jQuery(this).val(start_val);			
		});
		// now clear the shape if it exists
		//app.clearSelection();	// TODO: build shape tool support
	}
	
	// generic openmarker function
    app.openMarker = function(listing_id){		
        // get the data about this marker via ajax and display it
		jQuery.ajax({
			dataType: 'json',
			url: mapOptions.ipbaseurl+'index.php?option=com_iproperty&'+mapOptions.token+'=1&format=raw&task=ajax.ajaxGetListing&id='+listing_id,
			context: document.body
		}).done(function(tdata) {
			if (tdata.success){
				var content = ipMapFunctions.buildInfoWindow(tdata.data);
				app.infobox.setOptions({
					htmlContent: content,
					visible: true
				});
                app.toggleControls(false);
			}
		});
    };
	
	// build marker HTML
    app.buildInfoWindow = function(listing){
        // remove line breaks from banner
        listing.banner = listing.banner.replace(/(\r\n|\n|\r)/gm," ");
        var contentContainer = jQuery('<div />');
        var contentString = '<div class="row-fluid ip-bubble-window ip-bubble-window-solid">' +
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
	   var center = new Microsoft.Maps.Location(lat, lng);
	   app.map.setView({
		center: center,
		zoom: 17
	   });
	 }
	
    return app;
    
})(ipMapFunctions || {});
