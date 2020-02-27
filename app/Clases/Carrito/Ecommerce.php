<?php

namespace App\Clases\Carrito;

use App\Clases\Carrito\Ecommerce;
use Clases\Carrito\CuentaGateway;
use Clases\Carrito\eCommerceTipoEnvio;
use App\Sawubona\Sawubona;

use session;

class Ecommerce {
	public $idECommerce;
	public $segmento_compradores;
	public $Privacidad;
	public $idMoneda;
	public $usps;
	public $RetiroLocal;
	public $valorRetiroLocal;
	public $Reembolso;
	public $ReembolsoValor;
	public $idCatalogoDescuento;
	public $eCommerceTipoEnvio;
	public $emailAlerta;
	public $terminos;
	public $montoMinimo;
	public $cuentaMP;
	public $gatewayPagos;
	public $envioGratis;
	public $montoEnvioGratis;

	public function __construct() {
		$this->idECommerce = 0;
		$this->segmento_compradores = NULL;
		$this->Privacidad = '';
		$this->idMoneda = 1;
		$this->usps = 0; //atencion aca lo tenemos que sacar de la api
		$this->RetiroLocal = 0;
		$this->valorRetiroLocal = 0;
		$this->Reembolso = 0;
		$this->ReembolsoValor = 0;
		$this->idCatalogoDescuento = 0;
		$this->eCommerceTipoEnvio = array();
		$this->emailAlerta = '';
		$this->terminos = '';
		$this->montoMinimo = 0;
		$this->cuentaMP = null;
		$this->gatewayPagos = array();
		$this->envioGratis = false;
		$this->montoEnvioGratis = 0;
	}

	public function ecommerceConfiguracion() {
		if (!Session::has('ecommerce')) {
			session()->put('ecommerce',new Ecommerce());
			$this->SetEcomConfig(session('ID_ECOMMERCE_ENCODE'));
		}
	}

	public function getEnvioGratis() {
		return session('ecommerce')->envioGratis;
	}

	public function getMontoEnvioGratis() {
		return session('ecommerce')->montoEnvioGratis;
	}

	public function deleteSession() {
		session()->forget('ecommerce');
		session()->flush();
	}

	public function SetEcomTipoEnvio($xEcomTipo) {
		$xEcom = new Ecommerce();
		$xEcom->eCommerceTipoEnvio = $xEcomTipo;
	}

