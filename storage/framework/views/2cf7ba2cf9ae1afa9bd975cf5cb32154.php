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
                <i class="bi bi-graph-up me-2"></i><?php echo e(__('Reportes de Inventario')); ?>

            </h2>
            <div class="btn-group">
                <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
                <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Imprimir
                </button>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Estadísticas generales -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Productos</h6>
                                    <h2 class="mb-0"><?php echo e($stats['total_products']); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Valor Total Stock</h6>
                                    <h2 class="mb-0">₲ <?php echo e(number_format($stats['total_stock_value'])); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-exchange" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Stock Bajo</h6>
                                    <h2 class="mb-0"><?php echo e($stats['low_stock_products']); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-exclamation-triangle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Sin Stock</h6>
                                    <h2 class="mb-0"><?php echo e($stats['out_of_stock_products']); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Productos más movidos -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-arrow-up-down me-2"></i>
                                Productos más Movidos (Últimos 30 días)
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($topMovedProducts->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Producto</th>
                                                <th>Código</th>
                                                <th class="text-center">Movimientos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $topMovedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary me-2"><?php echo e($index + 1); ?></span>
                                                            <strong><?php echo e($product->name); ?></strong>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <code class="bg-light px-2 py-1 rounded"><?php echo e($product->code); ?></code>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success fs-6"><?php echo e($product->movements_count); ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No hay movimientos en los últimos 30 días</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Movimientos recientes -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                Movimientos Recientes
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if($recentMovements->count() > 0): ?>
                                <div class="list-group list-group-flush">
                                    <?php $__currentLoopData = $recentMovements->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?php echo e($movement->product->name); ?></h6>
                                                    <p class="mb-1">
                                                        <span class="badge <?php echo e($movement->type == 'in' ? 'bg-success' : 
                                                            ($movement->type == 'out' ? 'bg-danger' : 'bg-warning')); ?>">
                                                            <?php echo e($movement->type_name); ?>

                                                        </span>
                                                        <span class="ms-2"><?php echo e($movement->quantity_display); ?></span>
                                                    </p>
                                                    <small class="text-muted"><?php echo e($movement->user->name); ?></small>
                                                </div>
                                                <small class="text-muted"><?php echo e($movement->created_at->diffForHumans()); ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                
                                <div class="mt-3 text-center">
                                    <a href="<?php echo e(route('inventory.movements')); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Ver Todos los Movimientos
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="bi bi-clock-history" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">No hay movimientos registrados</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de tendencias -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-bar-chart me-2"></i>
                                Análisis de Inventario
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Estado del Inventario</h6>
                                    <?php
                                        $totalProducts = $stats['total_products'];
                                        $lowStockPct = $totalProducts > 0 ? ($stats['low_stock_products'] / $totalProducts) * 100 : 0;
                                        $outOfStockPct = $totalProducts > 0 ? ($stats['out_of_stock_products'] / $totalProducts) * 100 : 0;
                                        $okStockPct = 100 - $lowStockPct - $outOfStockPct;
                                    ?>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Stock OK</span>
                                            <strong class="text-success"><?php echo e(number_format($okStockPct, 1)); ?>%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: <?php echo e($okStockPct); ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Stock Bajo</span>
                                            <strong class="text-warning"><?php echo e(number_format($lowStockPct, 1)); ?>%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" style="width: <?php echo e($lowStockPct); ?>%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-between">
                                            <span>Sin Stock</span>
                                            <strong class="text-danger"><?php echo e(number_format($outOfStockPct, 1)); ?>%</strong>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-danger" style="width: <?php echo e($outOfStockPct); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-3">Recomendaciones</h6>
                                    
                                    <?php if($stats['out_of_stock_products'] > 0): ?>
                                        <div class="alert alert-danger">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            <strong>Crítico:</strong> <?php echo e($stats['out_of_stock_products']); ?> productos sin stock. 
                                            <a href="<?php echo e(route('inventory.low-stock')); ?>" class="alert-link">Ver detalle</a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($stats['low_stock_products'] > 0): ?>
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-circle me-2"></i>
                                            <strong>Atención:</strong> <?php echo e($stats['low_stock_products']); ?> productos con stock bajo. 
                                            <a href="<?php echo e(route('inventory.low-stock')); ?>" class="alert-link">Revisar</a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($stats['out_of_stock_products'] == 0 && $stats['low_stock_products'] == 0): ?>
                                        <div class="alert alert-success">
                                            <i class="bi bi-check-circle me-2"></i>
                                            <strong>Excelente:</strong> El inventario está en buen estado.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información de generación del reporte -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="text-center text-muted">
                        <small>
                            Reporte generado el <?php echo e(now()->format('d/m/Y H:i:s')); ?> por <?php echo e(auth()->user()->name); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/inventory/reports.blade.php ENDPATH**/ ?>