/**********************************************************************
spot.tr primary javascript. 

Note (8/2/18)


/**********************************************************************/



var app = angular.module('spottr', []);
app.controller('spottrCntrl', function($scope, $http) {

		//Pulls loc_name, latitude, and longitude from our DB
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: { key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'select',
				table: 'Location',
				columns: ['loc_name','latitude','longitude']}
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			$scope.index = response.data.length;
			$scope.map_location = response.data[0]['loc_name'];
			$scope.map_latitude = response.data[0]['latitude'];
			$scope.map_longitude = response.data[0]['longitude'];
            LOCATIONS = response.data;
            addMapMarkers(response.data);
            console.log(LOCATIONS);
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
	});


function addMapMarkers(locations){

    console.log(locations)
    var avgLat = 0;
    var avgLong = 0;
    for (i = 0; i < locations.length; i++){
        avgLat += parseFloat(locations[i]['latitude']);
        avgLong += parseFloat(locations[i]['longitude']);
    }

    avgLat = avgLat/locations.length;
    avgLong = avgLong/locations.length;
    console.log(avgLat);
    console.log(avgLong);

    var map = new google.maps.Map(
        //document.getElementById('map'), {zoom: 10, center: new google.maps.LatLng(37.5949, -120.9577)});
        document.getElementById('map'), {zoom: 10, center: new google.maps.LatLng(avgLat, avgLong)});

    var marker, i;

    for (i = 0; i < locations.length; i++) {  

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(parseFloat(locations[i]['latitude']), parseFloat(locations[i]['longitude'])),
            map: map
            });

        var infowindow = new google.maps.InfoWindow({
              content: 'test info'
            });
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
                }
            })(marker, i));
    }
}