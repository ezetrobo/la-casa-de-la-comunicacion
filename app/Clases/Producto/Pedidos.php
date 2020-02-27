<?php

namespace App\Clases\Producto;

use App\Clases\Common\Imagen;
use App\Sawubona\Sawubona;

class Pedidos {
	public $idPedido;
	public $fecha;
	public $totalP;
	public $listaProductosxPedido;
	public $estadoPago;

	public function __construct() {
		try {
			date_default_timezone_set('America/Argentina/Buenos_Aires');
			$this->idPedido = null;
			$this->fecha = null;
			$this->totalP = null;
			$this->listaProductosxPedido = array();
			$this->estadoPago = new EstadoPago();
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getByCampoValor description]
	 * @param  integer $xOffset     [description]
	 * @param  integer $xLimit      [description]
	 * @param  string  $xCampoValor [description]
	 * @param  string  $xValorCampo [description]
	 * @param  string  $xCampoOrden [description]
	 * @param  string  $xValorOrden [description]
	 * @return Pedidos              [description]
	 */
	public function getByCampoValor($xOffset = 0, $xLimit = 0, $xCampoValor = 'codigo_descuento', $xValorCampo = null, $xCampoOrden = '', $xValorOrden = '') {
		try {

			$xUrl = config('main.URL_API')
			. "Pedidos?xKey=" . config('main.FIDELITY_KEY')
			. "&xIdSitio=" . config('main.ID_SITIO')
			. "&xIdTipoContenido=" . config('main.ID_ECOMMERCE')
				. "&xOffset=" . $xOffset
				. "&xLimit=" . $xLimit
				. "&xCampoValor=" . $xCampoValor
				. "&xValorCampo=" . $xValorCampo
				. "&xCampoOrden=" . $xCampoOrden
				. "&xValorOrden=" . $xValorOrden;
			return $this->getPedidos($xUrl);

		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getByidPedido description] Retorna el pedido por el campo idPedido
	 * @param  string $idPedido 	[description]
	 * @return Pedidos           	[description]
	 */
	public function getByidPedido($idPedido = null) {
		try {
			if (!empty($idPedido)) {
				$xUrl = config('main.URL_API')
				. "Pedidos?xKey=" . config('main.FIDELITY_KEY')
					. "&xIdPedido=" . $idPedido;

				return $this->getPedidos($xUrl);
			}
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getArchivosCompra description] 	Devuelve historial de compras de una persona
	 * @param  [type]  $xIdTipoContenido [description]
	 * @param  integer $xOffSet          [description]
	 * @param  integer $xLimit           [description]
	 * @param  integer $xIdPersona       [description]
	 * @return [type]                    [description]
	 */
	public function getArchivosCompra($xIdTipoContenido = null, $xOffSet = 0, $xLimit = 30, $xIdPersona = 0) {
		try {
			$xUrl = config('main.URL_API')
			. 'Pedidos?xKey=' . config('main.FIDELITY_KEY')
			. '&xIdSitio=' . config('main.ID_SITIO')
				. '&xIdTipoContenido=' . $xIdTipoContenido
				. '&xIdPersona=' . $xIdPersona
				. '&xOffset=' . $xOffSet
				. '&xLimit=' . $xLimit;

			return $this->getPedidos($xUrl);
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
	/**
	 * [getLink description] Devuelve el link del contenido
	 * @param  string $xUrl [description]
	 * @return string       [description]
	 */
	public function getLink($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				if (config('MULTIDIOMAS')) {
					$xLink = Sawubona::getTranslation($xUrl);
					if (isset($xLink->href)) {
						return '/' . Sawubona::getIdioma() . '/' . $xLink->href . '/' . $this->idPedido . '/' . Caracteres::convertToUrl($this->idPedido);
					}
				} else {
					return '/' . Caracteres::convertToUrl($xUrl) . '/' . $this->idPedido . '/' . Caracteres::convertToUrl($this->idPedido);
				}
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}

	//	Funciones privadas---------------------------------------------------------------------------------------------------------
	/**
	 * [getPedidos description]
	 * @param  [type] $xUrl [description]
	 * @return [type]       [description]
	 */
	protected function getPedidos($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$vDataAPI = Sawubona::getCache($xUrl, config('main.CACHE_PRODUCTO'));
				// $vDataAPI = (object) json_decode($vDataAPI, false);
				if (isset($vDataAPI->data->pedidos)) {
					return $this->setPedidos($vDataAPI->data->pedidos);
				}
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [setPedidos description]
	 * @param [type] $vPedidosAPI [description]
	 */
	protected function setPedidos($vPedidosAPI = null) {
		try {
			if (is_array($vPedidosAPI) && !empty($vPedidosAPI)) {
				$vPedidos = array();
				foreach ($vPedidosAPI as $oPedidoAPI) {
					$vPedidos[] = $this->setPedido($oPedidoAPI);
				}

				unset($vPedidosAPI);
				return $vPedidos;
			}
		} catch (Exception $e) {
			// Sawubona::writeLog($e);
		}
	}
	/**
	 * [setPedido description] Convierte un Objeto ProductoAPI en Producto
	 * @param 	oPedido $oPedidoAPI [description]
	 * @return 	oPedido       		[description]
	 */
	protected function setPedido($oPedidoAPI = null) {
		try
		{
			$oPedido = new Pedidos();
			$oPedido->idPedido = isset($oPedidoAPI->idPedido) ? $oPedidoAPI->idPedido : null;
			$oPedido->totalP = isset($oPedidoAPI->total) ? $oPedidoAPI->total : null;
			$oPedido->fecha = isset($oPedidoAPI->fecha) ? $oPedidoAPI->fecha : new Carbon();
			if (isset($oPedidoAPI->listaProductosxPedido) &&
				is_array($oPedidoAPI->listaProductosxPedido)) {
				foreach ($oPedidoAPI->listaProductosxPedido as $oProductoPedidoAPI) {
					if (empty($oProductoPedidoAPI->producto->imagenesProducto)) {
						$oProductoPedidoAPI->producto->imagenesProducto[0] = new Imagen();
						$oProductoPedidoAPI->producto->imagenesProducto[0]->path = '/images/default/producto.jpg';
						$oProductoPedidoAPI->producto->imagenesProducto[0]->default = TRUE;
					}
					$oPedido->listaProductosxPedido[] = $oProductoPedidoAPI;
				}
			}
			$oPedido->estadoPago = isset($oPedidoAPI->estadoPago) ? $oPedidoAPI->estadoPago : null;
			return $oPedido;
		} catch (Exception $e) {
			//Sawubona::writeLog($e);
		}
	}
}
?>