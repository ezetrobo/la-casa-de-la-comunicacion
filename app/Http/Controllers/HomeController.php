<?php
namespace App\Http\Controllers;
use App\Clases\Contenido\Contenido;

/**
 *  [index description]  -> Muestra la aplicacion.
 *  [prueba description]  -> Funcion para realizar pruebas unicamente.
 */

class HomeController extends Controller {
	/**
	 * Muestra la aplicacion dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index() {
		$xIdTipoContenido = config('parametros.xIdCarousel');
		$xIdTipoContenido1 = config('parametros.xIdIntroduccion');
		$xIdTipoContenido2 = config('parametros.xIdEmpresas');
		$xIdTipoContenido3 = config('parametros.xIdFooter');
		$xIdTipoContenido4 = config('parametros.xIdFormulario');

		/* Contenido Carousel */
		$xOffSet = 0;
		$xLimit = 0;
		$xOrderBy = 'orden';
		$xOrderType = 'ASC';
		$oContenido = new Contenido();
		$oContenido = $oContenido->getAll($xIdTipoContenido, $xOffSet, $xLimit, $xOrderBy, $xOrderType);

		/* Contenido Introduccion */
		$xOffSet = 0;
		$xLimit = 0;
		$xOrderBy = 'orden';
		$xOrderType = 'ASC';
		$oContenido1 = new Contenido();
		$oContenido1 = $oContenido1->getAll($xIdTipoContenido1, $xOffSet, $xLimit, $xOrderBy, $xOrderType);

		/* Contenido Introduccion */
		$xOffSet = 0;
		$xLimit = 0;
		$xOrderBy = 'orden';
		$xOrderType = 'ASC';
		$oContenido2 = new Contenido();
		$oContenido2 = $oContenido2->getAll($xIdTipoContenido2, $xOffSet, $xLimit, $xOrderBy, $xOrderType);

		/* Contenido Footer */
		$xOffSet = 0;
		$xLimit = 0;
		$xOrderBy = 'orden';
		$xOrderType = 'ASC';
		$oContenido3 = new Contenido();
		$oContenido3 = $oContenido3->getAll($xIdTipoContenido3, $xOffSet, $xLimit, $xOrderBy, $xOrderType);


		return view('index',compact('oContenido','oContenido1','oContenido2','oContenido3'));
	}
}