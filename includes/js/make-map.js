
if (document.getElementById("map")) {
	function initialize() {
		var map = new google.maps.Map(document.getElementById("map"), {
		  center: {
		    lat: parseFloat(EazyPhotoMap.latitude),
		    lng: parseFloat(EazyPhotoMap.longitude),
		  },
		  zoom: 15
		});

		var marker = new google.maps.Marker({
		  position: {lat: parseFloat(EazyPhotoMap.latitude), lng: parseFloat(EazyPhotoMap.longitude)},
		  map: map
		});

		var infowindow = new google.maps.InfoWindow();
	}
	google.maps.event.addDomListener(window, "load", initialize);
}

if (document.getElementById("map-full")) {
	function initialize() {
		//set map background position, zoom & other options
	
	var map = new google.maps.Map(document.getElementById("map-full"), {
	  center: {
	    lat: parseFloat(EazyMapBG.latitude),
	    lng: parseFloat(EazyMapBG.longitude),
	  },
	  zoom: 11,
	  disableDefaultUI: true,
	  clickableIcons: false
	});
	

	var locations = EazyPhotoMap;
	var infowindow = new google.maps.InfoWindow();
	var marker, i;
	

    for (i = 0; i < locations.length; i++) {  
		  
	marker = new google.maps.Marker({
	  position: new google.maps.LatLng(locations[i].latitude, locations[i].longitude),
	  map: map
	});

		


    google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
        var windowContent = '<div id="map-window-content">'+
        '<a href="'+locations[i].link+'">'+
        '<img class="fullmap-infowindow-img" src="'+locations[i].img+'">'+
		'<h2 class="fullmap-infowindow-title">'+locations[i].title+'</h2>'+
		'</a>'+
		'</div>';
          infowindow.setContent(windowContent);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }




	}
	google.maps.event.addDomListener(window, "load", initialize);

}