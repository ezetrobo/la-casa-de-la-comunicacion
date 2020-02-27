<!DOCTYPE html>
<html lang="es">
<head>
	@include('layouts.head')
	@include('layouts.scripts.css')
</head>

<body>
    @yield('contenido')
    @include('layouts.footer')
    @include('layouts.scripts.js')
</body>

</html>
