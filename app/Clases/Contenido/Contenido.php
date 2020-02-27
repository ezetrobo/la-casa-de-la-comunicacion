<?php
namespace App\Clases\Contenido;

use App\Clases\Common\Archivo;
use App\Clases\Common\Imagen;
use App\Clases\Common\Paginador;
use App\Clases\Common\Video;
use App\Sawubona\Sawubona;

/**
 *  [printImagenes description]  -> Imprime las imagenes del producto.
 *  [getAll description]  -> Devuelve Contenidos por Tipo de contenido.
 *  [getAllDestacados description]  -> Devuelve Contenidos destacado.
 *  [getAllNoDestacados description]  -> Devuelve Contenidos NO destacado.
 *  [getRelacionados description]  -> Devuelve Contenidos relacionado a un contenido.
 *  [getById description]  -> Devuelve Contenidos por ID contenido.
 *  [getByFiltro description]  -> 
 *  [getContenidosByPerfil description]  -> Devuelve Contenidos por ID contenido.
 *  [getContenidosByPerfilAndFiltro description]  -> 
 *  [getContenidosPublic description static]  -> Solo para accederlo desde otra clase (Tag, AreaPrivada).
 *  [getContenidos description private]  -> Devuelve un Vector de Contenido.
 *  [setContenido description private]  -> Convierte un ContenidoAPI en Contenido.
*/


class Contenido {
	public $idContenido;
	public $titulo;
	public $descripcion;
	public $descripcionCorta;
	public $primerPalabraDescripcion;
	public $detalle;
	public $imagenes; //	Vector de Imagen
	public $fechaAlta;
	public $orden;
	public $bajada;
	public $videos; //	Vector de Video
	public $portada;
	public $link;
	public $activacion;
	public $baja;
	public $precio;
	public $fuente;
	public $subtitulo;
	public $tags;
	public $archivos; //	Vector de Archivo
	public $paginador; // 	Objeto Paginador
	public $productos;
	public $linkURL;

