<?php

namespace Clases\Carrito;

class MedioPago{
	public $idMedioPago;
	public $nombre;
	public $icono;
	public $idMedioPagoTipo;
	public $nombreTipo;

	function __construct($xIdMedioPago=null, $xNombre=null, $xIcono=null, $xIdMedioPagoTipo=null, $xNombreTipo=null){
		$this->idMedioPago = $xIdMedioPago;
		$this->nombre = $xNombre;
		$this->icono = $xIcono;
		$this->idMedioPagoTipo = $xIdMedioPagoTipo;
		$this->nombreTipo = $xNombreTipo;
	}
}
?>