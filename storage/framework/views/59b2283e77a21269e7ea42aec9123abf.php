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
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-9">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <a href="<?php echo e(route('cash.current')); ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                        <h4 class="mb-0 fw-bold"><i class="bi bi-lock-fill me-2 text-danger"></i>Corte de Caja</h4>
                    </div>

                    <div class="row g-4">
                        <!-- Resumen -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                                <div class="card-header bg-white fw-bold py-3" style="border-radius:15px 15px 0 0;">
                                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Resumen de la sesión
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm mb-0">
                                        <tr>
                                            <td class="text-muted">Monto inicial</td>
                                            <td class="text-end fw-semibold">₲ <?php echo e(number_format($register->opening_amount, 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-cash me-1 text-success"></i>Ventas efectivo</td>
                                            <td class="text-end text-success fw-semibold">+ ₲ <?php echo e(number_format($byMethod['CASH'], 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-credit-card me-1 text-primary"></i>Tarjeta</td>
                                            <td class="text-end fw-semibold">₲ <?php echo e(number_format($byMethod['CARD'], 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-bank me-1 text-info"></i>Transferencia</td>
                                            <td class="text-end fw-semibold">₲ <?php echo e(number_format($byMethod['TRANSFER'], 0, ',', '.')); ?></td>
                                        </tr>
                                        <?php if($byMethod['CHEQUE'] > 0): ?>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-journal-check me-1"></i>Cheque</td>
                                            <td class="text-end fw-semibold">₲ <?php echo e(number_format($byMethod['CHEQUE'], 0, ',', '.')); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-plus-circle me-1 text-success"></i>Ingresos manuales</td>
                                            <td class="text-end text-success fw-semibold">+ ₲ <?php echo e(number_format($incomes, 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><i class="bi bi-dash-circle me-1 text-danger"></i>Egresos manuales</td>
                                            <td class="text-end text-danger fw-semibold">- ₲ <?php echo e(number_format($expenses, 0, ',', '.')); ?></td>
                                        </tr>
                                        <tr class="table-light fw-bold border-top">
                                            <td>💵 Esperado en caja</td>
                                            <td class="text-end text-success fs-5">₲ <?php echo e(number_format($expected, 0, ',', '.')); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de arqueo -->
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:15px;">
                                <div class="card-header bg-white fw-bold py-3" style="border-radius:15px 15px 0 0;">
                                    <i class="bi bi-calculator me-2 text-warning"></i>Arqueo de Caja
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo e(route('cash.close.store')); ?>" id="closeForm">
                                        <?php echo csrf_field(); ?>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Monto físico contado <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text">₲</span>
                                                <input type="number" name="closing_amount" id="closingAmount" class="form-control fw-bold"
                                                       min="0" step="1000" placeholder="0"
                                                       value="<?php echo e(old('closing_amount')); ?>" required autofocus
                                                       oninput="calcDiff()">
                                            </div>
                                        </div>

                                        <!-- Diferencia en tiempo real -->
                                        <div class="alert mb-4" id="diffAlert" style="display:none; border-radius:10px;">
                                            <div class="d-flex justify-content-between">
                                                <span>Diferencia:</span>
                                                <strong id="diffValue">₲ 0</strong>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Notas de cierre</label>
                                            <textarea name="closing_notes" class="form-control" rows="3"
                                                      placeholder="Observaciones del cierre..."><?php echo e(old('closing_notes')); ?></textarea>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-danger btn-lg fw-bold">
                                                <i class="bi bi-lock-fill me-2"></i>Confirmar Cierre
                                            </button>
                                            <a href="<?php echo e(route('cash.current')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        var expected = <?php echo e($expected); ?>;

        function calcDiff() {
            var closing = parseFloat(document.getElementById('closingAmount').value) || 0;
            var diff = closing - expected;
            var alert = document.getElementById('diffAlert');
            var value = document.getElementById('diffValue');

            if (closing === 0) { alert.style.display = 'none'; return; }

            alert.style.display = '';
            value.textContent = (diff >= 0 ? '+' : '') + '₲ ' + diff.toLocaleString('es-PY');

            if (Math.abs(diff) < 1) {
                alert.className = 'alert alert-success mb-4';
                value.textContent = '✅ Sin diferencia';
            } else if (diff > 0) {
                alert.className = 'alert alert-warning mb-4';
            } else {
                alert.className = 'alert alert-danger mb-4';
            }
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
<?php endif; ?>
<?php /**PATH C:\laragon\www\bodega-app\resources\views/cash/close.blade.php ENDPATH**/ ?>