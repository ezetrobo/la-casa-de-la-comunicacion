<?php
namespace App\Clases\Persona;

class Telefono{
	public $codigo;
	public $caracteristica;
	public $numero;

	public function __construct($xCodigo = null, $xCaracteristica = null, $xNumero = null){
		$this->codigo = $xCodigo;
		$this->caracteristica = $xCaracteristica;
		$this->numero = $xNumero;
	}
}
?>