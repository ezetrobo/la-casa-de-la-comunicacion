shop.service("OpcionPrecioService", function($http, $q){
	//	Retorna la opcion de precio seleccionada
	this.getOpcionPrecio = function(){
		var defered = $q.defer();

		$http.get(baseUrl + 'shopping-cart/get-opcion-precio')
		.success(function(opcionPrecio){
			defered.resolve(opcionPrecio);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}

	//	Retorna todas las opciones de precio disponibles
	this.getOpcionesPrecio = function(){
		var defered = $q.defer();

		$http.get(baseUrl + 'shopping-cart/get-opciones-precio')
		.success(function(opcionPrecio){
			defered.resolve(opcionPrecio);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}
});