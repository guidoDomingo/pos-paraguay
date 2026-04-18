<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 font-weight-bold mb-0">
                <i class="bi bi-eye me-2"></i><?php echo e(__('Detalle del Producto')); ?>

            </h2>
            <div class="btn-group">
                <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row">
                <!-- Información principal -->
                <div class="col-xl-8">
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información General
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Nombre</h6>
                                    <h4 class="mb-3"><?php echo e($product->name); ?></h4>
                                    
                                    <h6 class="text-muted mb-1">Código</h6>
                                    <p class="mb-3">
                                        <code class="bg-light px-2 py-1 rounded fs-6"><?php echo e($product->code); ?></code>
                                    </p>
                                    
                                    <?php if($product->barcode): ?>
                                        <h6 class="text-muted mb-1">Código de Barras</h6>
                                        <p class="mb-3">
                                            <code class="bg-light px-2 py-1 rounded"><?php echo e($product->barcode); ?></code>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if($product->description): ?>
                                        <h6 class="text-muted mb-1">Descripción</h6>
                                        <p class="mb-3"><?php echo e($product->description); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Categoría</h6>
                                    <p class="mb-3">
                                        <?php if($product->category): ?>
                                            <span class="badge bg-secondary fs-6"><?php echo e($product->category->name); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">Sin categoría asignada</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <h6 class="text-muted mb-1">Estado</h6>
                                    <p class="mb-3">
                                        <?php if($product->is_active): ?>
                                            <span class="badge bg-success fs-6">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary fs-6">Inactivo</span>
                                        <?php endif; ?>
                                    </p>
                                    
                                    <h6 class="text-muted mb-1">Unidad de Medida</h6>
                                    <p class="mb-3"><?php echo e(ucfirst($product->unit ?? 'unidad')); ?></p>
                                    
                                    <h6 class="text-muted mb-1">Fecha de Creación</h6>
                                    <p class="mb-0"><?php echo e($product->created_at->format('d/m/Y H:i')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-currency-exchange me-2"></i>
                                Información de Precios
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Precio de Costo</h6>
                                        <h4 class="text-info mb-0">₲ <?php echo e(number_format($product->cost_price ?? 0)); ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Precio de Venta</h6>
                                        <h4 class="text-success mb-0">₲ <?php echo e(number_format($product->sale_price)); ?></h4>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted mb-1">IVA</h6>
                                    <h4 class="text-warning mb-0"><?php echo e($product->tax_rate ?? 10); ?>%</h4>
                                </div>
                            </div>
                            
                            <?php if($product->cost_price > 0): ?>
                                <hr>
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">Margen de Ganancia</h6>
                                        <h5 class="text-primary mb-0">
                                            ₲ <?php echo e(number_format($product->sale_price - $product->cost_price)); ?>

                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted mb-1">% Margen</h6>
                                        <h5 class="text-primary mb-0">
                                            <?php echo e(number_format((($product->sale_price - $product->cost_price) / $product->cost_price) * 100, 1)); ?>%
                                        </h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-xl-4">
                    <!-- Stock -->
                    <?php if($product->track_stock): ?>
                        <div class="card shadow mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-boxes me-2"></i>
                                    Control de Inventario
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="display-4 <?php echo e($product->stock <= $product->min_stock ? 'text-danger' : 'text-success'); ?> mb-1">
                                    <?php echo e($product->stock ?? 0); ?>

                                </h2>
                                <p class="text-muted mb-3"><?php echo e(ucfirst($product->unit ?? 'unidades')); ?> disponibles</p>
                                
                                <?php if($product->min_stock > 0): ?>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span>Stock Mínimo:</span>
                                        <strong><?php echo e($product->min_stock); ?></strong>
                                    </div>
                                    
                                    <?php if($product->stock <= $product->min_stock): ?>
                                        <div class="alert alert-warning mt-3 mb-0">
                                            <i class="bi bi-exclamation-triangle me-1"></i>
                                            <strong>Stock bajo!</strong> Este producto necesita reposición.
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card shadow mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-infinity me-2"></i>
                                    Sin Control de Stock
                                </h5>
                            </div>
                            <div class="card-body text-center">
                                <i class="bi bi-infinity" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">
                                    Este producto no tiene control de inventario activado.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Acciones rápidas -->
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-lightning me-2"></i>
                                Acciones Rápidas
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-primary">
                                    <i class="bi bi-pencil me-2"></i>Editar Producto
                                </a>
                                
                                <?php if($product->track_stock): ?>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#stockModal">
                                        <i class="bi bi-plus-square me-2"></i>Ajustar Stock
                                    </button>
                                <?php endif; ?>
                                
                                <?php if($product->is_active): ?>
                                    <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-warning w-100" 
                                                onclick="return confirm('¿Desactivar este producto?')">
                                            <i class="bi bi-pause-circle me-2"></i>Desactivar
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button type="button" class="btn btn-success" disabled>
                                        <i class="bi bi-check-circle me-2"></i>Producto Inactivo
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($product->track_stock): ?>
        <!-- Modal para ajustar stock -->
        <div class="modal fade" id="stockModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajustar Stock - <?php echo e($product->name); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="<?php echo e(route('inventory.adjust.store')); ?>" id="stockForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="adjustment_type" class="form-label">Tipo de Ajuste</label>
                                <select class="form-select" id="adjustment_type" name="adjustment_type">
                                    <option value="add">Agregar Stock</option>
                                    <option value="subtract">Reducir Stock</option>
                                    <option value="set">Establecer Stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="0" step="1" required>
                            </div>
                            <div class="mb-3">
                                <label for="reason" class="form-label">Motivo</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Motivo del ajuste de stock" required minlength="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Aplicar Ajuste</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/products/show.blade.php ENDPATH**/ ?>