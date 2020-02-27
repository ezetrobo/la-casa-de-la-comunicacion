<?php

namespace Clases\Carrito;

class CuentaGateway{
	public $idCuentaGatewayPago;
	public $idGatewayPago;
	public $nombre;
	public $icono;
	public $merchantId;
	public $merchantKey;
	public $signature;
	public $sandboxMode;
	public $indicePrecio;
	public $recargo;
	public $orden;
	public $leyenda;
	public $mediosPago;

	function __construct($xIdCuentaGatewayPago=null, $xIdGatewayPago=null, $xNombre=null, $xIcono=null, $xMerchantId=null, $xMerchantKey=null, $signature=null, $xSandboxMode=null, $xIndicePrecio=null, $xRecargo=null, $xOrden=null, $xLeyenda=null, $xMediosPago=null){		
		
		$this->idCuentaGatewayPago = $xIdCuentaGatewayPago;
		$this->idGatewayPago = $xIdGatewayPago;
		$this->nombre = $xNombre;
		$this->icono = $xIcono;
		$this->merchantId = $xMerchantId;
		$this->merchantKey = $xMerchantKey;
		$this->signature = $signature;
		$this->sandboxMode = $xSandboxMode;
		$this->indicePrecio = $xIndicePrecio;
		$this->recargo = $xRecargo;
		$this->orden = $xOrden;
		$this->leyenda = $xLeyenda;
		$this->mediosPago = $xMediosPago;
	}
}
?>