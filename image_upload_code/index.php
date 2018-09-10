<!DOCTYPE html>
	<html lang="en">
	<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<style>

	  /* Set the size of the div element that contains the map */
	  #map 
	{
        height: 500px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
    }

	.btn-file {
	    position: relative;
	    overflow: hidden;
	}
	.btn-file input[type=file] {
	    position: absolute;
	    top: 0;
	    right: 0;
	    min-width: 100%;
	    min-height: 100%;
	    font-size: 100px;
	    text-align: right;
	    filter: alpha(opacity=0);
	    opacity: 0;
	    outline: none;
	    background: white;
	    cursor: inherit;
	    display: block;
	}

	#img-upload{
	    width: 100%;
	}
	</style>
	</head>
	<body>
	<div class ="main">
    <p class="sansserif">
    <!--<img src ="C:\MAMP\htdocs\image_upload_code\Logo1.jpg" width="200"> -->
    <h1><center>S P O T .T R</center></h1>
    <!--The div element for the map -->
    <div id="map"></div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular.min.js"></script>
	
    <script src="script.js"></script>

    <!--Load the API from the specified URL
    * The async attribute allows the browser to render the page while the API loads
    * The key parameter will contain your own API key (which is not needed for this tutorial) I setup a key in geocoding api
    * The callback parameter executes the initMap() function
    -->
     <script async defer
     src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAxnMU9bIct86r3W4-rJ22Sirsli0U3uH4&callback=initMap">
     </script>
	 
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
	
<div id="map"></div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Location Name</h4>
      </div>
      <div class="modal-body">
		  <img src="\images\01.jpg" width="200" />
		  <img src="\images\02.jpg" width="200" />
		<img src="\images\03.jpg" width="200" />
		
		<div class="container" style="padding-top:50px">
			<h2>Upload Photo</h2>
			<form id="image_upload_form" method="post" enctype="multipart/form-data" action='upload.php' autocomplete="off">
				<div class="alert alert-danger hide"></div>
				<div class="alert alert-success hide"></div>
				<div class="col-md-6">
				    <div class="form-group">
				        <label>Upload Image</label>
				        <div class="input-group">
				            <input type="text" class="form-control" id="input_image_text" readonly>
				            <span class="input-group-btn">
				                <span class="btn btn-danger btn-file">
				                    Browse… <input type="file" id="photoimg" name="photoimg"/>
				                </span>
				                <button type="submit" class="btn btn-inverse">
				                    <i class="glyphicon glyphicon-upload"></i> upload
				                </button>
				            </span>
				        </div>
				    </div>
				</div>
			</form>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
	
	
	</body>
</html>



<script type="text/javascript">
	jQuery(document).ready(function(){
		$('#photoimg').on('change', function() {
			var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, ''),
			 input = $('#input_image_text').val(label);
			 $('.alert').addClass('hide');
			 $('.alert').removeClass('show');

		});

		var frm = $('#image_upload_form');

    frm.submit(function (e) {
    	e.preventDefault();
    	var formData = new FormData();
formData.append('photoimg', $('#photoimg')[0].files[0]);

        
        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: formData,
            dataType: "json",
            processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType

            success: function (data) {
                console.log(data['error']);
                if(data.error == 1) {
                	$('.alert-danger').removeClass('hide').addClass('show').html(data['msg']);
                } else {
                	$('.alert-success').removeClass('hide').addClass('show').html('Uploaded');
                console.log(data);
                }
                
            },
            error: function (data) {
                console.log(data);
                $('.alert-danger').removeClass('hide').addClass('show').html(data);
            },
        });
		});  
	});
</script>