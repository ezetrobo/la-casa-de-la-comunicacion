<?php
namespace App\Clases\Common;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 *  [getPaginador description]  -> Imprime la imagen.
*/

class Paginador {
	public $arreglo;
	public $paginate;
	public $page;

	public function __construct($oPaginadorAPI = null) {
		try {
			$this->arreglo;
			$this->paginate;
			$this->page;
		} catch (Exception $e) {
			Log::error($e);
		}
	}

	/**
	 * [getPaginador description] Devuelve el paginado de laravel ->links()
	 * @param  array  $arreglo  				[description]
	 * @param  integer $paginate 				[description]
	 * @param  Request->page  $page     		[description] Se envia $request->page
	 * @return LengthAwarePaginator            	[description]
	 */
	public static function getPaginador($arreglo, $paginate = 9, $page) {
		try {
			$offSet = ($page * $paginate) - $paginate;
			$itemsForCurrentPage = array_slice($arreglo, $offSet, $paginate, true);
			return new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($arreglo), $paginate, LengthAwarePaginator::resolveCurrentPage(), array('path' => LengthAwarePaginator::resolveCurrentPath()));
		} catch (Exception $e) {
			Log::error($e);
		}
	}
}
?>