	// funcion que retorna un array de array (idcostoenvio, zona, costo)
	// el primer elemento de la lista es relativo al id pasado como parametro, los siguientes se llena segun sus zonas dep
	public function GetZonaDepbyId($xidCostoEnvioZona) {
		$xLista = array();
		$flag = false;
		
		if ($xidCostoEnvioZona != '' and $xidCostoEnvioZona != null) {

			//primero verificamos que no es es retiro en local, contra reembolso, usps(id=1,2,3)
			if ($xidCostoEnvioZona < 4) {
				switch ($xidCostoEnvioZona) {
					case 1: 
						//retiro
						$xLista[] = array(1, "Retiro en el local", $this->valorRetiroLocal);
						break;
					case 2: 
						//reembolso
						$xLista[] = array(2, "Reembolso", $this->ReembolsoValor);
						break;
					case 3: 
						//usps
						$xLista[] = array(3, "USPS", null);
						break;
				}
			} 
			else if(!empty(session('ecommerce')->eCommerceTipoEnvio)) {
				foreach (session('ecommerce')->eCommerceTipoEnvio as $xeCommerceTipoEnvio) {
					if ($xeCommerceTipoEnvio->idCostoEnvio == $xidCostoEnvioZona) {
						// tenemos que mostrar el nivel 0
						$flag = true;
						if (!empty($xeCommerceTipoEnvio->listaCostoEnvioZona)) {
							//agregamos primero el elemento relativo al tipo de envio
							$xLista[] = array($xeCommerceTipoEnvio->idCostoEnvio, $xeCommerceTipoEnvio->nombre, null);
							foreach ($xeCommerceTipoEnvio->listaCostoEnvioZona as $xlistaCostoEnvioZona) {
								$xLista[] = array($xlistaCostoEnvioZona->idCostoEnvioZona, $xlistaCostoEnvioZona->nombre, $xlistaCostoEnvioZona->costo, $xlistaCostoEnvioZona->hasta, $xlistaCostoEnvioZona->excedente);
							}
						}
						else{
							//agregamos el tipo de envio solo, con costo zero ya que no tiene ninguna opcion cargada
							$xLista[] = array($xeCommerceTipoEnvio->idCostoEnvio, $xeCommerceTipoEnvio->nombre, 0);
						}
						break; //corta el ultimo foreach
					}
					else{
						//nivel1 ?
						if (!empty($xeCommerceTipoEnvio->listaCostoEnvioZona)) {
							foreach ($xeCommerceTipoEnvio->listaCostoEnvioZona as $xlistaCostoEnvioZona) {
								
								if ($xlistaCostoEnvioZona->idCostoEnvioZona == $xidCostoEnvioZona) {
									$xLista[] = array($xidCostoEnvioZona, $xlistaCostoEnvioZona->nombre, 
										$xlistaCostoEnvioZona->costo, $xlistaCostoEnvioZona->hasta, 
										$xlistaCostoEnvioZona->excedente);
									$flag = true;
									
									if (!empty($xlistaCostoEnvioZona->listaCostoEnvioZonaDep)) {
										foreach ($xlistaCostoEnvioZona->listaCostoEnvioZonaDep as $xCostoEnvioZona){
											//estamos al nivel 0 llenamos la lista
											$xLista[] = array($xCostoEnvioZona->idCostoEnvioZona, $xCostoEnvioZona->nombre, $xCostoEnvioZona->costo, $xCostoEnvioZona->hasta, $xCostoEnvioZona->excedente);
										}
									}
									break;
								} 
								else{
								// nivel 2?
									if (!empty($xlistaCostoEnvioZona->listaCostoEnvioZonaDep)) {
										foreach ($xlistaCostoEnvioZona->listaCostoEnvioZonaDep as $xCostoEnvioZona) {
											
											if ($xCostoEnvioZona->idCostoEnvioZona == $xidCostoEnvioZona){
												$xLista[] = array($xidCostoEnvioZona, $xCostoEnvioZona->nombre, $xCostoEnvioZona->costo, $xCostoEnvioZona->hasta, $xCostoEnvioZona->excedente);
												$flag = true;
												
												if (!empty($xCostoEnvioZona->listaCostoEnvioZonaDep)) {
													foreach ($xCostoEnvioZona->listaCostoEnvioZonaDep as 
														$xCostoEnvioDep) {
														$xLista[] = array($xCostoEnvioDep->idCostoEnvioZona, 
															$xCostoEnvioDep->nombre, $xCostoEnvioDep->costo, 
															$xCostoEnvioDep->hasta, $xCostoEnvioDep->excedente);
													}
												}
												break;
											} 
											else {
												//nivel 3 ? paramos
												if (!empty($xCostoEnvioZona->listaCostoEnvioZonaDep)) {
													foreach ($xCostoEnvioZona->listaCostoEnvioZonaDep as 
														$xCostoEnvioDep) {
														
														if ($xCostoEnvioDep->idCostoEnvioZona == $xidCostoEnvioZona) {
															$flag = true;
															$xLista[] = array($xCostoEnvioDep->idCostoEnvioZona, $xCostoEnvioDep->nombre, $xCostoEnvioDep->costo, $xCostoEnvioDep->hasta, $xCostoEnvioDep->excedente);
															break;
														}
													}
												}
											}
											if ($flag == true) {
												break;
											}
										}
									}
								}

								if ($flag == true) {
									break;
								}
							}
						}
					}

					if ($flag == true) {
						break;
					}
				}
			} 
			else{
				$xLista[] = array(0, "Sin costo de envio", 0);
			}
		}
		return $xLista;
	}

