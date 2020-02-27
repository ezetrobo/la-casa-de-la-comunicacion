<?php
namespace App\Clases\Producto;

use App\Clases\Common\Imagen;
use App\Clases\Common\Video;
use App\Clases\Producto\Categoria;
use App\Sawubona\Caracteres;
use App\Sawubona\Sawubona;
use Carbon\Carbon;

//  --------------------------------------------------------------  //
//  Model: Producto
//  Author: Enri Fonseca
//  Version: 2. 0
//  --------------------------------------------------------------  //
class Producto {
	public $idProducto;
	public $titulo;
	//public $fechaAlta;
	public $categoria; //  objeto Categoria
	public $codigoInterno;
	public $descripcion;
	public $descripcionCorta;
	public $destacado;
	public $descuento;
	public $detalle;
	public $direccion;
	public $distincion;
	public $email;
	public $especificacion;
	public $etiquetas;
	public $habilitado;
	public $localidad;
	public $orden;
	public $peso;
	public $ranking;
	public $sitio; //  objeto Sitio
	public $telefono;
	public $volumen;
	public $productos; //  vector de Producto
	public $precios; //  vector de float
	public $stocks; //  vector de int
	public $dropbox; //  vector de string
	public $archivos; //  vector de Archivo
	public $imagenes; //  vector de Imagen
	public $videos; //  vector de Video
	public $paginador; //   objeto Paginador
	public $puntos;
	public $etiquetasxProducto;
	public $combo;
	public $marca;
	public $datosInternos;
	public $servicio;
	public $estadoProducto;
	public $esFavorito;
	public $indexEtiquetasPromocion;
	public $precioDeLista;
	public $precioActivo;
	public $oDescuentoCruzado;

