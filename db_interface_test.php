<?php 

    require_once('user_funct.php');

    //We check to see if we have a passed session id
    if(isset($_GET['sid'])){
        //We make sure the session exists.
        $passedSID = session_id($_GET['sid']);
        session_start();
    } 
    //Pickup sessions without a GET SID.
    if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) { //We don't have a session to use
        header('Location: login.php');
    } 
    
?>

<?php 
require_once('db_connect.php');
?>

<!DOCTYPE html>
<html>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
</head>

<body>

<h1>DB Interface Test</h1>

<script>
    var app = angular.module('testDatabaseInterface', []);
    app.controller('dbInterfaceController', function($scope, $http) {
    	var testString = 'C2B9264423F72EA0A78699BB9663EEA4E8647BC595B64501BDFA1429F54C4FAB';
    	//Test 1: Test basic connection. We should get the same data back.
        $http({
          method: 'POST',
          url: 'db_interface.php',
          headers: {
            'Content-Type': 'application/json'
            },
          data: {test: 'testing345'}
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            ((response.data == 'testing345') ? $scope.basic_connectivity = 'Passed' : $scope.basic_connectivity = 'FAILED');

          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            $scope.basic_connectivity = "FAILED (callback)";
          });

        //Test 2: Test key test. We should get "key test passed" back
        $http({
          method: 'POST',
          url: 'db_interface.php',
          headers: {
            'Content-Type': 'application/json'
            },
          data: {key: 'B52C106C63CB00C850584523FB0EC12',
      			action: 'key_test'}
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            ((response.data == 'Key Test Passed') ? $scope.key_test = 'Passed' : $scope.key_test = 'FAILED');
            console.log(response.data);

          }, function errorCallback(response) {
            // called asynchronously if an error occurs
            // or server returns response with an error status.
            $scope.key_test = "FAILED (callback)";
          });

        //Test 3: Test INSERT function . Should recieve "success" back.
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: {key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'insert',
				table: 'Location',
				columns: ['loc_name','latitude','longitude'],
				values: [testString,0.0,0.0] }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'success') ? $scope.insert_test = 'Passed' : $scope.insert_test = 'FAILED');
			

			//Test 4: Test SELECT function . Should recieve "success" back. Nested here because It must execute AFTER Test 3 finishes.
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
				$scope.map_location = response.data[0]['loc_name'];
				$scope.map_latitude = response.data[0]['latitude'];
				$scope.map_longitude = response.data[0]['longitude'];
			}, function errorCallback(response) {
				// called asynchronously if an error occurs
				// or server returns response with an error status.
				$scope.select_test = "FAILED (callback)";
			});

		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.insert_test = "FAILED (callback)";
		});

		//Test 5: Blocking Code Injection (See the "DELETE" in table)
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: { key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'select',
				table: 'DELETE',
				columns: ['loc_name','latitude','longitude'],
				filter: {'loc_name': testString} }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'Invalid Table Name') ? $scope.injection_table_name= 'Passed' : $scope.injection_table_name = 'FAILED');
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
		//Test 6: Test code injection through columns. See "DELETE" in columns.
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: { key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'select',
				table: 'Location',
				columns: ['DELETE','latitude','longitude'],
				filter: {'loc_name': testString} }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'Invalid Column Names') ? $scope.injection_column_name= 'Passed' : $scope.injection_column_name = 'FAILED');
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
		//Test 7: Same as 5, only testing INSERT
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: {key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'insert',
				table: 'DELETE',
				columns: ['loc_name','latitude','longitude'],
				values: [testString,0.0,0.0] }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'Invalid Table Name') ? $scope.injection_table_name_insert= 'Passed' : $scope.injection_table_name_insert = 'FAILED');
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
		//Test 8: Same as 6, only testing INSERT
		$http({
		method: 'POST',
		url: 'db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: {key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'insert',
				table: 'Location',
				columns: ['DROP','latitude','longitude'],
				values: [testString,0.0,0.0] }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'Invalid Column Names') ? $scope.injection_column_name_insert= 'Passed' : $scope.injection_column_name_insert = 'FAILED');
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
});
</script>

<p>
<pre class='testPre' ng-app="testDatabaseInterface" ng-controller="dbInterfaceController">
Map Location: {{map_location}}
Map Latitude: {{map_latitude}}
Map Longitude: {{map_longitude}}

<script>
// Initialize and add the map
var mapLocation = {{map_location}};
var mapLatitude = {{map_latitude}};
var mapLongitude = {{map_longitude}};

console.log(mapLocation);
console.log(mapLatitude);
console.log(mapLongitude);

function initMap() {
 // The location of Uluru used Nicaragua but we can change
 var mapLocation = {lat: mapLatitude, lng: mapLatitude};
 // The map, centered at Uluru
 console.log('got here');
 var map = new google.maps.Map(

     document.getElementById('map'), {zoom: 4, center: mapLocation});
 // The marker, positioned at Uluru
 var marker = new google.maps.Marker({position: mapLocation, map: map});
}
</script>
   <!--Load the API from the specified URL
   * The async attribute allows the browser to render the page while the API loads
   * The key parameter will contain your own API key (which is not needed for this tutorial) I setup a key in geocoding api
   * The callback parameter executes the initMap() function
   -->
<script async defer
   src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxnMU9bIct86r3W4-rJ22Sirsli0U3uH4&callback=initMap">
</script>

</pre>

</p>
</body>
</html>