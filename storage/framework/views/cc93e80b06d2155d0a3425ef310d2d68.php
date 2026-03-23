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
                <i class="bi bi-tags me-2"></i><?php echo e(__('Gestión de Categorías')); ?>

            </h2>
            <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nueva Categoría
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <!-- Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar categorías..." id="searchCategories">
                    </div>
                </div>
                <div class="col-md-6">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todas las categorías</option>
                        <option value="active">Activas</option>
                        <option value="inactive">Inactivas</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de categorías -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Categorías (<?php echo e($categories->total()); ?> total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($categories->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th class="text-center">Productos</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-tag text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?php echo e($category->name); ?></strong>
                                                        <br><small class="text-muted">
                                                            Creada: <?php echo e($category->created_at->format('d/m/Y')); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($category->description): ?>
                                                    <?php echo e(Str::limit($category->description, 100)); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Sin descripción</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($category->products_count > 0): ?>
                                                    <span class="badge bg-info fs-6"><?php echo e($category->products_count); ?></span>
                                                    <br><small class="text-muted">productos</small>
                                                <?php else: ?>
                                                    <span class="text-muted">0 productos</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($category->is_active ?? true): ?>
                                                    <span class="badge bg-success">Activa</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactiva</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('categories.show', $category)); ?>" class="btn btn-sm btn-outline-info" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('categories.edit', $category)); ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if($category->products_count == 0): ?>
                                                        <form method="POST" action="<?php echo e(route('categories.destroy', $category)); ?>" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar" 
                                                                onclick="return confirm('¿Eliminar esta categoría?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-outline-secondary" title="No se puede eliminar (tiene productos)" disabled>
                                                            <i class="bi bi-lock"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <?php if($categories->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($categories->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-tags" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay categorías registradas</h5>
                            <p class="text-muted">Comienza creando tu primera categoría para organizar los productos</p>
                            <a href="<?php echo e(route('categories.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Crear Primera Categoría
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <?php if($categories->count() > 0): ?>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Categorías</h6>
                                        <h2 class="mb-0"><?php echo e($categories->total()); ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-tags" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Con Productos</h6>
                                        <h2 class="mb-0"><?php echo e($categories->where('products_count', '>', 0)->count()); ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-box-seam" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Vacías</h6>
                                        <h2 class="mb-0"><?php echo e($categories->where('products_count', 0)->count()); ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Productos Total</h6>
                                        <h2 class="mb-0"><?php echo e($categories->sum('products_count')); ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-archive" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/categories/index.blade.php ENDPATH**/ ?>