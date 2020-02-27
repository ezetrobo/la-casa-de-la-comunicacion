shop.service("LanguajeService", function($http, $q){

	this.get = function(){
		var defered = $q.defer();
		var idioma = 'eng';

		//	Falta setear el idioma segun la URL

		$http.get(baseUrl + 'idiomas/' + idioma + '/shopping-cart.json')
		.success(function(descuento){
			defered.resolve(descuento);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}
});