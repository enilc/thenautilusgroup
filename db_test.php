<!DOCTYPE html>
<html>
<head>
	<style>
	.testPre {
	background-color: #1C3144;
	color: #FFFCF9; font-family: monospace;
	font-size: 16pt;
	padding: 5px 5px 5px 5px;
	width: 75%;
	}
	</style>
</head>
<?php
require 'db_connect.php';

function testDatabaseConnection(){
	$dbConn = connectToDatabase();
	$testString = 'C2B9264423F72EA0A78699BB9663EEA4E8647BC595B64501BDFA1429F54C4FAB';
	$count = 1;
	$passed = 0;

	$locId = 0;
	//Test 1: Try inserting something into the Locations Table
	$sql = "INSERT INTO spotter.Location (name, latitude,longitude) VALUES (:testString, 0.0, 0.0)";
	$stmt = $dbConn -> prepare($sql);
	if(!$stmt -> execute(array(':testString' => $testString))){
		echo "Test $count error: failed to insert into the databse. [spotter.locations]\n";
	} else {
		echo "Test $count PASSED:   Successfully INSERTed into spotter.Location\n";
		$passed += 1;
	}
	$count += 1;

	//Test 2: Check to see if our inserted Location is present.
	$sql = "SELECT * FROM spotter.Location as l WHERE l.name = :testString";
	$stmt = $dbConn -> prepare($sql);
	$status = $stmt -> execute(array(':testString' => $testString));
	$locationResults = $stmt->fetchAll();
	if(!$status){
		echo "Test $count error: failed to select from the databse. [spotter.locations]\n";
	} else if(strcmp($locationResults['0']['name'],$testString) != 0){
		echo "_Test $count error: failed to select from the databse. [spotter.locations]\n";
	} else {
		echo "Test $count PASSED:   Successfully SELECTed from spotter.Location\n";
		$passed += 1;
	}
	$count += 1;

	//Test 3: Try Inserting Something into our User Table.
	$sql = "INSERT INTO spotter.User (email, password, first_name, last_name) VALUES (:testString, :pw, 'John', 'Doe');";
	$stmt = $dbConn -> prepare($sql);
	if(!$stmt -> execute(array(':testString' => $testString, ':pw' => sha1('testing123')))){
		echo "Test $count error: failed to insert into the databse. [spotter.User]\n";
	} else {
		echo "Test $count PASSED:   Successfully INSERTed into spotter.User\n";
		$passed += 1;
	}
	$count += 1;

	//Test 4: Try inserting something into our Photo Table.
	$sql = "INSERT INTO spotter.Photo (path, user_email, location) VALUES ('http://www.path.to/file',:testString, :loc);";
	$stmt = $dbConn -> prepare($sql);
	if(!$stmt -> execute(array(':testString' => $testString, ':loc' => $locationResults['0']['location_id']))){
		echo "Test $count error: failed to insert into the database. [spotter.Photo]\n";
	} else {
		echo "Test $count PASSED:   Successfully INSERTed into spotter.Photo\n";
		$passed += 1;
	}
	$count += 1;

	//Test 5: Check to see if our Photo entry was added.
	$sql = "SELECT * FROM spotter.Photo as l WHERE l.user_email = :testString";
	$stmt = $dbConn -> prepare($sql);

	$status = $stmt -> execute(array(':testString' => $testString));
	if(!$status){
		echo "Test $count error: failed to select from the databse. [spotter.Photo]\n";
	} else if(strcmp($stmt->fetchAll()['0']['user_email'],$testString) != 0){
		echo "_Test $count error: failed to select from the databse. [spotter.Photo]\n";
	} else {
		echo "Test $count PASSED:   Successfully SELECTed from spotter.Photo\n";
		$passed += 1;
	}
	$count += 1;

	//Test 6: Attempt to Delete our Photo entry
	$sql = "DELETE FROM spotter.Photo WHERE user_email = :testString";
	$stmt = $dbConn -> prepare($sql);
	$status = $stmt -> execute(array(':testString' => $testString));

	if(!$status){
		echo "Test $count error: failed to delete records from the databse. [spotter.Photo]\n";
	} else {
		echo "Test $count PASSED:   DELETE command accepted for spotter.Photo\n";
		$passed += 1;
	}
	$count += 1;

	//Test 7: Make sure a record was actually deleted from the Photo table.
	if($stmt->rowCount() <= 0){
		echo "Test $count error: expected record not present for deletion. [spotter.Photo]\n";
	} else {
		echo "Test $count PASSED:   Expected records found in spotter.Photo\n";
		$passed += 1;
	}

	$count += 1;

	//Test 8: Attempt to delete our Location entry
	$sql = "DELETE FROM spotter.Location WHERE name = :testString";
	$stmt = $dbConn -> prepare($sql);
	$status = $stmt -> execute(array(':testString' => $testString));

	if(!$status){
		echo "Test $count error: failed to delete records from the databse. [spotter.locations]\n";
	} else {
		echo "Test $count PASSED:   DELETE command accepted for spotter.Location\n";
		$passed += 1;
	}
	$count += 1;

	//Test 9: Check to see if entries were actually deleted from the Location Table.
	if($stmt->rowCount() <= 0){
		echo "Test $count error: expected record not present for deletion. [spotter.locations]\n";
	} else {
		echo "Test $count PASSED:   Expected records found in spotter.Location\n";
		$passed += 1;
	}

	$count += 1;



	//Test 10: Check the for our inserted User entry
	$sql = "SELECT * FROM spotter.User as l WHERE l.email = :testString";
	$stmt = $dbConn -> prepare($sql);

	$status = $stmt -> execute(array(':testString' => $testString));
	if(!$status){
		echo "Test $count error: failed to select from the databse. [spotter.User]\n";
	} else if(strcmp($stmt->fetchAll()['0']['email'],$testString) != 0){
		echo "_Test $count error: failed to select from the databse. [spotter.User]\n";
	} else {
		echo "Test $count PASSED:   Successfully SELECTed from spotter.User\n";
		$passed += 1;
	}
	$count += 1;

	//Test 11: Check to see if we can delete our User entry
	$sql = "DELETE FROM spotter.User WHERE email = :testString";
	$stmt = $dbConn -> prepare($sql);
	$status = $stmt -> execute(array(':testString' => $testString));

	if(!$status){
		echo "Test $count error: failed to delete records from the databse. [spotter.User]\n";
	} else {
		echo "Test $count PASSED:   DELETE command accepted for spotter.User\n";
		$passed += 1;
	}
	$count += 1;

	//Test 12: Confirm that something was actually deleted from the User table.
	if($stmt->rowCount() <= 0){
		echo "Test $count error: expected record not present for deletion. [spotter.User]\n";
	} else {
		echo "Test $count PASSED:   Expected records found in spotter.User\n";
		$passed += 1;
	}

	$count += 1;



/////////////////////////////////////////////////////////////////////////////
	$count -= 1;
	echo "<p>Passed $passed/$count tests</p>";

}


 ?>


