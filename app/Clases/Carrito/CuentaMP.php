<?php

namespace Clases\Carrito;

class CuentaMP{
	public $idMercado;
	public $nombre;
	public $client_id;
	public $client_secret;
	public $sandboxMode;
	public $indicePrecio;

	function __construct($xIdMercado=null, $xNombre=null, $xClient_id=null, $xClient_secret=null, $xSandboxMode=null, $xIndicePrecio = null){		
		$this->idMercado = $xIdMercado;
		$this->nombre = $xNombre;
		$this->client_id = $xClient_id;
		$this->client_secret = $xClient_secret;
		$this->sandboxMode = $xSandboxMode;
		$this->indicePrecio = $xIndicePrecio;
	}
}
?>