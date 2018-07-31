
	// Initialize and add the map
	function initMap() {
	
/******** content for locations*******/
	 var contentStringA = '<strong>Modesto Center Plaza</strong><br>\
							1000 L St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/dv8taNu62zq">Get Directiongs</a><br>\
							<img width= 250 src="https://www.modbee.com/latest-news/2tho7s/picture4071347/alternates/LANDSCAPE_1140/IMG_Modesto_Centre_Plaza_2_1_PMPR8F9_L17591618.JPG">';
	
	 var contentStringB = '<strong>Caswell Memorial State Park</strong><br>\
							28000 S Austin Rd<br> Ripon, CA 95366<br>\
							<a href="https://goo.gl/maps/U3qrRaq4UBm">Get Directiongs</a><br>\
							<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRJAo4XuAtivT0SGMDPNU_iQaWnZpq93sNyLFg6hDoLx-7sRTdY">';
	 
	 var contentStringC = '<strong>Donnelly Park</strong><br>\
							600 Pedras Rd<br> Turlock, CA 95382<br>\
							<a href="https://goo.gl/maps/kpttSy898Cq">Get Directiongs</a><br>\
							<img width=250 src="https://img00.deviantart.net/dec8/i/2016/048/9/b/donnelly_park_pond_2_by_sakaphotogrfx-d9s32nq.jpg">';
	
	
/************ Locations ********/
	// Modesto Center Plaza
	var modestoCenter = {info: 'modestoCenter', lat: 37.642187, lng: -121.001921};
	
	//Caswell Memorial State Park
	var caswellPark = {lat: 37.695933, lng: -121.182218};
	
	//Donnelly park
	var donnellyPark = {lat: 37.510958 , lng: -120.856358};
	
	// The map, centered at modesto Center plaza
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 10, 
		center: modestoCenter
		});
	
/*********** Markers *************/	
	
	// marker positioned at modestoCenter
	var markerA = new google.maps.Marker({position: modestoCenter, map: map, content: contentStringA});
	
	// marker position at caswellPark
	var markerB = new google.maps.Marker({position: caswellPark, map: map, content: contentStringB});
	
	// marker positioned at donnellyPark
	var markerC = new google.maps.Marker({position: donnellyPark, map: map, content: contentStringC});

/************* Info Windows *********/	
	
	// info window for modestoCenter
	var infowindowA = new google.maps.InfoWindow({
          content: contentStringA
        });

	markerA.addListener('click', function() {
          infowindowA.open(map, markerA);
        });
	// info window for caswellPark
	var infowindowB = new google.maps.InfoWindow({
		content: contentStringB
	});
	
	markerB.addListener('click', function()	{
		infowindowB.open(map, markerB);
	});
	
	// info window for donnellyPark
	var infowindowC = new google.maps.InfoWindow({
		content: contentStringC
	});
	
	markerC.addListener('click', function()	{
		infowindowC.open(map, markerC);
	});
	
}

/**** Future functiont to get location latitutde and Longtitude ****/

	function getLocation() {
		var locname, lats, longs;
	
	}
	

