

/********************************************************************************************************
 * Used the Builder Design Pattern 
 * 
 * The Builder is a design pattern designed to provide a flexible solution to various object creation 
 * problems in object-oriented programming. The intent of the Builder design pattern is to separate the 
 * construction of a complex object from its representation.
 * 
 * This Design Pattern will be integrated into the main branch 
 * 
 *********************************************************************************************************/

function initMap(){
	
	
		var modestoCenter = {
		info: '<strong>Modesto Center Plaza</strong><br>\
							1000 L St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/dv8taNu62zq">Get Directiongs</a>',
							
		lat: 37.642187,
		long: -121.001921
							
	};
	
		var theCentury = {
		info: '<strong>The Century</strong><br>\
							972 10th St<br> Modesto, CA 95354<br>\
							<a href="https://goo.gl/maps/eWbJy3afEqN2">Get Directiongs</a>',
							
		lat: 37.639793,
		long: -120.999968
							
	};
	
		var donnellyPark = {
		info: '<strong>Donnelly Park</strong><br>\
							600 Pedras Rd<br> Turlock, CA 95382<br>\
							<a href="https://goo.gl/maps/kpttSy898Cq">Get Directiongs</a>',
							
		lat: 37.510958,
		long: -120.856358
							
	};
	
	var locations = [
	[modestoCenter.info, modestoCenter.lat, modestoCenter.long, 0],
	[theCentury.info, theCentury.lat, theCentury.long, 1],
	[donnellyPark.info, donnellyPark.lat, donnellyPark.long, 2],
	];

	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 6, 
		center: new google.maps.LatLng(37.510958, -120.856358),
		});
		
			
let test = new spotterMapMarker.Builder()
.withlat(locations[0][1])  // Test marker for modestoCenter lat
.withlong(locations[0][2]) // Test marker for modestoCenter long
.withname(locations[0][0])
.withmap(map)
.build();

}
/********************************************************************************************************
 * 
 * The Builder Design Pattern to create marker on map
 * Generally Builder Design Pattern is not used in JavaScript, so it was a challenge to implement, but I 
 * was able to find enough recourses online and working with group
 * 
 *********************************************************************************************************/

class spotterMapMarker{
	
	constructor(build){

this.marker = build.marker;
// fututre code to add info window into the builder pattern
this.infowindow = build.infowindow;
this.map = map;
console.log(this.infowindow)

google.maps.event.addListener(this.marker, 'click', (function (){
	
	console.log()
	return function (){
		
		this.infowindow.setContent,(this.infowindow);
		this.infowindow.open(this.map, this.marker);
		
		// fututre code to add info window into the builder pattern
	}
})(this.marker));

		console.log("We made it") //test
	}

	static get Builder(){
		class Builder{
			constructor(infowindow){
				this.lat = 0.0;
				this.long = 0.0;
				this.name = "";
				this.map = null;
				console.log("We got here") //test
			}
	withlat(lat){
		this.lat = parseFloat(lat);
		return this;
	}
	withlong(long){
		this.long = parseFloat(long);
		return this;
	}
	withname(name){
		this.name = name;
		return this;
	}
	withmap(map){
		this.map = map;
		return this;
	}

			build(){
				this.marker = new google.maps.Marker({
					position: new google.maps.LatLng(this.lat, this.long),
					map: this.map,	
		});
		
		// fututre code to add info window into the builder pattern
		this.infowindow = new google.maps.InfoWindow({
			title: "title",
			content: this.name
		});
			return new spotterMapMarker(this);	
		}
		}
	return Builder;
	}
}

