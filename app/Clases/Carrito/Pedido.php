<?php

namespace Clases\Carrito;

use App\Sawubona\Sawubona;

date_default_timezone_set('America/Argentina/Buenos_Aires');

class Pedido {
	public $idPedido;
	public $idEstadoPago; //1 acreditado // 12 sin definir //  6 cancelado
	public $formaPago;

	public function __construct() {
		$this->idPedido = NULL;
		$this->idEstadoPago = NULL;
		$this->formaPago = NULL;
	}

	public function getByCampoValor($xOffset = 0, $xLimit = 0, $xCampoValor = 'codigo_descuento', $xValorCampo = null, $xCampoOrden = '', $xValorOrden = '') {
		try {
			$xValorCampo = 'codigo_descuento';
			if (is_string($xValorCampo) && str_replace(" ", "", $xValorCampo) != "") {
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
			}
		} catch (Exception $e) {
			Sawubona::writeLog($e);
		}
	}

	protected function getPedidos($xUrl = null) {
		try {
			if (is_string($xUrl)) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $xUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$vDataAPI = curl_exec($ch);
				curl_close($ch);
				$vDataAPI = (object) json_decode($vDataAPI, false);
				
				if ($vDataAPI->data != null) {
					$vDataAPI = $vDataAPI->data->pedidos;
				}

				return $this->setPedidos($vDataAPI);
			}
		} catch (Exception $e) {
			Sawubona::writeLog($e);
		}
	}

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
			Sawubona::writeLog($e);
		}
	}

	protected function setPedido($oPedidoAPI = null) {
		try {
			if ($oPedidoAPI != null && $oPedidoAPI instanceof stdClass) {
				$oPedido = new Pedido();
				$oPedido->idPedido = isset($oPedidoAPI->idPedido) ? $oPedidoAPI->idPedido : null;
				$oPedido->formaPago = isset($oPedidoAPI->formaPago) ? $oPedidoAPI->formaPago : null;
				isset($oPedidoAPI->estadoPago) ? $oPedido->idEstadoPago = $oPedidoAPI->estadoPago->idEstado: null;
				
				return $oPedido;
			}
		} catch (Exception $e) {
			Sawubona::writeLog($e);
		}
	}
}
?>