	public function __construct() {
		try {
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$this->idContenido = null;
			$this->titulo = null;
			$this->descripcion = null;
			$this->detalle = null;
			$this->imagenes = array();
			$this->fechaAlta = null;
			$this->orden = 9999;
			$this->bajada = null;
			$this->videos = array();
			$this->portada = null;
			$this->link = null;
			$this->activacion = null;
			$this->baja = null;
			$this->precio = null;
			$this->fuente = null;
			$this->subtitulo = null;
			$this->tags = null;
			$this->archivos = null;
			$this->paginador = new Paginador();
			$this->productos = null;
			$this->linkURL = null;
		} catch (Exception $e) {
			Log::error($e);
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
	 * @return [type]              [description]
	 */
	public function printImagenes($xLimit = 1, $xClass = '', $xContainer = false, $xCondition = null, $xBool = true, $xDefault = 'contenido.jpg') {

		try {
			$xOffSet = 0;
			$xCount = count($this->imagenes);
			$xLimit = (is_numeric($xLimit)) ? $xLimit : null;
			$xCondition = (is_string($xCondition)) ? $xCondition : '';
			$xBool = (is_bool($xBool)) ? $xBool : true;

			if (is_array($this->imagenes) && $xCount > 0 && $this->imagenes[0]->default == '') {
				for ($i = 0; $i < $xCount; $i++) {
					if ($this->imagenes[$i]) {
						if ($xCondition != '') {
							if ($xBool && $this->imagenes[$i]->nombre == $xCondition) {
								$this->imagenes[$i]->printImagen($xClass, $xContainer, $this->titulo);
								$xOffSet++;
							} else if (!$xBool && $this->imagenes[$i]->nombre != $xCondition) {
								$this->imagenes[$i]->printImagen($xClass, $xContainer, $this->titulo);
								$xOffSet++;
							}
						} else {
							$this->imagenes[$i]->printImagen($xClass, $xContainer, $this->titulo);
							$xOffSet++;
						}

						if (is_numeric($xLimit) && $xOffSet > $xLimit) {
							break;
						}
					}
				}
			} else {
				$oImagen = new Imagen(0, $this->titulo, '../images/default/contenido.jpg');
				$oImagen->printImagen($xClass, $xContainer, $this->titulo);
				unset($oImagen);
			}

			unset($xCount, $xLimit, $xCondition, $xBool, $xOffSet);

		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getAll description] Devuelve Contenidos por Tipo de contenido
	 * @param  integer $xIdTipoContenido [description]
	 * @param  integer $xOffSet          [description]
	 * @param  integer $xLimit           [description]
	 * @param  string  $xOrderBy         [description]
	 * @param  string  $xOrderType       [description]
	 * @return array                     [description]
	 */
	public function getAll($xIdTipoContenido = null, $xOffSet = 0, $xLimit = 5, $xOrderBy = 'idCont', $xOrderType = 'DESC') {
		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xOffSet) && is_numeric($xLimit) && is_string($xOrderBy) && is_string($xOrderType)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xIdTipoContenido=" . $xIdTipoContenido
				. "&xCampo=" . $xOrderBy
				. "&xOrden=" . $xOrderType
				. "&xOpcion=";
				unset($xIdTipoContenido, $xOffSet, $xLimit, $xOrderBy, $xOrderType);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getAllDestacados description] Devuelve Contenidos destacado
	 * @param  integer  $xIdTipoContenido 	[description]
	 * @param  integer $xOffSet          	[description]
	 * @param  integer $xLimit           	[description]
	 * @return array                    	[description]
	 */
	public function getAllDestacados($xIdTipoContenido = null, $xOffSet = 0, $xLimit = 5) {
		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xOffSet) && is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdTipoContenido
					. "&xCampo=" . "Portada"
					. "&xValor=" . "S";

				unset($xIdTipoContenido, $xOffSet, $xLimit);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getAllNoDestacados description] Devuelve Contenidos NO destacado
	 * @param  integer  $xIdTipoContenido 	[description]
	 * @param  integer $xOffSet          	[description]
	 * @param  integer $xLimit           	[description]
	 * @return array                    	[description]
	 */
	public function getAllNoDestacados($xIdTipoContenido = null, $xOffSet = 0, $xLimit = 5) {

		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xOffSet) && is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdTipoContenido
					. "&xCampo=" . "Portada"
					. "&xValor=" . "N";

				unset($xIdTipoContenido, $xOffSet, $xLimit);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getRelacionados description] Devuelve Contenidos relacionados a un Contenido
	 * @param  integer  $xIdTipoContenido 	[description]
	 * @param  integer  $xIdContenido     	[description]
	 * @param  integer $xOffSet          	[description]
	 * @param  integer $xLimit           	[description]
	 * @return array                    	[description]
	 */
	public function getRelacionados($xIdTipoContenido = null, $xIdContenido = null, $xOffSet = 0, $xLimit = 5) {
		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xIdContenido) && is_numeric($xOffSet) && is_numeric($xLimit)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdTipoContenido
					. "&xIdContenido=" . $xIdContenido
					. "&xCampoValor=" . ""
					. "&xValorCampo=" . "";

