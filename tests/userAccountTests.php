<?php 

    require_once('../etc/user_funct.php');

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
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
        require_once('../includes/nav_bar.php') 
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-arrows-alt fa-fw"></i> Tests User Registration Functionality
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive" ng-app="testDatabaseUserInterface" ng-controller="dbInterfaceUserController">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Test Description</th>
                                            <th>Options Tested</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr ng-class="testStatus[0]">
                                            <td>1</td>
                                            <td>User Exist Test</td>
                                            <td><span class="badge progress-bar-success">Extant Email</span></td>
                                            <td class="usrTest">{{testResults[0]}}</td>
                                        </tr>
                                        <tr ng-class="testStatus[1]">
                                            <td>2</td>
                                            <td>User Does Not Exist Test</td>
                                            <td><span class="badge progress-bar-danger">Non-Existant Email</span></td>
                                            <td class="usrTest">{{testResults[1]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[2]">
                                            <td>3</td>
                                            <td>Email Awaiting Verification</td>
                                            <td> <span class="badge progress-bar-danger">Bad Token</span>
                                                <span class="badge progress-bar-danger">Bad Email</span></td>
                                                
                                            <td class="usrTest">{{testResults[2]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[3]">
                                            <td>4</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-danger">Bad Token</span>
                                                <span class="badge progress-bar-success">Good Email</span></td>
                                            <td class="usrTest">{{testResults[3]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[4]">
                                            <td>5</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-success">Good Token</span>
                                                <span class="badge progress-bar-danger">Bad Email</span></td>
                                            <td class="usrTest">{{testResults[4]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[5]">
                                            <td>6</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-success">Good Token</span>
                                                <span class="badge progress-bar-success">Good Email</span></td>
                                            <td class="usrTest">{{testResults[5]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[6]">
                                            <td>7</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-warning">Missing Token</span>
                                                <span class="badge progress-bar-success">Good Email</span></td>
                                            <td class="usrTest">{{testResults[6]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[7]">
                                            <td>8</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-success">Good Token</span>
                                                <span class="badge progress-bar-warning">Missing Email</span></td>
                                            <td class="usrTest">{{testResults[7]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[8]">
                                            <td>9</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-warning">Missing Token</span>
                                                <span class="badge progress-bar-danger">Bad Email</span></td>
                                            <td class="usrTest">{{testResults[8]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[9]">
                                            <td>10</td>
                                            <td>Email Awaiting Verification </td>
                                            <td> <span class="badge progress-bar-danger">Bad Token</span>
                                                <span class="badge progress-bar-warning">Missing Email</span></td>
                                            <td class="usrTest">{{testResults[9]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[10]"> <!-- User Registration: all okay-->
                                            <td>11</td>
                                            <td><b>User Registration</b><br /> All Okay </td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-success">Good Token</span>
                                            </td>
                                            <td class="usrTest">{{testResults[10]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[11]"> <!-- User Registration: email in system-->
                                            <td>12</td>
                                            <td><b>User Registration</b><br /> Email Already registered</td>
                                            <td> 
                                                <span class="badge progress-bar-danger">Email Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-success">Good Token</span></td>
                                            <td class="usrTest">{{testResults[11]}}</td>
                                        </tr>

                                            <tr ng-class="testStatus[12]"> <!-- User Registration: passwords don't match-->
                                            <td>13</td>
                                            <td><b>User Registration</b><br /> Passwords Don't Match </td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-danger">Unmatching Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-success">Good Token</span></td>
                                            <td class="usrTest">{{testResults[12]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[13]"> <!-- User Registration: passwords too short-->
                                            <td>14</td>
                                            <td><b>User Registration</b><br /> Passwords are Too Short</td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-danger">Short Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-success">Good Token</span></td>
                                            <td class="usrTest">{{testResults[13]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[14]"> <!-- User Registration: first name blank-->
                                            <td>15</td>
                                            <td><b>User Registration</b><br /> Blank First Name</td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-danger">First Name Blank</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-success">Good Token</span></td>
                                            <td class="usrTest">{{testResults[14]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[15]"> <!-- User Registration: last name blank-->
                                            <td>16</td>
                                            <td><b>User Registration</b><br /> Blank Last Name</td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-danger">Last Name Blank</span>
                                                <span class="badge progress-bar-success">Good Token</span></td>
                                            <td class="usrTest">{{testResults[15]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[16]"> <!-- User Registration: bad token-->
                                            <td>17</td>
                                            <td><b>User Registration</b><br /> Bad Token</td>
                                            <td> 
                                                <span class="badge progress-bar-success">Email Not Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-danger">Bad Token</span></td>
                                            <td class="usrTest">{{testResults[16]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[17]"> <!-- User Registration: All Bad-->
                                            <td>18</td>
                                            <td><b>User Registration</b><br /> Everything wrong</td>
                                            <td> 
                                                <span class="badge progress-bar-danger">Email Registered</span>
                                                <span class="badge progress-bar-danger">Passwords too short and don't match</span>
                                                <span class="badge progress-bar-danger">First Name is Blank</span>
                                                <span class="badge progress-bar-danger">Last Name is Blank</span>
                                                <span class="badge progress-bar-danger">Bad Token</span></td>
                                            <td class="usrTest">{{testResults[17]}}</td>
                                        </tr>
                                            <tr ng-class="testStatus[18]"> <!-- User Registration: email in system-->
                                            <td>19</td>
                                            <td><b>User Registration</b><br /> Missing Token</td>
                                            <td> 
                                                <span class="badge progress-bar-warning">Email Not Registered</span>
                                                <span class="badge progress-bar-success">Good Passwords</span>
                                                <span class="badge progress-bar-success">Good First Name</span>
                                                <span class="badge progress-bar-success">Good Last Name</span>
                                                <span class="badge progress-bar-warning">Missing Token</span></td>
                                            <td class="usrTest">{{testResults[18]}}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->                 
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->



    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>


    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

<script>
    var app = angular.module('testDatabaseUserInterface', []);
    app.controller('dbInterfaceUserController', function($scope, $http) {

        var numberOfTests = 10;
    	
        $scope.testStatus = [];
    	$scope.testResults = [];

        for(var i = 0; i < numberOfTests; i++){
           $scope.testStatus.push('');
           $scope.testResults.push('');
        }



	$http({
	method: 'POST',
	url: '../etc/db_interface.php',
	headers: {
		'Content-Type': 'application/json'
	},
	data: {key: 'B52C106C63CB00C850584523FB0EC12',
		action: 'user_exist',
		email: 'caleb.allen@gmail.com'}
	}).then(function successCallback(response) {
	// this callback will be called asynchronously
	// when the response is available
	if(response.data === '1'){
		$scope.testStatus[0] = 'success';
		$scope.testResults[0] = 'Passed';
	} else {
		$scope.testStatus[0] = 'danger';
		$scope.testResults[0] = 'FAILED';
		console.log('testing123')
		console.log(response.data);
	}

	}, function errorCallback(response) {
	// called asynchronously if an error occurs
	// or server returns response with an error status.
	$scope.key_test = "FAILED (callback)";
	});

	$http({
	method: 'POST',
	url: '../etc/db_interface.php',
	headers: {
		'Content-Type': 'application/json'
	},
	data: {key: 'B52C106C63CB00C850584523FB0EC12',
		action: 'user_exist',
		email: 'smerd@gmail.com'}
	}).then(function successCallback(response) {
	// this callback will be called asynchronously
	// when the response is available
	if(response.data == '0'){
		$scope.testStatus[1] = 'success';
		$scope.testResults[1] = 'Passed';
	} else {
		$scope.testStatus[1] = 'danger';
		$scope.testResults[1] = 'FAILED';
		console.log(response.data);
	}

	}, function errorCallback(response) {
	// called asynchronously if an error occurs
	// or server returns response with an error status.
	$scope.key_test = "FAILED (callback)";
	});


    //Test if both email AND token are wrong
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'smerd@gmail.com',
        token: 'Welcome123'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[2] = 'success';
        $scope.testResults[2] = 'Passed';
    } else {
        $scope.testStatus[2] = 'danger';
        $scope.testResults[2] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if email is good but token is wrong
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'negativeinfinity@gmail.com',
        token: 'Welcome123'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[3] = 'success';
        $scope.testResults[3] = 'Passed';
    } else {
        $scope.testStatus[3] = 'danger';
        $scope.testResults[3] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });
//29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453

    //Test if token is good but email is wrong
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'smerd@mailinator.com',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[4] = 'success';
        $scope.testResults[4] = 'Passed';
    } else {
        $scope.testStatus[4] = 'danger';
        $scope.testResults[4] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both token and email are good.
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'negativeinfinity@gmail.com',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '1'){
        $scope.testStatus[5] = 'success';
        $scope.testResults[5] = 'Passed';
    } else {
        $scope.testStatus[5] = 'danger';
        $scope.testResults[5] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no token good email
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'negativeinfinity@gmail.com'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[6] = 'success';
        $scope.testResults[6] = 'Passed';
    } else {
        $scope.testStatus[6] = 'danger';
        $scope.testResults[6] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[7] = 'success';
        $scope.testResults[7] = 'Passed';
    } else {
        $scope.testStatus[7] = 'danger';
        $scope.testResults[7] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });
    //Test if both no token good email
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        email: 'smerd@mailinator.com'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[8] = 'success';
        $scope.testResults[8] = 'Passed';
    } else {
        $scope.testStatus[8] = 'danger';
        $scope.testResults[8] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/db_interface.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'verify_email',
        token: 'Welcome123'}
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[9] = 'success';
        $scope.testResults[9] = 'Passed';
    } else {
        $scope.testStatus[9] = 'danger';
        $scope.testResults[9] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '1'){
        $scope.testStatus[10] = 'success';
        $scope.testResults[10] = 'Passed';
    } else {
        $scope.testStatus[10] = 'danger';
        $scope.testResults[10] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });



    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'caleb.allen@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[11] = 'success';
        $scope.testResults[11] = 'Passed';
    } else {
        $scope.testStatus[11] = 'danger';
        $scope.testResults[11] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });


    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[12] = 'success';
        $scope.testResults[12] = 'Passed';
    } else {
        $scope.testStatus[12] = 'danger';
        $scope.testResults[12] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Wel',
        passwordTwo: 'Wel',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[13] = 'success';
        $scope.testResults[13] = 'Passed';
    } else {
        $scope.testStatus[13] = 'danger';
        $scope.testResults[13] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: '',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[14] = 'success';
        $scope.testResults[14] = 'Passed';
    } else {
        $scope.testStatus[14] = 'danger';
        $scope.testResults[14] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: '',
        token: '29B61A8EF1DEA154AE76EB234AE3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[15] = 'success';
        $scope.testResults[15] = 'Passed';
    } else {
        $scope.testStatus[15] = 'danger';
        $scope.testResults[15] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',
        token: '29B61A8EF1DEA154AE76CDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[16] = 'success';
        $scope.testResults[16] = 'Passed';
    } else {
        $scope.testStatus[16] = 'danger';
        $scope.testResults[16] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'caleb.allen@gmail.com',
        passwordOne: 'Wel',
        passwordTwo: 'Welcome123',
        firstName: '',
        lastName: '',
        token: '29B3CA207800CCDAE5C42857490959F5C29FD453'
    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[17] = 'success';
        $scope.testResults[17] = 'Passed';
    } else {
        $scope.testStatus[17] = 'danger';
        $scope.testResults[17] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });


    //Test if both no email good token 
    $http({
    method: 'POST',
    url: '../etc/registerUser.php',
    headers: {
        'Content-Type': 'application/json'
    },
    data: {
        email:'negativeinfinity@gmail.com',
        passwordOne: 'Welcome123',
        passwordTwo: 'Welcome123',
        firstName: 'Bobs',
        lastName: 'Uruncle',

    }
    }).then(function successCallback(response) {
    // this callback will be called asynchronously
    // when the response is available
    if(response.data == '0'){
        $scope.testStatus[18] = 'success';
        $scope.testResults[18] = 'Passed';
    } else {
        $scope.testStatus[18] = 'danger';
        $scope.testResults[18] = 'FAILED';
        console.log(response.data);
    }

    }, function errorCallback(response) {
    // called asynchronously if an error occurs
    // or server returns response with an error status.
    $scope.key_test = "FAILED (callback)";
    });

});

//credit: https://stackoverflow.com/a/1349426
function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

  for (var i = 0; i < 5; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}
</script>




<script>


</script>
</body>

</html>
