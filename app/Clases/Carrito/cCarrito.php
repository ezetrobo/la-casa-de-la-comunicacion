<?php
namespace App\Clases\Carrito;

use App\Clases\Carrito\Descuento;
use App\Clases\Carrito\Envio;
use App\Clases\Carrito\OpcionPrecio;
use App\Clases\Persona\Direccion;
use App\Clases\Persona\Documento;
use App\Clases\Persona\Persona;
use App\Clases\Persona\Telefono;
use Session;;

date_default_timezone_set('America/Argentina/Buenos_Aires');

class cCarrito {
	public $idioma;
	public $persona;
	public $productos;
	public $descuento;
	public $envio;
	public $costoTotal;
	public $subTotal;
	public $terminos;
	public $idpedido;
	public $entrega;
	public $observaciones;
	public $opcionprecio;
	public $IVA;

	public function __construct() {
		$this->idioma = null;
		$oPersona = new Persona();
		$oPersona->direccion = new Direccion(array());
		$oPersona->documento = new Documento(array());
		$this->persona = $oPersona;
		$this->productos = array();
		$this->descuento = new Descuento();
		$this->envio = new Envio();
		$this->costoTotal = null;
		$this->subTotal = null;
		$this->terminos = null;
		$this->idpedido = null;
		$this->entrega = null;
		$this->observaciones = null;
		$this->opcionprecio = new OpcionPrecio();
		$this->IVA = null;
	}

	// Iniciamos carrito
	public function iniciarCarrito() {
		if (!Session::has('carrito')):
			session()->put('carrito', new cCarrito());
		endif;
	}

	//	Funcion que borra la session
	public function deleteSession() {
		//session()->pull('carrito');
	}

	//	Seteamos idioma
	public function setIdioma($xIdioma = null) {
		try {
			if ($xIdioma != null) {
				if (Session::has('carrito')) {
					session('carrito')->idioma = $xIdioma;
				}
			}
		} catch (Exception $e) {}
	}

	public function getIdioma() {
		if (Session::has('carrito')) {
			if (isset(session('carrito')->idioma) && session('carrito')->idioma != null) {
				return session('carrito')->idioma;
			}
			else {
				return null;
			}
		}
	}