				unset($xIdTipoContenido, $xIdContenido, $xOffSet, $xLimit);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getById description] Devuelve Contenido por idContenido
	 * @param  integer $xIdTipoContenido 	[description]
	 * @param  integer $xIdContenido     	[description]
	 * @return array                   		[description]
	 */
	public function getById($xIdTipoContenido = null, $xIdContenido = null) {
		try {
			if (is_numeric($xIdTipoContenido) && is_numeric($xIdContenido)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdTipoContenido
					. "&xIdContenido=" . $xIdContenido;

				$vContenidos = $this->getContenidos($xUrl);
				$oContenido = isset($vContenidos[0]) ? $vContenidos[0] : null;

				unset($vContenidos, $xUrl, $xIdTipoContenido, $xIdContenido);

				if ($oContenido) {
					//Yii::app()->session['meta-contenido'] = $oContenido;
					return $oContenido;
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getByFiltro description]
	 * @param  [type] $xIdTipoContenido [description]
	 * @param  [type] $xIdContenido     [description]
	 * @return [type]                   [description]
	 */
	public function getByFiltro($xIdTipoContenido = null, $xIdContenido = null) {
		try {
			if (is_numeric($xIdTipoContenido)
				&& is_numeric($xIdContenido)) {

				$xUrl = config('main.URL_API')
				. "Contenidos?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xIdTipoContenido=" . $xIdTipoContenido
					. "&xCampo=" . "idCont"
					. "&xValor=" . $xIdContenido;

				$vContenidos = $this->getContenidos($xUrl);
				$oContenido = isset($vContenidos[0]) ? $vContenidos[0] : null;

				unset($vContenidos, $xUrl, $xIdTipoContenido, $xIdContenido);

				if ($oContenido) {
					//Yii::app()->session['meta-contenido'] = $oContenido;
					return $oContenido;
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getContenidosByPerfil description]
	 * @param  integer  $xIdPerfilCustomValor 	[description]
	 * @param  integer $xOffSet              	[description]
	 * @param  integer $xLimit               	[description]
	 * @return array                        	[description]
	 */
	public function getContenidosByPerfil($xIdPerfilCustomValor = null, $xOffSet = 0, $xLimit = 5) {
		try {
			if (is_numeric($xIdPerfilCustomValor)) {

				$xUrl = config('main.URL_API')
				. "AreaPrivadaContenidos"
				. "?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
					. "&xOffSet=" . $xOffSet
					. "&xLimit=" . $xLimit
					. "&xIdPerfilCustomValor=" . $xIdPerfilCustomValor;

				unset($xIdPerfilCustomValor, $xOffSet, $xLimit);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getContenidosByPerfilAndFiltro description]
	 * @param  [type]  $xIdPerfilCustomValor [description]
	 * @param  integer $xOffSet              [description]
	 * @param  integer $xLimit               [description]
	 * @param  string  $vCampoValor          [description]
	 * @param  string  $xValorCampo          [description]
	 * @return [type]                        [description]
	 */
	public function getContenidosByPerfilAndFiltro($xIdPerfilCustomValor = null, $xOffSet = 0, $xLimit = 5, $vCampoValor = '', $xValorCampo = '') {
		try {
			if (is_numeric($xIdPerfilCustomValor)) {
				$xUrl = config('main.URL_API')
				. "AreaPrivadaContenidos"
				. "?xKey=" . config('main.FIDELITY_KEY')
				. "&xIdSitio=" . config('main.ID_SITIO')
				. "&xOffSet=" . $xOffSet
				. "&xLimit=" . $xLimit
				. "&xIdPerfilCustomValor=" . $xIdPerfilCustomValor
				. "&xCampoValor=" . urlencode($vCampoValor)
				. "&xValorCampo=" . urlencode(strtolower($xValorCampo));

				unset($xIdPerfilCustomValor, $xOffSet, $xLimit, $vCampoValor, $xValorCampo);

				return $this->getContenidos($xUrl);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getContenidosPublic description] Solo para accederlo desde otra clase (Tag, AreaPrivada)
	 * @param  [type] $xUrl [description]
	 * @return [type]       [description]
	 */
	public static function getContenidosPublic($xUrl = null) {
		try {
			$oContenido = new Contenido();
		    return $oContenido->getContenidos($xUrl);
		} catch (Exception $e) {
			Log::error($e);
		}
	}


	public function convertToUrl($xString = null){
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
			Log::error($e);
		}
	}


	//----------------------------------------------------------------------------------------------------//
	//	Funciones privadas a la Clase 	//----------------------------------------------------------------------------------------------------//

	/**
	 * Devuelve un Vector de Contenido
	 * @param  String $xUrl [description]
	 * @return Contenido    [description]
	 */
	protected function getContenidos($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_CONTENIDO'));
				if (isset($vDataAPI->data->contenidos)) {
					return $this->setContenidos($vDataAPI->data->contenidos);
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * Convierte un Vector de ContenidoAPI en Vector de Contenido
	 * @param array $vContenidosAPI [description]
	 */
	private function setContenidos($vContenidosAPI = null) {
		try {
			if (is_array($vContenidosAPI)) {
				$vContenidos = array();
				foreach ($vContenidosAPI as $oContenidoAPI) {
					$vContenidos[] = $this->setContenido($oContenidoAPI);
				}
				unset($vContenidosAPI);
				return $vContenidos;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [setContenido description] Convierte un ContenidoAPI en Contenido
	 * @param Contenido $oContenidoAPI [description]
	 */
	private function setContenido($oContenidoAPI = null) {
		try {
			if ($oContenidoAPI) {
				$oContenido = new Contenido();

				$oContenido->idContenido = isset($oContenidoAPI->idContenido) ? $oContenidoAPI->idContenido : null;
				$oContenido->idTipoContenido = isset($oContenidoAPI->idTipoContenido) ? $oContenidoAPI->idTipoContenido : null;
				$oContenido->titulo = isset($oContenidoAPI->titulo) ? $oContenidoAPI->titulo : null;
				$oContenido->descripcion = isset($oContenidoAPI->descripcion) ? $oContenidoAPI->descripcion : null;
				$oContenido->detalle = isset($oContenidoAPI->detalle) ? $oContenidoAPI->detalle : null;
				$oContenido->orden = isset($oContenidoAPI->orden) ? $oContenidoAPI->orden : null;
				$oContenido->bajada = isset($oContenidoAPI->bajada) ? $oContenidoAPI->bajada : null;
				$oContenido->portada = (isset($oContenidoAPI->portada) && $oContenidoAPI->portada == 'S ') ? true : false;
				$oContenido->precio = isset($oContenidoAPI->precio) ? $oContenidoAPI->precio : null;
				$oContenido->fuente = isset($oContenidoAPI->fuente) ? $oContenidoAPI->fuente : null;
				$oContenido->subtitulo = isset($oContenidoAPI->subtitulo) ? $oContenidoAPI->subtitulo : null;
				$oContenido->tags = isset($oContenidoAPI->tags) ? $oContenidoAPI->tags : null;
				$oContenido->link = isset($oContenidoAPI->link) ? $oContenidoAPI->link : null;

				$oContenido->primerPalabraDescripcion = '';
				if (preg_match('/(?<=>)((\w| )+)(?=<)|^ *\w+(?=<)*/',
					$oContenido->descripcion,
					$oContenido->primerPalabraDescripcion)) {

					$oContenido->primerPalabraDescripcion = $oContenido->primerPalabraDescripcion[0];
				} else {
					$oContenido->primerPalabraDescripcion = '';
				}

				//	Imagenes
				if (isset($oContenidoAPI->imagenes) && is_array($oContenidoAPI->imagenes)) {

					foreach ($oContenidoAPI->imagenes as $oImagenAPI) {
						if (isset($oImagenAPI->idImagen) && isset($oImagenAPI->nombre) && isset($oImagenAPI->path) && isset($oImagenAPI->orden)) {
							$oContenido->imagenes[] = new Imagen($oImagenAPI->idImagen, $oImagenAPI->nombre, $oImagenAPI->path, $oImagenAPI->orden);
						}

					}
				} else {
					$IMG_idImagen = 0;
					$IMG_nombre = 'default_contenido';
					$IMG_path = asset('/default/default.contenido');
					$IMG_orden = 0;
					$IMG_default = TRUE;
					$oContenido->imagenes[0] = new Imagen($IMG_idImagen, $IMG_nombre, $IMG_path, $IMG_orden, $IMG_default
					);
				}
				// Videos
				if (isset($oContenidoAPI->vimeo)) {

					$oContenido->videos[] = new Video('https://www.vimeo.com/' . $oContenidoAPI->vimeo);
				}
				if (isset($oContenidoAPI->youtube)) {

					$oContenido->videos[] = new Video('https://www.youtube.com/watch?v=' . $oContenidoAPI->youtube);
				}
				// Archivos
				if (isset($oContenidoAPI->archivosxContenido)
					&& is_array($oContenidoAPI->archivosxContenido)) {
					foreach ($oContenidoAPI->archivosxContenido as $oArchivoAPI) {
						$oContenido->archivos[] = new Archivo($oArchivoAPI);
					}
				}

				unset($oContenidoAPI);

				return $oContenido;
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Devuelve el link del contenido
	public function getLink($xUrl = null){
		try{
			if(is_string($xUrl)){
				// return request()->getHost().'/'.$xUrl.'/'.$this->idContenido .'/'. Sawubona::convertToUrl($this->titulo);
				return '/'.$xUrl.'/'.$this->idContenido .'/'. Sawubona::convertToUrl($this->titulo);
			}
		}
		catch(Exception $e){
			Log::error($e);
		}
	}

	public function printShared($xLink = null) {
		echo '<ul class="shared">'
		. '<li class="btn-facebook">'
		. '<a href="' . $xLink . '">'
		. '<img src="'.asset('images/icons/facebook.png').'"'
			. '</a>'
			. '</li>';

		echo '<li class="btn-twitter">'
		. '<a href="' . $xLink . '">'
		. '<img src="'.asset('images/icons/twitter.png').'"'
			. '</a>'
			. '</li>'
			. '</ul>';
	}
}
?>