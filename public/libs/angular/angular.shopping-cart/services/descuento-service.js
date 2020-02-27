shop.service("DescuentoService", function($http, $q){

	//	Retorna todos los productos del carro
	this.getDescuento = function(){
		var defered = $q.defer();
		$http.get(baseUrl + 'shopping-cart/get-descuento/')
		.success(function(descuento){
			defered.resolve(descuento);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}

	//	Asgina el descuento al carro
	this.updateDescuento = function(codigo){
		var defered = $q.defer();
		$http.put(baseUrl + 'shopping-cart/update-descuento/' + codigo)
		.success(function(descuento){
			defered.resolve(descuento);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}
});