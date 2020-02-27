<?php

namespace App\Clases\Carrito;
use App\Sawubona\Sawubona;

class OpcionPrecio{
	public $indice;
	public $nombre;
	public $moneda;

	function __construct($xIndice = 0, $xNombre = null, $oMoneda = null) {
		try{			
			$this->indice = $xIndice;
			$this->nombre = $xNombre;
			if ($oMoneda)
				$this->moneda = $oMoneda;
			else
				$this->moneda = new Moneda();
		}	
		catch(Exception $e){
			Sawubona::writeLog($e);
		}	
	}
}
?>