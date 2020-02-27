<?php
namespace App\Clases\Persona;

class DatoDuro
{
	public $idDatoDuro;
	public $nombre;
	public $valor;

	public function __construct($oDatoDuroAPI = null){
        $this->idDatoDuro =
            isset($oDatoDuroAPI->idDatoDuro)   ? $oDatoDuroAPI->idDatoDuro   : 
            isset($oDatoDuroAPI['idDatoDuro']) ? $oDatoDuroAPI['idDatoDuro'] : null;
        $this->nombre =
            isset($oDatoDuroAPI->nombre)   ? $oDatoDuroAPI->nombre   :
            isset($oDatoDuroAPI['nombre']) ? $oDatoDuroAPI['nombre'] : null;
        $this->valor =
            isset($oDatoDuroAPI->valor)   ? $oDatoDuroAPI->valor   :
            isset($oDatoDuroAPI['valor']) ? $oDatoDuroAPI['valor'] : null;
	}
}
