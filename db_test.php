<!DOCTYPE html>
<html>
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
	<pre style='background-color: #1C3144; color: #FFFCF9; font-family: monospace; font-size: 16pt; padding: 5px 5px 5px 5px; width: 75%;'><?php
testDatabaseConnection()
	?>
	</pre>

	<h2> Bonus AngularJS Test</h2>
<div ng-app="">
  <p>You will know that angularJS is working if you can type your name into the box and see it appear on the page</p>
  <p>Name: <input type="text" ng-model="name"></p>
  <p ng-bind="name"></p>
</div>

</body>
</html>