shop.service("ProductosService", function($http, $q){

	//	Retorna todos los productos del carro
	this.getProductos = function(){
		var defered = $q.defer();
		$http.get(baseUrl + 'shopping-cart/get-productos')
		.success(function(productos){
			defered.resolve(productos);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}

	//	Agrega un producto al carro, si existe incrementa la cantidad
	this.addProducto = function(idProducto, cantidad){
		var defered = $q.defer();
		var data = {
			'idProducto' : idProducto,
			'cantidad' : cantidad
		}

		$http.post(baseUrl + 'shopping-cart/add-producto/')
		.success(function(result){
			defered.resolve(result);
		})
		.error(function(e){
			defered.reject(e);
		});
	}

	//	Elimina un producto del carro
	this.deleteProducto = function(idProducto){
		var defered = $q.defer();
		$http.delete(baseUrl + 'shopping-cart/delete-producto/' + idProducto)
		.success(function(result){
			defered.resolve(result);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}

	//	Elimina todos los producto del carro
	this.deleteProductos = function(){
		var defered = $q.defer();
		$http.delete(baseUrl + 'shopping-cart/delete-productos/')
		.success(function(result){
			defered.resolve(result);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}

	//	Actualiza la cantidad de productos en el carro
	this.updateProducto = function(idProducto, cantidad){
		var defered = $q.defer();
		$http.put(baseUrl + 'shopping-cart/update-producto/' + idProducto + '/' + cantidad)
		.success(function(result){
			defered.resolve(result);
		})
		.error(function(e){
			defered.reject(e);
		});

		return defered.promise;
	}
});