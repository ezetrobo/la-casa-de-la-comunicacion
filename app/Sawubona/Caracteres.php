<?php
namespace App\Sawubona;
/**
 *  [toUppercase description]  -> Convierte un string a Mayuscula.
 *  [toLowercase description]  -> Convierte un string a Minuscula.
 *  [removeHtml description]   -> Elimina codigo HTML de un string.
 *  [cutChar description]      -> Divide un string en x numero de caracteres.
 *  [cutWord description]      -> Divide un string en x cantidad de palabras.
 *  [convertToUrl description] -> Convierte un string para utilizarlo en URL.
 *  [removeTilde description]  -> Elimina los acentos, ñ y caracteres especiales de un string.
 *  [convertToId description]  -> Convierte un string en id.
 *  [convertToVector description] -> Convierte un String en un array de String.
 *  [getNombreCategoria description] -> Retorna el nombre de la categoria.
 *  [convertToVariableAPI description] -> 
 *  [compareString description] -> Compara 2 string.
 */

class Caracteres {
	/**
	 * summary
	 */
	
	//	Convierte un string a Mayuscula
	public static function toUppercase($xString = null) {
		try {
			if (is_string($xString)) {
				return strtoupper($xString);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Convierte un string a Minuscula
	public static function toLowercase($xString = null) {
		try {
			if (is_string($xString)) {
				return strtolower($xString);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Elimina todo el codigo HTML de un string
	public static function removeHtml($xString = null) {
		try {
			if (is_string($xString)) {
				return strip_tags($xString);
			}

		} catch (Exception $e) {
			Log::error($e);
		}
	}
	
	//	Corta un string en X cantidad de caracteres, por defecto 100
	public static function cutChar($xString = null, $xCantidad = 100) {
		try {
			if (is_string($xString) && is_numeric($xCantidad)) {
				return substr(Caracteres::removeHtml($xString), 0, $xCantidad);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Corta un string en X cantidad de palabras, por defecto 20
	public static function cutWord($xString = null, $xCantidad = 20) {
		try {
			if (is_string($xString) && is_numeric($xCantidad)) {
				$xString = Caracteres::removeHtml($xString);
				$vString = explode(" ", $xString);
				$xString = '';
				foreach ($vString as $index => $string) {
					if ($xCantidad > $index) {
						$xString .= $string . ' ';
					} else {
						break;
					}

				}
				return $xString;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Prepara un string para utilizarlo en una URL
	public static function convertToUrl($xString = null) {
		try {
			if (is_string($xString)) {
				$xString = htmlentities($xString);
				$xString = str_replace('&nbsp;', ' ', $xString);
				$xString = str_slug($xString);
				return $xString;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Elimina todos los acentos, ñ y caracteres especiales
	public static function removeTilde($xString = null) {
		try {
			if (is_string($xString)) {
				$xString = str_replace(
					array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
					array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
					$xString
				);
				$xString = str_replace(
					array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
					array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
					$xString
				);
				$xString = str_replace(
					array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
					array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
					$xString
				);

				$xString = str_replace(
					array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
					array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
					$xString
				);

				$xString = str_replace(
					array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
					array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
					$xString
				);

				$xString = str_replace(
					array('ñ', 'Ñ', 'ç', 'Ç'),
					array('n', 'N', 'c', 'C'),
					$xString
				);

				$xString = str_replace(
					array(
						"#", "@", "|", "!", "\"",
						"·", "$", "%", "&", "/",
						"(", ")", "?", "'", "¡",
						"¿", "[", "^", "`", "]",
						"+", "}", "{", "¨", "´",
						";", ",", ":", "."),
					"",
					$xString
				);
				return $xString;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Convierte un string en id con tipografia camello
	public static function convertToId($xString = null) {
		try {
			if (is_string($xString)) {
				$xString = Caracteres::removeHtml($xString);
				$xString = Caracteres::removeTilde($xString);
				$xString = Caracteres::cutWord($xString, 2);
				$xString = ucwords($xString);
				$xString = str_replace(" ", "", $xString);
				return $xString;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Convierte un String en un array de String
	public static function convertToVector($xString = null, $xDelimiter = null) {
		try {
			if (is_string($xString) && is_string($xDelimiter)) {
				return explode($xDelimiter, $xString);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getNombreCategoria description]
	 * @param  string $cadena [description]
	 * @return string         [description] 
	 * Retorna el nombre de la categoria
	 */
	public static function getNombreCategoria($cadena = null) {
		try {
			// Verifico que $cadena es string y contenga -
			if(is_string($cadena) && strpos($cadena, "-" ) !== false ){
				$arrayCadena = explode('-', $cadena);
				$arrayNombreCategoria = '';
				
				foreach ($arrayCadena as $nombre) {
					$nombre = ucwords($nombre) . ' ';
					$arrayNombreCategoria .= $nombre;
				}
				return $arrayNombreCategoria;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Remueve los espacios y los reemplaza por %20
	public static function convertToVariableAPI($xString = null) {
		try {
			if (is_string($xString)) {
				$xString = str_replace(" ", "%20", $xString);
				$xString = str_replace("+", "MAS", $xString);
				return $xString;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Compara dos String, devuelve true si son iguales
	public static function compareString($xStringA = null, $xStringB = null) {
		try {
			if (is_string($xStringA) && is_string($xStringB)) {
				$xStringA = Caracteres::removeHtml($xStringA);
				$xStringA = Caracteres::removeTilde($xStringA);
				$xStringA = Caracteres::toLowercase($xStringA);

				$xStringB = Caracteres::removeHtml($xStringB);
				$xStringB = Caracteres::removeTilde($xStringB);
				$xStringB = Caracteres::toLowercase($xStringB);

				if ($xStringA == $xStringB) {
					return true;
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
