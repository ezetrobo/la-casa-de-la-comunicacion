function maps(params){
	Sawubona.map = this;
	var self = this;

	//	Parametros de configuracion
	self.url = (params != undefined && params.url != undefined) ? params.url : window.location.href;
	self.centerDefault = (params != undefined && params.center != undefined) ? params.center : {lat: -31.4001876, lng: -64.1819856}
	self.idContainer = (params != undefined && params.idContainer != undefined) ? params.idContainer : 'sawubona-map';
	self.idAutocomplete = (params != undefined && params.idAutocomplete != undefined) ? params.idAutocomplete : 'sawubona-map-autocomplete';
	self.key = (params != undefined && params.key != undefined) ? params.key : 'AIzaSyAnKNRFzUipr5i8KY5FvpZfzzASKMHB3mM';
	self.zoom = (params != undefined && params.zoom != undefined) ? params.zoom : 14;
	self.radio = (params != undefined && params.radio != undefined) ? params.radio : 5000;
	self.icons = (params != undefined && params.icons != undefined) ? params.icons : {'default' : Sawubona.baseUrl + 'img/map/marker-default.png', 'destacado' : Sawubona.baseUrl + 'img/map/marker-destacado.png', 'user' : Sawubona.baseUrl + 'img/map/marker-user.png'}
	self.centerUser = (params != undefined && params.centerUser != undefined) ? params.centerUser : true;

	//	Objetos utilizados
	self.map = null;
	self.circle = null;
	self.infowindow = null;
	self.directionsRenderer = null;
	self.directionsService = null;
	self.distance = null;
	self.autocomplete = null;
	self.user = null;
	self.markers = Array();

	//	Centra el mapa en el marcador que coincida con el id pasado por parametro (usada por los botones externos)
	self.center = function(idMarker){
		for(var i = 0; i < self.markers.length; i++){
			if(self.markers[i].idMarker == idMarker){
				//self.infowindow.close();
				self.map.setCenter(self.markers[i].marker.position);
				self.infowindow.setContent(self.markers[i].description);
				self.infowindow.open(self.map, self.markers[i].marker);
				self.map.setZoom(self.zoom);
				self.getDirections(self.markers[i].marker);
				break;
			}
		}
	}

	//	Crea los objetos (de google) con los que trabaja el mapa
	self.construct = function(){
		self.map = new google.maps.Map(
			document.getElementById(self.idContainer),
			{
				center: self.centerDefault,
				zoom: self.zoom
			}
		);
		self.circle = new google.maps.Circle(
			{
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 0,
				fillColor: '#FF0000',
				fillOpacity: 0.35,
				map: self.map,
				center: null,
				radius: self.radio / 2
			}
		);
		self.infowindow = new google.maps.InfoWindow();

		if(document.getElementById(self.idAutocomplete) != null){
			self.autocomplete = new google.maps.places.Autocomplete(
				(document.getElementById(self.idAutocomplete)),
				{types: ['geocode']}
			);
		}

		self.directionsRenderer = new google.maps.DirectionsRenderer({suppressMarkers: true});
		self.directionsService = new google.maps.DirectionsService();
		self.distance = new google.maps.DistanceMatrixService();
	}

	//	Cambia la posicion del usuario al completar la direccion
	self.getAutocomplete = function(){
		var autoComplete = document.getElementById(self.idAutocomplete);
		if(autocomplete != null){
			autocomplete.placeholder = 'Ingrese su dirección';

			self.autocomplete.addListener('place_changed', function(){
				var place = self.autocomplete.getPlace();
				var lat = place.geometry.location.lat();
				var lng = place.geometry.location.lng();
				if(lat != 0 && lng != 0){
					self.directionsRenderer.setMap();
					(self.user != null) ? self.user.marker.setPosition({lat: lat, lng: lng}) : self.user = new self.marker(0, 'Usted está aquí', lat, lng, self.icons.user);
					self.circle.setCenter(self.user.marker.position);
					self.circle.setMap(self.map);
					self.map.setCenter({lat: lat, lng: lng});
					self.infowindow.close();
					self.getDistance();
				}
			});
		}
	}

	//	Obtiene las indicaciones para llegar del origen al destino
	self.getDirections = function(marker){
		//	Si tengo las coordenadas del usuario
		if(self.user != null){
			self.circle.setMap();
			self.directionsRenderer.setMap(self.map);

			self.directionsService.route({
					origin: self.user.marker.position,
					destination: {lat: marker.position.lat(), lng: marker.position.lng()},
					travelMode: google.maps.TravelMode.DRIVING
				},
				function(result, status){
					if (status == google.maps.DirectionsStatus.OK){
						self.directionsRenderer.setDirections(result);
					}
				}
			);
		}
	}

	//	Obtiene la distancia entre el usuario y los demas marcadores
	self.getDistance = function(){
		//	Si tengo las coordenadas del usuario
		if(self.user != null){
			var offset = 0;
			var limit = 25;
			var k = 0;

			while(offset < self.markers.length){
				var destinations = Array();
				var markers = self.markers.slice(offset, offset + limit);

				for(var i = 0; i < markers.length; i++){
					destinations.push( new google.maps.LatLng(markers[i].marker.position.lat(), markers[i].marker.position.lng()) );
				}

				self.distance.getDistanceMatrix({
					origins: [self.user.marker.position],
					destinations: destinations,
					travelMode: google.maps.TravelMode.DRIVING,
					unitSystem: google.maps.UnitSystem.METRIC,
					avoidHighways: false,
					avoidTolls: false,
				},
				function(response, status){
					if(status == 'OK'){
						for(var i = 0; i < limit; i++){
							if(self.markers[k + i] != null)
								self.markers[k + i].marker.setIcon( (response.rows[0].elements[i].distance.value < (self.radio / 2) + 250 ) ? self.icons.destacado : self.icons.default );
						}

						k += limit;
						self.map.setZoom(self.zoom);
						self.circle.setCenter(self.user.marker.position);
					}
				});

				offset += limit;
			}
		}
	}

	//	Escribe en el documento la llamada a la Google API
	self.getGoogleApi = function(){
		var src = 	'https://maps.googleapis.com/maps/api/js'
				+	'?key=' + self.key
				+	'&libraries=places'
				+	'&callback=self.init';
		document.write('<' + 'script src="' + src + '"><' + '/script>');
	}

	//	Obtiene los marcadores
	self.getMarkers = function(){
		$.post(
			self.url,
			function(result){
				for(var i = 0; i < result.length; i++){
					self.markers.push( new self.marker(result[i].idMarcador, result[i].descripcion, result[i].latitud, result[i].longitud) );
				}
				self.getUserPosition();
			},
			'json'
		);
	}

	//	Obtiene la posicion del usuario
	self.getUserPosition = function(){
		if (navigator.geolocation){
			navigator.geolocation.getCurrentPosition(
				//	Si el usuario permite la posicion, creo el marcador y centro el mapa
				function(position){
					self.user = new self.marker(0, 'Usted está aquí.', position.coords.latitude, position.coords.longitude, self.icons.user);
					self.circle.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
					self.map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
					
					if(self.centerUser === false)
						self.map.setCenter(self.markers[0].marker.position);

					self.getDistance();
				},
				//	Sino, centro en el primer marker del vector, o en la posicion por defecto si no hay marcadores
				function(e){
					if(self.markers.length > 0)
						self.map.setCenter(self.markers[0].marker.position);
					else
						self.map.setCenter({lat: self.centerDefault.lat, lng: self.centerDefault.lng});
				}
			);
		}
	}

	//	Todas las funciones que se ejecutan al inicio
	self.init = function(){
		self.construct();
		self.getMarkers();
		self.getAutocomplete();
	}

	//	Class para construir el objeto marker (no es el mismo marker que el de google)
	self.marker = function(idMarker, description, lat, lng, icon){
		this.idMarker = idMarker || 0;
		this.description = description || null;
		this.lat = Number(lat) || null;
		this.lng = Number(lng) || null;
		this.marker = null;

		//	Si tengo latitud y longitud
		if(this.lat != null && this.lng != null){
			//	Creo el google marker
			this.marker = new google.maps.Marker({
				animation: (idMarker == 0) ? google.maps.Animation.DROP : null,
				position: {lat: this.lat, lng: this.lng},
				map: self.map,
				icon: (icon != undefined) ? icon : self.icons.default
			});
			//	Asigno el evento click
			this.marker.addListener('click', function(){
				self.infowindow.setContent(description);
				self.infowindow.open(self.map, this);
				//	Si el id es 0 significa q es el usuario
				if(idMarker != 0)
					self.getDirections(this);
			});
		}
	}

	self.getGoogleApi();
}