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
    <style>

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
                                <input type="submit" class="hidden" id="subFormBtn"><hr />
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

    <!-- New Location Modal -->
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

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script>
    var app = angular.module('userAuthentication', []);
    app.controller('login', function($scope, $http) {


        $scope.eClass = "form-group";
        $scope.pClass = "form-group";

        $scope.loginBtn = "btn btn-lg btn-success btn-block";
        $scope.btnMessage = "Login";

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
                // this callback will be called asynchronously
                // when the response is available
                if(response.data == '1'){
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
$(document).ready(function(){
    $('#regUsr').click(function() {

        switch (true){
            //Is the email valid
            case (!isEmail($('#regEmail').val())):

                //Remove Existing Classes
                $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                //Overwrite Classes
                $('#emlGrp').addClass('form-group has-warning has-feedback');
                $('#emlFeedback').addClass('glyphicon glyphicon-repeat fast-right-spinner form-control-feedback');

                $('#emlMessage').html('This email appears to be incomplete or invalid.')
                break;
            //Passwords don't match. Feedback is built into the form.
            case ($('#regPW1').val() !== $('#regPW2').val()):
                break;
            //Is the password short or empty
            case ($('#regPW1').val().length < 8):
                break;
            case ($('#fnInput').val() === '' && $('#lnInput').val() === ''):
                nameFeedback('first');
                nameFeedback('last');
                break;
            case ($('#fnInput').val() === ''):
                nameFeedback('first');
                break;
            case ($('#lnInput').val() === ''):
                nameFeedback('last');
                break;
            default:
                $('#newUserForm').submit();
                break;
        }


    });

    $('#regEmail').change(function(){

        $('#emlGrp').removeClass($('#emlGrp').attr('class'));
        $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));
        
        $('#emlGrp').addClass('form-group has-success has-feedback');
        $('#emlFeedback').addClass('glyphicon glyphicon-repeat fast-right-spinner form-control-feedback');

        $.ajax({
        url: "etc/db_interface.php",
        type: "POST",
        data: {key: 'B52C106C63CB00C850584523FB0EC12',
            action: 'user_exist',
            email: $('#regEmail').val().toLowerCase()},
        dataType: "text",
        success: function(response) {
            console.log(response);
            //User Email Already Exists, so we cannot register it.
            if(response === '1'){

                //Remove Existing Classes
                $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                //Overwrite Classes
                $('#emlGrp').addClass('form-group has-warning has-feedback');
                $('#emlFeedback').addClass('glyphicon glyphicon-warning-sign form-control-feedback');

                $('#emlMessage').html('This email address is already registered.');
            } else {
                //Remove Existing Classes
                $('#emlGrp').removeClass($('#emlGrp').attr('class'));
                $('#emlFeedback').removeClass($('#emlFeedback').attr('class'));

                //Overwrite Classes
                $('#emlGrp').addClass('form-group has-success has-feedback');
                $('#emlFeedback').addClass('glyphicon glyphicon-ok form-control-feedback');

                $('#emlMessage').html('');
            }
        },
        });
    });

    $('#regPW1').change(function(){
        passwordFeedback();
    });

    $('#regPW2').change(function(){
        passwordFeedback();
    });

    $('#fnInput').change(function(){
        nameFeedback('first');
    })
    $('#lnInput').change(function(){

        nameFeedback('last');
    })


    //$('#emlFeedback').addClass('glyphicon glyphicon-repeat fast-right-spinner form-control-feedback');
});

function nameFeedback(type){

    var nm = ((type === 'first') ? 'fn' : 'ln');

    if($('#' + nm + 'Input').val() === ''){
        //Remove Existing Classes
        $('[id*="' + nm + 'Grp"]').removeClass($('[id*="' + nm + 'Grp"]').attr('class'));
        $('[id*="' + nm + 'Feedback"]').removeClass($('[id*="' + nm + 'Feedback"]').attr('class'));

        //Overwrite Classes
        $('[id*="' + nm + 'Grp"]').addClass('form-group has-error has-feedback');
        $('[id*="' + nm + 'Feedback"]').addClass('pwFeedback glyphicon glyphicon-remove form-control-feedback');

        $('[id*="' + nm + 'Message"]').html('Both your first and last names are required.');

    } else {
        //Remove Existing Classes
        $('[id*="' + nm + 'Grp"]').removeClass($('[id*="' + nm + 'Grp"]').attr('class'));
        $('[id*="' + nm + 'Feedback"]').removeClass($('[id*="' + nm + 'Feedback"]').attr('class'));

        //Overwrite Classes
        $('[id*="' + nm + 'Grp"]').addClass('form-group has-success has-feedback');
        $('[id*="' + nm + 'Feedback"]').addClass('pwFeedback glyphicon glyphicon-ok form-control-feedback');

        $('[id*="' + nm + 'Message"]').html('');
    }

}

function passwordFeedback(){

    if($('#regPW1').val() === '' || $('#regPW2').val() === ''){
        return;
    } else if ($('#regPW1').val() === $('#regPW2').val()){
        if($('#regPW1').val().length < 8){
            //Remove Existing Classes
            $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
            $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

            //Overwrite Classes
            $('[id*="pwGrp"]').addClass('form-group has-error has-feedback');
            $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-remove form-control-feedback');

            $('[id*="passwordMessage"]').html('Password is too short.');
        } else {
            //Remove Existing Classes
            $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
            $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

            //Overwrite Classes
            $('[id*="pwGrp"]').addClass('form-group has-success has-feedback');
            $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-ok form-control-feedback');

            $('[id*="passwordMessage"]').html('');
        }

    } else {
        //Remove Existing Classes
        $('[id*="pwGrp"]').removeClass($('[id*="pwGrp"]').attr('class'));
        $('[id*="pwFeedback"]').removeClass($('[id*="pwFeedback"]').attr('class'));

        //Overwrite Classes
        $('[id*="pwGrp"]').addClass('form-group has-error has-feedback');
        $('[id*="pwFeedback"]').addClass('pwFeedback glyphicon glyphicon-remove form-control-feedback');

        $('[id*="passwordMessage"]').html('Passwords do not match.');
        if($('#regPW1').val().length < 8 || $('#regPW2').val().length < 8){
            $('[id*="passwordMessage"]').html($('[id*="passwordMessage"]').html() + ' Also, at least one password is too short.');
        }

    }
}

//Credit: https://stackoverflow.com/a/2507043
function isEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

</script>

</html>
