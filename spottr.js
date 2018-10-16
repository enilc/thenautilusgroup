/**********************************************************************
  spot.tr primary javascript. 

  This page reacts to the user and handles Google Map details on load.

  Started AngularJS to comply with requirements last class, but switched
  to jQuery because none of us had been able to figure AngularJS ont and
  we needed to get the app going.

  A hypothetical version 2 of Spottr would see this section extensively
  retooled.

/**********************************************************************/


/****************Global Variables**************************************/

//Custom ICON for Google Map Markers
var MAP_MARKER_IMG = '/images/markerSmall1.png';

//Spottr brand styling for the Google Map
var MAP_STYLE = [
            {
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#f5f5f5"
                }
              ]
            },
            {
              "elementType": "labels.icon",
              "stylers": [
                {
                  "visibility": "off"
                }
              ]
            },
            {
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#616161"
                }
              ]
            },
            {
              "elementType": "labels.text.stroke",
              "stylers": [
                {
                  "color": "#f5f5f5"
                }
              ]
            },
            {
              "featureType": "administrative.land_parcel",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#bdbdbd"
                }
              ]
            },
            {
              "featureType": "landscape.natural.terrain",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#404040"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#eeeeee"
                }
              ]
            },
            {
              "featureType": "poi",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#757575"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#e5e5e5"
                }
              ]
            },
            {
              "featureType": "poi.park",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            },
            {
              "featureType": "road",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#ffffff"
                }
              ]
            },
            {
              "featureType": "road.arterial",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#757575"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#1d9e5f"
                }
              ]
            },
            {
              "featureType": "road.highway",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#616161"
                }
              ]
            },
            {
              "featureType": "road.local",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            },
            {
              "featureType": "transit.line",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#e5e5e5"
                }
              ]
            },
            {
              "featureType": "transit.station",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#eeeeee"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "geometry",
              "stylers": [
                {
                  "color": "#c9c9c9"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "geometry.fill",
              "stylers": [
                {
                  "color": "#2b2b2b"
                }
              ]
            },
            {
              "featureType": "water",
              "elementType": "labels.text.fill",
              "stylers": [
                {
                  "color": "#9e9e9e"
                }
              ]
            }
          ];


//AngularJS instance of the Spottr app.
var app = angular.module('spottr', []);
app.controller('spottrCntrl', function($scope, $http) {
    //Initialize paths to photos in the database
    $scope.picturePaths = [];

    //On load, Spottr requests a list of the photos in the database
    $http({
    method: 'POST',
    url: 'etc/db_interface.php',
    headers: {
    'Content-Type': 'application/json'
    },
    data: { key: 'B52C106C63CB00C850584523FB0EC12',
        action: 'select',
        table: 'Photo',
        columns: ['path','location']}
    }).then(function successCallback(response) {
        /*********************************
          Note: Version 1 of Spottr pulls
                all images from the  data-
                base, but this will be 
                impractical as the database
                grows. 

                This call should be aware of
                locations in window and should
                fill that way.
        *********************************/

        //We shuffle the returned JSON
        shuffle(response.data);

        //We add all our photos to the path
        response.data.forEach(function(element) {
          $scope.picturePaths.push(element['path']);
        });
    }, function errorCallback(response) {
      //Log the callback
      console.log("AngularJS Call for List of Photos failed.");
    });



		//Pull our locations from the database.
		$http({
		method: 'POST',
		url: 'etc/db_interface.php',
		headers: {
		'Content-Type': 'application/json'
		},
		data: { key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'select',
				table: 'Location',
				columns: ['loc_name','latitude','longitude','location_id']}
		}).then(function successCallback(response) {
        //Once the database has sent known locations,
        //add them to the map.
        addMapMarkers(response.data);
		}, function errorCallback(response) {
			//Log if there is an error
			console.log("AngularJS Call for List of Locations failed.");
		});



	});



//jQuery is used for dynamic content.
$('document').ready(function () {

  //This function is invoked when a user tries to add a location to the datbase.
	$('#locSave').on('click', function() {

    //Ensure that all fields are filled out before processing
		if($('#lgLocationName').val() !== "" && $('#lgLocationLat').val() !== "" && $('#lgLocationLong').val() !== ""){

      //Switch the button to a loading state
			$('#locSave').button('loading');

      //Add the location to the database.
			$.ajax({
			url: "etc/db_interface.php",
			type: "post",
			data: {
				key: 'B52C106C63CB00C850584523FB0EC12',
				action: 'insert',
				table: 'Location',
				columns: ['loc_name','latitude', 'longitude'],
				values: [$('#lgLocationName').val(),$('#lgLocationLat').val(),$('#lgLocationLong').val()]
			},
			dataType: "text",
			success: function(response) {
        //Return our loading button to a clickable state
				$('#locSave').button('reset');

        //Close the add location modal
				$('#newLocationModal').modal('toggle');

        //Reload the page for the user.
				location.reload();
			  }
		  });
		}
	});

  //This function is invoked when a user tries to upload an image without specifying a lcoation
	$('#img_upld').on('click', function(){

      //This call populates a flat list of locations to choose from
      //when uploading a file.
			$.ajax({
				url: "etc/db_interface.php",
				type: "post",
				data: {
					key: 'B52C106C63CB00C850584523FB0EC12',
					action: 'select',
					table: 'Location',
					columns: ['location_id','loc_name'],
				},
				dataType: "text",
				success: function(response) {
          //Parse our response
					var resp = JSON.parse(response);

          //grab the select field we wish to populate
					var sel = $('#img_upload_loc');

					for(i in resp){
            //Add a location name/id pair per loaction to the select element
						jQuery('<option/>', {
							value: resp[i]['location_id'],
							html: resp[i]['loc_name']
						}).appendTo(sel);
					}
				}
			});
	});

  //This function is invoked when a user tries to add a location
  //without clicking on the main Google Map.
  $('#addLocBtn').on('click',function () {

      //Reset latitude and longitude to their default values.
      $('#lgLocationLat').val('');
      $('#lgLocationLong').val('');

      //Pull up the modal.
      $('#newLocationModal').show();
  });

  //This function is invoked when an image upload
  //button is invoked from the general dialog
  $('#upload').on('click', function(){

    var fd = new FormData();
    var files = $('#file')[0].files[0];
    fd.append('file',files);
    fd.append('location_id',$('#img_upload_loc').val())
    //TODO: Error without a location selected.

    if($('#img_upload_loc').val() === ''){
      alert('Please select a location!');
    } else {

      // Now we post the file to upload.php for processing.
      $.ajax({
        url: 'etc/upload.php',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
          //Note: Remove this in the future. It's annoying
          alert('Image Successfully Uploaded!');

          //Reload the page for the user after the image uploads
          location.reload();
        }
      });
      
    }
  });

  //This function is invoked when an image upload
  //button is invoked from a marker click dialog
   $('#uploadLoc').on('click', function(){

    var fd = new FormData();
    var files = $('#fileLoc')[0].files[0];
    fd.append('file',files);
    fd.append('location_id',$('#currentLocationID').html());

    if($('#currentLocationID').html() === ''){
      //Note: Should never hit this. This field is populated on modal launch.
      alert('Please select a location!');
    } else {

      // Now we post the file to upload.php for processing.
      $.ajax({
        url: 'etc/upload.php',
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
          //Note: Remove this in the future. It's annoying
          alert('Image Successfully Uploaded!');

          //Reload the page for the user after the image uploads
          location.reload();
        }
      });
      
    }
  });
});


