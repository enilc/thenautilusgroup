/**********************************************************************
spot.tr primary javascript. 



/**********************************************************************/

var MAP_MARKER_IMG = '/images/markerSmall1.png';
var ACTIVE_LOCATION_NAME = null;
var ACTIVE_LOCATION_ID = null;
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


var app = angular.module('spottr', []);
app.controller('spottrCntrl', function($scope, $http) {
		$scope.picturePaths = [];

		/***************TODO************************
			This section is supposed to be able to
			tell what markers are on the google
			maps api.

			Current iteration, naively, grabs all
			locations and adds to map. This is
			probably okay, but we would then need
			to know what locations are showing up
			because we want to populate the thumbs.
		/***********END TODO************************/
		//Pulls loc_name, latitude, and longitude from our DB
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
			// this callback will be called asynchronously
			// when the response is available
            addMapMarkers(response.data);
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});


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
			// this callback will be called asynchronously
			// when the response is available
            console.log(response.data)
            response.data.forEach(function(element) {
            	$scope.picturePaths.push(element['path']);
            });
		}, function errorCallback(response) {
			// called asynchronously if an error occurs
			// or server returns response with an error status.
			$scope.select_test = "FAILED (callback)";
		});
	});

$('document').ready(function () {
	$('#locSave').on('click', function() {
		if($('#lgLocationName').val() !== "" && $('#lgLocationLat').val() !== "" && $('#lgLocationLong').val() !== ""){
			$('#locSave').button('loading');
      console.log('what is going on with location adding?');
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
					console.log(response);
					$('#locSave').button('reset');
					$('#newLocationModal').modal('toggle');
					location.reload();
				}
			});
		}
	});

	$('#img_upld').on('click', function(){


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
					var resp = JSON.parse(response);

					var sel = $('#img_upload_loc');

					for(i in resp){
						jQuery('<option/>', {
							value: resp[i]['location_id'],
							html: resp[i]['loc_name']
						}).appendTo(sel);
					}
				}
			});
	});
});

//AJAX code to upload image
$(document).ready(function(){
  $('#upload').on('click', function(){

    var fd = new FormData();
    var files = $('#file')[0].files[0];
    fd.append('file',files);
    fd.append('location_id',$('#img_upload_loc').val())
    //TODO: Error without a location selected.

    // AJAX request
    $.ajax({
      url: 'upload.php',
      //url: 'scratch.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function(response){
      	console.log(response);
        alert('Image Successfully Uploaded!');
		    location.reload();
      }
    });
  });

   $('#uploadLoc').on('click', function(){

    var fd = new FormData();
    var files = $('#fileLoc')[0].files[0];
    fd.append('file',files);
    fd.append('location_id',$('#currentLocationID').html());

    // AJAX request
    $.ajax({
      url: 'upload.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function(response){
      	console.log(response);
        alert('Image Successfully Uploaded!');
		location.reload();
      }
    });
  });
});


function handleLocationError(flag,center){
  return
}

function addMapMarkers(locations){
    var pos = {
      lat: 37.63419900, 
      long: -120.98063900
    }

    //When we switch to HTTPS...this will auto-center.
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        console.log(position);
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


map = new google.maps.Map(document.getElementById('map'), {
          //For alpha, just centering on Modesto. Need to get a default location from users...gonna have to figure this out later.
          center: new google.maps.LatLng(pos['lat'], pos['long']),
          zoom: 9,
          //****JSON Object to draw the map, green road color is the same as the logo #1D935F *****/
          styles: MAP_STYLE
        });

    google.maps.event.addListener(map, 'click', function( event ){ 
      $("#newLocationModal").modal('show');
      $('#lgLocationLat').val(event.latLng.lat());
      $('#lgLocationLong').val(event.latLng.lng());
      //alert( "Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng() ); 
    });


    var marker, i;

  for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
           position: new google.maps.LatLng(locations[i][1], locations[i][2]),
           icon: MAP_MARKER_IMG,
           map: map
      });


	    google.maps.event.addListener(marker, 'click', (function(marker, i) {
	    	 var nm = locations[i]['loc_name'];
	    	 var id = locations[i]['location_id'];

	         return function() {

	         	$('#currentLocationID').html(id);
	         	$('#currentLocationName').html(nm);
            //Clear the picture preview
            $('#pictureWell').html('');
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
                console.log(response);
                var resp = JSON.parse(response);

                for(var i = 0; i < 3 && i < resp.length; i++){
                  var dv = jQuery('<div/>', {
                    class: ((i < 2) ? 'col-sm-12 col-md-4' : 'hidden-sm hidden-xs col-md-4')
                  }).appendTo($('#pictureWell'));
                  
                  jQuery('<img/>', {
                    src: resp[i]['path'],
                    class: 'img-rounded well-img img-thumbnail marker-img'
                  }).appendTo(dv);
                }
              }
            });


	          $("#mapMarkerModal").modal('show');
	         }
	    })(marker, i));
	}


}