
	// Initialize and add the map
	function initMap() {
	
/******** content for locations*******/
	 var contentStringA = '<strong>Modesto Center Plaza</strong><br>\
							1000 L St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/dv8taNu62zq">Get Directiongs</a><br>\
							<img width= 250 src="https://www.modbee.com/latest-news/2tho7s/picture4071347/alternates/LANDSCAPE_1140/IMG_Modesto_Centre_Plaza_2_1_PMPR8F9_L17591618.JPG">';

							

							
	
	
/************ Locations ********/
	// Modesto Center Plaza
	var modestoCenter = {info: 'modestoCenter', lat: 37.642187, lng: -121.001921};

	
	// The map, centered at modesto Center plaza
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 2, 
		center: modestoCenter
		});
	
/*********** Markers *************/	
	
	// marker positioned at modestoCenter
	var markerA = new google.maps.Marker({position: modestoCenter, map: map, content: contentStringA});

	
/************* Info Windows *********/	
	
	// info window for modestoCenter
	var infowindowA = new google.maps.InfoWindow({
          content: contentStringA
        });

	markerA.addListener('click', function() {
          infowindowA.open(map, markerA);
        });


/**** Future functiont to get location latitutde and Longtitude ****/

/*
	function getLocation() {
		
		var locname = document.getElementById("locname");
		
		var lat = parseFloat(document.getElementById('lats').value);
		
		var lng = parseFloat(document.getElementById('longs').value);
		
		var secondLoc = {lat, lng};
		var secMark = new google.maps.Marker({position: secondLoc, map: map});
		
	
	}
	
	function getLocation()	{
		
	var locname = document.getElementById("locname");
		

	var secondLoc = {lat = document.getElementById("lats").value, lng: document.getElementById("longs").value};
	var secMark = new google.maps.Marker({position: secondLoc, map: map, content: locname});
	}}*/
	
	function updateMarkerAddress(str) {
		document.getElementById('locname').innerHTML = str;
	}
	
	function moveMarker() {
		var lat = parseFloat(document.getElementById('lats').value);
		var lng = parseFloat(document.getElementById('longs').value);
		var newLatLng = new google.maps.LatLng(lat, lng);
		marker.setPosition(newLatLng)
	}
	
	google.maps.event.addDomListener(window, 'load', initialize);

	
}