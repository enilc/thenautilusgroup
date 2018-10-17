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

      <title>Spot.tr</title>

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
         height: 600px;  /* The height is 600 pixels */
         width: 100%;  /* The width is the width of the web page */
        }

       .top-img {
        width: 100%;
       }

       .well-img {
        max-height: 300px;
       }

       .mr-5 {
        margin-right: 5px;
       }

       .mt-5 {
        margin-top: 5px;
       }

       .logo-img {
        width: 100%;
       }
      </style>

  </head>

  <body ng-app="spottr" ng-controller="spottrCntrl">

      <div id="wrapper">

          <?php 
          //Allows us to edit the navigation bar one time for all website pages.
          require_once('includes/nav_bar.php') 
          ?>

          <div id="page-wrapper">

              <div class="row">

                  <div class="col-lg-12">

                      <div class="panel panel-default mt-5">

                          <div class="panel-heading">

                              <i class="fa fa-map-marker fa-fw"></i>Spotted Locations

                              <div class="pull-right">

                                  <div class="btn-group">

                                    <button type="button" id="addLocBtn" class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#newLocationModal">Add Location</button>

                                    <button type="button" id="img_upld" class="btn btn-xs btn-primary mr-5" data-toggle="modal" data-target="#uploadModal">Add Image</button>

                                  </div>

                              </div>

                          </div>

                          <!-- /.panel-heading -->

                          <div class="panel-body">
                              <!-- DIV for the Google Map -->
                              <div id="map"></div>

                          </div>

                          <!-- /.panel-body -->

                      </div>

                      <!-- /.panel -->

                  </div>

                  <!-- /.col-lg-8 -->

              </div>

              <!-- /.row -->

              <div class="row">

                  <div class="hidden-sm hidden-xs col-lg-12">

                      <div class="panel panel-default">

                          <div class="panel-heading">

                              <i class="fa fa-map-marker fa-fw"></i>User Photos

                          </div>

                          <!-- /.panel-heading -->

                          <div class="panel-body">
                            
                    <div class="col-md-1">

                      <img src="{{picturePaths[0]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[1]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>
   
                    <div class="col-md-1">
   
                      <img src="{{picturePaths[2]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[3]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[4]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[5]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[6]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[7]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[8]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[9]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[10]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                    <div class="col-md-1">

                      <img src="{{picturePaths[11]}}" alt=".." class="img-rounded top-img img-thumbnail">

                    </div>

                          </div>

                          <!-- /.panel-body -->

                      </div>

                      <!-- /.panel -->

                  </div>

                  <!-- /.col-lg-12 -->

              </div>

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

                      <input type="text" ng-model="locFormName" class="form-control form-control-lg" id="lgLocationName" placeholder="Type New Location Name Here">

                    </div>

                  </div>

                  <div class="form-group row">

                    <label for="lgLocationLat" class="col-sm-2 col-form-label col-form-label-lg">Latitude</label>

                    <div class="col-sm-10">

                      <input type="number" ng-model="locFormLat" class="form-control form-control-lg" name="lgLocationLat" id="lgLocationLat">

                    </div>

                  </div>

                  <div class="form-group row">

                    <label for="lgLocationLong" class="col-sm-2 col-form-label col-form-label-lg">Longitude</label>

                    <div class="col-sm-10">

                      <input type="number" ng-model="locFormLong" class="form-control form-control-lg" name="lgLocationLong" id="lgLocationLong">

                    </div>

                  </div>

                </form>

            </div>

            <div class="modal-footer">

              <button type="button" id="locSave" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing Order" class="btn btn-success">Add Location</button>
  			
        			<button type="button" class="btn btn-outline-success" data-dismiss="modal">Close</button>

            </div>

          </div>

        </div>

      </div>

      <!-- Upload Image Modal (Marker Listener Context) -->
      <div id="mapMarkerModal" class="modal fade" role="dialog">

        <div class="modal-dialog">

          <!-- Modal content-->

          <div class="modal-content">

            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal">&times;</button>

              <h4 class="modal-title">Photos at <span id="currentLocationName"></span></h4>

            </div>

            <div class="modal-body">

              <div class="row" id='pictureWell'>

              </div>

              <hr />

              <h3>Do you have a photo at this spot? Add it below!</h3>

              <form method='post' action='' enctype="multipart/form-data">

                <span id='currentLocationID' style="display:none"></span>

                Select file : <input type='file' name='file' id='fileLoc' class='form-control' ><br>

                <input type='button' class='btn btn-info' value='Upload' id='uploadLoc'>

                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>

              </form>

            </div>

          </div>

        </div>

      </div>

      <!-- End Upload Image Modal (Marker Listener Context) -->

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

              <form method='post' action='' enctype="multipart/form-data">

              <select name="location_id" id="img_upload_loc">

                <option>&nbsp;</option>

              </select>   

              <hr />

              Select file : <input type='file' name='file' id='file' class='form-control' ><br>

              <input type='button' class='btn btn-info' value='Upload' id='upload'>

              <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>

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

      <!-- Metis Menu Plugin JavaScript 
      <script src="vendor/metisMenu/metisMenu.min.js"></script>-->


      <!-- Custom Theme JavaScript -->
      <script src="dist/js/sb-admin-2.js"></script>

  </body>

</html>