	public function __construct() {
		try
		{
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$this->archivos = NULL;
			$this->categoria = NULL;
			$this->codigoInterno = NULL;
			$this->descripcion = NULL;
			$this->destacado = NULL;
			$this->destacado = NULL;
			$this->detalle = NULL;
			$this->direccion = NULL;
			$this->distincion = NULL;
			$this->dropbox = NULL;
			$this->email = NULL;
			$this->especificacion = NULL;
			$this->etiquetas = NULL;
			//$this->fechaAlta               = NULL;
			$this->habilitado = 1;
			$this->idProducto = NULL;
			$this->imagenes = NULL;
			$this->localidad = NULL;
			$this->orden = NULL;
			$this->precios = NULL;
			$this->paginador = NULL;
			$this->peso = NULL;
			$this->productos = NULL;
			$this->ranking = NULL;
			$this->sitio = NULL;
			$this->stocks = NULL;
			$this->telefono = NULL;
			$this->titulo = NULL;
			$this->videos = NULL;
			$this->volumen = 0;
			$this->puntos = NULL;
			$this->etiquetasxProducto = NULL;
			$this->combo = NULL;
			$this->marca = NULL;
			$this->datosInternos = NULL;
			$this->servicio = NULL;
			$this->estadoProducto = NULL;
			$this->precioActivo = NULL;
			$this->precioUnitarioActivo = NULL;
			$this->esFavorito = FALSE;
			$this->indexEtiquetasPromocion = FALSE;
			$this->precioDeLista = NULL;
			$this->oDescuentoCruzado = NULL;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [printImagenes description] Imprime las imagenes del producto
	 * @param  integer $xLimit     [description]
	 * @param  string  $xClass     [description]
	 * @param  boolean $xContainer [description]
	 * @param  [type]  $xCondition [description]
	 * @param  boolean $xBool      [description]
	 * @param  string  $xDefault   [description]
	 * @return img                 [description]
	 */
	public function printImagenes($xLimit = 1, $xClass = '', $xContainer = false, $xCondition = null, $xBool = true, $xDefault = 'producto.png') {
		try
		{
			$xCount = ($this->imagenes) ? count($this->imagenes) : 0;
			$xLimit = (is_numeric($xLimit)) ? $xLimit : null;
			$xCondition = (is_string($xCondition)) ? $xCondition : '';
			$xBool = (is_bool($xBool)) ? $xBool : true;
			$xOffSet = 0;

			if (is_array($this->imagenes)
				AND $xCount > 0) {
				for ($i = 0; $i < $xCount; $i++) {
					if ($this->imagenes[$i]) {
						if ($xCondition != '') {
							if ($xBool AND $this->imagenes[$i]->nombre == $xCondition) {
								$this->imagenes[$i]->printImagen($xClass, $xContainer, $this->titulo);
								$xOffSet++;
							} elseif (!$xBool AND $this->imagenes[$i]->nombre != $xCondition) {
								$this->imagenes[$i]->printImagen($xClass, $xContainer, $this->titulo);
								$xOffSet++;
							}
						} else {
							$this->imagenes[$i]->printImagen(
								$xClass,
								$xContainer,
								$this->titulo
							);
							$xOffSet++;
						}
						if (is_numeric($xLimit) AND $xOffSet > $xLimit) {
							break;
						}
					}
				}
			} else {
				$oImagen = new Imagen(0, $this->titulo, '/images/default/' . $xDefault);
				$oImagen->printImagen($xClass, $xContainer, $this->titulo);
				unset($oImagen);
			}
			unset($xCount, $xLimit, $xCondition, $xBool, $xOffSet);
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}

	// //  Imprime los botones sociales
	// public function printShared($xUrl = '') {
	// 	try
	// 	{
	// 		if (is_string($xUrl)) {
	// 			//Shared::printShared($this->getLink($xUrl));
	// 		}

	// 	} catch (Exception $e) {
	// 		// Sawubona::writeLog($e);
	// 	}
	// }

	//  Devuelve el link de producto
	public function getLink($xUrl = null, $oCategoriaMadre = null, $oCategoriaAbuela = null) {
		try
		{
			if (!is_string($xUrl)) {
				return NULL;
			}
			$xLink = NULL;
			if (config('main.MULTIDIOMAS')) {
				$xLink = Sawubona::getTranslation($xUrl);
				if (isset($xLink->href)) {
					$xLink = '/' . Sawubona::getIdioma() . '/' . $xLink->href;
				}
			} else {
				$xLink = '/' . $xUrl;
			}
			if ($oCategoriaAbuela) {
				$xLink .= '/' . Caracteres::convertToUrl($oCategoriaAbuela->nombre);
			}
			if ($oCategoriaMadre) {
				$xLink .= '/' . Caracteres::convertToUrl($oCategoriaMadre->nombre);
			}
			if ($this->categoria) {
				$xLink .= '/' . $this->categoria->idCategoria . '/' . Caracteres::convertToUrl($this->categoria->nombre);
			}
			$xLink .= '/' . $this->idProducto . '/' . Caracteres::convertToUrl($this->titulo);

			return $xLink;
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [getNext description] Devuelve el proximo producto de la base relacionado por categoria
	 * @param  integer $xIdCatalogo [description]
	 * @return [type]              	[description]
	 */
	public function getNext($xIdCatalogo = null) {
		try
		{
			if (is_numeric($xIdCatalogo) AND $this->categoria) {
				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xIdProducto=" . $this->idProducto
				. "&xIdCategoria=" . $this->categoria->idCategoria;

				$vProductos = $this->getProductos($xUrl);

				if (is_array($vProductos) AND !empty($vProductos)) {
					$oProducto = $vProductos[0];

					unset($xIdCatalogo, $xUrl, $vProductos);

					return $oProducto;
				}
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}

	//  Devuelve todos los productos de un catalogo
	/**
	 * [getAll description] Devuelve todos los productos de un catalogo
	 * @param  integer  $xIdCatalogo 	[description]
	 * @param  integer 	$xOffSet     	[description]
	 * @param  integer 	$xLimit      	[description]
	 * @param  string  	$xOrderBy    	[description]
	 * @param  string  	$xOrderType  	[description]
	 * @param  boolean 	$xGetChild   	[description]
	 * @return Producto             	[description]
	 */
	public function getAll($xIdCatalogo = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try
		{
			if (is_numeric($xIdCatalogo) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {
				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . "0"
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . ""
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;

				unset($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);
				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getDestacados description] Devuelve todos los productos Destacados de un catalogo
	 * @param  [type]  $xIdCatalogo [description]
	 * @param  integer $xOffSet     [description]
	 * @param  integer $xLimit      [description]
	 * @param  string  $xOrderBy    [description]
	 * @param  string  $xOrderType  [description]
	 * @param  boolean $xGetChild   [description]
	 * @return [type]               [description]
	 */
	public function getDestacados($xIdCatalogo = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try
		{
			if (is_numeric($xIdCatalogo) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . "0"
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . "TRUE"
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;

				unset($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getNoDestacados description] Devuelve todos los productos NO Destacados de un catalogo
	 * @param  integer  $xIdCatalogo 	[description]
	 * @param  integer 	$xOffSet     	[description]
	 * @param  integer 	$xLimit      	[description]
	 * @param  string  	$xOrderBy    	[description]
	 * @param  string  	$xOrderType  	[description]
	 * @param  boolean 	$xGetChild   	[description]
	 * @return [type]               	[description]
	 */
	public function getNoDestacados($xIdCatalogo = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try
		{
			if (is_numeric($xIdCatalogo) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {
				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . "0"
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . "FALSE"
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;
				unset($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [getByCategoria description] Devuelve todos los productos de una categoria (incluye categorias hijas y nietas)
	 * @param  integer  $xIdCatalogo  	[description]
	 * @param  integer  $xIdCategoria 	[description]
	 * @param  integer 	$xOffSet      	[description]
	 * @param  integer 	$xLimit       	[description]
	 * @param  string  	$xOrderBy     	[description]
	 * @param  string  	$xOrderType   	[description]
	 * @param  boolean 	$xGetChild    	[description]
	 * @return array                	[description]
	 */
	public function getByCategoria($xIdCatalogo = null, $xIdCategoria = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {

		try {
			if (is_numeric($xIdCatalogo) AND is_numeric($xIdCategoria) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . $xIdCategoria
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . ""
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;

				//Sawubona::diePretty($xUrl);

				unset($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getDestacadosByCategoria description] Devuelve los productos destacados de una categoria (incluye categorias hijas y nietas)
	 * @param  integer  $xIdCatalogo  	[description]
	 * @param  Integer  $xIdCategoria 	[description]
	 * @param  integer 	$xOffSet      	[description]
	 * @param  integer 	$xLimit       	[description]
	 * @param  string  	$xOrderBy     	[description]
	 * @param  string  	$xOrderType   	[description]
	 * @param  boolean 	$xGetChild    	[description]
	 * @return array                	[description]
	 */
	public function getDestacadosByCategoria($xIdCatalogo = null, $xIdCategoria = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try {
			if (is_numeric($xIdCatalogo) AND is_numeric($xIdCategoria) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . $xIdCategoria
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . "TRUE"
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;

				unset($xIdCatalogo, $xIdCategoria, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getNoDestacadosByCategoria description] Devuelve los productos NO destacados de una categoria (incluye categorias hijas y nietas)
	 * @param  integer  $xIdCatalogo  	[description]
	 * @param  integer  $xIdCategoria 	[description]
	 * @param  integer 	$xOffSet      	[description]
	 * @param  integer 	$xLimit       	[description]
	 * @param  string  	$xOrderBy     	[description]
	 * @param  string  	$xOrderType   	[description]
	 * @param  boolean 	$xGetChild    	[description]
	 * @return array                	[description]
	 */
	public function getNoDestacadosByCategoria($xIdCatalogo = null, $xIdCategoria = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try {
			if (is_numeric($xIdCatalogo) AND is_numeric($xIdCategoria) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xIdCategoria=" . $xIdCategoria
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrden=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValor=" . ""
					. "&xDestacado=" . "FALSE"
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;

				unset($xIdCatalogo, $xIdCategoria, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [getRelacionados description] Devuelve productos relacionados a un producto
	 * @param  integer  $xIdCatalogo [description]
	 * @param  Producto $oProducto   [description]
	 * @param  integer 	$xOffSet     [description]
	 * @param  integer 	$xLimit      [description]
	 * @param  string  	$xOrderBy    [description]
	 * @param  string  	$xOrderType  [description]
	 * @param  boolean 	$xGetChild   [description]
	 * @return array               	 [description]
	 */
	public function getRelacionados($xIdCatalogo = null, $oProducto = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = '', $xOrderType = 'DESC', $xGetChild = false) {
		try {
			if (is_numeric($xIdCatalogo) AND $oProducto AND $oProducto->categoria AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xOrderBy) AND is_string($xOrderType) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xIdProducto=" . $oProducto->idProducto
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdCategoria=" . $oProducto->categoria->idCategoria
					. "&xCampoOrden=" . $xOrderBy
					. "&xOrdenCampo=" . $xOrderType
					. "&xCampoValor=" . ""
					. "&xValorCampo=" . ""
					. "&xDestacado=" . ""
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;
				unset($xIdCatalogo, $oProducto, $xOffSet, $xLimit, $xOrderBy, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [getRelacionadosByTag description] Devuelve productos relacionados a un producto por el campo etiquetas
	 * @param  integer  	$xIdCatalogo [description]
	 * @param  Producto 	$oProducto   [description]
	 * @param  integer 		$xOffSet     [description]
	 * @param  integer 		$xLimit      [description]
	 * @return array        		     [description]
	 */
	public function getRelacionadosByTag($xIdCatalogo = null, $oProducto = null, $xOffSet = 0, $xLimit = 5) {
		try {
			if (is_numeric($xIdCatalogo) AND $oProducto AND is_numeric($xOffSet) AND is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdProducto=" . $oProducto->idProducto//excluye este producto de la lista devuelta
				 . "&xEtiquetas=" . urlencode($oProducto->etiquetas);

				unset($xIdCatalogo, $oProducto, $xOffSet, $xLimit);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [getProductosAutocomplete description] Devuelve todo el listado de productos de un catalogo para el autocomplete
	 * @param  integer $xIdCatalogo [description]
	 * @return array              	[description]
	 */
	public function getProductosAutocomplete($xIdCatalogo = null) {
		try {
			if (is_numeric($xIdCatalogo)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo;
				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Log::writeLog($e);
		}
	}
	/**
	 * [getById description] Devuelve un producto por idProducto
	 * @param  integer  $xIdProducto [description]
	 * @param  boolean 	$xOrderChild [description]
	 * @return array               	 [description]
	 */
	public function getById($xIdProducto = null, $xOrderChild = false) {
		try {
			if (is_numeric($xIdProducto)) {
				$xUrl = config('main.URL_API')
				. "Productos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . "0"
				. "&xLimit=" . "1"
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdProducto=" . $xIdProducto;
				
				$vProductos = $this->getProductos($xUrl);
				if (is_array($vProductos) AND !empty($vProductos)) {
					$oProducto = $vProductos[0];
					//Yii::app()->session['meta-producto'] = $oProducto;
					if ($xOrderChild === true) {
						$vProductos = array();
						$xCount = count($oProducto->productos);
						for ($i = 0; $i < $xCount; $i++) {
							if ($oProducto->productos[$i]) {
								$vProductos[$oProducto->productos[$i]->orden] = $oProducto->productos[$i];
							}
						}
						ksort($vProductos);
						$oProducto->productos = $vProductos;
					}

					unset($xIdProducto, $vProductos, $xOrderChild, $xCount);

					return $oProducto;
				}
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [search description] Retorna un listado de productos de acuerdo a un patron de busqueda
	 * @param  integer $xIdCatalogo [description]
	 * @param  string  $xSearch     [description]
	 * @param  integer $xOffSet     [description]
	 * @param  integer $xLimit      [description]
	 * @param  string  $xOrderBy    [description]
	 * @param  string  $xOrderType  [description]
	 * @param  boolean $xGetChild   [description]
	 * @param  string  $xDestacado  [description]
	 * @return array                [description]
	 */
	public function search($xIdCatalogo = null, $xSearch = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = 'orden', $xOrderType = 'asc', $xGetChild = false, $xDestacado = '') {
		try {
			if (is_numeric($xIdCatalogo) AND is_string($xSearch) AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_bool($xGetChild)) {
				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xIdCategoria=" . "0"
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xCampoOrden=" . $xOrderBy
				. "&xOrden=" . $xOrderType
				. "&xCampoValor=" . ""
				. "&xValor=" . ""
				. "&xDestacado=" . $xDestacado
				. "&xBusqueda=" . urlencode($xSearch)
					. "&xHijos=" . $xGetChild;

				unset($xIdCatalogo, $xSearch, $xOffSet, $xLimit, $xGetChild
				);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [searchByCampo description] Retorna un listado de productos de acuerdo a un patron de busqueda por un campo especifico
	 * @param  imteger $xIdCatalogo [description]
	 * @param  string  $xSearch     [description]
	 * @param  string  $xSearchBy   [description]
	 * @param  integer $xOffSet     [description]
	 * @param  integer $xLimit      [description]
	 * @param  boolean $xGetChild   [description]
	 * @param  string  $xCampoOrden [description]
	 * @param  string  $xOrden      [description]
	 * @param  string  $xDestacado  [description]
	 * @return array                [description]
	 */
	public function searchByCampo($xIdCatalogo = null, $xSearch = null, $xSearchBy = null, $xOffSet = 0, $xLimit = 5, $xGetChild = false, $xCampoOrden = null, $xOrden = null, $xDestacado = '') {
		try {
			if (is_numeric($xIdCatalogo) AND is_string($xSearch) AND str_replace(" ", "", $xSearch) != "" AND is_numeric($xOffSet) AND is_numeric($xLimit) AND is_string($xSearchBy) AND is_bool($xGetChild)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xIdCategoria=" . "0"
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xCampoOrden=" . $xCampoOrden
				. "&xOrden=" . $xOrden
				. "&xCampoValor=" . urlencode($xSearchBy)
				. "&xValor=" . urlencode($xSearch)
					. "&xDestacado=" . $xDestacado
					. "&xBusqueda=" . ""
					. "&xHijos=" . $xGetChild;
				unset($xIdCatalogo, $xSearch, $xOffSet, $xLimit, $xSearchBy, $xGetChild);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [cast description] Conviente un Vector de ProductoAPI en Vector de Producto
	 * @param  array $vProductosAPI [description]
	 * @return array                [description]
	 */
	public static function cast($vProductosAPI = null) {
		$oProducto = new Producto();
		return $oProducto->setProductos($vProductosAPI);
	}
	/**
	 * [getAllByTag description] Retorna un listado de objetos Producto segun etiquetas tag
	 * @param  integer $xIdCatalogo [description]
	 * @param  integer $xOffSet     [description]
	 * @param  integer $xLimit      [description]
	 * @param  string  $xEtiquetas  [description]
	 * @return array                [description]
	 */
	public function getAllByTag($xIdCatalogo = null, $xOffSet = 0, $xLimit = 5, $xEtiquetas = '') {
		try {
			if (is_numeric($xIdCatalogo) AND is_numeric($xOffSet) AND is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Productos?"
				. "xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdCatalogo
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdProducto=" . '0'
				. "&xEtiquetas=" . urldecode($xEtiquetas);

				unset($xIdCatalogo, $xOffSet, $xLimit
				);

				return $this->getProductos($xUrl);
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}

	}

	//  ---------------------------------------------------------------    //
	//  Funciones privadas
	//  ---------------------------------------------------------------    //
	/**
	 * [getProductos description]
	 * @param  string $xUrl [description]
	 * @return array       [description]
	 */
	protected function getProductos($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_PRODUCTO'));
				$vDataAPI = $vDataAPI;
				if ($vDataAPI->data != null) {
					$vDataAPI = $vDataAPI->data->productos;
				}
				return $this->setProductos($vDataAPI);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [setProductos description] Conviente un Vector de ProductoAPI en Vector de Producto
	 * @param array $vProductosAPI [description]
	 */
	protected function setProductos($vProductosAPI = null) {
		try
		{
			if (is_array($vProductosAPI) AND !empty($vProductosAPI)) {
				$vProductos = array();
				foreach ($vProductosAPI as $oProductoAPI) {
					$oProducto = $this->setProducto($oProductoAPI);
					if (!($oProducto)) {
						continue;
					}
					$vProductos[] = $oProducto;
				}
				unset($vProductosAPI);
				return $vProductos;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
	/**
	 * [CalcularPorcentajeAplicado description] Calcular qué porcentaje del total es una cantidad
	 * @param Producto $oProducto [description]
	 */
	public function CalcularPorcentajeAplicado($oProducto) {
		//Ejemplo: Descuento = 100 - ((precioActivo * 100) / preciodeLista )
		return $oProducto->descuento = 100 - (($oProducto->precioActivo * 100) / $oProducto->precioDeLista);

	}
	/**
	 * [setProducto description] Convierte un Objeto ProductoAPI en Producto
	 * @param array $oProductoAPI [description]
	 */
	public function setProducto($oProductoAPI = null) {
		try
		{
			$oProducto = new Producto();
			//  PRIMERO revisamos que haya stock, si no, no hace falta seguir seteando el objecto
			if (isset($oProductoAPI->stock1)) {
				$oProducto->stocks[0] = $oProductoAPI->stock1;
			} elseif (isset($oProductoAPI->stock2)) {
				$oProducto->stocks[1] = $oProductoAPI->stock2;
			}
			if ($oProducto->stocks[0] < $oProducto->volumen) {
				return;
			}
			$arrProductosFavoritos = [];
			isset($oProductoAPI->idProducto) AND !empty($oProductoAPI->idProducto) ? $oProducto->idProducto = $oProductoAPI->idProducto : null;
			isset($oProductoAPI->titulo) AND !empty($oProductoAPI->titulo) ? $oProducto->titulo = $oProductoAPI->titulo : null;
			isset($oProductoAPI->descripcion) AND !empty($oProductoAPI->descripcion) ? $oProducto->descripcion = $oProductoAPI->descripcion : null;
			isset($oProductoAPI->habilitado) AND !empty($oProductoAPI->habilitado) ? $oProducto->habilitado = $oProductoAPI->habilitado : null;
			isset($oProductoAPI->ranking) AND !empty($oProductoAPI->ranking) ? $oProducto->ranking = $oProductoAPI->ranking : null;
			isset($oProductoAPI->destacado) AND !empty($oProductoAPI->destacado) ? $oProducto->destacado = $oProductoAPI->destacado : null;
			isset($oProductoAPI->descuento) AND !empty($oProductoAPI->descuento) ? $oProducto->descuento = $oProductoAPI->descuento : null;
			isset($oProductoAPI->orden) AND !empty($oProductoAPI->orden) ? $oProducto->orden = $oProductoAPI->orden : null;
			isset($oProductoAPI->etiquetas) AND !empty($oProductoAPI->etiquetas) ? $oProducto->etiquetas = $oProductoAPI->etiquetas : null;
			isset($oProductoAPI->especificacion) AND !empty($oProductoAPI->especificacion) ? $oProducto->especificacion = $oProductoAPI->especificacion : null;
			isset($oProductoAPI->detalle) AND !empty($oProductoAPI->detalle) ? $oProducto->detalle = $oProductoAPI->detalle : null;
			isset($oProductoAPI->direccion) AND !empty($oProductoAPI->direccion) ? $oProducto->direccion = $oProductoAPI->direccion : null;
			isset($oProductoAPI->localidad) AND !empty($oProductoAPI->localidad) ? $oProducto->localidad = $oProductoAPI->localidad : null;
			isset($oProductoAPI->telefono) AND !empty($oProductoAPI->telefono) ? $oProducto->telefono = $oProductoAPI->telefono : null;
			isset($oProductoAPI->email) AND !empty($oProductoAPI->email) ? $oProducto->email = $oProductoAPI->email : null;
			isset($oProductoAPI->peso) AND !empty($oProductoAPI->peso) ? $oProducto->peso = $oProductoAPI->peso : null;
			isset($oProductoAPI->codigoInterno) AND !empty($oProductoAPI->codigoInterno) ? $oProducto->codigoInterno = $oProductoAPI->codigoInterno : null;
			isset($oProductoAPI->distincion) AND !empty($oProductoAPI->distincion) ? $oProducto->distincion = $oProductoAPI->distincion : null;
			isset($oProductoAPI->fechaAlta) AND !empty($oProductoAPI->fechaAlta) ? $oProducto->fechaAlta = new Carbon($oProductoAPI->fechaAlta) : new Carbon();
			isset($oProductoAPI->puntos) AND !empty($oProductoAPI->puntos) ? $oProducto->puntos = $oProductoAPI->puntos : null;
			isset($oProductoAPI->combo) AND !empty($oProductoAPI->combo) ? $oProducto->combo = $oProductoAPI->combo : null;
			isset($oProductoAPI->marca) AND !empty($oProductoAPI->marca) ? $oProducto->marca = $oProductoAPI->marca : null;
			isset($oProductoAPI->datosInternos) AND !empty($oProductoAPI->datosInternos) ? $oProducto->datosInternos = $oProductoAPI->datosInternos : null;
			isset($oProductoAPI->servicio) AND !empty($oProductoAPI->servicio) ? $oProducto->servicio = $oProductoAPI->servicio : null;
			isset($oProductoAPI->estadoProducto) AND !empty($oProductoAPI->estadoProducto) ? $oProducto->estadoProducto = $oProductoAPI->estadoProducto : null;
			isset($oProductoAPI->volumen) AND !empty($oProductoAPI->volumen) ? $oProducto->volumen = $oProductoAPI->volumen : 0;

			// Video
			if (isset($oProductoAPI->video)) {
				$vVideosAPI = explode(',', $oProductoAPI->video);
				foreach ($vVideosAPI as $oVideoAPI) {
					$oProducto->videos[] = new Video(trim($oVideoAPI));
				}
			}
			//  Precios
			isset($oProductoAPI->precio) ? $oProducto->precios[] = $oProductoAPI->precio : null;
			isset($oProductoAPI->precio2) ? $oProducto->precios[] = $oProductoAPI->precio2 : null;
			isset($oProductoAPI->precio3) ? $oProducto->precios[] = $oProductoAPI->precio3 : null;
			$oProducto->precioDeLista = $oProducto->precios[0] ?: $oProducto->precios[1] ?: $oProducto->precios[2] ?: NULL;
			//  Dropbox
			isset($oProductoAPI->dropBox1) AND !empty($oProductoAPI->dropBox1) ? $oProducto->dropbox[] = $oProductoAPI->dropBox1 : null;
			isset($oProductoAPI->dropBox2) AND !empty($oProductoAPI->dropBox2) ? $oProducto->dropbox[] = $oProductoAPI->dropBox2 : null;
			isset($oProductoAPI->dropBox3) AND !empty($oProductoAPI->dropBox3) ? $oProducto->dropbox[] = $oProductoAPI->dropBox3 : null;
			//  Categorias
			if (!isset($oProductoAPI->categoria)) {
				$oProducto->categoria = NULL;
				$oProducto->jerarquiaCategorias = [0, 0, 0];
			} else {
				$oProducto->categoria = new Categoria($oProductoAPI->categoria);
				$cat = &$oProducto->categoria;
				$oProducto->jerarquiaCategorias =
					[
					isset($cat->idCategoria) ? $cat->idCategoria : 0,
					isset($cat->madre->idCategoria) ? $cat->madre->idCategoria : 0,
					isset($cat->madre->madre->idCategoria) ? $cat->madre->madre->idCategoria : 0,
				];
				unset($cat);
			}
			//  Imagenes
			if (isset($oProductoAPI->imagenesProducto)) {
				foreach ($oProductoAPI->imagenesProducto as $oImagenAPI) {
					$oProducto->imagenes[] =
					new Imagen($oImagenAPI->imagen->idImagen, $oImagenAPI->imagen->pieImagen, $oImagenAPI->path
					);
				}
			} else {
				$IMG_idImagen = 0;
				$IMG_nombre = 'producto';
				$IMG_path = '/images/default/producto.jpg';
				$IMG_orden = 0;
				$IMG_default = true;
				$oProducto->imagenes[] =
				new Imagen($IMG_idImagen, $IMG_nombre, $IMG_path, $IMG_orden, $IMG_default
				);
			}
			// Archivos
			if (isset($oProductoAPI->archivosProducto)) {
				foreach ($oProductoAPI->archivosProducto as $oArchivoAPI) {
					$oProducto->archivos[] = new Archivo($oArchivoAPI);
				}
			}
			//  Etiquetas por producto
			if (isset($oProductoAPI->etiquetasxProducto)) {
				foreach ($oProductoAPI->etiquetasxProducto as $index => $oEtiquetaxProductoAPI) {
					$oProducto->etiquetasxProducto[] = $oEtiquetaxProductoAPI;
				}
				// if ($oEtiquetaxProductoAPI->id == $idCategoriaEtiquetasPromocion) {
				// 	$oProducto->indexEtiquetasPromocion = $index;
				// }

			}
			// Favorito
			// $favoritosArr = is_null(session('productosFavoritos')) OR empty(session('productosFavoritos')) ? [] : session('productosFavoritos');
			// if (in_array($oProducto->idProducto, $favoritosArr)) {
			// 	$oProducto->esFavorito = true;
			// }

			unset($oProductoAPI);

			return $oProducto;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [descripcionCorta description] Crea una descripcon corta del producto
	 * @param  Producto $oProductos [description]
	 * @return array                [description]
	 */
	public static function descripcionCorta($oProductos) {
		if ($oProductos) {
			foreach ($oProductos as $producto) {
				if (!empty($producto->descripcion)) {
					$producto->descripcionCorta = Caracteres::cutWord($producto->descripcion);
				}
			}
			return $oProductos;
		}
	}
}
?>