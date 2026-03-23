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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🧹 Gestión de Datos del Sistema
        </h2>
     <?php $__env->endSlot(); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="text-muted">
                                Limpiar y reiniciar datos transaccionales del sistema
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                        </div>
                        <div><?php echo e(session('success')); ?></div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <strong>Error de validación:</strong> <?php echo e($errors->first()); ?>

                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 9v4"></path>
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636-2.87L12.637 3.59a1.914 1.914 0 0 0-3.274 0z"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                        <div><?php echo e(session('error')); ?></div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <!-- Estado Actual del Sistema -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📊 Estado Actual - <?php echo e($company->name); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-primary text-white avatar">💰</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?php echo e(number_format($stats['sales_count'])); ?> Ventas
                                                    </div>
                                                    <div class="text-muted">
                                                        Total: $<?php echo e(number_format($stats['total_sales_amount'], 0)); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-success text-white avatar">📄</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?php echo e(number_format($stats['invoices_count'])); ?> Facturas
                                                    </div>
                                                    <div class="text-muted">Electrónicas</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-info text-white avatar">📦</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?php echo e(number_format($stats['products_count'])); ?> Productos
                                                    </div>
                                                    <div class="text-muted">
                                                        <?php echo e(number_format($stats['stock_movements_count'])); ?> movimientos
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <span class="bg-warning text-white avatar">💾</span>
                                                </div>
                                                <div class="col">
                                                    <div class="font-weight-medium">
                                                        <?php echo e($stats['database_size']); ?> MB
                                                    </div>
                                                    <div class="text-muted">
                                                        Última venta: <?php echo e($stats['last_sale'] ?? 'Ninguna'); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Opciones de Limpieza -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">🗑️ Opciones de Limpieza</h3>
                        </div>
                        
                        <form action="<?php echo e(route('admin.data-management.clean')); ?>" method="POST" id="cleanForm">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                
                                <!-- Tipo de Limpieza -->
                                <div class="mb-3">
                                    <label class="form-label">Tipo de limpieza</label>
                                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="transactions" class="form-selectgroup-input" checked>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Solo Transacciones</strong>
                                                    <div class="text-muted">Elimina ventas, facturas, movimientos de stock. Mantiene productos, clientes, configuraciones.</div>
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="all_except_products" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Transacciones + Historial de Clientes</strong>
                                                    <div class="text-muted">Incluye transacciones + resetea historial de compras de clientes.</div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="custom" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong>Limpieza Personalizada</strong>
                                                    <div class="text-muted">Permite configurar opciones específicas abajo.</div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="clean_type" value="total_cleanup" class="form-selectgroup-input">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3 border-danger">
                                                <div class="me-3">
                                                    <span class="form-selectgroup-check"></span>
                                                </div>
                                                <div>
                                                    <strong class="text-danger">🔥 LIMPIEZA TOTAL</strong>
                                                    <div class="text-muted">SOLO mantiene usuarios y roles. Elimina TODO lo demás.</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Opciones Personalizadas -->
                                <div id="customOptions" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="preserve_products" id="preserve_products" checked>
                                                <label class="form-check-label" for="preserve_products">
                                                    Preservar productos y stock actual
                                                </label>
                                                <small class="form-hint">Mantiene la lista de productos y su stock actual</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="preserve_customers" id="preserve_customers" checked>
                                                <label class="form-check-label" for="preserve_customers">
                                                    Preservar clientes
                                                </label>
                                                <small class="form-hint">Mantiene información de clientes pero resetea su historial</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3" id="stockResetOption" style="display: none;">
                                        <label class="form-label">Resetear stock a valor específico</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="reset_stock" min="0" max="999999" placeholder="Ej: 0, 100">
                                            <span class="input-group-text">unidades</span>
                                        </div>
                                        <small class="form-hint">Deja vacío para mantener stock actual</small>
                                    </div>
                                </div>

                                <!-- Confirmación -->
                                <div class="mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="confirmation" id="confirmation" required>
                                        <label class="form-check-label text-danger" for="confirmation">
                                            <strong>⚠️ Confirmo que entiendo que esta acción eliminará datos de forma permanente</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <button type="button" class="btn btn-danger" id="cleanButton" disabled
                                        data-bs-toggle="modal" data-bs-target="#confirmModal">
                                    <i class="bi bi-trash me-1"></i>
                                    <span id="cleanButtonText">Ejecutar Limpieza</span>
                                </button>
                            </div>
                        </form>

                        
                        <div class="modal fade" id="confirmModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content border-danger">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmar Limpieza</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="confirmModalText" class="fw-bold text-danger mb-2"></p>
                                        <p class="text-muted small mb-0">Esta acción <strong>no se puede deshacer</strong>. Los datos eliminados se perderán permanentemente.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger" id="confirmExecuteBtn">
                                            <i class="bi bi-fire me-1"></i>Sí, ejecutar limpieza
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">💡 Información Importante</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h4>✅ Datos que SE MANTIENEN (Limpieza Normal):</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li>👥 Usuarios y roles</li>
                                    <li>🏢 Información de la empresa</li>
                                    <li>🏪 Almacenes</li>
                                    <li>🏷️ Categorías</li>
                                    <li>📋 Productos (configurable)</li>
                                    <li>👤 Clientes (configurable)</li>
                                    <li>🏭 Proveedores</li>
                                    <li>📄 <strong>Timbres fiscales (CRÍTICO)</strong></li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h4 class="text-danger">🔥 LIMPIEZA TOTAL:</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li class="text-success">✅ SOLO usuarios y roles</li>
                                    <li class="text-danger">❌ TODO lo demás se elimina</li>
                                </ul>
                            </div>

                            <div class="mb-3">
                                <h4>❌ Datos que SE ELIMINAN:</h4>
                                <ul class="list list-timeline list-timeline-simple">
                                    <li>💰 Todas las ventas</li>
                                    <li>📄 Todas las facturas</li>
                                    <li>📦 Movimientos de inventario</li>
                                    <li>💵 Registros de pagos</li>
                                    <li>🏧 Sesiones de caja</li>
                                    <li>📊 Reportes históricos</li>
                                </ul>
                            </div>

                            <div class="alert alert-info">
                                <h4 class="alert-title">💡 Recomendación</h4>
                                <div class="text-muted">
                                    Después de la limpieza, ejecuta:<br>
                                    <code>php artisan db:seed</code><br>
                                    para restaurar datos de prueba.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cleanTypeInputs = document.querySelectorAll('input[name="clean_type"]');
    const customOptions   = document.getElementById('customOptions');
    const preserveProducts = document.getElementById('preserve_products');
    const stockResetOption = document.getElementById('stockResetOption');
    const confirmation    = document.getElementById('confirmation');
    const cleanButton     = document.getElementById('cleanButton');
    const cleanButtonText = document.getElementById('cleanButtonText');
    const confirmModalText = document.getElementById('confirmModalText');
    const confirmExecuteBtn = document.getElementById('confirmExecuteBtn');

    // Toggle custom options & button label
    cleanTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            customOptions.style.display = this.value === 'custom' ? 'block' : 'none';
            cleanButtonText.textContent = this.value === 'total_cleanup' ? '🔥 LIMPIEZA TOTAL' : 'Ejecutar Limpieza';
        });
    });

    // Toggle stock reset option
    preserveProducts.addEventListener('change', function() {
        stockResetOption.style.display = this.checked ? 'none' : 'block';
    });

    // Enable button when checkbox is checked
    confirmation.addEventListener('change', function() {
        cleanButton.disabled = !this.checked;
    });

    // Update modal text before showing
    document.getElementById('confirmModal').addEventListener('show.bs.modal', function() {
        const selectedType = document.querySelector('input[name="clean_type"]:checked').value;
        if (selectedType === 'total_cleanup') {
            confirmModalText.textContent = '🔥 Se eliminarán TODOS los datos excepto usuarios y roles (ventas, facturas, productos, clientes, timbrados, etc.)';
        } else {
            confirmModalText.textContent = '⚠️ Se eliminarán los datos seleccionados de forma permanente.';
        }
    });

    // Submit form when user confirms in modal
    confirmExecuteBtn.addEventListener('click', function() {
        document.getElementById('cleanForm').submit();
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
<?php endif; ?><?php /**PATH C:\laragon\www\bodega-app\resources\views/admin/data-management/index.blade.php ENDPATH**/ ?>