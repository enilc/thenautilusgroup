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
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-map-coffee fa-fw"></i>Mediator Object Tests
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
Test 9 {{cleanup}}: db cleanup

							</pre>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-8 -->

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
					//Test 9: cleaning up after ourselves.
						$http({
							method: 'POST',
							url: 'db_interface.php',
							headers: {
							'Content-Type': 'application/json'
							},
							data: {key: 'B52C106C63CB00C850584523FB0EC12',
									action: 'clean_test',
									filter: testString }
							}).then(function successCallback(response) {
								// this callback will be called asynchronously
								// when the response is available
								((response.data == 'success') ? $scope.cleanup = 'Passed' : $scope.cleanup = 'FAILED');

							}, function errorCallback(response) {
								// called asynchronously if an error occurs
								// or server returns response with an error status.
								$scope.cleanup = "FAILED (callback)";
							});
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

</body>

</html>
