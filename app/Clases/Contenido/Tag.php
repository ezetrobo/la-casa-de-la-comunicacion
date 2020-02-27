<?php
namespace App\Clases\Contenido;

use App\Sawubona\Caracteres;

/**
 *  [getAll description]  -> Devuelve todos los tag de los contenidos del mismo tipo de contenido para listar como si fueran categorias.
 *  [getContenidos description]  -> Retorna una lista de contenidos que tienen esa tag.
*/

class Tag {
	public $nombre;
	public $value;

	public function __construct($xNombre = null) {
		$this->nombre = $xNombre;
		$this->value = Caracteres::convertToUrl($xNombre);
	}

	/**
	 * [getAll description] Devuelve todos los tag de los contenidos del mismo tipo de contenido para listar como si fueran categorias
	 * @param  integer $xIdTipoContenido 	[description]
	 * @return array                   		[description]
	 */
	public function getAll($xIdTipoContenido = null) {
		try {
			if (is_numeric($xIdTipoContenido)) {
				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido;

				$vContenidos = Contenido::getContenidosPublic($xUrl);
				$xCount = count($vContenidos);
				$vResult = array();

				for ($i = 0; $i < $xCount; $i++) {
					if ($vContenidos[$i] && is_string($vContenidos[$i]->tags)) {
						$vTags = explode(',', $vContenidos[$i]->tags);
						$xCount2 = count($vTags);

						for ($j = 0; $j < $xCount2; $j++) {
							$vResult[] = trim($vTags[$j]);
						}

						unset($vTags, $xCount2);
					}
				}

				unset($xIdTipoContenido, $vContenidos, $xCount, $xUrl);
				sort($vResult);
				$vResult = array_merge(array_unique($vResult));
				$xCount = count($vResult);
				for ($i = 0; $i < $xCount; $i++) {
					$vResult[$i] = new Tag($vResult[$i]);
				}

				unset($xCount);
				return $vResult;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getContenidos description] Retorna una lista de contenidos que tienen esa tag
	 * @param  integer  $xIdTipoContenido 	[description]
	 * @param  string  $xNombreTag       	[description]
	 * @param  integer $xOffSet          	[description]
	 * @param  integer $xLimit           	[description]
	 * @return array                    	[description]
	*/
	public function getContenidos($xIdTipoContenido = null, $xNombreTag = null, $xOffSet = 0, $xLimit = 5) {
		try {
			if (is_numeric($xIdTipoContenido) && is_string($xNombreTag) && is_numeric($xOffSet) && is_numeric($xLimit)) {
				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido
				. "&xCampo=Tags&xValor=" . urlencode(trim($xNombreTag));

				unset($xIdTipoContenido, $xNombreTag, $xOffSet, $xLimit);
				return Contenido::getContenidosPublic($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>