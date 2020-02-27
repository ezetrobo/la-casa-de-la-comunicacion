<?php
namespace App\Clases\Common;

use App\Clases\Common\Imagen;

/**
 *  [printImagenes description]  -> Imprime las imagenes del video.
 *  [printVideo description]  -> Imprime un iframe con el video.
 *  [setIdVideo description private]  -> 
 *  [setImagenes description private]  -> 
*/

class Video {
	public $idVideo;
	public $type;
	public $link;
	public $imagenes;

	public function __construct($xLink = null, $xGetImagenes = false) {
		try {
			$this->link = is_string($xLink) ? $xLink : '';
			$this->type = (strpos($xLink, 'youtube.') > -1) ? 'youtube' : ((strpos($xLink, 'vimeo.') > -1) ? 'vimeo' : null);
			$this->setIdVideo();
			$this->imagenes = array();

			if ($xGetImagenes === true) {
				$this->setImagenes();
			}

		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [printImagenes description] Imprime las imagenes del video
	 * @param  integer $xLimit     [description]
	 * @param  string  $xClass     [description]
	 * @param  boolean $xContainer [description]
	 * @return Imagen              [description]
	 */
	public function printImagenes($xLimit = null, $xClass = '', $xContainer = false) {
		try {
			$xCount = count($this->imagenes);
			if ($xCount == 0) {
				$this->setImagenes();
				$xCount = count($this->imagenes);
			}
			$xLimit = (is_numeric($xLimit)) ? $xLimit : null;
			$xOffset = 0;
			if (is_array($this->imagenes) AND $xCount > 0) {
				for ($i = 0; $i < $xCount; $i++) {
					if ($this->imagenes[$i]) {
						$this->imagenes[$i]->printImagen($xClass, $xContainer, '');
						$xOffset++;
						if (is_numeric($xLimit) AND $xOffset > $xLimit) {
							break;
						}
					}
				}
			} else {
				$oImagen = new Imagen(0, '', '/images/default/contenido.jpg');
				$oImagen->printImagen($xClass, $xContainer, '');
				unset($oImagen);
			}
			unset($xLimit, $xClass, $xContainer, $xCount);
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [printVideo description] Imprime un iframe con el video
	 * @param  integer $xWidth  [description]
	 * @param  integer $xHeight [description]
	 * @return [type]           [description]
	 */
	public function printVideo($xWidth = 640, $xHeight = 480) {
		try {
			if ($this->idVideo != null AND is_numeric($xWidth) AND is_numeric($xHeight)) {
				if ($this->type == 'youtube') {
					echo '<iframe src="https://www.youtube-nocookie.com/embed/' . $this->idVideo . '?rel=0&amp;showinfo=0" frameborder="0" width="' . $xWidth . '" height="' . $xHeight . '"></iframe>';
				} else if ($this->type == 'vimeo') {
					echo '<iframe src="https://player.vimeo.com/video/' . $this->idVideo . '" frameborder="0" width="' . $xWidth . '" height="' . $xHeight . '"></iframe>';
				}

				unset($xWidth, $xHeight);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	---------------------------------------------------------------		//
	//	Funciones protegidas												//
	//	---------------------------------------------------------------		//

	/**
	 * [setIdVideo description]
	 */
	protected function setIdVideo() {
		try {
			if ($this->type == 'youtube') {
				$this->idVideo = str_replace(['http://', 'https://', 'www.', 'youtu.be/', 'youtube.com/watch?v=', 'youtube.com/embed/'], [''], $this->link);
			} else if ($this->type == 'vimeo') {
				if (preg_match('/([0-9])\w+/', $this->link, $ids)) {
					$this->idVideo = $ids[0];
				}

			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [setImagenes description]
	 */
	protected function setImagenes() {
		try {
			$this->imagenes = array();

			if ($this->type == 'youtube' AND $this->idVideo != null) {
				$this->imagenes[] = new Imagen(0, '', 'http://img.youtube.com/vi/' . $this->idVideo . '/0.jpg');
			} else if ($this->type == 'vimeo' AND $this->idVideo != 0 AND @file_get_contents('http://vimeo.com/api/v2/video/' . $this->idVideo . '.json')) {
				$vData = @file_get_contents('http://vimeo.com/api/v2/video/' . $this->idVideo . '.json');
				$vData = json_decode($vData, true);

				if (isset($vData[0]) AND isset($vData[0]['thumbnail_large'])) {
					$this->imagenes[] = new Imagen(0, '', $vData[0]['thumbnail_large']);
					unset($vData);
				}
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>