<?php

namespace App\Clases\Persona;

use App\Clases\Common\Segmento;
use App\Clases\Persona\Direccion;
use App\Clases\Persona\Documento;
use App\Clases\Persona\Telefono;
use App\Sawubona\Sawubona;

class Persona {
	public $nombre;
	public $apellido;
	public $tipoPers;
	public $dni;
	public $pais;
	public $provincia;
	public $localidad;
	public $pref3;
	public $movil;
	public $email;
	public $habilitado;
	public $segmento;

	public function __construct() {
		try {
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$this->nombre = null;
			$this->apellido = null;
			$this->tipoPers = "persona";
			$this->dni = null;
			$this->pais = null;
			$this->provincia = null;
			$this->localidad = null;
			$this->pref3 = null;
			$this->movil = null;
			$this->email = null;
			$this->habilitado = "S";
			$this->segmento = new Segmento();
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public function convertPersonatoPersonaApi() {
		try {
			$sitio = array("idsitio" => config('main.ID_SITIO'));
			$segmento = array("idsegmento" => session('ecommerce')->segmento_compradores);
			
			$personaApi =
				array(
					"sitio" => $sitio,
					"segmento" => $segmento,
					"habilitado" => $this->habilitado,
					"idPersona" => $this->idPersona,
					"tipoPers" => $this->tipo,
					"nombre" => $this->nombre,
					"apellido" => $this->apellido,
					"dni" => $this->documento->numero,
					"fechaNac" => $this->fecha,
					"sexo" => $this->sexo,
					"empresa" => $this->empresa,
					"nomFantasia" => $this->nombreFantasia,
					"idioma" => $this->idioma,
					"observaciones" => $this->observaciones,
					"direccion" => $this->direccion->calle,
					"numero" => $this->direccion->numero,
					"piso" => $this->direccion->piso,
					"dpto" => $this->direccion->dpto,
					"barrio" => $this->direccion->barrio,
					"localidad" => $this->direccion->localidad,
					"provincia" => $this->direccion->provincia,
					"pais" => $this->direccion->pais,
					"cp" => $this->direccion->cp,
					"telefono3" => $this->telefonos[3]->numero,
					"pref3" => $this->telefonos[2]->caracteristica,
					"movil" => $this->telefonos[2]->numero,
					"email" => $this->emails[0],
				);

			return $personaApi;
		} catch (Exception $e) { 
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]); 
		}
	}

	//    Agrega esta persona y retorna el id
	public function add() {
		try {
			$xUrl = config('main.URL_API'). '/Personas?xKey=' . config('main.FIDELITY_KEY');
			$this->idPersona = $this->curl($xUrl, $this->setPersonaAPI($this));

			return $this->idPersona;
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	//registrar persona
	public function RegistrarP(
		$xNombre,
		$xApellido,
		$xUsuario,
		$xPrefijo,
		$xNumero,
		$empresa,
		$cuit) {

		$this->nombre = $xNombre;
		$this->apellido = $xApellido;
		$this->email = $xUsuario;
		$this->habilitado = 'N';
		$this->pref3 = $xPrefijo;
		$this->movil = $xNumero;
		$this->empresa = $empresa;
		$this->tipoPers = 'persona';
		$this->datosDurosxSitioxPersona = array(
		 	0 => array(
		 		'idDatoDuro' => 3881,
		 		'valor' => (integer) $cuit,
		 	),
		 );

		$this->sitio = array("idsitio" => config('main.ID_SITIO'));
		$this->segmento = array("idsegmento" => Sawubona::getParam('idAreaPrivadaSegmento'));

		$xUrl = config('main.URL_API').'AreaPrivadaLogin?xKey='.config('main.FIDELITY_KEY').'&xIdTipoCont='.Sawubona::getParam('idAreaPrivada').'&enviarCuentaEmail='.'false';

		$ch = curl_init($xUrl);
		$oPersonaAPI = json_encode($this);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $oPersonaAPI);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($oPersonaAPI),
			)
		);

		$result = json_decode(curl_exec($ch), true);

