
	// Initialize and add the map
	function initMap() {
		
	 var contentString = '<strong>Modesto Center Plaza</strong><br>\
							1000 L St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/dv8taNu62zq">Get Directiongs</a>';
	
	// Modesto Center Plaza
	var modestoCenter = {info: 'modestoCenter', lat: 37.642187, lng: -121.001921};
	
	//The Century
	var theCentury = {lat: 37.639793, lng: -120.999968};
	
	//Donnelly park
	var donnellyPark = {lat: 37.510958 , lng: -120.856358};
	
	// The map, centered at modesto Center plaza
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10, 
		center: modestoCenter
		});
	
	
	// The marker, positioned at modestoCenter
	var marker = new google.maps.Marker({position: modestoCenter, map: map, content: contentString});
	//
	var marker = new google.maps.Marker({position: theCentury, map: map});
	
	var marker = new google.maps.Marker({position: donnellyPark, map: map});

	var infowindow = new google.maps.InfoWindow({
          content: contentString
        });

	marker.addListener('click', function() {
          infowindow.open(map, marker);
        });
		

	
}

	function getLocation() {
		var name, lats, longs;
	
	}