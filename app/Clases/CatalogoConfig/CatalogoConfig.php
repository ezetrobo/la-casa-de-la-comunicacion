<?php
namespace App\Clases\CatalogoConfig;

use App\Sawubona\Sawubona;

/**
 *  [getById description]  -> Devuelve un producto por idProducto.
 *  [getConfig description]  -> 
 */

class CatalogoConfig{
	public $idProducto;
	public $configPrecios;			

	public function __construct() {

		try{
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$this->idProducto 		= null;
			$this->configPrecios 	= null;

			$idProductoConfiguracion= config('parametros.idProductoConfiguracion')
			$oProductoConfiguracion = $this->getById( $idProductoConfiguracion );
			
			$this->idProducto 		= $oProductoConfiguracion->idProducto;
			$this->configPrecios 	= [
				"porcentajeThreshold" 		=> $oProductoConfiguracion->precio,
				"montoMinimoDeFlexibilidad" => $oProductoConfiguracion->precio2,
				"porcentajeDeMeta" 			=> $oProductoConfiguracion->precio3
			];
		}

		catch(Exception $e){ 
			Log::error($e); 
		}
	}

	//	Devuelve un producto por idProducto
	public function getById( $xIdProducto = null ) {
		try{
			if( is_numeric($xIdProducto) ) {
				$xUrl = config('main.URL_API')
					."Productos?xKey=". config('main.FIDELITY_KEY')
					."&xOffSet=" .		"0"
					."&xLimit=" . 		"1"
					."&xIdSitio=" . 	config('main.ID_SITO')
					."&xIdProducto=" . 	$xIdProducto;

				$vConfig = $this->getConfig($xUrl);

				if( is_array($vConfig) && !empty($vConfig) ) {
					$oProducto = $vConfig[0];
					session()->put('meta-producto',$oProducto);
					unset($xIdProducto, $vConfig);

					return $oProducto;
				}
			}
		}
		catch(Exception $e) { 
			log::error($e); 
		}
	}
	
	// 
	protected function getConfig( $xUrl = null ) {
		try{
			if( is_string($xUrl) ) {
				$vDataAPI = 
					Sawubona::getCache( $xUrl, config('main.CACHE_PRODUCTO'));
				$vDataAPI = (object) json_decode($vDataAPI, false);

				if( $vDataAPI->data != null)
				{
					$vDataAPI = $vDataAPI->data->productos;
				}
				return $vDataAPI;
			}
		}
		catch(Exception $e) { 
			Log::error($e);
		}
	}
}
?>