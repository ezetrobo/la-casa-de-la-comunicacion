<?php

namespace App\Clases\Carrito;

class Moneda{
	public $nombre;
	public $simbolo;
	public $abreviacion;

	function __construct($xNombre='peso',$xSimbolo='$',$xAbreviacion='ARS'){		
		$this->nombre=$xNombre;
		$this->simbolo=$xSimbolo;
		$this->abreviacion=$xAbreviacion;
	}
}
?>