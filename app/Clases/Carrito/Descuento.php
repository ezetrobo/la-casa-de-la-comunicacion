<?php

namespace App\Clases\Carrito;

use App\Clases\Carrito\Ecommerce;
use App\Clases\Producto\Producto;
use Clases\Carrito\Pedido;

class Descuento{
	public $codigo;
	public $valor;
	public $isNum;
	public $isUnic;

	function __construct($xCodigo=NULL, $xValor=NULL){
		$this->codigo=$xCodigo;
		$this->valor=$xValor;
		$this->isNum=false;
		$this->isUnic=false;
	}

	public function getDescuentobycodigo($xCodigo=null){
		try {
			$xCodigo=str_replace(" ","",$xCodigo);
			
			for ($i=0; $i < count(session('ecommerce')->gatewayPagos); $i++) {
				if (session('ecommerce')->gatewayPagos[$i]->idGatewayPago == 1) {
					$oCuentaMp = session('ecommerce')->gatewayPagos[$i];
					$xMp = new cMp($oCuentaMp->merchantId, $oCuentaMp->merchantKey);
				}
				else if(session('ecommerce')->gatewayPagos[$i]->idGatewayPago == 4){
					$MERCHANT = session('ecommerce')->gatewayPagos[$i]->merchantId;
					$AUTHORIZATION = session('ecommerce')->gatewayPagos[$i]->merchantKey;
					$SECURITY = explode(" ", $AUTHORIZATION)[1];
					$SPSsandbox = session('ecommerce')->gatewayPagos[$i]->sandboxMode;
				}
			}

			$flag=false;
			if ($xCodigo!=null){				
				$xEcom = new Ecommerce();
				$xEcom->ecommerceConfiguracion();
				$xIdCatalogoDescuento = session('ecommerce')->idCatalogoDescuento;
				
				$oProducto = new Producto();
				$vProducto = $oProducto->searchByCampo($xIdCatalogoDescuento,$xCodigo,'codigointerno',0,1);
				
				if ($vProducto != null && $xCodigo == str_replace(" ","",$vProducto[0]->codigoInterno)){
					//verificar validez
					if ($vProducto[0]->etiquetas != ""){
						date_default_timezone_set('America/Argentina/Buenos_Aires');
						$fecha = new DateTime();
						$fechas=explode(" ",$vProducto[0]->etiquetas);

						$fecha0=new DateTime($fechas[0]."T00:00:00+00:00");
						$fecha1=new DateTime($fechas[1]."T23:59:59+00:00");

						if ($fecha>$fecha0 and $fecha<$fecha1)
						{
							$flag = true;
						}
						else
						{
							return json_encode(array('estado' => false, 'mensaje' => 'Descuento Vencido'));
						}
					}
					else
					{
						$flag=true;
					}


					//consultar si hay pedido acreditado con este cupon
					//
					
					$oPedido = new Pedido();
					$vPedidos = $oPedido->getByCampoValor(0,0,'codigo_descuento',$xCodigo);
					
					if ($vPedidos!=null){
						foreach ($vPedidos as $oPedidoReg) {
							
							if(isset($xMp) && strpos($oPedidoReg->formaPago, "MercadoPago") !== false && $oPedidoReg->idEstadoPago!=1){
								$idPayment = split("MercadoPago: ", $oPedidoReg->formaPago)[1];
								$xMp->cancel_payment($idPayment);
							}

							if(isset($AUTHORIZATION) && strpos($oPedidoReg->formaPago, "Decidir") !== false && $oPedidoReg->idEstadoPago!=1){
								
								$idPayment=config('main.NOMBRE_SITIO')."-".$oPedidoReg->idPedido;
								$optionsGAA = array (     
							        'security'   => $SECURITY,  
							        'merchant'   => $MERCHANT,    
							        'nro_operacion' => $idPayment   
								);

								$http_header = array('Authorization'=>$AUTHORIZATION, 
									'user_agent' => 'PHPSoapClient'
								);

								$connector = $connector = ($SPSsandbox) ? new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_TEST) : new Decidir\Connector($http_header, Decidir\Connector::DECIDIR_ENDPOINT_PROD);

								$gaa_data = new \Decidir\Authorize\Execute\Anulacion($optionsGAA);
								$rta = $connector->Authorize()->Execute($gaa_data);								
							}
							
							if($oPedidoReg->idEstadoPago==1){
								$flag=false;								
							}
						}
						if (!$flag) {
							return json_encode(array('estado' => 'false', 'mensaje' => 'Descuento utilizado'));
						}
					}
					
					if ($flag==true){
						return json_encode(array('estado' => 'true', 'mensaje' => 'Descuento disponible'));

						$this->codigo=$vProducto[0]->codigoInterno;
						$this->valor=$vProducto[0]->precios[0];
						$this->isNum=$vProducto[0]->descuento;
						$this->isUnic = $vProducto[0]->destacado;
					}
					else {
						$this->codigo='';
						$this->valor='';
						$this->isNum=false;
						$this->isUnic = false;
					}
				}
				return json_encode(array('estado'=>'false', 'mensaje'=>'Descuento no habilitado'))	;
			}
		}
		catch (Exception $e){
		}
	}
}
?>