		if (isset($result['data']['personas'])) {
			return $result['data']['personas'];
		}
	}

	// Retorna una lista de Personas de un segmento
	public function getBySegmento($xIdSegmento = null,$xOffSet = 0,$xLimit = 5,$xOrderBy = 'idPersona',$xOrderType = 'DESC') {

		try {
			if (is_numeric($xIdSegmento) && is_numeric($xOffSet) && is_numeric($xLimit) && is_string($xOrderBy) && is_string($xOrderType)) {

				$xUrl = config('main.URL_API')
				. "Personas?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdSegmento=" . $xIdSegmento
				. "&xOrderBy=" . $xOrderBy
				. "&xOrden=" . $xOrderType;

				unset($xIdSegmento,$xOffSet,$xLimit,$xOrderBy,$xOrderType);

				return $this->getPersonas($xUrl);
			}
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	//    Devuelve un Vector de Persona por Segmento por Perfil
	public function getBySegmentoByPerfil($xIdSegmento = null,$xIdPerfil = null,$xOffSet = 0,$xLimit = 5) {
		try {
			if (is_numeric($xIdSegmento) && is_numeric($xIdPerfil) && is_numeric($xOffSet) && is_numeric($xLimit)) {
				$xUrl = config('main.URL_API')
				. "Personas?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdSegmento=" . $xIdSegmento
				. "&xIdPerfil=" . $xIdPerfil
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit;

				unset($xIdSegmento,$xIdPerfil,$xOffSet,$xLimit);

				return $this->getPersonas($xUrl);
			}
		} catch (Exception $e) {Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);}
	}

	//Retorna un objeto Persona por idPersona
	public function getById($xIdSegmento = null,$xIdPersona = null) {
		try {
			if (is_numeric($xIdSegmento) && is_numeri($xIdPersona)) {

				$xUrl = config('main.URL_API')
				. "Personas?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdSegmento=" . $xIdSegmento
				. "&xCampo=" . "idPersona"
				. "&xValor=" . $xIdPersona;

				$vPersonas = $this->getPersonas($xUrl);

				return isset($vPersonas[0]) ? $vPersonas[0] : null;
			}
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	//    Encripta un idPersona
	public function encryptId($xIdPersona = null) {
		try {
			if (is_numeric($xIdPersona)) {
				$xUrl = config('main.URL_API')
				. "Security?xKey=" . config('main.FIDELITY_KEY')
				. "&xOpcion=" . "encript"
				. "&xValue=" . $xIdPersona;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $xUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$vDataAPI = curl_exec($ch);
				curl_close($ch);
				$vDataAPI = json_decode($vDataAPI, false);

				return $vDataAPI->mensajeJson->mensaje;
			}
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	//    Desencripta un idPersona
	public function decryptId($xIdPersona = null) {
		try {
			if (is_string($xIdPersona)) {

				$xUrl = config('main.URL_API')
				. "Security?xKey=" . config('main.FIDELITY_KEY')
					. "&xOpcion=" . "decript"
					. "&xValue=" . $xIdPersona;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $xUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$vDataAPI = curl_exec($ch);
				curl_close($ch);
				$vDataAPI = json_decode($vDataAPI, false);

				return $vDataAPI->mensajeJson->mensaje;
			}
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	//    Setea las propiedades de la Persona a partir de variables POST y FILE (para usar con los formularios)
	public function setByPost() {

		try {
			if (!empty($_FILES)
				&& is_array($_FILES)) {

				foreach ($_FILES as $oFile) {
					$oArchivo = Archivo::getAttachment($oFile);
					if ($oArchivo instanceof Archivo) {
						$this->archivos[] = $oArchivo;
					} else {
						return $oArchivo;
					}

				}
			}

			if (empty($_POST) || !is_array($_POST)) {
				return 'POST sin datos';
			}

			$this->idPersona = isset($_POST['idPersona']) ? $_POST['idPersona'] : 0;
			$this->nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
			$this->apellido = isset($_POST['apellido']) ? $_POST['apellido'] : null;
			$this->fecha = isset($_POST['fechaNac']) ? new DateTime($_POST['fechaNac']) : null;
			$this->sexo = isset($_POST['sexo']) ? $_POST['sexo'] : null;
			$this->empresa = isset($_POST['empresa']) ? $_POST['empresa'] : null;
			$this->nombreFantasia = isset($_POST['nomFantasia']) ? $_POST['nomFantasia'] : null;
			$this->idioma = isset($_POST['idioma']) ? $_POST['idioma'] : null;
			$this->observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;
			$this->tipo = isset($_POST['tipoPers']) ? $_POST['tipoPers'] : null;

			$this->documento = new Documento($_POST);
			$this->direccion = new Direccion($_POST);

			//    Telefonos
			if (isset($_POST['codigoPais'])
				&& isset($_POST['pref1'])
				&& isset($_POST['telefono'])) {
				$this->telefonos[] = new Telefono(
					$_POST['codigoPais'],
					$_POST['pref1'],
					$_POST['telefono']
				);
			}

			if (isset($_POST['codigoPais'])
				&& isset($_POST['pref2'])
				&& isset($_POST['telefono2'])) {
				$this->telefonos[] = new Telefono(
					$_POST['codigoPais'],
					$_POST['pref2'],
					$_POST['telefono2']
				);
			}

			if (isset($_POST['codigoPais'])
				&& isset($_POST['pref3'])
				&& isset($_POST['movil'])) {
				$this->telefonos[] = new Telefono(
					$_POST['codigoPais'],
					$_POST['pref3'],
					$_POST['movil']
				);
			}

			if (isset($_POST['telefono3'])) {
				$this->telefonos[] = new Telefono(
					null,
					null,
					$_POST['telefono3']
				);
			}

			//    Emails
			if (isset($_POST['email'])) {
				$this->emails[] = trim($_POST['email']);
			}

			if (isset($_POST['email2'])) {
				$this->emails[] = trim($_POST['email2']);
			}

			foreach ($_POST as $clave => $valor) {
				if (strpos($clave, 'pf-') > -1) {
					//$oPersona['perfilxPersona'][] = new Perfil(str_replace('pf-', '', $clave));
				} else if (strpos($clave, 'pr-') > -1) {
					//$oPersona['preferencia'][] = new Preferencia(str_replace('pr-', '', $clave));
				} else if (strpos($clave, 'dd-') > -1) {
					$oDatoDuro = new DatoDuro();
					$oDatoDuro->idDatoDuro = str_replace('dd-', '', $clave);
					$oDatoDuro->valor = $valor;
					$this->datosDuros[] = $oDatoDuro;
				} else if (strpos($clave, 'pc-') > -1) {
					//$oPersona['perfilCustomxPersona'][] = new PerfilCustom(str_replace('pc-', '', $i));
				}
			}
		} catch (Exception $e) {Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);}
	}

	//    Envia un email a esta (this) persona
	public function sendMail($xFrom = null, $xSubject = null, $xBody = null) {
		try {
			$xEnvio = array(
				'from' => $xFrom,
				'to' => $this->emails[0],
				'subject' => $xSubject,
				'isBodyHtml' => true,
				'body' => $xBody,
			);

			$xUrl = config('main.URL_API') . "Soporte?xKey=" . config('main.FIDELITY_KEY');

			return $this->curl($xUrl, $xEnvio);

		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	public function loginPersona($xUsuario = null, $xClave = null) {
		try {
			if (filter_var(rtrim($xUsuario), FILTER_VALIDATE_EMAIL) && !empty($xUsuario) && is_string($xClave) && !empty($xClave)) {
				
				$xUrl = config('main.URL_API').'/AreaPrivadaLogin?xKey=' . config('main.FIDELITY_KEY');

				$oPersonaAPI = json_encode(
					array(
						'sitio' => array(
							'idSitio' => config('main.ID_SITIO'),
						),
						'email' => $xUsuario,
						'clave' => $xClave,
					)
				);
				$ch = curl_init($xUrl);
				
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");				
				curl_setopt($ch, CURLOPT_POSTFIELDS, $oPersonaAPI);				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);				
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: ' . 'application/json',
					'Content-Length: ' . strlen($oPersonaAPI))
				);
				$result = json_decode(curl_exec($ch), true);

				if (!empty($result['data']['personas'][0])) {
					$oPersona = $this->setPersona((object) $result['data']['personas'][0]);
					return $oPersona;
				}
			}
		} catch (Exception $e) {}
	}

	public function validarNombreUsuario($xIdSegmento = null, $xIdTipoContenido = null, $xUsuario = null) {
		if (is_numeric($xIdSegmento) && is_numeric($xIdTipoContenido) && is_string($xUsuario)) {

			$xUrl = config('main.URL_API')
			. 'AreaPrivadaLogin?xKey=' . config('main.FIDELITY_KEY')
			. '&xIdSitio=' . config('main.ID_SITIO')
				. '&xIdSegmento=' . $xIdSegmento
				. '&xIdTipoCont=' . $xIdTipoContenido
				. '&xEmail=' . $xUsuario;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $xUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$result = json_decode(curl_exec($ch), true);

			curl_close($ch);

			return $result['mensajeJson']['mensaje'];
		}
	}

	//Devuelve OK si el mail es correcto y envia clave al mail del mismo
	public function recoveryPasswordPersona($xEmail = null) {
		try {
			if (filter_var(rtrim($xEmail), FILTER_VALIDATE_EMAIL)) {

				$xUrl = config('main.URL_API')
				. "AreaPrivadaLogin?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xEmail=" . $xEmail;

				$ch = curl_init($xUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$result = json_decode(curl_exec($ch), true);

				if (!empty($result['mensajeJson']['mensaje'])) {

					if ($result['mensajeJson']['mensaje'] == 'OK') {
						return 'Se ha enviado la contraseña a su email';
					} else {
						return $result['mensajeJson']['mensaje'];
					}

				}
			}
		} catch (Exception $e) {
			//Yii::app()->Controller->render("/error/index", ["xMensaje" => $e]);
		}
	}

	public function RegistrarPersona($token = null) {
		$token = Sawubona::consultarToken();

		$xUrl = 'https://ws.fidelitytools.net/v2/api/segmentacion/persona/set';

		$ch = curl_init($xUrl);

		$aPersona = array($this);

		$aPersona = json_encode($aPersona);

		//print_r($aPersona);die;
		
		// Para consumir los servicios web:
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $xUrl,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $aPersona,
			CURLOPT_HTTPHEADER => array(
				"Authorization: " . $token,
				"Content-Type: application/json; charset=UTF-8",
				"cache-control: no-cache",
				"key: ".config("main.WS_KEY"),
			),
		));
		$result = json_decode(curl_exec($curl), true);

		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			return $result;
		}
	}

	//    -----------------------------------------
	//    Funciones privadas a la Clase
	//    -----------------------------------------
	//    Convierte un objeto Persona en PersonaAPI
	public function setPersonaAPI($oPersona = null) {

		try {
			if ($oPersona instanceof Persona) {

				$oPersonaAPI = array();
				$oPersonaAPI->idPersona = $oPersona->idPersona;
				$oPersonaAPI->habilitado = 'S ';
				$oPersonaAPI->sitio = array('idSitio' => config('main.ID_SITIO'));
				$oPersonaAPI->segmento = array('idSegmento' => $oPersona->segmento->idSegmento);
				$oPersonaAPI->nombre = $oPersona->nombre;
				$oPersonaAPI->apellido = $oPersona->apellido;
				$oPersonaAPI->fechaNac = ($oPersona->fecha instanceof DateTime) ? $oPersona->fecha->format('d-m-Y') : null;
				$oPersonaAPI->sexo = $oPersona->sexo;
				$oPersonaAPI->empresa = $oPersona->empresa;
				$oPersonaAPI->nomFantasia = $oPersona->nombreFantasia;
				$oPersonaAPI->idioma = $oPersona->idioma;
				$oPersonaAPI->observaciones = $oPersona->observaciones;
				$oPersonaAPI->tipoPers = $oPersona->tipo;
				$oPersonaAPI->clave = $oPersona->clave;

				if ($oPersona->documento instanceof Documento) {
					$oPersonaAPI->tipoDoc = $oPersona->documento->tipo;
					$oPersonaAPI->dni = (isset($oPersona->documento->numero) AND !empty($oPersona->documento->numero)) ? $oPersona->documento->numero : 0;
				}

				if ($oPersona->direccion instanceof Direccion) {
					$oPersonaAPI->direccion = $oPersona->direccion->calle;
					$oPersonaAPI->numero = $oPersona->direccion->numero;
					$oPersonaAPI->piso = $oPersona->direccion->piso;
					$oPersonaAPI->dpto = $oPersona->direccion->dpto;
					$oPersonaAPI->barrio = $oPersona->direccion->barrio;
					$oPersonaAPI->localidad = $oPersona->direccion->localidad;
					$oPersonaAPI->provincia = $oPersona->direccion->provincia;
					$oPersonaAPI->pais = $oPersona->direccion->pais;
					$oPersonaAPI->cp = $oPersona->direccion->cp;
				} else {
					$oPersona->direccion = new Direccion();
				}

				if (isset($oPersona->telefonos[0])) {
					$oPersonaAPI->codigoPais = $oPersona->telefonos[0]->codigo;
					$oPersonaAPI->pref1 = $oPersona->telefonos[0]->caracteristica;
					$oPersonaAPI->telefono = $oPersona->telefonos[0]->numero;
				}

				if (isset($oPersona->telefonos[1])) {
					$oPersonaAPI->codigoPais = $oPersona->telefonos[1]->codigo;
					$oPersonaAPI->pref2 = $oPersona->telefonos[1]->caracteristica;
					$oPersonaAPI->telefono2 = $oPersona->telefonos[1]->numero;
				}

				if (isset($oPersona->telefonos[2])) {
					$oPersonaAPI->codigoPais = $oPersona->telefonos[2]->codigo;
					$oPersonaAPI->pref3 = $oPersona->telefonos[2]->caracteristica;
					$oPersonaAPI->movil = $oPersona->telefonos[2]->numero;
				}

				if (isset($oPersona->telefonos[3])) {
					$oPersonaAPI->telefono3 = $oPersona->telefonos[3]->numero;
				}

				if (isset($oPersona->emails[0])) {
					$oPersonaAPI->email = $oPersona->emails[0];
				}

				if (isset($oPersona->emails[1])) {
					$oPersonaAPI->email2 = $oPersona->emails[1];
				}

				if (is_array($oPersona->datosDuros)
					&& count($oPersona->datosDuros) > 0) {

					$oPersonaAPI->datosDurosxSitioxPersona = array();
					foreach ($oPersona->datosDuros as $oDatoDuro) {
						$oPersonaAPI->datosDurosxSitioxPersona[] = array(
							'idDatoDuro' => $oDatoDuro->idDatoDuro,
							'nombre' => $oDatoDuro->nombre,
							'valor' => $oDatoDuro->valor,
						);
					}
				}

				if (is_array($oPersona->perfilesCustom)
					&& count($oPersona->perfilesCustom) > 0) {

					$oPersonaAPI->perfilCustomxPersona = array();
					foreach ($oPersona->perfilesCustom as $oPerfilCustom) {
						$oPersonaAPI->perfilCustomxPersona[] = array(
							'idPerfil' => $oPerfilCustom->idPerfil,
							'nombre' => $oPerfilCustom->nombre,
							'perfilesCustomValor' => $oPerfilCustom->valores,
						);
					}
				}

				unset($oPersona);

				return $oPersonaAPI;
			}
		} catch (Excepcion $e) {
			Log::error($e);
		}
	}

	//Devuelve un Vector de Persona
	protected function getPersonas($xUrl = null) {
		try {
			if (is_string($xUrl)) {

				$vDataAPI = Sawubona::getCache($xUrl, config('main.cachePersona'), config('main.cache'));
				$vDataAPI = json_decode($vDataAPI, true);
				$this->paginador = new Paginador($vDataAPI['paginador']);
				$vDataAPI = $vDataAPI['data']['personas'];

				return $this->setPersonas($vDataAPI);
			}
		} catch (Excepcion $e) {
			Log::error($e);
		}
	}

	//Convierte un Vector de PersonaAPI en un Vector de Persona
	protected function setPersonas($vPersonasAPI = null) {
		try {
			if (is_array($vPersonasAPI)) {

				$vPersonas = array();
				foreach ($vPersonasAPI as $oPersonaAPI) {
					$vPersonas[] = $this->setPersona($oPersonaAPI);
				}

				unset($vPersonasAPI);

				return $vPersonas;
			}
		} catch (Excepcion $e) {
			Log::error($e);
		}
	}

	//Convierte un Objeto PersonaAPI en Persona
	protected function setPersona($oPersonaAPI = null) {
		try
		{
			if (!empty($oPersonaAPI)) {
				//Sawubona::diePretty($oPersonaAPI);
				$oPersona = new Persona();
				$oPersona->idPersona = isset($oPersonaAPI->idPersona) ? $oPersonaAPI->idPersona : 0;
				$oPersona->nombre = isset($oPersonaAPI->nombre) ? $oPersonaAPI->nombre : null;
				$oPersona->apellido = isset($oPersonaAPI->apellido) ? $oPersonaAPI->apellido : null;
				$oPersona->fecha = isset($oPersonaAPI->fechaNac) ? new DateTime($oPersonaAPI->fechaNac) : null;
				$oPersona->sexo = isset($oPersonaAPI->sexo) ? $oPersonaAPI->sexo : null;
				$oPersona->empresa = isset($oPersonaAPI->empresa) ? $oPersonaAPI->empresa : null;
				$oPersona->nombreFantasia = isset($oPersonaAPI->nomFantasia) ? $oPersonaAPI->nomFantasia : null;
				$oPersona->idioma = isset($oPersonaAPI->idioma) ? $oPersonaAPI->idioma : null;
				$oPersona->observaciones = isset($oPersonaAPI->observaciones) ? $oPersonaAPI->observaciones : null;
				$oPersona->tipo = isset($oPersonaAPI->tipoPers) ? $oPersonaAPI->tipoPers : null;

				$oPersona->documento = new Documento($oPersonaAPI);
				$oPersona->direccion = new Direccion($oPersonaAPI);

				//    Telefonos
				if (isset($oPersonaAPI->codigoPais) && isset($oPersonaAPI->pref1) && isset($oPersonaAPI->telefono)) {
					$oPersona->telefonos[] = new Telefono(
						$oPersonaAPI->codigoPais,
						$oPersonaAPI->pref1,
						$oPersonaAPI->telefono
					);
				}

				if (isset($oPersonaAPI->codigoPais) && isset($oPersonaAPI->pref2) && isset($oPersonaAPI->telefono2)) {
					$oPersona->telefonos[] = new Telefono(
						$oPersonaAPI->codigoPais,
						$oPersonaAPI->pref2,
						$oPersonaAPI->telefono2
					);
				}

				if (isset($oPersonaAPI->codigoPais) && isset($oPersonaAPI->pref3) && isset($oPersonaAPI->movil)) {
					$oPersona->telefonos[] = new Telefono(
						$oPersonaAPI->codigoPais,
						$oPersonaAPI->pref3,
						$oPersonaAPI->movil
					);
				}

				if (isset($oPersonaAPI->telefono3)) {
					$oPersona->telefonos[] = new Telefono(
						null,
						null,
						$oPersonaAPI->telefono3
					);
				}

				//    Emails
				if (isset($oPersonaAPI->email)) {
					$oPersona->emails[] = trim($oPersonaAPI->email);
				}

				if (isset($oPersonaAPI->email2)) {
					$oPersona->emails[] = trim($oPersonaAPI->email2);
				}

				if ($oPersonaAPI->perfilCustomxPersona) {

					foreach ($oPersonaAPI->perfilCustomxPersona as $oPerfilCustomAPI) {
						$perfilCustomValor = $oPerfilCustomAPI['perfilesCustomValor'][0];
						$oPersona->perfilesCustom[] = new Perfil(
							$oPerfilCustomAPI['idPerfil'],
							$oPerfilCustomAPI['nombre'],
							$valores = array(
								'idValor' => $perfilCustomValor['idPerfilCustomValor'],
								'nombreValor' => $perfilCustomValor['nombre'],
							)
						);
					}

				}

				//    Datos Duros
				if (isset($oPersonaAPI->datosDurosxSitioxPersona) &&
					is_array($oPersonaAPI->datosDurosxSitioxPersona)) {
					foreach ($oPersonaAPI->datosDurosxSitioxPersona as $oDatoDuroAPI) {
						if ($oDatoDuroAPI['idDatoDuro'] === Sawubona::getParam('idDatoDuroCodigoPresea')) {
							$oPersona->codigoPresea = $oDatoDuroAPI['valor'];
						} else {
							$oPersona->datosDuros[] = new DatoDuro($oDatoDuroAPI);
						}
					}

				}

				unset($oPersonaAPI);

				return $oPersona;
			}
		} catch (Excepcion $e) {
			Log::error($e);
		}
	}

	//Gestiona una conexion con la API
	protected function curl($xUrl = null, $oPersonaAPI = null) {
		try {
			if (is_string($xUrl)) {

				$ch = curl_init($xUrl);
				$oPersonaAPI = json_encode($oPersonaAPI);

				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $oPersonaAPI);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/json',
					'Content-Length: ' . strlen($oPersonaAPI))
				);

				$result = json_decode(curl_exec($ch), true);

				return isset($result['mensajeJson']['mensaje']) ? $result['mensajeJson']['mensaje'] : 0;
			}
		} catch (Excepcion $e) {
			Log::error($e);
		}
	}
}