<?php

namespace App\Clases\Producto;

use App\Sawubona\Caracteres;
use App\Sawubona\Sawubona;

class Etiqueta {
	public $idEtiqueta;
	public $nombre;
	public $etiquetas;

	public function __construct($xIdEtiqueta = 0, $xNombre = '') {
		try {
			$this->idEtiqueta = $xIdEtiqueta;
			$this->nombre = $xNombre;
			$this->etiquetas = null;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getAll description] Retorna una lista de todas las etiquetas que se encuentran en el sitio
	 * @param  integer $xIdTipoContenido 	[description]
	 * @return Etiqueta                    	[description]
	 */
	public function getAll($xIdTipoContenido = 0) {
		try {
			$xUrl = config('main.URL_API')
			. "EtiquetasProductos?"
			. "xKey=" . config('main.FIDELITY_KEY')
			. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido;

			return $this->getEtiquetas($xUrl);
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getAllExceptHome description] Retorna un array de todas las etiquetas menos las utilizadas para poblar el home
	 * @param  integer $xIdTipoContenido [description]
	 * @return Etiqueta                    [description]
	 */
	public function getAllExceptHome($xIdTipoContenido = 0) {
		try {
			$xUrl = config('main.URL_API')
			. "EtiquetasProductos?"
			. "xKey=" . config('main.FIDELITY_KEY')
			. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido;

			return $this->getEtiquetas($xUrl);
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getLink description] Devuelve el link
	 * @return string [description]
	 */
	public function getLink() {
		try {
			$vNombres = explode('@', $this->nombre);
			$xLink = $_SERVER['REQUEST_URI'];

			if (!strpos($xLink, '/filtro')) {
				$xLink .= '/';
				$xLink .= Caracteres::convertToUrl($vNombres[0]);
			} else if (!strpos($xLink, Caracteres::convertToUrl($vNombres[0]))) {
				$xLink .= '_' . Caracteres::convertToUrl($vNombres[0]);
			}

			$xLink = str_replace(['&dinamic=true', '?dinamic=true'], ['', ''], $xLink);

			return $xLink;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getLinkDelete description] Devuelve el link de eliminación de la etiquetas
	 * @return string [description]
	 */
	public function getLinkDelete() {
		try {
			$xLink = $_SERVER['REQUEST_URI'];
			if (strpos($this->nombre, '@')) {
				$this->nombre = substr($this->nombre, 0, -8);
			}
			$xNombre = Caracteres::convertToUrl($this->nombre);

			if (strpos($xLink, $xNombre)) {
				$xLink = str_replace(
					[
						'_' . $xNombre,
						$xNombre . '_',
						$xNombre,
						'&dinamic=true',
						'?dinamic=true',
					],
					['', '', '', '', ''],
					$xLink
				);
				return $xLink;
			}

		} catch (Exception $e) {
			Sawubona::writeLog($e);
		}
	}
	/**
	 * [getEtiquetasSelected description] Devuelve la etiquetas seleccionada
	 * @param  string  $xEtiquetas  [description]
	 * @param  integer $xIdCatalogo [description]
	 * @return array               	[description]
	 */
	public function getEtiquetasSelected($xEtiquetas = null, $xIdCatalogo = 0) {
		try {
			if (is_string($xEtiquetas) && $xEtiquetas != '') {
				$vEtiquetasSelected = explode('_', $xEtiquetas);
				$vEtiquetas = $this->getAll($xIdCatalogo);
				$vEtiquetasFiltradas = array();

				foreach ($vEtiquetas as $oEtiqueta) {
					foreach ($oEtiqueta->etiquetas as $oEtiquetaHija) {
						$vNombres = explode('@', $oEtiquetaHija->nombre);
						if (in_array(Caracteres::convertToUrl($vNombres[0]), $vEtiquetasSelected)) {
							$oEtiquetaHija->nombreDisplay = substr($oEtiquetaHija->nombre, strlen($oEtiqueta->nombre) + 1);
							$vEtiquetasFiltradas[] = $oEtiquetaHija;
						}
					}
				}
				return $vEtiquetasFiltradas;
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}

	/**
	 * Funciones privadas
	 */

	/**
	 * [getEtiquetas description]
	 * @param  [type] $xUrl [description]
	 * @return [type]       [description]
	 */
	protected function getEtiquetas($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_PRODUCTO'));

				if ($vDataAPI->data != null) {
					$vDataAPI = $vDataAPI->data->etiquetasProductos;
				}
				return $this->setEtiquetas($vDataAPI);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [setEtiquetas description]
	 * @param [type] $vEtiquetasAPI [description]
	 */
	protected function setEtiquetas($vEtiquetasAPI = null) {
		try {
			$vEtiquetas = array();
			if (is_array($vEtiquetasAPI)) {

				$xCount = count($vEtiquetasAPI);
				for ($i = 0; $i < $xCount; $i++) {
					$vEtiquetas[] = $this->setEtiqueta($vEtiquetasAPI[$i]);
				}

			}

			return $vEtiquetas;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [setEtiqueta description]
	 * @param [type] $oEtiquetaAPI [description]
	 */
	protected function setEtiqueta($oEtiquetaAPI = null) {
		try {
			$oEtiqueta = new Etiqueta(
				$oEtiquetaAPI->id,
				$oEtiquetaAPI->nombre);

			$xCount = count($oEtiquetaAPI->valores);
			if (is_array($oEtiquetaAPI->valores)
				&& $xCount > 0) {

				$oEtiqueta->etiquetas = array();
				for ($i = 0; $i < $xCount; $i++) {
					$oEtiqueta->etiquetas[] = $this->setEtiquetaHija($oEtiquetaAPI->valores[$i], $oEtiqueta);
				}

			}

			return $oEtiqueta;

		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [setEtiquetaHija description]
	 * @param [type] $oEtiquetaAPI   [description]
	 * @param [type] $oEtiquetaPadre [description]
	 */
	protected function setEtiquetaHija($oEtiquetaAPI = null, $oEtiquetaPadre = null) {

		try {
			$oEtiqueta = new Etiqueta($oEtiquetaAPI->id, $oEtiquetaPadre->nombre . "-" . $oEtiquetaAPI->nombre);
			return $oEtiqueta;

		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [cast description]
	 * @param  [type] $vEtiquetasAPI [description]
	 * @return [type]                [description]
	 */
	public static function cast($vEtiquetasAPI = null) {
		try {
			if (is_array($vEtiquetasAPI)) {
				$oEtiqueta = new Etiqueta();
				return $oEtiqueta->setEtiquetas($vEtiquetasAPI);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
}
?>