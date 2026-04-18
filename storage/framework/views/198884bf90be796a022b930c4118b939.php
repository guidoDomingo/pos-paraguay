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

            <?php $__currentLoopData = ['success','error']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(session($type)): ?>
            <div class="alert alert-<?php echo e($type === 'error' ? 'danger' : $type); ?> alert-dismissible fade show mb-3">
                <?php echo e(session($type)); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-shield-lock-fill me-2 text-primary"></i>Roles</h4>
                    <p class="text-muted small mb-0">Define los permisos de cada rol</p>
                </div>
                <a href="<?php echo e(route('admin.roles.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Rol
                </a>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Nombre interno</th>
                                    <th>Display</th>
                                    <th>Descripción</th>
                                    <th class="text-center">Permisos</th>
                                    <th class="text-center">Usuarios</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4">
                                        <code class="bg-light px-2 py-1 rounded" style="font-size:12px;"><?php echo e($role->name); ?></code>
                                    </td>
                                    <td class="fw-semibold"><?php echo e($role->display_name); ?></td>
                                    <td class="text-muted small"><?php echo e($role->description ?: '—'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill"><?php echo e(count($role->permissions ?? [])); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php echo e($role->users_count > 0 ? 'bg-info' : 'bg-light text-dark'); ?> rounded-pill">
                                            <?php echo e($role->users_count); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php echo e($role->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                            <?php echo e($role->is_active ? 'Activo' : 'Inactivo'); ?>

                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo e(route('admin.roles.edit', $role)); ?>" class="btn btn-outline-primary btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if($role->name !== 'admin'): ?>
                                        <form method="POST" action="<?php echo e(route('admin.roles.destroy', $role)); ?>" class="d-inline"
                                            onsubmit="return confirm('¿Eliminar rol <?php echo e(addslashes($role->display_name)); ?>?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-shield-x fs-3 d-block mb-2"></i>
                                        No hay roles creados
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
<?php /**PATH C:\laragon\www\bodega-app\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>