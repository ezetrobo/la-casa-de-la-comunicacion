Sawubona Onload Image
============================

Posiciona la imagen de acuerdo a su contenedor para que se visualize correctamente en todos los tamaños de pantalla.

## Como usar

+	Colocar la imagen dentro de un contenedor con la clase "container-image".
+	En la etiqueta img agregar el evento onload con el valor onloadImage(this).

``` html
<div class="container-image">
	<img onload="onloadImage(this)" src="" alt="">
</div>
```

## Asignacion automatica

En los modelos Producto y Contenido, existe el metodo printImages(), que imprime la etiqueta img con el evento onload ya asignado.
al llamar PrintImagenes aclarar que venga con contenedor y con la clase-container-image.