//This is stub to handle geolocation blocking. Will need to be fleshed out
//when Spottr purchases an SSL certificate for encrypted communication.
function handleLocationError(flag,center){
  return
}

function addMapMarkers(locations){

    //Temporary, Default location is Modesto, CA, which is the near
    //each member of the Spottr Development team.
    var pos = {
      lat: 37.63419900, 
      long: -120.98063900
    }

    //When we switch to HTTPS...this will auto-center.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        pos = {
          lat: position.coords.latitude,
          long: position.coords.longitude
          };
    
      }, function() {
    handleLocationError(true, map.getCenter());
    });
    } else {
    // Browser doesn't support Geolocation
    handleLocationError(false, map.getCenter());
    }

    //Create our google map
    map = new google.maps.Map(document.getElementById('map'), {
          //Center on our derived position.
          center: new google.maps.LatLng(pos['lat'], pos['long']),
          zoom: 9,
          fullscreenControl: false,
          //****"styles" is a JSON Object to draw the map, green road color is the same as the logo #1D935F *****/
          styles: MAP_STYLE
        });

    //Add a listener to the whole map that captures the latitude
    //and longitude of a click. This allows users to add locations
    //by just clicking on the appropriate spot on the Google Map
    google.maps.event.addListener(map, 'click', function( event ){ 

      //Populate the Latitude and Longitude fields of the 
      //"New Location" Modal for the user, with the location
      //they clicked on.
      $('#lgLocationLat').val(event.latLng.lat());
      $('#lgLocationLong').val(event.latLng.lng());

      //Show the "New Location" Modal
      $("#newLocationModal").modal('show');
      
    });


    //Declare what we need to create our markers
    var marker, i;

  //This loop adds markers to to the Google Map
  for (i = 0; i < locations.length; i++) {  
      //We create one marker per location
      marker = new google.maps.Marker({
           //Marker's Position
           position: new google.maps.LatLng(locations[i][1], locations[i][2]),

           //Path to Custom Icon
           icon: MAP_MARKER_IMG,

           //The google map we wish to add the marker to
           map: map
      });


      //For each marker we create, create an onClick listener that pulls
      //up a modal window with example photos and location_id auto-populated
      //for contextual import.
	    google.maps.event.addListener(marker, 'click', (function(marker, i) {

         //Store contextual variable in listener. Acting as a closure
	    	 var nm = locations[i]['loc_name'];
	    	 var id = locations[i]['location_id'];

          //Return a function to execute on marker click
	         return function() {

            //Change the location ID in the Marker Modal
	         	$('#currentLocationID').html(id);

            //Add the location name to the Marker Modal
	         	$('#currentLocationName').html(nm);

            //Clear the picture preview
            $('#pictureWell').html('');

            //Show the Marker Modal (while we wait for the photos to load)
            $("#mapMarkerModal").modal('show');

            //Query the database for photos at this location
            $.ajax({
              url: "etc/db_interface.php",
              type: "post",
              data: {
                key: 'B52C106C63CB00C850584523FB0EC12',
                action: 'select',
                table: 'Photo',
                columns: ['path'],
                filter: [['location',id]]
              },
              dataType: "text",
              success: function(response) {

                //Parse our response to a JSON object
                var resp = JSON.parse(response);

                //resp will be an array, shuffle it so we don't always
                //present the same photos
                shuffle(resp);

                //Add three photos to the Marker Modal from this location
                for(var i = 0; i < 3 && i < resp.length; i++){
                  //Wrap the images in Bootstrap Columnar divs.
                  //Hide all images beyond the second.
                  var dv = jQuery('<div/>', {
                    class: ((i < 2) ? 'col-sm-12 col-md-4' : 'hidden-sm hidden-xs col-md-4')
                  }).appendTo($('#pictureWell'));
                  
                  //Create our iamge object itself and add to Bootstrap Columnar Div
                  jQuery('<img/>', {
                    src: resp[i]['path'],
                    class: 'img-rounded well-img img-thumbnail marker-img'
                  }).appendTo(dv);
                }
              }
            });


	         }
	    })(marker, i));
	}


}

//Shuffle function for all the randomization
//we need right now.
//Credit: https://stackoverflow.com/a/6274381
function shuffle(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}