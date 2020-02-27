<?php
namespace App\Clases\Common;

use App\Clases\Common\Archivo;
use App\Clases\Common\Paginador;

/**
 *  [getAllByTipoContenido description]  -> Retorna una lista de Archivo relacionado con un tipo de contenido.
 *  [getAttachment description]  -> Para hacer uso en el envio de formularios con archivos adjuntos.
 *  [getArchivos description]  -> Devuelve un vector de archivo.
 *  [setArchivos description]  -> Retorna un vector de Archivo recibiendo un vector de API.
*/

class Archivo {
	public $nombre;
	public $path;
	public $tamano;
	public $descripcion;
	public $extension;
	public $content;
	public $contenido;

	public function __construct($oArchivoAPI = null) {
		$this->nombre = isset($oArchivoAPI->nombre) ? $oArchivoAPI->nombre : '';
		$this->path = isset($oArchivoAPI->path) ? $oArchivoAPI->path : '';
		$this->tamano = isset($oArchivoAPI->tamano) ? $oArchivoAPI->tamano : '';
		$this->descripcion = isset($oArchivoAPI->descripcion) ? $oArchivoAPI->descripcion : '';
		$this->extension = isset($oArchivoAPI->extension) ? $oArchivoAPI->extension : '';
		$this->content = '';
		$this->contenido = isset($oArchivoAPI->contenido) ? $oArchivoAPI->contenido : '';
	}

	//	Retorna una lista de Archivo relacionado con un tipo de contenido
	public function getAllByTipoContenido($xIdTipoContenido = null, $xOffSet = 0, $xLimit = 30, $xOrderBy = 'idCont', $xOrderType = 'DESC') {
		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xOffSet) && is_numeric($xLimit) && is_string($xOrderBy) && is_string($xOrderType)) {
				$xUrl = config('main.URL_API')
				. "Archivos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido
				. "&xCampoOrden=" . $xOrderBy
				. "&xOrden=" . $xOrderType;

				return $this->getArchivos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Para hacer uso en el envio de formularios con archivos adjuntos
	public function getAttachment($oFile = null) {
		try {
			if (!empty($oFile)) {
				$xDirectorio = 'temp/';
				$xRuta = $xDirectorio . $oFile['name'];
				$xExtension = pathinfo($oFile['name'])['extension'];

				if (in_array($xExtension, Yii::app()->params['archivo-extensiones'])) {
					if (move_uploaded_file($oFile['tmp_name'], $xRuta) && $oFile['size'] < 2048000) {
						$oArchivo = new Archivo();
						$oArchivo->nombre = $oFile['name'];
						$oArchivo->tamano = filesize($xRuta);
						$oArchivo->descripcion = '';
						$oArchivo->extension = $xExtension;
						$oArchivo->content = base64_encode(fread(fopen($xRuta, "rb"), $oArchivo->tamano));

						unlink($xRuta);
						return $oArchivo;
					} else {
						return 'El archivo no puede superar los 2MB.';
					}

				} else {
					return 'No se permite este tipo de archivo';
				}
			}
		} catch (Exception $e) {
			Log::error();
		}
	}

	//	Devuelve un vector de archivo
	private function getArchivos($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_ARCHIVO'));
				$vDataAPI = json_decode($vDataAPI, false);
				$this->paginador = new Paginador($vDataAPI->paginador);
				return $this->setArchivos($vDataAPI->data->archivos);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Retorna un vector de Archivo recibiendo un vector de API
	private function setArchivos($vArchivosAPI = null) {
		try {
			if (is_array($vArchivosAPI)) {
				$vArchivos = array();
				foreach ($vArchivosAPI as $oArchivoAPI) {
					$vArchivos[] = new Archivo($oArchivoAPI);
				}

				return $vArchivos;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>