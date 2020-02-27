<?php

namespace App\Clases\Producto;

use App\Sawubona\Caracteres;
use App\Sawubona\Sawubona;

class SCatalogo {
	public $productos;
	public $etiquetas;
	public $categorias;

	public function __construct() {
		$this->productos = array();
		$this->etiquetas = array();
		$this->categorias = array();
	}
	/**
	 * @param  integer  $xIdCatalogo  [description]
	 * @param  integer $xIdCategoria  [description]
	 * @param  string  $xEtiquetas    [description]
	 * @param  integer $xOffSet       [description]
	 * @param  integer $xLimit        [description]
	 * @param  string  $xOrderBy      [description]
	 * @param  string  $xOrderType    [description]
	 * @return array                  [description]
	 */
	public function getProductos($xIdCatalogo = null, $xIdCategoria = 0, $xEtiquetas = null, $xOffSet = 0, $xLimit = 6, $xOrderBy = 'orden', $xOrderType = 'asc') {
		try
		{
			if (is_numeric($xIdCatalogo) && is_numeric($xIdCategoria) && is_numeric($xOffSet) && is_numeric($xLimit)) {

				$prioridad = (is_numeric($xIdCategoria) && $xIdCategoria > 0) ? 1 : 2;
				$xUrl = config('main.URL_API')
				. "Catalogo?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdCategoria=" . $xIdCategoria
				. "&vEtiquetas=" . $this->getEtiquetasUrl($xEtiquetas, $xIdCatalogo)
					. "&xPrioridad=" . ((is_numeric($xIdCategoria) && $xIdCategoria > 0) ? 2 : 1)
					. '&xIdProducto=0'
					. '&xCampoOrden=' . $xOrderBy
					. '&xOrdenCampo=' . $xOrderType;
				$this->getCatalogo($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * @param  integer  $xIdCatalogo       		[description] REQUIRED
	 * @param  integer $xIdCategoria      		[description] REQUIRED but can be 0
	 * @param  integer $xOffSet           		[description]
	 * @param  integer $xLimit            		[description]
	 * @param  integer $vIdEtiquetasMadre 		[description]
	 * @return array                     		[description]
	 */
	public function getByEtiquetaMadre($xIdCatalogo = null, $xIdCategoria = 0, $xOffSet = 0, $xLimit = 12, $vIdEtiquetasMadre = 0) {
		try {
			if (is_numeric($xIdCatalogo) && is_numeric($xIdCategoria) && is_numeric($xOffSet) && is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Catalogo?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdCategoria=" . $xIdCategoria
				. "&vEtiquetasMadres=" . urlencode($vIdEtiquetasMadre)
					. "&xCampoOrden=" . ""
					. "&xOrdenCampo=" . ""
					. "&xCampoValor=" . ""
					. "&xValorCampo=" . ""
					. "&xCampoDesdeHasta=" . ""
					. "&xDesde=" . ""
					. "&xHasta=" . "";

				unset($xIdCatalogo, $xOffSet, $xLimit);

				return $this->getCatalogo($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * @param  integer  $xIdCatalogo  		[description] REQUIRED
	 * @param  integer $xIdCategoria 		[description] REQUIRED but can be 0
	 * @param  string  $xEtiquetas   		[description]
	 * @param  integer $xOffSet      		[description]
	 * @param  integer $xLimit       		[description]
	 * @param  string  $xCampo       		[description]
	 * @param  string  $xLimteInf    		[description]
	 * @param  string  $xLimteSup    		[description]
	 * @param  string  $xOrderBy     		[description]
	 * @param  string  $xOrderType   		[description]
	 * @param  string  $xCampoValor  		[description] El campo por el cual se busca e.g stock, precio, orden, etc. El valor deseado del campo indicado en $xCampoValor
	 * @param  string  $xValorCampo  		[description]
	 * @return array                 		[description]
	 */
	public function getProductosConFiltroRango($xIdCatalogo = null, $xIdCategoria = 0, $xEtiquetas = null, $xOffSet = 0, $xLimit = 12, $xCampo = '', $xLimteInf = '', $xLimteSup = '', $xOrderBy = '', $xOrderType = 'asc', $xCampoValor = '', $xValorCampo = '') {
		try {
			if (is_numeric($xIdCatalogo) && is_numeric($xIdCategoria)) {

				$prioridad = (is_numeric($xIdCategoria) && $xIdCategoria > 0) ? 2 : 1;
				$xUrl = config('main.URL_API')
				. "Catalogo?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdCategoria=" . $xIdCategoria
				. "&vEtiquetas=" . $this->getEtiquetasUrl($xEtiquetas, $xIdCatalogo)
					. "&xPrioridad=" . $prioridad
					. "&xCampo=" . $xCampo
					. "&xDesde=" . $xLimteInf
					. "&xHasta=" . $xLimteSup
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . $xCampoValor
					. "&xValorCampo=" . $xValorCampo;

				$this->getCatalogo($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * @param  [type]  $xEtiquetas  [description]
	 * @param  integer $xIdCatalogo [description]
	 * @return [type]               [description]
	 */
	private function getEtiquetasUrl($xEtiquetas = null, $xIdCatalogo = 0) {
		try {
			$oEtiqueta = new Etiqueta();
			$vEtiquetas = $oEtiqueta->getAll($xIdCatalogo);
			$vIdEtiquetas = array();
			if (is_array($vEtiquetas)) {

				$xEtiquetas = explode('_', $xEtiquetas);
				foreach ($vEtiquetas as $oEtiqueta) {
					if ($oEtiqueta->etiquetas) {
						foreach ($oEtiqueta->etiquetas as $oEtiquetaH) {
							$vNombres = explode('@', $oEtiquetaH->nombre);
							if (in_array(
								Caracteres::convertToUrl($vNombres[0]),
								$xEtiquetas)) {
								$vIdEtiquetas[] = $oEtiquetaH->idEtiqueta;
							}

						}
					}
				}
			}
			return implode(',', $vIdEtiquetas);
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * @param  [type] $xUrl [description]
	 * @return [type]       [description]
	 */
	private function getCatalogo($xUrl) {

		try {
			if (is_string($xUrl)) {

				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_PRODUCTO'));

				if (isset($vDataAPI->data->catalogos)) {
					$this->setCatalogo($vDataAPI->data->catalogos);
				}

			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * @param [type] $vCatalogosAPI [description]
	 */
	private function setCatalogo($vCatalogosAPI = null) {

		try {
			if (is_array($vCatalogosAPI)) {

				$this->etiquetas = isset($vCatalogosAPI[0]->etiquetas)
				? Etiqueta::cast($vCatalogosAPI[0]->etiquetas)
				: array();
				$this->categorias = isset($vCatalogosAPI[0]->categorias)
				? Categoria::cast($vCatalogosAPI[0]->categorias, true)
				: array();
				$this->productos = isset($vCatalogosAPI[0]->productos)
				? Producto::cast($vCatalogosAPI[0]->productos)
				: array();
			}
		} catch (Exception $e) {
			//Log::writeLog($e);
		}
	}
}
?>