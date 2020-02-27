<?php

namespace App\Clases\Producto;

use App\Clases\Common\Imagen;
use App\Sawubona\Caracteres;
use App\Sawubona\Sawubona;

//	--------------------------------------------------------------	//
//	Model: Categoria
//	Author: Enri Fonseca
//	Version: 2.0
//	--------------------------------------------------------------	//
class Categoria {
	public $idCategoria;
	public $nombre;
	public $nombreEn;
	public $orden;
	public $imagen; //  objeto Imagen
	public $madre; //  objeto Categoria
	public $categorias; //  vector de Categoria

	public function __construct($oCategoriaAPI = null) {
		try {
			$this->idCategoria = isset($oCategoriaAPI->idCategoria) ? $oCategoriaAPI->idCategoria : 0;
			$this->nombre = isset($oCategoriaAPI->nombre) ? $oCategoriaAPI->nombre : '';
			$this->idIdioma = isset($oCategoriaAPI->idIdioma) ? $oCategoriaAPI->idIdioma : null;
			$this->nombreEn = isset($oCategoriaAPI->nombreEn) ? $oCategoriaAPI->nombreEn : null;
			$this->orden = isset($oCategoriaAPI->catOrden) ? $oCategoriaAPI->catOrden : 0;
			$this->madre = isset($oCategoriaAPI->categoriaMadre) ? new Categoria($oCategoriaAPI->categoriaMadre) : null;
			$this->imagen = isset($oCategoriaAPI->imagen)
			? new Imagen(
				$oCategoriaAPI->imagen->idImagen,
				$oCategoriaAPI->imagen->nombre,
				preg_replace('/&size=\d+/', '', $oCategoriaAPI->imagen->path)
			)
			: new Imagen(0, 'default', '/images/default/categoria.jpg', 0, true);

			$this->categorias = array();
			if (isset($oCategoriaAPI->categoriasHijas) && is_array($oCategoriaAPI->categoriasHijas)) {
				foreach ($oCategoriaAPI->categoriasHijas as $oCategoriaAPI) {
					$this->categorias[] = new Categoria($oCategoriaAPI);
				}

			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}

	//	Retorna el link de acceso al listado de productos por categoria
	public function getLink($xUrl = null, $oCategoriaMadre = null, $oCategoriaAbuela = null) {
		try {
			if (is_string($xUrl)) {
				$xLink = '/' . $xUrl . '/' . $this->idCategoria . '/';
				$xLink .= ($oCategoriaAbuela) ? Caracteres::convertToUrl($oCategoriaAbuela->nombre) . '/' : '';
				$xLink .= ($oCategoriaMadre) ? Caracteres::convertToUrl($oCategoriaMadre->nombre) . '/' : '';
				$xLink .= Caracteres::convertToUrl($this->nombre);

				unset($xUrl, $oCategoriaMadre, $oCategoriaAbuela);
				return $xLink;
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getAll description]Devuelve un Vector de Categoria (madres e hijas sin ordenar)
	 * @param  integer   $xIdCatalogo     [description]
	 * @param  boolean   $xOrderByNombre  [description]
	 * @return Categoria                  [description]
	 */
	public function getAll($xIdCatalogo = null, $xOrderByNombre = false) {
		try {
			if (is_numeric($xIdCatalogo)) {
				$xUrl = config('main.URL_API')
				. "Categorias?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xOpcion=1";

				unset($xIdCatalogo);
				return ($xOrderByNombre === true) ? $this->orderByNombre($this->getCategorias($xUrl)) : $this->getCategorias($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getAllOrdenadas description] Devuelve un Vector de Categoria (madres con las hijas ordenadas)
	 * @param  integer  	$xIdCatalogo    [description]
	 * @param  boolean 		$xOrderByNombre [description]
	 * @return Categoria                  	[description]
	 */
	public function getAllOrdenadas($xIdCatalogo = null, $xOrderByNombre = false) {
		try {
			if (is_numeric($xIdCatalogo)) {
				$xUrl = config('main.URL_API')
				. "Categorias?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xOpcion=5";
				unset($xIdCatalogo);
				return ($xOrderByNombre === true) ? $this->orderByNombre($this->getCategorias($xUrl)) : $this->getCategorias($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getMadres description] Devuelve un Vector de Categoria (madres)
	 * @param  integer  	$xIdCatalogo    [description]
	 * @param  boolean 		$xOrderByNombre [description]
	 * @return Categoria                  	[description]
	 */
	public function getMadres($xIdCatalogo = null, $xOrderByNombre = false) {
		try {
			if (is_numeric($xIdCatalogo)) {
				$xUrl = config('main.URL_API')
				. "Categorias?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xOpcion=2";

				unset(
					$xIdCatalogo
				);

				return ($xOrderByNombre === true) ? $this->orderByNombre($this->getCategorias($xUrl)) : $this->getCategorias($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getHijas description] Devuelve un Vector de Categoria (hijas)
	 * @param  Integer  	$xIdCatalogo    [description]
	 * @param  boolean 		$xOrderByNombre [description]
	 * @return Categoria                  	[description]
	 */
	public function getHijas($xIdCatalogo = null, $xOrderByNombre = false) {
		try {
			if (is_numeric($xIdCatalogo)) {
				$xUrl = config('main.URL_API')
				. "Categorias?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdCatalogo
					. "&xOpcion=4";

				unset($xIdCatalogo);
				return ($xOrderByNombre === true) ? $this->orderByNombre($this->getCategorias($xUrl)) : $this->getCategorias($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getById description] Devuelve un Objeto Categoria por idCategoria
	 * @param  integer 		$xIdCategoria 	[description]
	 * @return Categoria               		[description]
	 */
	public function getById($xIdCategoria = null) {
		try {
			if (is_numeric($xIdCategoria)) {
				$xUrl = config('main.URL_API')
				. "Categorias?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdCategoria=" . $xIdCategoria;

				unset($xIdCategoria);
				$vCategorias = $this->getCategorias($xUrl);
				return is_array($vCategorias) ? $vCategorias[0] : null;
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [cast description]
	 * @param  array  		$vCategoriasAPI [description]
	 * @param  boolean 		$xOrderByNombre [description]
	 * @return Categoria                  	[description]
	 */
	public static function cast($vCategoriasAPI = null, $xOrderByNombre = false) {
		$oCategoria = new Categoria();
		$vCategorias = $oCategoria->setCategorias($vCategoriasAPI);
		if (is_bool($xOrderByNombre) && $xOrderByNombre) {
			return $oCategoria->orderByNombre($vCategorias);
		} else {
			return $vCategorias;
		}

	}

	//	---------------------------------------------------------------		//
	//	Funciones privadas
	//	---------------------------------------------------------------		//

	//	Ordena un vector de Categorias por nombre
	private function orderByNombre($vCategorias = null, $xOrden = 'ASC') {
		try {
			if (is_array($vCategorias)) {
				$xCount = count($vCategorias);
				$xOrden = ($xOrden == 'DESC') ? false : true;

				for ($i = 0; $i < $xCount; $i++) {
					$vCategorias[$i]->categorias = $this->orderByNombre($vCategorias[$i]->categorias);
					for ($j = $i + 1; $j < $xCount; $j++) {
						if (($vCategorias[$i]->nombre > $vCategorias[$j]->nombre) == $xOrden) {
							$oCategoria = $vCategorias[$i];
							$vCategorias[$i] = $vCategorias[$j];
							$vCategorias[$j] = $oCategoria;
							unset($oCategoria);
						}
					}
				}

				unset($xCount, $xOrden);
				return $vCategorias;
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}

	//	Devuelve un Vector de Categoria
	private function getCategorias($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_CATEGORIA'));
				//$vDataAPI = json_decode($vDataAPI, false);
				//$this->paginador = new Paginador($vDataAPI->paginador);
				return $this->setCategorias($vDataAPI->data->categorias);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}

	//	Convierte un vector de CategoriaAPI en vector Categoria
	private function setCategorias($vCategoriasAPI = null) {
		try {
			if (is_array($vCategoriasAPI)) {
				$vCategorias = array();
				foreach ($vCategoriasAPI as $oCategoriaAPI) {
					$vCategorias[] = new Categoria($oCategoriaAPI);
				}

				unset($vCategoriasAPI);
				return $vCategorias;
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
}
?>