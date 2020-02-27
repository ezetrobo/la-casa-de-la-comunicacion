<?php

namespace Clases\Carrito;

class OpcionEnvio{
	public $idActivo;
	public $listaCostoEnvioZona;

	public function __construct($xIdActivo=NULL, $xListaCostoEnvioZona=null){
		$this->idActivo=$xIdActivo;
		$this->listaCostoEnvioZona=$xListaCostoEnvioZona;
	}

	public function getCostobyId($id){
		$costo=null;
		if ($id!=null){
			if ($this->listaCostoEnvioZona!=null){
				foreach($this->listaCostoEnvioZona as $zona){
					if ($zona[0]==$id and isset($zona[2])){
						$costo = $zona[2];
						break;
					}
				}
			}
		}
		return $costo;
	}
	
	public function getHastabyId($id){
		$costo=null;
		if ($id!=null){
			if ($this->listaCostoEnvioZona!=null){
				foreach($this->listaCostoEnvioZona as $zona){
					if ($zona[0]==$id and isset($zona[3])){
						$costo = $zona[3];
						break;
					}
				}
			}
		}
		return $costo;
	}
	
	public function getExcedentebyId($id){
		$costo=null;
		if ($id!=null){
			if ($this->listaCostoEnvioZona!=null){
				foreach($this->listaCostoEnvioZona as $zona){
					if ($zona[0]==$id and isset($zona[4])){
						$costo = $zona[4];
						break;
					}
				}
			}
		}
		return $costo;
	}
	
	public function getLocalidadActiva(){
		$localidad=null;		
		if ($this->listaCostoEnvioZona!=null){
			foreach($this->listaCostoEnvioZona as $zona){
				if ($zona[0]==$this->idActivo and isset($zona[1])){
					$localidad = $zona[1];
					break;
				}
			}
		}		
		return $localidad;
	}
}
?>