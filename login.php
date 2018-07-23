<?php
    require_once('user_funct.php');

    if(!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) { //We don't have a session to use
        if(isset($_POST['email']) && isset($_POST['password'])){ //We don't have any login information posted
            if(authenticateUser($_POST['email'],$_POST['password'])){
                session_start();
                $_SESSION['userEmail'] = $_POST['email'];
                $_SESSION['authenticated'] = True;
                header('Location: index.php?sid=' . session_id());
            }
        } 
    } else{
        header('Location: index.php');
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

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body" ng-app="userAuthentication" ng-controller="login">
                        <form role="form" action="login.php" method="post">
                            <fieldset>
                                <div ng-class="eClass">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" ng-model="nEmail" autofocus>
                                </div>
                                <div ng-class="pClass">
                                    <input class="form-control" placeholder="Password" name="password" type="password" ng-model="nPassword" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <div ng-click="attemptAutentication()" ng-class="loginBtn">{{btnMessage}}</div>
                                <input type="submit" class="hidden" id="subFormBtn">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
    var app = angular.module('userAuthentication', []);
    app.controller('login', function($scope, $http) {

        $scope.nEmail = "nemo@nautilus.group";
        $scope.nPassword = "-----NautilusAdmin-----";

        $scope.eClass = "form-group";
        $scope.pClass = "form-group";

        $scope.loginBtn = "btn btn-lg btn-success btn-block";
        $scope.btnMessage = "Login";

        $scope.attemptAutentication = function() {
            $http({
              method: 'POST',
              url: 'db_interface.php',
              headers: {
                'Content-Type': 'application/json'
                },
              data: {
                    action: 'userLogin',
                    password: $scope.nPassword,
                    email: $scope.nEmail,
                }
            }).then(function successCallback(response) {
                // this callback will be called asynchronously
                // when the response is available
                if(response.data == '1'){
                    console.log('passwd');
                    angular.element(document.getElementById('subFormBtn')).trigger('click');
                } else {
                    $scope.eClass = "form-group has-error has-feedback";
                    $scope.pClass = "form-group has-error has-feedback";
                    $scope.loginBtn = "btn btn-lg btn-danger btn-block";
                    $scope.btnMessage = "Retry";
                }
              }, function errorCallback(response) {
                // called asynchronously if an error occurs
                // or server returns response with an error status.
                $scope.basic_connectivity = "FAILED (callback)";
              }); 
        };
    });

</script>

</html>
