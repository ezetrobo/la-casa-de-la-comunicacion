<?php
namespace App\Clases\AreaPrivada;

class AreaPrivada{
	private $idAreaPrivada;
	private $usuario;
	private $session = 'sawubona-area-privada';

	public function __construct($xIdAreaPrivada = null){
		try{
			session()->get($this->session) = isset(session()->get($this->session)) ? session()->get($this->session) : $this;
			$this->idAreaPrivada = $xIdAreaPrivada;
			$this->usuario = isset(session()->get($this->session)->usuario) ? session()->get($this->session)->usuario : null;
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	public function setUsuario($oUsuario = null){
		session()->get($this->session)->usuario = ($oUsuario instanceof Usuario) ? $oUsuario : null;
		$this->usuario = session()->get($this->session)->usuario;
	}

	public function getUsuario(){
		return (session()->get($this->session)->usuario instanceof Usuario) ? session()->get($this->session)->usuario : null;
	}

	//	Loguea por email y clave
	public function login($oUsuario = null){
		try{
			if($oUsuario instanceof Usuario){
				$oUsuario = $oUsuario->login();
				if($oUsuario instanceof Usuario){
					$this->setUsuario($oUsuario);
					return true;
				}

				return $this->logout();
			}

			return $this->logout();
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	//	Loguea por email, debe asignarse el segmento del usuario
	public function loginByEmail($oUsuario = null){
		try{
			if($oUsuario instanceof Usuario){
				$oUsuario = $oUsuario->exist();
				if($oUsuario instanceof Usuario){
					$this->setUsuario($oUsuario);
					return true;
				}

				return $this->logout();
			}

			return $this->logout();
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}
	
?>