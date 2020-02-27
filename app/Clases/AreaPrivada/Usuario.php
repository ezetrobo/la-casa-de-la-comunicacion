<?php
namespace App\Clases\AreaPrivada;

use App\Clases\Common\Sitio;
use App\Clases\Common\Segmento;

class Usuario extends Persona{
	public $email;
	public $clave;

	public function __construct(){
		parent::__construct();
		$this->idPersona = 0;
		$this->email = null;
		$this->clave = null;
		$this->sitio = new Sitio(config('main.ID_SITIO'));
		$this->segmento = new Segmento();
	}

	//	Retorna el usuario en caso existir el email
	public function exist(){
		try{
			if(filter_var(trim($this->email), FILTER_VALIDATE_EMAIL) && is_numeric($this->segmento->idSegmento)){
				$xUrl = config('main.URL_API')
					."Personas?xKey=". config('main.FIDELITY_KEY')
					."&xIdSitio=". config('main.ID_SITIO')
					."&xIdSegmento=". $this->segmento->idSegmento
					."&xCampo=Email"
					."&xValor=". trim($this->email);

				$vUsuarios = $this->getUsuarios($xUrl);
				return ( isset($vUsuarios[0]) && $vUsuarios[0] instanceof Usuario ) ? $vUsuarios[0] : null;
			}
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	//	---------------------------------------------------------------		//
	//	Funciones privadas
	//	---------------------------------------------------------------		//

	protected function getUsuarios($xUrl = null){
		try{
			if(is_string($xUrl)){
				$vDataAPI = Sawubona::getCache($xUrl, 0, false);
				$vDataAPI = json_decode($vDataAPI, true);
				$vDataAPI = $vDataAPI['data']['personas'];
				return $this->setUsuarios($vDataAPI);
			}
		}
		catch(Excepcion $e){
			Sawubona::writeLog($e);
		}
	}

	protected function setUsuarios($vUsuariosAPI = null){
		try{
			if(is_array($vUsuariosAPI)){
				$vUsuarios = array();
				foreach ($vUsuariosAPI as $oUsuarioAPI)
					$vUsuarios[] = $this->setUsuario($oUsuarioAPI);
				unset($vUsuariosAPI);
				return $vUsuarios;
			}
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	protected function setUsuario($oUsuarioAPI = null){
		try{
			if(is_array($oUsuarioAPI)){
				$oUsuario = new Usuario();
				$oUsuario->idPersona = isset($oUsuarioAPI->idPersona) ? $oUsuarioAPI->idPersona : null;
				$oUsuario->email = isset($oUsuarioAPI->email) ? $oUsuarioAPI->email : null;
				$oUsuario->clave = isset($oUsuarioAPI->clave) ? $oUsuarioAPI->clave : null;

				return $oUsuario;
			}
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	protected function getUsuarioAPI(){
		try{
			$oUsuarioAPI = array();

			$oUsuarioAPI->email = $this->email;
			$oUsuarioAPI->clave = $this->clave;
			$oUsuarioAPI->sitio = array(
				'idSitio' => $this->sitio->idSitio
			);

			return $oUsuarioAPI;
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}
}
?>