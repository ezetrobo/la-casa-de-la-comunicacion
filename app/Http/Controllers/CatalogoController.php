<?php
namespace App\Http\Controllers;

use App\Clases\Common\Paginador;
use App\Clases\Producto\Categoria;
use App\Clases\Producto\Etiqueta;
use App\Clases\Producto\Producto;
use App\Clases\Producto\SCatalogo;
use App\Sawubona\Caracteres;
use Illuminate\Http\Request;

/**
 *  [index description]  -> Muestra el catálogo de productos.
 *  [show description]  -> Consulta el producto por id.
 *  [categoria description]  -> Filtra el catálogo solo por categorías.
 *  [etiquetas description]  -> Filtra el catálogo solo por etiquetas.
 *  [filtros description]  -> Filtra el catalogo por categoría y etiquetas
*/

class CatalogoController extends Controller {
	/**
	 * [index description]
	 * @return Muestra el catálogo de productos y envia las variables 'oProductos', 'oEtiquetas', 'oCategorias'
	 */
	public function index(Request $request) {
		$xIdCatalogo = config('parametros.idTipoCatalogo_es');
		$oProductos = new Producto();
		$xOffSet = 0;
		$xLimit = 1000;
		$xOrderBy = '';
		$xOrderType = 'DESC';
		$xGetChild = false;
		$oProductos = $oProductos->getAll($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);
		$oProductos = Paginador::getPaginador($oProductos, $paginate = 9, $request->page);
		$oProductos = Producto::descripcionCorta($oProductos);
		
		/**
		 * [$Etiquetas description]
		 * @var [type]
		 */
		$oEtiquetas = new Etiqueta();
		$xOffSet = 0;
		$xLimit = 10;
		$xOrderBy = '';
		$xOrderType = 'DESC';
		$xGetChild = false;
		$oEtiquetas = $oEtiquetas->getAll($xIdCatalogo, $xOffSet, $xLimit, $xOrderBy, $xOrderType, $xGetChild);
		
		/**
		 * Traer el arbol de categorias
		 */
		$oCategorias = new Categoria();
		$xOrderByNombre = false;
		$oCategorias = $oCategorias->getAllOrdenadas($xIdCatalogo, $xOrderByNombre);

		return view('catalogo.index')->with(compact('oProductos', 'oEtiquetas', 'oCategorias'));
	}

	/**
	 * [show description] Consulta el producto por id
	 * @param  integer $xIdProducto 		   [description] idProducto
	 * @return Product oProduct    [description] Muestra la vista del producto
	 */
	public function show($xIdProducto) {
		$oProducto = new Producto();
		$xOrderChild = false;
		$oProducto = $oProducto->getById($xIdProducto, $xOrderChild);
		return view('catalogo.producto-ampliado')->with(compact('oProducto'));
	}

	/**
	 * [categoria description] Filtra el catálogo solo por categorías.
	 * @param  integer $idCategoria      [description] idCategoria
	 * @param  string  $nombreCategoria  [description] Nombre de la categoría
	 * @return Envia  a la vista las variables a mostrar 'oProductos', 'oEtiquetas', 'oCategorias', 'nombreCategoria'
	 */
	public function categoria(Request $request, $idCategoria, $nombreCategoria) {
		$nombreCategoria = Caracteres::getNombreCategoria($nombreCategoria);
		$xIdCategoria = $idCategoria;

		// Mismo id para todas las busquedas
		$xIdCatalogo = config('parametros.idTipoCatalogo_es');

		// Buscamos el producto
		$oProductosFiltro = new SCatalogo();
		$xEtiquetas = '';
		$xOffSet = 0;
		$xLimit = 1000;
		$xOrderBy = '';
		$xOrderType = 'asc';
		$oProductosFiltro->getProductos($xIdCatalogo, $xIdCategoria, $xEtiquetas, $xOffSet, $xLimit, $xOrderBy, $xOrderType);
		$oEtiquetas = new Etiqueta();
		$oEtiquetas = $oProductosFiltro->etiquetas;
		$oProductos = new Producto();
		$oProductos = $oProductosFiltro->productos;
		$oProductos = Paginador::getPaginador($oProductos, $paginate = 9, $request->page);
		$oProductos = Producto::descripcionCorta($oProductos);
		$oCategorias = new Categoria();
		$oCategorias = $oProductosFiltro->categorias;

		return view('catalogo.index')->with(compact('oProductos', 'oEtiquetas', 'oCategorias', 'nombreCategoria'));
	}

