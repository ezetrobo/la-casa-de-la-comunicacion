Bootstrap Carousel responsive
=============

Utilizando la estructura de un carousel de bootstrap permite que se adapte a diferentes tamaños de pantalla, cambiando la cantidad de items a mostrar.

## Como usar

+	Agregar la clase "carousel-responsive" al contenedor con clase "carousel"
+	Agregar el atributo "data-items" con los valores que corresponden a la cantidad de items que se muestran en cada tamaño, ordenado de menor a mayor tamaño. En caso de no agregar este atributo se toma por defecto la configuracion 1, 2, 3, 4.

``` html
<div id="carousel-example-generic" class="carousel carousel-responsive slide" data-ride="carousel" data-items="2, 2, 4, 4">
	<div class="carousel-inner" role="listbox">
		<div class="item active">
			<div class="item-responsive">
				<img src="..." alt="...">
				<div class="carousel-caption">...</div>
			</div>
			<div class="item-responsive">
				<img src="..." alt="...">
				<div class="carousel-caption">...</div>
			</div>
		</div>
		<div class="item">
			<div class="item-responsive">
				<img src="..." alt="...">
				<div class="carousel-caption">...</div>
			</div>
			<div class="item-responsive">
				<img src="..." alt="...">
				<div class="carousel-caption">...</div>
			</div>
		</div>
	</div>
</div>
```