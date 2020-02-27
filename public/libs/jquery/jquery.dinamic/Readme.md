Jquery Dinamic
=========================

Detecta el evento click en cualquier link del selector en el que se inicie.

Dependiendo los atributos de la etiqueta a intercepta o no el evento.

# Como usar

+ 	En el controller utilizar el renderDinamic
+ 	En el enlace (a) utilizar los siguentes parametros:
	+	sw-target: se puede colocar un id (#contenedor) o una class ('.contenedor'). Es donde se renderizara el contenido traido del controller.
	+	sw-open: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se agregara la clase "open".
	+	sw-close: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se les quitara la clase "open".
	+	sw-show: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se agregara la clase "active".
	+	sw-hide: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se les quitara la clase "active".
	+	sw-tg-show: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se hara un toggle la clase "active".
	+	sw-tg-open: se puede colocar un/varios id (#contenedor) o una/variosclass ('.contenedor-1, .contenedor-2'). A estos elementos se hara un toggle la clase "open".

+	Prioridades:
	+	Si colocamos un sw-tg-open, las directivas sw-open y sw-close no tendran efecto.
	+	Si colocamos un sw-tg-show, las directivas sw-show y sw-hide no tendran efecto.
	+	En caso de no existir un tg, primero se ejecuta el close/hide y luego el show/open.

Para iniciar ejecutar la funcion "dinamic" desde un selector JQuery.

```js
$('body').dinamic();
```

Y en el HTML
```js
<a href="la-url-de-destino" sw-target="#main" sw-open=".seccion-d" sw-close=".menu, .sidebar">Ir a destino</a>
```