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
        <div class="container-fluid px-4" style="max-width:800px;">

            <div class="mb-4">
                <a href="<?php echo e(route('admin.roles.index')); ?>" class="text-muted text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Volver a Roles
                </a>
                <h4 class="fw-bold mt-2 mb-0"><i class="bi bi-shield-lock me-2 text-primary"></i>Editar Rol: <?php echo e($role->display_name); ?></h4>
            </div>

            <?php $__currentLoopData = ['success','error']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($type)): ?>
            <div class="alert alert-<?php echo e($type === 'error' ? 'danger' : $type); ?> alert-dismissible fade show mb-3">
                <?php echo e(session($type)); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <form method="POST" action="<?php echo e(route('admin.roles.update', $role)); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Información del rol</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold">Nombre interno</label>
                                <input type="text" class="form-control bg-light" value="<?php echo e($role->name); ?>" disabled>
                                <div class="form-text">El nombre interno no se puede cambiar</div>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Nombre para mostrar <span class="text-danger">*</span></label>
                                <input type="text" name="display_name" class="form-control <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('display_name', $role->display_name)); ?>" required>
                                <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <input type="text" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('description', $role->description)); ?>" maxlength="500">
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Permisos</h6>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleAll(this)">
                                Seleccionar todos
                            </button>
                        </div>

                        <?php $currentPerms = old('permissions', $role->permissions ?? []); ?>

                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="fw-semibold small text-uppercase text-muted" style="letter-spacing:.5px;"><?php echo e($groupName); ?></span>
                                <div class="flex-fill border-top"></div>
                                <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted"
                                    onclick="toggleGroup(this)">
                                    Sel. grupo
                                </button>
                            </div>
                            <div class="row g-2 ps-2">
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-4 col-6">
                                    <div class="form-check">
                                        <input class="form-check-input perm-check" type="checkbox"
                                            name="permissions[]" value="<?php echo e($perm); ?>" id="perm_<?php echo e($perm); ?>"
                                            <?php echo e(in_array($perm, $currentPerms) ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="perm_<?php echo e($perm); ?>">
                                            <code style="font-size:11px;"><?php echo e($perm); ?></code>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('admin.roles.index')); ?>" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-check-circle me-1"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAll(btn) {
            const checks = document.querySelectorAll('.perm-check');
            const anyUnchecked = [...checks].some(c => !c.checked);
            checks.forEach(c => c.checked = anyUnchecked);
            btn.textContent = anyUnchecked ? 'Deseleccionar todos' : 'Seleccionar todos';
        }

        function toggleGroup(btn) {
            const container = btn.closest('.mb-4');
            if (!container) return;
            const checks = [...container.querySelectorAll('.perm-check')];
            const anyUnchecked = checks.some(c => !c.checked);
            checks.forEach(c => c.checked = anyUnchecked);
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
<?php /**PATH C:\laragon\www\bodega-app\resources\views/admin/roles/edit.blade.php ENDPATH**/ ?>