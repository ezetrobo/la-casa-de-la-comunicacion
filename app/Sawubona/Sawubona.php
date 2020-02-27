<?php
namespace App\Sawubona;

use App\Sawubona\Sawubona;
use Illuminate\Support\Facades\Lang;
use session;

/**
 *  [getCache description]  -> Gestiona un cache por cada URL que solicite.
 *  [writeLog description]  -> Escribe en un archivo log los errores a nivel objeto.
 *  [getIdioma description]   -> Retorna el idioma activo.
 *  [getTranslation description] -> Retorna un string en el idioma activo.
 *  [getLinkTranslation description] -> Retorna toda la URL de tranlation.
 *  [getLibrariesJS description] -> Agrega todas las librerias JS de un directorio.
 *  [array_sort description]  -> Ordena un array.
 */

class Sawubona {
	/**
	 * Gestiona un cache por cada URL que solicite data
	 * @param  String  $xUrl       [description]
	 * @param  integer $xTimeCache [description]
	 * @return JSON                [description]
	 */

	//Gestiona un cache por cada URL que solicite
	public static function getCache($xUrl = null, $xTimeCache = 3600) {
		try {
			// Si la variable no existe la creamos
			if (config('main.IS_CACHE') AND !\Cache::has($xUrl)) {
				$body = file_get_contents($xUrl);
				$json = (object) json_decode($body);
				\Cache::put($xUrl, $json, $xTimeCache);

				return \Cache::get($xUrl);
			} elseif (config('main.IS_CACHE') AND \Cache::has($xUrl)) {
				return \Cache::get($xUrl);
			}

			$body = file_get_contents($xUrl);
			$json = (object) json_decode($body);

			return $json;
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	public static function printShared($xLink = null) {
		echo '<ul class="shared">'
		. '<li class="btn-facebook">'
		. '<a href="' . $xLink . '">'
		. '<img src="'.asset('../images/icons/facebook.png').'"'
			. '</a>'
			. '</li>';

		echo '<li class="btn-twitter">'
		. '<a href="' . $xLink . '">'
		. '<img src="'.asset('../images/icons/twitter.png').'"'
			. '</a>'
			. '</li>'
			. '</ul>';
	}

	

	public static function convertToUrl($xString = null){
		try{
			if(is_string($xString)){
				$xString = strip_tags($xString);
				$xString = trim($xString);
				$xString = str_replace(
			        array(
			        	'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'é',
			        	'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë', 'í', 'ì', 'ï',
			        	'î', 'Í', 'Ì', 'Ï', 'Î', 'ó', 'ò', 'ö', 'ô', 'Ó',
			        	'Ò', 'Ö', 'Ô', 'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û',
			        	'Ü', 'ñ', 'Ñ', 'ç', 'Ç', "#", "@", "|", "!", "\"",
			        	"·", "$", "%", "&", "/", "(", ")", "?", "'", "¡",
			        	"¿", "[", "^", "`", "]", "+", "}", "{", "¨", "´",
			        	";", ",", ":", ".", " ", "  "
			        ),
			        array(
			        	'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'e',
			        	'e', 'e', 'e', 'E', 'E', 'E', 'E', 'i', 'i', 'i',
			        	'i', 'I', 'I', 'I', 'I', 'o', 'o', 'o', 'o', 'O',
			        	'O', 'O', 'O', 'u', 'u', 'u', 'u', 'U', 'U', 'U',
			        	'U', 'n', 'N', 'c', 'C',  "",  "",  "",  "",  "",
			        	 "",  "",  "",  "",  "",  "",  "",  "",  "",  "",
			        	 "",  "",  "",  "",  "",  "",  "",  "",  "",  "",
			        	 "",  "",  "",  "", "-", "-"
			        ),
			        $xString
			    );
				$xString = strtolower($xString);
				return $xString;
			}
		}
		catch(Exception $e){
			Sawubona::writeLog($e);
		}
	}

	//	Gestiona los parametros para contenidos y catalogos
	public static function getParam($xNombre = null) {
		if (is_string($xNombre)) {
			$vParams = (object) config('parametros.$xNombre');
			$xIdioma = Sawubona::getIdioma();
			if (isset($vParams->$xNombre->$xIdioma)) {
				return $vParams->$xNombre->$xIdioma;
			}
		}
	}

	//	Escribe en un archivo log los errores a nivel objeto
	public static function writeLog($e) {
		try {
			Log::warning($e->getMessage());
		} catch (Execption $e) {
			Log::error($e);
		}
	}
	
	//	Retorna el idioma activo
	public static function getIdioma() {
		try {
			$xIdioma = substr(str_replace(url(''), '', $_SERVER['REQUEST_URI']), 0, 4);
			
			switch (str_replace('/','', $xIdioma)) {
				case 'es':
					return 'es';
				case 'en':
					return 'en';
				case 'pt':
					return 'pt';
				case 'it':
					return 'it';
				case 'fr':
					return 'fr';
				default:
					return 'es';
			}	
		} catch (Exception $e) {
			Log::error($e);
		}
	}
	
	//	Retorna un string en el idioma activo
	public static function getTranslation($xClave = null, $xIdioma = null) {
		try {
			$xIdioma = is_null($xIdioma) ? Sawubona::getIdioma() : $xIdioma;
			$xClave = 'translations/' . $xIdioma . '/' . str_replace('.', '/data.json-', $xClave);
			$vClave = explode('-', $xClave);

			if (count($vClave) >= 2) {
				$xRoute = $vClave[0];
				$xIndex = $vClave[1];

				if (file_exists($xRoute)) {
					$xData = (object) json_decode(file_get_contents($xRoute), false);
					if (isset($xData->$xIndex)) {
						return $xData->$xIndex;
					}
				}
				return $xIndex;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Retorna toda la URL de tranlation
	public static function getLinkTranslation($xClave = null, $xIdioma = null) {
		$xIdioma = is_null($xIdioma) ? Sawubona::getIdioma() : $xIdioma;
		if (Sawubona::getTranslation($xClave, $xIdioma) != null) {
			return is_string($xClave) ? '/' . $xIdioma . Sawubona::getTranslation($xClave, $xIdioma)->href : null;
		}
	}

	//	Agrega todas las librerias JS de un directorio
	public function getLibrariesJS($xNameFolder = null) {
		try {
			$xDir = $_SERVER['DOCUMENT_ROOT'];

			if (file_exists($xDir . $xNameFolder) && is_dir($xDir . $xNameFolder)) {
				$xContenido = scandir($xDir . $xNameFolder);
				$xCount = count($xContenido);

				for ($i = 2; $i < $xCount; $i++) {
					if (is_dir($xDir . $xNameFolder . '/' . $xContenido[$i])) {
						Sawubona::getLibrariesJS($xNameFolder . '/' . $xContenido[$i]);
					} else {
						echo '<script src="' . $xNameFolder . '/' . $xContenido[$i] . '"></script>' . "\n";
					}
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	// Ordena un array
	public static function array_sort($array, $on, $order = SORT_ASC) {
		try {
			$new_array = array();
			$sortable_array = array();

			if (count($array) > 0) {
				foreach ($array as $k => $v) {
					if (is_array($v)) {
						foreach ($v as $k2 => $v2) {
							if ($k2 == $on) {
								$sortable_array[$k] = $v2;
							}
						}
					} else {
						$sortable_array[$k] = $v;
					}
				}

				switch ($order) {
					case SORT_ASC:
						asort($sortable_array);
					break;
					case SORT_DESC:
						arsort($sortable_array);
					break;
					default:
						asort($sortable_array);
				}

				foreach ($sortable_array as $k => $v){
					$new_array[$k] = $array[$k];
				}
			}
			return $new_array;	

		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [setIdiomaTexto description] Traduce el texto segun el idioma establecido.
	 * @param  string  $xTipo   [description] Tipo de texto establecido
	 * @param  string  $xExtra  [description] Texto extra para agregar al mensaje de respuesta
	 * @return Envia  al controlador el texto traducido
	 */
	public static function setIdiomaTexto($xTipo){
		try {
			return Lang::get("textos.".$xTipo);
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [camposPersonales description] Armamos el array con campos personales
	 * @param  array $arrayCamposPersonales
	 * @return array camposPersonales
	 */
	public static function camposPersonales($arrayCamposPersonales) {
		$campos_fechas = array(4401,4402);
		foreach ($arrayCamposPersonales as $key => $value) {
			$camposPersonales[] = array(
				'idCampoPersonal' => $key,
				'valor' => ((in_array(array_search($value, $arrayCamposPersonales),$campos_fechas)) ? date("d/m/Y", strtotime($value)) : $value)
			);
		}
		return $camposPersonales;
	}
	
	/**
	 * [perfilesGenerales description] Armamos el array con los perfiles generales
	 * @param  array $arrayPerfilesGenerales
	 * @return array perfilesGenerales
	 */
	public static function perfilesGenerales($arrayPerfilesGenerales) {
		foreach ($arrayPerfilesGenerales as $key => $value) {
			$perfilesGenerales[] = array(
				'idPerfilGeneral' => $key,
				'perfilGeneralValores' => array(
					0 => array(
						'idPerfilGeneralValor' => $value,
					),
				),
			);
		}
		return $perfilesGenerales;
	}

	public static function consultarToken() {
		try {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => config('main.URL_WS') . "/User/GetTokenRefresh",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
					"key: " . config("main.WS_KEY"),
					"token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImMyRjNkV0p2Ym1FdFlYUmxjbmx0IiwicGFzc3dvcmQiOiJjMkYzZFdKdmJtRXRZWFJsY25sdE1qQXhPU0UiLCJuYmYiOjE1NjU3MDYyMTgsImV4cCI6MTU2NTc5MjYxOCwiaWF0IjoxNTY1NzA2MjE4LCJpc3MiOiJIUiQycElqSFIkMnBJajEyIiwiYXVkIjoiaHR0cHM6Ly93cy5maWRlbGl0eXRvb2xzLm5ldC92MiJ9.XiO-zg_NMcqrORar5hTliSb3qwXy6-UAU-Z-3zMCBmE",
				),
			));

			$response = curl_exec($curl);

			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				throw new Exception("cURL Error #:" . $err);
			}
			$resultado = json_decode($response);
			if (isset($resultado->mensajes[0]->respuesta)) {
				$token = $resultado->mensajes[0]->respuesta;
				setcookie("token", $token, time() + 86400, "/");

			} else {
				throw new Exception($response);
			}

			return $token;

		} catch (Exception $e) {
			Log::error($e);
			return $response;
		}
	}

}