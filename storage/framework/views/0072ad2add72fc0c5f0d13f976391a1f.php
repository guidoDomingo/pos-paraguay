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
                <div class="col-xl-5 col-lg-6">

                    <?php if(session('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo e(session('warning')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>
                    <?php if(session('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i><?php echo e(session('info')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php endif; ?>

                    <div class="card shadow-lg border-0" style="border-radius:20px; overflow:hidden;">
                        <!-- Header -->
                        <div class="card-header text-white text-center py-4" style="background:linear-gradient(135deg,#28a745,#20c997);">
                            <div style="font-size:3rem; margin-bottom:.5rem;">🏧</div>
                            <h3 class="mb-0 fw-bold">Apertura de Caja</h3>
                            <small class="opacity-75"><?php echo e(now()->format('d/m/Y H:i')); ?></small>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="<?php echo e(route('cash.store')); ?>">
                                <?php echo csrf_field(); ?>

                                <!-- Monto inicial -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-cash-stack me-1 text-success"></i>
                                        Monto Inicial en Caja <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text fw-bold">₲</span>
                                        <input type="number" name="opening_amount" class="form-control <?php $__errorArgs = ['opening_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('opening_amount', 0)); ?>" min="0" step="1000"
                                               placeholder="0" autofocus>
                                        <?php $__errorArgs = ['opening_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-text">Ingresa el efectivo físico con el que arranca la caja.</div>
                                </div>

                                <!-- Botones rápidos de monto -->
                                <div class="mb-4">
                                    <label class="form-label text-muted small">Montos comunes:</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php $__currentLoopData = [50000, 100000, 200000, 500000]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $amt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                onclick="document.querySelector('[name=opening_amount]').value = <?php echo e($amt); ?>">
                                            ₲ <?php echo e(number_format($amt, 0, ',', '.')); ?>

                                        </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <!-- Notas -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-chat-left-text me-1 text-muted"></i>
                                        Notas de apertura
                                    </label>
                                    <textarea name="opening_notes" class="form-control" rows="2"
                                              placeholder="Observaciones opcionales..."><?php echo e(old('opening_notes')); ?></textarea>
                                </div>

                                <!-- Info cajero -->
                                <div class="alert alert-light border mb-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle text-primary fs-5"></i>
                                        <div>
                                            <div class="fw-semibold"><?php echo e(Auth::user()->name); ?></div>
                                            <small class="text-muted">Cajero responsable</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg fw-bold py-3">
                                        <i class="bi bi-unlock-fill me-2"></i>Abrir Caja
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('cash.index')); ?>" class="text-muted small">
                            <i class="bi bi-clock-history me-1"></i>Ver historial de cajas
                        </a>
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
<?php /**PATH C:\laragon\www\bodega-app\resources\views/cash/open.blade.php ENDPATH**/ ?>