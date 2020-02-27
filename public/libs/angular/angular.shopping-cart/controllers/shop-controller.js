shop.controller('ShopController', function($scope, $http, ProductosService, OpcionPrecioService, DescuentoService, LanguajeService, TipoEnvioService){
	$scope.productos = Array();
	
	$scope.tiposEnvio = Array();
	$scope.tempZonas = Array();
	$scope.tipoEnvio = null;

	$scope.opcionesPrecio = Array();
	$scope.opcionPrecio = null;
	
	$scope.subtotal = 0;
	$scope.total = 0;
	$scope.descuento = {'codigo': null, 'valor': 0};
	$scope.languaje = null;

	//	----------------------------------------------------------------------------	//
	//	GETTERS | Traen el objeto del shop, se asignan en el objeto del controller
	//	----------------------------------------------------------------------------	//

	//	Retorna la opcion de precio
	$scope.getOpcionPrecio = function(){
		OpcionPrecioService.getOpcionPrecio()
		.then(function(opcionPrecio){
			$scope.opcionPrecio = opcionPrecio;
		})
		.catch(function(e){
			console.log(e);
		});
	}

	//	Retorna todas las opciones de precio disponible
	$scope.getOpcionesPrecio = function(){
		OpcionPrecioService.getOpcionesPrecio()
		.then(function(opcionesPrecio){
			$scope.opcionesPrecio = opcionesPrecio;
		})
		.catch(function(e){
			console.log(e);
		});
	}	

	//	Retorna todos los productos del shop
	$scope.getProductos = function(){
		ProductosService.getProductos()
		.then(function(productos){
			$scope.productos = productos;
			$scope.setTotal();
		})
		.catch(function(e){
			console.log(e);
		});
	}

	//	Retorna el Descuento
	$scope.getDescuento = function(){
		DescuentoService.getDescuento($scope.descuento.codigo)
		.then(function(descuento){
			$scope.descuento = descuento;
			$scope.setTotal();
		})
		.catch(function(e){
			console.log(e);
		});
	}

	//	Retorna los tipos de envio
	$scope.getTiposEnvio = function(){
		TipoEnvioService.getTiposEnvio()
		.then(function(tiposEnvio){
			$scope.tiposEnvio = tiposEnvio;
		})
		.catch(function(e){
			console.log(e);
		});
	}

	//	----------------------------------------------------------------------------	//
	//	SETTERS
	//	----------------------------------------------------------------------------	//

	//	Pausado hasta definir en FFTT
	$scope.setTipoEnvio = function(object, order){
		/*$scope.tipoEnvio = $scope.tiposEnvio[0];
		switch(order){
			case 1:
				$scope.tempZonas[1] = object;
				break;
			case 2:
				$scope.tempZonas[2] = object;
				break;
			case 3:
				break;
		}*/
	}

	//	Calcula el subtotal (suma de productos por cantidades)
	$scope.setSubtotal = function(){
		$scope.subtotal = 0;
		$scope.cantidad = 0;

		for(var i = 0; i < $scope.productos.length; i++){
			$scope.subtotal += $scope.productos[i].cantidad * $scope.productos[i].precio;
			$scope.cantidad += $scope.productos[i].cantidad;
		}
	}

	//	Calcula el total (subtotal - descuento + envio)
	$scope.setTotal = function(){
		$scope.setSubtotal();
		$scope.total = $scope.subtotal - $scope.descuento.valor;
	}

	//	----------------------------------------------------------------------------	//
	//	ACCIONES
	//	----------------------------------------------------------------------------	//	

	//	Elimina un producto del shop
	$scope.deleteProducto = function(idProducto){
		ProductosService.deleteProducto(idProducto)
		.then(function(r){
			if(r.code == 8){
				for(var i = 0; i < $scope.productos.length; i++){
					if($scope.productos[i].idProducto == idProducto)
						$scope.productos.splice(i, 1);
				}
			}
			$scope.setTotal();
			$().toast(r.message);
		})
		.catch(function(e){
			console.log(e);
		})
	}

	//	Resetea el carro
	$scope.reset = function(){
		$http.delete(baseUrl + 'shopping-cart/reset')
		.success(function(r){
			$scope.getProductos();
			$scope.getDescuento();
			$scope.getTiposEnvio();
		})
		.error(function(e){
			defered.reject(e);
		});
	}

	//	Elimina todos los productos (revisar)
	$scope.deleteProductos = function(){
		ProductosService.deleteProductos()
		.then(function(productos){

		})
		.catch(function(e){
			console.log(e);
		});
	}

	//	Actualiza la cantidad de unidades de un producto
	$scope.updateProducto = function(idProducto){
		var producto = null;
		for(var i = 0; i < $scope.productos.length; i++){
			if(idProducto == $scope.productos[i].idProducto){
				producto = $scope.productos[i];
				break;
			}
		}

		if(producto != null){
			ProductosService.updateProducto(producto.idProducto, producto.cantidad)
			.then(function(r){
				$scope.setTotal();
				$().toast(r.message);
			})
			.catch(function(e){
				$().toast(e.message);
			});
		}
	}

	//	Actualiza el Descuento de la compra
	$scope.updateDescuento = function(){
		if($scope.descuento.codigo != null && $scope.descuento.codigo != ''){
			DescuentoService.updateDescuento($scope.descuento.codigo)
			.then(function(descuento){
				$scope.descuento = descuento;
				$scope.setTotal();
				$().toast(descuento.message);
			})
			.catch(function(e){
				console.log(e);
			});
		}
	}

	//	----------------------------------------------------------------------------	//
	//	UTILIDADES
	//	----------------------------------------------------------------------------	//	

	//	Retorna un vector de enteros entre from y to
	$scope.range = function(from, to){
		var result = Array();
		if(from <= to){
			for(var i = from; i <= to; i++)
				result.push(i);
		}
		return result;
	}

	//	----------------------------------------------------------------------------	//
	//	IDIOMA
	//	----------------------------------------------------------------------------	//		
	$scope.setLanguaje = function(){
		LanguajeService.get()
		.then(function(languaje){
			$scope.languaje = languaje;
		}).
		catch(function(e){
			console.log(e);
		});
	}

	//	----------------------------------------------------------------------------	//
	//	AL INCIAR EL CARRO
	//	----------------------------------------------------------------------------	//		
	$scope.init = function(){
		$scope.setLanguaje();
		//$scope.getOpcionPrecio();
	}
});