Sawubona Scrolling
===========================

Carga contenido mientras se realiza un scroll vertical en el sitio.

## Como usar

En el controlador

+	Para renderizar la pagina utilizar la funcion $this->renderScroll() pasando los parametros necesarios.

``` php
$this->renderScroll();
```

En la vista

+	Asignarle la clase "container-scroll" al elemento donde se renderizara el contenido

``` html
<div class="container-scroll"></div>
```