<?php
namespace App\Clases\Marcador;

use App\Clases\Persona\Persona;
use App\Clases\Marcador\Marcador;

/**
 *  [getBySegmento description]  -> Devuelve un Vector de Marcador por Segmento.
 *  [getBySegmentoByPerfil description]  -> Devuelve un Vector de Marcador por Segmento por Perfil.
 *  [convertPersonasToMarcadores description private]  -> Convierte un Vector de Persona en Vector de Marcador.
 *  [convertPersonaToMarcador description private]  -> Convierte un Objeto Persona en Objeto Marcador.
*/

class Marcador{
	public $idMarcador;
	public $descripcion;
	public $latitud;
	public $longitud;

	public function __construct(){
		$this->idMarcador = 0;
		$this->descripcion = null;
		$this->latitud = null;
		$this->longitud = null;

		if(isset(config('main.JS_MAP')))
			config('main.JS_MAP') = true;
	}

	//	Devuelve un Vector de Marcador por Segmento
	public function getBySegmento($xIdSegmento = null, $xOffset = 0, $xLimit = 50, $xEstructura = null){
		try{
			if(is_numeric($xIdSegmento)){
				$oPersona = new Persona();
				$vPersonas = $oPersona->getBySegmento($xIdSegmento, $xOffset, $xLimit);
				return $this->convertPersonasToMarcadores($vPersonas, $xEstructura);
			}
		}
		catch(Exception $e){
			Log::error($e);
		}
	}

	//	Devuelve un Vector de Marcador por Segmento por Perfil
	public function getBySegmentoByPerfil($xIdSegmento = null, $xIdPerfil = null, $xOffset = 0, $xLimit = 50, $xEstructura){
		try{
			if(is_numeric($xIdSegmento) && is_numeric($xIdPerfil)){
				$oPersona = new Persona();
				$vPersonas = $oPersona->getBySegmentoByPerfil($xIdSegmento, $xIdPerfil, $xOffset, $xLimit);
				return $this->convertPersonasToMarcadores($vPersonas, $xEstructura);
			}
		}
		catch(Exception $e){
			Log::error($e);
		}
	}


	//--------------------------------------------------------------------------------------------
	//	Funciones privadas a la Clase 	//--------------------------------------------------------------------------------------------

	//	Convierte un Vector de Persona en Vector de Marcador
	private function convertPersonasToMarcadores($vPersonas = null, $xEstructura = null){
		try{
			if(is_array($vPersonas) && count($vPersonas) > 0){
				$vMarcadores = array();
				foreach ($vPersonas as $oPersona){
					$oMarcador = $this->convertPersonaToMarcador($oPersona, $xEstructura);
					if($oMarcador instanceof Marcador)
						$vMarcadores[] = $oMarcador;
				}
				unset($vPersonas);
				return $vMarcadores;
			}
		}
		catch(Exception $e){
			Log::error($e);
		}
	}

	//	Convierte un Objeto Persona en Objeto Marcador
	private function convertPersonaToMarcador($oPersona = null, $xEstructura = null){
		try{
			if($oPersona instanceof Persona){
				$oMarcador = new Marcador();
				$oMarcador->idMarcador = $oPersona->idPersona;

				if(is_array($oPersona->datosDuros) && count($oPersona->datosDuros > 0)){
					//	Busco latitud y longitud
					foreach ($oPersona->datosDuros as $oDatoduro){
						switch (strtolower($oDatoduro->nombre)) {
							case 'latitud':
								$oMarcador->latitud = $oDatoduro->valor;
								break;

							case 'longitud':
								$oMarcador->longitud = $oDatoduro->valor;
								break;
						}
	                }

	                if($oMarcador->latitud != null && $oMarcador->longitud != null){
		                if(!is_array($xEstructura))
		                	$xEstructura = array('nombre', 'empresa', 'telefono', 'direccion', 'observaciones');

		                $oMarcador->descripcion = '';

		                foreach ($xEstructura as $xPropiedad){
	                		switch ($xPropiedad){
	                			case 'empresa':
	                				if(property_exists('Persona', 'empresa'))
	                					$oMarcador->descripcion .= '<p><b>'. trim($oPersona->empresa) .'</b></p>';
	                				break;

	                			case 'nombre':
	                				if(property_exists('Persona', 'nombre'))
	                					$oMarcador->descripcion .= '<p><b>'. trim($oPersona->nombre) .'</b></p>';
	                				break;

	                			case 'direccion':
	                				if(property_exists('Persona', 'direccion')){
	                					$oMarcador->descripcion .= '<p>';
	                					if(!empty($oPersona->direccion->calle)) $oMarcador->descripcion .= $oPersona->direccion->calle .' ';
	                					if(!empty($oPersona->direccion->numero)) $oMarcador->descripcion .= $oPersona->direccion->numero .' | ';
	                					if(!empty($oPersona->direccion->piso)) $oMarcador->descripcion .= $oPersona->direccion->piso .' ';
	                					if(!empty($oPersona->direccion->dpto)) $oMarcador->descripcion .= $oPersona->direccion->dpto .'<br>';
	                					if(!empty($oPersona->direccion->localidad)) $oMarcador->descripcion .= $oPersona->direccion->localidad .', ';
	                					if(!empty($oPersona->direccion->provincia)) $oMarcador->descripcion .= $oPersona->direccion->provincia .', ';
	                					if(!empty($oPersona->direccion->pais)) $oMarcador->descripcion .= $oPersona->direccion->pais;
	                					$oMarcador->descripcion .= '</p>';
	                				}
	                				break;

	                			case 'telefono':
	                				if(property_exists('Persona', 'telefonos') && is_array($oPersona->telefonos) && count($oPersona->telefonos) > 0){
	                					$oMarcador->descripcion .= '<p>';
	                					foreach ($oPersona->telefonos as $oTelefono){
	                						if(!empty($oTelefono->codigo)) $oMarcador->descripcion .= $oTelefono->codigo .' ';
	                						if(!empty($oTelefono->caracteristica)) $oMarcador->descripcion .= $oTelefono->caracteristica .' ';
	                						if(!empty($oTelefono->numero)) $oMarcador->descripcion .= $oTelefono->numero .' | ';
	                					}
	                					$oMarcador->descripcion .= '</p>';
	                				}
	                				break;
	                			
	                			default:
	                				if(property_exists('Persona', $xPropiedad))
	                					$oMarcador->descripcion .= '<p>'. $oPersona->$xPropiedad .'</p>';
	                				break;
	                		}
		                }

		                unset($oPersona, $xEstructura);
	                	return $oMarcador;
	                }
				}
			}
		}
		catch(Exception $e){
			Log::error($e);
		}
	}
}
?>