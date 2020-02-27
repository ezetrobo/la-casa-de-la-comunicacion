<?php $__env->startSection('body-clase','landing-page sidebar-collapse'); ?>

<?php $__env->startSection('contenido'); ?>
    <?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid nopadding" id="contenedor-slide">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel" data-intervar="8000">

        <div class="carousel-inner" role="listbox">  
            
            <div class="carousel-item active">
                <div class="overlay"><img src="<?php echo e(asset('/images/overlay.png')); ?>" class="" alt=""></div>
                    <div class="img-slide">
                        <img style="background-image: url('<?="images/slide-1.jpg"?>')"> 
                    </div>    
                    <div class="container">
                        <div class="carousel-caption vivify fadeIn delay-100">
                            <div class="col-xl-7">
                                <div class="lead"> Sala Sun Tzu<br> 
                                    <div class="thin"> Lugar para la estrategia y planificación</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="carousel-item">
                <div class="overlay"><img src="<?php echo e(asset('/images/overlay.png')); ?>" class="" alt=""></div>
                    <div class="img-slide">
                        <img style="background-image: url('<?="images/slide-2.jpg"?>')"> 
                    </div>
                    <div class="container">
                        <div class="carousel-caption vivify fadeIn delay-100">
                            <div class="col-xl-7">
                                <div class="lead"> Sala San Martín<br> 
                                    <div class="thin">Lugar para conquista de objetivos</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        
            <div class="container d-none d-sm-block">
                <div class="indicadores vivify fadeIn delay-2000">
                    <div class="col-lg-5 col-md-6">
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" data-slide="prev">
                            <img src="<?php echo e(asset('images/prev.png')); ?>" alt="">
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" data-slide="next">
                            <img src="<?php echo e(asset('images/next.png')); ?>" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="main-text">
            <div class="col-lg-5 col-md-6 col-12">
                <img src="<?php echo e(asset('/images/logo.png')); ?>" class="vivify fadeInTop delay-1000" alt="">
                <h1 class="vivify fadeInBottom delay-1000">30 AÑOS DE EXPERIENCIA <br><strong>EN COMUNICACIÓN 360º</strong></h1>
                <p class="vivify fadeInBottom delay-1000">Una empresa para cada necesidad. La Casa de la Comunicación engloba grupos de 
                trabajo especializados en comunicación integral, omnicanalidad y gestión de bases de datos.</p>
                <div class="botones vivify fadeInBottom delay-1000">
                    <a class="btn-contacto vivify fadeInBottom delay-1000" href="#contacto">CONTACTANOS</a>
                    <a class="btn-visita vivify fadeInBottom delay-1000" href="#visita">VISITÁ LA CASA</a>
                </div>
            </div>
        </div>
    </div>

</div>



<section id="marcas">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="contenedor-logos">
                    <a id="rombo-logos" class="rombo1 rombo" target="1"></a>
                    <a id="rombo-logos" class="rombo2 rombo" target="2"></a>
                    <a id="rombo-logos" class="rombo3 rombo" target="3"></a>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-0 col-md-10 offset-md-1 col-sm-12 offset-sm-0">
                <div class="cnt">
                    <div id="div0" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                        <h1>¿QUIENES SOMOS Y QUÉ HACEMOS?</h1>
                        <p>La casa de la Comunicación une equipos interdisciplinarios para brindar soluciones 360º.
                        Seleccioná una empresa para conocer su área de especialidad.</p>
                    </div>
                    <div id="div1" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                        <h1>OMNICANAL: GESTIÓN DE CLIENTES A GRAN ESCALA</h1>
                        <p>Omnicanal Teledirecto es una poderosa plataforma para el control, atención, contacto y 
                        seguimiento de clientes que responde a los múltiples canales digitales de comunicación 
                        (Whatsapp, Facebook, Instagram, llamadas y sms).</p>
                        <a class="btn-rombo"href="http://www.teledirecto.com/" target="_blank">IR AL SITIO WEB</a>
                    </div>
                    <div id="div2" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                        <h1>SAWUBONA: PROGRAMAS DE COMUNICACIÓN</h1>
                        <p>Sawubona se encarga de la comunicación en todos sus niveles y mediante todos los
                        canales. Asesoramiento, táctica comunicacional, creación de marcas, 
                        identidad corporativa, audiovisuales, sitios web y mucho más.</p>
                        <a class="btn-rombo"href="http://www.sawubona.com.ar/" target="_blank">IR AL SITIO WEB</a>
                    </div>
                    <div id="div3" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                        <h1>FIDELITY TOOLS: INTELIGENCIA COMERCIAL</h1>
                        <p>FidelityTools es una completa herramienta que mecaniza las acciones que promueven la
                        fidelización, la comunicación y las ventas. Confiere a la empresa 
                        inteligencia comercial.</p>
                        <a class="btn-rombo"href="http://www.fidelitytools.net/" target="_blank">IR AL SITIO WEB</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<footer>
    <div class="overlay"><img src="<?php echo e(asset('/images/overlay-2.png')); ?>" alt=""></div>
    <img class="img-footer" style="background-image: url('<?="images/img-footer2.jpg"?>')">
    <div class="container">
        <div class="row">

               <div class="col-xl-5 col-lg-6 col-md-5 col-sm-3 col-7 d-none d-md-flex" id="contenedor-comillas">
                    <img src="<?php echo e(asset('images/comillas.png')); ?>" alt="">
                    <p>Más valiente que una persona que salta en paracaídas, es una que se abraza a sus sueños.</p>
                </div>
            

                <div class="col-xl-4 offset-xl-3 col-lg-12 offset-lg-0">
                    <div class="card">
                        <h1>Estamos para ayudarte</h1>
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" id="formularios" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link nav-botones active" id="btn-contacto" href="#contacto" role="tab" aria-controls="contacto" aria-selected="true">Escribinos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-botones" id="btn-visita"  href="#visita" role="tab" aria-controls="visita" aria-selected="false">Visitá la casa</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content mt-3">
                                <div class="tab-pane vivify fadeIn active" id="contacto" role="tabpanel">
                                    <p class="card-text">Contanos sobre tu idea, proyecto o necesidad. Nuestros 
                                    asesores se pondrán en contacto en la brevedad.</p>
                                    <iframe src="https://app.fidelitytools.net/resource/suscriptor/?f=NjIzNQ&s=NDU1&ididioma=1" frameborder="0"></iframe>
                                </div>
                                <div class="tab-pane vivify fadeIn" id="visita" role="tabpanel" aria-labelledby="visita-tab">  
                                    <p class="card-text">Te esperamos para hacer un recorrido guiado en la Casa de la Comunicación. 
                                    Elegí una fecha y horario preferencial y nos contactaremos para coordinar la visita. </p>
                                    <iframe src="https://app.fidelitytools.net/resource/suscriptor/?f=NjM4Nw&s=NDU1&ididioma=1" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div id="sawubona-footer"></div>
        </div>
    </div>


</footer>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\SITIOS PHP\casa-de-la-comunicacion\resources\views/index.blade.php ENDPATH**/ ?>