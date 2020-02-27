 @extends('layouts.app')

@section('body-clase','landing-page sidebar-collapse')

@section('contenido')
    @include('layouts.menu')

	<section id="head-error">
		<div class="bg-head">
			<div class="overlay"></div>
			<div id="topgracias">
				<img src="{{asset('images/icons/path.png')}}" alt="" class="mb-5">
				<h1 class="title-video">Se ha producido un error</h1>
				<p class="info-error">La página a la que está intentando acceder no se encuentra disponible en este momento.</p>
				<button class="btn-lg btn-video">VOLVER AL INICIO</button>
			</div>
			<div class="icon-slide">
				<img src="{{('images/home/path.png')}}" alt="">
			</div>
		</div>
	</section>



@include('layouts.footer')

@endsection