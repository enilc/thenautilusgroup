<?php 
    require_once('etc/user_funct.php');
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
//require_once('etc/db_connect.php');

?>

<!DOCTYPE html>
<html lang="en">


<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>

<!-- primary spot.tr javascript file. -->

<script src="spottr.js"></script>


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

<body ng-app="spottr" ng-controller="spottrCntrl">

<!-- THIS WOULD SERVE AS THE BUSINESS OBJECT AS IT FILLS THE TRANSFER OBJECT WITH DATA FROM THE DATABASE -->
<!-- BUSINESS OBJECT STARTS HERE -->
<script>

</script>
<!-- BUSINESS OBJECT ENDS HERE -->

    <div id="wrapper">

        <?php 
        //Allows ud to edit the navigation bar one time for all website pages.
        require_once('includes/nav_bar.php') 
        ?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <br />
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

                                <img src="{{picturePaths[0]}}" class="img-thumbnail" alt="Image1">
                                <img src="{{picturePaths[1]}}" class="img-thumbnail" alt="Image1">
                                <img src="{{picturePaths[2]}}" class="img-thumbnail" alt="Image1">
                                <img src="{{picturePaths[3]}}" class="img-thumbnail" alt="Image1">
                                <img src="{{picturePaths[4]}}" class="img-thumbnail" alt="Image1">

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

<!-- THIS WOULD SERVE AS THE CLIENT AS IT DISPLAYS THE OUTPUT FROM BOTH THE BUSINESS OBJECT AND TRANSFER OBJECT COMBINED -->
<!-- CLIENT STARTS HERE -->
                            <div id="map"></div>
                            <hr>
							<div id="buttonSpace">
                            <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#newLocationModal">Add New Location</button>
							<button type="button" id="img_upld" class="btn btn-info" data-toggle="modal" data-target="#uploadModal">Upload Image</button>
							</div>
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


    <!-- New Location Modal -->
    <div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog" aria-labelledby="newLocLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h2 class="modal-title d-inline" id="newLocLabel">Add spot to spot.tr!

            <button type="button" id="locModalCloseBtn" class="close d-inline" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </h2>

          </div>
          <div class="modal-body">
              <form>
                <div class="form-group row">
                  <label for="lgLocationName" class="col-sm-2 col-form-label col-form-label-lg">Location</label>
                  <div class="col-sm-10">
                    <input type="text" ng-model="locFormName" class="form-control form-control-lg" id="lgLocationName" placeholder="Taj Mahal">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="lgLocationLat" class="col-sm-2 col-form-label col-form-label-lg">Latitude</label>
                  <div class="col-sm-10">
                    <input type="number" ng-model="locFormLat" class="form-control form-control-lg" id="lgLocationLat" placeholder="37.63968970">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="lgLocationLong" class="col-sm-2 col-form-label col-form-label-lg">Longitude</label>
                  <div class="col-sm-10">
                    <input type="number" ng-model="locFormLong" class="form-control form-control-lg" id="lgLocationLong" placeholder="-120.99970480">
                  </div>
                </div>
              </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" id="locSave" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" class="btn btn-primary">Add Location</button>
          </div>
        </div>
      </div>
    </div>

<!-- Upload Image Modal -->
<div id="mapMarkerModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Photos at <span id="currentLocationName"></span></h4>
      </div>
      <div class="modal-body">
        <div class="well">
            This is where the photos go.
        </div>

        <!-- Form -->
        <hr />
        <h3>Do you have a photo at this spot? Add it below!</h3>
        <form method='post' action='' enctype="multipart/form-data">
            <span id='currentLocationID' style="display:none"></span>
          Select file : <input type='file' name='file' id='file' class='form-control' ><br>
          <input type='button' class='btn btn-info' value='Upload' id='uploadLoc'>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Upload Image Modal -->

<!-- Upload Image Modal -->
<div id="uploadModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Image Upload Form</h4>
      </div>
      <div class="modal-body">
        <!-- Form -->
    
        <form method='post' action='' enctype="multipart/form-data">
            <select name="location_id" id="img_upload_loc">
                <option>&nbsp;</option>
            </select>   
            <hr />
          Select file : <input type='file' name='file' id='file' class='form-control' ><br>
          <input type='button' class='btn btn-info' value='Upload' id='upload'>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End Upload Image Modal -->

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

<!-- THIS WOULD SERVE AS THE TRANSFER OBJECT AS THIS PULLS IN THE DATA FROM THE BUSINESS OBJECT TO INTERACT WITH IT -->
<!-- TRANSFER OBJECT STARTS HERE -->

<!-- TRANSFER OBJECT ENDS HERE -->


</body>

</html>