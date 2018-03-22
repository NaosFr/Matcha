var layer = 0;

mapboxgl.accessToken = 'pk.eyJ1IjoibmNlbGxhOTgiLCJhIjoiY2oydWE5MjRkMDA4cDMybWo5OHIyNG9oaiJ9.FPbLepZHRQXMAbaInwLFog';
var map = new mapboxgl.Map({
	container: 'map',
	style: 'mapbox://styles/mapbox/light-v9',
	center: [2.318371, 48.896663],
	zoom: 4
});

function show_position(latitude, longitude){
	    map.loadImage('../assets/icon/piker.png', function(error, image) {
	        if (error) throw error;
	        map.addImage('piker', image);
	        map.addLayer({
	            "id": "symbols_" + layer,
	            "type": "symbol",
	            "source": {
	                "type": "geojson",
	                "data": {
	                    "type": "FeatureCollection",
	                    "features": [{
	                        "type": "Feature",
	                        "geometry": {
	                            "type": "Point",
	                            "coordinates": [longitude, latitude]
	                        }
	                    }]
	                }
	            },
	            "layout": {
	                "icon-image": "piker",
	                "icon-size": 0.1
	            }
	        });
	    });
}


function reload_position(latitude, longitude){		
			map.removeLayer('symbols_' + layer);
			layer++;

			map.addLayer(
			{
				"id": "symbols_" + layer,
				"type": "symbol",
				"source":
				{
					"type": "geojson",
					"data":
					{
						"type": "FeatureCollection",
						"features": [
						{
							"type": "Feature",
							"properties": {},
							"geometry":
							{
								"type": "Point",
								"coordinates": [longitude, latitude]
							}
						}]
					}
				},
				"layout":
				{
					"icon-image": "piker",
					"icon-size": 0.1
				}
			});
}

function add_location()
{
	var formData = {
	            'latitude'      	: latitude,
	            'longitude'      	: longitude,
	            'submit'    		: "add_location"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : '../php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            }
	        })
}

function add_location_auto()
{
	    if (navigator.geolocation) {
	        navigator.geolocation.getCurrentPosition(showPosition);
	    } else { 
	        alert("Geolocation is not supported by this browser.");
	    }
}


function showPosition(position) {

	    var formData = {
	            'latitude'      	: position.coords.latitude,
	            'longitude'      	: position.coords.longitude,
	            'submit'    		: "add_location"
	        };

	        $.ajax({
	            type        : 'POST',
	            url         : '../php/setting.php',
	            data        : formData,
	            encode      : true,
	            success		: function(data){
	            	$('#alert').html(data);
	            	reload_position(position.coords.latitude, position.coords.longitude);
	            }
	        })
}






