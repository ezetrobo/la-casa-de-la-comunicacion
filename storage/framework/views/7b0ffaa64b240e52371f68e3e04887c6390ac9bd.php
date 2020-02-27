<?php $__env->startSection('body-clase','landing-page sidebar-collapse'); ?>

<?php $__env->startSection('contenido'); ?>
    <?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container-fluid nopadding" id="contenedor-slide">
    <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel" data-intervar="8000">

        <div class="carousel-inner" role="listbox">  
            <?php if(!empty($oContenido)): ?>
                <?php $__currentLoopData = $oContenido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="carousel-item <?php if($key == 0): ?> active <?php endif; ?>">
                    <div class="overlay"><img src="<?php echo e(asset('/images/overlay.png')); ?>" class="" alt=""></div>
                        <div class="img-slide">
                        <img style="background-image: url('<?php echo e($contenido->imagenes[0]->path); ?>')"> 
                        </div>    
                        <div class="container">
                            <div class="carousel-caption vivify fadeIn delay-100">
                                <div class="col-xl-7">
                                    <div class="lead"> <?php echo e($contenido->titulo); ?><br> 
                                        <div class="thin"> <?php echo $contenido->descripcion; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            <?php endif; ?>

        
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
                <?php if(!empty($oContenido1)): ?>
                    <?php $__currentLoopData = $oContenido1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <img src="<?php echo e(asset('/images/logo.png')); ?>" class="vivify fadeInTop delay-1000" alt="">
                    <h1 class="vivify fadeInBottom delay-1000"><?php echo e($contenido->titulo); ?> <br> <strong><?php echo e($contenido->bajada); ?></strong> </h1>
                    <div class="vivify fadeInBottom delay-1000"><?php echo $contenido->descripcion; ?></div>
                    <div class="botones vivify fadeInBottom delay-1000">
                        <a class="btn-contacto vivify fadeInBottom delay-1000" href="#contacto">CONTACTANOS</a>
                        <a class="btn-visita vivify fadeInBottom delay-1000" href="#visita">VISITÁ LA CASA</a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
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
                    <a id="rombo-logos" class="rombo2 rombo" target="3"></a>
                    <a id="rombo-logos" class="rombo3 rombo" target="2"></a>
                </div>
            </div>
            <div class="col-lg-6 offset-lg-0 col-md-10 offset-md-1 col-sm-12 offset-sm-0">
                <div class="cnt">
                    <?php if(!empty($oContenido2)): ?>
                    <?php $__currentLoopData = $oContenido2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($contenido->portada == false): ?>
                        <div id="div<?php echo e($key); ?>" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                            <h1><?php echo e($contenido->titulo); ?></h1>
                                <?php echo $contenido->descripcion; ?>

                            </div>
                        <?php else: ?>
                            <div id="div<?php echo e($key); ?>" class="col-lg-11 offset-lg-1 col-md-12 offset-md-0 col-sm-12 offset-sm-0 div-rombo vivify fadeIn delay-100">
                                <h1><?php echo e($contenido->titulo); ?></h1>
                                <?php echo $contenido->descripcion; ?>

                                <a class="btn-rombo" href="<?php echo e($contenido->link); ?>" target="_blank">IR AL SITIO WEB</a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>




<footer>
    <div class="overlay"><img src="<?php echo e(asset('/images/overlay-2.png')); ?>" alt=""></div>
        <?php if(!empty($oContenido3)): ?>
            <?php $__currentLoopData = $oContenido3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <img class="img-footer" style="background-image: url('<?php echo e($contenido->imagenes[0]->path); ?>')"> 

        <div class="container">
            <div class="row">

                <div class="col-xl-5 col-lg-6 col-md-5 col-sm-3 col-7 d-none d-md-flex" id="contenedor-comillas">
                        <img src="<?php echo e(asset('images/comillas.png')); ?>" alt="">
                        <p><?php echo $contenido->bajada; ?></p>
                    </div>
                

                    <div class="col-xl-4 offset-xl-3 col-lg-12 offset-lg-0">
                        <div class="card">
                            <h1><?php echo $contenido->titulo; ?></h1>

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
                                    <div class="card-text"><?php echo $contenido->descripcion; ?></div>
                                    <iframe src="https://app.fidelitytools.net/resource/suscriptor/?f=NjIzNQ&s=NDU1&ididioma=1" frameborder="0"></iframe>
                                </div>
                                <div class="tab-pane vivify fadeIn" id="visita" role="tabpanel" aria-labelledby="visita-tab">  
                                    <div class="card-text"><?php echo $contenido->detalle; ?></div>
                                    <iframe src="https://app.fidelitytools.net/resource/suscriptor/?f=NjM4Nw&s=NDU1&ididioma=1" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
            <?php endif; ?>
                <div id="sawubona-footer"></div>
        </div>
    </div>


</footer>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\casa-de-la-comunicacion\resources\views/index.blade.php ENDPATH**/ ?>