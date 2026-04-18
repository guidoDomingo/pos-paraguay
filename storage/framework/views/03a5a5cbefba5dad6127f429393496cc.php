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
    <div class="py-4">
        <div class="container-fluid px-4">

            <?php $__currentLoopData = ['success','error','warning','info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($type)): ?>
            <div class="alert alert-<?php echo e($type === 'error' ? 'danger' : $type); ?> alert-dismissible fade show mb-3" role="alert">
                <?php echo e(session($type)); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-cash-coin me-2 text-success"></i>Caja Actual</h4>
                    <div class="text-muted small">
                        Abierta por <strong><?php echo e($register->user->name); ?></strong>
                        el <?php echo e($register->opened_at->format('d/m/Y')); ?> a las <?php echo e($register->opened_at->format('H:i')); ?>

                        &nbsp;·&nbsp; Monto inicial: <strong>₲ <?php echo e(number_format($register->opening_amount, 0, ',', '.')); ?></strong>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#movementModal" data-type="INCOME">
                        <i class="bi bi-plus-circle-fill me-1"></i>Ingreso
                    </button>
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#movementModal" data-type="EXPENSE">
                        <i class="bi bi-dash-circle-fill me-1"></i>Egreso
                    </button>
                    <a href="<?php echo e(route('cash.close')); ?>" class="btn btn-danger btn-sm fw-bold">
                        <i class="bi bi-lock-fill me-1"></i>Corte de Caja
                    </a>
                    <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-cash-stack me-1"></i>Ir al POS
                    </a>
                </div>
            </div>

            <!-- Tarjetas resumen -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-success fs-5 fw-bold">₲ <?php echo e(number_format($byMethod['CASH'], 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-cash me-1"></i>Efectivo</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-primary fs-5 fw-bold">₲ <?php echo e(number_format($byMethod['CARD'], 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-credit-card me-1"></i>Tarjeta</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-info fs-5 fw-bold">₲ <?php echo e(number_format($byMethod['TRANSFER'], 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-bank me-1"></i>Transferencia</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-warning fs-5 fw-bold">₲ <?php echo e(number_format($byMethod['CHEQUE'], 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-journal-check me-1"></i>Cheque</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-success fs-5 fw-bold">₲ <?php echo e(number_format($incomes, 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-plus-circle me-1"></i>Ingresos</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100" style="border-radius:12px;">
                        <div class="text-danger fs-5 fw-bold">₲ <?php echo e(number_format($expenses, 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-dash-circle me-1"></i>Egresos</div>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-xl">
                    <div class="card border-0 shadow-sm text-center py-3 h-100 border-success" style="border-radius:12px; border:2px solid #28a745 !important;">
                        <div class="text-success fs-5 fw-bold">₲ <?php echo e(number_format($expected, 0, ',', '.')); ?></div>
                        <div class="text-muted small mt-1"><i class="bi bi-wallet2 me-1"></i>Esperado en caja</div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Ventas de la sesión -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3" style="border-radius:15px 15px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Ventas de esta sesión</h6>
                            <span class="badge bg-primary"><?php echo e($register->sales->count()); ?></span>
                        </div>
                        <div class="card-body p-0">
                            <?php if($register->sales->isEmpty()): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-3"></i>
                                <p class="mt-2 mb-0">Aún no hay ventas en esta sesión</p>
                            </div>
                            <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">N°</th>
                                            <th>Cliente</th>
                                            <th>Método</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-end pe-3">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $register->sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-3">
                                                <a href="<?php echo e(route('sales.show', $sale)); ?>" class="text-decoration-none fw-semibold">
                                                    <?php echo e($sale->sale_number); ?>

                                                </a>
                                            </td>
                                            <td class="text-muted small"><?php echo e($sale->customer_name ?: 'Consumidor final'); ?></td>
                                            <td>
                                                <?php $pm = $sale->payment_method; ?>
                                                <span class="badge <?php echo e($pm==='CASH'?'bg-success':($pm==='CARD'?'bg-primary':($pm==='TRANSFER'?'bg-info':'bg-secondary'))); ?>">
                                                    <?php echo e(match($pm){ 'CASH'=>'Efectivo','CARD'=>'Tarjeta','TRANSFER'=>'Transfer.','CHEQUE'=>'Cheque',default=>$pm }); ?>

                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></td>
                                            <td class="text-end pe-3 text-muted small">
                                                <?php echo e($sale->sale_date->format('H:i')); ?>

                                                <?php if($sale->status === 'PENDING'): ?>
                                                <br><span class="badge bg-warning text-dark" style="font-size:10px;">Pendiente</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="ps-3 fw-bold">Total ventas</td>
                                            <td class="text-end fw-bold text-success">₲ <?php echo e(number_format($register->sales->sum('total_amount'), 0, ',', '.')); ?></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Movimientos manuales -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white py-3" style="border-radius:15px 15px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Movimientos manuales</h6>
                        </div>
                        <div class="card-body p-0">
                            <?php if($register->movements->isEmpty()): ?>
                            <div class="text-center py-4 text-muted small">
                                <i class="bi bi-inbox fs-4"></i>
                                <p class="mt-1 mb-0">Sin movimientos</p>
                            </div>
                            <?php else: ?>
                            <div style="max-height:340px; overflow-y:auto;">
                                <?php $__currentLoopData = $register->movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-start px-3 py-2 border-bottom">
                                    <div>
                                        <span class="badge <?php echo e($mov->type==='INCOME'?'bg-success':($mov->type==='EXPENSE'?'bg-danger':'bg-warning text-dark')); ?> me-1">
                                            <?php echo e($mov->getTypeLabel()); ?>

                                        </span>
                                        <div class="text-muted small mt-1"><?php echo e($mov->description); ?></div>
                                        <div class="text-muted" style="font-size:11px;"><?php echo e($mov->created_at->format('H:i')); ?></div>
                                    </div>
                                    <span class="fw-bold <?php echo e($mov->type==='INCOME'?'text-success':'text-danger'); ?>">
                                        <?php echo e($mov->type==='INCOME'?'+':'-'); ?> ₲ <?php echo e(number_format($mov->amount, 0, ',', '.')); ?>

                                    </span>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ingreso / Egreso -->
    <div class="modal fade" id="movementModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:15px;">
                <form method="POST" action="<?php echo e(route('cash.movement')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="movementModalTitle">Registrar Movimiento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="type" id="movementType" value="INCOME">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo</label>
                            <div class="d-flex gap-2">
                                <button type="button" id="btnIncome" class="btn btn-success flex-fill" onclick="setType('INCOME')">
                                    <i class="bi bi-plus-circle me-1"></i>Ingreso
                                </button>
                                <button type="button" id="btnExpense" class="btn btn-outline-danger flex-fill" onclick="setType('EXPENSE')">
                                    <i class="bi bi-dash-circle me-1"></i>Egreso
                                </button>
                                <button type="button" id="btnRefund" class="btn btn-outline-warning flex-fill" onclick="setType('REFUND')">
                                    <i class="bi bi-arrow-return-left me-1"></i>Devolución
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Monto <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₲</span>
                                <input type="number" name="amount" class="form-control" min="1" step="1" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                            <input type="text" name="description" class="form-control" placeholder="Motivo del movimiento..." required maxlength="500">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success fw-bold" id="movementSubmitBtn">
                            <i class="bi bi-check-circle me-1"></i>Registrar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setType(type) {
            document.getElementById('movementType').value = type;
            const labels = { INCOME: 'Ingreso', EXPENSE: 'Egreso', REFUND: 'Devolución' };
            document.getElementById('movementModalTitle').textContent = 'Registrar ' + labels[type];
            document.getElementById('btnIncome').className  = type === 'INCOME'  ? 'btn btn-success flex-fill'           : 'btn btn-outline-success flex-fill';
            document.getElementById('btnExpense').className = type === 'EXPENSE' ? 'btn btn-danger flex-fill'            : 'btn btn-outline-danger flex-fill';
            document.getElementById('btnRefund').className  = type === 'REFUND'  ? 'btn btn-warning flex-fill text-dark' : 'btn btn-outline-warning flex-fill';
            document.getElementById('movementSubmitBtn').className = type === 'EXPENSE' || type === 'REFUND'
                ? 'btn btn-danger fw-bold' : 'btn btn-success fw-bold';
        }

        document.getElementById('movementModal').addEventListener('show.bs.modal', function(e) {
            const type = e.relatedTarget ? e.relatedTarget.getAttribute('data-type') : 'INCOME';
            setType(type || 'INCOME');
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/cash/current.blade.php ENDPATH**/ ?>