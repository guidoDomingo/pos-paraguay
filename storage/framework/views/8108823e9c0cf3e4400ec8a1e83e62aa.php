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
                <i class="bi bi-box-seam me-2"></i><?php echo e(__('Gestión de Productos')); ?>

            </h2>
            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Nuevo Producto
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
                        <input type="text" class="form-control" placeholder="Buscar productos..." id="searchProducts">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterCategory">
                        <option value="">Todas las categorías</option>
                        <!-- Aquí se cargarían las categorías -->
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de productos -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Productos (<?php echo e($products->total()); ?> total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($products->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="80">Imagen</th>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Precio Venta</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if($product->hasImage()): ?>
                                                    <img src="<?php echo e($product->getImageUrl('thumbnail')); ?>" 
                                                         alt="<?php echo e($product->name); ?>" 
                                                         class="product-thumbnail cursor-pointer" 
                                                         data-full-image="<?php echo e($product->getImageUrl('medium')); ?>"
                                                         data-product-name="<?php echo e($product->name); ?>"
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid #e3e6f0; transition: all 0.3s ease;"
                                                         title="Click para ampliar">
                                                <?php else: ?>
                                                    <div class="product-thumbnail-placeholder d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px;">
                                                        <i class="bi bi-image text-muted" style="font-size: 20px;"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <code class="bg-light px-2 py-1 rounded"><?php echo e($product->code); ?></code>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($product->name); ?></strong>
                                                    <?php if($product->description): ?>
                                                        <br><small class="text-muted"><?php echo e(Str::limit($product->description, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($product->category): ?>
                                                    <span class="badge bg-secondary"><?php echo e($product->category->name); ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin categoría</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong class="text-success">₲ <?php echo e(number_format($product->sale_price)); ?></strong>
                                                <?php if($product->cost_price > 0): ?>
                                                    <br><small class="text-muted">Costo: ₲ <?php echo e(number_format($product->cost_price)); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($product->track_stock): ?>
                                                    <?php if($product->stock <= $product->min_stock): ?>
                                                        <span class="badge bg-danger"><?php echo e($product->stock ?? 0); ?></span>
                                                        <br><small class="text-danger">Stock bajo</small>
                                                    <?php else: ?>
                                                        <span class="badge bg-success"><?php echo e($product->stock ?? 0); ?></span>
                                                    <?php endif; ?>
                                                    <br><small class="text-muted">Mín: <?php echo e($product->min_stock); ?></small>
                                                <?php else: ?>
                                                    <span class="text-muted">No controlado</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($product->is_active): ?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactivo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-sm btn-outline-info" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if($product->is_active): ?>
                                                        <form method="POST" action="<?php echo e(route('products.destroy', $product)); ?>" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Desactivar" 
                                                                onclick="return confirm('¿Desactivar este producto?')">
                                                                <i class="bi bi-pause-circle"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <?php if($products->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($products->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-box-seam" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay productos registrados</h5>
                            <p class="text-muted">Comienza agregando tu primer producto al inventario</p>
                            <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Crear Primer Producto
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

    <!-- Modal para ampliar imagen -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">
                        <i class="bi bi-image me-2"></i>
                        <span id="imageModalProductName">Imagen del Producto</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="modalProductImage" src="" alt="" class="img-fluid rounded shadow" style="max-height: 500px;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .product-thumbnail {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .product-thumbnail:hover {
            transform: scale(1.1);
            border-color: var(--bs-primary) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10;
            position: relative;
        }
        
        .product-thumbnail-placeholder:hover {
            border-color: var(--bs-primary);
            background: #f0f0f0;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        /* Animación del modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
        }
        
        .modal.show .modal-dialog {
            transform: none;
        }
        
        /* Responsive para móvil */
        @media (max-width: 768px) {
            .product-thumbnail {
                width: 40px !important;
                height: 40px !important;
            }
            
            .product-thumbnail-placeholder {
                width: 40px !important;
                height: 40px !important;
            }
            
            .product-thumbnail-placeholder i {
                font-size: 16px !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar clicks en las miniaturas de productos
            const productThumbnails = document.querySelectorAll('.product-thumbnail');
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            const modalImage = document.getElementById('modalProductImage');
            const modalTitle = document.getElementById('imageModalProductName');
            
            productThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    const fullImageUrl = this.getAttribute('data-full-image');
                    const productName = this.getAttribute('data-product-name');
                    
                    if (fullImageUrl) {
                        // Configurar el modal
                        modalImage.src = fullImageUrl;
                        modalImage.alt = productName;
                        modalTitle.textContent = productName;
                        
                        // Mostrar el modal
                        imageModal.show();
                    }
                });
                
                // Agregar efecto de tooltip
                thumbnail.setAttribute('title', 'Click para ampliar imagen');
            });
            
            // Precargar imágenes al pasar el mouse (opcional, mejora UX)
            productThumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('mouseenter', function() {
                    const fullImageUrl = this.getAttribute('data-full-image');
                    if (fullImageUrl) {
                        const preloadImage = new Image();
                        preloadImage.src = fullImageUrl;
                    }
                });
            });
            
            // Cerrar modal con tecla Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    imageModal.hide();
                }
            });
        });
        
        // Funcionalidad de búsqueda mejorada (opcional)
        const searchInput = document.getElementById('searchProducts');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const tableRows = document.querySelectorAll('tbody tr');
                
                tableRows.forEach(row => {
                    const productName = row.children[2].textContent.toLowerCase();
                    const productCode = row.children[1].textContent.toLowerCase();
                    
                    if (productName.includes(searchTerm) || productCode.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
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
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/products/index.blade.php ENDPATH**/ ?>