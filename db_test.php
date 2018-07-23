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


	function testDatabaseConnection(){
		$dbConn = connectToDatabase();
		$testString = 'C2B9264423F72EA0A78699BB9663EEA4E8647BC595B64501BDFA1429F54C4FAB';
		$count = 1;
		$passed = 0;

		$locId = 0;
		//Test 1: Try inserting something into the Locations Table
		$sql = "INSERT INTO spotter.Location (loc_name, latitude,longitude) VALUES (:testString, 0.0, 0.0)";
		$stmt = $dbConn -> prepare($sql);
		if(!$stmt -> execute(array(':testString' => $testString))){
			echo "Test $count error: failed to insert into the databse. [spotter.locations]\n";
		} else {
			echo "Test $count PASSED:   Successfully INSERTed into spotter.Location\n";
			$passed += 1;
		}
		$count += 1;

		//Test 2: Check to see if our inserted Location is present.
		$sql = "SELECT * FROM spotter.Location as l WHERE l.loc_name = :testString";
		$stmt = $dbConn -> prepare($sql);
		$status = $stmt -> execute(array(':testString' => $testString));
		$locationResults = $stmt->fetchAll();
		if(!$status){
			echo "Test $count error: failed to select from the databse. [spotter.locations]\n";
		} else if(strcmp($locationResults['0']['loc_name'],$testString) != 0){
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
		$sql = "DELETE FROM spotter.Location WHERE loc_name = :testString";
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

	function testUserAccountFunctionality(){
		/********************************************
			Step 1: Create a user account in the User
					of the database.
			Step 2: Check to see if that user account
					can be queried out of database.
			Step 3: Delete the user account from the
					database.
		/********************************************/
		//Get PDO object use for database operations
		$dbConn = connectToDatabase();

		//Test Counters.
		$passed = 0;
		$count = 1;

		$uEmail = 'bobs@yourUncle.com';
		$uPassword = 'NwMnA&j`3.r,et*';
		$uFirstName = 'Sunny';
		$uLastName = 'Bono';


		//Test 1: Try to create a user.
		$sql = "INSERT INTO spotter.User (email, password,first_name,last_name) VALUES (:email, :password, :fName, :lName)";
		$stmt = $dbConn -> prepare($sql);
		if(!$stmt -> execute(array(':email' => $uEmail, ':password' => password_hash($uPassword, PASSWORD_DEFAULT), ':fName' => $uFirstName, ':lName' => $uLastName))){
			echo "Test $count error: failed to insert user into the database WITH password hash. [spotter.Users]\n";
		} else {
			echo "Test $count PASSED:   Successfully INSERTed into spotter.Users\n";
			$passed += 1;
		}
		$count += 1;

		//Test 2: See if we can get the same user back (with hash this time)
		$sql = "SELECT email, password, first_name, last_name FROM spotter.User WHERE email = :email";
		$stmt = $dbConn -> prepare($sql);
		if(!$stmt -> execute(array(':email' => $uEmail))){
			echo "Test $count error: failed to SELECT from the user database in Password Hash Section. [spotter.Users]\n";
		} else {
			$selected = $stmt -> fetchAll() [0]; //Grab the first selected record.
			if($selected['email'] == $uEmail && password_verify($uPassword,$selected['password'])
				&& $selected['first_name'] == $uFirstName && $selected['last_name'] == $uLastName){
				echo "Test $count PASSED:   Successfully SELECTed into spotter.Users. Password hashes match.\n";
				$passed += 1;
			} else {
				echo "Test $count error: SELECTed records have unexpected values in Password Hash section. [spotter.Users]\n";
			}
		}
		$count += 1;

		//Test 3: Try to delete entry
		$sql = "DELETE FROM spotter.User WHERE email = :email";
		$stmt = $dbConn -> prepare($sql);
		$status = $stmt -> execute(array(':email' => $uEmail));

		if(!$status){
			echo "Test $count error: failed to delete records from the databse in password hash section. [spotter.User]\n";
		} else {
			echo "Test $count PASSED: DELETE command accepted for spotter.User in password hash section.\n";
			$passed += 1;
		}
		$count += 1;

		//Test 4: Confirm that something was actually deleted from the User table.
		if($stmt->rowCount() <= 0){
			echo "Test $count error: expected record not present for deletion. [spotter.User]\n";
		} else {
			echo "Test $count PASSED:   Expected records found in spotter.User\n";
			$passed += 1;
		}

		$count += 1;


		//////////////////////////////////////////////////////
		$count -= 1;
		echo "<p>Passed $passed/$count tests</p>";
	}

 ?>

<!DOCTYPE html>
<html lang="en">



<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <style>
      /* Set the size of the div element that contains the map */
     #map {
       height: 600px;  /* The height is 400 pixels */
       width: 100%;  /* The width is the width of the web page */
      }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
</head>

<body>

    <div id="wrapper">

        <?php 
        //Allows ud to edit the navigation bar one time for all website pages.
        require_once('includes/nav_bar.php') 
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Spottr, the photo location scouting app! </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-arrows-alt fa-fw"></i> Tests for PDO Connection to Database
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<pre>
<?php testDatabaseConnection(); ?>
							</pre>						
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-4 -->

                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-map-coffee fa-fw"></i>AngularJS interactions with db_interface.php tests
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<pre class='testPre' ng-app="testDatabaseInterface" ng-controller="dbInterfaceController">
Test 1 {{basic_connectivity}}: Database Interface Basic Connectivity 
Test 2 {{key_test}}: Database Interaction Key Test
Test 3 {{insert_test}}: SQL INSERTion test.
Test 4 {{select_test}}: SQL SELECT test.
Test 5 {{injection_table_name}}: SQL Table Code Injection Attack Prevention (SELECT).
Test 6 {{injection_column_name}}: SQL Column Code Injection Attack Prevention. (SELECT)
Test 7 {{injection_table_name_insert}}: SQL Table Code Injection Attack Prevention (INSERT).
Test 8 {{injection_column_name_insert}}: SQL Column Code Injection Attack Prevention. (INSERT)

							</pre>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-8 -->

            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i> User Account Tests
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
							<pre ><?php testUserAccountFunctionality(); ?> </pre>					
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-4 -->

            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->



    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

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
					columns: ['loc_name','latitude','longitude'],
					filter: {'loc_name': testString} }
			}).then(function successCallback(response) {
				// this callback will be called asynchronously
				// when the response is available
				((response.data[0]['loc_name'] == testString && response.data[0]['latitude'] == 0.00000000 && response.data[0]['longitude'] == 0.00000000) ? $scope.select_test= 'Passed' : $scope.select_test = 'FAILED');
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




<script>


</script>
</body>

</html>