<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<body>
	<h1> Spotter App Database Test Page </h1>
	<pre class='testPre'><?php
//testDatabaseConnection()
	?>
	</pre>

	<h2> Angular/db_interface.php test</h2>
<div ng-app="testDatabaseInterface" ng-controller="dbInterfaceController">
  <p>db_interface.php tests</p>
  <pre class='testPre'>
  	Test 1 {{basic_connectivity}}: Database Interface Basic Connectivity 
  	Test 2 {{key_test}}: Database Interaction Key Test
  	Test 3 {{insert_test}}: SQL INSERTion test.
  	Test 4 {{select_test}}: SQL SELECT test.

  </pre>
</div>

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
      			key_test: 'true'}
        }).then(function successCallback(response) {
            // this callback will be called asynchronously
            // when the response is available
            ((response.data == 'Key Test Passed') ? $scope.key_test = 'Passed' : $scope.key_test = 'FAILED');

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
				columns: ['name','latitude','longitude'],
				values: [testString,0.0,0.0] }
		}).then(function successCallback(response) {
			// this callback will be called asynchronously
			// when the response is available
			((response.data == 'success') ? $scope.insert_test = 'Passed' : $scope.insert_test = 'FAILED');
			        //Test 4: Test SELECT function . Should recieve "success" back.
			$http({
			method: 'POST',
			url: 'db_interface.php',
			headers: {
			'Content-Type': 'application/json'
			},
			data: { key: 'B52C106C63CB00C850584523FB0EC12',
					action: 'select',
					table: 'Location',
					columns: ['name','latitude','longitude'],
					filter: {'name': testString} }
			}).then(function successCallback(response) {
				// this callback will be called asynchronously
				// when the response is available
				((response.data[0]['name'] == testString && response.data[0]['latitude'] == 0.00000000 && response.data[0]['longitude'] == 0.00000000) ? $scope.select_test= 'Passed' : $scope.select_test = 'FAILED');
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


    });
</script>
</body>
</html>