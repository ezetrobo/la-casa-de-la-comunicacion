<?php
namespace App\Clases\Common;

/**
 *  [urlWithSizeParam description]  -> 
 *  [printImagen description]  -> Imprime la imagen.
*/

class Imagen {
	public $idImagen;
	public $nombre;
	public $path;
	public $orden;
	public $default; //TRUE significa que la imagen viene desde el servidor y no desde fidelity

	public function __construct($xIdImagen = 0, $xNombre = '', $xPath = '', $xOrden = 0, $xDefault = false) {
		try {
			$this->idImagen = is_numeric($xIdImagen) ? $xIdImagen : 0;
			$this->nombre = is_string($xNombre) ? $xNombre : '';
			$this->path = is_string($xPath) ? $xPath : '';
			$this->orden = is_numeric($xOrden) ? $xOrden : 0;
			$this->default = is_bool($xDefault) ? $xDefault : false;
		} catch (Exception $e) {
			log::error($e);
		}
	}

	//
	public function urlWithSizeParam($pxSize) {
		try {
			return $this->default ? $this->path : $this->path . "&size=$pxSize";
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	//	Imprime la imagen
	public function printImagen($xClass = null, $xContainer = false, $xTitle = '') {
		try {
			if (is_string($xClass) && is_string($xTitle)) {
				if ($xContainer === true) {
					echo '<div class="' . $xClass . '">';
				}
				echo '<img  src="' . $this->path . '" alt="' . $this->nombre . '" title="' . $xTitle . '" class="';
				if ($xContainer === false) {
					echo $xClass;
				}
				echo '">';
				if ($xContainer === true) {
					echo '</div>';
				}
				unset($xClass, $xContainer, $xTitle);
			}
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}