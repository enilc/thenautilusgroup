      // Note: This example requires that you consent to location sharing when
      // prompted by your browser. If you see the error "The Geolocation service
      // failed.", it means you probably did not give permission for the browser to
      // locate you.
      var map, infoWindow;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });
        var contentStringA = '<strong>Modesto Center Plaza</strong><br>\
							1000 L St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/dv8taNu62zq">Get Directiongs</a><br>\
							<img width= 250 src="https://www.modbee.com/latest-news/2tho7s/picture4071347/alternates/LANDSCAPE_1140/IMG_Modesto_Centre_Plaza_2_1_PMPR8F9_L17591618.JPG">'

        	// Modesto Center Plaza
	      var modestoCenter = {info: 'modestoCenter', lat: 37.642187, lng: -121.001921};
        // marker positioned at modestoCenter
	      var markerA = new google.maps.Marker({position: modestoCenter, map: map, content: contentStringA});
        	// info window for modestoCenter
	      var infowindowA = new google.maps.InfoWindow({
        content: contentStringA
        });
        
        markerA.addListener('click', function() {
          $('#myModal').modal('show')
          });
        /***markerA.addListener('click', function() {
          infowindowA.open(map, markerA);
        });***/

        infoWindow = new google.maps.InfoWindow;
        
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            //infoWindow.setContent('You are here!');
            //infoWindow.open(map);
            map.setCenter(pos);
          
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }
      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);

      }



