Sawubona Maps
=============

Utilizando la API de Google Maps dibuja un mapa en el contenedor indicado, y representa los marcadores y al usuario. Al hacer click en un marcador, crea la ruta entre el usuario y el marcador presionado.

## Como usar

En el controlador

+	Instanciar un objeto Marcador para que se agrege la libreria que gestiona el mapa en la web
+	Preguntar si la solicitud es del tipo AJAX
+	En caso que lo sea, obtener Marcadores por el metodo que sea necesario ("getBySegmento", "getBySegmentoByPerfil")
+	Imprimir en formato JSON el resultado obtenido y detener la ejecucion del codigo

``` php
$oMarcador = new Marcador();
if($this->isAjax()){
	$vMarcadores = $oMarcador->getBySegmento($xIdSegmento, $xOffset, $xLimit, $xEstructura);
	echo json_encode($vMarcadores);
	exit;
}
```

En la vista

+	Crear un contenedor "div" con el id "sawubona-map"
+	Colocar (solo si es necesario) un input con el id "sawubona-map-autocomplete"

``` html
<input type="text" id="sawubona-map-autocomplete">
<div id="sawubona-map"></div>
```

## Parametros

Donde se ejecuta la libreria ('views/layouts/script/js.php') se puede configurar diferentes parametros.

+	url: por defecto es la misma que renderiza el resto de la pagina. En caso de ser necesario puede cambiarse. (string)
+	center: los valores (lat, lng) donde se centrara por defecto el mapa en caso de no haber marcadores ni posicion de usuario. (object {lat: float, lng: float})
+	idContainer: el id del contenedor donde se renderiza el mapa. Por defecto 'sawubona-map'. (string)
+	idAutocomplete: el id del input para escribir la ubicacion del usuario. (string)
+	key: la clave de API Google Maps. (string)
+	zoom: el valor del zoom con el que aparece el mapa. Por defecto 14. (int)
+	radio: el valor en metros del circulo de proximidad de marcadores al usuario. Por defecto 5000. (int)
+	icons: la ruta de cada uno de los iconos de los marcadores (default, destacado, user). (object {default: string, destacado: string, user: string})
+	centerUser: indica si el mapa se centrara en el usuario (en caso de éste permitir su ubicacion) o no. Por defecto es true. (bool)

``` js
maps({
	url: 'url-de-marcadores',
	center: {lat: 123, lng: 123},
	idContainer: 'id-contenedor-map',
	idAutocomplete: 'id-autocomplete',
	key: 'key-de-google-api',
	zoom: 10,
	radio: 1500,
	icons: {
		default: 'url-imagen-marcador-tipo-default',
		destacado: 'url-imagen-marcador-tipo-destacado',
		user: 'url-imagen-marcador-tipo-user'
	},
	centerUser: false
});
```

## Extras

Se puede centrar el mapa en un marcador desde un elemento externo al mapa, para ello ejecutar la function Sawubona.map.center(), pasando como parametro el idMarcador, que corresponde al id de la persona cargada en FFTT.

``` html
<button onclick="Sawubona.map.center(idMarcador)"></button>
```

``` js
$('button').on('click', function(){
	Sawubona.map.center(idMarcador);
});
```