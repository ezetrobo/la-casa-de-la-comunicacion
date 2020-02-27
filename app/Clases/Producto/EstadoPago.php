<?php
namespace App\Clases\Producto;

class EstadoPago {
	public $idEstado;
	public $nombre;

	public function __construct() {
		try {
			$this->idEstado = null;
			$this->nombre = null;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
}
?>