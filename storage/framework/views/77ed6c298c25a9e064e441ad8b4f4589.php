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
                <i class="bi bi-clock-history me-2"></i><?php echo e(__('Historial de Movimientos')); ?>

            </h2>
            <div class="btn-group">
                <a href="<?php echo e(route('inventory.adjust')); ?>" class="btn btn-primary">
                    <i class="bi bi-sliders me-1"></i>Nuevo Ajuste
                </a>
                <a href="<?php echo e(route('inventory.index')); ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Volver
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Producto</label>
                    <select class="form-select" id="productFilter">
                        <option value="">Todos los productos</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" <?php echo e(request('product_id') == $product->id ? 'selected' : ''); ?>>
                                <?php echo e($product->name); ?> (<?php echo e($product->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tipo</label>
                    <select class="form-select" id="typeFilter">
                        <option value="">Todos los tipos</option>
                        <option value="in" <?php echo e(request('type') == 'in' ? 'selected' : ''); ?>>Entrada</option>
                        <option value="out" <?php echo e(request('type') == 'out' ? 'selected' : ''); ?>>Salida</option>
                        <option value="adjustment" <?php echo e(request('type') == 'adjustment' ? 'selected' : ''); ?>>Ajuste</option>
                        <option value="sale" <?php echo e(request('type') == 'sale' ? 'selected' : ''); ?>>Venta</option>
                        <option value="purchase" <?php echo e(request('type') == 'purchase' ? 'selected' : ''); ?>>Compra</option>
                        <option value="return" <?php echo e(request('type') == 'return' ? 'selected' : ''); ?>>Devolución</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" id="dateFromFilter" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" id="dateToFilter" value="<?php echo e(request('date_to')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-secondary" onclick="applyFilters()">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i> Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de movimientos -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Movimientos de Inventario (<?php echo e($movements->total()); ?> registros)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($movements->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Stock Anterior</th>
                                        <th class="text-center">Stock Nuevo</th>
                                        <th>Usuario</th>
                                        <th>Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($movement->created_at->format('d/m/Y')); ?></strong>
                                                    <br><small class="text-muted"><?php echo e($movement->created_at->format('H:i:s')); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($movement->product->name); ?></strong>
                                                    <br><code class="bg-light px-1 rounded small"><?php echo e($movement->product->code); ?></code>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                    $typeClasses = [
                                                        'in' => 'bg-success',
                                                        'out' => 'bg-danger', 
                                                        'adjustment' => 'bg-warning',
                                                        'sale' => 'bg-info',
                                                        'purchase' => 'bg-primary',
                                                        'return' => 'bg-secondary'
                                                    ];
                                                ?>
                                                <span class="badge <?php echo e($typeClasses[$movement->type] ?? 'bg-secondary'); ?>">
                                                    <?php echo e($movement->type_name); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?php echo e($movement->quantity >= 0 ? 'bg-success' : 'bg-danger'); ?> fs-6">
                                                    <?php echo e($movement->quantity_display); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted"><?php echo e($movement->previous_stock); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-primary"><?php echo e($movement->new_stock); ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($movement->user->name); ?></strong>
                                                    <br><small class="text-muted"><?php echo e($movement->user->email); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($movement->reason): ?>
                                                    <span class="text-muted"><?php echo e(Str::limit($movement->reason, 50)); ?></span>
                                                    <?php if(strlen($movement->reason) > 50): ?>
                                                        <button class="btn btn-sm btn-link p-0" data-bs-toggle="tooltip" title="<?php echo e($movement->reason); ?>">
                                                            <i class="bi bi-info-circle"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">Sin motivo especificado</span>
                                                <?php endif; ?>
                                                
                                                <?php if($movement->unit_cost): ?>
                                                    <br><small class="text-success">Costo: ₲ <?php echo e(number_format($movement->unit_cost, 2)); ?></small>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if($movements->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($movements->withQueryString()->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay movimientos registrados</h5>
                            <p class="text-muted">Los movimientos de inventario aparecerán aquí cuando se realicen ajustes</p>
                            <a href="<?php echo e(route('inventory.adjust')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-minus me-1"></i>Realizar Primer Ajuste
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Resumen estadístico -->
            <?php if($movements->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Movimientos</h6>
                                <h3 class="mb-0"><?php echo e($movements->total()); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Entradas</h6>
                                <h3 class="mb-0">
                                    <?php echo e($movements->where('type', 'in')->count() + 
                                        $movements->where('type', 'purchase')->count() + 
                                        $movements->where('type', 'adjustment')->where('quantity', '>', 0)->count()); ?>

                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Salidas</h6>
                                <h3 class="mb-0">
                                    <?php echo e($movements->where('type', 'out')->count() + 
                                        $movements->where('type', 'sale')->count() + 
                                        $movements->where('type', 'adjustment')->where('quantity', '<', 0)->count()); ?>

                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h6 class="card-title">Ajustes</h6>
                                <h3 class="mb-0"><?php echo e($movements->where('type', 'adjustment')->count()); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function applyFilters() {
            const product = document.getElementById('productFilter').value;
            const type = document.getElementById('typeFilter').value;
            const dateFrom = document.getElementById('dateFromFilter').value;
            const dateTo = document.getElementById('dateToFilter').value;
            
            const url = new URL(window.location.href);
            url.searchParams.set('product_id', product);
            url.searchParams.set('type', type);
            url.searchParams.set('date_from', dateFrom);
            url.searchParams.set('date_to', dateTo);
            
            window.location.href = url.toString();
        }
        
        function clearFilters() {
            window.location.href = '<?php echo e(route("inventory.movements")); ?>';
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/inventory/movements.blade.php ENDPATH**/ ?>