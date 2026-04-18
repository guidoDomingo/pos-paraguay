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

            <!-- Header -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <a href="<?php echo e(route('cash.index')); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="mb-0 fw-bold">
                        <i class="bi bi-journal-check me-2 text-secondary"></i>
                        Detalle de Caja #<?php echo e($cashRegister->id); ?>

                    </h4>
                    <small class="text-muted">
                        <?php echo e($cashRegister->opened_at->format('d/m/Y H:i')); ?> —
                        <?php if($cashRegister->closed_at): ?>
                            Cerrada <?php echo e($cashRegister->closed_at->format('d/m/Y H:i')); ?>

                        <?php else: ?>
                            <span class="text-success fw-semibold">Abierta</span>
                        <?php endif; ?>
                    </small>
                </div>
                <span class="badge ms-auto <?php echo e($cashRegister->status === 'OPEN' ? 'bg-success' : 'bg-secondary'); ?> py-2 px-3">
                    <?php echo e($cashRegister->status === 'OPEN' ? '🟢 Abierta' : '🔒 Cerrada'); ?>

                </span>
            </div>

            <!-- Arqueo / Resumen financiero -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Resumen
                        </div>
                        <div class="card-body">
                            <table class="table table-sm mb-0">
                                <tr>
                                    <td class="text-muted">Cajero</td>
                                    <td class="fw-semibold"><?php echo e($cashRegister->user->name); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Monto inicial</td>
                                    <td class="fw-semibold">₲ <?php echo e(number_format($cashRegister->opening_amount, 0, ',', '.')); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Total ventas</td>
                                    <td class="fw-semibold text-primary">₲ <?php echo e(number_format($cashRegister->sales->sum('total_amount'), 0, ',', '.')); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Ingresos manuales</td>
                                    <td class="fw-semibold text-success">₲ <?php echo e(number_format($incomes, 0, ',', '.')); ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Egresos manuales</td>
                                    <td class="fw-semibold text-danger">₲ <?php echo e(number_format($expenses, 0, ',', '.')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-pie-chart me-2 text-info"></i>Ventas por método
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = ['CASH'=>['Efectivo','success'],'CARD'=>['Tarjeta','primary'],'TRANSFER'=>['Transferencia','info'],'CHEQUE'=>['Cheque','warning'],'CREDIT'=>['Crédito','secondary']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>[$label,$color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($byMethod[$key] > 0): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted"><?php echo e($label); ?></span>
                                <span class="fw-semibold text-<?php echo e($color); ?>">₲ <?php echo e(number_format($byMethod[$key], 0, ',', '.')); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <?php if($cashRegister->status === 'CLOSED'): ?>
                <div class="col-md-12 col-xl-4">
                    <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-calculator me-2 text-warning"></i>Resultado del Arqueo
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Esperado en caja</span>
                                <span class="fw-bold">₲ <?php echo e(number_format($cashRegister->expected_amount, 0, ',', '.')); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Contado físicamente</span>
                                <span class="fw-bold">₲ <?php echo e(number_format($cashRegister->closing_amount, 0, ',', '.')); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Diferencia</span>
                                <span class="fw-bold fs-4 <?php echo e($cashRegister->difference_amount == 0 ? 'text-success' : ($cashRegister->difference_amount > 0 ? 'text-warning' : 'text-danger')); ?>">
                                    <?php echo e($cashRegister->difference_amount >= 0 ? '+' : ''); ?>₲ <?php echo e(number_format($cashRegister->difference_amount, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <?php if($cashRegister->difference_amount == 0): ?>
                            <div class="alert alert-success mt-3 mb-0 text-center py-2">✅ Caja cuadrada</div>
                            <?php elseif($cashRegister->difference_amount > 0): ?>
                            <div class="alert alert-warning mt-3 mb-0 text-center py-2">⚠️ Sobrante</div>
                            <?php else: ?>
                            <div class="alert alert-danger mt-3 mb-0 text-center py-2">❌ Faltante</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Tablas de ventas y movimientos -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Ventas</h6>
                            <span class="badge bg-primary"><?php echo e($cashRegister->sales->count()); ?></span>
                        </div>
                        <div class="card-body p-0">
                            <?php if($cashRegister->sales->isEmpty()): ?>
                            <div class="text-center py-4 text-muted"><i class="bi bi-cart-x fs-3"></i><p class="mt-2 mb-0">Sin ventas</p></div>
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
                                        <?php $__currentLoopData = $cashRegister->sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="ps-3"><a href="<?php echo e(route('sales.show', $sale)); ?>" class="text-decoration-none fw-semibold"><?php echo e($sale->sale_number); ?></a></td>
                                            <td class="text-muted small"><?php echo e($sale->customer_name ?: 'Consumidor final'); ?></td>
                                            <td>
                                                <?php $pm = $sale->payment_method; ?>
                                                <span class="badge <?php echo e($pm==='CASH'?'bg-success':($pm==='CARD'?'bg-primary':($pm==='TRANSFER'?'bg-info':'bg-secondary'))); ?>">
                                                    <?php echo e(match($pm){ 'CASH'=>'Efectivo','CARD'=>'Tarjeta','TRANSFER'=>'Transfer.','CHEQUE'=>'Cheque',default=>$pm }); ?>

                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">₲ <?php echo e(number_format($sale->total_amount, 0, ',', '.')); ?></td>
                                            <td class="text-end pe-3 text-muted small"><?php echo e($sale->sale_date->format('H:i')); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Movimientos</h6>
                        </div>
                        <div class="card-body p-0">
                            <?php if($cashRegister->movements->isEmpty()): ?>
                            <div class="text-center py-4 text-muted small"><i class="bi bi-inbox fs-4"></i><p class="mt-1 mb-0">Sin movimientos</p></div>
                            <?php else: ?>
                            <?php $__currentLoopData = $cashRegister->movements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-start px-3 py-2 border-bottom">
                                <div>
                                    <span class="badge <?php echo e($mov->type==='INCOME'?'bg-success':($mov->type==='EXPENSE'?'bg-danger':'bg-warning text-dark')); ?> me-1"><?php echo e($mov->getTypeLabel()); ?></span>
                                    <div class="text-muted small mt-1"><?php echo e($mov->description); ?></div>
                                    <div class="text-muted" style="font-size:11px;"><?php echo e($mov->created_at->format('H:i')); ?></div>
                                </div>
                                <span class="fw-bold <?php echo e($mov->type==='INCOME'?'text-success':'text-danger'); ?>">
                                    <?php echo e($mov->type==='INCOME'?'+':'-'); ?> ₲ <?php echo e(number_format($mov->amount, 0, ',', '.')); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/cash/show.blade.php ENDPATH**/ ?>