<?php
namespace App\Clases\Persona;

class Documento{
	public $tipo;
	public $numero;

	public function __construct($oPersonaAPI = null){
		$this->tipo = isset($oPersonaAPI->tipoDoc) ? $oPersonaAPI->tipoDoc : null;
		$this->numero = isset($oPersonaAPI->dni) ? $oPersonaAPI->dni : null;
	}
}
?>