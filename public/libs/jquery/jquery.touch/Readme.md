Jquery Touch
==========================

Permite la deteccion de eventos touch sobre un elemento

## Como usar

+	Obtener el elemento en cuestion con el selector Jquery
+	Ejecutar la funcion hammer() y sobre esta el evento bind()
+	Dentro del evento bind pasamos el tipo de evento y la funcion a ejecutar

``` js
$('div').hammer().bind('swipe', function(){
	//	El codigo que se ejecuta al suceder el evento swipe
});
```

## Eventos

+	swipe
+	swipeleft
+	swiperight
+	swipeup
+	swipedown