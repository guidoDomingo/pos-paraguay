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

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2 text-secondary"></i>Historial de Cajas</h4>
                <a href="<?php echo e(route('cash.current')); ?>" class="btn btn-success btn-sm fw-bold">
                    <i class="bi bi-cash-coin me-1"></i>Caja Actual
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    <?php if($registers->isEmpty()): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2"></i>
                        <p class="mt-2">No hay registros de caja aún.</p>
                        <a href="<?php echo e(route('cash.open')); ?>" class="btn btn-success">Abrir primera caja</a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Cajero</th>
                                    <th>Apertura</th>
                                    <th>Cierre</th>
                                    <th class="text-end">Monto Inicial</th>
                                    <th class="text-end">Total Ventas</th>
                                    <th class="text-end">Diferencia</th>
                                    <th class="text-center">Estado</th>
                                    <th class="pe-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $registers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-muted"><?php echo e($reg->id); ?></td>
                                    <td><?php echo e($reg->user->name); ?></td>
                                    <td class="small"><?php echo e($reg->opened_at->format('d/m/Y H:i')); ?></td>
                                    <td class="small text-muted"><?php echo e($reg->closed_at ? $reg->closed_at->format('d/m/Y H:i') : '—'); ?></td>
                                    <td class="text-end">₲ <?php echo e(number_format($reg->opening_amount, 0, ',', '.')); ?></td>
                                    <td class="text-end text-primary fw-semibold">₲ <?php echo e(number_format($reg->getTotalSales(), 0, ',', '.')); ?></td>
                                    <td class="text-end">
                                        <?php if($reg->status === 'CLOSED'): ?>
                                            <?php $diff = $reg->difference_amount; ?>
                                            <span class="fw-bold <?php echo e($diff == 0 ? 'text-success' : ($diff > 0 ? 'text-warning' : 'text-danger')); ?>">
                                                <?php echo e($diff >= 0 ? '+' : ''); ?>₲ <?php echo e(number_format($diff, 0, ',', '.')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge py-2 px-3 <?php echo e($reg->status === 'OPEN' ? 'bg-success' : 'bg-secondary'); ?>">
                                            <?php echo e($reg->status === 'OPEN' ? '🟢 Abierta' : '🔒 Cerrada'); ?>

                                        </span>
                                    </td>
                                    <td class="pe-4">
                                        <a href="<?php echo e(route('cash.show', $reg)); ?>" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4 py-3">
                        <?php echo e($registers->links()); ?>

                    </div>
                    <?php endif; ?>
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
<?php /**PATH C:\laragon\www\bodega-app\resources\views/cash/index.blade.php ENDPATH**/ ?>