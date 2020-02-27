<?php

namespace App\Http\Controllers;

use App\Clases\Carrito\Ecommerce;
use App\Clases\Carrito\ProductoCarrito;
use App\Clases\Carrito\cCarrito;
use App\Clases\Persona\Direccion;
use App\Clases\Persona\Persona;
use App\Clases\Persona\Telefono;
use App\Clases\Producto\Categoria;
use App\Clases\Producto\Producto;
use App\Sawubona\Sawubona;
use Illuminate\Http\Request;
use View;

class CarritoController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index() {
		//
	}

	/**
	*	Funcion agregar producto al carro
	**/
	public function addProducto($xIdProducto = null, $xCantidad = 0, $xImg, $xIdProductoDep = 0) {
		try {
			$xParams = array();
			if ($_POST):
				$xIdProducto = $_POST['idProducto'];
				$xCantidad = $_POST['cantidad'];
			endif;

			$xIdioma = config('main.IDIOMA_DEFAULT');
			
			$xCarrito = new cCarrito();
			$xCarrito-> iniciarCarrito();

			if ($xCarrito->idioma == null):
				$xCarrito->setIdioma($xIdioma);
			endif;

			$oProducto = new Producto();
			$oProducto = $oProducto->getById($xIdProducto);
			$bonificado = strip_tags($oProducto->servicio);

			if ($xImg != ""):
				$imagen = $xImg;
			else:
				if (!empty($oProducto->imagenes)):
					foreach ($oProducto->imagenes as $oImagen):
						if ($oImagen->nombre != 'big'):
							$imagen = $oImagen->path;
						endif;
					endforeach;
				else:
					$imagen = "/img/default/producto.jpg";
				endif;
			endif;

		if($xCantidad > 0):
			if (!empty($oProducto->stocks)):
				if ($oProducto->stocks[0] != null and $oProducto->stocks[0] != 0):
					if ($xCarrito->validarStock($xIdProducto, $oProducto->stocks[0], $xCantidad)):
						
						if ($xIdioma == 'en'):
							$xDescripcion = $oProducto->productosDep[0]->titulo;
						else:
							$xDescripcion = $oProducto->titulo;
						endif;

						if (!empty($oProducto->precios)):
							if ($oProducto->precios[0] >= 0):
								$xPrecio = 0;
								
								if ($oProducto->precios[1] != 0):
									$xPrecio = $oProducto->precios[1];
								else:
									$xPrecio = $oProducto->precios[0];
								endif;

								if ($xIdProductoDep > 0):
									$oProductoAsoc = $oProducto->getById($xIdProductoDep);
									if (strrpos($oProducto->datosInternos, trim($oProductoAsoc->codigoInterno)) !== false):

										$xProducto = new ProductoCarrito($oProducto->idProducto, trim($oProducto->codigoInterno), $xDescripcion, $xPrecio, $xCantidad, $imagen, $oProducto->stocks[0], $xIdProductoDep, $oProducto->etiquetasxProducto, $bonificado);
										$xCarrito->setAddProducto($xProducto);
									endif;
								else:
									$xProducto = new ProductoCarrito($oProducto->idProducto, trim($oProducto->codigoInterno), $xDescripcion, $xPrecio, $xCantidad, $imagen, $oProducto->stocks[0], $xIdProductoDep, $oProducto->etiquetasxProducto, $bonificado, $oProducto->peso, $oProducto->volumen);
									$xCarrito->setAddProducto($xProducto);
									$xParams = array('cantidadProductos' => $xCarrito->contCarrito());
									
								endif;
								return json_encode(array('estado' => true, 'parametros' => $xParams, 'mensaje'=> Sawubona::setIdiomaTexto("productoAgregado")));
							else:
								return json_encode(array('estado' => false, 'parametros'=> $xParams,  'mensaje'=> Sawubona::setIdiomaTexto("productoSinStock")));
							endif;
						else:
							return json_encode(array('estado' => false, 'parametros'=> $xParams,  'mensaje'=> Sawubona::setIdiomaTexto("productoSinStock")));
						endif;
					else:
						return json_encode(array('estado' => false, 'parametros'=> $xParams,  'mensaje'=> Sawubona::setIdiomaTexto("productoExedido")));
					endif;
				else:
					return json_encode(array('estado' => false, 'parametros'=> $xParams,  'mensaje'=> Sawubona::setIdiomaTexto("productoSinStock")));
				endif;
			else:
				return json_encode(array('estado' => false, 'parametros'=> $xParams, 'mensaje'=> Sawubona::setIdiomaTexto("productoSinStock")));
			endif;
		else:
			return json_encode(array('estado' => false, 'parametros'=> $xParams,  'mensaje'=>Sawubona::setIdiomaTexto("cantidadMayorCero")));
		endif;
		} catch (Exception $e) {
			Log::error($e);
		}
	}


	/**
	*	Funcion eliminar producto al carro
	**/
	public function deleteProducto() {
		try {
			$xCarrito = new cCarrito();
			$xCarrito->iniciarCarrito();
			$xCarrito->deleteProducto($_POST['idProducto']);
			$xParams = array('cantidadProductos' => $xCarrito->contCarrito());

			return json_encode(array('estado' => true, 'parametros' => $xParams));
		} catch (Exception $e) {
			return json_encode(array('estado' => false, 'Mensaje'=>"Ha ocurrido un error ".$e->getMessage() ));
		}
	}

	/**
	*
	**/
	public function printCarrito(){
		$oCarrito = session('carrito')->productos;
		//print_r($oCarrito);die;

		return View::make('index', compact('oCarrito'))->renderSections()['carrito'];
	}


	// Funcion para obtener los terminos del ecommerce
	public function getTerminos() {
		$xEcom = new Ecommerce();
		$xEcom->ecommerceConfiguracion();
		echo $xEcom->terminos;
		echo "TERMINOS: " . session('ecommerce')->terminos;
	}

	// Funcion para imprimir el carro
	public function printPaso() {
		try {
			$xEnvioGratis = false;
			$xPaso = 1;
			$xIdioma = config("main.IDIOMA_DEFAULT");
			$xGuardarPedido = isset($_POST['guardarPedido']) ? $_POST['guardarPedido'] : false;
			$oCategoria = new Categoria();
			$xCarrito = new cCarrito();
			$xCarrito->iniciarCarrito();
			$xSesIdioma = $xCarrito->getIdioma();
			$xecom = new Ecommerce();
			$xecom->ecommerceConfiguracion();
			$vTipos = $xecom->getTipoEnvio();

			$oSucursales = new Persona();

			if ($xSesIdioma != null and $xSesIdioma != $xIdioma) {
				//si el idioma del carrito no es el idioma del sitio consultamos para sacar el idioma corresp
				$xCarrito->actualizarIdioma($xIdioma);
			}

			// verificamos validez del descuento
			if (session('carrito')->descuento->codigo != "") {
				$xDescuento = new Descuento();
				$xDescuento->getDescuentobycodigo(session('carrito')->descuento->codigo);
				$xCarrito->setDescuento($xDescuento);
			}

			$xCarrito->calcularCostoEnvio();
			$xCarrito->calcularCostoTotal();
			$xDescuento = 0;
			$xAfavor = 0;

			// Descuentos
			if (session('carrito')->descuento->isNum) {
				$xDescuento = session('carrito')->descuento->valor;
			} else {
				$xDescuento = number_format(session('carrito')->subTotal * session('carrito')->descuento->valor / 100, 2);
			}

			// 
			if (session('carrito')->costoTotal == 0) {
				$xAfavor = $xDescuento - session('carrito')->subTotal - session('carrito')->envio->costo;
			}

			//
			if ($xecom->getEnvioGratis() && $xCarrito->getSubtotal() >= $xecom->getMontoEnvioGratis()) {
				$xEnvioGratis = true;
			}

			for ($i = 1; $i < 5; $i++) {
				if ($xCarrito->validarBandera($i)) {
					$xPaso = $i;
					break;
				}
			}
			

			// Paso 2
			if ($xPaso == 2) {
				$xIdSegmento = Sawubona::getParam('idSegmentoSucursal');
				$xOffSet = 0;
				$xLimit = 5;
				$xOrderBy = 'idPersona';
				$xOrderType = 'ASC';
				$oSucursales = $oSucursales->getBySegmento($xIdSegmento, $xOffSet, $xLimit, $xOrderBy, $xOrderType);
			}

			// Paso 3
			if ($xPaso == 3 or $xPaso == 4) {
				$xPaso = 3;
				$hasmp = false;

				// Guarda pedido o obtiene el Actual
				if ($xGuardarPedido) {
					$xIdPedido = $xCarrito->guardarPedido(Yii::app()->params['idGeneral']);
				} else {
					$xIdPedido = session('carrito')->idpedido;
				}

				// 
				if (is_numeric($xIdPedido)) {
					$xCarrito->setIdPedido($xIdPedido);
					for ($i = 0; $i < count(session('ecommerce')->gatewayPagos); $i++) {
						if (session('ecommerce')->gatewayPagos[$i]->idGatewayPago == 1) {
							$xPreference = $xCarrito->setMp(session('ecommerce')->gatewayPagos[$i]);
							$hasmp = true;
						}
					}
					if ($hasmp == false) {
						$xPreference['response']['init_point'] = "/Mp/compraError";
						$xPreference['response']['sandbox_init_point'] = "/Mp/compraError";
					}
				} else {
					$xPreference['response']['init_point'] = "/Mp/compraError";
					$xPreference['response']['sandbox_init_point'] = "/Mp/compraError";
				}
			} else {
				$xPreference['response']['init_point'] = "";
				$xPreference['response']['sandbox_init_point'] = "";
			}



			return json_encode(array('estado' => true, 'resultado' => View::make('/carrito/paso' . $xPaso, compact('xIdioma','xPreference', 'vTipos', 'xEnvioGratis', 'xDescuento', 'xAfavor', 'oSucursales'))->render(), 'cantidadCarro' => $this->contCarrito()) );
		}
		catch (Exception $e) {
			Log::error($e);
		}
	}

	public function contCarrito() {
		$xCarrito = new cCarrito();
		$xCarrito->iniciarCarrito();
		return json_encode(array('parametros' => array('cantidadProductos' => $xCarrito->contCarrito())));
	}

	

	// Funcion para cambiar la cantidad de un producto en el carro
	public function changeCantidad() {
		try {
			$xCarrito = new cCarrito();
			$xCarrito->iniciarCarrito();
			$xCarrito->changeCantidad($_POST['idProducto'], $_POST['cantidad']);
			
			return json_encode(array('estado' => true));
		} catch (Exception $e) {echo $e;}
	}

	// Funcion para eliminar todo el contenido del carro
	public function deleteAll() {
		try {
			$xCarrito = new cCarrito();
			$xCarrito->iniciarCarrito();
			$xCarrito->deleteAll();
			return json_encode(array('estado' => true));
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	// Avanza paso siguiente
	public function setPasoSiguiente() {
		try {
			$xPaso = $_POST['paso'];
			$xCarrito = new cCarrito();
			$xCarrito->iniciarCarrito();
			if ($xCarrito->validarBandera(4)) {
				$xCarrito->deleteIdPedido();
			}
			if ($xPaso == 'paso0') {
				$xCarrito->setBanderaActiva(1);
			} else if ($xPaso == 'paso1') {
				$xEcom = new Ecommerce();
				$xEcom->ecommerceConfiguracion();
				$xCarrito->setBanderaActiva(2);
			}
			return json_encode(array('estado' => true));
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function setpersona(Request $request) {
		//dd($request);
		$rules = [
            'nombre' => 'required|min:2',
            'apellido' => 'required|min:2',
            'dni' => 'required|numeric|min:7',
            'email' => 'required|email',
        ];
        /**
         * Mensaje a mostrar
         */
        //dd($rules);
        $message = [
            'nombre.required' => 'El campo nombre es obligatorio',
            'nombre.min' => 'El campo nombre debe tener al menos 2 caracteres',
            'apellido.required' => 'El campo Apellido es obligatorío',
            'apellido.min' => 'El campo apellido debe tener al menos 2 caracteres',
            'dni.required' => 'El campo D.N.I es obligatorio',
            'dni.min' => 'El campo dni debe tener al menos 6 caracteres',
            'dni.numeric' => 'El campo dni debe ser numérico',
            'email.required' => 'El campo email es obligatorio',
            'email.email' => 'El campo email debe ser valido',
        ];

        //$this->validate($request, $rules, $message);
		
		try {
			if ($request) {
				$oPersona = new Persona();
				// si ya teniamos un registro los datos en base, recuperamos el id
				if (session('carrito')->persona->idPersona) {
					$oPersona->idPersona = session('carrito')->persona->idPersona;
				}

				$oPersona->nombre = $request['nombre'];
				$oPersona->apellido = $request['apellido'];
				$oPersona->emails[] = $request['email'];
				$oPersona->telefonos[3] = new Telefono(null, null, $request['telefono3']);
				$oPersona->telefonos[2] = new Telefono(null, $request['pref3'], $request['movil']);
				$oPersona->direccion = new Direccion();
				$oPersona->direccion->calle = $request['direccion'];
				$oPersona->direccion->numero = $request['numero'];
				$oPersona->direccion->localidad = $request['localidad'];
				$oPersona->direccion->provincia = $request['provincia'];
				$oPersona->direccion->pais = $request['pais'];
				$oPersona->direccion->cp = $request['cp'];
				$oPersona->direccion->piso = $request['piso'];
				$oPersona->direccion->dpto = $request['dpto'];
				$oPersona->documento->numero = $request['dni'];
				$xCarrito = new cCarrito();
				$xCarrito->iniciarCarrito();
				$xCarrito->setPersona($oPersona);
				$xCarrito->setObservaciones($request['observaciones']);
				$xCarrito->setTerminos();
				$xCarrito->setBanderaActiva(3);

				return json_encode(array('estado' => true));
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
