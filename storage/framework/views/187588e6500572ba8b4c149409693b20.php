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
                <i class="bi bi-boxes me-2"></i><?php echo e(__('Gestión de Inventario')); ?>

            </h2>
            <div class="btn-group">
                <a href="<?php echo e(route('inventory.adjust')); ?>" class="btn btn-primary">
                    <i class="bi bi-sliders me-1"></i>Ajustar Stock
                </a>
                <a href="<?php echo e(route('inventory.movements')); ?>" class="btn btn-outline-info">
                    <i class="bi bi-clock-history me-1"></i>Movimientos
                </a>
                <a href="<?php echo e(route('inventory.reports')); ?>" class="btn btn-outline-success">
                    <i class="bi bi-graph-up me-1"></i>Reportes
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Estadísticas -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Productos con Stock</h6>
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
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Stock Bajo</h6>
                                    <h2 class="mb-0"><?php echo e($stats['low_stock']); ?></h2>
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
                                    <h2 class="mb-0"><?php echo e($stats['out_of_stock']); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
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
                                    <h6 class="card-title">Valor Total</h6>
                                    <h2 class="mb-0">₲ <?php echo e(number_format($stats['total_value'])); ?></h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-currency-exchange" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar productos..." id="searchInput" value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Todas las categorías</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="statusFilter">
                        <option value="">Todos los estados</option>
                        <option value="in_stock" <?php echo e(request('status') == 'in_stock' ? 'selected' : ''); ?>>Con stock</option>
                        <option value="low_stock" <?php echo e(request('status') == 'low_stock' ? 'selected' : ''); ?>>Stock bajo</option>
                        <option value="out_of_stock" <?php echo e(request('status') == 'out_of_stock' ? 'selected' : ''); ?>>Sin stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="btn-group w-100">
                        <button type="button" class="btn btn-outline-secondary" onclick="applyFilters()">
                            <i class="bi bi-funnel"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Inventario de Productos
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($products->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th class="text-center">Stock Actual</th>
                                        <th class="text-center">Stock Mín.</th>
                                        <th>Valor Stock</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($product->name); ?></strong>
                                                    <br><code class="bg-light px-2 py-1 rounded small"><?php echo e($product->code); ?></code>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($product->category): ?>
                                                    <span class="badge bg-secondary"><?php echo e($product->category->name); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin categoría</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    $stock = $product->stock ?? 0;
                                                    $minStock = $product->min_stock ?? 0;
                                                    $isLowStock = $stock <= $minStock && $minStock > 0;
                                                    $isOutOfStock = $stock <= 0;
                                                ?>
                                                
                                                <span class="badge <?php echo e($isOutOfStock ? 'bg-danger' : ($isLowStock ? 'bg-warning' : 'bg-success')); ?> fs-6">
                                                    <?php echo e($stock); ?> <?php echo e($product->unit ?? 'unidades'); ?>

                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="text-muted"><?php echo e($minStock); ?> <?php echo e($product->unit ?? 'unidades'); ?></span>
                                            </td>
                                            <td>
                                                <strong class="text-success">₲ <?php echo e(number_format(($product->stock ?? 0) * ($product->cost_price ?? 0))); ?></strong>
                                            </td>
                                            <td>
                                                <?php if($isOutOfStock): ?>
                                                    <span class="badge bg-danger">Sin stock</span>
                                                <?php elseif($isLowStock): ?>
                                                    <span class="badge bg-warning">Stock bajo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">OK</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-sm btn-outline-info" title="Ver producto">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('inventory.adjust')); ?>?product=<?php echo e($product->id); ?>" class="btn btn-sm btn-outline-primary" title="Ajustar stock">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('inventory.movements')); ?>?product_id=<?php echo e($product->id); ?>" class="btn btn-sm btn-outline-secondary" title="Ver movimientos">
                                                        <i class="bi bi-clock-history"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <?php if($products->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($products->withQueryString()->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-boxes" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay productos con control de stock</h5>
                            <p class="text-muted">Los productos deben tener activado el control de stock para aparecer aquí</p>
                            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-primary">
                                <i class="bi bi-box-seam me-1"></i>Ver Productos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert">
                <div class="toast-header bg-success text-white">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong class="me-auto">Éxito</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    <?php echo e(session('success')); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

    <script>
        function applyFilters() {
            const search = document.getElementById('searchInput').value;
            const category = document.getElementById('categoryFilter').value;
            const status = document.getElementById('statusFilter').value;
            
            const url = new URL(window.location.href);
            url.searchParams.set('search', search);
            url.searchParams.set('category', category);
            url.searchParams.set('status', status);
            
            window.location.href = url.toString();
        }
        
        function clearFilters() {
            window.location.href = '<?php echo e(route("inventory.index")); ?>';
        }
        
        // Enter key support for search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
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
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/inventory/index.blade.php ENDPATH**/ ?>