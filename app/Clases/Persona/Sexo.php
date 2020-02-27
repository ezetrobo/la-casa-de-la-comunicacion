<?php
namespace App\Clases\Persona;

class Sexo{
	public $idSexo;
	public $descripcion;

	public function __construct($idSexo = 3){
		//	1: Masculino
		//	0: Femenino
		//	3: indeterminado 
		$this->idSexo = $idSexo;
		$this->descripcion = ($this->idSexo == 1) ? 'Masculino' : ( ($this->idSexo == 0) ? 'Femenino' : 'Indeterminado' );
	}
}
?>