	/**
	 * [etiquetas description] Filtra el catálogo solo por etiquetas
	 * @param  string $etiquetas [description] nombre de la etiqueta
	 * @return Envia las variables a la vista 'oProductos', 'oEtiquetas', 'oCategorias', 'etiquetasSelected'
	 */
	public function etiquetas(Request $request, $etiquetas) {
		$xIdCatalogo = config('parametros.idTipoCatalogo_es');
		$xIdCategoria = 0;
		$xEtiquetas = $etiquetas;
		$xOffSet = 0;
		$xLimit = 1000;
		$xOrderBy = '';
		$xOrderType = 'asc';
		/**
		 * [$oProductosFiltro description] Filtramos los productos
		 * @var SCatalogo
		 */
		$oProductosFiltro = new SCatalogo();
		$oProductosFiltro->getProductos($xIdCatalogo, $xIdCategoria, $xEtiquetas, $xOffSet, $xLimit, $xOrderBy, $xOrderType);
		$oEtiquetas = new Etiqueta();
		$oEtiquetas = $oProductosFiltro->etiquetas;
		$oProductos = new Producto();
		$oProductos = $oProductosFiltro->productos;
		$oProductos = Paginador::getPaginador($oProductos, $paginate = 9, $request->page);
		$oProductos = Producto::descripcionCorta($oProductos);
		$oCategorias = new Categoria();
		$oCategorias = $oProductosFiltro->categorias;
		$etiquetasSelected = new Etiqueta();
		$etiquetasSelected = $etiquetasSelected->getEtiquetasSelected($xEtiquetas, $xIdCatalogo);

		return view('catalogo.index')->with(compact('oProductos', 'oEtiquetas', 'oCategorias', 'etiquetasSelected'));
	}

	/**
	 * [filtros description] Filtra el catalogo por categoría y etiquetas
	 * @param  integer $idCategoria     [description]
	 * @param  string  $nombreCategoria [description]
	 * @param  string  $etiquetas       [description]
	 * @return Envia a la vista las variables necesarias. 'oProductos', 'oEtiquetas', 'oCategorias', 'etiquetasSelected', 'nombreCategoria'
	 */
	public function filtros(Request $request, $idCategoria, $nombreCategoria, $etiquetas) {
		$xIdCategoria = $idCategoria;
		// Mismo id para todas las busquedas
		$xIdCatalogo = config('parametros.idTipoCatalogo_es');

		$xEtiquetas = $etiquetas;
		$xOffSet = 0;
		$xLimit = 1000;
		$xOrderBy = '';
		$xOrderType = 'asc';
		/**
		 * [$oProductosFiltro description] Filtramos los productos
		 * @var SCatalogo
		 */
		$oProductosFiltro = new SCatalogo();
		$oProductosFiltro->getProductos($xIdCatalogo, $xIdCategoria, $xEtiquetas, $xOffSet, $xLimit, $xOrderBy, $xOrderType);
		$oEtiquetas = new Etiqueta();
		$oEtiquetas = $oProductosFiltro->etiquetas;
		$oProductos = new Producto();
		$oProductos = $oProductosFiltro->productos;
		$oProductos = Paginador::getPaginador($oProductos, $paginate = 9, $request->page);
		$oProductos = Producto::descripcionCorta($oProductos);
		$oCategorias = new Categoria();
		$oCategorias = $oProductosFiltro->categorias;
		
		/**
		 * [$etiquetasSelected description] Etiquetas seleccionada
		 * @var Etiqueta
		 */
		$etiquetasSelected = new Etiqueta();
		$etiquetasSelected = $etiquetasSelected->getEtiquetasSelected($xEtiquetas, $xIdCatalogo);

		return view('catalogo.index')->with(compact('oProductos', 'oEtiquetas', 'oCategorias', 'etiquetasSelected', 'nombreCategoria'));
	}
}
