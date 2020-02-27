<?php
namespace App\Clases\Common;

class Sitio{
	public $idSitio;

	public function __construct($xIdSitio = null){
		try {
			$this->idSitio = $xIdSitio;
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>