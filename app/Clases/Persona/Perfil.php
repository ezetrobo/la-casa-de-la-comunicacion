<?php
namespace App\Clases\Persona;

/**
 *  [getBySegmento description]  -> 
 *  [setValores description]  -> 
 *  [setValoresAPI description]  -> 
 *  [getPerfiles description private]  -> 
 *  [setPerfiles description private]  -> 
 *  [setPerfil description private]  -> 
*/

class Perfil{
	
	public $idPerfil;
	public $nombre;
	public $valores;	//	Lista de valores seleccionados
	public $perfiles;	//	Lista de valores posibles

	public function __construct($xIdPerfil, $xNombre, $xValores, $xPerfiles = null){
		$this->idPerfil = $xIdPerfil;
		$this->nombre = $xNombre;
		$this->valores = $xValores;
		$this->perfiles = $xPerfiles;
	}

	public function getBySegmento($xIdSegmento = null){
		try {
			if(is_numeric($xIdSegmento)){
				$xUrl = config('main.URL_API')
				."PerfilCustom?xKey=". config('main.FIDELITY_KEY')
				."&xIdSitio=". config('main.ID_SITIO')
				."&xIdSegmento=". $xIdSegmento;
				return $this->getPerfiles($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function setValores($vValoresAPI = null){
		try {			
			if(is_array($vValoresAPI) && count($vValoresAPI) > 0){
				$vValores = array();
				foreach($vValoresAPI as $oValorAPI){
					$oPerfil = new Perfil();
					$oPerfil->idPerfil = $oValorAPI->idPerfilCustomValor;
					$oPerfil->nombre = $oValorAPI->nombre;
					$oPerfil->perfiles = null;
					$oPerfil->valores = null;
					$vValores[] = $oPerfil;
				}
				return $vValores;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function setValoresAPI($vValoresAPI = null){
		try {
			
			if(is_array($vValoresAPI) && count($vValoresAPI) > 0){
				$vValores = array();
				foreach($vValoresAPI as $oValorAPI){
					$oPerfil = new Perfil();
					$oPerfil->idPerfil = $oValorAPI['idPerfilCustomValor'];
					$oPerfil->nombre = $oValorAPI['nombre'];
					$oPerfil->perfiles = null;
					$oPerfil->valores = null;
					$vValores[] = $oPerfil;
				}
				return $vValores;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}


//--------------------------------------------------------------------------
//	Funciones privadas
//--------------------------------------------------------------------------

	private function getPerfiles($xUrl = null){
		try {
			if(is_string($xUrl)){
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_PERSONA'), config('main.IS_CACHE'));
				$vDataAPI = (object)json_decode($vDataAPI, false);
				$vDataAPI = $vDataAPI->data->perfilesCustom;
				return $this->setPerfiles($vDataAPI);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	private function setPerfiles($vPerfilesAPI = null){
		try {
			if(is_array($vPerfilesAPI)){
				$vPerfiles = array();
				foreach ($vPerfilesAPI as $oPerfilAPI)
					$vPerfiles[] = $this->setPerfil($oPerfilAPI);
				unset($vPerfilesAPI);
				return $vPerfiles;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	private function setPerfil($oPerfilAPI = null){
		try {
			$oPerfil = new Perfil();
			$oPerfil->idPerfil = isset($oPerfilAPI->idPerfilCustom) ? $oPerfilAPI->idPerfilCustom : 0;
			$oPerfil->nombre = isset($oPerfilAPI->nombre) ? $oPerfilAPI->nombre : null;
			$oPerfil->valores = isset($oPerfilAPI->listaPerfilCustomValor) ? $this->setValores($oPerfilAPI->listaPerfilCustomValor) : array();

			unset($oPerfilAPI);
			return $oPerfil;
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>