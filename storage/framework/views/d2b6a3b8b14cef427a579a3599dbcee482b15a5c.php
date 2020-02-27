<div class="contenedorCarro">
	<span id="contadorCarro" class="glyphicon glyphicon-shopping-cart"><p></p></span>

	<div class="row carrito" style="display: none;">
    	<div class="col-md-12">
    		<div class="box box-solid">
	            <div class="box-body">
	            	<div class="box-group" id="accordion">
		                <div class="panel box box-primary">
		                	<div class="box-header with-border">
		                    	<h4 class="box-title">
		                     		<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Entrega</a>
		                    	</h4>
		                  	</div>
		                  	<div id="collapseOne" class="panel-collapse collapse">
		                    	<div class="box-body">
		                      	A
		                    	</div>
		                	</div>
		                </div>

		                <div class="panel box box-danger">
		                	<div class="box-header with-border">
		                    	<h4 class="box-title">
		                      		<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Productos</a>
		                    	</h4>
		                  	</div>
		                	<div id="collapseTwo" class="panel-collapse collapse">
		                    	<div class="box-body">
		                    		<div id="contenidoCarro">
			                      		<?php $__env->startSection('carrito'); ?>
			                      			<?php if(!empty($oCarrito)): ?>
			                      				<table style="width: 100%">
		                      						<tr>
		                      							<td>Cantidad</td>
		                      							<td>Descripcion</td>
		                      							<td>Precio</td>
		                      							<td>Total</td>
		                      							<td>Opciones</td>
		                      						</tr>
			                      				<?php $__currentLoopData = $oCarrito; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $xCarrito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			                      						<tr>
			                      							<td><?php echo e($xCarrito->cantidad); ?></td>
			                      							<td><?php echo e($xCarrito->titulo); ?></td>
			                      							<td>
			                      								<input type="text" class="form-control" name="" value="<?php echo e($xCarrito->precio); ?>">
			                      							</td>
			                      							<td><?php echo e($xCarrito->precio * $xCarrito->cantidad); ?></td>
			                      							<td>
			                      								<span onclick="deleteProducto(<?php echo $xCarrito->idProducto; ?>)" class="glyphicon glyphicon-remove"></span>
			                      							</td>
			                      						</tr>
			                      				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			                      				</table>
			                      			<?php endif; ?>
			                      		<?php $__env->stopSection(); ?>
			                    	</div>
		                		</div>
		                	</div>
		                </div>
		                <div class="panel box box-success">
		                	<div class="box-header with-border">
		                    	<h4 class="box-title">
		                      		<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Completa tus datos</a>
		                    	</h4>
		                  	</div>
		                	<div id="collapseThree" class="panel-collapse collapse">
		                    	<div class="box-body">
		                    		C
		                    	</div>
		                	</div>
		                </div>

		                <div class="panel box box-success">
		                	<div class="box-header with-border">
		                    	<h4 class="box-title">
		                    		<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">Forma de pago</a>
		                    	</h4>
		                  	</div>
		                  	<div id="collapseFour" class="panel-collapse collapse">
		                    	<div class="box-body">
		                    		D
		                    	</div>
		                	</div>
		                </div>

	              </div>
	            </div>
    		</div>
    	</div>
	</div>
<?php /**PATH C:\xampp\htdocs\sitios-laravel\laravel-maqueta\resources\views/layouts/menu.blade.php ENDPATH**/ ?>