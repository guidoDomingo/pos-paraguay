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
                <i class="bi bi-receipt me-2"></i><?php echo e(__('Facturas Emitidas')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4">
        <div class="container-fluid">
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Filtro/búsqueda -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Buscar por número, cliente, RUC..." id="searchInvoices">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterCondition">
                        <option value="">Todas las condiciones</option>
                        <option value="CONTADO">Contado</option>
                        <option value="CREDITO">Crédito</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterElectronic">
                        <option value="">Todos los tipos</option>
                        <option value="electronic">Electrónica</option>
                        <option value="normal">Normal</option>
                    </select>
                </div>
            </div>

            <!-- Tabla -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        Lista de Facturas (<?php echo e($invoices->total()); ?> total)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($invoices->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>N° Factura</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Condición</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px; flex-shrink: 0;">
                                                        <i class="bi bi-receipt text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="text-primary"><?php echo e($invoice->invoice_number); ?></strong>
                                                        <?php if($invoice->is_electronic): ?>
                                                            <br><span class="badge bg-info" style="font-size: 0.7rem;">
                                                                <i class="bi bi-lightning-fill"></i> Electrónica
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong><?php echo e($invoice->customer_name ?? 'Sin nombre'); ?></strong>
                                                <?php if($invoice->customer_ruc): ?>
                                                    <br><small class="text-muted">RUC: <?php echo e($invoice->customer_ruc); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span><?php echo e(\Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y')); ?></span>
                                                <?php if($invoice->sale?->user): ?>
                                                    <br><small class="text-muted"><?php echo e($invoice->sale->user->name); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($invoice->condition === 'CONTADO'): ?>
                                                    <span class="badge bg-success">Contado</span>
                                                <?php elseif($invoice->condition === 'CREDITO'): ?>
                                                    <span class="badge bg-warning text-dark">Crédito</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e($invoice->condition); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <strong><?php echo e(number_format($invoice->total_amount, 0, ',', '.')); ?> Gs.</strong>
                                                <?php if($invoice->total_iva > 0): ?>
                                                    <br><small class="text-muted">IVA: <?php echo e(number_format($invoice->total_iva, 0, ',', '.')); ?> Gs.</small>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($invoice->is_electronic): ?>
                                                    <?php $status = $invoice->electronic_status; ?>
                                                    <?php if($status === 'approved'): ?>
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aprobada</span>
                                                    <?php elseif($status === 'rejected'): ?>
                                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rechazada</span>
                                                    <?php elseif($status === 'error'): ?>
                                                        <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Error</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary"><i class="bi bi-clock"></i> Pendiente</span>
                                                    <?php endif; ?>
                                                <?php elseif($invoice->is_printed): ?>
                                                    <span class="badge bg-success"><i class="bi bi-printer"></i> Impresa</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> Sin imprimir</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('invoices.show', $invoice)); ?>" class="btn btn-sm btn-outline-info" title="Ver detalle">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('invoices.print', $invoice)); ?>" class="btn btn-sm btn-outline-secondary" title="Imprimir" target="_blank">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if($invoices->hasPages()): ?>
                            <div class="card-footer">
                                <?php echo e($invoices->links()); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-receipt" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">No hay facturas registradas</h5>
                            <p class="text-muted">Las facturas aparecerán aquí una vez que se realicen ventas.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        document.getElementById('searchInvoices')?.addEventListener('input', function(e) {
            filterRows();
        });

        document.getElementById('filterCondition')?.addEventListener('change', function() {
            filterRows();
        });

        document.getElementById('filterElectronic')?.addEventListener('change', function() {
            filterRows();
        });

        function filterRows() {
            const search = document.getElementById('searchInvoices').value.toLowerCase();
            const condition = document.getElementById('filterCondition').value.toLowerCase();
            const electronic = document.getElementById('filterElectronic').value;
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchSearch = !search || text.includes(search);
                const matchCondition = !condition || text.includes(condition);
                const matchElectronic = !electronic
                    || (electronic === 'electronic' && row.querySelector('.badge.bg-info'))
                    || (electronic === 'normal' && !row.querySelector('.badge.bg-info'));

                row.style.display = matchSearch && matchCondition && matchElectronic ? '' : 'none';
            });
        }
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/invoices/index.blade.php ENDPATH**/ ?>