	//Seteamos observaciones
	public function setObservaciones($observaciones) {
		try {
			if(Session::has('carrito')){
				if($observaciones != null)
				{
					session('carrito')->observaciones = $observaciones;
				}
				else{
					session('carrito')->observaciones = null;
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function getObservaciones() {
		if (Session::has('carrito')) {
			return session('carrito')->observaciones;
		} 
		else {
			return null;
		}
	}

	//	Funcion para personas
	public function setPersona($xPersona = null) {
		try {
			if ($xPersona != null) {
				print '{"notice":"xPeronsa isn`t null"}';
				if ($this->validarBandera(1) || $this->validarBandera(2)) {
					print '{"notice":"bandera (1) valida"}';
					if (Session::has('carrito')) {
						print '{"notice":"seteando persona"}';
						session('carrito')->persona = $xPersona;
						$this->persona = $xPersona;
					}
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	// Seteamos direcciones por indice
	public function setDireccionByIndex($xIndex = null) {
		try
		{
			if ($xIndex === null) {
				return 'xIndex no puede ser NULL';
			}

			if (!isset(session('carrito')->persona->idPersona)) {
				return 'No se puede setear direccion sin primero setear persona';
			}

			if (!isset(session('direcciones')[$xIndex])) {
				return 'No se encontró la dirección con el indice solicitado';
			}

			print '{"notice":"ajuste direccion por index"}';
			session('carrito')->persona->direccion = session('direccion')[$xIndex];
		}
		catch (Exception $e) {

		}
	}

	// Seteamos direcciones por session
	public function setDireccionFromSession() {
		try
		{
			if (!isset(session('carrito')->persona->direccion->calle) || empty(session('carrito')->persona->direccion)) {
				throw new Exception('{"Exception":"Sin dirección almacenada en sesión"}');
			}

			$this->persona->direccion = session('carrito')->persona->direccion;
			print '{"notice":"Configuración de la dirección de la sesión"}';
		} 
		catch (Exception $e) {
			print $e->getMessage;
		}
	}

	public function getPersona() {
		if (Session::has('carrito')) {
			if (isset(session('carrito')->persona) and session('carrito')->persona != null) {
				return session('carrito')->persona;
			}
			else {
				return null;
			}
		}
	}

	//	Funciones para productos
	public function setAddProducto($xProductoCarrito = null) {
		try
		{
			if ($xProductoCarrito != null && $this->existeSesion()):
				foreach (session('carrito')->productos as $key => $xProducto) {
					if ($xProducto->idProducto == $xProductoCarrito->idProducto) {
						$this->deleteProducto($xProductoCarrito->idProducto);
						$xProductoCarrito->cantidad = $xProducto->cantidad + $xProductoCarrito->cantidad;
						break;
					}
				}
				array_push(session('carrito')->productos, $xProductoCarrito);
			endif;
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	// Funcion para actualizar el idioma
	public function actualizarIdioma($xIdioma) {
		try {
			if ($xIdioma != null) {
				$this->setIdioma($xIdioma);
				$this->priActualizarIdioma($xIdioma);
			}
		} catch (Exception $e) {

		}
	}

	// Funcion para eliminar un producto
	public function deleteProducto($xIdProducto = null) {
		try {
			if ($xIdProducto != null && $this->existeSesion()) {
				foreach (session('carrito')->productos as $index => $xProducto) {
					if ($xProducto->idProducto == $xIdProducto) {
						unset(session('carrito')->productos[$index]);
						break;
					}
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	// Funcion para eliminar todos los productos
	public function deleteAll() {
		try {
			if (!$this->validarBandera(1)) {
				if ($this->validarBandera(4)) {
					$this->deleteIdPedido();
				}
				$this->setBanderaActiva(1);
			}
			if ($this->existeSesion()) {
				session('carrito')->productos = array();
			}
		} catch (Exception $e) {

		}
	}

	// Funcion para modificar la cantidad
	public function changeCantidad($xIdProducto = null, $xCantidad = null) {
		try {
			if ($xIdProducto != null and $xCantidad != null) {
				if (!$this->validarBandera(1)) {
					if ($this->validarBandera(4)) {
						$this->deleteIdPedido();
					}
					$this->setBanderaActiva(1);
				}
				$this->productoChangeCantidad($xIdProducto, $xCantidad);
			}
		} catch (Exception $e) {

		}
	}

	// Funcion para eliminar pedido
	public function deleteIdPedido() {
		session('carrito')->idpedido = null;
	}

	public function getProductos() {
		return $this->priGetProductos();
	}

	//Cuenta la cantidad de producto
	public function contCarrito() {
		try {
			$total = 0;
			if (Session::has('carrito')) {
				if (isset(session('carrito')->productos)) {
					$vProductos = session('carrito')->productos;
					foreach ($vProductos as $xProducto) {
						$total += $xProducto->cantidad;
					}
				}
			}
			return $total;
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Funcion para descuento
	public function setDescuento($xDescuento = null) {
		try {
			if ($xDescuento != null) {
				if ($this->validarBandera(1)) {
					if (Session::has('carrito')) {
						session('carrito')->descuento = $xDescuento;
					}
				}
			}
		} catch (Exception $e) {}
	}

	public function getDescuento() {
		if (Session::has('carrito')) {
			if (isset(session('carrito')->descuento) and session('carrito')->descuento != null) {
				return session('carrito')->descuento;
			} else {
				return null;
			}
		}

	}

	public function getDescuentoValor() {
		if (Session::has('carrito')) {
			if (session('carrito')->descuento->valor != null) {
				if (session('carrito')->descuento->isNum) {
					return session('carrito')->descuento->valor;
				} else {
					return round((session('carrito')->subTotal * session('carrito')->descuento->valor / 100), 2);
				}
			}
		} else {
			return 0;
		}

	}

	//	Funcion para que carga combos en session y costo
	public function setEnvio($xEnvio = null) {
		if ($xEnvio) {
			try
			{
				if ($this->validarBandera(1)) {
					if (!empty(session('carrito'))) {
						if ($xEnvio->idCostoEnvio == session('carrito')->envio->idCostoEnvio) {
							if (empty($xEnvio->costo)) {
								for ($i = 0; $i < 3; $i++) {
									if (!empty($xEnvio->opciones[$i]) and !empty(session('carrito')->envio->opciones[$i]->listaCostoEnvioZona)) {
										// verificamos si el combo i es igual al de la session
										if ($xEnvio->opciones[$i]->listaCostoEnvioZona == session('carrito')->envio->opciones[$i]->listaCostoEnvioZona) {
											//print_r("combo ".$i." igual<br>");
											//verificamos si tenemos la misma zona seleccionada sino la asignamos y cambiamos el costo y salimos
											if ($xEnvio->opciones[$i]->idActivo != session('carrito')->envio->opciones[$i]->idActivo) {
												//print_r("activo cambio<br>");
												session('carrito')->envio->opciones[$i]->idActivo = $xEnvio->opciones[$i]->idActivo;
												session('carrito')->envio->costo = null;
												//$xOpcion=new OpcionEnvio();
												$xEcom = new Ecommerce();
												$xEcom->ecommerceConfiguracion();

												$xLista = $xEcom->GetZonaDepbyId($xEnvio->opciones[$i]->idActivo);
												$opcionenvio = new OpcionEnvio();

												if (!empty($xLista[1])) {
													for ($j = 1; $j < count($xLista); $j++) {
														$opcionenvio->listaCostoEnvioZona[] = $xLista[$j];
													}
												} else //si no hay listadep, va el costo del activo
												{
													session('carrito')->envio->costo = $xLista[0][2];
												}

												session('carrito')->envio->opciones[$i + 1] = $opcionenvio;
												// si se cambia el primer nivel, vaciamos el nivel 3

												if ($i == 0) {
													session('carrito')->envio->opciones[2] = null;
												}

												break;
											}
										}
										// si el combo es diferente es que vino vacio
										else {
											print_r("combo " . $i . " no igual");
											session('carrito')->envio->opciones[$i] = null;
										}
									} else {
										session('carrito')->envio->costo = null;
										//imprimimos en funcion de idActivo
										if (empty($xEnvio->opciones[$i])) {
											//if vacio tratamos llenar con el idActivo anterior
											if (!empty($xEnvio->opciones[$i - 1]->idActivo)) {
												$xEcom = new Ecommerce();
												$xEcom->ecommerceConfiguracion();
												$xLista = $xEcom->GetZonaDepbyId($xEnvio->opciones[$i - 1]->idActivo);
												if ($xLista[0][2]) {
													session('carrito')->envio->costo = $xLista[0][2];
												} else {
													$opcionenvio = new OpcionEnvio();
													if (!empty($xLista[1])) {
														for ($j = 1; $j < count($xLista); $j++) {
															$opcionenvio->listaCostoEnvioZona[] = $xLista[$j];
														}
													} else {
														$opcionenvio->listaCostoEnvioZona[] = $xLista[0];
													}
													session('carrito')->envio->opciones[$i] = $opcionenvio;
												}
												break;
												break;
											} else {
												session('carrito')->envio->opciones[$i] = null;
											}
										}
									}
								}
							} else //si viene costo > 0 ponemos en session directamente
							{
								print_r("costo>0");
								session('carrito')->envio = $xEnvio;
							}
						}
						//si el idCostoenvio es diferente al de la session busco en la base nuevo combo+
						//No lo usamos, si se cambia el idCostoEnvio, no entramos por aca.
						/*
						else if ($xEnvio->idCostoEnvio!=null){
							print_r("entro a llenar el combo0");
							//guardamos en session el tipo de envio
							print_r($xEnvio->idCostoEnvio);
							session('carrito')->envio->idCostoEnvio=$xEnvio->idCostoEnvio;
							session('carrito')->envio->idCostoEnvio=$xEnvio->nombre;
							$newEnvio=new Envio();
							$newEnvio->getOpcionesEnviobyId($xEnvio->idCostoEnvio);
							print_r($newEnvio);
							session('carrito')->envio=$newEnvio;
							//imprimi el combo (falta)
						}
						*/
					}
				}
				//print_r(session('carrito')->envio);
			} catch (Exception $e) {
				print_r($e);
			}
		}
	}

	public function setEnvioTipo($xEnvio = null) {
		try
		{
			if ($xEnvio && Session::has('carrito')) {
				session('carrito')->envio = $xEnvio;
			}
		} catch (Exception $e) {
			echo $e;
		}
	}

	public function getEnvio() {
		if (Session::has('carrito')) {
			if (isset(session('carrito')->envio) and session('carrito')->envio != null) {
				return session('carrito')->envio;
			} else {
				return null;
			}
		}

	}

	public function calcularCostoEnvio() {
		$pmax = 0;
		//print_r(session('carrito')->envio);
		if (session('carrito')->envio->costo !== null) {
			//print_r(session('carrito')->envio->costo);
			$xCosto = 0;
			$weightTotal = 0;
			$volumenTotal = 0;
			foreach (session('carrito')->productos as $xProductoCarrito) {
				$oProducto = new Producto();
				$xProducto = $oProducto->getById($xProductoCarrito->idProducto);
				if (empty($xProducto->peso)) {
					$xProducto->peso = 0;
				}

				if (empty($xProducto->volumen)) {
					$xProducto->volumen = 0;
				}

				$weightTotal += $xProducto->peso * $xProductoCarrito->cantidad;
				$volumenTotal += $xProducto->volumen * $xProductoCarrito->cantidad;
				//print_r($weightTotal);
			}

			//en funcion del ult id seleccionado en opciones, buscamos valor hasta y excedente
			for ($i = 0; $i < 3; $i++) {
				if (!empty(session('carrito')->envio->opciones[$i])) {
					if (session('carrito')->envio->opciones[$i]->idActivo != null) {
						$xidActivo = session('carrito')->envio->opciones[$i]->idActivo;
						$xHasta = session('carrito')->envio->opciones[$i]->getHastabyId($xidActivo);
						$xExcedente = session('carrito')->envio->opciones[$i]->getExcedentebyId($xidActivo);
						$xCosto = session('carrito')->envio->opciones[$i]->getCostobyId($xidActivo);
					}

				}
			}
			//print_r("<br>".$xidActivo);
			if (!empty($xidActivo)) {
				$vidActivos = explode(",", $xidActivo);
				if (count($vidActivos) > 1) {
					//caso multipeso
					$vHasta = explode(",", $xHasta);
					//print_r($vHasta);
					//print_r("<br>");
					$vCosto = explode(",", $xCosto);
					sort($vHasta);
					//print_r($vHasta);
					sort($vCosto);
					//buscar el peso superior minimo
					for ($i = 0; $i < count($vidActivos); $i++) {
						//print_r("pmax=".$pmax."<br>");
						if ($pmax <= $vHasta[$i]) {
							$pmax = $vHasta[$i];
						}

						if ($weightTotal <= $pmax) {
							break;
						}

					}
					//print_r("<br>".$i."<br>");
					//print_r($vCosto[$i]);
					if ($weightTotal < $pmax) {
						$xCosto = $vCosto[$i];
					}
					//si no se encontro, hemos guardado el peso max
					else {
						//calculamos cant paq con peso max
						$cantpaq = floor($weightTotal / $pmax);
						//print_r($cantpaq);
						$weightleft = $weightTotal - ($cantpaq * $pmax);
						//print_r($weightleft);
						$xCosto = $cantpaq * $vCosto[$i - 1];
						$pmax = 0;
						for ($i = 0; $i < count($vidActivos); $i++) {
							if ($pmax < $vHasta[$i]) {
								$pmax = $vHasta[$i];
							}

							if ($weightleft <= $pmax) {
								break;
							}

						}
						$xCosto += $vCosto[$i];
					}

				} else {
					if (!empty($xHasta)) {
						//caso excedente
						if ($xHasta > 0) {
							$cantCamion = floor($weightTotal / 5000);
							$weightleft = $weightTotal - ($cantCamion * 5000);
							$xCosto += $cantCamion * ((5000 * $xExcedente) + $xCosto);
							if ($weightleft > $xHasta) {
								$ExcedentKG = $weightleft - $xHasta;
								//print_r($ExcedentKG."-");
								$xCosto += ($ExcedentKG * $xExcedente);
							}
						}
					}
				}
			}
			if (session('ecommerce')->envioGratis && $this->getSubtotal() - $this->getDescuentoValor() >= session('ecommerce')->montoEnvioGratis) {
				$xCosto = 0;
			}
			session('carrito')->envio->costo = $xCosto;
		}
	}

	//	Funcion para banderas
	public function validarBandera($xPosicion = null) {
		if ($xPosicion != null) {
			return session('carrito')->banderas[$xPosicion];
		}
	}

	public function setBanderaActiva($xPosicion = null) {
		if ($xPosicion != null) {
			for ($i = 1; $i < 5; $i++) {
				session('carrito')->banderas[$i] = false;
			}

			session('carrito')->banderas[$xPosicion] = true;
		}
	}

	public function calcularCostoTotal() {
		try {
			$costoTotal = 0;
			if (Session::has('carrito')) {
				/*foreach(session('carrito')->productos as $xProductoCarrito) {
					$costoTotal+=$xProductoCarrito->precio*$xProductoCarrito->cantidad;
				}*/
				$xSubTotal = $this->getSubtotal();
				$this->setSubtotal($xSubTotal);
				$costoTotal = $xSubTotal;

				if ($costoTotal == 0) {
					$this->deleteAll();
				}
				//calcular descuento si hay
				// Atencion si es valor numerico sumamos el envio,sino aplicamos el descuento sin el envio.
				if (session('carrito')->descuento->valor != null) {
					if (session('carrito')->descuento->isNum) {
						if (session('carrito')->envio->costo != null) {
							$costoTotal += session('carrito')->envio->costo;
						}
						$costoTotal -= session('carrito')->descuento->valor;
						if ($costoTotal < 0) {
							$costoTotal = 0;
						}
					} else {
						$costoTotal = $costoTotal - number_format(
							$xSubTotal * session('carrito')->descuento->valor / 100,
							2
						);
						if (session('carrito')->envio->costo != null) {
							$costoTotal += session('carrito')->envio->costo;
						}
					}
				} else {
					if (session('carrito')->envio->costo != null) {
						$costoTotal += session('carrito')->envio->costo;
					}
				}
				//agregamos el iva
				//$costoTotal += number_format($costoTotal*0.09,2);

				session('carrito')->IVA = round($costoTotal * 0.21, 2, PHP_ROUND_HALF_UP);
				session('carrito')->costoTotal = round($costoTotal * 1.21, 2, PHP_ROUND_HALF_UP);
			}
		} catch (Exception $e) {
			$data = json_encode($e->getMessage());
			header('content-type:application/json');
			return print($data);
		}
	}

	// subtotal de los productos
	public function setSubtotal($xSubTotal) {
		try {
			if ($xSubTotal != null) {
				session('carrito')->subTotal = $xSubTotal;
			}
		} catch (Exception $e) {}
	}

	public function setIdPedido($xidpedido) {
		try {
			if ($xidpedido != null) {
				session('carrito')->idpedido = $xidpedido;
			}
		} catch (Exception $e) {}
	}

	public function getSubtotal() {
		try {
			$subtotal = 0;
			if (Session::has('carrito')) {
				foreach (session('carrito')->productos as $xProductoCarrito) {
					$subtotal += $xProductoCarrito->precio * $xProductoCarrito->cantidad;
				}
			}
			return $subtotal;
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function validarStock($xIdProducto = null, $xStock = null, $xCantidad = null) {
		try
		{
			if ($xIdProducto != null && $xCantidad != null && $xStock != null) {
				return $this->priValidarStock($xIdProducto, $xStock, $xCantidad);
			}

		} catch (Exception $e) {}
	}

	public function setTerminos() {
		session('carrito')->terminos = 1;
	}

	// function para guardar el pedido - si viene idpedido !=null, se actualiza
	public function guardarPedido($esPresupuesto = FALSE, $idEstado = 12, $idPedido = 0) {
		//registramos el pedido
		date_default_timezone_set('America/Argentina/Buenos_Aires');

		$fecha = new DateTime();

		$sitio =
			[
			"idsitio" => config('main.ID_SITIO'),
		];

		$segmento =
			[
			"idsegmento" => session('ecommerce')->segmento_compradores,
		];

		$tipoContenido =
			[
			"idTipoContenido" => config('main.ID_ECOMMERCE'),
		];

		$persona = new Persona();
		$persona = $esPresupuesto || $idEstado !== 12 ? session('ap') : session('carrito')->persona;

		$persona->sitio = $sitio;
		$persona->segmento = $segmento;
		$persona->tipo = 'persona';
		$persona->habilitado = 'S';

		$personaApi = $persona->convertPersonatoPersonaApi();

		//aca tenemos que ver si pedimos direccion de entrega y si por defecto es la misma que la de la persona
		$xentrega = $personaApi['direccion'] . " N°" . $personaApi['numero'] . "<br>";
		if ($personaApi['piso'] != '' || $personaApi['dpto'] != '') {
			$xentrega .= "Piso: " . $personaApi['piso'] . " Depto: " . $personaApi['dpto'] . "<br>";
		}
		$xentrega .= $personaApi['cp'] . " " . $personaApi['localidad'] . "<br>" .
			$personaApi['provincia'] . " - " . $personaApi['pais'];

		session('carrito')->entrega = $xentrega;

		//reconstruimos la lista de productos
		$lista = [];
		foreach (session('carrito')->productos as $key) {
			$presentacion = '';
			if ($key->etiquetasxProducto != null) {
				foreach ($key->etiquetasxProducto as $etiqueta) {
					//print_r($etiqueta->id);
					if ($etiqueta->id == 38) {
						$presentacion = $etiqueta->valores[0]->nombre;
					}
				}
			}

			$producto =
				[
				"idProducto" => $key->idProducto,
				"titulo" => $key->titulo . " " . $presentacion,
			];

			$lista[] =
				[
				"cantidad" => $key->cantidad,
				"producto" => $producto,
				"precio" => $key->precio,
				"subtotal" => $key->precio * $key->cantidad,
			];
		}

		if (session('carrito')->descuento->valor != null) {
			//aca podemos vovler a preguntar si es valido
			$codigoDesc = session('carrito')->descuento->codigo;
			$valorDesc = $this->getDescuentoValor();
		} else {
			$codigoDesc = "";
			$valorDesc = 0;
		}

		//reconstruimos las opciones de envio seleccionadas
		$destino = '';
		if (!empty(session('carrito')->envio->opciones)) {
			foreach (session('carrito')->envio->opciones as $opcion) {
				if (!empty($opcion)) {
					$destino = $destino . "-" . $opcion->getLocalidadActiva();
				}
			}
		} else {
			$destino = 'Envio bonificado';
		}

		//recuperamos nombre del tipo de envio
		$xEcom = new Ecommerce();
		$xEcom->ecommerceConfiguracion();
		$nbreTransporte = $xEcom->getNombreTransporte(session('carrito')->envio->idCostoEnvio);

		$estadoPago =
			[
			"idEstado" => $esPresupuesto ? 21 : $idEstado,
		];

		$pedido =
			[
			"idPedido" => $idPedido ?: session('carrito')->idpedido ?: 0,
			"sitio" => $sitio,
			"tipoContenido" => $tipoContenido,
			"persona" => $personaApi,
			"fecha" => $fecha->format("d/m/Y"),
			"estado" => '',
			"estadoPago" => $estadoPago,
			"total" => session('carrito')->costoTotal,
			"destino" => $destino,
			"entrega" => $xentrega,
			"transporteNombre" => $nbreTransporte,
			"transporteCosto" => session('carrito')->envio->costo,
			"descuento" => $valorDesc,
			"codigoDescuento" => $codigoDesc,
			"logPedido" => $fecha->format("d/m/Y") . ": Registro pedido, ",
			"listaProductosxPedido" => $lista,
			"observaciones" => session('carrito')->observaciones,
		];

		$data_string = json_encode($pedido);

		Sawubona::logPedido($data_string);

		$ch = curl_init(config('main.URL_API').'/Pedidos?xKey=265c092ff1813072ffeb07ec2ab84e4d');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);

		$result = curl_exec($ch);

		$result2 = json_decode($result);
		Log::warning($result2);

		session('carrito')->persona->idPersona = $result2->data->pedidos[0]->persona->idPersona;
		return $result2->data->pedidos[0]->idPedido;
	}

	//	Funcion para pago
	public function setMp($oCuentaGatewayPago) {
		$fecha = getdate();
		$mp = $mp = new cMp($oCuentaGatewayPago->merchantId, $oCuentaGatewayPago->merchantKey);

		$preference_data =
			[
			"items" =>
			[
				[
					"id" => "Code",
					"title" => "Su Pedido en " . config('main.NOMBRE_SITIO') . ": " . session('carrito')->idpedido,
					"currency_id" => "ARS",
					"picture_url" => "http://app.fidelitytools.net/inc/funciones/logos.asp?idSitio=" . config('main.ID_SITIO'),
					"description" => "Total de su carrito",
					"quantity" => 1,
					"unit_price" => session('carrito')->costoTotal,
				],
			],
			"payer" =>
			[
				"name" => session('carrito')->persona->nombre,
				"surname" => session('carrito')->persona->apellido,
				"email" => session('carrito')->persona->emails[0],
				"date_created" => $fecha,
				"phone" =>
				[
					"area_code" => session('carrito')->persona->telefonos[2]->caracteristica,
					"number" => session('carrito')->persona->telefonos[2]->numero,
				],
				"identification" =>
				[
					"type" => "DNI",
					"number" => session('carrito')->persona->documento->numero,
				],
				"address" =>
				[
					"street_name" => session('carrito')->persona->direccion->calle,
					"street_number" => session('carrito')->persona->direccion->numero,
					"zip_code" => session('carrito')->persona->direccion->cp,
				],
			],
			"back_urls" =>
			[
				"success" => $_SERVER['HTTP_HOST'] . "/Mp/compraok", //http://www.cibart.com.ar
				"failure" => $_SERVER['HTTP_HOST'] . "/Mp/compraerror",
				"pending" => $_SERVER['HTTP_HOST'] . "/Mp/comprapendiente",
			],
			//"auto_return" => "approved",
			//"payment_methods" => array(
			//"excluded_payment_methods" => array(
			//	array(
			//		"id" => "amex",
			//	)
			//),
			//"excluded_payment_types" => array(
			//	array(
			//		"id" => "ticket"
			//	)
			//),
			//"installments" => 24,
			//"default_payment_method_id" => null,
			//"default_installments" => null,
			//),
			"shipments" =>
			[
				"receiver_address" =>
				[
					"zip_code" => session('carrito')->persona->direccion->cp,
					"street_number" => session('carrito')->persona->direccion->numero,
					"street_name" => session('carrito')->persona->direccion->calle,
					//"floor"=> 4,
					//"apartment"=> "C"
				],
			],
			"notification_url" => $_SERVER['HTTP_HOST'] . "/Mp/notificacion", //"http://192.168.0.200"
			"external_reference" => session('carrito')->idpedido,
			"expires" => FALSE,
		];
		/*
			if (session('carrito')->envio->costo>0) {
				$preference_data["shipments"]["cost"] = floatval(number_format(session('carrito')->envio->costo,2));
			}
		*/
		$preference_data = json_encode($preference_data);

		$urlWebApi = config('main.URL_API'). "/MP/Agregar/" . $oCuentaGatewayPago->merchantId . "/" . $oCuentaGatewayPago->merchantKey;
		$ch = curl_init($urlWebApi);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $preference_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($preference_data))
		);

		$result = curl_exec($ch);

		//Sawubona::diePretty($result);

		return json_decode($result, TRUE);
	}

	//	Metodos privados -----------------------------------------------------------------------------------------------------------------------

	//funcion creada para Millex que requería que el carro contenga un cierto monto de productos sugeridos para completar la compra
	private function priSetSubTotalSugeridos() {
		$carrito = session('carrito');
		$productosCarrito = $carrito->productos;

		$carrito->subTotalSugeridos = 0;

		foreach ($productosCarrito as $Producto) {
			if ($Producto->estadoProducto == 1) {
				$carrito->subTotalSugeridos += $Producto->precio * $Producto->cantidad;
			}
		}
		return $carrito->subTotalSugeridos;
	}

	private function priActualizarIdioma($xIdioma = null) {
		try {
			if ($xIdioma != null && $this->existeSesion()) {
				$vProductos = $this->priGetProductos();
				foreach ($vProductos as $xProducto) {
					$oProducto = new Producto();
					$oProducto = $oProducto->getById($xProducto->idProducto);
					if ($xIdioma == 'en') //ahi tendriamos que recorrer listaproducto dep y verificar el idioma
					{
						$xDescripcion = $oProducto->productosDep[0]->titulo;
					} else {
						$xDescripcion = $oProducto->titulo;
					}

					$xProducto->titulo = $xDescripcion;
				}
				$this->priSetProductos($vProductos);
				$this->priSetSubTotalSugeridos();
			}
		} catch (Exception $e) {}
	}

	

	private function priDeleteRegalo($xProductoCarrito = null) {
		try {
			$xTieneProdAsoc = false;
			if ($xProductoCarrito != null && $this->existeSesion()) {
				$vProductos = $this->priGetProductos();

				if ($vProductos != null) {

					foreach ($vProductos as $index => $xProducto) {

						if (strrpos($xProducto->idProductoDep, trim($xProductoCarrito->idProducto)) !== false) {
							$xIdProductos = str_replace($xProductoCarrito->idProducto, '', $xProducto->idProductoDep);
							$vIdProductos = split(',', $xIdProductos);
							foreach ($vIdProductos as $xIdProducto) {
								if ($xIdProducto != '') {
									$xTieneProdAsoc = true;
									break;
								}
							}
							if ($xTieneProdAsoc) {
								$xProducto->idProductoDep = $xIdProductos;
							} else {
								unset($vProductos[$index]);
							}
						}
					}
					session('carrito')->productos = $vProductos;
					$this->priSetSubTotalSugeridos();
				}
			}
		} catch (Exception $e) {}
	}

	private function productoChangeCantidad($xIdProducto = null, $xCantidad = null) {
		try {
			if ($xIdProducto != null && $xCantidad != null && $this->existeSesion()) {
				if (session('carrito')->productos != null) {
					foreach (session('carrito')->productos as $key => $xProducto) {
						if ($xProducto->idProducto == $xIdProducto) {
								session('carrito')->productos[$key]->cantidad = $xCantidad;
							break;
						}
					}
				}
			}
		} catch (Exception $e) {
			Log::eror($e);
		}
	}

	private function priGetProductos() {
		if(!Session::has('carrito')) {
			return null;
		}

		return session('carrito')->productos;
	}

	

	private function existeSesion() {
		if (Session::has('carrito')) {
			return true;
		} else {
			return false;
		}

	}

	private function priUspsCalculate($weight, $container) {
		try {
			$userName = '325CREAT4758';
			$orig_zip = '91107'; //
			$dest_zip = '91107';
			$url = "http://Production.ShippingAPIs.com/ShippingAPI.dll";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			$data = "API=RateV4&XML=<RateV4Request USERID=\"$userName\"><Package ID=\"1ST\"><Service>PRIORITY</Service>";
			$data .= "<ZipOrigination>$orig_zip</ZipOrigination><ZipDestination>$dest_zip</ZipDestination>";
			$data .= "<Pounds>0</Pounds><Ounces>$weight</Ounces><Container>$container</Container><Size>REGULAR</Size></Package></RateV4Request>";
			//echo $data."<br>";
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$result = curl_exec($ch);
			$data = strstr($result, '<?');

			//echo $data;

			$xml_parser = xml_parser_create();
			xml_parse_into_struct($xml_parser, $data, $vals, $index);
			xml_parser_free($xml_parser);
			$params = array();
			$level = array();
			foreach ($vals as $xml_elem) {
				if ($xml_elem['type'] == 'open') {
					$level[$xml_elem['level']] = $xml_elem['tag'];
					if ($xml_elem['tag'] == 'ERROR') {
						//echo "break</br>";
						break;
					}
				}
				if ($xml_elem['type'] == 'complete') {
					$start_level = 1;
					$php_stmt = '$params';
					while ($start_level < $xml_elem['level']) {
						$php_stmt .= '[$level[' . $start_level . ']]';
						$start_level++;
					}
					$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
					//print_r ($php_stmt);
					eval($php_stmt);
				}
			}
			curl_close($ch);
			if (isset($params['RATEV4RESPONSE']['PACKAGE']['POSTAGE']['RATE'])) {
				$xRate = $params['RATEV4RESPONSE']['PACKAGE']['POSTAGE']['RATE'];
				return $xRate;
			} else {
				return 0;
			}

		} catch (Exception $e) {
			echo $e;
		}
	}

	private function priValidarStock($xIdProducto, $xStock, $xCantidad) {
		$flag = false;
		try {
			if ($xIdProducto != null && $xCantidad != null && $xStock != null && $this->existeSesion()) {
				$vProductos = $this->priGetProductos();
				if ($vProductos != null) {
					foreach ($vProductos as $xProducto) {
						if ($xProducto->idProducto == $xIdProducto) {
							$newCantidad = $xProducto->cantidad + $xCantidad;
							$flag = true;
							break;
						}
					}
				}
				if ($flag == false) {
					$newCantidad = $xCantidad;
				}
				if ($newCantidad <= $xStock) {
					return true;
				} else {
					return false;
				}
			}
		} catch (Exception $e) {}
		Log::error($e);
	}

	public function ConvertProvinciaCodigo($xString) {
		$xString = Caracteres::convertToUrl($xString);
		switch ($xString) {
		case 'caba':
			return 'C'; //
			break;
		case 'buenosaires': //
			return 'B';
			break;
		case 'catamarca': //
			return 'K';
			break;
		case 'chaco': //
			return 'H';
			break;
		case 'chubut': //
			return 'U';
			break;
		case 'cordoba': //
			return 'X';
			break;
		case 'corrientes': //
			return 'W';
			break;
		case 'entrerios': //
			return 'E';
			break;
		case 'corrientes':
			return 'W';
			break;
		case 'formosa':
			return 'P';
			break;
		case 'jujuy':
			return 'Y';
			break;
		case 'lapampa':
			return 'L';
			break;
		case 'larioja':
			return 'F';
			break;
		case 'mendoza':
			return 'M';
			break;
		case 'misiones':
			return 'N';
			break;
		case 'neuquen':
			return 'Q';
			break;
		case 'rionegro':
			return 'R';
			break;
		case 'salta':
			return 'A';
			break;
		case 'sanjuan':
			return 'J';
			break;
		case 'sanluis':
			return 'D';
			break;
		case 'santacruz':
			return 'Z';
			break;
		case 'santafe':
			return 'S';
			break;
		case 'santiagodelestero':
			return 'G';
			break;
		case 'tierradelfuego':
			return 'V';
			break;
		case 'tucuman':
			return 'T';
			break;
		}
	}
}
?>