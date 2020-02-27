shop.service("TipoEnvioService", function($http, $q){

	//	Retorna todos los tipos de envio
	this.getTiposEnvio = function(){
		var defered = $q.defer();
		$http.get(baseUrl + 'shopping-cart/get-tipos-envio/')
		.success(function(tiposEnvio){
			defered.resolve(tiposEnvio);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}
});