	//busca con la api los parametros de configuracion de ecommerce y lo guarda en session
	public function SetEcomConfig($xEncodeIdTipoContenido) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,
			config('main.URL_REST').'/ECommerce/ObtenerConfiguracion/'.$xEncodeIdTipoContenido);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$eConfig = curl_exec($ch);
		curl_close($ch);
		$xData = json_decode($eConfig, true);

		$this->habilitado = true;
		$this->idECommerce = $xData['idEcommerce'];
		$this->segmento_compradores = $xData['idSegmentoDestino'];
		$this->Privacidad = $xData['privacidad'];
		$this->idMoneda = $xData['tiposPrecios'][0]['moneda']['idMoneda'];
		$this->usps = $xData['usps'];
		$this->RetiroLocal = $xData['retiroLocal'];
		$this->valorRetiroLocal = $xData['valorRetiroLocal'];
		$this->Reembolso = $xData['reembolso'];
		$this->ReembolsoValor = $xData['reembolsoValor'];
		$this->idCatalogoDescuento = $xData['catalogoDescuento'];
		
		if ($xData['emailAlerta'] != null) {
			for ($i = 0; $i < count($xData['emailAlerta']); $i++) {
				if ($i == 0) {
					$this->emailAlerta = $xData['emailAlerta'][$i];
				} 
				else{
					$this->emailAlerta = $this->emailAlerta . "," . $xData['emailAlerta'][$i];
				}
			}
		}

		$this->terminos = $xData['terminos'];
		$this->montoMinimo = $xData['montoMinimo'];
		$this->envioGratis = $xData['envioGratis'];
		$this->montoEnvioGratis = $xData['montoEnvioGratis'];

		if ($xData['cuentasGatewayPago'] != NULL) {
			$xData['cuentasGatewayPago'] = Sawubona::array_sort($xData['cuentasGatewayPago'], 'orden', SORT_ASC);
			
			foreach ($xData['cuentasGatewayPago'] as $dataG) {
				if($xData['cuentasGatewayPago'] == NULL){
					continue;
				}

				$xCuentaMP = new CuentaGateway();
				
				if ($dataG['mediosPago'] != NULL) {
					foreach ($dataG['mediosPago'] as $xMedioPago) {
						$xMedio = new MedioPago(
							$xMedioPago['idMedioPago'],
							$xMedioPago['nombre'],
							$xMedioPago['icono'],
							$xMedioPago['medioPagoTipo']['idMedioPagoTipo'],
							$xMedioPago['medioPagoTipo']['nombre']
						);
						$xCuentaMP->mediosPago[] = $xMedio;
					}
				}

				$xCuentaMP->idCuentaGatewayPago = $dataG['idCuentaGatewayPago'];
				$xCuentaMP->idGatewayPago = $dataG['gatewayPago']['idGatewayPago'];
				$xCuentaMP->nombre = $dataG['gatewayPago']['nombre'];
				$xCuentaMP->icono = $dataG['gatewayPago']['icono'];
				$xCuentaMP->merchantId = $dataG['merchantId'];
				$xCuentaMP->merchantKey = $dataG['merchantKey'];
				$xCuentaMP->signature = $dataG['signature'];
				$xCuentaMP->sandboxMode = $dataG['sandboxMode'];
				$xCuentaMP->indicePrecio = $dataG['indicePrecio'];
				$xCuentaMP->recargo = $dataG['recargo'];
				$xCuentaMP->orden = $dataG['orden'];
				$xCuentaMP->leyenda = $dataG['leyenda'];

				$this->gatewayPagos[] = $xCuentaMP;
			}
		}

		if ($xData['tiposEnvios'] != NULL) {
			foreach ($xData['tiposEnvios'] as $xDataTipoEnvio) {
				$xTipoEnvio = new eCommerceTipoEnvio();
				$xTipoEnvio->idEcommerceTipo = $xDataTipoEnvio['idTipoEnvio'];
				$xTipoEnvio->idCostoEnvio = $xDataTipoEnvio['costoEnvio']['idCostoEnvio'];
				$xTipoEnvio->nombre = $xDataTipoEnvio['costoEnvio']['nombre'];
				if ($xDataTipoEnvio['costoEnvio']['costosEnviosZonas'] != NULL) {
					$xDataTipoEnvio['costoEnvio']['costosEnviosZonas'] = Sawubona::array_sort($xDataTipoEnvio['costoEnvio']['costosEnviosZonas'], 'zona', SORT_ASC);
					$tmp = "";
					$j = -1;
					
					foreach ($xDataTipoEnvio['costoEnvio']['costosEnviosZonas'] as $xDataCostoEnvio) {
						if ($tmp == $xDataCostoEnvio['zona']) {

							$xTipoEnvio->listaCostoEnvioZona[$j]->idCostoEnvioZona .= ',' . $xDataCostoEnvio['idCostoEnvioZona'];
							$xTipoEnvio->listaCostoEnvioZona[$j]->costo .= ',' . $xDataCostoEnvio['valor'];
							$xTipoEnvio->listaCostoEnvioZona[$j]->hasta .= ',' . $xDataCostoEnvio['hastaKG'];
						}
						else{
							$j++;
							$tmp = $xDataCostoEnvio['zona'];
							
							$xCostoEnvio = new CostoEnvioZona(
								$xDataCostoEnvio['idCostoEnvioZona'],
								$xDataCostoEnvio['zona'],
								$xDataCostoEnvio['valor'],
								$xDataCostoEnvio['hastaKG'],
								$xDataCostoEnvio['excedente'],
								NULL
							);

							if ($xDataCostoEnvio['costosEnviosZonasDep'] != NULL) {
								$tmp1 = "";
								$k = -1;
								
								foreach ($xDataCostoEnvio['costosEnviosZonasDep'] as $xDataCostoEnvioDep) {
									if ($tmp1 == $xDataCostoEnvioDep['zona']) {
										$xCostoEnvio->listaCostoEnvioZonaDep[$k]->idCostoEnvioZona .= ',' . $xDataCostoEnvioDep['idCostoEnvioZona'];
										$xCostoEnvio->listaCostoEnvioZonaDep[$k]->costo .= ',' . $xDataCostoEnvioDep['valor'];
										$xCostoEnvio->listaCostoEnvioZonaDep[$k]->hasta .= ',' . $xDataCostoEnvioDep['hastaKG'];
									}
									else {
										$k++;
										$tmp1 = $xDataCostoEnvioDep['zona'];
										$xCostoEnvioDep = new CostoEnvioZona(
											$xDataCostoEnvioDep['idCostoEnvioZona'],
											$xDataCostoEnvioDep['zona'],
											$xDataCostoEnvioDep['valor'],
											$xDataCostoEnvioDep['hastaKG'],
											$xDataCostoEnvioDep['excedente'],
											NULL
										);

										if($xDataCostoEnvioDep['costosEnviosZonasDep'] != NULL) {
											$tmp2 = "";
											$l = -1;
											foreach ($xDataCostoEnvioDep['costosEnviosZonasDep'] as $xDataCostoEnvioDepDep) {
												if ($tmp2 == $xDataCostoEnvioDepDep['zona']) {
													$xCostoEnvioDep->listaCostoEnvioZonaDep[$k]->idCostoEnvioZona .= ',' . $xDataCostoEnvioDepDep['idCostoEnvioZona'];
													$xCostoEnvioDep->listaCostoEnvioZonaDep[$k]->costo .= ',' . $xDataCostoEnvioDepDep['valor'];
													$xCostoEnvioDep->listaCostoEnvioZonaDep[$k]->hasta .= ',' . $xDataCostoEnvioDepDep['hastaKG'];
												}
												else {
													$l++;
													$tmp2 = $xDataCostoEnvioDepDep['zona'];
													$xCostoEnvioDepDep = new CostoEnvioZona(
														$xDataCostoEnvioDepDep['idCostoEnvioZona'],
														$xDataCostoEnvioDepDep['zona'],
														$xDataCostoEnvioDepDep['valor'],
														$xDataCostoEnvioDepDep['hastaKG'],
														$xDataCostoEnvioDepDep['excedente'],
														NULL
													);

													$xCostoEnvioDep->listaCostoEnvioZonaDep[] = $xCostoEnvioDepDep;
												}
											}
										}
										$xCostoEnvio->listaCostoEnvioZonaDep[] = $xCostoEnvioDep;
									}
								}
							}
							$xTipoEnvio->listaCostoEnvioZona[] = $xCostoEnvio;
						}
					}
				}
				$this->eCommerceTipoEnvio[] = $xTipoEnvio;
			}
		}
		session()->put('ecommerce',$this);
	}

	public function getSegmentoCompradores() {
		return session('ecommerce')->segmento_compradores;
	}

	// Retorna lista (id name)  para checkbox en paso 1 del tipo de envio
	public function getTipoEnvio() {
		$vTipos = [];
		if (session('ecommerce')->RetiroLocal == 1) {
			$vTipos[] = array( 1, 'Retiro en local', session('ecommerce')->valorRetiroLocal,);
		}

		if (session('ecommerce')->Reembolso == 1) {
			$vTipos[] = array(2, 'Contra Reembolso', session('ecommerce')->ReembolsoValor,);
		}

		if (session('ecommerce')->usps == 1) {
			$vTipos[] = array( 3, 'Usps', NULL,);
		}

		foreach (session('ecommerce')->eCommerceTipoEnvio as $xTipo) {
			if (empty($xTipo->listaCostoEnvioZona)) {
				$vTipos[] =[$xTipo->idCostoEnvio, $xTipo->nombre, 0,];
			}
			else {
				$vTipos[] =[$xTipo->idCostoEnvio, $xTipo->nombre, NULL,];
			}
		}

		return $vTipos;
	}

	public function getNombreTransporte($id) {
		$nombre = '';
		$TiposEnvio = $this->getTipoEnvio();
		foreach ($TiposEnvio as $tipo) {
			if ($tipo[0] == $id) {
				$nombre = $tipo[1];
				break;
			}
		}
		return $nombre;
	}

	// creada solo para realizar prueba
	public function getLocal0($i) {
		$xlista = array();
		$locales = new eCommerceTipoEnvio();
		$locales = session('ecommerce')->eCommerceTipoEnvio[$i];
		
		foreach ($locales->listaCostoEnvioZona as $local) {
			$xLista[] = $local->listaCostoEnvioZonaDep;
		}
		
		return $xLista;
	}
}
?>