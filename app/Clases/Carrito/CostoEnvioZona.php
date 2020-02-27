<?php 

namespace Clases\Carrito;

class CostoEnvioZona{
	public $idCostoEnvioZona;
	public $nombre;
	public $costo;
	public $hasta;
	public $excedente;
	public $listaCostoEnvioZonaDep;

	function __construct($xIdCostoEnvioZona=NULL, $xNombre=NULL, $xCosto=NULL, $xHasta=NULL, $xExcedente=NULL, $xListaCostoEnvioZonaDep=array()){
		$this->idCostoEnvioZona = $xIdCostoEnvioZona;
		$this->nombre = $xNombre;
		$this->costo = $xCosto;
		$this->hasta = $xHasta;
		$this->excedente = $xExcedente;
		$this->listaCostoEnvioZonaDep = $xListaCostoEnvioZonaDep;

	}

	function convertZonadepApitoCostoEnvioZona($xListaCostoEnvioZonaDepApi=NULL){
		try{
			if ($xListaCostoEnvioZonaDepApi){
				$this->idCostoEnvioZona = $xListaCostoEnvioZonaDepApi['idCostoEnvioZona'];
				$this->nombre = $xListaCostoEnvioZonaDepApi['zona'];
				$this->costo = $xListaCostoEnvioZonaDepApi['valor'];
				$this->hasta = $xListaCostoEnvioZonaDepApi['hastaKG'];
				$this->excedente = $xListaCostoEnvioZonaDepApi['excedente'];
				$this->listaCostoEnvioZonaDep = NULL;
			}
		}
		catch(Excepcion $e){
			print_r($e);
		}
	}
}
?>