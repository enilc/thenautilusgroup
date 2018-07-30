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
<html lang="en">

<head>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DB Interface Test</title>

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

   <script src="http://maps.google.com/maps/api/js?key=AIzaSyAxnMU9bIct86r3W4-rJ22Sirsli0U3uH4&sensor=false" 
          type="text/javascript">
   </script>
	
    <style>
      /* Set the size of the div element that contains the map */
     #map {
       height: 600px;  /* The height is 400 pixels */
       width: 100%;  /* The width is the width of the web page */
      }
    </style>

</head>

<body>

<script>
    var app = angular.module('testDatabaseInterface', []);
    app.controller('dbInterfaceController', function($scope, $http) {
    	var testString = 'C2B9264423F72EA0A78699BB9663EEA4E8647BC595B64501BDFA1429F54C4FAB';
			
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
			}, function errorCallback(response) {
				// called asynchronously if an error occurs
				// or server returns response with an error status.
				$scope.select_test = "FAILED (callback)";
			});
		});
</script>

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
                <div class="col-lg-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-photo fa-fw"></i> Photos in this region
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                                <img src="images/01.jpg" class="img-thumbnail" alt="Image1">
                                <img src="images/02.jpg" class="img-thumbnail" alt="Image1">
                                <img src="images/03.jpg" class="img-thumbnail" alt="Image1">
                                <img src="images/04.jpg" class="img-thumbnail" alt="Image1">
                                <img src="images/05.jpg" class="img-thumbnail" alt="Image1">
<div class='testPre' ng-app="testDatabaseInterface" ng-controller="dbInterfaceController">

<!-- This tests that the data from DB is accessible via Angular variables -->
Index Count: {{index}}
Map Longitude: {{map_longitude}}
Map Location: {{map_location}}
Map Location: {{map_location}}
Map Latitude: {{map_latitude}}

<!-- This is a workaround attempt to make Angular variables accessible to JS -->
<input type="hidden" id="angularLat" value={{map_latitude}}>
<input type="hidden" id="angularLng" value={{map_longitude}}>
<input type="hidden" id="angularLoc" value={{map_location}}>

<!-- This tests that JS variables with passed Angular values have correct values -->
<p id="demo1"></p>
<p id="demo2"></p>
<p id="demo3"></p>
</div>

                            <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->

                </div>
                <!-- /.col-lg-4 -->

                <div class="col-lg-10">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-map-marker fa-fw"></i>Spotted Locations
                            <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                        Actions
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Action</a>
                                        </li>
                                        <li><a href="#">Another action</a>
                                        </li>
                                        <li><a href="#">Something else here</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">

                            <div id="map"></div>
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



    <!-- AngularJS -->
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
// This passes Angular values to a JS variable
var mapLat = $("#angularLat").val();
var mapLng = $("#angularLng").val();
var mapLoc = $("#angularLoc").val();

// This uses new JS variables
document.getElementById("demo1").innerHTML = "Lat: " + mapLat;
document.getElementById("demo2").innerHTML = "Lng: " + mapLng;
document.getElementById("demo3").innerHTML = "Loc: " + mapLoc;
</script>
	
<script type="text/javascript">

var mapLat2 = 37.4947;
var mapLng2 = -120.8466;
var mapLoc2 = 'Turlock';
// Initialize and add the map
// Tests that JS variables can be passed into JS array
var locations = [['Manteca', 37.7974, -121.2161],[mapLoc2, mapLat2, mapLng2],['Ceres', 37.5949, -120.9577]];

//locations[1][0] = mapLoc;
//locations[1][1] = mapLat;
//locations[1][2] = mapLng;

 var map = new google.maps.Map(
     document.getElementById('map'), {zoom: 10, center: new google.maps.LatLng(37.5949, -120.9577)});

 var marker, i;

 for (i = 0; i < locations.length; i++) {  
   marker = new google.maps.Marker({
     position: new google.maps.LatLng(locations[i][1], locations[i][2]),
     map: map
   });

   google.maps.event.addListener(marker, 'click', (function(marker, i) {
    return function() {
        infowindow.setContent(locations[i][0]);
        infowindow.open(map, marker);
    }
   })(marker, i));
 }
   
</script>



</body>

</html>