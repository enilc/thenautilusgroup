<?php
    require_once('etc/user_funct.php');

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
    	
    	<!-- Manifest for PWA -->
    	<link rel="manifest" href="/manifest.json">
    	
    	<!-- Add to home screen for Safari on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="Spot.tr">
        <link rel="apple-touch-icon" href="images/icons/icon-152x152.png">
    	
    	<!-- Add to home screen for Microsoft -->
    	<meta name="msapplication-TileImage" content="images/icons/icon-144x144.png">
        <meta name="msapplication-TileColor" content="#2F3BA2">

        <title>Spot.tr Login</title>

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
        <style>
            /* Credit: https://codepen.io/P1N2O/pen/pyBNzX*/
            body {
                width: 100wh;
                height: 90vh;
                font-family: "Century Gothic", "sans serif";
                background: linear-gradient(-45deg, #1F9F5F, #D0EADD, #F5F5F5, #1F9F5F);
                background-size: 400% 400%;
                -webkit-animation: Gradient 35s ease infinite;
                -moz-animation: Gradient 35s ease infinite;
                animation: Gradient 35s ease infinite;
            }

            @-webkit-keyframes Gradient {
                0% {
                    background-position: 0% 50%
                }
                50% {
                    background-position: 100% 50%
                }
                100% {
                    background-position: 0% 50%
                }
            }

            @-moz-keyframes Gradient {
                0% {
                    background-position: 0% 50%
                }
                50% {
                    background-position: 100% 50%
                }
                100% {
                    background-position: 0% 50%
                }
            }

            @keyframes Gradient {
                0% {
                    background-position: 0% 50%
                }
                50% {
                    background-position: 100% 50%
                }
                100% {
                    background-position: 0% 50%
                }
            }

            h1,
            h6 {
                font-family: 'Open Sans';
                font-weight: 300;
                text-align: center;
                position: absolute;
                top: 45%;
                right: 0;
                left: 0;
            }

            /* End Credit */

            .btn-primary {
                background-color: #0E1B1A;
            }
            .btn-primary:hover {
                background-color: #1F9F5F;
            }
            .btn-primary:focus {
                background-color: #0E1B1A;
            }
            .btn-success {
                background-color: #1F9F5F;
            }
            .btn-success:hover {
                background-color: #0E1B1A;
            }

            .btn-secondary {
                background-color: #ACBDBA;
                color: #0E1B1A;
            }
            .btn-secondary:hover {
                background-color: #0E1B1A;
                color: #f5f5f5;
            }
            .modal-body {
                color: #0E1B1A;
            }
            .spottr-logo {
                width: 100%;
                margin-bottom: -45px;
                margin-top: 10px;
            }
            /* This styling is for the spining loading icon */
            .glyphicon.fast-right-spinner {
                -webkit-animation: glyphicon-spin-r 1s infinite linear;
                animation: glyphicon-spin-r 1s infinite linear;
            }

            @-webkit-keyframes glyphicon-spin-r {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }

                100% {
                    -webkit-transform: rotate(359deg);
                    transform: rotate(359deg);
                }
            }

            @keyframes glyphicon-spin-r {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                }

                100% {
                    -webkit-transform: rotate(359deg);
                    transform: rotate(359deg);
                }
            }
        </style>

    </head>

    <body>

        <div class="container">
 
            <div class="row">

                <div class="col-sm-2 col-sm-offset-5 col-md-4 col-md-offset-4">

                    <img src="images/logo2.png" class="img-fluid spottr-logo" alt="Spot.tr Logo">

                </div> 

                <!-- end .col-md-4 -->

            </div>

            <!-- End .row -->

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

                                    <div ng-click="attemptAutentication()" ng-class="loginBtn">{{btnMessage}}</div>

                                    <input type="submit" class="hidden" id="subFormBtn">

                                    <hr />

                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#registerUserModal">

                                        Create an Account!

                                    </button>

                                </fieldset>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Register User Modal -->
        <div class="modal fade" id="registerUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserLable" aria-hidden="true">

          <div class="modal-dialog modal-lg">

            <div class="modal-content">

              <div class="modal-header">

                <h2 class="modal-title d-inline" id="newUserLable">Start spotting!

                <button type="button" id="locModalCloseBtn" class="close d-inline" data-dismiss="modal" aria-label="Close">

                  <span aria-hidden="true">&times;</span>

                </button>

                </h2>

              </div>

                <div id="newUserBody" class="modal-body">

                <div class="container">

                    <div class="container-fluid">

                      <form id="newUserForm" class="form-horizontal" action="etc/registerUser.php" method="post">

                        <div id="fnGrp" class="form-group">

                          <label class="col-sm-2 control-label" for="fnInput">First Name</label>

                          <div class="col-sm-6">

                            <input type="text" class="form-control" name="firstName" id="fnInput"><span id="fnMessage"></span>

                            <span id="fnFeedback"></span>

                          </div>

                        </div>

                        <div id="lnGrp" class="form-group">

                          <label class="col-sm-2 control-label" for="lnInput">Last Name</label>

                          <div class="col-sm-6">

                            <input type="text" class="form-control" name="lastName" id="lnInput"><span id="lnMessage"></span>

                            <span id="lnFeedback"></span>

                          </div>

                        </div>

                        <div id="emlGrp" class="form-group">

                          <label class="col-sm-2 control-label" for="regEmail">Email Address</label>

                          <div class="col-sm-6">

                            <input type="text" class="form-control" name="email" id="regEmail"><span id="emlMessage"></span>

                            <span id="emlFeedback" class="form-control-feedback"></span>

                          </div>

                        </div>

                        <div id="pwGrp1" class="form-group">

                          <label class="col-sm-2 control-label" for="regPW1">Password</label>

                          <div class="col-sm-6">

                            <input type="password" name="passwordOne" class="form-control" id="regPW1">

                            <span id="pwFeedback1"></span>

                          </div>

                        </div>

                        <div id="pwGrp2" class="form-group">

                          <label class="col-sm-2 control-label" for="regPW2">Confirm Password</label>

                          <div class="col-sm-6">

                            <input type="password" name="passwordTwo" class="form-control" id="regPW2"><span id="passwordMessage"></span>

                            <span id="pwFeedback2"></span>

                          </div>

                        </div>

                      </form>

                    </div>

                </div>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                <button type="button" id="regUsr" data-loading-text="&nbsp;&nbsp;&nbsp;<i class='glyphicon glyphicon-repeat fast-right-spinner'></i>&nbsp;&nbsp;&nbsp;" class="btn btn-primary">Register!</button>

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

    <!--Import AngularJS -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
    <script>

        //Angular Portion
        var app = angular.module('userAuthentication', []);
        app.controller('login', function($scope, $http) {

            //Initialize our form input classes.
            $scope.eClass = "form-group";
            $scope.pClass = "form-group";
            $scope.loginBtn = "btn btn-lg btn-success btn-block";
            $scope.btnMessage = "Login";

            //Try to authenticate user
            $scope.attemptAutentication = function() {
                $http({
                  method: 'POST',
                  url: 'etc/db_interface.php',
                  headers: {
                    'Content-Type': 'application/json'
                    },
                  data: {
                        action: 'userLogin',
                        password: $scope.nPassword,
                        email: $scope.nEmail,
                    }
                }).then(function successCallback(response) {
                    if(response.data == '1'){
                        //If the everything is good, go ahead and submit form.
                        angular.element(document.getElementById('subFormBtn')).trigger('click');
                    } else {
                        //Give feedback to user if they've improperly typed thier password/email combination
                        $scope.eClass = "form-group has-error has-feedback";
                        $scope.pClass = "form-group has-error has-feedback";
                        $scope.loginBtn = "btn btn-lg btn-danger btn-block";
                        $scope.btnMessage = "Retry";
                    }
                  }, function errorCallback(response) {

                    console.log("Authentication Request FAILED!");
                  }); 
            };
        });


    //Register User fuctions. Added post Angular to jQuery transition
    $(document).ready(function(){
        $('#regUsr').click(function() {
            //Check values before submitting registration form.
            var canSubmit = true;

            //Is the email valid
            if (!isEmail($('#regEmail').val())){
                //Remove Existing Classes
                $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                //Overwrite Classes
                $('#emlGrp').addClass('form-group has-warning has-feedback');
                $('#emlFeedback').addClass('glyphicon glyphicon-warning-sign form-control-feedback');

                //Give feedback to the user
                $('#emlMessage').html('This email appears to be incomplete or invalid.')
                canSubmit = false;
            }

            //Passwords don't match.
            if($('#regPW1').val() !== $('#regPW2').val()){
                passwordFeedback(true);
                canSubmit = false;
            }
            //Is the password short or empty.
            if($('#regPW1').val().length < 8){
                passwordFeedback(true);
                canSubmit = false;
            }
            //If both First Name and Last Name are blank. Give feedback to user
            if($('#fnInput').val() === '' && $('#lnInput').val() === ''){
                nameFeedback('first');
                nameFeedback('last');
                canSubmit = false;
            }
            //If just the first name is blank. Give feedback to user
            if($('#fnInput').val() === ''){
                nameFeedback('first');
                canSubmit = false;
            }

            //If just last name is blank. Give feedback to user
            if($('#lnInput').val() === ''){
                nameFeedback('last');
                canSubmit = false;
            }

            //If everything is copacetic, submit the registration form.
            if(canSubmit){
                $('#newUserForm').submit();
            }
            

        });

        //Give feedback when user enters an email. Turn field to error state if email is already registered.
        $('#regEmail').change(function(){
            //Break if user has put in an invalid email.
            if(!isEmail($('#regEmail').val())){
                 //Remove Existing Classes
                $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                //Overwrite Classes
                $('#emlGrp').addClass('form-group has-warning has-feedback');
                $('#emlFeedback').addClass('glyphicon glyphicon-warning-sign form-control-feedback');

                //Give feedback to the user
                $('#emlMessage').html('This email appears to be incomplete or invalid.')

                return null;
            }
            //Clear classed
            $('#emlGrp').removeClass($('#emlGrp').attr('class'));
            $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));
            
            //Switch to a "loading" state while awaiting ajax call
            $('#emlGrp').addClass('form-group has-success has-feedback');
            $('#emlFeedback').addClass('glyphicon glyphicon-repeat fast-right-spinner form-control-feedback');

            //Query the database to see if email address is available.
            $.ajax({
            url: "etc/db_interface.php",
            type: "POST",
            data: {key: 'B52C106C63CB00C850584523FB0EC12',
                action: 'user_exist',
                email: $('#regEmail').val().toLowerCase()},
            dataType: "text",
            success: function(response) {
                //User Email Already Exists, so we cannot register it.
                if(response === '1'){

                    //Remove Existing Classes
                    $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                    $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                    //Give user warning feedback
                    $('#emlGrp').addClass('form-group has-warning has-feedback');
                    $('#emlFeedback').addClass('glyphicon glyphicon-warning-sign form-control-feedback');

                    //Inform the user of the problem.
                    $('#emlMessage').html('This email address is already registered.');
                //Email does not exist, so we may add it here.
                } else {
                    //Remove Existing Classes
                    $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                    $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                    //Give user "ok" feedback
                    $('#emlGrp').addClass('form-group has-success has-feedback');
                    $('#emlFeedback').addClass('glyphicon glyphicon-ok form-control-feedback');

                    //Remove error message if there was one.
                    $('#emlMessage').html('');
                }
            },
            });
        });

        //If they enter a password, call the feedback function
        $('#regPW1').change(function(){
            passwordFeedback();
        });

        //If they enter a password, call the feedback function
        $('#regPW2').change(function(){
            passwordFeedback();
        });

        //If they enter a first name, call the feedback function
        $('#fnInput').change(function(){
            nameFeedback('first');
        })

        //If they enter a last name, call the feedback function
        $('#lnInput').change(function(){
            nameFeedback('last');
        })

    });

    //Function provides feedback for first name or last name depending on type.
    function nameFeedback(type){

        //Set our flag
        var nm = ((type === 'first') ? 'fn' : 'ln');

        //If the First Name field is blank, give error feedback to user
        if($('#' + nm + 'Input').val() === ''){
            //Remove Existing Classes
            $('[id*="' + nm + 'Grp"]').removeClass($('[id*="' + nm + 'Grp"]').attr('class'));
            $('[id*="' + nm + 'Feedback"]').removeClass($('[id*="' + nm + 'Feedback"]').attr('class'));

            //Put input in error state
            $('[id*="' + nm + 'Grp"]').addClass('form-group has-error has-feedback');
            $('[id*="' + nm + 'Feedback"]').addClass('glyphicon glyphicon-remove form-control-feedback');

            //Inform user of the problem.
            $('[id*="' + nm + 'Message"]').html('Both your first and last names are required.');
        //If the Field has been filled in, give "okay" feedback to user
        } else {
            //Remove Existing Classes
            $('[id*="' + nm + 'Grp"]').removeClass($('[id*="' + nm + 'Grp"]').attr('class'));
            $('[id*="' + nm + 'Feedback"]').removeClass($('[id*="' + nm + 'Feedback"]').attr('class'));

            //Put input into "okay" state
            $('[id*="' + nm + 'Grp"]').addClass('form-group has-success has-feedback');
            $('[id*="' + nm + 'Feedback"]').addClass('pwFeedback glyphicon glyphicon-ok form-control-feedback');

            //Rmeove error message if there was one.
            $('[id*="' + nm + 'Message"]').html('');
        }

    }

    //Give feedback to the user when putting in passwords in the registration from.
    function passwordFeedback(forceFeedback = false){

        //Give the user a chance to fill in both password fields before giving feedback.
        if(($('#regPW1').val() === '' || $('#regPW2').val() === '') && !forceFeedback){
            return;
        // If the passwords match, give feedback to the user
        } else if ($('#regPW1').val() === $('#regPW2').val()){
            // If passwords are too short, but still match, give error feedback to the user.
            if($('#regPW1').val().length < 8){
                //Remove Existing Classes
                $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
                $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

                //Put fields in the error state
                $('[id*="pwGrp"]').addClass('form-group has-error has-feedback');
                $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-remove form-control-feedback');

                //Present error message to the user.
                $('[id*="passwordMessage"]').html('Password is too short.');
            //If passwords match, and are long enough, give "okay" feedback to the user.
            } else {
                //Remove Existing Classes
                $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
                $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

                //Put the password fields in the "okay" state.
                $('[id*="pwGrp"]').addClass('form-group has-success has-feedback');
                $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-ok form-control-feedback');

                //Remove any error messages.
                $('[id*="passwordMessage"]').html('');
            }
        //Case that the passwords do not match
        } else {
            //Remove Existing Classes
            $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
            $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

            //Put password fields in the error state
            $('[id*="pwGrp"]').addClass('form-group has-error has-feedback');
            $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-remove form-control-feedback');

            //Let user know that the passwords do not match.
            $('[id*="passwordMessage"]').html('Passwords do not match.');

            //Additional feedback. Passwords do not match and at least one is too short.
            if($('#regPW1').val().length < 8 || $('#regPW2').val().length < 8){
                $('[id*="passwordMessage"]').html($('[id*="passwordMessage"]').html() + ' Also, at least one password is too short.');
            }

        }
    }

    //Function to check if an email address follows the correct form.
    //Credit: https://stackoverflow.com/a/2507043
    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }

    </script>

</html>
