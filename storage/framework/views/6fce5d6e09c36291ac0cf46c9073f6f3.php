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
            <div class="alert alert-<?php echo e($type === 'error' ? 'danger' : $type); ?> alert-dismissible fade show mb-3">
                <?php echo e(session($type)); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold"><i class="bi bi-people-fill me-2 text-primary"></i>Usuarios</h4>
                    <p class="text-muted small mb-0">Gestiona los usuarios de tu empresa</p>
                </div>
                <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-person-plus-fill me-1"></i>Nuevo Usuario
                </a>
            </div>

            <!-- Filtros -->
            <form method="GET" class="row g-2 mb-4">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, email o código..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role_id" class="form-select form-select-sm">
                        <option value="">Todos los roles</option>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>" <?php echo e(request('role_id') == $role->id ? 'selected' : ''); ?>><?php echo e($role->display_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos los estados</option>
                        <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Activos</option>
                        <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-secondary btn-sm flex-fill">Filtrar</button>
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg"></i>
                    </a>
                </div>
            </form>

            <!-- Tabla -->
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Código</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-end pe-4">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                                                style="width:36px;height:36px;background:<?php echo e(['#6366f1','#8b5cf6','#06b6d4','#10b981','#f59e0b','#ef4444'][crc32($user->name)%6]); ?>;font-size:14px;">
                                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:14px;"><?php echo e($user->name); ?></div>
                                                <?php if($user->phone): ?>
                                                <div class="text-muted" style="font-size:11px;"><?php echo e($user->phone); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small"><?php echo e($user->email); ?></td>
                                    <td>
                                        <?php if($user->role): ?>
                                        <span class="badge rounded-pill" style="background:#6366f1;font-size:11px;"><?php echo e($user->role->display_name); ?></span>
                                        <?php else: ?>
                                        <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small"><?php echo e($user->employee_code ?: '—'); ?></td>
                                    <td class="text-center">
                                        <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.toggle', $user)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-link p-0 border-0" title="<?php echo e($user->is_active ? 'Desactivar' : 'Activar'); ?>">
                                                <span class="badge <?php echo e($user->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                                    <?php echo e($user->is_active ? 'Activo' : 'Inactivo'); ?>

                                                </span>
                                            </button>
                                        </form>
                                        <?php else: ?>
                                        <span class="badge bg-success">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-outline-secondary btn-sm" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-outline-primary btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if($user->id !== auth()->id()): ?>
                                        <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>" class="d-inline"
                                            onsubmit="return confirm('¿Eliminar usuario <?php echo e(addslashes($user->name)); ?>?')">
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
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-3 d-block mb-2"></i>
                                        No se encontraron usuarios
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if($users->hasPages()): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <?php echo e($users->links()); ?>

                </div>
                <?php endif; ?>
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
<?php /**PATH C:\laragon\www\bodega-app\resources\views/admin/users/index.blade.php ENDPATH**/ ?>