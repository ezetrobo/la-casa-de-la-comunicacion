Sawubona Validations
============================

Validaciones de campos para formularios, permite las siguientes validaciones:

+	documento (is-document)
+	email (is-email)
+	numero (is-number)
+	requerido (is-required)
+	string (is-string)
+	telefono (is-telephone)

## Como usar

Al elemento a validar dentro del formulario se le debe asignar la clase correspondiente a cada validacion. Se puede asignar mas de una clase por elemento.

``` html
<form action="">
	<label for="">Nombre</label>
	<input type="text" class="is-required is-string">
	<label for="">Documento</label>
	<input type="text" class="is-required id-document">
	<label for="">Telefono</label>
	<input type="text" class="is-telephone">
</form>
```

Si el elemento no pasa la validacion se le agrega la clase "error", caso contrario se remueve dicha clase.